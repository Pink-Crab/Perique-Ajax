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



