<?php
/**
 * The Delete
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

use Awesome9\Database\Query\Where;
use Awesome9\Database\Interface\Query;

/**
 * Delete class.
 */
class Delete extends Where implements Query {

	/**
	 * Translate the current query to a SQL delete statement
	 *
	 * @return string
	 */
	public function get_query() {
		$build = [ 'DELETE FROM ' . $this->get_table() ];

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

	/**
	 * Execute query.
	 *
	 * @return mixed
	 */
	public function execute() {
		return $this->processor->query( $this->get_query() );
	}
}
