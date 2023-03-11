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

