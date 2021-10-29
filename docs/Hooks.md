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
            "Error thrown when calling %s, returned error message %s"), 
            get_class($ajax), 
            $exception->getMessage()
        ));
    }, 
    10, 
    2
);
```
