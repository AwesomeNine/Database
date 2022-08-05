<?php
/**
 * The Select query tests.
 *
 * @since   1.0.0
 * @package Awesome9\Tests\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Tests\Database;

/**
 * TestSelectQuery class.
 */
class TestSelectQuery extends UnitTestCase {

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

	public function test_get_data() {
		$builder = new \Awesome9\Database\Select( 'users' );
		$data = $builder->select()->execute();
		$this->assertNotEmpty( $data );

		$count = $builder->selectCount( 'ID' )->var();
		$this->assertEquals( 1, $count );
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_simple() {

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit',
			function( $table ) {}
		);

		$this->assertQueryTranslation(
			'SELECT * FROM wptests_phpunit',
			function( $table ) {
				$table->select();
			}
		);

		$this->assertQueryTranslation(
			'SELECT DISTINCT * FROM wptests_phpunit',
			function( $table ) {
				$table->distinct();
			}
		);

		$this->assertQueryTranslation(
			'SELECT SQL_CALC_FOUND_ROWS * FROM wptests_phpunit',
			function( $table ) {
				$table->found_rows();
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_fields() {

		$this->assertQueryTranslation(
			'SELECT id FROM wptests_phpunit',
			function( $table ) {
				$table->select( 'id' );
			}
		);

		// Comma seperated fields.
		$this->assertQueryTranslation(
			'SELECT id, foo FROM wptests_phpunit',
			function( $table ) {
				$table->select( 'id, foo' );
			}
		);

		// With array.
		$this->assertQueryTranslation(
			'SELECT id, foo FROM wptests_phpunit',
			function( $table ) {
				$table->select( [ 'id', 'foo' ] );
			}
		);

		// With alias as string.
		$this->assertQueryTranslation(
			'SELECT id, foo AS f FROM wptests_phpunit',
			function( $table ) {
				$table->select( 'id, foo AS f' );
			}
		);

		// With array with alias.
		$this->assertQueryTranslation(
			'SELECT id AS d, foo AS f FROM wptests_phpunit',
			function( $table ) {
				$table->select(
					[
						'id'  => 'd',
						'foo' => 'f',
					]
				);
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_count() {
		$this->assertQueryTranslation(
			'SELECT COUNT(*), foo AS f FROM wptests_phpunit',
			function( $table ) {
				$table->selectCount()->select( 'foo AS f' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT COUNT(id) AS count FROM wptests_phpunit',
			function( $table ) {
				$table->selectCount( 'id', 'count' );
			}
		);
	}

	/**
	 * MySql grammar tests
	 */
	public function test_select_others() {
		$this->assertQueryTranslation(
			'SELECT SUM(id) AS count FROM wptests_phpunit',
			function( $table ) {
				$table->selectSum( 'id', 'count' );
			}
		);

		$this->assertQueryTranslation(
			'SELECT AVG(id) AS average FROM wptests_phpunit',
			function( $table ) {
				$table->selectAvg( 'id', 'average' );
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
