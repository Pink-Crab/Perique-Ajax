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
use Psr\Http\Message\ServerRequestInterface;

class Ajax_Request_Validator {

	/** @var ServerRequestInterface */
	protected $server_request;

	public function __construct( ServerRequestInterface $server_requet ) {
		$this->server_requet = $server_requet;
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
	}

	/**
	 * Attempts to extract the nonce from the request
	 *
	 * @param string $nonce_field
	 * @return string|null
	 */
	protected function find_nonce( string $nonce_field ): ?string {
		# code...
	}
    
    /**
     * Attempts to extract the args from the request.
     * Uses the request type to determine the location and format.
     *
     * @return array<string, string>
     */
    protected function extract_args_from_request(): array
    {
        # code...
    }
}
