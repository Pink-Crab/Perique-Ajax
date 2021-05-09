<?php

declare(strict_types=1);
/**
 * Basic Ajax Call using Get with none json headers
 *
 * @since 0.2.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Registerables
 */

namespace PinkCrab\Registerables\Tests\Fixtures\Ajax;

use PinkCrab\HTTP\HTTP_Helper;
use PinkCrab\Registerables\Ajax;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Ajax_Post_Simple extends Ajax {

	protected $nonce_handle = 'ajax_post_simple';
	protected $action       = 'ajax_post_simple';

	/**
	 * Handles the callback.
	 *
	 * @param ServerRequestInterface $request
	 * @return void
	 */
	public function callback( ResponseInterface $response ): ResponseInterface {
		return $response->withBody(
			HTTP_Helper::stream_from_scalar(
				array( 'success' => 'Ajax_Post_Simple' )
			)
		);
		// wp_send_json_success( $response->getParsedBody() );
	}
}
