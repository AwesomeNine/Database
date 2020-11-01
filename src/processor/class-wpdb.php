<?php
/**
 * The WordPress DB Processor.
 *
 * @since   1.0.0
 * @package Awesome9\Database\Processor
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Processor;

use Awesome9\Database\Processor;

/**
 * WordPress class.
 */
class WordPress implements Processor {

	/**
	 * Translate the given query object and return the results
	 *
	 * @param string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 *
	 * @return mixed
	 */
	public function get( $output = \OBJECT ) {
		global $wpdb;

		$this->last_query = $this->translate();
		$this->reset();

		return $wpdb->get_results( $this->last_query, $output );
	}

	/**
	 * Translate the given query object and return the results
	 *
	 * @param string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 *
	 * @return mixed
	 */
	public function one( $output = \OBJECT ) {
		global $wpdb;

		$this->limit( 1 );
		$this->last_query = $this->translate();
		$this->reset();

		return $wpdb->get_row( $this->last_query, $output );
	}

	/**
	 * Translate the given query object and return one variable from the database
	 *
	 * @return mixed
	 */
	public function var() {
		$row = $this->one( \ARRAY_A );

		return is_null( $row ) ? false : current( $row );
	}

	/**
	 * Insert a row into table.
	 *
	 * @return mixed
	 */
	public function insert() {
		$this->last_query = $this->translate();
		$this->reset();

		return $this->query( $this->last_query );
	}

	/**
	 * Update a row into table.
	 *
	 * @return mixed
	 */
	public function update() {
		$this->last_query = $this->translate();
		$this->reset();

		return $this->query( $this->last_query );
	}

	/**
	 * Get found rows.
	 *
	 * @return int
	 */
	public function found_rows() {
		global $wpdb;

		return $wpdb->get_var( 'SELECT FOUND_ROWS();' );
	}

	/**
	 * Perform a MySQL database query, using current database connection.
	 *
	 * @see wpdb::query
	 *
	 * @param string $query Database query.
	 *
	 * @return int|false Number of rows affected|selected or false on error.
	 */
	public function query( $query ) {
		global $wpdb;
		return $wpdb->query( $query );
	}
}
