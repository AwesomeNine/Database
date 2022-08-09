<?php
/**
 * DB Expression
 *
 * Value holder to let system identify not to escaped the given string.
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

class Expression {

	/**
	 * The value holder
	 *
	 * @var string|int
	 */
	protected $value = null;

	/**
	 * The constructor that assigns our value
	 *
	 * @param string|int $value Value to hold.
	 * @return void
	 */
	public function __construct( $value ) {
		$this->value = $value;
	}

	/**
	 * Return the expressions value
	 *
	 * @return string|int
	 */
	public function value() {
		return $this->value;
	}

	/**
	 * To string magic returns the expression value
	 */
	public function __toString() {
		return $this->value();
	}
}
