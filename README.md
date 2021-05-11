# Perique - Ajax

A simple but powerful Ajax library for the PinkCrab Perique framework. Allows for the creation of object based Ajax calls that handle all basic Nonce validation, WP Actions and makes use of the HTTP PSR Interfaces.

![alt text](https://img.shields.io/badge/Current_Version-0.1.0-yellow.svg?style=flat " ") 
[![Open Source Love](https://badges.frapsoft.com/os/mit/mit.svg?v=102)]()
![](https://github.com/Pink-Crab/Framework__core/workflows/GitHub_CI/badge.svg " ")
[![codecov](https://codecov.io/gh/Pink-Crab/Plugin-Framework/branch/master/graph/badge.svg?token=VW566UL1J6)](https://codecov.io/gh/Pink-Crab/Plugin-Framework)

## Version 0.1.0 ##

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

**public function callback( ServerRequestInterface $request, ResponseFactoryInterface $response_factory )**
> @param \Psr\Http\Message\ServerRequestInterface $request  
> @param \Psr\Http\Message\ResponseFactoryInterface $response_factory  
> @return \Psr\Http\Message\ResponseInterface  

The callback method, called if the request has been validated. Passed in the current request and has access to all depenencies injected into model. Callback must return a valid HTTP Response (See Response_Factory for more details)

### Example ###

```php
// Ajax Model
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
    public function callback( ServerRequestInterface $request, ResponseFactoryInterface $response_factory ): ResponseInterface{
        return $response_factory->success([
            'some_data' => $this->some_service->some_method()
        ]);
    }
}

// Localised values passed to enqueue
[
    'nonce'   => Ajax_Helper::get_nonce(My_Ajax::class)->token,
    'action'  => Ajax_Helper::get_action(My_Ajax::class),
    'ajaxUrl' => admin_url( 'admin-ajax.php' )
]

```
```js
// The Ajax Call.
jQuery(".some_action").click( function(e) {
    e.preventDefault(); 
    jQuery.ajax({
        type : "POST",
        dataType : "json",
        url : localised.ajaxUrl,
        data : {
            action: localised.action, 
            nonce: localised.nonce
        },
        success: function(response) {
            // Do domething
        }
    }); 
});
```
