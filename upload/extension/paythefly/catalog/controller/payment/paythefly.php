<?php
namespace Opencart\Catalog\Controller\Extension\Paythefly\Payment;

/**
 * Class PayTheFly
 *
 * Catalog controller for PayTheFly crypto payment gateway.
 * Handles checkout flow, payment URL generation, and webhook callbacks.
 *
 * @package Opencart\Catalog\Controller\Extension\Paythefly\Payment
 */
class Paythefly extends \Opencart\System\Engine\Controller {
	/**
	 * Index - Render the payment method selection in checkout
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('extension/paythefly/payment/paythefly');

		$data['language'] = $this->config->get('config_language');

		$chain = $this->config->get('payment_paythefly_chain') ?: 'bsc';
		$data['chain_name'] = ($chain === 'tron') ? 'TRON' : 'BNB Smart Chain (BSC)';

		return $this->load->view('extension/paythefly/payment/paythefly', $data);
	}

	/**
	 * Confirm - Initiate the payment process
	 *
	 * Creates a PayTheFly payment request with EIP-712 signature,
	 * generates the payment URL, and redirects the customer.
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('extension/paythefly/payment/paythefly');
		$this->load->model('extension/paythefly/payment/paythefly');

		$json = [];

		// Validate order exists in session
		if (!isset($this->session->data['order_id'])) {
			$json['error'] = $this->language->get('error_order');

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		// Validate payment method
		if (!isset($this->session->data['payment_method']) || $this->session->data['payment_method']['code'] != 'paythefly.paythefly') {
			$json['error'] = $this->language->get('error_payment_method');

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		// Load order
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if (!$order_info) {
			$json['redirect'] = $this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true);
			unset($this->session->data['order_id']);

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			return;
		}

		try {
			// Payment parameters
			$projectId = $this->config->get('payment_paythefly_project_id');
			$chain = $this->config->get('payment_paythefly_chain') ?: 'bsc';
			$chainConfig = $this->model_extension_paythefly_payment_paythefly->getChainConfig($chain);

			$tokenAddress = $this->config->get('payment_paythefly_token_address')
				?: $chainConfig['nativeToken'];

			$decimals = (int)($this->config->get('payment_paythefly_token_decimals')
				?: $chainConfig['decimals']);

			$deadlineOffset = (int)($this->config->get('payment_paythefly_deadline_offset') ?: 3600);
			$deadline = time() + $deadlineOffset;

			// Generate unique serial number from order ID
			$serialNo = 'OC-' . $order_info['order_id'] . '-' . time();

			// Amount: human-readable for URL, raw wei for signature
			$amountDisplay = number_format((float)$order_info['total'], 8, '.', '');
			$amountRaw = $this->model_extension_paythefly_payment_paythefly->toRawAmount($amountDisplay, $decimals);

			// Generate EIP-712 signature
			$signature = $this->model_extension_paythefly_payment_paythefly->signPaymentRequest([
				'projectId' => $projectId,
				'token'     => $tokenAddress,
				'amount'    => $amountRaw,     // Raw wei for signature
				'serialNo'  => $serialNo,
				'deadline'  => $deadline,
			]);

			// Build payment URL (uses human-readable amount)
			$paymentUrl = $this->model_extension_paythefly_payment_paythefly->buildPaymentUrl([
				'projectId'     => $projectId,
				'amountDisplay' => $amountDisplay,
				'serialNo'      => $serialNo,
				'deadline'      => $deadline,
				'signature'     => $signature,
			]);

			// Record the transaction
			$this->model_extension_paythefly_payment_paythefly->addTransaction([
				'order_id'       => $order_info['order_id'],
				'serial_no'      => $serialNo,
				'project_id'     => $projectId,
				'chain_symbol'   => strtoupper($chain),
				'chain_id'       => $chainConfig['chainId'],
				'token_address'  => $tokenAddress,
				'amount'         => $amountRaw,
				'amount_display' => $amountDisplay,
				'signature'      => $signature,
				'deadline'       => $deadline,
				'payment_url'    => $paymentUrl,
			]);

			// Set order to pending
			$this->model_checkout_order->addHistory(
				$order_info['order_id'],
				(int)$this->config->get('payment_paythefly_pending_status_id'),
				sprintf($this->language->get('text_pending_comment'), $serialNo),
				true
			);

			// Log if debug
			$this->model_extension_paythefly_payment_paythefly->log(
				"Payment created: Order #{$order_info['order_id']}, Serial: {$serialNo}, " .
				"Amount: {$amountDisplay} (raw: {$amountRaw}), Chain: {$chain}, Deadline: {$deadline}"
			);

			// Redirect to PayTheFly payment page
			$json['redirect'] = $paymentUrl;

		} catch (\RuntimeException $e) {
			$this->model_extension_paythefly_payment_paythefly->log('ERROR: ' . $e->getMessage());
			$json['error'] = $this->language->get('error_payment_creation') . ' ' . $e->getMessage();
		} catch (\Throwable $e) {
			$this->model_extension_paythefly_payment_paythefly->log('FATAL: ' . $e->getMessage());
			$json['error'] = $this->language->get('error_payment_creation');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Webhook - Handle PayTheFly payment notifications
	 *
	 * Endpoint: index.php?route=extension/paythefly/payment/paythefly.webhook
	 *
	 * Webhook body format:
	 *   { "data": "<json string>", "sign": "<hmac hex>", "timestamp": <unix int> }
	 *
	 * Webhook data fields:
	 *   project_id, chain_symbol, tx_hash, wallet, value, fee,
	 *   serial_no, tx_type (1=payment, 2=withdrawal), confirmed, create_at
	 *
	 * @return void
	 */
	public function webhook(): void {
		$this->load->model('extension/paythefly/payment/paythefly');

		// Read raw POST body
		$rawBody = file_get_contents('php://input');

		$this->model_extension_paythefly_payment_paythefly->log('Webhook received: ' . $rawBody);

		$body = json_decode($rawBody, true);

		// Validate webhook structure
		if (!$body || !isset($body['data']) || !isset($body['sign']) || !isset($body['timestamp'])) {
			$this->model_extension_paythefly_payment_paythefly->log('Webhook ERROR: Invalid body structure');
			$this->webhookResponse(false, 'Invalid request body');
			return;
		}

		$data = $body['data'];
		$sign = $body['sign'];
		$timestamp = (int)$body['timestamp'];

		// Verify HMAC signature
		if (!$this->model_extension_paythefly_payment_paythefly->verifyWebhookSignature($data, $sign, $timestamp)) {
			$this->model_extension_paythefly_payment_paythefly->log('Webhook ERROR: Signature verification failed');
			$this->webhookResponse(false, 'Invalid signature');
			return;
		}

		// Parse the data JSON string
		$webhookData = json_decode($data, true);

		if (!$webhookData || !isset($webhookData['serial_no'])) {
			$this->model_extension_paythefly_payment_paythefly->log('Webhook ERROR: Missing serial_no in data');
			$this->webhookResponse(false, 'Missing serial_no');
			return;
		}

		$serialNo = $webhookData['serial_no'];

		// Find the transaction
		$transaction = $this->model_extension_paythefly_payment_paythefly->getTransactionBySerialNo($serialNo);

		if (!$transaction) {
			$this->model_extension_paythefly_payment_paythefly->log("Webhook ERROR: Transaction not found for serial_no: {$serialNo}");
			$this->webhookResponse(false, 'Transaction not found');
			return;
		}

		// Verify project_id matches
		if (isset($webhookData['project_id']) && $webhookData['project_id'] !== $transaction['project_id']) {
			$this->model_extension_paythefly_payment_paythefly->log("Webhook ERROR: Project ID mismatch for serial_no: {$serialNo}");
			$this->webhookResponse(false, 'Project ID mismatch');
			return;
		}

		// Update transaction record
		$txType = (int)($webhookData['tx_type'] ?? 1);
		$confirmed = !empty($webhookData['confirmed']) ? 1 : 0;

		$this->model_extension_paythefly_payment_paythefly->updateTransaction($serialNo, [
			'tx_hash'      => $webhookData['tx_hash'] ?? '',
			'wallet'       => $webhookData['wallet'] ?? '',
			'fee'          => $webhookData['fee'] ?? '0',
			'chain_symbol' => $webhookData['chain_symbol'] ?? '',
			'tx_type'      => $txType,
			'confirmed'    => $confirmed,
			'status'       => $confirmed ? 'confirmed' : 'pending',
			'webhook_raw'  => $rawBody,
		]);

		// Update order status if payment is confirmed
		if ($txType === 1 && $confirmed) {
			$this->load->model('checkout/order');

			$comment = sprintf(
				"PayTheFly payment confirmed.\nTx: %s\nWallet: %s\nChain: %s\nAmount: %s\nFee: %s",
				$webhookData['tx_hash'] ?? 'N/A',
				$webhookData['wallet'] ?? 'N/A',
				$webhookData['chain_symbol'] ?? 'N/A',
				$webhookData['value'] ?? 'N/A',
				$webhookData['fee'] ?? '0'
			);

			$this->model_checkout_order->addHistory(
				(int)$transaction['order_id'],
				(int)$this->config->get('payment_paythefly_confirmed_status_id'),
				$comment,
				true  // Notify customer
			);

			$this->model_extension_paythefly_payment_paythefly->log(
				"Payment CONFIRMED: Order #{$transaction['order_id']}, Serial: {$serialNo}, Tx: " . ($webhookData['tx_hash'] ?? 'N/A')
			);
		} elseif ($txType === 1 && !$confirmed) {
			// Payment seen but not yet confirmed on chain
			$this->model_extension_paythefly_payment_paythefly->log(
				"Payment PENDING confirmation: Order #{$transaction['order_id']}, Serial: {$serialNo}"
			);
		}

		// Response must contain "success"
		$this->webhookResponse(true, 'success');
	}

	/**
	 * Callback - Handle redirect back from PayTheFly
	 *
	 * When a customer returns from the PayTheFly payment page,
	 * redirect them to the appropriate OpenCart page.
	 *
	 * @return void
	 */
	public function callback(): void {
		$this->load->language('extension/paythefly/payment/paythefly');

		if (isset($this->session->data['order_id'])) {
			// Redirect to success page (actual confirmation comes via webhook)
			$this->response->redirect(
				$this->url->link('checkout/success', 'language=' . $this->config->get('config_language'), true)
			);
		} else {
			$this->response->redirect(
				$this->url->link('checkout/failure', 'language=' . $this->config->get('config_language'), true)
			);
		}
	}

	/**
	 * Send webhook response
	 *
	 * @param bool   $success
	 * @param string $message
	 *
	 * @return void
	 */
	private function webhookResponse(bool $success, string $message): void {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode([
			'success' => $success,
			'message' => $message,
		]));
	}
}
