<?php

declare(strict_types=1);

/**
 * Mock Ajax class that returns the request body as its responce
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax\Tests\Fixtures\Ajax;

use PinkCrab\Ajax\Ajax;
use PinkCrab\Ajax\Dispatcher\Response_Factory;
use PinkCrab\Ajax\Ajax_Helper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Repeating_Ajax extends Ajax {

	public const ACTION = 'repeating_ajax_action';

	/**
	 * Define the action to call.
	 * @var string
	 */
	protected $action = self::ACTION;

	/**
	 * The callback
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \PinkCrab\Ajax\Dispatcher\Response_Factory $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function callback(
		ServerRequestInterface $request,
		Response_Factory $response_factory
	): ResponseInterface {
		return $response_factory->success( Ajax_Helper::extract_server_request_args( $request ) );
	}
}
