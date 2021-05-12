<?php

declare(strict_types=1);

/**
 * Ajax model
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

namespace PinkCrab\Ajax;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Dispatcher\Response_Factory;
use Psr\Http\Message\ResponseFactoryInterface;

abstract class Ajax {

	/**
	 * Define the action to call.
	 *
	 * @var string|null
	 * @required
	 */
	protected $action;

	/**
	 * The ajax calls nonce handle.
	 *
	 * @var string|null
	 */
	protected $nonce_handle;

	/**
	 * The field name/id for the nonce field.
	 *
	 * @var string
	 */
	protected $nonce_field = 'nonce';

	/**
	 * Should the ajax call be registered if the user is logged in.
	 *
	 * @var boolean
	 */
	protected $logged_in = true;

	/**
	 * Should the ajax call be registered if the user is not logged in
	 * non_priv
	 *
	 * @var boolean
	 */
	protected $logged_out = true;

	/**
	 * The callback
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \PinkCrab\Ajax\Dispatcher\Response_Factory $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	abstract public function callback(
		ServerRequestInterface $request,
		Response_Factory $response_factory
	): ResponseInterface;


	/**
	 * Get define the action to call.
	 *
	 * @return string|null
	 */
	public function get_action(): ?string {
		return $this->action;
	}

	/**
	 * Get the ajax calls nonce handle.
	 *
	 * @return string|null
	 */
	public function get_nonce_handle(): ?string {
		return $this->nonce_handle;
	}

	/**
	 * Get the field name/id for the nonce field.
	 *
	 * @return string
	 */
	public function get_nonce_field(): string {
		return $this->nonce_field;
	}

	/**
	 * Get should the ajax call be registered if the user is logged in.
	 *
	 * @return boolean
	 */
	public function get_logged_in(): bool {
		return $this->logged_in;
	}

	/**
	 * Get non_priv
	 *
	 * @return boolean
	 */
	public function get_logged_out(): bool {
		return $this->logged_out;
	}

	/**
	 * Checks if the action is defined.
	 *
	 * @return bool
	 */
	public function has_valid_action(): bool {
		return is_string( $this->get_action() )
		&& \mb_strlen( $this->get_action() ) > 0;
	}

	/**
	 * Checks if this Ajax call uses a nonce.
	 *
	 * @return bool
	 */
	public function has_nonce(): bool {
		return ! is_null( $this->get_nonce_handle() );
	}
}
