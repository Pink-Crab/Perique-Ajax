<?php

declare(strict_types=1);

/**
 * The Bootstrap class for adding all needed services to Perique
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
 * @since 1.0.2
 */

namespace PinkCrab\Ajax;

use PinkCrab\Perique\Application\Hooks;
use PinkCrab\HTTP\HTTP_Helper;
use Psr\Http\Message\ServerRequestInterface;

class Ajax_Bootstrap {

	/**
	 * Called to bootstrap Ajax with Perique.
	 *
	 * Sets the DI rule for the ServerRequest interface.
	 *
	 * @return void
	 */
	public static function use(): void {
		add_filter(
			Hooks::APP_INIT_SET_DI_RULES,
			function( array $rules ): array {

				// Ensure the global rules exist.
				if ( ! \array_key_exists( '*', $rules ) ) {
					$rules['*'] = array();
				}
				if ( ! \array_key_exists( 'substitutions', $rules['*'] ) ) {
					$rules['*']['substitutions'] = array();
				}

				// Set the global rule for ServerRequestInterface
				$rules['*']['substitutions'][ ServerRequestInterface::class ]
					= HTTP_Helper::global_server_request();

				return $rules;
			}
		);
	}
}
