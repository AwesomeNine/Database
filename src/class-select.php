<?php
/**
 * The Select
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

use Awesome9\Database\Interfaces\Query;
use Awesome9\Database\Query\Select as SelectBase;

/**
 * Select class.
 */
class Select extends SelectBase implements Query {

	/**
	 * Make a distinct selection
	 *
	 * @var bool
	 */
	private $distinct = false;

	/**
	 * Make SQL_CALC_FOUND_ROWS in selection
	 *
	 * @var bool
	 */
	private $found_rows = false;

	/**
	 * Select parts.
	 *
	 * @var array
	 */
	protected $select = [];

	/**
	 * Distinct select setter
	 *
	 * @param bool $distinct Is disticnt.
	 *
	 * @return self The current query builder.
	 */
	public function distinct( $distinct = true ) {
		$this->distinct = $distinct;

		return $this;
	}

	/**
	 * SQL_CALC_FOUND_ROWS select setter
	 *
	 * @param bool $found_rows Should get found rows.
	 *
	 * @return self The current query builder.
	 */
	public function found_rows( $found_rows = true ) {
		$this->found_rows = $found_rows;

		return $this;
	}

	/**
	 * Get found rows.
	 *
	 * @return int
	 */
	public function get_found_rows() {
		return $this->processor->var( 'SELECT FOUND_ROWS();' );
	}

	/**
	 * Translate the current query to a SQL select statement
	 *
	 * @return string
	 */
	public function get_query() {
		$build = array( 'SELECT' );

		if ( $this->found_rows ) {
			$build[] = 'SQL_CALC_FOUND_ROWS';
		}
		if ( $this->distinct ) {
			$build[] = 'DISTINCT';
		}

		// Build the selected fields.
		$build[] = ! empty( $this->select ) && is_array( $this->select )
			? join( ', ', $this->select ) : '*';

		// Append the table.
		$build[] = 'FROM ' . $this->get_table();

		// Build the where statements.
		if ( ! empty( $this->wheres ) ) {
			$build[] = $this->get_where_clauses();
		}

		// Build the group by statements.
		if ( ! empty( $this->groups ) ) {
			$build[] = 'GROUP BY ' . join( ', ', $this->groups );

			if ( ! empty( $this->having ) ) {
				$build[] = $this->having;
			}
		}

		// Build the order statement.
		if ( ! empty( $this->orders ) ) {
			$build[] = $this->get_order_clauses();
		}

		// Build offset and limit.
		if ( ! empty( $this->limit ) ) {
			$build[] = $this->limit;
		}

		return join( ' ', $build );
	}

	/**
	 * Set the selected fields
	 *
	 * @param array $fields Fields to select.
	 *
	 * @return self The current query builder.
	 */
	public function select( $fields = '' ) {
		if ( empty( $fields ) ) {
			return $this;
		}

		if ( is_string( $fields ) ) {
			$this->select[] = $fields;
			return $this;
		}

		foreach ( $fields as $key => $field ) {
			$this->select[] = is_string( $key )
				? $this->wrap_alias( $key, $field )
				: $field;
		}

		return $this;
	}

	/**
	 * Shortcut to add a count function
	 *
	 *     ->selectCount('id')
	 *     ->selectCount('id', 'count')
	 *
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	public function selectCount( $field = '*', $alias = null ) { // @codingStandardsIgnoreLine
		return $this->selectFunc( 'COUNT', $field, $alias );
	}

	/**
	 * Shortcut to add a sum function
	 *
	 *     ->selectSum('id')
	 *     ->selectSum('id', 'total')
	 *
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	public function selectSum( $field, $alias = null ) { // @codingStandardsIgnoreLine
		return $this->selectFunc( 'SUM', $field, $alias );
	}

	/**
	 * Shortcut to add a avg function
	 *
	 *     ->selectAvg('id')
	 *     ->selectAvg('id', 'average')
	 *
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	public function selectAvg( $field, $alias = null ) { // @codingStandardsIgnoreLine
		return $this->selectFunc( 'AVG', $field, $alias );
	}

	/**
	 * Shortcut to add a max function
	 *
	 *     ->selectMax('id')
	 *     ->selectMax('id', 'average')
	 *
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	public function selectMax( $field, $alias = null ) { // @codingStandardsIgnoreLine
		return $this->selectFunc( 'MAX', $field, $alias );
	}

	/**
	 * Shortcut to add a min function
	 *
	 *     ->selectMin('id')
	 *     ->selectMin('id', 'average')
	 *
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	public function selectMin( $field, $alias = null ) { // @codingStandardsIgnoreLine
		return $this->selectFunc( 'MIN', $field, $alias );
	}

	/**
	 * Shortcut to add a function
	 *
	 * @param string $func  Function name.
	 * @param string $field Column name.
	 * @param string $alias (Optional) Column alias.
	 *
	 * @return self The current query builder.
	 */
	private function selectFunc( $func, $field, $alias = null ) { // @codingStandardsIgnoreLine
		$this->select[] = $this->wrap_alias( "$func({$field})", $alias );

		return $this;
	}
}
