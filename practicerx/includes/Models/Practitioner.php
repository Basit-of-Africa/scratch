<?php
namespace PracticeRx\Models;

/**
 * Class Practitioner
 *
 * Model for Practitioner data.
 */
class Practitioner extends AbstractModel {

	/**
	 * Table name (without prefix).
	 *
	 * @var string
	 */
	protected static $table = 'ppms_practitioners';

	/**
	 * Get practitioner by User ID.
	 *
	 * @param int $user_id WP User ID.
	 * @return object|null
	 */
	public static function get_by_user_id( $user_id ) {
		global $wpdb;
		$table = self::get_table();

		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE user_id = %d", $user_id ) );
	}
}
