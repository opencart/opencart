<?php
class ControllerExtensionPaymentDivido extends Controller {
	const
		STATUS_ACCEPTED = 'ACCEPTED',
		STATUS_ACTION_LENDER = 'ACTION-LENDER',
		STATUS_CANCELED = 'CANCELED',
		STATUS_COMPLETED = 'COMPLETED',
		STATUS_DEPOSIT_PAID = 'DEPOSIT-PAID',
		STATUS_DECLINED = 'DECLINED',
		STATUS_DEFERRED = 'DEFERRED',
		STATUS_REFERRED = 'REFERRED',
		STATUS_FULFILLED = 'FULFILLED',
		STATUS_SIGNED = 'SIGNED';

	private $status_id = array(
		self::STATUS_ACCEPTED => 1,
		self::STATUS_ACTION_LENDER => 2,
		self::STATUS_CANCELED => 0,
		self::STATUS_COMPLETED => 2,
		self::STATUS_DECLINED => 8,
		self::STATUS_DEFERRED => 1,
		self::STATUS_REFERRED => 1,
		self::STATUS_DEPOSIT_PAID => 1,
		self::STATUS_FULFILLED => 1,
		self::STATUS_SIGNED => 2,
	);

	private $history_messages = array(
		self::STATUS_ACCEPTED => 'Credit request accepted',
		self::STATUS_ACTION_LENDER => 'Lender notified',
		self::STATUS_CANCELED => 'Credit request canceled',
		self::STATUS_COMPLETED => 'Credit application completed',
		self::STATUS_DECLINED => 'Credit request declined',
		self::STATUS_DEFERRED => 'Credit request deferred',
		self::STATUS_REFERRED => 'Credit request referred',
		self::STATUS_DEPOSIT_PAID => 'Deposit paid',
		self::STATUS_FULFILLED => 'Credit request fulfilled',
		self::STATUS_SIGNED => 'Contract signed',
	);

	public function index() {
		$this->load->language('extension/payment/divido');
		$this->load->model('extension/payment/divido');
		$this->load->model('checkout/order');

		$api_key   = $this->config->get('payment_divido_api_key');
		$key_parts = explode('.', $api_key);
		$js_key    = strtolower(array_shift($key_parts));

		list($total, $totals) = $this->model_extension_payment_divido->getOrderTotals();

		$this->model_extension_payment_divido->setMerchant($this->config->get('payment_divido_api_key'));

		$plans = $this->model_extension_payment_divido->getCartPlans($this->cart);
		foreach ($plans as $key => $plan) {
			$planMinTotal = $total - ($total * ($plan->min_deposit / 100));
			if ($plan->min_amount > $planMinTotal) {
				unset($plans[$key]);
			}
		}

		$plans_ids  = array_map(function ($plan) {
			return $plan->id;
		}, $plans);
		$plans_ids  = array_unique($plans_ids);
		$plans_list = implode(',', $plans_ids);

		$data = array(
			'button_confirm'           => $this->language->get('divido_checkout'),
			'merchant_script'          => "//cdn.divido.com/calculator/{$js_key}.js",
			'grand_total'              => $total,
			'plan_list'                => $plans_list,
			'generic_credit_req_error' => 'Credit request could not be initiated',
		);

		return $this->load->view('extension/payment/divido', $data);
	}

	public function update() {
		$this->load->language('extension/payment/divido');
		$this->load->model('extension/payment/divido');
		$this->load->model('checkout/order');

		$data = json_decode(file_get_contents('php://input'));

		if (!isset($data->status)) {
			$this->response->setOutput('');
			return;
		}

		$lookup = $this->model_extension_payment_divido->getLookupByOrderId($data->metadata->order_id);
		if ($lookup->num_rows != 1) {
			$this->response->setOutput('');
			return;
		}

		$hash = $this->model_extension_payment_divido->hashOrderId($data->metadata->order_id, $lookup->row['salt']);
		if ($hash !== $data->metadata->order_hash) {
			$this->response->setOutput('');
			return;
		}

		$order_id = $data->metadata->order_id;
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$status_id = $order_info['order_status_id'];
		$message = "Status: {$data->status}";
		if (isset($this->history_messages[$data->status])) {
			$message = $this->history_messages[$data->status];
		}

		if ($data->status == self::STATUS_SIGNED) {
			$status_override = $this->config->get('payment_divido_order_status_id');
			if (!empty($status_override)) {
				$this->status_id[self::STATUS_SIGNED] = $status_override;
			}
		}

		if (isset($this->status_id[$data->status]) && $this->status_id[$data->status] > $status_id) {
			$status_id = $this->status_id[$data->status];
		}

		if ($data->status == self::STATUS_DECLINED && $order_info['order_status_id'] == 0) {
			$status_id = 0;
		}

		$this->model_extension_payment_divido->saveLookup($data->metadata->order_id, $lookup->row['salt'], null, $data->application);
		$this->model_checkout_order->addOrderHistory($order_id, $status_id, $message, false);
		$this->response->setOutput('ok');
	}

	public function confirm() {
		$this->load->language('extension/payment/divido');

		$this->load->model('extension/payment/divido');

		ini_set('html_errors', 0);
		if (!$this->session->data['payment_method']['code'] == 'divido') {
			return false;
		}

		$this->model_extension_payment_divido->setMerchant($this->config->get('payment_divido_api_key'));

		$api_key   = $this->config->get('payment_divido_api_key');

		$deposit = $this->request->post['deposit'];
		$finance = $this->request->post['finance'];

		$address = $this->session->data['payment_address'];
		if (isset($this->session->data['shipping_address'])) {
			$address = $this->session->data['shipping_address'];
		}

		$country  = $address['iso_code_2'];
		$language = strtoupper($this->language->get('code'));
		$currency = strtoupper($this->session->data['currency']);
		$order_id = $this->session->data['order_id'];

		if ($this->customer->isLogged()) {
			$this->load->model('account/customer');
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

			$firstname = $customer_info['firstname'];
			$lastname  = $customer_info['lastname'];
			$email     = $customer_info['email'];
			$telephone = $customer_info['telephone'];
		} elseif (isset($this->session->data['guest'])) {
			$firstname = $this->session->data['guest']['firstname'];
			$lastname  = $this->session->data['guest']['lastname'];
			$email     = $this->session->data['guest']['email'];
			$telephone = $this->session->data['guest']['telephone'];
		}

		$postcode  = $address['postcode'];

		$products  = array();
		foreach ($this->cart->getProducts() as $product) {
			$products[] = array(
				'type' => 'product',
				'text' => $product['name'],
				'quantity' => $product['quantity'],
				'value' => $product['price'],
			);
		}

		list($total, $totals) = $this->model_extension_payment_divido->getOrderTotals();

		$sub_total  = $total;
		$cart_total = $this->cart->getSubTotal();
		$shiphandle = $sub_total - $cart_total;

		$products[] = array(
			'type'     => 'product',
			'text'     => 'Shipping & Handling',
			'quantity' => 1,
			'value'    => $shiphandle,
		);

		$deposit_amount = round(($deposit / 100) * $total, 2, PHP_ROUND_HALF_UP);

		$shop_url = $this->config->get('config_url');
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$shop_url = $this->config->get('config_ssl');
		}

		$callback_url = $this->url->link('extension/payment/divido/update', '', true);
		$return_url = $this->url->link('checkout/success', '', true);
		$checkout_url = $this->url->link('checkout/checkout', '', true);

		$salt = uniqid('', true);
		$hash = $this->model_extension_payment_divido->hashOrderId($order_id, $salt);

		$request_data = array(
			'merchant' => $api_key,
			'deposit'  => $deposit_amount,
			'finance'  => $finance,
			'country'  => $country,
			'language' => $language,
			'currency' => $currency,
			'metadata' => array(
				'order_id'   => $order_id,
				'order_hash' => $hash,
			),
			'customer' => array(
				'title'         => '',
				'first_name'    => $firstname,
				'middle_name'   => '',
				'last_name'     => $lastname,
				'country'       => $country,
				'postcode'      => $postcode,
				'email'         => $email,
				'mobile_number' => '',
				'phone_number'  => $telephone,
			),
			'products'     => $products,
			'response_url' => $callback_url,
			'redirect_url' => $return_url,
			'checkout_url' => $checkout_url,
		);

		$response = Divido_CreditRequest::create($request_data);

		if ($response->status == 'ok') {

			$this->model_extension_payment_divido->saveLookup($order_id, $salt, $response->id, null, $deposit_amount);

			$data = array(
				'status' => 'ok',
				'url'    => $response->url,
			);
		} else {
			$data = array(
				'status'  => 'error',
				'message' => $this->language->get($response->error),
			);
		}

		$this->response->setOutput(json_encode($data));
	}

	public function calculator($args) {
		$this->load->language('extension/payment/divido');

		$this->load->model('extension/payment/divido');

		if (!$this->model_extension_payment_divido->isEnabled()) {
			return null;
		}

		$this->model_extension_payment_divido->setMerchant($this->config->get('payment_divido_api_key'));

		$product_selection = $this->config->get('payment_divido_productselection');
		$price_threshold   = $this->config->get('payment_divido_price_threshold');
		$product_id        = $args['product_id'];
		$product_price     = $args['price'];
		$type              = $args['type'];

		if ($product_selection == 'threshold' && $product_price < $price_threshold) {
			return null;
		}

		$plans = $this->model_extension_payment_divido->getProductPlans($product_id);
		if (empty($plans)) {
			return null;
		}

		$plans_ids = array_map(function ($plan) {
			return $plan->id;
		}, $plans);

		$plan_list = implode(',', $plans_ids);

		$data = array(
			'planList'     => $plan_list,
			'productPrice' => $product_price
		);

		$filename = ($type == 'full') ? 'extension/payment/divido_calculator' : 'extension/payment/divido_widget';

		return $this->load->view($filename, $data);
	}
}
