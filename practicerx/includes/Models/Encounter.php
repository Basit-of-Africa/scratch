<?php
namespace PracticeRx\Models;

/**
 * Class Encounter
 *
 * Model for Clinical Encounters (Notes).
 */
class Encounter extends AbstractModel {

	/**
	 * Table name (without prefix).
	 *
	 * @var string
	 */
	protected static $table = 'ppms_encounters';

	/**
	 * Get encounters by Patient ID.
	 *
	 * @param int $patient_id Patient ID.
	 * @return array
	 */
	public static function get_by_patient( $patient_id ) {
		global $wpdb;
		$table = self::get_table();

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE patient_id = %d ORDER BY created_at DESC", $patient_id ) );
	}

	/**
	 * Get encounters by Appointment ID.
	 *
	 * @param int $appointment_id Appointment ID.
	 * @return array
	 */
	public static function get_by_appointment( $appointment_id ) {
		global $wpdb;
		$table = self::get_table();

		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} WHERE appointment_id = %d ORDER BY created_at DESC", $appointment_id ) );
	}
}
