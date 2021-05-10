<?php

declare(strict_types=1);

/**
 * Creates the responses used for ajax calls.
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

use Nyholm\Psr7\Stream;
use PinkCrab\HTTP\HTTP;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Response_Factory implements ResponseFactoryInterface {

	/** @var HTTP */
	protected $http;

	public function __construct( HTTP $http ) {
		$this->http = $http;
	}

	/**
	 * Create a new response.
	 *
	 * @param int $code The HTTP status code. Defaults to 200.
	 * @param string $reasonPhrase
	 */
	public function createResponse( int $code = 200, string $reasonPhrase = '' ): ResponseInterface {
		return $this->http->psr7_response()
			->withStatus( $code )
			->withBody( Stream::create( $reasonPhrase ) );
	}

	/**
	 * Return a 200 response, with the passed payload
	 *
	 * @param array<mixed> $payload
	 * @return ResponseInterface
	 */
	public function success( array $payload ): ResponseInterface {
		return $this->createResponse( 200, json_encode( $payload ) ?: '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	}

	/**
	 * Returns a 401 response, with an optional payload.
	 * Defaults to [ 'error' => 'unauthorised' ]
	 *
	 * @param array<mixed> $payload
	 * @return ResponseInterface
	 */
	public function unauthorised( array $payload = array( 'error' => 'unauthorised' ) ): ResponseInterface {
		return $this->createResponse( 401, json_encode( $payload ) ?: '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	}

	/**
	 * Returns a 500 response, with an optional payload.
	 * Defaults to [ 'error' => 'error' ]
	 *
	 * @param array<mixed> $payload
	 * @return ResponseInterface
	 */
	public function failure( array $payload = array( 'error' => 'error' ) ): ResponseInterface {
		return $this->createResponse( 500, json_encode( $payload ) ?: '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	}

	/**
	 * Returns a 404 response, with an optional payload.
	 * Defaults to [ 'error' => 'not found' ]
	 *
	 * @param array<mixed> $payload
	 * @return ResponseInterface
	 */
	public function not_found( array $payload = array( 'error' => 'not found' ) ): ResponseInterface {
		return $this->createResponse( 404, json_encode( $payload ) ?: '' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	}
}
