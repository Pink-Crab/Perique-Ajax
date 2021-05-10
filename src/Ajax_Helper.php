<?php

declare(strict_types=1);

/**
 * Helper class for working with Ajax models
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Ajax
 */

namespace PinkCrab\Ajax;

use PinkCrab\Ajax\Ajax;
use PinkCrab\Nonce\Nonce;
use PinkCrab\Ajax\Ajax_Exception;


use ReflectionClass;


class Ajax_Helper {

	/**
	 * Cache of all reflected ajax class, constructed.
	 *
	 * @var array<string,Ajax>
	 */
	private static $class_cache = array();

	/**
	 * Returns the reflection of an Ajax instance.
	 * Either from cache or created without constructor.
	 *
	 * @param string $class
	 * @return Ajax
	 * @throws Ajax_Exception (code 1) If none valid Ajax class passed.
	 */
	private static function get_reflected( string $class ): Ajax {
		if ( ! \is_subclass_of( $class, Ajax::class ) ) {
			throw Ajax_Exception::none_ajax_model( 'get reflection' );
		}

		if ( ! array_key_exists( $class, self::$class_cache ) ) {
			$reflection                  = new ReflectionClass( $class );
			self::$class_cache[ $class ] = $reflection->newInstanceWithoutConstructor();
		}

		return self::$class_cache[ $class ];
	}

	/**
	 * Gets the action from an Ajax class
	 * uses reflection to create instance without using the constructor.
	 *
	 * @param string $class
	 * @return string|null
	 * @throws Ajax_Exception (code 2) If no action defined
	 */
	public static function get_action( string $class ):? string {
		$instance = self::get_reflected( $class );

		if ( ! $instance->has_valid_action() ) {
			throw Ajax_Exception::undefined_action( $class );
		}

		return $instance->get_action();
	}

	/**
	 * Returns if the passed ajax class  has a nonce
	 *
	 * @param string $class
	 * @return boolean
	 * @throws Ajax_Exception (code 1) If none valid Ajax class passed.
	 */
	public static function has_nonce( string $class ): bool {
		return self::get_reflected( $class )->has_nonce();
	}

	/**
	 * Returns a Nonce object if the passed class has a none handle defined.
	 *
	 * @param string $class
	 * @return Nonce|null
	 * @throws Ajax_Exception (code 1) If none valid Ajax class passed.
	 */
	public static function get_nonce( string $class ): ?Nonce {
		$instance = self::get_reflected( $class );

		return $instance->has_nonce()
			? new Nonce( $instance->get_nonce_handle() )
			: null;
	}

	/**
	 * Return the defined nonce field from the Ajax class passed
	 *
	 * @param string $class
	 * @return string
	 * @throws Ajax_Exception (code 1) If none valid Ajax class passed.
	 */
	public static function get_nonce_field( string $class ): string {
		return self::get_reflected( $class )->get_nonce_field();
	}
}
