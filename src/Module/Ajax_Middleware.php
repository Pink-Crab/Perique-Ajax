<?php

declare(strict_types=1);

/**
 * Ajax Dispatcher Middleware
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Module;

use PinkCrab\Ajax\Ajax;
use PinkCrab\Ajax\Dispatcher\Ajax_Dispatcher;
use PinkCrab\Perique\Interfaces\Registration_Middleware;


class Ajax_Middleware implements Registration_Middleware {

	/** @var Ajax_Dispatcher */
	public $dispatcher;

	public function __construct( Ajax_Dispatcher $dispatcher ) {
		$this->dispatcher = $dispatcher;
	}

	/**
	 * Add all valid ajax calls to the dispatcher.
	 *
	 * @param object $class
	 * @return object
	 */
	public function process( object $class ): object {
		if ( is_a( $class, Ajax::class )
		&& is_admin()
		&& wp_doing_ajax()
		) {
			$this->dispatcher->add_ajax_call( $class );
		}
		return $class;
	}

	public function setup(): void {
		/*noOp*/
	}

	/**
	 * Register all ajax calls.
	 *
	 * @return void
	 */
	public function tear_down(): void {
		$this->dispatcher->execute();
	}
}
