<?php
/**
 * The Orderby query tests.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

/**
 * TestOrderbyQuery class.
 */
class TestOrderbyQuery extends UnitTestCase {

	/**
	 * MySql grammar tests
	 */
	public function test_instance() {
		$table = $this->create_builder();
		$this->assertInstanceOf( '\Awesome9\Database\SELECT', $table );
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
	 * MySql grammar tests
	 */
	public function test_orderby() {

		// Simple.
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit ORDER BY id ASC',
			function( $table ) {
				$table->orderBy( 'id' );
			}
		);

		// Other direction.
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit ORDER BY id DESC',
			function( $table ) {
				$table->orderBy( 'id', 'desc' );
			}
		);

		// More keys comma separated.
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit ORDER BY firstname DESC, lastname DESC',
			function( $table ) {
				$table->orderBy( 'firstname, lastname', 'desc' );
			}
		);

		// Multipe sortings diffrent direction.
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit ORDER BY firstname ASC, lastname DESC',
			function( $table ) {
				$table->orderBy( array(
					'firstname' => 'asc',
				'lastname'  => 'desc',
			) );
		});

		// Raw sorting.
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit ORDER BY firstname <> nick',
			function( $table ) {
				$table->orderBy( 'firstname <> nick', null );
			}
		);
	}

	/**
	 * [create_builder description]
	 *
	 * @return [type] [description]
	 */
	protected function create_builder() {
		return new \Awesome9\Database\Select( 'phpunit' );
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
