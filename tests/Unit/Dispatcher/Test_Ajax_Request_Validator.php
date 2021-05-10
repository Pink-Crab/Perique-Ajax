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
use Gin0115\WPUnit_Helpers\Objects;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Dispatcher\Ajax_Request_Validator;

class Test_Ajax_Request_Validator extends WP_UnitTestCase {

	/** @testdox When creating an instance of the Ajax Request Validator, all internal state and dependencies should be populated. */
	public function test_validator_constructed_with_internal_states(): void {
		$validator = new Ajax_Request_Validator( $this->createMock( ServerRequestInterface::class ) );
		$this->assertInstanceOf(
			ServerRequestInterface::class,
			Objects::get_property( $validator, 'server_request' )
		);
	}

	public function test_validate_ajax_with_no_nonce(): void {
		# code...
	}
}
