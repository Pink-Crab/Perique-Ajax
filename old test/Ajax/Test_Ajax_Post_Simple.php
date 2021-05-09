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
use PinkCrab\Registerables\Tests\Fixtures\Ajax\Ajax_Post_Simple;

class Test_Ajax_Post_Simple extends  TestCase {

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

		// HTTP helper.
		$http = new HTTP();

		// Mock the request global.s
		$_SERVER['REQUEST_METHOD']      = 'POST';
		$_POST['nonce']                 = wp_create_nonce( 'ajax_post_simple' );
		$_POST['ajax_post_simple_data'] = 'Test_Ajax_Post_Simple';

		// Body stream
		$stream = $http->stream_from_scalar(
			array(
				'nonce'                 => wp_create_nonce( 'ajax_post_simple' ),
				'ajax_post_simple_data' => 'Test_Ajax_Post_Simple',
			)
		);

		// Request
		$request = $http->request_from_globals()->withBody( $stream );

		self::$ajax_instance = new Ajax_Post_Simple( $request );

		$loader = new Loader;
		self::$ajax_instance->register( $loader );
		$loader->register_hooks();
	}

	/**
	 * Ensure the ajax call has been registered.
	 *
	 * @return void
	 */
	public function test_ajax_registered() {
		$this->assertArrayHasKey( 'wp_ajax_nopriv_ajax_post_simple', $GLOBALS['wp_filter'] );

	}


	/**
	 * Check a none logged in user can use.
	 *
	 * @runInSeparateProcess
	 * @return void
	 */
	public function test_callable_logged_out() {
		$this->expectOutputRegex( '/Ajax_Post_Simple/' );
		do_action( 'wp_ajax_nopriv_ajax_post_simple' );
	}

	/**
	 * Test that we can create the nonce field.
	 *
	 * @return void
	 */
	public function test_nonce_field(): void {

		ob_start();
		self::$ajax_instance::nonce_field();
		$nonce_field = ob_get_contents();
		ob_end_clean();

		$this->assertGreaterThan( 0, strpos( $nonce_field, $_POST['nonce'] ) );
		$this->assertGreaterThan( 0, strpos( $nonce_field, "type='hidden'" ) );
		$this->assertGreaterThan( 0, strpos( $nonce_field, "id='nonce'" ) );
		$this->assertGreaterThan( 0, strpos( $nonce_field, "name='nonce'" ) );
	}

	/**
	 * Test the static get nonce value returns the current nonce.
	 *
	 * @return void
	 */
	public function test_can_get_nonce_value(): void {
		$this->assertEquals( self::$ajax_instance::nonce_value(), $_POST['nonce'] );
	}

	/**
	 * Test the static get nonce value returns the current nonce.
	 *
	 * @return void
	 */
	public function test_can_get_action(): void {
		$this->assertEquals( self::$ajax_instance::action(), 'ajax_post_simple' );
	}
}
