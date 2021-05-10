<?php

declare(strict_types=1);

/**
 * Unit tests for the Ajax Validator
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Unit;

use WP_UnitTestCase;
use PinkCrab\Nonce\Nonce;
use PinkCrab\HTTP\HTTP_Helper;
use Gin0115\WPUnit_Helpers\Objects;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Dispatcher\Ajax_Request_Validator;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Simple_Get_Call;

class Test_Ajax_Request_Validator extends WP_UnitTestCase {

	/** @testdox When creating an instance of the Ajax Request Validator, all internal state and dependencies should be populated. */
	public function test_validator_constructed_with_internal_states(): void {
		$validator = new Ajax_Request_Validator( $this->createMock( ServerRequestInterface::class ) );
		$this->assertInstanceOf(
			ServerRequestInterface::class,
			Objects::get_property( $validator, 'server_request' )
		);
	}

	/** @testdox When validating an ajax call which has no nonce defined, it should pass validation regardless of the serverrequest contents */
	public function test_validate_ajax_with_no_nonce(): void {
		$validator = new Ajax_Request_Validator( $this->createMock( ServerRequestInterface::class ) );
		$ajax      = new Invalid_Ajax();
		$this->assertTrue( $validator->validate( $ajax ) );
	}

	/** @testdox When validating an ajax call which has a defined nonce, but no nonce defined in ServerRequest should fail validation. */
	public function test_validation_fail_if_no_nonce_in_request(): void {
		$validator = new Ajax_Request_Validator( $this->createMock( ServerRequestInterface::class ) );
		$ajax      = new Simple_Get_Call();
		$this->assertFalse( $validator->validate( $ajax ) );
	}

	/** @testdox When validating an ajax call which has a defined nonce, invalid nonce defined in ServerRequest should fail validation. */
	public function test_validation_fail_if_invalid_nonce_in_request(): void {
		$ajax = new Simple_Get_Call();

		$validator = new Ajax_Request_Validator(
			HTTP_Helper::global_server_request()->withQueryParams(
				array(
					$ajax::NONCE_FIELD => 'NOPE',
				)
			)
		);

		$this->assertFalse( $validator->validate( $ajax ) );
	}

	/** @testdox When validating an ajax request with the nonce set in the request, it should pass if the tokens match. */
	public function test_validation_success_with_valid_nonce_in_request(): void {
		$ajax = new Simple_Get_Call();

		$validator = new Ajax_Request_Validator(
			HTTP_Helper::global_server_request()->withQueryParams(
				array(
					$ajax::NONCE_FIELD => ( new Nonce( $ajax->get_nonce_handle() ) )->token(),
				)
			)
		);

		$this->assertTrue( $validator->validate( $ajax ) );
	}
}
