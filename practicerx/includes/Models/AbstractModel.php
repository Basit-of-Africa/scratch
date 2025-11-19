<?php
namespace PracticeRx\Models;

/**
 * Class AbstractModel
 *
 * Base class for all data models.
 */
abstract class AbstractModel {

	/**
	 * The table name.
	 *
	 * @var string
	 */
	protected static $table;

	/**
	 * The primary key.
	 *
	 * @var string
	 */
	protected static $primary_key = 'id';

	/**
	 * Get the full table name with prefix.
	 *
	 * @return string
	 */
	public static function get_table() {
		global $wpdb;
		return $wpdb->prefix . static::$table;
	}

	/**
	 * Get a record by ID.
	 *
	 * @param int $id Record ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		global $wpdb;
		$table = self::get_table();
		$pk    = static::$primary_key;

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE {$pk} = %d", $id ) );
	}

	/**
	 * Create a new record.
	 *
	 * @param array $data Data to insert.
	 * @return int|false Inserted ID or false on failure.
	 */
	public static function create( $data ) {
		global $wpdb;
		$table = self::get_table();

		// Add timestamps if they exist in the table schema
		if ( ! isset( $data['created_at'] ) ) {
			$data['created_at'] = current_time( 'mysql' );
		}

		$result = $wpdb->insert( $table, $data );

		if ( $result ) {
			return $wpdb->insert_id;
		}

		return false;
	}

	/**
	 * Update a record.
	 *
	 * @param int   $id   Record ID.
	 * @param array $data Data to update.
	 * @return int|false Number of rows affected or false on failure.
	 */
	public static function update( $id, $data ) {
		global $wpdb;
		$table = self::get_table();
		$pk    = static::$primary_key;

		if ( ! isset( $data['updated_at'] ) ) {
			$data['updated_at'] = current_time( 'mysql' );
		}

		return $wpdb->update( $table, $data, array( $pk => $id ) );
	}

	/**
	 * Delete a record.
	 *
	 * @param int $id Record ID.
	 * @return int|false Number of rows affected or false on failure.
	 */
	public static function delete( $id ) {
		global $wpdb;
		$table = self::get_table();
		$pk    = static::$primary_key;

		return $wpdb->delete( $table, array( $pk => $id ) );
	}

	/**
	 * Get all records.
	 *
	 * @param array $args Query arguments (limit, offset, orderby, order).
	 * @return array
	 */
	public static function all( $args = array() ) {
		global $wpdb;
		$table = self::get_table();

		$defaults = array(
			'limit'   => 20,
			'offset'  => 0,
			'orderby' => static::$primary_key,
			'order'   => 'DESC',
		);

		$args = wp_parse_args( $args, $defaults );

		$sql = "SELECT * FROM {$table} ORDER BY {$args['orderby']} {$args['order']} LIMIT %d OFFSET %d";

		return $wpdb->get_results( $wpdb->prepare( $sql, $args['limit'], $args['offset'] ) );
	}
}
