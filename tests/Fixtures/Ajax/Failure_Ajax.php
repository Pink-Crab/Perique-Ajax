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

use Exception;
use PinkCrab\Ajax\Ajax;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Ajax\Dispatcher\Response_Factory;

class Failure_Ajax extends Ajax {

	public const ACTION       = 'failure_action';
	public const NONCE_HANDLE = 'failure_nonce';
	public const NONCE_FIELD  = 'failure_nonce_field';

	/**
	 * Define the action to call.
	 * @var string
	 */
	protected ?string $action = self::ACTION;

	/**
	 * The ajax calls nonce handle.
	 * @var string
	 */
	protected ?string $nonce_handle = self::NONCE_HANDLE;

	/**
	 * The nonce field
	 * @var string
	 */
	protected string $nonce_field = self::NONCE_FIELD;

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
		throw new Exception( "Exception thrown by: ". __CLASS__ );
	}

	/**
	 * Override the ajax handle
	 *
	 * @param string|null $handle
	 * @return void
	 */
	public function set_ajax_handle( ?string $handle ): void {
		$this->nonce_handle = $handle;
	}
}
