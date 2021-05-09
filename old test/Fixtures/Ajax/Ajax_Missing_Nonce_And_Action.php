<?php

declare(strict_types=1);
/**
 * Ajax call with missing nonce and action
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

class Ajax_Missing_Nonce_And_Action extends Ajax {

	protected $nonce_handle = null;
	protected $action       = null;

	/**

	 * @param ResponseInterface $response New response instance
	 * @return ResponseInterface
	 */
	public function callback( ResponseInterface $response ): ResponseInterface {
		//NO OP
	}
}
