<?php
class ControllerPaymentDivido extends Controller {
	const
		STATUS_ACCEPTED = 'ACCEPTED',
		STATUS_CANCELED = 'CANCELED',
		STATUS_COMPLETED = 'COMPLETED',
		STATUS_DEPOSIT_PAID = 'DEPOSIT-PAID',
		STATUS_DECLINED = 'DECLINED',
		STATUS_DEFERRED = 'DEFERRED',
		STATUS_FULFILLED = 'FULFILLED',
		STATUS_SIGNED = 'SIGNED';

	private $status_id = array(
		self::STATUS_ACCEPTED => 1,
		self::STATUS_CANCELED => 1,
		self::STATUS_COMPLETED => 1,
		self::STATUS_DECLINED => 1,
		self::STATUS_DEFERRED => 1,
		self::STATUS_DEPOSIT_PAID => 1,
		self::STATUS_FULFILLED => 5,
		self::STATUS_SIGNED => 1,
	);

	private $history_messages = array(
		self::STATUS_ACCEPTED => 'Credit request accepted',
		self::STATUS_CANCELED => 'Credit request canceled',
		self::STATUS_COMPLETED => 'Credit application completed',
		self::STATUS_DECLINED => 'Credit request declined',
		self::STATUS_DEFERRED => 'Credit request deferred',
		self::STATUS_DEPOSIT_PAID => 'Deposit paid',
		self::STATUS_FULFILLED => 'Credit request fulfilled',
		self::STATUS_SIGNED => 'Contract signed',
	);

	public function index() {
		$this->load->language('payment/divido');
		$this->load->model('payment/divido');
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$api_key   = $this->config->get('divido_api_key');
		$key_parts = explode('.', $api_key);
		$js_key    = strtolower(array_shift($key_parts));

		list($total, $totals) = $this->model_payment_divido->getOrderTotals();

		$this->model_payment_divido->setMerchant($this->config->get('divido_api_key'));

		$plans = $this->model_payment_divido->getCartPlans($this->cart);
		foreach ($plans as $key => $plan) {
			if ($plan->min_amount > $total) {
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
			'text_loading'             => $this->language->get('text_loading'),
			'text_choose_deposit'      => $this->language->get('text_choose_deposit'),
			'text_choose_plan'         => $this->language->get('text_choose_plan'),
			'text_checkout_title'      => $this->language->get('text_checkout_title'),
			'text_monthly_payments'    => $this->language->get('text_monthly_payments'),
			'text_months'              => $this->language->get('text_months'),
			'text_term'                => $this->language->get('text_term'),
			'text_deposit'             => $this->language->get('text_deposit'),
			'text_credit_amount'       => $this->language->get('text_credit_amount'),
			'text_amount_payable'      => $this->language->get('text_amount_payable'),
			'text_total_interest'      => $this->language->get('text_total_interest'),
			'text_monthly_installment' => $this->language->get('text_monthly_installment'),
			'text_redirection'         => $this->language->get('text_redirection'),

			'merchant_script'          => "//cdn.divido.com/calculator/{$js_key}.js",
			'grand_total'              => $total,
			'plan_list'                => $plans_list,
			'generic_credit_req_error' => 'Credit request could not be initiated',
		);

		return $this->load->view('payment/divido', $data);
	}

	public function update() {
		$this->load->language('payment/divido');
		$this->load->model('payment/divido');
		$this->load->model('checkout/order');

		$data = json_decode(file_get_contents('php://input'));

		if (!isset($data->status)) {
			$this->response->setOutput('');
			return;
		}

		$lookup = $this->model_payment_divido->getLookupByOrderId($data->metadata->order_id);
		if ($lookup->num_rows != 1) {
			$this->response->setOutput('');
			return;
		}

		$hash = $this->model_payment_divido->hashOrderId($data->metadata->order_id, $lookup->row['salt']);
		if ($hash !== $data->metadata->order_hash) {
			$this->response->setOutput('');
			return;
		}

		$order_id  = $data->metadata->order_id;
		$status_id = $this->status_id[$data->status];
		$message   = $this->history_messages[$data->status];
		$notify    = $data->status == self::STATUS_FULFILLED;

		if ($data->status == self::STATUS_FULFILLED) {
			$status_override = $this->config->get('divido_order_status_id');
			if (!empty($status_override)) {
				$status =  $status_override;
			}
		}

		$this->model_payment_divido->saveLookup($data->metadata->order_id, $lookup->row['salt'], null, $data->application);

		$this->model_checkout_order->addOrderHistory($order_id, $status_id, $message, $notify);

		$this->response->setOutput('ok');
	}

	public function confirm() {
		$this->load->language('payment/divido');

		$this->load->model('payment/divido');

		ini_set('html_errors', 0);
		if (!$this->session->data['payment_method']['code'] == 'divido') {
			return false;
		}

		$this->model_payment_divido->setMerchant($this->config->get('divido_api_key'));

		$api_key   = $this->config->get('divido_api_key');

		$deposit = $this->request->post['deposit'];
		$finance = $this->request->post['finance'];

		$country  = $this->session->data['payment_address']['iso_code_2'];
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

		$postcode  = $this->session->data['payment_address']['postcode'];

		$products  = array();
		foreach ($this->cart->getProducts() as $product) {
			$products[] = array(
				'type' => 'product',
				'text' => $product['name'],
				'quantity' => $product['quantity'],
				'value' => $product['price'],
			);
		}

		list($total, $totals) = $this->model_payment_divido->getOrderTotals();

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

		$callback_url = $this->url->link('payment/divido/update', '', true);
		$return_url = $this->url->link('checkout/success', '', true);
		$checkout_url = $this->url->link('checkout/checkout', '', true);

		$salt = uniqid('', true);
		$hash = $this->model_payment_divido->hashOrderId($order_id, $salt);

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

			$this->model_payment_divido->saveLookup($order_id, $salt, $response->id);

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
		$this->load->language('payment/divido');

		$this->load->model('payment/divido');

		if (!$this->model_payment_divido->isEnabled()) {
			return null;
		}

		$this->model_payment_divido->setMerchant($this->config->get('divido_api_key'));

		$product_selection = $this->config->get('divido_productselection');
		$price_threshold   = $this->config->get('divido_price_threshold');
		$product_id        = $args['product_id'];
		$product_price     = $args['price'];
		$type              = $args['type'];

		if ($product_selection == 'threshold' && $product_price < $price_threshold) {
			return null;
		}

		$plans = $this->model_payment_divido->getProductPlans($product_id);
		if (empty($plans)) {
			return null;
		}

		$plans_ids = array_map(function ($plan) {
			return $plan->id;
		}, $plans);

		$plan_list = implode(',', $plans_ids);

		$data = array(
			'planList'                 => $plan_list,
			'productPrice'             => $product_price,
			'text_loading'             => $this->language->get('text_loading'),
			'text_choose_deposit'      => $this->language->get('text_choose_deposit'),
			'text_choose_plan'         => $this->language->get('text_choose_plan'),
			'text_checkout_title'      => $this->language->get('text_checkout_title'),
			'text_monthly_payments'    => $this->language->get('text_monthly_payments'),
			'text_months'              => $this->language->get('text_months'),
			'text_term'                => $this->language->get('text_term'),
			'text_deposit'             => $this->language->get('text_deposit'),
			'text_credit_amount'       => $this->language->get('text_credit_amount'),
			'text_amount_payable'      => $this->language->get('text_amount_payable'),
			'text_total_interest'      => $this->language->get('text_total_interest'),
			'text_monthly_installment' => $this->language->get('text_monthly_installment'),
		);

		$filename = ($type == 'full') ? 'payment/divido_calculator' : 'payment/divido_widget';

		return $this->load->view($filename, $data);
	}
}
