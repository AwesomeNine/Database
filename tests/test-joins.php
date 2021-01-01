<?php
/**
 * The Joins tests.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

/**
 * TestJoinsQuery class.
 */
class TestJoinsQuery extends UnitTestCase {

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

	public function test_join_simple() {

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_posts JOIN wptests_postmeta ON wptests_posts.ID = wptests_postmeta.ID',
			function( $table ) {
				$table->select()
					->join( 'postmeta', 'ID' );
			}
		);
	}

	public function test_join_with_subquery() {
		$this->assertQueryTranslation(
			'SELECT * FROM wptests_posts JOIN ( SELECT post_id FROM wptests_product_lookup WHERE active = 1 ) AS plk ON wptests_posts.ID = plk.ID',
			function( $table ) {
				$table->select()
					->join(
						function( $query ) {
							$query
								->select( 'post_id' )
								->from( 'product_lookup' )
								->where( 'active', 1 );
						},
						'ID',
						null,
						'plk'
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
		return new \Awesome9\Database\Select( 'posts', 'posts' );
	}
}
