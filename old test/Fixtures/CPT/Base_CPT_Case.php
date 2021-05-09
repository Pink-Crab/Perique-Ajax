<?php

declare(strict_types=1);

/**
 * Loader tests.
 *
 * @since 0.1.0
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Core
 */

namespace PinkCrab\Registerables\Tests\Fixtures\CPT;


use WP_UnitTestCase;
use PinkCrab\Registerables\Post_Type;
use PinkCrab\Loader\Loader;


class Base_CPT_Case extends WP_UnitTestCase {



	/**
	 * Holds instance of the Post_Type object.
	 *
	 * @var Post_Type
	 */
	protected $cpt;

	/**
	 * Selection of generic posts.
	 *
	 * @var array[WP_Post]
	 */
	protected $posts = array();

	/** THE TEST CASES */
	// String of CPT class name
	protected $cpt_type;
	// Array of features expected.
	protected $supports = array();
	// Array of expected settings.
	protected $settings = array();
	// Array of user roels that can create..
	protected $user_access_create = array();
	// Array of user roles that can read
	protected $user_access_view = array();
	// Array of user roles than can delete
	protected $user_access_delete = array();
	// Array of user roles than can edit other peoples posts.
	protected $user_access_edit_others = array();
	// Should this post type create single posts for testing.
	protected $has_single;

	/** THE SETUP */

	public function setUp(): void {
		parent::setup();

		if ( ! $this->cpt ) {
			// Create the CPT and Loader instances.
			$this->cpt = new $this->cpt_type;
			$loader    = new Loader();

			// Run registration.
			$this->cpt->register( $loader );
			$loader->register_hooks();

			// Set the rewrite rules.
			\flush_rewrite_rules();

			// Create 5 random posts.
			if ( $this->has_single ) {
				$this->create_mock_posts();
			}

			// Set the permalinks.
			$this->set_permalink_structure( '/%postname%/' );
		}
	}

	/**
	 * Creates fake posts if needed.
	 *
	 * @return void
	 */
	protected function create_mock_posts(): void {
		$this->posts = array_map(
			function( $e ) {
				return get_post( $e );
			},
			array(
				$this->factory->post->create( array( 'post_type' => $this->cpt->key ) ),
				$this->factory->post->create( array( 'post_type' => $this->cpt->key ) ),
				$this->factory->post->create( array( 'post_type' => $this->cpt->key ) ),
				$this->factory->post->create( array( 'post_type' => $this->cpt->key ) ),
				$this->factory->post->create( array( 'post_type' => $this->cpt->key ) ),
			)
		);
		$this->posts;
	}


	/**
	 * Test that the post type has been created.
	 *
	 * @return void
	 */
	public function test_post_type_exists(): void {
		$this->assertArrayHasKey( $this->cpt->key, get_post_types() );
	}

	/**
	 * test that the key is used for slug in permalinks.
	 *
	 * @return void
	 */
	public function test_permalink_uses_key_for_slug(): void {
		if ( $this->has_single ) {
			foreach ( $this->posts as $post ) {
				$this->assertRegexp( '/' . $this->cpt->slug() . '/', get_the_permalink( $post ) );
			}
		} else {
			$this->assertTrue( true ); // Well it nots giving me a warning ;)
		}
	}

	/**
	 * Check only defined features are supported.
	 *
	 * @return void
	 */
	public function test_post_type_supports(): void {
		foreach ( $this->supports as $feature => $expected ) {
			$this->assertEquals(
				$expected,
				post_type_supports( $this->cpt->key, $feature ),
				sprintf(
					'Failed asserting that %s was %s for %s',
					$feature,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}

	/**
	 * Test that all basic values are set as expected.
	 *
	 * @return void
	 */
	public function test_post_type_settings() {
		foreach ( $this->settings as $property => $expected ) {
			$this->assertEquals(
				$expected,
				get_post_type_object( $this->cpt->key )->{$property},
				sprintf(
					'Failed asserting setting that %s was %s for %s',
					$property,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}

	/**
	 * Test that defined user role access is allowed to create posts.
	 *
	 * @return void
	 */
	public function test_user_role_access_create_posts(): void {
		foreach ( $this->user_access_create as $role => $expected ) {
			wp_set_current_user(
				$this->factory->user->create( array( 'role' => $role ) )
			);
			$this->assertEquals(
				$expected,
				current_user_can(
					get_post_type_object( $this->cpt->key )->cap->create_posts
				),
				sprintf(
					'Failed asserting that role %s could create post. Expected %s for %s',
					$role,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}

	/**
	 * Test that defined user role access is allowed to view posts.
	 *
	 * @return void
	 */
	public function test_user_role_access_view_posts(): void {
		foreach ( $this->user_access_view as $role => $expected ) {

			wp_set_current_user(
				$this->factory->user->create( array( 'role' => $role ) )
			);

			$this->assertEquals(
				$expected,
				current_user_can(
					get_post_type_object( $this->cpt->key )->cap->read
				),
				sprintf(
					'Failed asserting that role %s could view post. Expected %s for %s',
					$role,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}

	/**
	 * Test that defined user role access is allowed to delete posts.
	 *
	 * @return void
	 */
	public function test_user_role_access_delete_posts(): void {
		foreach ( $this->user_access_delete as $role => $expected ) {
			wp_set_current_user(
				$this->factory->user->create( array( 'role' => $role ) )
			);
			$this->assertEquals(
				$expected,
				current_user_can(
					get_post_type_object( $this->cpt->key )->cap->delete_posts
				),
				sprintf(
					'Failed asserting that role %s could delete post. Expected %s for %s',
					$role,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}

	/**
	 * Test that defined user role access is allowed to delete posts.
	 *
	 * @return void
	 */
	public function test_user_role_access_edit_other_users_posts(): void {
		foreach ( $this->user_access_edit_others as $role => $expected ) {
			wp_set_current_user(
				$this->factory->user->create( array( 'role' => $role ) )
			);
			$this->assertEquals(
				$expected,
				current_user_can(
					get_post_type_object( $this->cpt->key )->cap->edit_others_posts
				),
				sprintf(
					'Failed asserting that role %s could edit other users post. Expected %s for %s',
					$role,
					$expected ? 'TRUE' : 'FALSE',
					$this->cpt->key
				)
			);
		}
	}
}
