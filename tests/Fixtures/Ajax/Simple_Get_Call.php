<?php

declare(strict_types=1);

/**
 * Mock Ajax call for a simple GET
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Fixtures\Ajax;

use PinkCrab\Ajax\Ajax;

class Simple_Get_Call extends Ajax {

	public const ACTION       = 'simple_get_call_action';
	public const NONCE_HANDLE = 'simple_get_call_nonce';

	/**
	 * Define the action to call.
	 */
	protected $action = self::ACTION;

	/**
	 * The ajax calls nonce handle.
	 */
	protected $nonce_handle = self::NONCE_HANDLE;

	/**
     * Sets the callback
     */
    public function set_callback(): void {
		$this->callback = function() {
			print 1;
		};
	}
}
