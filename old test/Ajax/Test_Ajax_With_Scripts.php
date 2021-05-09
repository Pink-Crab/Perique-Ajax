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

use WP_UnitTestCase;
use PinkCrab\HTTP\HTTP;
use PinkCrab\Loader\Hook;
use PinkCrab\Loader\Loader;
use PinkCrab\Enqueue\Enqueue;
use Nyholm\Psr7\ServerRequest;
use PinkCrab\Registerables\Ajax;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Registerables\Tests\Fixtures\Ajax\Ajax_With_Scripts;

class Test_Ajax_With_Scripts extends WP_UnitTestCase {

	protected function server_request_provider(): ServerRequest {
		return ( new HTTP() )->request_from_globals();
	}

	/**
	 * Ensure that the scripts are registered to the ajax call.
	 *
	 * @return void
	 */
	public function test_scripts_added_to_scripts_on_register(): void {

		$ajax_instance = new Ajax_With_Scripts( $this->server_request_provider() );

		// Just run setup to set the scripts.
		$ajax_instance->set_up();
		// Extract scripts as an array.
		$scripts = Objects::get_property( $ajax_instance, 'scripts' )->to_array();

		$this->assertInstanceOf( Enqueue::class, $scripts[0] );
		$this->assertEquals( 'ajax_with_scripts_one', Objects::get_property( $scripts[0], 'handle' ) );
		$this->assertEquals( 'script', Objects::get_property( $scripts[0], 'type' ) );
		$this->assertEquals( 'http://www.url.tld/file.js', Objects::get_property( $scripts[0], 'src' ) );

		$this->assertInstanceOf( Enqueue::class, $scripts[1] );
		$this->assertEquals( 'ajax_with_scripts_two', Objects::get_property( $scripts[1], 'handle' ) );
		$this->assertEquals( 'script', Objects::get_property( $scripts[1], 'type' ) );
		$this->assertGreaterThan( 0, strpos( Objects::get_property( $scripts[1], 'src' ), 'Ajax/file.js' ) );
	}

	/**
	 * Check that both scripts are added to the loaded as front end scripts.
	 *
	 * @return void
	 */
	public function test_scripts_added_to_loader_front_end(): void {

		/** @var Ajax */
		$ajax_instance = new Ajax_With_Scripts( $this->server_request_provider() );
		$loader        = new Loader();
	
		$ajax_instance->register( $loader );

		// Extract all actions
		$scripts = Objects::get_property( $loader, 'hooks' );
		$scripts = array_filter(
			$scripts->export(),
			function( Hook $hook ) {
				return $hook->get_type() === Hook::ACTION;
			}
		);


		// Check we have 2 front facing and all values are expected.
		$this->assertCount( 2, $scripts );
		foreach ( $scripts as $script ) {
			$this->assertEquals( 'wp_enqueue_scripts', $script->get_handle() );
			$this->assertTrue( is_callable( $script->get_callback() ) );
			$this->assertEquals( 10, $script->get_priority() );
			$this->assertEquals( 1, $script->args_count() );
		}
	}

	/**
	 * Test the scripts are enqueued.
	 *
	 * @return void
	 */
	public function test_loader_enqueues_scripts(): void {

		/** @var Ajax */
		$ajax_instance = new Ajax_With_Scripts( $this->server_request_provider() );
		$loader        = new Loader();
		$ajax_instance->register( $loader );
		$loader->register_hooks();

		// Trigger the scripts being added to enqueue list.
		do_action( 'wp_enqueue_scripts' );
		$registered_scripts = Objects::get_property( $GLOBALS['wp_scripts'], 'registered' );

		// Check first script.
		$script_one = $registered_scripts['ajax_with_scripts_one'];
		$this->assertArrayHasKey( 'ajax_with_scripts_one', $registered_scripts );
		$this->assertInstanceOf( \_WP_Dependency::class, $script_one );
		$this->assertEquals( 'ajax_with_scripts_one', $script_one->handle );
		$this->assertEquals( 'http://www.url.tld/file.js', $script_one->src );
		$this->assertEquals( '1.2.3', $script_one->ver );
		$this->assertCount( 1, $script_one->deps );
		$this->assertContains( 'jquery', $script_one->deps );

		// Second script.
		$script_two = $registered_scripts['ajax_with_scripts_two'];
		$this->assertArrayHasKey( 'ajax_with_scripts_two', $registered_scripts );
		$this->assertInstanceOf( \_WP_Dependency::class, $script_two );
		$this->assertEquals( 'ajax_with_scripts_two', $script_two->handle );
		$this->assertGreaterThan( 0, strpos( $script_two->src, 'Ajax/file.js' ) );
		$this->assertEquals( '0.1.2', $script_two->ver );
		$this->assertCount( 1, $script_two->deps );
		$this->assertContains( 'angular', $script_two->deps );
	}

	/**
	 * Check the conditional output can be called.
	 * Uses a temp property for setting output
	 * Would actually be a proper conditional.
	 *
	 * @return void
	 */
	public function text_optional_conditional_can_be_used(): void {
		$ajax_instance = new Ajax_With_Scripts( $this->server_request_provider() );

		// Set to false (helper property for tests)
		$ajax_instance->_conditional_value = false;
		$this->assertFalse( $ajax_instance->conditional() );

		// Set to true.
		$ajax_instance->_conditional_value = true;
		$this->assertTrue( $ajax_instance->conditional() );
	}

	public function test_admin_scripts_loaded_in_admin(): void {
		set_current_screen( 'edit.php' );

		/** @var Ajax */
		$ajax_instance = new Ajax_With_Scripts( $this->server_request_provider() );
		$loader        = new Loader();
		$ajax_instance->register( $loader );
		$loader->register_hooks();

		// Trigger the scripts being added to enqueue list.
		do_action( 'admin_enqueue_scripts' );
		$registered_scripts = Objects::get_property( $GLOBALS['wp_scripts'], 'registered' );

		// Check first script.
		$script_one = $registered_scripts['ajax_with_scripts_one'];
		$this->assertArrayHasKey( 'ajax_with_scripts_one', $registered_scripts );
		$this->assertInstanceOf( \_WP_Dependency::class, $script_one );
		$this->assertEquals( 'ajax_with_scripts_one', $script_one->handle );
		$this->assertEquals( 'http://www.url.tld/file.js', $script_one->src );
		$this->assertEquals( '1.2.3', $script_one->ver );
		$this->assertCount( 1, $script_one->deps );
		$this->assertContains( 'jquery', $script_one->deps );

		// Second script.
		$script_two = $registered_scripts['ajax_with_scripts_two'];
		$this->assertArrayHasKey( 'ajax_with_scripts_two', $registered_scripts );
		$this->assertInstanceOf( \_WP_Dependency::class, $script_two );
		$this->assertEquals( 'ajax_with_scripts_two', $script_two->handle );
		$this->assertGreaterThan( 0, strpos( $script_two->src, 'Ajax/file.js' ) );
		$this->assertEquals( '0.1.2', $script_two->ver );
		$this->assertCount( 1, $script_two->deps );
		$this->assertContains( 'angular', $script_two->deps );
	}
}
