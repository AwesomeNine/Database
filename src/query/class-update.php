<?php
/**
 * The Update
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

/**
 * Update class.
 */
class Update extends WhereQuery {

	/**
	 * Translate the current query to a SQL update statement
	 *
	 * @return string
	 */
	public function translate() {
		$build = [ 'UPDATE ' . $this->get_table() . ' SET' ];

		if ( ! empty( $this->values ) ) {
			$build[] = join( ', ', $this->values );
		}

		// Build the where statements.
		if ( ! empty( $this->wheres ) ) {
			$build[] = $this->get_where_clauses();
		}

		// Build offset and limit.
		if ( ! empty( $this->limit ) ) {
			$build[] = $this->limit;
		}

		return join( ' ', $build );
	}
}
