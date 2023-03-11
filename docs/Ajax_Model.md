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

**protected string⎮null $nonce_handle;**
> @type string⎮null 

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

```php
class Some_Ajax extends Ajax {
    
    /**
     * @var string
     **/
    protected string $action = 'some_ajax_action';

    /**
     * @var string
     */
    protected string $nonce_handle = 'some_ajax_nonce';

    /**
     * @var string
     */
    protected string $nonce_field = 'some_ajax_nonce';

    /**
     * @var bool
     */
    protected bool $logged_in;

    /**
     * @var bool
     */
    protected bool $logged_out;

    /**
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \PinkCrab\Ajax\Dispatcher\Response_Factory $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function callback(
		ServerRequestInterface $request,
		Response_Factory $response_factory
	): ResponseInterface {
        // Return something 
        return $response_factory->success( 'something' );
    }
}
```