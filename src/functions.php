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
 * @param  string $name  A query unique id.
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @return Query Query instance.
 */
function select( $name, $table, $alias = '' ) {
	return new Select( $name, $table, $alias );
}

/**
 * Retrieve a insert query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $name  A query unique id.
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @return Query Query instance.
 */
function insert( $name, $table, $alias = '' ) {
	return new Insert( $name, $table, $alias );
}

/**
 * Retrieve a update query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $name  A query unique id.
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @return Query Query instance.
 */
function update( $name, $table, $alias = '' ) {
	return new Update( $name, $table, $alias );
}

/**
 * Retrieve a delete query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $name  A query unique id.
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @return Query Query instance.
 */
function delete( $name, $table, $alias = '' ) {
	return new Delete( $name, $table, $alias );
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
