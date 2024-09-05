<?php
/**
 * The WordPress DB Processor
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
	public $last_query = '';

	/**
	 * Prefix table.
	 *
	 * @param  string $table Table.
	 * @return string
	 */
	public function wrap_table( $table ) {
		global $wpdb;

		return $wpdb->prefix . $table;
	}

	/**
	 * Execute given query and return the results.
	 *
	 * @param  string $query  Database query.
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function get( $query, $output = \OBJECT ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->get_results( $this->last_query, $output ); // phpcs:ignore
	}

	/**
	 * Execute given query and return the one row.
	 *
	 * @param  string $query  Database query.
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function get_row( $query, $output = \OBJECT ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->get_row( $this->last_query, $output ); // phpcs:ignore
	}

	/**
	 * Execute given query and return one variable from the database.
	 *
	 * @param  string $query Database query.
	 * @return mixed
	 */
	public function get_var( $query ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->get_var( $query ); // phpcs:ignore
	}

	/**
	 * Perform a MySQL database query, using current database connection.
	 *
	 * @see wpdb::query
	 *
	 * @param  string $query Database query.
	 * @return int|false Number of rows affected|selected or false on error.
	 */
	public function query( $query ) {
		global $wpdb;

		$this->last_query = $query;
		return $wpdb->query( $query ); // phpcs:ignore
	}

	/**
	 * Get insert id.
	 *
	 * @return int
	 */
	public function get_insert_id() {
		global $wpdb;

		return $wpdb->insert_id;
	}
}
