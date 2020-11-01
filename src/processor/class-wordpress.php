<?php
/**
 * The WordPress DB Processor.
 *
 * @since   1.0.0
 * @package Awesome9\Database\Processor
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Processor;

use Awesome9\Database\Interfaces\Processor;

/**
 * WordPress class.
 */
class WordPress implements Processor {

	/**
	 * Last executed query.
	 *
	 * @var string
	 */
	private $last_query = '';

	/**
	 * Wrap table.
	 *
	 * @param  string $table table.
	 * @return string
	 */
	public function wrap_table( $table ) {
		global $wpdb;

		return $wpdb->prefix . $table;
	}

	/**
	 * Translate the given query object and return the results
	 *
	 * @param  string $query Database query.
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function get( $query, $output = \OBJECT ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->get_results( $this->last_query, $output ); // phpcs:ignore
	}

	/**
	 * Translate the given query object and return the results
	 *
	 * @param  string $query Database query.
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function one( $query, $output = \OBJECT ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->get_row( $this->last_query, $output ); // phpcs:ignore
	}

	/**
	 * Translate the given query object and return one variable from the database
	 *
	 * @param  string $query Database query.
	 * @return mixed
	 */
	public function var( $query ) {
		$row = $this->one( $query, \ARRAY_A );
		return is_null( $row ) ? false : current( $row );
	}

	/**
	 * Perform a MySQL database query, using current database connection.
	 *
	 * @see wpdb::query
	 *
	 * @param string $query Database query.
	 * @return int|false Number of rows affected|selected or false on error.
	 */
	public function query( $query ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->query( $query ); // phpcs:ignore
	}
}
