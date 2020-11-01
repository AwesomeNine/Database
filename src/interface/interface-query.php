<?php
/**
 * The Query interface.
 *
 * @since   1.0.0
 * @package Awesome9\Database\Interface
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Interface;

/**
 * Query class.
 */
interface Query {

	/**
	 * Translate the current query to a SQL statement.
	 *
	 * @return string
	 */
	public function get_query();

	/**
	 * Execute query.
	 *
	 * @return string
	 */
	public function execute();
}
