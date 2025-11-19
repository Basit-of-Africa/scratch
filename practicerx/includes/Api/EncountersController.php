<?php
namespace PracticeRx\Api;

use WP_REST_Server;
use PracticeRx\Models\Encounter;

/**
 * Class EncountersController
 */
class EncountersController extends ApiController {

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/encounters', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );

		register_rest_route( $this->namespace, '/patients/(?P<id>\d+)/encounters', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items_for_patient' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Get encounters for a patient.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_items_for_patient( $request ) {
		$patient_id = $request->get_param( 'id' );
		$items      = Encounter::get_by_patient( $patient_id );

		return rest_ensure_response( $items );
	}

	/**
	 * Create encounter.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function create_item( $request ) {
		$data = $request->get_json_params();
		
		// Basic validation
		if ( empty( $data['patient_id'] ) || empty( $data['practitioner_id'] ) || empty( $data['content'] ) ) {
			return new \WP_Error( 'missing_fields', 'Missing required fields.', array( 'status' => 400 ) );
		}

		$id = Encounter::create( $data );

		if ( ! $id ) {
			return new \WP_Error( 'create_failed', 'Could not save encounter.', array( 'status' => 500 ) );
		}

		return rest_ensure_response( Encounter::get( $id ) );
	}
}
