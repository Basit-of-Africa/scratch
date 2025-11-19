<?php
namespace PracticeRx\Services;

use PracticeRx\Services\Gateways\GatewayFactory;

/**
 * Class BillingService
 */
class BillingService {

	/**
	 * Process a payment for an invoice.
	 *
	 * @param int    $invoice_id Invoice ID.
	 * @param string $gateway_id Gateway ID.
	 * @return array|\WP_Error
	 */
	public function process_payment( $invoice_id, $gateway_id ) {
		// 1. Get Invoice (Mock for now)
		$invoice = (object) array(
			'id' => $invoice_id,
			'total_amount' => 100.00,
			'currency' => 'USD',
			'patient_email' => 'test@example.com',
		);

		// 2. Get Gateway
		$gateway = GatewayFactory::get( $gateway_id );
		if ( is_wp_error( $gateway ) ) {
			return $gateway;
		}

		// 3. Process
		return $gateway->process_payment(
			$invoice->total_amount,
			$invoice->currency,
			array( 'email' => $invoice->patient_email ),
			'INV-' . $invoice_id
		);
	}
}
