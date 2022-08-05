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
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @param  string $name  A query unique id.
 * @return Query Query instance.
 */
function select( $table, $alias = '', $name = '' ) {
	return new Select( $table, $alias, $name );
}

/**
 * Retrieve a insert query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @param  string $name  A query unique id.
 * @return Query Query instance.
 */
function insert( $table, $alias = '', $name = '' ) {
	return new Insert( $table, $alias, $name );
}

/**
 * Retrieve a update query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @param  string $name  A query unique id.
 * @return Query Query instance.
 */
function update( $table, $alias = '', $name = '' ) {
	return new Update( $table, $alias, $name );
}

/**
 * Retrieve a delete query instance with table name.
 *
 * @since  1.0.0
 *
 * @param  string $table The table to run query against.
 * @param  string $alias The table alias.
 * @param  string $name  A query unique id.
 * @return Query Query instance.
 */
function delete( $table, $alias = '', $name = '' ) {
	return new Delete( $table, $alias, $name );
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
