# Perique - Ajax

A simple but powerful Ajax library for the PinkCrab Perique framework. Allows for the creation of object based Ajax calls that handle all basic Nonce validation, WP Actions and makes use of the HTTP PSR Interfaces.

![alt text](https://img.shields.io/badge/Current_Version-0.1.0-yellow.svg?style=flat " ") 
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)]()
![](https://github.com/Pink-Crab/Perique-Ajax/workflows/GitHub_CI/badge.svg " ")
[![codecov](https://codecov.io/gh/Pink-Crab/Perique-Ajax/branch/master/graph/badge.svg?token=NEZOz6FsKK)](https://codecov.io/gh/Pink-Crab/Perique-Ajax)

## Version 0.1.0-beta ##

****

## Why? ##
Writing Ajax scripts for WordPress can get messy really quickly, with the need to define upto 2 actions with a shared callback. The Perique Ajax Module makes use of the registration and dependecny injection aspects of the framework. This allows for the injection of services into your callback, allowing for clean and testable code.

****

## Setup ##

>*Requires the PinkCrab Perique Framework and Composer*

**Install the Module using composer**
```bash 
$ composer require pinkcrab/perique-ajax
```
**Include the custom Ajax Middleware**
```php
// file:plugin.php

// Boot the app as normal.
$app = ( new App_Factory )      
    ->with_wp_dice( true )
    ->boot();

// Include the custom middleware.
$app->registration_middleware(
    // Constuct using the DI Container, to handle all DI.
    $app::make( 'PinkCrab\Ajax\Registration_Middleware\Ajax_Middleware' )
);
```
**Set DI Rules for ServerRequest**
```php
// file:plugin.php

use PinkCrab\HTTP\HTTP_Helper;
use Psr\Http\Message\ServerRequestInterface;

return [
    '*' => [
        'subsitutions' => [
            // Always pass the current global server request as ServerRequestInterface.
            ServerRequestInterface::class => HTTP_Helper::global_server_request()
        ]
    ]
];
```
**Add all your Ajax Classes to `registration.php`**
```php
// file:registration.php

return [
    ....
    My_Ajax_Call::class,
    ....
];
```

****

## Ajax Models ##

Each ajax call is defined as a model, this is used to define the properties (action, nonce, priv/non_priv) and the callback used. As each Model is constructed using the DI Container, all dependenices can be injected. 

To create a new Model, just extend the Abstract Ajax class.

```php
class My_Ajax extends \PinkCrab\Ajax\Ajax {...}
```

### Properties ###

****

**protected string $action;**
> @type string  
> **@required**

The action used when registering the ajax call and should be passed as part of the payload.

****

**protected string|null $nonce_handle;**
> @type string|null 

The handle used for the nonce which is created, if not set will not do a nonce check with the ajax call. This is useful if you want to use an alteranive request verification.

****

**protected string $nonce_field;**
> @type string  
> *@default '**nonce**'*

The property in the request which is used to verify the nonce. If not defined will default to **nonce**.

****

**protected bool $logged_in;**
> @type bool  
> *@default **true***

If true will set the piv_ ajax hook. Allowing logged in user to use this call.

****

**protected bool $logged_out;**
> @type bool  
> *@default **true***

If true will set the non_piv_ ajax hook. Allowing logged out user to use this call.

****

### Methods ###

**public function callback( ServerRequestInterface $request, Response_Factory $response_factory )**
> @param \Psr\Http\Message\ServerRequestInterface $request  
> @param \PinkCrab\Ajax\Dispatcher\Response_Factory $response_factory  
> @return \Psr\Http\Message\ResponseInterface  

The callback method, called if the request has been validated. Passed in the current request and has access to all depenencies injected into model. Callback must return a valid HTTP Response (See Response_Factory for more details)

### Example ###

> Create the Ajax callback object.
```php
class My_Ajax extends \PinkCrab\Ajax\Ajax{

    // Define the action and nonce handle.
    protected $action = 'my_ajax_action';
    protected $nonce_handle = 'my_ajax_nonce';

    // Inject services to handle all logic & functionality
    protected $some_service;

    public function __construct(Some_Service $some_service){
        $this->some_service = $some_service;
    }

    // Return back a 200 response with some payload in the body.
    public function callback( ServerRequestInterface $request, Response_Factory $response_factory ): ResponseInterface{
        return $response_factory->success([
            'some_data' => $this->some_service->some_method()
        ]);
    }
}
```
> Enqueue your script with required data passed as Localized data.

```php
// Localised values passed to enqueue of JS file.
public function some_controller_method(){
    Enqueue::script('My_Ajax')
        ->src('https://url.tld/wp-content/plugins/my_plugn/assets/js/my-script.js')
        ->deps('jquery')
        ->lastest_version()
        ->localize([
            'nonce'   => Ajax_Helper::get_nonce(My_Ajax::class)->token(),
            'action'  => Ajax_Helper::get_action(My_Ajax::class),
            'ajaxUrl' => Ajax_Helper::admin_ajax_url(),
        ])->register();
}

```
```js
// The Ajax Call.
jQuery(".some_click").click( function(e) {
    e.preventDefault(); 
    jQuery.ajax({
        type : "POST",
        dataType : "json",
        url : My_Ajax.ajaxUrl,
        data : {
            action: My_Ajax.action, 
            nonce: My_Ajax.nonce
        },
        success: function(response) {
            // Do domething with response.some_data
        }
    }); 
});
```

## Helper Methods ##

There is a small class of static methods which can be used throughout your code to make it a little easier working with Ajax calls. Primarily these are around accessing values from an Ajax Model without the need to construct the object. This allows us to access the actions, nonce handle etc, without worrying about dependencies. `Please note if you use the constructor to set any of your properties, you will need to use App::make(Your_Ajax_Model::class) in place of these methods`

### Methods ###

**public static function admin_ajax_url()**
> @return string

Returns the sites current admin-ajax url. Just a wrapper for `admin_url('admin-ajax.php')` really.

****

**public static function get_action( string $class )**
> @param string $class  
> @return string  
> @throws Ajax_Exception (code 100) If non valid Ajax class passed.
> @throws Ajax_Exception (code 101) If no action defined

Gets the defined action from a passed Ajax Model. Will throw an exception if the class is not extending the Abstract Ajax Model or has no Action defined.

****

**public static function has_nonce( string $class )**
> @param string $class  
> @return boolean  
> @throws Ajax_Exception (code 100) If non valid Ajax class passed.

Checks if passed Ajax Model uses a nonce (has nonce_handle defined). Will throw an exception if the class is not extending the Abstract Ajax Model.

****

**public static function get_nonce( string $class )**
> @param string $class   
> @return Nonce|null  
> @throws Ajax_Exception (code 100) If non valid Ajax class passed.

Returns a populated [Nonce object](https://github.com/Pink-Crab/Nonce) if the class has a nonce_handle defined. Returns null if class doesnt have a nonce_handle defined. Will throw an exception if the class is not extending the Abstract Ajax Model.

****

**public static function get_nonce_field( string $class )**
> @param string $class  
> @return string  
> @throws Ajax_Exception (code 100) If non valid Ajax class passed.

Returns the property expected to find the Nonce token held in the ServerRequest. If not set in the Ajax Model will use the default of 'nonce'. Will throw an exception if the class is not extending the Abstract Ajax Model.

****

**public static function extract_server_request_args( ServerRequestInterface $request )**
> @param ServerRequestInterface $request  
> @return array<string, mixed>

When the current ServerRequest is passed in, it will return the Params or Body of the request based on the HTTP Method. Works for $_GET and $_POST (including urlEncoded values.). Returned back in a key=>value array, returns a blank array if unknown method or request is empty. Should not be used if you planning to use a mix of QueryParams and BodyArguments.

## Response Factory ##

We make use of the PSR HTTP Messaging interface throughout this module, as a result all responses should be returned as valid PSR7 Response Objects. To make this a little easier and cleaner, we have injected a `Response_Factory` into the callback method. This allows you to quickly return a response and have all payloads encoded as expected.

### Methods ###

**public function createResponse( int $code = 200, string $reasonPhrase = '' )**
> @param int $code The HTTP status code. Defaults to 200.  
> @param string $reasonPhrase  
> @return Psr\Http\Message\ResponseInterface

Used to create a general response, must be passed a string (JSON) representation of the response body.

****

**public function success( array $payload = [] )**
> @param array<mixed> $payload  
> @return Psr\Http\Message\ResponseInterface

Used to return a 200 response with the passed payload (array) represented as JSON in the response body.

****

**public function unauthorised( array $payload = ['error' => 'unauthorised'] )**
> @param array<mixed> $payload   
> @return Psr\Http\Message\ResponseInterface  

Used to return a 401 response with the passed payload (array) represented as JSON in the response body. Defaults to ['error' => 'unauthorised'] if no body passed.

****

**public function failure( array $payload = ['error' => 'error'] )**
> @param array<mixed> $payload  
> @return Psr\Http\Message\ResponseInterface  

Used to return a 500 response with the passed payload (array) represented as JSON in the response body. Defaults to ['error' => 'error'] if no body passed.

****

**public function not_found( array $payload = ['error' => 'not found'] )**
> @param array<mixed> $payload  
> @return Psr\Http\Message\ResponseInterface  

Used to return a 404 response with the passed payload (array) represented as JSON in the response body. Defaults to ['error' => 'not found'] if no body passed.


## Hooks ##

There are a few actions and filters which are used by the callback, these allow the extension of all Validation checks, manipulating the request/response objects and also for catching Exceptions thrown in callbacks.

> All the hooks comes with a Enum style class of constants.

**Ajax_Hooks::REQUEST_NONCE_VERIFICATION**
> @param  bool $validated  
> @param  Ajax $ajax  
> @param  ServerRequestInterface $request  
> @return bool  
> @hook   filter  

Once the ajax class has been passed through the Ajax_Validator, it is then run through this filter. This allows for adding in extra validation checks, or overruling the validator altogether. 
The full filter handle is `pinkcrab/ajax/request_nonce_verification`

```php
add_filter(
    Ajax_Hooks::REQUEST_NONCE_VERIFICATION, 
    function(bool $valid, Ajax $ajax, ServerRequestInterface $request ): bool {
        // Always allow Some_Ajax to pass even if fails nonce, all other classes as normal.
        return get_class($ajax) === Some_Ajax::class 
            ? true : $valid; 
    }, 
    10, 
    3
);
```

**Ajax_Hooks::CALLBACK_REQUEST_FILTER**
> @param  ServerRequestInterface $request  
> @param  Ajax $ajax  
> @return ServerRequestInterface  
> @hook   filter  

The request that is passed into Ajax::callback(), runs through this filter first.  
The full filter handle is `pinkcrab/ajax/callback_request_filter`

```php
add_filter(
    Ajax_Hooks::CALLBACK_REQUEST_FILTER, 
    function(ServerRequestInterface $request , Ajax $ajax ): ServerRequestInterface {
        return $request->withQueryParams(['some_key' => 'some_value']);
    }, 
    10, 
    2
);
```

**Ajax_Hooks::CALLBACK_RESPONSE_FILTER**
> @param  ResponseInterface $response  
> @param  Ajax $ajax  
> @param  ServerRequestInterface $request  
> @return ResponseInterface  
> @hook   filter  

Before a response is emitted, the Response object is run through this filter.
The full filter handle is `pinkcrab/ajax/callback_response_filter`

```php
add_filter(
    Ajax_Hooks::CALLBACK_RESPONSE_FILTER, 
    function(ResponseInterface $response, Ajax $ajax, ServerRequestInterface $request ): ResponseInterface {
        // Teapot Ajax should return as 418 always, even if callback is 200.
        return get_class($ajax) === Teapot_Ajax::class 
            ? $response->withStatusCode(418) 
            : $response; 
    }, 
    10, 
    3
);
```

**Ajax_Hooks::CALLBACK_EXECUTION_EXCEPTION**
> @param  Ajax_Exception $exception   
> @param  Ajax $ajax   
> @return void    
> @hook   action  

If an exception is thrown or it bubbled up during the callback() call, this hook will be fired. This allows for hooking in Logging.
The full action handle is `pinkcrab/ajax/callback_execution_exception`

```php
add_action(
    Ajax_Hooks::CALLBACK_EXECUTION_EXCEPTION, 
    function(Ajax_Exception $exception, Ajax $ajax ): ResponseInterface {
        $this->logger->error(sprintf(
            "Error thrown when calling %s, returned error messge %s"), 
            get_class($ajax), 
            $exception->getMessage()
        ));
    }, 
    10, 
    2
);
```

## License ##

### MIT License ###
http://www.opensource.org/licenses/mit-license.html  

## Change Log ##
* 0.1.0 Extracted from the Registerables module. Now makes use of a custom Registration_Middleware service for dispatching all Ajax calls.