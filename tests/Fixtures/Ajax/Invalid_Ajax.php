<?php

declare(strict_types=1);

/**
 * Mock Ajax call that fails validation.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Fixtures\Ajax;

use PinkCrab\Ajax\Ajax;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseFactoryInterface;

class Invalid_Ajax extends Ajax {

	/**
	 * The callback
	 *
	 * @param  \Psr\Http\Message\ServerRequestInterface $request
	 * @param  \Psr\Http\Message\ResponseFactoryInterface $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function callback(
		ServerRequestInterface $request,
		ResponseFactoryInterface $response_factory
	): ResponseInterface {}
}