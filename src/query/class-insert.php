<?php
/**
 * The Insert
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

/**
 * Insert class.
 */
class Insert extends Query {

	/**
	 * Set values for insert/update
	 *
	 * @param string|array $name  Key of pair.
	 * @param string|array $value Value of pair.
	 *
	 * @return self The current query builder.
	 */
	public function set( $name, $value = null ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $key => $val ) {
				$this->values[ $key ] = $this->esc_value( $val );
			}
		} else {
			$this->values[ $name ] = $this->esc_value( $value );
		}

		return $this;
	}

	/**
	 * Translate the current query to a SQL insert statement
	 *
	 * @return string
	 */
	public function translate() {
		$build = [ 'INSERT INTO ' . $this->get_table() ];

		$build[] = '(' . join( ', ', array_keys( $this->values ) ) . ')';
		$build[] = 'VALUES';
		$build[] = '(' . join( ', ', $this->values ) . ')';

		return join( ' ', $build );
	}
}
