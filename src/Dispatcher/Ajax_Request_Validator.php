<?php

declare(strict_types=1);

/**
 * Primary service for dispatching ajax calls.
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

use PinkCrab\Ajax\Ajax;
use PinkCrab\Nonce\Nonce;
use PinkCrab\Ajax\Ajax_Helper;
use Psr\Http\Message\ServerRequestInterface;

class Ajax_Request_Validator {

	protected ServerRequestInterface $server_request;

	public function __construct( ServerRequestInterface $server_request ) {
		$this->server_request = $server_request;
	}

	/**
	 * Validates a ajax call based on current request.
	 *
	 * @param \PinkCrab\Ajax\Ajax $ajax
	 * @return bool
	 */
	public function validate( Ajax $ajax ): bool {
		if ( ! $ajax->has_nonce() ) {
			return true;
		}

		// Find nonce value in request
		$nonce_value = $this->find_nonce( $ajax->get_nonce_field() );

		// If no nonce value found in request.
		if ( is_null( $nonce_value ) ) {
			return false;
		}

		/* @phpstan-ignore-next-line, nonce handle checked at start of method*/
		return ( new Nonce( $ajax->get_nonce_handle() ) )
			->validate( $nonce_value );
	}

	/**
	 * Attempts to extract the nonce from the request
	 *
	 * @param string $nonce_field
	 * @return string|null
	 */
	protected function find_nonce( string $nonce_field ): ?string {
		$args = Ajax_Helper::extract_server_request_args( $this->server_request );

		return \array_key_exists( $nonce_field, $args )
			? $args[ $nonce_field ]
			: null;
	}

}
