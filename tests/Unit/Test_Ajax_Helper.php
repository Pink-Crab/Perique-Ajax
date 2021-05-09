<?php

declare(strict_types=1);

/**
 * Unit tests for the Ajax_Middleware class
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Unit;

use WP_UnitTestCase;
use PinkCrab\Nonce\Nonce;
use PinkCrab\Ajax\Ajax_Helper;
use PinkCrab\Ajax\Ajax_Exception;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Ajax\Dispatcher\Ajax_Dispatcher;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Simple_Get_Call;
use PinkCrab\Ajax\Registration_Middleware\Ajax_Middleware;

class Test_Ajax_Helper extends WP_UnitTestCase {

    /** @testdox A cache of all ajax classes should be created and populated with each new instance created. */
    public function test_reflected_instances_should_be_cached(): void {
        $helper = new Ajax_Helper();
        $helper::get_action(Simple_Get_Call::class);
        $this->assertArrayHasKey(
            Simple_Get_Call::class,
            Objects::get_property($helper, 'class_cache')
        );
    }
    
    /** @testdox It should be possible to get the handle for an Ajax class using reflection and avoiding the constructor. */
    public function test_get_ajax_handle(): void {
        // Valid ajax class
        $this->assertEquals(
            Simple_Get_Call::ACTION,
            Ajax_Helper::get_action(Simple_Get_Call::class)
        );
    }

    /** @testdox Attempting to get the action of a none Ajax class should result in an exception being thrown */
    public function test_throws_exception_getting_handle_of_none_ajax_class(): void {
        $this->expectException(Ajax_Exception::class);
        $this->expectExceptionCode(1);
        Ajax_Helper::get_action(stdClass::class);
    }

    /** @testdox Attempting to get the action of an invalid Ajax class should result in an exception being thrown */
    public function test_throws_exception_getting_handle_of_invalid_ajax_class(): void {
        $this->expectException(Ajax_Exception::class);
        $this->expectExceptionCode(2);
        Ajax_Helper::get_action(Invalid_Ajax::class);
    }

    /** @testdox It should be possible to check if an ajax class uses a nonce */
    public function test_has_nonce(): void {
        $this->assertTrue(Ajax_Helper::has_nonce(Simple_Get_Call::class));
        $this->assertFalse(Ajax_Helper::has_nonce(Invalid_Ajax::class));
    }

    /** @testdox It should be possible to get a nonce object for an Ajax class which has a defined nonce handle */
    public function test_get_nonce(): void {
        $nonce = Ajax_Helper::get_nonce(Simple_Get_Call::class);
        $this->assertInstanceOf(Nonce::class, $nonce);
        $this->assertEquals(
            Simple_Get_Call::NONCE_HANDLE, 
            Objects::get_property($nonce, 'action')
        );
    }

    /** @testdox Attempting to get a nonce object on a class with no Nonce Handle, should return null */
    public function test_returns_null_if_get_nonce_with_no_nonce_handle(): void {
        $this->assertNull(Ajax_Helper::get_nonce(Invalid_Ajax::class));
    }
 
    /** Clears the Helpers internal class cache */
    public function tearDown(): void    {
        $helper = new Ajax_Helper();
        Objects::set_property($helper, 'class_cache', []);
    }
}
