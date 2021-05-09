<?php

declare(strict_types=1);
/**
 * Mock Post Type with meta data
 *
 * @since 0.4.1
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Registerables
 */

namespace PinkCrab\Registerables\Tests\Fixtures\CPT;

use PinkCrab\Registerables\Meta_Data;
use PinkCrab\Registerables\Post_Type;

class Meta_Data_CPT extends Post_Type {

	public const META_1 = array(
		'key'               => 'meta_key_1',
		'type'              => 'string',
		'default'           => 'default value 1',
		'description'       => 'test 1',
		'single'            => true,
		'sanitize_callback' => 'strtoupper',
		'auth_callback'     => '__return_true',
	);

	public const META_2 = array(
		'key'               => 'meta_key_2',
		'type'              => 'number',
		'default'           => 3.14,
		'description'       => 'test 2',
		'single'            => true,
		'sanitize_callback' => 'floatval',
		'auth_callback'     => '__return_true',
	);


	public $key      = 'metadata_cpt';
	public $singular = 'singular';
	public $plural   = 'plural';

	/**
	 * Define some fake meta.
	 *
	 * @return void
	 */
	public function meta_data(): void {

		$this->meta_data[] = ( new Meta_Data( self::META_1['key'] ) )
			->type( self::META_1['type'] )
			->default( self::META_1['default'] )
			->description( self::META_1['description'] )
			->single( self::META_1['single'] )
			->sanitize( self::META_1['sanitize_callback'] )
			->permissions( self::META_1['auth_callback'] );

		$this->meta_data[] = ( new Meta_Data( self::META_2['key'] ) )
			->type( self::META_2['type'] )
			->default( self::META_2['default'] )
			->description( self::META_2['description'] )
			->single( self::META_2['single'] )
			->sanitize( self::META_2['sanitize_callback'] )
			->permissions( self::META_2['auth_callback'] );
	}

}
