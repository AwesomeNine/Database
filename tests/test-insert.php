<?php
/**
 * The Array helpers.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

/**
 * TestInsertQuery class.
 */
class InsertQuery extends UnitTestCase {

	/**
	 * MySql grammar tests
	 */
	public function test_instance() {
		$table = $this->create_builder();
		$this->assertInstanceOf( '\Awesome9\Database\Insert', $table );
	}

	/**
	 * MySql grammar tests
	 */
	public function test_insert() {

		// Simple.
		$this->assertQueryTranslation(
			"INSERT INTO phpunit (foo) VALUES ('bar')",
			function( $table ) {
				$table->set( 'foo', 'bar' );
			}
		);

		$this->assertQueryTranslation(
			"INSERT INTO phpunit (foo) VALUES (1)",
			function( $table ) {
				$table->set( 'foo', 1 );
			}
		);

		// Multiple.
		$this->assertQueryTranslation(
			"INSERT INTO phpunit (foo, bar) VALUES ('bar', 'foo')",
			function( $table ) {
				$table
				->set( 'foo', 'bar' )
				->set( 'bar', 'foo' );
			}
		);

		// Array.
		$this->assertQueryTranslation(
			"INSERT INTO phpunit (foo, bar) VALUES ('bar', 'foo')",
			function( $table ) {
				$table->set(
					array(
						'foo' => 'bar',
						'bar' => 'foo',
					)
				);
			}
		);
	}

	/**
	 * Assert SQL Query.
	 *
	 * @param  [type] $expected  [description].
	 * @param  [type] $translate [description].
	 * @param  [type] $callback  [description].
	 */
	protected function assertQueryTranslation( $expected, $callback ) {
		$builder = $this->create_builder();
		call_user_func_array( $callback, array( $builder ) );
		$query = $this->invokeMethod( $builder, 'get_query' );
		$this->assertEquals( $expected, $query );
	}

	/**
	 * [create_builder description]
	 *
	 * @return [type] [description]
	 */
	protected function create_builder() {
		return new \Awesome9\Database\Insert( 'phpunit', 'phpunit' );
	}

	/**
	 * [log description]
	 *
	 * @param  [type] $text [description].
	 */
	protected function log( $text ) {
		fwrite( STDERR, $text );
	}
}
