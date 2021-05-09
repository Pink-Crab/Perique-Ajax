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

namespace PinkCrab\Ajax\Dispatcher;

use PinkCrab\Ajax\Ajax;
use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Ajax\Dispatcher\Response_Controller;

class Ajax_Dispatcher {

	/** @var Hook_Loader */
	protected $loader;

	/** @var Response_Controller */
	protected $response_controller;

	public function __construct( Response_Controller $response_controller ) {
		$this->loader              = new Hook_Loader();
		$this->response_controller = $response_controller;
	}

	/**
	 * Adds an ajax call to the loader
	 *
	 * @param \PinkCrab\Ajax\Ajax $ajax
	 * @return void
	 */
	public function add_ajax_call( Ajax $ajax ): void {

		// If ajax is not valid (no action), skip
		if ( ! $ajax->is_valid() ) {
			return;
		}

		$this->loader->ajax(
			$ajax->get_action(),
			$this->response_controller->create_callback( $ajax ),
			$ajax->get_logged_out(),
			$ajax->get_logged_in()
		);
	}

	/**
	 * Register all hooks
	 *
	 * @return void
	 */
	public function execute(): void {
		$this->loader->register_hooks();
	}

}
