<?php

declare(strict_types=1);
/**
 * Mock Post Type with metaboxes
 *
 * @since 0.2.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Registerables
 */

namespace PinkCrab\Registerables\Tests\Fixtures\CPT;

use PinkCrab\Registerables\MetaBox;
use PinkCrab\Registerables\Post_Type;

class MetaBox_CPT extends Post_Type {

	public $key      = 'metabox_cpt';
	public $singular = 'singular';
	public $plural   = 'plural';

	public function metaboxes(): void {

		$this->metaboxes[] = MetaBox::normal( 'metabox_cpt_normal' )
			->label( 'metabox_cpt_normal TITLE' )
			->view(
				function( \WP_Post $post, array $args ) {
					print( 'metabox_cpt_normal VIEW' );
				}
			)->view_vars( array( 'key1' => 1 ) );

		$this->metaboxes[] = MetaBox::side( 'metabox_cpt_side' )
			->label( 'metabox_cpt_side TITLE' )
			->view(
				function( \WP_Post $post, array $args ) {
					print( 'metabox_cpt_side VIEW' );
				}
			)->view_vars( array( 'key2' => 2 ) );
	}

}
