<?php
namespace PracticeRx\Models;

/**
 * Class Appointment
 *
 * Model for Appointment data.
 */
class Appointment extends AbstractModel {

	/**
	 * Table name (without prefix).
	 *
	 * @var string
	 */
	protected static $table = 'ppms_appointments';

	/**
	 * Get appointments by range.
	 *
	 * @param string $start_date Start date (Y-m-d).
	 * @param string $end_date   End date (Y-m-d).
	 * @param int    $practitioner_id Optional practitioner ID.
	 * @return array
	 */
	public static function get_by_range( $start_date, $end_date, $practitioner_id = 0 ) {
		global $wpdb;
		$table = self::get_table();

		$sql = "SELECT * FROM {$table} WHERE start_time >= %s AND end_time <= %s";
		$args = array( $start_date, $end_date );

		if ( $practitioner_id ) {
			$sql .= " AND practitioner_id = %d";
			$args[] = $practitioner_id;
		}

		$sql .= " ORDER BY start_time ASC";

		return $wpdb->get_results( $wpdb->prepare( $sql, ...$args ) );
	}
}
