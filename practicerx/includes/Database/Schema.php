<?php
namespace PracticeRx\Database;

/**
 * Class Schema
 *
 * Handles database table creation and updates.
 */
class Schema {

	/**
	 * Create or update custom tables.
	 */
	public static function create_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$tables = array(
			"CREATE TABLE {$wpdb->prefix}ppms_practitioners (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				user_id bigint(20) NOT NULL,
				specialty varchar(255) DEFAULT '',
				license_number varchar(100) DEFAULT '',
				bio text DEFAULT '',
				availability_settings longtext DEFAULT '',
				created_at datetime DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (id),
				KEY user_id (user_id)
			) $charset_collate;",

			"CREATE TABLE {$wpdb->prefix}ppms_patients (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				user_id bigint(20) NOT NULL,
				dob date DEFAULT '0000-00-00',
				gender varchar(50) DEFAULT '',
				phone varchar(50) DEFAULT '',
				address text DEFAULT '',
				emergency_contact longtext DEFAULT '',
				medical_history_summary longtext DEFAULT '',
				PRIMARY KEY  (id),
				KEY user_id (user_id)
			) $charset_collate;",

			"CREATE TABLE {$wpdb->prefix}ppms_services (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				name varchar(255) NOT NULL,
				duration_minutes int(11) NOT NULL DEFAULT 30,
				price decimal(10,2) NOT NULL DEFAULT 0.00,
				currency varchar(3) NOT NULL DEFAULT 'USD',
				practitioner_ids longtext DEFAULT '',
				is_active tinyint(1) NOT NULL DEFAULT 1,
				PRIMARY KEY  (id)
			) $charset_collate;",

			"CREATE TABLE {$wpdb->prefix}ppms_appointments (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				patient_id bigint(20) NOT NULL,
				practitioner_id bigint(20) NOT NULL,
				service_id bigint(20) NOT NULL,
				start_time datetime NOT NULL,
				end_time datetime NOT NULL,
				status varchar(50) NOT NULL DEFAULT 'scheduled',
				notes text DEFAULT '',
				meeting_link varchar(255) DEFAULT '',
				PRIMARY KEY  (id),
				KEY patient_id (patient_id),
				KEY practitioner_id (practitioner_id),
				KEY service_id (service_id)
			) $charset_collate;",

			"CREATE TABLE {$wpdb->prefix}ppms_encounters (
				id bigint(20) NOT NULL AUTO_INCREMENT,
				appointment_id bigint(20) DEFAULT NULL,
				practitioner_id bigint(20) NOT NULL,
				patient_id bigint(20) NOT NULL,
				type varchar(50) NOT NULL DEFAULT 'general',
				content longtext DEFAULT '',
				created_at datetime DEFAULT CURRENT_TIMESTAMP,
				updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
				PRIMARY KEY  (id),
				KEY appointment_id (appointment_id),
				KEY patient_id (patient_id)
			) $charset_collate;"
		);

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		foreach ( $tables as $sql ) {
			dbDelta( $sql );
		}
	}
}
