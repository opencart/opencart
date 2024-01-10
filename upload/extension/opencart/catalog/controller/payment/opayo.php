<?php
/**
 * Class Opayo
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Payment
 */
namespace Opencart\Catalog\Controller\Extension\Opayo\Payment;
class Opayo extends \Opencart\System\Engine\Controller {
	/**
	 * @var string
	 */
	private string $separator = '.';

	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		if ($this->config->get('payment_opayo_vendor')) {
			$this->load->language('extension/opayo/payment/opayo');

			// Setting
			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
			$_config->load('opayo');

			$config_setting = $_config->get('opayo_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			$data['card_types'] = $setting['card_type'];
			$data['card_save'] = (bool)$setting['general']['card_save'];

			$data['logged'] = $this->customer->isLogged();

			$data['cards'] = [];

			if ($data['logged'] && $data['card_save']) {
				$this->load->model('extension/opayo/payment/opayo');

				$data['cards'] = $this->model_extension_opayo_payment_opayo->getCards($this->customer->getId());
			}

			$data['months'] = [];

			for ($i = 1; $i <= 12; $i++) {
				$data['months'][] = [
					'code' => sprintf('%02d', $i),
					'name' => sprintf('%02d', $i)
				];
			}

			$today = getdate();

			$data['years'] = [];

			for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
				$data['years'][] = [
					'code' => sprintf('%04d', $i),
					'name' => sprintf('%02d', $i % 100)
				];
			}

			$data['separator'] = $this->separator;

			$data['language'] = $this->config->get('config_language');

			return $this->load->view('extension/opayo/payment/opayo', $data);
		} else {
			return '';
		}
	}

	/**
	 * Get Form
	 *
	 * @return void
	 */
	public function getForm(): void {
		$this->response->setOutput($this->index());
	}

	/**
	 * Confirm
	 *
	 * @return void
	 */
	public function confirm(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$this->load->model('checkout/order');
		$this->load->model('extension/opayo/payment/opayo');
		$this->load->model('account/order');

		// Setting
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$config_setting = $_config->get('opayo_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

		$payment_data = [];

		if ($setting['general']['environment'] == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp';
			$payment_data['VPSProtocol'] = '4.00';
		} elseif ($setting['general']['environment'] == 'test') {
			$url = 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp';
			$payment_data['VPSProtocol'] = '4.00';
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['Vendor'] = $this->config->get('payment_opayo_vendor');
		$payment_data['VendorTxCode'] = $this->session->data['order_id'] . 'SD' . date('YmdHis') . mt_rand(1, 999);
		$payment_data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$payment_data['Currency'] = $this->session->data['currency'];
		$payment_data['Description'] = substr($this->config->get('config_name'), 0, 100);
		$payment_data['TxType'] = $setting['general']['transaction_method'];

		if (!empty($this->request->post['opayo_card_existing']) && !empty($this->request->post['opayo_card_token'])) {
			$payment_data['Token'] = $this->request->post['opayo_card_token'];
			$payment_data['CV2'] = $this->request->post['opayo_card_cvv2_1'];
			$payment_data['StoreToken'] = '1';
			$payment_data['COFUsage'] = 'SUBSEQUENT';
		} else {
			$payment_data['CardHolder'] = $this->request->post['opayo_card_owner'];
			$payment_data['CardNumber'] = $this->request->post['opayo_card_number'];
			$payment_data['ExpiryDate'] = $this->request->post['opayo_card_expire_date_month'] . substr($this->request->post['opayo_card_expire_date_year'], 2);
			$payment_data['CardType'] = $this->request->post['opayo_card_type'];
			$payment_data['CV2'] = $this->request->post['opayo_card_cvv2_2'];
			$payment_data['COFUsage'] = 'FIRST';

			if (!empty($this->request->post['opayo_card_save'])) {
				$payment_data['CreateToken'] = '1';
				$payment_data['StoreToken'] = '1';
			}
		}

		$payment_data['BillingSurname'] = substr($order_info['payment_lastname'], 0, 20);
		$payment_data['BillingFirstnames'] = substr($order_info['payment_firstname'], 0, 20);
		$payment_data['BillingAddress1'] = substr($order_info['payment_address_1'], 0, 100);

		if ($order_info['payment_address_2']) {
			$payment_data['BillingAddress2'] = $order_info['payment_address_2'];
		}

		$payment_data['BillingCity'] = substr($order_info['payment_city'], 0, 40);
		$payment_data['BillingPostCode'] = substr($order_info['payment_postcode'], 0, 10);
		$payment_data['BillingCountry'] = $order_info['payment_iso_code_2'];

		if ($order_info['payment_iso_code_2'] == 'US') {
			$payment_data['BillingState'] = $order_info['payment_zone_code'];
		}

		$payment_data['BillingPhone'] = substr($order_info['telephone'], 0, 20);

		if ($this->cart->hasShipping()) {
			$payment_data['DeliverySurname'] = substr($order_info['shipping_lastname'], 0, 20);
			$payment_data['DeliveryFirstnames'] = substr($order_info['shipping_firstname'], 0, 20);
			$payment_data['DeliveryAddress1'] = substr($order_info['shipping_address_1'], 0, 100);

			if ($order_info['shipping_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}

			$payment_data['DeliveryCity'] = substr($order_info['shipping_city'], 0, 40);
			$payment_data['DeliveryPostCode'] = substr($order_info['shipping_postcode'], 0, 10);
			$payment_data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$payment_data['CustomerName'] = substr($order_info['firstname'] . ' ' . $order_info['lastname'], 0, 100);
			$payment_data['DeliveryPhone'] = substr($order_info['telephone'], 0, 20);
		} else {
			$payment_data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$payment_data['DeliverySurname'] = $order_info['payment_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['payment_city'];
			$payment_data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];
		}

		$order_products = $this->model_account_order->getProducts($this->session->data['order_id']);

		$cart_rows = 0;
		$str_basket = '';

		foreach ($order_products as $product) {
			$str_basket
					.= ":" . str_replace(":", " ", $product['name'] . " " . $product['model'])
					. ":" . $product['quantity']
					. ":" . $this->currency->format($product['price'], $order_info['currency_code'], false, false)
					. ":" . $this->currency->format($product['tax'], $order_info['currency_code'], false, false)
					. ":" . $this->currency->format(($product['price'] + $product['tax']), $order_info['currency_code'], false, false)
					. ":" . $this->currency->format(($product['price'] + $product['tax']) * $product['quantity'], $order_info['currency_code'], false, false);
			$cart_rows++;
		}

		$order_totals = $this->model_account_order->getTotals($this->session->data['order_id']);

		foreach ($order_totals as $total) {
			$str_basket .= ":" . str_replace(":", " ", $total['title']) . ":::::" . $this->currency->format($total['value'], $order_info['currency_code'], false, false);

			$cart_rows++;
		}

		$str_basket = $cart_rows . $str_basket;

		$payment_data['Basket'] = $str_basket;

		$payment_data['CustomerEMail'] = substr($order_info['email'], 0, 255);
		$payment_data['ClientIPAddress'] = $this->request->server['REMOTE_ADDR'];
		$payment_data['ChallengeWindowSize'] = '01';
		$payment_data['Apply3DSecure'] = '0';

		$payment_data['ThreeDSNotificationURL'] = str_replace('&amp;', '&', $this->url->link('extension/opayo/payment/opayo' . $this->separator . 'threeDSnotify', 'order_id=' . $this->session->data['order_id'] . '&language=' . $this->config->get('config_language')));

		$payment_data['InitiatedType'] = 'CIT';

		$browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);
		$browser_language = strtolower(reset($browser_languages));

		$payment_data['BrowserAcceptHeader'] = $this->request->server['HTTP_ACCEPT'];
		$payment_data['BrowserColorDepth'] = $this->request->post['BrowserColorDepth'];
		$payment_data['BrowserJavaEnabled'] = '1';
		$payment_data['BrowserJavascriptEnabled'] = '1';
		$payment_data['BrowserLanguage'] = $browser_language;
		$payment_data['BrowserScreenHeight'] = $this->request->post['BrowserScreenHeight'];
		$payment_data['BrowserScreenWidth'] = $this->request->post['BrowserScreenWidth'];
		$payment_data['BrowserTZ'] = $this->request->post['BrowserTZ'];
		$payment_data['BrowserUserAgent'] = $this->request->server['HTTP_USER_AGENT'];

		$response_data = $this->model_extension_opayo_payment_opayo->sendCurl($url, $payment_data);

		$json = [];

		if ($response_data['Status'] == '3DAUTH') {
			$json['ACSURL'] = $response_data['ACSURL'];
			$json['CReq'] = !empty($response_data['CReq']) ? $response_data['CReq'] : '';
			$json['ACSTransID'] = !empty($response_data['ACSTransID']) ? $response_data['ACSTransID'] : '';
			$json['DSTransID'] = !empty($response_data['DSTransID']) ? $response_data['DSTransID'] : '';
			$json['MD'] = !empty($response_data['MD']) ? $response_data['MD'] : '';
			$json['PaReq'] = !empty($response_data['PAReq']) ? $response_data['PAReq'] : '';

			$response_data['VPSTxId'] = !empty($response_data['VPSTxId']) ? $response_data['VPSTxId'] : '';
			$response_data['SecurityKey'] = !empty($response_data['SecurityKey']) ? $response_data['SecurityKey'] : '';
			$response_data['TxAuthNo'] = !empty($response_data['TxAuthNo']) ? $response_data['TxAuthNo'] : '';

			$card_id = '';

			if (!empty($payment_data['CreateToken']) && $this->customer->isLogged()) {
				$card_data = [];

				$card_data['customer_id'] = $this->customer->getId();
				$card_data['Token'] = '';
				$card_data['Last4Digits'] = substr(str_replace(' ', '', $payment_data['CardNumber']), -4, 4);
				$card_data['ExpiryDate'] = $this->request->post['opayo_card_expire_date_month'] . '/' . substr($this->request->post['opayo_card_expire_date_year'], 2);
				$card_data['CardType'] = $payment_data['CardType'];

				$card_id = $this->model_extension_opayo_payment_opayo->addCard($card_data);
			} elseif (!empty($payment_data['Token'])) {
				$card = $this->model_extension_opayo_payment_opayo->getCard(false, $payment_data['Token']);
				$card_id = $card['card_id'];
			}

			$this->model_extension_opayo_payment_opayo->addOrder($this->session->data['order_id'], $response_data, $payment_data, $card_id);

			$this->model_extension_opayo_payment_opayo->log('Response data', $response_data);
			$this->model_extension_opayo_payment_opayo->log('Payment data', $payment_data);
			$this->model_extension_opayo_payment_opayo->log('Order Id', $this->session->data['order_id']);

			$json['TermUrl'] = str_replace('&amp;', '&', $this->url->link('extension/opayo/payment/opayo' . $this->separator . 'threeDSnotify', 'order_id=' . $this->session->data['order_id'] . '&language=' . $this->config->get('config_language'), true));
		} elseif ($response_data['Status'] == 'OK' || $response_data['Status'] == 'AUTHENTICATED' || $response_data['Status'] == 'REGISTERED') {
			$message = '';

			if (isset($response_data['TxAuthNo'])) {
				$message .= 'TxAuthNo: ' . $response_data['TxAuthNo'] . "\n";
			} else {
				$response_data['TxAuthNo'] = '';
			}

			if (isset($response_data['AVSCV2'])) {
				$message .= 'AVSCV2: ' . $response_data['AVSCV2'] . "\n";
			}

			if (isset($response_data['AddressResult'])) {
				$message .= 'AddressResult: ' . $response_data['AddressResult'] . "\n";
			}

			if (isset($response_data['PostCodeResult'])) {
				$message .= 'PostCodeResult: ' . $response_data['PostCodeResult'] . "\n";
			}

			if (isset($response_data['CV2Result'])) {
				$message .= 'CV2Result: ' . $response_data['CV2Result'] . "\n";
			}

			if (isset($response_data['3DSecureStatus'])) {
				$message .= '3DSecureStatus: ' . $response_data['3DSecureStatus'] . "\n";
			}

			if (isset($response_data['CAVV'])) {
				$message .= 'CAVV: ' . $response_data['CAVV'] . "\n";
			}

			$card_id = '';

			if (!empty($payment_data['CreateToken']) && !empty($response_data['Token']) && $this->customer->isLogged()) {
				$card_data = [];

				$card_data['customer_id'] = $this->customer->getId();
				$card_data['Token'] = $response_data['Token'];
				$card_data['Last4Digits'] = substr(str_replace(' ', '', $payment_data['CardNumber']), -4, 4);
				$card_data['ExpiryDate'] = $this->request->post['opayo_card_expire_date_month'] . '/' . substr($this->request->post['opayo_card_expire_date_year'], 2);
				$card_data['CardType'] = $payment_data['CardType'];

				$card_id = $this->model_extension_opayo_payment_opayo->addCard($card_data);
			} elseif (!empty($payment_data['Token'])) {
				$card = $this->model_extension_opayo_payment_opayo->getCard(false, $payment_data['Token']);
				$card_id = $card['card_id'];
			}

			$opayo_order_id = $this->model_extension_opayo_payment_opayo->addOrder($order_info['order_id'], $response_data, $payment_data, $card_id);

			$this->model_extension_opayo_payment_opayo->log('Response data', $response_data);
			$this->model_extension_opayo_payment_opayo->log('Payment data', $payment_data);
			$this->model_extension_opayo_payment_opayo->log('Order Id', $this->session->data['order_id']);

			$this->model_extension_opayo_payment_opayo->addOrderTransaction($opayo_order_id, $setting['general']['transaction_method'], $order_info);

			$this->model_checkout_order->addHistory($this->session->data['order_id'], $setting['general']['order_status_id'], $message, false);

			if ($setting['general']['transaction_method'] == 'PAYMENT') {
				$this->load->model('checkout/subscription');

				$order_products = $this->model_checkout_order->getProducts($this->session->data['order_id']);

				// Loop through any products that are subscription items
				foreach ($order_products as $order_product) {
					$order_subscription = $this->model_checkout_subscription->getSubscriptionByOrderProductId($this->session->data['order_id'], $order_product['order_product_id']);

					if ($order_subscription && $order_subscription['order_product_id'] == $order_product['order_product_id']) {
						$order_subscription['subscription'] = $order_subscription;
						$order_subscription['subscription']['name'] = $order_product['name'];

						$this->model_extension_opayo_payment_opayo->subscriptionPayment($order_subscription, $payment_data['VendorTxCode']);
					}
				}
			}

			$json['redirect'] = $this->url->link('checkout/success', 'language=' . $this->config->get('config_language'));
		} else {
			$json['error'] = $response_data['Status'] . ': ' . $response_data['StatusDetail'];

			$this->model_extension_opayo_payment_opayo->log('Response data', $json['error']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * threeDSnotify
	 *
	 * @return void
	 */
	public function threeDSnotify(): void {
		if (isset($this->request->get['order_id'])) {
			$this->load->language('extension/opayo/payment/opayo');

			$this->load->model('extension/opayo/payment/opayo');
			$this->load->model('checkout/order');

			// Setting
			$_config = new \Opencart\System\Engine\Config();
			$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
			$_config->load('opayo');

			$config_setting = $_config->get('opayo_setting');

			$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

			$opayo_order_info = $this->model_extension_opayo_payment_opayo->getOrder($this->request->get['order_id']);

			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/direct3dcallback.vsp';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/direct3dcallback.vsp';
			}

			$this->request->post['VPSTxId'] = $opayo_order_info['vps_tx_id'];

			$response_data = $this->model_extension_opayo_payment_opayo->sendCurl($url, $this->request->post);

			$this->model_extension_opayo_payment_opayo->log('Response Data', $response_data);

			if ($response_data['Status'] == 'OK' || $response_data['Status'] == 'AUTHENTICATED' || $response_data['Status'] == 'REGISTERED') {
				$message = '';

				if (isset($response_data['TxAuthNo'])) {
					$message .= 'TxAuthNo: ' . $response_data['TxAuthNo'] . "\n";
				} else {
					$response_data['TxAuthNo'] = '';
				}

				if (isset($response_data['AVSCV2'])) {
					$message .= 'AVSCV2: ' . $response_data['AVSCV2'] . "\n";
				}

				if (isset($response_data['AddressResult'])) {
					$message .= 'AddressResult: ' . $response_data['AddressResult'] . "\n";
				}

				if (isset($response_data['PostCodeResult'])) {
					$message .= 'PostCodeResult: ' . $response_data['PostCodeResult'] . "\n";
				}

				if (isset($response_data['CV2Result'])) {
					$message .= 'CV2Result: ' . $response_data['CV2Result'] . "\n";
				}

				if (isset($response_data['3DSecureStatus'])) {
					$message .= '3DSecureStatus: ' . $response_data['3DSecureStatus'] . "\n";
				}

				if (isset($response_data['CAVV'])) {
					$message .= 'CAVV: ' . $response_data['CAVV'] . "\n";
				}

				$order_info = $this->model_checkout_order->getOrder($this->request->get['order_id']);

				$this->model_extension_opayo_payment_opayo->log('Order Info', $order_info);
				$this->model_extension_opayo_payment_opayo->log('opayo Order Info', $opayo_order_info);

				$this->model_extension_opayo_payment_opayo->updateOrder($order_info, $response_data);
				$this->model_extension_opayo_payment_opayo->addOrderTransaction($opayo_order_info['opayo_order_id'], $this->config->get('payment_opayo_transaction'), $order_info);

				$this->model_checkout_order->addHistory($this->request->get['order_id'], $setting['general']['order_status_id'], $message, false);

				if (!empty($response_data['Token']) && $this->customer->isLogged()) {
					$this->model_extension_opayo_payment_opayo->updateCard($opayo_order_info['card_id'], $response_data['Token']);
				} else {
					$this->model_extension_opayo_payment_opayo->deleteCard($opayo_order_info['card_id']);
				}

				if ($setting['general']['transaction_method'] == 'PAYMENT') {
					$payment_data = [];

					$this->load->model('checkout/subscription');

					$order_products = $this->model_checkout_order->getProducts($this->session->data['order_id']);

					$payment_data['VendorTxCode'] = $this->session->data['order_id'] . 'SD' . date('YmdHis') . mt_rand(1, 999);

					// Loop through any products that are subscription items
					foreach ($order_products as $order_product) {
						$order_subscription = $this->model_checkout_subscription->getSubscriptionByOrderProductId($this->session->data['order_id'], $order_product['order_product_id']);

						if ($order_subscription && $order_subscription['order_product_id'] == $order_product['order_product_id']) {
							$order_subscription['subscription'] = $order_subscription;
							$order_subscription['subscription']['name'] = $order_product['name'];

							$this->model_extension_opayo_payment_opayo->subscriptionPayment($order_subscription, $payment_data['VendorTxCode']);
						}
					}
				}

				$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
			} else {
				$this->session->data['error'] = $response_data['StatusDetail'];

				$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
			}
		} else {
			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}
	}

	/**
	 * deleteCard
	 *
	 * @return void
	 */
	public function deleteCard(): void {
		$this->load->language('extension/opayo/payment/opayo');

		$json = [];

		$this->load->model('extension/opayo/payment/opayo');

		// Setting
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$config_setting = $_config->get('opayo_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

		$card = $this->model_extension_opayo_payment_opayo->getCard(false, $this->request->post['opayo_card_token']);

		if (!empty($card['token'])) {
			if ($setting['general']['environment'] == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/removetoken.vsp';
			} elseif ($setting['general']['environment'] == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/removetoken.vsp';
			}

			$payment_data = [];

			$payment_data['VPSProtocol'] = '4.00';
			$payment_data['Vendor'] = $this->config->get('payment_opayo_vendor');
			$payment_data['TxType'] = 'REMOVETOKEN';
			$payment_data['Token'] = $card['token'];

			$response_data = $this->model_extension_opayo_payment_opayo->sendCurl($url, $payment_data);

			if ($response_data['Status'] == 'OK') {
				$this->model_extension_opayo_payment_opayo->deleteCard($card['card_id']);

				$this->session->data['success'] = $this->language->get('text_success_card');

				$json['success'] = true;
			} else {
				$json['error'] = $this->language->get('text_fail_card');
			}
		} else {
			$json['error'] = $this->language->get('text_fail_card');
		}

		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Cron
	 *
	 * @return void
	 */
	public function cron(): void {
		// Setting
		$_config = new \Opencart\System\Engine\Config();
		$_config->addPath(DIR_EXTENSION . 'opayo/system/config/');
		$_config->load('opayo');

		$config_setting = $_config->get('opayo_setting');

		$setting = array_replace_recursive((array)$config_setting, (array)$this->config->get('payment_opayo_setting'));

		if (isset($this->request->get['token']) && hash_equals($setting['cron']['token'], $this->request->get['token'])) {
			$this->load->model('extension/opayo/payment/opayo');

			$orders = $this->model_extension_opayo_payment_opayo->cronPayment();

			$this->model_extension_opayo_payment_opayo->updateCronRunTime();

			$this->model_extension_opayo_payment_opayo->log('Repeat Orders', $orders);
		}
	}
}
