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

namespace PinkCrab\Ajax\Tests\Intergration;

use WP_UnitTestCase;
use PinkCrab\Ajax\Dispatcher\Ajax_Dispatcher;
use PinkCrab\Perique\Application\App_Factory;
use PinkCrab\Ajax\Tests\Fixtures\Ajax\Simple_Get_Call;
use PinkCrab\Perique\Interfaces\Registration_Middleware;
use PinkCrab\Ajax\Registration_Middleware\Ajax_Middleware;

class Test_Middleware extends WP_UnitTestCase {

	protected static $perique_app;
    
    public static function setUpBeforeClass(): void
    {
        self::$perique_app = ( new App_Factory )->with_wp_dice( true )->boot();
    }
    
    
    /**
	 * Creates a functional instance of the Ajax middleware
	 *
	 * @return Registration_Middleware
	 */
	public function middleware_provider(): Registration_Middleware {
		// $ajax_dispatcher = new Ajax_Dispatcher( self::$perique_app );
		// $middleware = new Ajax_Middleware( $ajax_dispatcher );
		// return $middleware;
	}

	/** @testdox It should be possible to dispatch multiple Ajax calls using Registration Middleware class. */
	public function test_can_dispatch_ajax_calls(): void {
		// // Run setup.
		// $middleware = $this->middleware_provider();
        // $middleware->setup();

        // $middleware->process(new Simple_Get_Call());
        // dump($middleware);
	}
}
