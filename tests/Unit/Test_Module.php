<?php

declare(strict_types=1);

/**
 * Unit tests for the Ajax Module
 *
 * @since 2.0.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Unit;

use Dice\Dice;
use WP_UnitTestCase;
use PinkCrab\Ajax\Module\Ajax;
use PinkCrab\Perique\Services\Dice\PinkCrab_Dice;

class Test_Module extends WP_UnitTestCase {

	/** @testdox When the module is constructed, the DI Rule for ServerRequestInterface should be created */
	public function test_can_construct_module(): void {
		$pc_dice = new PinkCrab_Dice( new \Dice\Dice() );
        new Ajax( $pc_dice );

        // Check rule is set.
        $rule = $pc_dice->getRule( 'Psr\Http\Message\ServerRequestInterface' )['substitutions'];
        $this->assertArrayHasKey( 'Psr\Http\Message\ServerRequestInterface', $rule );

        // Check the rule is set as a JIT closure
        $this->assertArrayHasKey(Dice::INSTANCE, $rule['Psr\Http\Message\ServerRequestInterface'] );
        $this->assertInstanceOf( \Closure::class, $rule['Psr\Http\Message\ServerRequestInterface'][Dice::INSTANCE] );
        
        // Invoking the closure should return a ServerRequestInterface
        $instance = $rule['Psr\Http\Message\ServerRequestInterface'][Dice::INSTANCE]();
        $this->assertInstanceOf( \Psr\Http\Message\ServerRequestInterface::class, $instance );
	}
}
