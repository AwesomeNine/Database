<?php
/**
 * The Joins grammer
 *
 * @since   1.0.0
 * @package Awesome9\Database\Query
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Query;

use Closure;
use Awesome9\Database\Select;

/**
 * Joins class.
 */
class Joins extends Groupby {

	/**
	 * Joins statements
	 *
	 * @var array
	 */
	protected $joins = array();

	/**
	 * Add an group by statement to the current query.
	 *
	 *     ->join( 'created_at' )
	 *
	 * @param  string $table    Foreign table.
	 * @param  string $primary  Primary table column.
	 * @param  string $foreign  Foreign table column.
	 * @param  string $alias    Foreign table alias.
	 * @param  string $operator Comparison operator.
	 * @return Query The current query.
	 */
	public function join( $table, $primary, $foreign = null, $alias = null, $operator = '=' ) { // @codingStandardsIgnoreLine
		// If the table is a Closure, it means the developer is performing an entire
		// sub-select within the query and we will need to compile the sub-select
		// within the join clause to get the appropriate query record results.
		if ( $table instanceof Closure ) {
			if ( is_null( $alias ) ) {
				$alias = uniqid( 'join_' );
			}

			$foreign_table = $this->join_sub( $table ) . ' AS ' . $alias;
		} else {
			$table         = $this->processor->wrap_table( $table );
			$foreign_table = $this->wrap_alias( $table, $alias );

			if ( is_null( $alias ) ) {
				$alias = $table;
			}
		}

		// If foreign key not defined means it is same as primary key.
		if ( is_null( $foreign ) ) {
			$foreign = $primary;
		}

		$this->joins[] = sprintf(
			'JOIN %1$s ON %2$s.%3$s %4$s %5$s.%6$s',
			$foreign_table,
			$this->get_table(),
			$primary,
			$operator,
			$alias,
			$foreign
		);

		return $this;
	}

	/**
	 * Reset query.
	 */
	public function reset() {
		parent::reset();
		$this->joins = array();
	}

	/**
	 * Generate joins sub-query.
	 *
	 * @param  Closure $callback Callback.
	 * @return Query The current query.
	 */
	private function join_sub( Closure $callback ) {
		$query = new Select( uniqid( 'nested-' ), '', '' );
		call_user_func( $callback, $query );

		return '( ' . $query->get_query() . ' )';
	}
}
