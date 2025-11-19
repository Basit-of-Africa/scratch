<?php
namespace PracticeRx\Services;

use PracticeRx\Models\Appointment;
use PracticeRx\Models\Practitioner;

/**
 * Class AppointmentService
 *
 * Handles business logic for appointments.
 */
class AppointmentService {

	/**
	 * Create a new appointment.
	 *
	 * @param array $data Appointment data.
	 * @return int|\WP_Error Appointment ID or error.
	 */
	public function create_appointment( $data ) {
		// Validate required fields
		if ( empty( $data['patient_id'] ) || empty( $data['practitioner_id'] ) || empty( $data['service_id'] ) || empty( $data['start_time'] ) ) {
			return new \WP_Error( 'missing_fields', 'Missing required fields.', array( 'status' => 400 ) );
		}

		// Check availability
		if ( ! $this->is_slot_available( $data['practitioner_id'], $data['start_time'], $data['end_time'] ) ) {
			return new \WP_Error( 'slot_unavailable', 'The selected slot is not available.', array( 'status' => 409 ) );
		}

		// Create appointment
		$id = Appointment::create( $data );

		if ( ! $id ) {
			return new \WP_Error( 'db_error', 'Could not save appointment.', array( 'status' => 500 ) );
		}

		return $id;
	}

	/**
	 * Check if a slot is available.
	 *
	 * @param int    $practitioner_id Practitioner ID.
	 * @param string $start_time      Start time (Y-m-d H:i:s).
	 * @param string $end_time        End time (Y-m-d H:i:s).
	 * @return bool
	 */
	public function is_slot_available( $practitioner_id, $start_time, $end_time ) {
		// 1. Check practitioner working hours (simplified for now)
		// TODO: Implement detailed working hours check from Practitioner settings

		// 2. Check for overlapping appointments
		$conflicts = Appointment::get_by_range( $start_time, $end_time, $practitioner_id );
		
		// Filter out appointments that don't actually overlap (get_by_range is broad)
		foreach ( $conflicts as $appointment ) {
			if ( $appointment->status === 'cancelled' ) {
				continue;
			}
			
			// Check overlap: (StartA < EndB) and (EndA > StartB)
			if ( $appointment->start_time < $end_time && $appointment->end_time > $start_time ) {
				return false;
			}
		}

		return true;
	}
}
