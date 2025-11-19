<?php
namespace PracticeRx\Api;

use WP_REST_Server;
use PracticeRx\Core\DemoDataSeeder;

/**
 * Class SystemController
 */
class SystemController extends ApiController {

	/**
	 * Register routes.
	 */
	public function register_routes() {
		register_rest_route( $this->namespace, '/system/seed', array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'seed_data' ),
				'permission_callback' => array( $this, 'check_permissions' ),
			),
		) );
	}

	/**
	 * Seed demo data.
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response
	 */
	public function seed_data( $request ) {
		$count = DemoDataSeeder::run();
		return rest_ensure_response( array( 'success' => true, 'items_created' => $count ) );
	}
	
	/**
	 * Check admin permissions.
	 */
	public function check_permissions( $request ) {
		return current_user_can( 'manage_options' );
	}
}
