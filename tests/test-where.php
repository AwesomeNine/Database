<?php
/**
 * The Where query tests.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

use Awesome9\Database\Expression;

/**
 * TestWhereQuery class.
 */
class TestWhereQuery extends UnitTestCase {

	/**
	 * MySql grammar tests
	 */
	public function test_instance() {
		$table = $this->create_builder();
		$this->assertInstanceOf( '\Awesome9\Database\Select', $table );
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

	public function test_simple() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE votes = 100',
			function( $table ) {
				$table->where( 'votes', '=', 100 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE votes = 100',
			function( $table ) {
				$table->where( 'votes', 100 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE votes >= 100',
			function( $table ) {
				$table->where( 'votes', '>=', 100 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE votes <> 100',
			function( $table ) {
				$table->where( 'votes', '<>', 100 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE ( status = 1 AND subscribed <> 1 )',
			function( $table ) {
				$table->where( [
					[ 'status', '=', 1 ],
					[ 'subscribed', '<>', 1 ],
				] );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 2 AND active = 1',
			function( $table ) {
				$table->where( 'id', 2 )
					->where( 'active', 1 );
			}
		);
	}

	public function test_or() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 42 OR active = 1',
			function( $table ) {
				$table->where( 'id', 42 )
					->orWhere( 'active', 1 );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE ( status = 2 AND subscribed <> 1 )',
			function( $table ) {
				$table->orWhere( [
					[ 'status', '=', 2 ],
					[ 'subscribed', '<>', 1 ],
				] );
			}
		);
	}

	public function test_nesting() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 2 OR ( status = 3 AND subscribed <> 1 )',
			function( $table ) {
				$table
					->where( 'id', 2 )
					->orWhere( [
						[ 'status', '=', 3 ],
						[ 'subscribed', '<>', 1 ],
					] );
			}
		);

		$this->assertQueryTranslation(
			"SELECT * FROM wptests_phpunit WHERE votes > 100 AND ( name = 'Abigail' AND votes > 50 )",
			function( $table ) {
				$table
					->where('votes', '>', 100)
					->Where( function( $query ) {
						$query->where( 'name', 'Abigail' )
							  ->where( 'votes', '>', 50 );
					} );
			}
		);

		$this->assertQueryTranslation(
			"SELECT * FROM wptests_phpunit WHERE votes > 100 AND ( name = 'Abigail' OR votes > 50 )",
			function( $table ) {
				$table
					->where('votes', '>', 100)
					->Where( function( $query ) {
						$query->where( 'name', 'Abigail' )
							  ->orWhere( 'votes', '>', 50 );
					} );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE a = 1 OR ( a > 10 AND a < 20 ) AND c = 30',
			function( $table ) {
				$table->where( 'a', 1 )
					->orWhere( array(
						array( 'a', '>', 10 ),
						array( 'a', '<', 20 ),
					) )
					->where( 'c', 30 );
			}
		);
	}

	public function test_in_not_in() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id IN (23, 25, 30)',
			function( $table ) {
				$table->whereIn( 'id', array( 23, 25, 30 ) );
			}
		);

		$this->assertQueryTranslation(
			"SELECT * FROM wptests_phpunit WHERE skills IN ('php', 'javascript', 'ruby')",
			function( $table ) {
				$table->whereIn( 'skills', array( 'php', 'javascript', 'ruby' ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id NOT IN (23, 25, 30)',
			function( $table ) {
				$table->whereNotIn( 'id', array( 23, 25, 30 ) );
			}
		);

	}

	public function test_between() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id BETWEEN 10 AND 100',
			function( $table ) {
				$table->whereBetween( 'id', array( 10, 100 ) );
			}
		);

		$this->assertQueryTranslation(
			"SELECT * FROM wptests_phpunit WHERE dates BETWEEN '10-04-2018' AND '10-09-2018'",
			function( $table ) {
				$table->whereBetween( 'dates', array( '10-04-2018', '10-09-2018' ) );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id NOT BETWEEN 10 AND 100',
			function( $table ) {
				$table->whereNotBetween( 'id', array( 10, 100 ) );
			}
		);
	}

	public function test_between_with_expression() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE allowed_to_export = 0 AND created_at BETWEEN (CURDATE() - INTERVAL 1 MONTH ) AND CURDATE()',
			function( $table ) {
				$table
					->where( 'allowed_to_export', 0 )
					->whereBetween( 'created_at', [ new Expression( '(CURDATE() - INTERVAL 1 MONTH )' ), new Expression( 'CURDATE()' ) ] );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_null_not_null() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE name IS NULL',
			function( $table ) {
				$table->whereNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 5 AND name IS NULL',
			function( $table ) {
				$table
					->where( 'id', 5 )
					->whereNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 5 AND name IS NOT NULL',
			function( $table ) {
				$table
					->where( 'id', 5 )
					->whereNotNull( 'name' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id = 5 OR name IS NOT NULL',
			function( $table ) {
				$table
					->where( 'id', 5 )
					->orWhereNotNull( 'name' );
			}
		);
	}

	public function test_where_sub_query() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit WHERE id IN (SELECT user_id FROM wptests_users WHERE active = 1)',
			function( $table ) {
				$table
					->whereIn(
						'id',
						function( $query ) {
							$query
								->select( 'user_id' )
								->from( 'users' )
								->where( 'active', 1 );
						}
					);
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
