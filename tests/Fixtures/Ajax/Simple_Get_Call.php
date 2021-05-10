<?php

declare(strict_types=1);

/**
 * Mock Ajax call for a simple GET
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

class Simple_Get_Call extends Ajax {

	public const ACTION       = 'simple_get_call_action';
	public const NONCE_HANDLE = 'simple_get_call_nonce';
	public const NONCE_FIELD  = 'simple_get_call_nonce_field';

	/**
	 * Define the action to call.
	 * @var string
	 */
	protected $action = self::ACTION;

	/**
	 * The ajax calls nonce handle.
	 * @var string
	 */
	protected $nonce_handle = self::NONCE_HANDLE;

	/**
	 * The nonce field
	 * @var string
	 */
	protected $nonce_field = self::NONCE_FIELD;

	/**
	 * The callback
	 *
	 * @param \Psr\Http\Message\ServerRequestInterface $request
	 * @param \Psr\Http\Message\ResponseFactoryInterface $response_factory
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function callback(
		ServerRequestInterface $request,
		ResponseFactoryInterface $response_factory
	): ResponseInterface {
		return $response_factory->success( array( 'success' => __CLASS__ ) );
	}
}
