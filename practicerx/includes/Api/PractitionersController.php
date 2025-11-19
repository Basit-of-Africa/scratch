<?php
namespace PracticeRx\Api;

use WP_REST_Server;
use PracticeRx\Models\Practitioner;

/**
 * Class PractitionersController
 */
class PractitionersController extends ApiController {

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/practitioners', array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Get practitioners.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function get_items( $request ) {
		$items = Practitioner::all();
		return rest_ensure_response( $items );
	}
}
