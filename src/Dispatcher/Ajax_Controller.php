<?php

declare(strict_types=1);

/**
 * The controller which is used for handling responses/ajax callbacks.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Dispatcher;

use Closure;
use Exception;
use PinkCrab\Ajax\Ajax;
use PinkCrab\HTTP\HTTP;
use PinkCrab\Ajax\Ajax_Hooks;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Ajax_Controller {

	/** @var ServerRequestInterface */
	protected $server_request;

	/** @var Response_Factory */
	protected $response_factory;

	/** @var HTTP */
	protected $http_helper;

	/** @var Ajax_Request_Validator */
	protected $request_validator;

	public function __construct(
		ServerRequestInterface $server_request,
		Response_Factory $response_factory,
		HTTP $http_helper,
		Ajax_Request_Validator $request_validator
	) {
		$this->response_factory  = $response_factory;
		$this->server_request    = $server_request;
		$this->http_helper       = $http_helper;
		$this->request_validator = $request_validator;
	}

	/**
	 * Validates an ajax call based on the server requests contents.
	 *
	 * @param \PinkCrab\Ajax\Ajax $ajax_class
	 * @return bool
	 */
	public function validate_request( Ajax $ajax_class ): bool {
		return $this->request_validator->validate( $ajax_class );
	}

	/**
	 * Used to invoke the callback supplied in an Ajax instance.
	 *
	 * @param \PinkCrab\Ajax\Ajax $ajax_class
	 * @return \Psr\Http\Message\ResponseInterface
	 * @filter Ajax_Hooks::CALLBACK_REQUEST_FILTER
	 */
	public function invoke_callback( Ajax $ajax_class ): ResponseInterface {
		return $ajax_class->callback(
			\apply_filters( Ajax_Hooks::CALLBACK_REQUEST_FILTER, $this->server_request, $ajax_class ), /* @phpstan-ignore-line */
			$this->response_factory
		);
	}

	/**
	 * Returns the Closure for a ajax request.
	 *
	 * @param \PinkCrab\Ajax\Ajax $ajax_class
	 * @return \Closure():noreturn
	 * @filter Ajax_Hooks::REQUEST_NONCE_VERIFICATION
	 * @action Ajax_Hooks::CALLBACK_EXECUTION_EXCEPTION
	 * @filter Ajax_Hooks::CALLBACK_RESPONSE_FILTER
	 */
	public function create_callback( Ajax $ajax_class ): Closure {
		/**
		 * @param \PinkCrab\Ajax\Ajax $ajax_class
		 * @return noreturn
		 */
		return function() use ( $ajax_class ): void {

			/** @phpstan-ignore-next-line */
			$valid_nonce = apply_filters(
				Ajax_Hooks::REQUEST_NONCE_VERIFICATION,
				$this->validate_request( $ajax_class ),
				$ajax_class,
				$this->server_request
			);

			try {
				$response = $valid_nonce
				? $this->invoke_callback( $ajax_class )
				: $this->response_factory->unauthorised();
			} catch ( Exception $th ) {

				do_action( Ajax_Hooks::CALLBACK_EXECUTION_EXCEPTION, $th, $ajax_class );

				$response = $this->response_factory->failure( array( 'error' => $th->getMessage() ) );
			}
			$this->http_helper->emit_psr7_response(
				/** @phpstan-ignore-next-line */
				\apply_filters( Ajax_Hooks::CALLBACK_RESPONSE_FILTER, $response, $ajax_class, $this->server_request )
			);

			\wp_die();
		};
	}
}
