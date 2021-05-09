<?php

declare(strict_types=1);
/**
 * Invalid cpt
 *
 * @since 0.2.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Registerables
 */

namespace PinkCrab\Registerables\Tests\Fixtures\CPT;

use PinkCrab\Registerables\Post_Type;

class Invlaid_CPT extends Post_Type {

	public $key      = null;
	public $singular = null;
	public $plural   = null;

}
