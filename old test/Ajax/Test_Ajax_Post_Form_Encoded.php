<?php

declare(strict_types=1);

/**
 * Tests the Ajax_Get mock.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Core
 */

namespace PinkCrab\Registerables\Tests;

use PinkCrab\HTTP\HTTP;
use PinkCrab\Loader\Loader;
use PinkCrab\Registerables\Ajax;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;
use PinkCrab\Registerables\Tests\Fixtures\Ajax\Ajax_Post_Form_Encoded;

class Test_Ajax_Post_Form_Encoded extends TestCase {

	/**
	 * Holds the instance of the
	 *
	 * @var Ajax
	 */
	protected static $ajax_instance;

	/**
	 * Ensure the headers are cleared on each test.
	 *
	 * @var bool
	 */
	protected $preserveGlobalState = false;

	protected function set_up() {
		parent::set_up();

		// Request
		$_SERVER['REQUEST_METHOD'] = 'POST';

		$request             = ( new HTTP() )->request_from_globals()
			->withParsedBody(
				array( 'ajax_post_form_endcoded' => 'Test_Ajax_Post_Simple' )
			)
			->withHeader( 'Content-Type', 'application/x-www-form-urlencoded;' );
		self::$ajax_instance = new Ajax_Post_Form_Encoded( $request );
		$loader              = new Loader;
		self::$ajax_instance->register( $loader );
		$loader->register_hooks();
	}

	/**
	 * Ensure the ajax call has been registered.
	 *
	 * @return void
	 */
	public function test_ajax_registered() {
		$this->assertArrayHasKey( 'wp_ajax_nopriv_ajax_post_form_endcoded', $GLOBALS['wp_filter'] );
	}

	/**
	 * Check a none logged in user can use.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_callable_logged_out() {
		$this->expectOutputRegex( '/Ajax_Post_Form_Encoded/' );
		do_action( 'wp_ajax_nopriv_ajax_post_form_endcoded' );
	}

}
