<?php

declare(strict_types=1);
/**
 * Ajax call using form-urlencode format (parsedBody).
 * Not testing with a Nonce.
 * Just tests that the values are decoded properly from the parsedBody, not body
 * as a "regular" POST call.
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

class Ajax_Post_Form_Encoded extends Ajax {

	protected $action = 'ajax_post_form_endcoded';

	/**
	 * Handles the callback.
	 *
	 * @param ServerRequestInterface $request
	 * @return void
	 */
	public function callback( ResponseInterface $response ): ResponseInterface {
		return $response->withBody(
			HTTP_Helper::stream_from_scalar(
				array( 'success' => 'Ajax_Post_Form_Encoded' )
			)
		);
	}
}
