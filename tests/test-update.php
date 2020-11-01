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
 * TestUpdateQuery class.
 */
class UpdateQuery extends UnitTestCase {

	/**
	 * MySql grammar tests
	 */
	public function test_instance() {
		$table = $this->create_builder();
		$this->assertInstanceOf( '\Awesome9\Database\Update', $table );
	}

	/**
	 * MySql grammar tests
	 */
	public function test_update() {

		// Simple.
		$this->assertQueryTranslation(
			"UPDATE wptests_phpunit SET foo = 'bar'",
			function( $table ) {
				$table->set( 'foo', 'bar' );
			}
		);

		// Multiple.
		$this->assertQueryTranslation(
			"UPDATE wptests_phpunit SET foo = 'bar', bar = 'foo'",
			function( $table ) {
				$table
				->set( 'foo', 'bar' )
				->set( 'bar', 'foo' );
			}
		);

		// Array.
		$this->assertQueryTranslation(
			"UPDATE wptests_phpunit SET foo = 'bar', bar = 'foo'",
			function( $table ) {
				$table->set(
					array(
						'foo' => 'bar',
						'bar' => 'foo',
					)
				);
			}
		);

		// With where and limit.
		$this->assertQueryTranslation(
			"UPDATE wptests_phpunit SET foo = 'bar', bar = 'foo' WHERE id = 1 LIMIT 0, 1",
			function( $table ) {
				$table
					->set( 'foo', 'bar' )
					->set( 'bar', 'foo' )
					->where( 'id', 1 )
					->limit( 1 );
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
		return new \Awesome9\Database\Update( 'phpunit', 'phpunit' );
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
