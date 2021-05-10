<?php

declare(strict_types=1);

/**
 * Unit tests for the Ajax model and its helpers
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Unit;

use WP_UnitTestCase;
use PinkCrab\Ajax\Ajax;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Simple_Get_Call;
use PinkCrab\Registerables\Tests\Fixtures\CPT\Invlaid_CPT;

class Test_Ajax extends WP_UnitTestCase {

	/** @testdox It should be possible to get the wp_ajax action from an Ajax model. */
	public function test_can_get_action(): void {
		$this->assertEquals(
			Simple_Get_Call::ACTION,
			( new Simple_Get_Call )->get_action()
		);
	}

	/** @testdox Attempting to get the action on an Ajax model with no action defined should return null. */
	public function test_returns_null_if_no_action_defined(): void {
		$this->assertNull(
			( new Invalid_Ajax )->get_action()
		);
	}

	/** @testdox It should be possible to get the wp_ajax nonce_handle from an Ajax model. */
	public function test_can_get_nonce_handle(): void {
		$this->assertEquals(
			Simple_Get_Call::NONCE_HANDLE,
			( new Simple_Get_Call )->get_nonce_handle()
		);
	}

	/** @testdox Attempting to get the nonce_handle on an Ajax model with no nonce_handle defined should return null. */
	public function test_returns_null_if_no_nonce_handle_defined(): void {
		$this->assertNull(
			( new Invalid_Ajax )->get_nonce_handle()
		);
	}

	/** @testdox It should be possible to get the none field key from an Ajax model */
	public function test_can_get_nonce_field_key(): void {
		$this->assertEquals(
			Simple_Get_Call::NONCE_HANDLE,
			( new Simple_Get_Call )->get_nonce_handle()
		);

		// Fallback if not defined.
		$this->assertEquals(
			'nonce',
			( new Invalid_Ajax )->get_nonce_field()
		);
	}

	/** @testdox It should be possible to get the logged in/out or prive/non_priv definitions from an ajax model. */
	public function test_can_get_priv_and_none_priv_settings(): void {

		$all_false = $this->createMock( Ajax::class );
		Objects::set_property( $all_false, 'logged_in', false );
		Objects::set_property( $all_false, 'logged_out', false );

		$this->assertFalse( $all_false->get_logged_in() );
		$this->assertFalse( $all_false->get_logged_out() );

		$all_true = new Simple_Get_Call;

		$this->assertTrue( $all_true->get_logged_in() );
		$this->assertTrue( $all_true->get_logged_out() );
	}
}
