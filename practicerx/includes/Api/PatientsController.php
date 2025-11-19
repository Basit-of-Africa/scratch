<?php
namespace PracticeRx\Api;

use WP_REST_Server;
use PracticeRx\Models\Patient;

/**
 * Class PatientsController
 */
class PatientsController extends ApiController {

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/patients', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );

		register_rest_route( $this->namespace, '/patients/(?P<id>\d+)', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_item' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Get patients.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_items( $request ) {
		// Pagination logic here...
		$items = Patient::all();

		return rest_ensure_response( $items );
	}

	/**
	 * Get single patient.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function get_item( $request ) {
		$id = $request->get_param( 'id' );
		$item = Patient::get( $id );

		if ( ! $item ) {
			return new \WP_Error( 'not_found', 'Patient not found', array( 'status' => 404 ) );
		}

		return rest_ensure_response( $item );
	}
}
