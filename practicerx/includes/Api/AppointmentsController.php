<?php
namespace PracticeRx\Api;

use WP_REST_Server;
use PracticeRx\Models\Appointment;

/**
 * Class AppointmentsController
 */
class AppointmentsController extends ApiController {

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/appointments', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Get appointments.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_items( $request ) {
		$start_date = $request->get_param( 'start_date' );
		$end_date   = $request->get_param( 'end_date' );
		
		if ( ! $start_date || ! $end_date ) {
			return new \WP_Error( 'missing_params', 'Start and End dates are required', array( 'status' => 400 ) );
		}

		$items = Appointment::get_by_range( $start_date, $end_date );

		return rest_ensure_response( $items );
	}

	/**
	 * Create appointment.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function create_item( $request ) {
		$data = $request->get_json_params();
		
		$service = new \PracticeRx\Services\AppointmentService();
		$result  = $service->create_appointment( $data );

		if ( is_wp_error( $result ) ) {
			return $result;
		}

		return rest_ensure_response( Appointment::get( $result ) );
	}
}
