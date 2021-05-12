<?php

declare(strict_types=1);

/**
 * Intergration tests of the hooks used in the callback process.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Intergration;

use Exception;
use PinkCrab\Ajax\Ajax;
use PinkCrab\Ajax\Ajax_Hooks;
use PinkCrab\Ajax\Ajax_Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Tests\Fixtures\Ajax_BaseCase;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Failure_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Repeating_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Has_Nonce_Ajax;

class Test_Callback_Hooks extends Ajax_BaseCase {

	/**
	 * @testdox It should be possible to hook in when exceptions are thrown, so all errors can be logged or handled by some other means.
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_action_fired_on_callback_exception(): void {
		// Populate array by reference if exception is thrown and action is called.
		$error_thrown = array();

		add_action(
			Ajax_Hooks::CALLBACK_EXECUTION_EXCEPTION,
			function( $th, $ajax_class ) use ( &$error_thrown ) {
				$error_thrown = array(
					'exception' => $th,
					'class'     => $ajax_class,
				);
			},
			10,
			2
		);

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

		$this->assertCount( 2, $error_thrown );
		$this->assertArrayHasKey( 'exception', $error_thrown );
		$this->assertArrayHasKey( 'class', $error_thrown );
		$this->assertSame( $ajax, $error_thrown['class'] );
		$this->assertInstanceOf( Exception::class, $error_thrown['exception'] );
	}

	/**
	 * @testdox It should be possible to filter the request before an ajax calls, callback method is called.
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_callback_request_filter(): void {

		add_filter(
			Ajax_Hooks::CALLBACK_REQUEST_FILTER,
			function( ServerRequestInterface $request, Ajax $ajax_class ) {
				return $request->withQueryParams( array( 'callback_hook' => 'test' ) );
			},
			10,
			2
		);

		// Remove the nonce handle.
		$ajax = new Repeating_Ajax();

		// Setup dispatcher and populate with ajax call.
		$dispatcher = $this->ajax_dispatcher_provider();
		$dispatcher->add_ajax_call( $ajax );
		$dispatcher->execute();

		// Trigger the ajax call, supress WP_DIE()!
		try {
			$this->_handleAjax( Repeating_Ajax::ACTION );
		} catch ( \WPAjaxDieContinueException $e ) {
		}

		$response = json_decode( $this->_last_response );
		$this->assertTrue( \property_exists( $response, 'callback_hook' ) );
		$this->assertEquals( 'test', $response->callback_hook );
		$this->assertEquals( 200, http_response_code() );
	}

	/**
	 * @testdox It should be possible to add additional validation checks via a filter for all ajax calls.
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_callback_request_validation_filter(): void {

		add_filter(
			Ajax_Hooks::REQUEST_NONCE_VERIFICATION,
			function( bool $verified, Ajax $ajax_class, ServerRequestInterface $request ) {
				return true; // Just approve the call, even though no nonce set in request
			},
			10,
			3
		);

		$ajax = new Has_Nonce_Ajax();

		// Setup dispatcher and populate with ajax call.
		$dispatcher = $this->ajax_dispatcher_provider();
		$dispatcher->add_ajax_call( $ajax );
		$dispatcher->execute();

		// Trigger the ajax call, supress WP_DIE()!
		try {
			$this->_handleAjax( Has_Nonce_Ajax::ACTION );
		} catch ( \WPAjaxDieContinueException $e ) {
		}

		$response = json_decode( $this->_last_response );

		// Should have failed due to no nonce.
		$this->assertTrue( \property_exists( $response, 'success' ) );
		$this->assertEquals( 200, http_response_code() );
	}

	/**
	 * @testdox It should be possible to filter the response from an ajax call and have access to the Ajax Model and initial Request
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_callback_response_validation_filter(): void {

		add_filter(
			Ajax_Hooks::CALLBACK_RESPONSE_FILTER,
			function( ResponseInterface $response, Ajax $ajax_class, ServerRequestInterface $request ) {
				return $response->withStatus( 418 );
			},
			10,
			3
		);

		$ajax = new Repeating_Ajax();

		// Setup dispatcher and populate with ajax call.
		$dispatcher = $this->ajax_dispatcher_provider();
		$dispatcher->add_ajax_call( $ajax );
		$dispatcher->execute();

		// Trigger the ajax call, supress WP_DIE()!
		try {
			$this->_handleAjax( Repeating_Ajax::ACTION );
		} catch ( \WPAjaxDieContinueException $e ) {
		}

		$response = json_decode( $this->_last_response );

		// Should have a 418 response
		$this->assertEquals( 418, http_response_code() );
	}
}
