<?php
/**
 * Database functions
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

/**
 * Retrieve a select query instance with table name.
 *
 * @since  1.0.0
 *
 * @param string $table   The table to run query against.
 * @param string $alias   (Optional) The table alias.
 * @param array  $options (Optional) Query options.
 *
 * @return Query Query instance.
 */
function select( $table, $alias = '', $options = [] ) {
	return new Select( $table, $alias, $options );
}

/**
 * Retrieve a insert query instance with table name.
 *
 * @since  1.0.0
 *
 * @param string $table   The table to run query against.
 * @param string $alias   (Optional) The table alias.
 * @param array  $options (Optional) Query options.
 *
 * @return Query Query instance.
 */
function insert( $table, $alias = '', $options = [] ) {
	return new Insert( $table, $alias, $options );
}

/**
 * Retrieve a update query instance with table name.
 *
 * @since  1.0.0
 *
 * @param string $table   The table to run query against.
 * @param string $alias   (Optional) The table alias.
 * @param array  $options (Optional) Query options.
 *
 * @return Query Query instance.
 */
function update( $table, $alias = '', $options = [] ) {
	return new Update( $table, $alias, $options );
}

/**
 * Retrieve a delete query instance with table name.
 *
 * @since  1.0.0
 *
 * @param string $table   The table to run query against.
 * @param string $alias   (Optional) The table alias.
 * @param array  $options (Optional) Query options.
 *
 * @return Query Query instance.
 */
function delete( $table, $alias = '', $options = [] ) {
	return new Delete( $table, $alias, $options );
}

/**
 * Truncate table.
 *
 * @param string $table The table to truncate.
 *
 * @return mixed
 */
function truncate( $table ) {
	global $wpdb;

	$table = $wpdb->prefix . $table;

	return $wpdb->query( "TRUNCATE TABLE {$table};" ); // phpcs:ignore
}

/**
 * Drop table.
 *
 * @param string $table The table to drop.
 *
 * @return mixed
 */
function drop( $table ) {
	global $wpdb;

	$table = $wpdb->prefix . $table;

	return $wpdb->query( "DROP TABLE {$table};" ); // phpcs:ignore
}
