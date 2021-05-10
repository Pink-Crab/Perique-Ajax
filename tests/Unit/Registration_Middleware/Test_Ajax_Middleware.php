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
use PinkCrab\Ajax\Dispatcher\Ajax_Dispatcher;
use PinkCrab\Ajax\Registration_Middleware\Ajax_Middleware;

class Test_Ajax_Get_Failures extends WP_UnitTestCase {

	public function test_initialisation(): void {
		$middleware = new Ajax_Middleware(
			$this->createMock( Ajax_Dispatcher::class )
		);

	}
}
