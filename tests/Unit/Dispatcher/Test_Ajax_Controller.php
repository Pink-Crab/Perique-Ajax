<?php

declare(strict_types=1);

/**
 * Unit tests for the Ajax (Dispatcher) Controller
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Unit;

use WP_UnitTestCase;
use PinkCrab\HTTP\HTTP;
use ReflectionFunction;
use Gin0115\WPUnit_Helpers\Objects;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Dispatcher\Ajax_Controller;
use PinkCrab\Ajax\Dispatcher\Response_Factory;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Dispatcher\Ajax_Request_Validator;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Logged_In_Out_Ajax;

class Test_Ajax_Controller extends WP_UnitTestCase {

	/** @testdox When an instance of the Ajax controller is constructed, all dependencies should be set as properties. */
	public function test_controller_initialisation(): void {
		$response_factory  = $this->createMock( Response_Factory::class );
		$server_request    = $this->createMock( ServerRequestInterface::class );
		$http_helper       = $this->createMock( HTTP::class );
		$request_validator = $this->createMock( Ajax_Request_Validator::class );

		$controller = new Ajax_Controller( $server_request, $response_factory, $http_helper, $request_validator );

		$this->assertSame( $server_request, Objects::get_property( $controller, 'server_request' ) );
		$this->assertSame( $response_factory, Objects::get_property( $controller, 'response_factory' ) );
		$this->assertSame( $http_helper, Objects::get_property( $controller, 'http_helper' ) );
		$this->assertSame( $request_validator, Objects::get_property( $controller, 'request_validator' ) );
	}

	/** @testdox It should be possible to validate a request from the controller */
	public function test_can_validate_ajax(): void {
		$server_request = $this->createMock( ServerRequestInterface::class );
		$controller     = new Ajax_Controller(
			$server_request,
			$this->createMock( Response_Factory::class ),
			$this->createMock( HTTP::class ),
			new Ajax_Request_Validator( $server_request )
		);

		$this->assertTrue( $controller->validate_request( new Invalid_Ajax() ) );
		$this->assertFalse( $controller->validate_request( new Logged_In_Out_Ajax() ) );
	}

	/** @testdox The controller should be able to invoke the callback of an ajax call and be returns a populated with valid headers, body/params */
	public function test_can_invoke_callback(): void {
		$server_request = $this->createMock( ServerRequestInterface::class );
		$http           = new HTTP();
		$controller     = new Ajax_Controller(
			$server_request,
			new Response_Factory( $http ),
			$http,
			new Ajax_Request_Validator( $server_request )
		);

		$response      = $controller->invoke_callback( new Logged_In_Out_Ajax() );
		$response_body = \json_decode( (string) $response->getBody() );

		$this->assertEquals( 200, $response->getStatusCode() );
		$this->assertEquals( Logged_In_Out_Ajax::class, $response_body->success );
	}

	/** @testdox The callback used for the ajax call should be contructed bound to the controller with access to the ajax class. */
    public function test_can_create_callback(): void {
		$controller = new Ajax_Controller(
			$this->createMock( ServerRequestInterface::class ),
			$this->createMock( Response_Factory::class ),
			$this->createMock( HTTP::class ),
			$this->createMock( Ajax_Request_Validator::class )
		);

		$callback  = $controller->create_callback( new Logged_In_Out_Ajax );
		$reflected = new ReflectionFunction( $callback );

		$this->assertSame( $controller, $reflected->getClosureThis() );
		$this->assertArrayHasKey( 'ajax_class', $reflected->getStaticVariables() );
		$this->assertInstanceOf( Logged_In_Out_Ajax::class, $reflected->getStaticVariables()['ajax_class'] );
	}
}
