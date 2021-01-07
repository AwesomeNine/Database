<?php
/**
 * The Grouby grammer
 *
 * @since   1.0.0
 * @package Awesome9\Database\Query
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Query;

/**
 * Groupby class.
 */
class Groupby extends Orderby {

	/**
	 * Group by statements
	 *
	 * @var array
	 */
	protected $groups = array();

	/**
	 * Having statements
	 *
	 * @var array
	 */
	protected $having = array();

	/**
	 * Add an group by statement to the current query.
	 *
	 *     ->groupBy('created_at')
	 *
	 * @param  array|string $columns Columns.
	 * @return Query The current query.
	 */
	public function groupBy( $columns ) { // @codingStandardsIgnoreLine
		if ( is_string( $columns ) ) {
			$columns = $this->argument_to_array( $columns );
		}

		$this->groups = $this->groups + $columns;

		return $this;
	}

	/**
	 * Generate Having clause.
	 *
	 * @param  string $column   The SQL column.
	 * @param  string $operator Operator or value depending if $value is not set.
	 * @param  mixed  $value    The value if $operator is set.
	 * @return Query The current query.
	 */
	public function having( $column, $operator = null, $value = null ) {
		$this->having = $this->generate_where( $column, $operator, $value, 'HAVING' );

		return $this;
	}

	/**
	 * Reset query.
	 */
	public function reset() {
		parent::reset();
		$this->groups = array();
		$this->having = array();
	}
}
