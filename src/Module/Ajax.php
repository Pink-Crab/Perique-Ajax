<?php

declare(strict_types=1);

/**
 * Ajax Module
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Module;

use PinkCrab\HTTP\HTTP_Helper;
use PinkCrab\Loader\Hook_Loader;
use PinkCrab\Perique\Interfaces\Module;
use PinkCrab\Ajax\Module\Ajax_Middleware;
use PinkCrab\Perique\Application\App_Config;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Perique\Interfaces\DI_Container;

class Ajax implements Module {

	public function __construct( DI_Container $di_container ) {
		$di_container->addRule(
			'*',
			array(
				'substitutions' => array(
					ServerRequestInterface::class => array(
						\Dice\Dice::INSTANCE => fn() => HTTP_Helper::global_server_request(),
					),
				),
			)
		);
	}

	/** @inheritDoc */
	public function get_middleware(): ?string {
		return Ajax_Middleware::class;
	}

	## Unused methods
	public function pre_register( App_Config $config, Hook_Loader $loader, DI_Container $di_container ): void {} // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceBeforeLastUsed
	public function pre_boot( App_Config $config, Hook_Loader $loader, DI_Container $di_container ): void {} // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceBeforeLastUsed
	public function post_register( App_Config $config, Hook_Loader $loader, DI_Container $di_container ): void {} // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceBeforeLastUsed
}
