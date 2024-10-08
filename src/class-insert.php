<?php
/**
 * The Insert query
 *
 * @since   1.0.0
 * @package Awesome9\Database
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Database;

use Awesome9\Database\Query\Base;
use Awesome9\Database\Interfaces\Query;

/**
 * Insert class.
 */
class Insert extends Base implements Query {

	/**
	 * Query type
	 *
	 * @var string
	 */
	const TYPE = 'INSERT';

	/**
	 * Set values for insert/update.
	 *
	 * @param  string|array $name  Key of pair.
	 * @param  string|array $value Value of pair.
	 * @return Query The current query.
	 */
	public function set( $name, $value = null ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $key => $val ) {
				$this->values[ $key ] = $this->esc_value( $val );
			}
		} else {
			$this->values[ $name ] = $this->esc_value( $value );
		}

		return $this;
	}

	/**
	 * Translate the current query to a SQL insert statement.
	 *
	 * @return string
	 */
	public function get_query() {
		$build = [ 'INSERT INTO ' . $this->get_table() ];

		$build[] = '(' . join( ', ', array_keys( $this->values ) ) . ')';
		$build[] = 'VALUES';
		$build[] = '(' . join( ', ', $this->values ) . ')';

		return join( ' ', $build );
	}

	/**
	 * Execute query.
	 *
	 * @param  string $output (Optional) Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
	 * @return mixed
	 */
	public function execute( $output = \ARRAY_N ) {
		$this->processor->query( $this->get_query() );

		return $this->processor->get_insert_id();
	}
}
