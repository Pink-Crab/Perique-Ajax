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
use PinkCrab\HTTP\HTTP_Helper;
use Gin0115\WPUnit_Helpers\Objects;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Ajax\Tests\Fixtures\Ajax_BaseCase;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Invalid_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Has_Nonce_Ajax;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Repeating_Ajax;
use PinkCrab\Ajax\Registration_Middleware\Ajax_Middleware;

class Test_App_Instance extends Ajax_BaseCase {

	/** @testdox It should be possible to add the Ajax Dispatcher in as Registration Middleware as part of the Prique Framework. You then should be able to just add Ajax Models to the Registration list used for the internal Registeration system.  */
    public function test_app_instance(): void {
		
        // Construct the app
        $app = ( new App_Factory )->with_wp_dice( true )
			->app_config( array() )
			->di_rules(
				array(
					'*' => array(
						'substitutions' => array(
							ServerRequestInterface::class => HTTP_Helper::global_server_request(),
						),
					),
				)
			)
			->registration_classses(
				array(
					Has_Nonce_Ajax::class,
					Repeating_Ajax::class,
				)
			)
			->boot();

		// Add the custom middleware
        $middleware = $app::make( Ajax_Middleware::class );
		$app->registration_middleware( $middleware );
		
        // Trigger app intialisation
        do_action( 'init' );

		// Extract the hooks from the dispatcher, from middleware
        $dispatcher = Objects::get_property( $middleware, 'dispatcher' );
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
