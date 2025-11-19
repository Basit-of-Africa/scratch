<?php
namespace PracticeRx\Core;

use PracticeRx\Database\Schema;

/**
 * Class Installer
 *
 * Handles plugin installation and updates.
 */
class Installer {

	/**
	 * Run the installation process.
	 */
	public static function install() {
		// Check for capability
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// Create tables
		Schema::create_tables();

		// Add roles
		self::add_roles();

		// Flush rewrite rules
		flush_rewrite_rules();
	}

	/**
	 * Add custom roles.
	 */
	private static function add_roles() {
		add_role(
			'ppms_practitioner',
			__( 'Practitioner', 'practicerx' ),
			array(
				'read' => true,
				'ppms_read_own_appointments' => true,
				'ppms_edit_own_encounters' => true,
				'ppms_view_patients' => true,
			)
		);

		add_role(
			'ppms_patient',
			__( 'Patient', 'practicerx' ),
			array(
				'read' => true,
				'ppms_read_own_profile' => true,
				'ppms_book_appointment' => true,
			)
		);
	}
}
