
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
> @return Nonce⎮null  
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
