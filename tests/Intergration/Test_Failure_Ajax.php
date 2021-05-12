<?php

declare(strict_types=1);

/**
 * Intergration test of various ajax calls which fail.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Intergration;

use Exception;
use PinkCrab\Ajax\Ajax_Hooks;
use PinkCrab\Ajax\Tests\Fixtures\Ajax_BaseCase;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Failure_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Has_Nonce_Ajax;

class Test_Failure_Ajax extends Ajax_BaseCase {

	/**
	 * @testdox If an ajax call throws an exception during its execution, it should return a 500 respoonse.
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_missing_action_ajax_model(): void {

		// Remove the nonce handle.
		$ajax = new Failure_Ajax();
		$ajax->set_ajax_handle( null );

		// Setup dispatcher and populate with ajax call.
		$dispatcher = $this->ajax_dispatcher_provider();
		$dispatcher->add_ajax_call( $ajax );
		$dispatcher->execute();

		// Trigger the ajax call, supress WP_DIE()!
		try {
			$this->_handleAjax( Failure_Ajax::ACTION );
		} catch ( \WPAjaxDieContinueException $e ) {
		}

		$response = json_decode( $this->_last_response );
		$this->assertTrue( \property_exists( $response, 'error' ) );
		$this->assertStringContainsString( Failure_Ajax::class, $response->error );
		$this->assertStringContainsString( 'Exception thrown by: ', $response->error );
		$this->assertEquals( 500, http_response_code() );
	}

	/**
	 * @testdox If an Ajax calls fails nonce verification, it should automatically return a 401
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_fails_nonce_check(): void {
		$ajax = new Failure_Ajax();
		// Setup dispatcher and populate with ajax call.
		$dispatcher = $this->ajax_dispatcher_provider();
		$dispatcher->add_ajax_call( $ajax );
		$dispatcher->execute();

		// Trigger the ajax call, supress WP_DIE()!
		try {
			$this->_handleAjax( Failure_Ajax::ACTION );
		} catch ( \WPAjaxDieContinueException $e ) {
		}

		$response = json_decode( $this->_last_response );
		$this->assertTrue( \property_exists( $response, 'error' ) );
		$this->assertEquals( 'unauthorised', $response->error );
		$this->assertEquals( 401, http_response_code() );
	}

}
