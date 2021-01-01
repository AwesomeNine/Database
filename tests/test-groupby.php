<?php
/**
 * The Groupby query tests.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

/**
 * TestGroupByQuery class.
 */
class TestGroupByQuery extends UnitTestCase {

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
	public function test_groupby() {
		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS incoming, target_post_id AS post_id FROM wptests_phpunit WHERE target_post_id IN (100, 120, 123) GROUP BY target_post_id',
			function( $table ) {
				$table
					->selectCount( 'id', 'incoming' )
					->select( 'target_post_id AS post_id' )
					->whereIn( 'target_post_id', array( 100, 120, 123 ) )
					->groupBy( 'target_post_id' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS incoming, target_post_id AS post_id FROM wptests_phpunit WHERE target_post_id IN (100, 120, 123) GROUP BY target_post_id HAVING COUNT(id) > 25',
			function( $table ) {
				$table->selectCount( 'id', 'incoming' )->select( 'target_post_id AS post_id' )
					->whereIn( 'target_post_id', array( 100, 120, 123 ) )
					->groupBy( 'target_post_id' )
					->having( 'COUNT(id)', '>', 25 );
			}
		);
	}

	/**
	 * [create_builder description]
	 *
	 * @return [type] [description]
	 */
	protected function create_builder() {
		return new \Awesome9\Database\Select( 'phpunit', 'phpunit' );
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
