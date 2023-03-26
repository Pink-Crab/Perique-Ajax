<?php

declare(strict_types=1);

/**
 * Intergration tests of using Registration Middleware with live app.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Intergration;

use Exception;
use PinkCrab\Ajax\Module\Ajax;
use PinkCrab\HTTP\HTTP_Helper;
use PinkCrab\Ajax\Ajax_Bootstrap;
use Gin0115\WPUnit_Helpers\Objects;
use PinkCrab\Ajax\Module\Ajax_Middleware;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Ajax\Tests\Fixtures\Ajax_BaseCase;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Has_Nonce_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Repeating_Ajax;
use PinkCrab\Perique\Interfaces\Registration_Middleware;

class Test_App_Instance extends Ajax_BaseCase {

	/** @testdox It should be possible to add the Ajax Dispatcher in as Registration Middleware as part of the Prique Framework. You then should be able to just add Ajax Models to the Registration list used for the internal Registeration system.  */
	public function test_app_instance(): void {

		// Construct the app
		$app = ( new App_Factory() )
			->set_base_view_path( FIXTURES_PATH )
			->default_setup()
			->module( Ajax::class )
			->registration_classes(
				array(
					Has_Nonce_Ajax::class,
					Repeating_Ajax::class,
				)
			)->boot();

		// Trigger app intialisation
		do_action( 'init' );


		// Extract the hooks from the dispatcher, from middleware
		$module_manager = Objects::get_property( $app, 'module_manager' );
		$registration   = Objects::get_property( $module_manager, 'registration_service' );
		$middleware     = Objects::get_property( $registration, 'middleware' );
		$this->assertArrayHasKey( Ajax_Middleware::class, $middleware);
		$this->assertInstanceOf( Registration_Middleware::class, $middleware[Ajax_Middleware::class]);

		$dispatcher  = Objects::get_property( $middleware[Ajax_Middleware::class], 'dispatcher' );
		$hook_loader = Objects::get_property( $dispatcher, 'loader' );
		$hooks       = Objects::get_property( $hook_loader, 'hooks' );
		$hooks       = $hooks->export();


		// Check ajax calls are registered.
		$this->assertTrue( $hooks[0]->is_registered() );
		$this->assertTrue( \has_action( 'wp_ajax_' . $hooks[0]->get_handle() ) );
		$this->assertTrue( $hooks[1]->is_registered() );
		$this->assertTrue( \has_action( 'wp_ajax_' . $hooks[1]->get_handle() ) );
	}

}
