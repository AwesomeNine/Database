<?php
/**
 * The Query base calss
 *
 * @since   1.0.0
 * @package Awesome9\Database\Query
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database\Query;

use Awesome9\Database\Processor\WordPress;

/**
 * Base class.
 */
class Base {

	/**
	 * Table name.
	 *
	 * @var string
	 */
	protected $table = '';

	/**
	 * Query unique id.
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * Table alias.
	 *
	 * @var string
	 */
	protected $alias = '';

	/**
	 * Values for update/insert.
	 *
	 * @var array
	 */
	protected $values = [];

	/**
	 * The query limit.
	 *
	 * @var int
	 */
	protected $limit = null;

	/**
	 * Database processor.
	 *
	 * @var Processor
	 */
	protected $processor = null;

	/**
	 * The Constructor.
	 *
	 * @param  string $table The table to run query against.
	 * @param  string $alias The table alias.
	 * @param  string $name  A query unique id for caching results.
	 * @return Query The current query.
	 */
	public function __construct( $table, $alias = '', $name = '' ) {
		$this->table     = $table;
		$this->name      = $name;
		$this->alias     = $alias;
		$this->processor = new WordPress();

		return $this;
	}

	/**
	 * From table.
	 *
	 * @param  string $table The table to run query against.
	 * @param  string $alias The table alias.
	 * @return Query The current query.
	 */
	public function from( $table, $alias = '' ) {
		$this->table = $table;
		$this->alias = $alias;

		return $this;
	}

	/**
	 * Get table name.
	 *
	 * @return string
	 */
	public function get_table() {
		return $this->wrap_alias(
			$this->processor->wrap_table( $this->table ),
			$this->alias
		);
	}

	/**
	 * Execute query.
	 *
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function execute( $output = \ARRAY_N ) {
		return 'SELECT' === static::TYPE
			? $this->processor->get( $this->get_query(), $output )
			: $this->processor->query( $this->get_query() );
	}

	/**
	 * Set values for insert/update
	 *
	 * @param  string|array $name  Key of pair.
	 * @param  string|array $value Value of pair.
	 * @return Query The current query.
	 */
	public function set( $name, $value = null ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $key => $val ) {
				$this->values[ $key ] = $this->wrap_value( $key, $this->esc_value( $val ) );
			}
		} else {
			$this->values[ $name ] = $this->wrap_value( $name, $this->esc_value( $value ) );
		}

		return $this;
	}

	/**
	 * Set the limit clause.
	 *
	 * @param  int $limit  Limit size.
	 * @param  int $offset Offeset.
	 * @return Query The current query.
	 */
	public function limit( $limit, $offset = 0 ) {
		global $wpdb;
		$limit  = \absint( $limit );
		$offset = \absint( $offset );

		$this->limit = $wpdb->prepare( 'LIMIT %d, %d', $offset, $limit );

		return $this;
	}

	/**
	 * Create an query limit based on a page and a page size.
	 *
	 * @param  int $page Page number.
	 * @param  int $size Page size.
	 * @return Query The current query.
	 */
	public function page( $page, $size = 25 ) {
		$size   = \absint( $size );
		$offset = $size * \absint( $page );

		$this->limit( $size, $offset );

		return $this;
	}

	/**
	 * Escape array values for sql.
	 *
	 * @param  array $arr Array to escape.
	 * @return array
	 */
	protected function esc_array( $arr ) {
		return array_map( [ $this, 'esc_value' ], $arr );
	}

	/**
	 * Escape value for sql.
	 *
	 * @param  mixed $value Value to escape.
	 * @return mixed
	 */
	protected function esc_value( $value ) {
		global $wpdb;

		if ( is_int( $value ) ) {
			return $wpdb->prepare( '%d', $value );
		}

		if ( is_float( $value ) ) {
			return $wpdb->prepare( '%f', $value );
		}

		if ( is_string( $value ) ) {
			return 'null' === $value ? $value : $wpdb->prepare( '%s', $value );
		}

		return $value;
	}

	/**
	 * Escape value for like statement.
	 *
	 * @param  string $value Value for like statement.
	 * @param  string $start (Optional) The start of like query.
	 * @param  string $end   (Optional) The end of like query.
	 * @return string
	 */
	protected function esc_like( $value, $start = '%', $end = '%' ) {
		global $wpdb;
		return $start . $wpdb->esc_like( $value ) . $end;
	}

	/**
	 * Wrap a value that has an alias.
	 *
	 * @param  string $value Value.
	 * @param  string $alias Alias.
	 * @return string
	 */
	protected function wrap_alias( $value, $alias ) {
		return empty( $alias ) ? $value : $value . ' AS ' . $alias;
	}

	/**
	 * Wrap a value.
	 *
	 * @param  string $column   Column name.
	 * @param  string $value    Value.
	 * @param  string $operator Operator.
	 * @return string
	 */
	protected function wrap_value( $column, $value, $operator = '=' ) {
		return join( ' ', [ $column, $operator, $value ] );
	}

	/**
	 * Reset query.
	 */
	public function reset() {
		$this->limit  = null;
		$this->values = [];
	}
}
