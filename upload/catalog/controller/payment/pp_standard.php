<?php
class ControllerPaymentPPStandard extends Controller {

	private $error;
	private $order_info;

	protected function index() {
    	$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		if (!$this->config->get('pp_standard_test')) {
    		$this->data['action'] = 'https://www.paypal.com/cgi-bin/webscr';
  		} else {
			$this->data['action'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		$this->load->model('checkout/order');

		$this->order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// Check for supported currency, otherwise convert to USD.
		$currencies = array('AUD','CAD','EUR','GBP','JPY','USD','NZD','CHF','HKD','SGD','SEK','DKK','PLN','NOK','HUF','CZK','ILS','MXN','MYR','BRL','PHP','PLN','TWD','THB');
		if (in_array($this->order_info['currency'], $currencies)) {
			$currency = $this->order_info['currency'];
		} else {
			$currency = 'USD';
		}

		// Get all totals
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$this->load->model('checkout/extension');

		$sort_order = array();

		$results = $this->model_checkout_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['key'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		$discount_total = 0;
		foreach ($results as $result) {
			$this->load->model('total/' . $result['key']);
			$old_total = $total;
			$this->{'model_total_' . $result['key']}->getTotal($total_data, $total, $taxes);

			if ($total < $old_total) {
				$discount_total += $old_total - $total;
			}
		}
		$total = $this->currency->format($total, $currency, FALSE, FALSE);
		$shipping_total = 0;
		// Create form fields
		$this->fields = array();
		$this->data['fields']['cmd'] = '_cart';
		$this->data['fields']['upload'] = '1';
		if ($this->cart->hasShipping()) {
			$shipping_total = $this->currency->format($this->session->data['shipping_method']['cost'], $currency, FALSE, FALSE);
			$this->data['fields']['shipping_1'] = $shipping_total;
		}
		$tax_total = 0;
		foreach ($taxes as $key => $value) {
				$tax_total += $this->currency->format($value, $currency, FALSE, FALSE);
		}
		//$this->data['fields']['tax'] = $tax_total;
		$this->data['fields']['tax_cart'] = $tax_total;

		$product_total = 0;
		$i = 1;
		foreach ($this->cart->getProducts() as $product) {
			$price = $this->currency->format($product['price'], $currency, FALSE, FALSE);
	        $this->data['fields']['item_number_' . $i . ''] = $product['model'];
            $this->data['fields']['item_name_' . $i . ''] = $product['name'];
            $this->data['fields']['amount_' . $i . ''] = $price;
            $this->data['fields']['quantity_' . $i . ''] = $product['quantity'];
	        $this->data['fields']['weight_' . $i . ''] = $product['weight'];
	        $product_total += ($price * $product['quantity']);
            if (!empty($product['option'])) {
                $x=0;
                foreach ($product['option'] as $res) {
                    $this->data['fields']['on' . $x . '_' . $i . '']=$res['name'];
                    $this->data['fields']['os' . $x . '_' . $i . '']=$res['value'];
                    $x++;
                }
            }
            $i++;
        }

		$this->data['fields']['discount_amount_cart'] = number_format($discount_total, 2, '.', '');

		$remaining_total = $total - $product_total - $tax_total - $shipping_total + $discount_total;

		if ($remaining_total > 0) {
			$this->data['fields']['handling_cart'] = number_format(abs($remaining_total), 2, '.', '');
		}

		$this->data['fields']['business'] = $this->config->get('pp_standard_email');
		$this->data['fields']['currency_code'] = $currency;
		$this->data['fields']['first_name'] = html_entity_decode($this->order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['last_name'] = html_entity_decode($this->order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['address1'] = html_entity_decode($this->order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['address2'] = html_entity_decode($this->order_info['payment_address_2'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['city'] = html_entity_decode($this->order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['zip'] = html_entity_decode($this->order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['country'] = $this->order_info['payment_iso_code_2'];
		$this->data['fields']['email'] = $this->order_info['email'];
		$this->data['fields']['invoice'] = $this->session->data['order_id'] . ' - ' . html_entity_decode($this->order_info['payment_firstname'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($this->order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$this->data['fields']['lc'] = $this->session->data['language'];
		$this->data['fields']['rm'] = '2';

		if (!$this->config->get('pp_standard_transaction')) {
			$this->data['fields']['paymentaction'] = 'authorization';
		} else {
			$this->data['fields']['paymentaction'] = 'sale';
		}

		//$this->data['fields']['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';
		$this->data['fields']['return'] = HTTPS_SERVER . 'index.php?route=payment/pp_standard/pdt';
		$this->data['fields']['notify_url'] = HTTP_SERVER . 'index.php?route=payment/pp_standard/callback';
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['fields']['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['fields']['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		$this->data['fields']['custom'] = $encryption->encrypt($this->session->data['order_id']);

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->data['testmode'] = $this->config->get('pp_standard_test');

		$this->data['text_testmode'] = $this->language->get('text_testmode');

		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/pp_standard.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/pp_standard.tpl';
		} else {
			$this->template = 'default/template/payment/pp_standard.tpl';
		}

		$this->render();
	}

	public function confirm() {
		if ($this->config->get('pp_standard_ajax')) {
			$this->load->model('checkout/order');
			$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
		}
	}

	public function pdt() {

		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}

		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: PDT INIT <-- $g_msg");
		}

        if (!isset($this->request->get['tx']) || $this->config->get('pp_standard_pdt_token') == '') {
			$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/success');
		}

        $this->load->language('payment/pp_standard');

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		if (isset($this->request->get['cm'])) {
			$order_id = $encryption->decrypt($this->request->get['cm']);
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$this->order_info = $this->model_checkout_order->getOrder($order_id);

		if ($this->order_info) {
			if ($this->order_info['order_status_id'] != 0) {
			//if ($this->order_info['order_status_id'] == $this->config->get('pp_standard_order_status_id')) {
				$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/success');
			}
		}

         // Paypal possible values for payment_status
		$success_status = array('Completed', 'Pending', 'In-Progress', 'Processed');
		$failed_status = array('Denied', 'Expired', 'Failed');

        // read the post from PayPal system and add 'cmd'
        $request = 'cmd=_notify-synch';
        $request .= '&tx=' . $this->request->get['tx'];
        $request .= '&at=' . $this->config->get('pp_standard_pdt_token');

        if (!$this->config->get('pp_standard_test')) {
			$url = 'https://www.paypal.com/cgi-bin/webscr';
		} else {
			$url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}

		if (ini_get('allow_url_fopen')) {
			$response = file_get_contents($url . '?' . $request);
		} else {		
			$response = file_get_contents_curl($url . '?' . $request);
		}
		
		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: PDT REQ  --> $request");
			$this->log->write("PP_STANDARD :: PDT RESP <-- " . str_replace("\n", "&", $response));
		}
		
		$resp_array = array();
		
		$verified = false;
		
		if ($response) {
				
			$lines = explode("\n", $response);
			if ($lines[0] == 'SUCCESS') {
				for ($i=1; $i<(count($lines)-1); $i++){
					list($key,$val) = explode("=", $lines[$i]);
					$resp_array[urldecode($key)] = urldecode($val);
				}
			}
		}
		
		if (isset($resp_array['memo'])) {
			$memo = $resp_array['memo'];
		} else {
			$memo = '';
		}
		
		if (!$this->validate($resp_array)) {
			if ($this->order_info['order_status_id'] == '0') {
				$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $memo . "\r\n\r\n" . $this->error);
			} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
				$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'),  $this->error, FALSE);
			}
			mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), $this->error . "\r\n\r\n" . str_replace("&", "\n", $g_msg));
		}

		if (strcmp($lines[0], 'SUCCESS') == 0) {
			$verified = true;
		}
		
		$this->checkPaymentStatus($resp_array, $verified);	
		
	}

	public function callback() {

		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}

		if ($this->config->get('pp_standard_debug')) {
			$this->log->write("PP_STANDARD :: IPN INIT <-- $p_msg");
		}

		if (isset($this->request->post['memo'])) {
			$memo = $this->request->post['memo'];
		} else {
			$memo = '';
		}

		$this->load->language('payment/pp_standard');

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		if (isset($this->request->post['custom'])) {
			$order_id = $encryption->decrypt($this->request->post['custom']);
		} else {
			$order_id = 0;
		}

		$this->load->model('checkout/order');

		$this->order_info = $this->model_checkout_order->getOrder($order_id);

		if ($this->order_info) {
			$request = 'cmd=_notify-validate';

			$get_magic_quotes_exists = false;
	        if (function_exists('get_magic_quotes_gpc')) {
	            $get_magic_quotes_exists = true;
	        }

			foreach ($this->request->post as $key => $value) {
				if ($get_magic_quotes_exists && get_magic_quotes_gpc() == 1) {
					//$request .= '&' . $key . '=' . urlencode(stripslashes($value));
					$request .= '&' . $key . '=' . urlencode($value);
				} else {
					$request .= '&' . $key . '=' . urlencode($value);
					//$request .= '&' . $key . '=' . urlencode(stripslashes($value));
				}
			}

			if (!$this->config->get('pp_standard_test')) {
				$ch = curl_init('https://www.paypal.com/cgi-bin/webscr');
			} else {
				$ch = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
			}

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($request)));
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

			$response = curl_exec($ch);

			curl_close($ch);

			if ($this->config->get('pp_standard_debug')) {
				$this->log->write("PP_STANDARD :: IPN REQ  --> $request");
				$this->log->write("PP_STANDARD :: IPN RESP <-- $response");
			}

			if (!$this->validate($this->request->post)) {
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $memo . "\r\n\r\n" . $this->error);
				} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'),  $this->error, FALSE);
				}
				mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), $this->error . "\r\n\r\n" . str_replace("&", "\n", $p_msg));
			}

			$verified = false;
			if (strcmp($response, 'VERIFIED') == 0) {
				$verified = true;
			}

			$this->checkPaymentStatus($this->request->post, $verified);
		}
	}

	private function checkPaymentStatus($data, $verified) {

		if (isset($this->request->post)) {
        	$p_msg = "DEBUG POST VARS::"; foreach($this->request->post as $k=>$v) { $p_msg .= $k."=".$v."&"; }
		}
		if (isset($this->request->get)) {
        	$g_msg = "DEBUG GET VARS::"; foreach($this->request->get as $k=>$v) { $g_msg .= $k."=".$v."&"; }
		}

		if (isset($this->order_info['order_id'])) {
			$order_id = $this->order_info['order_id'];
		} else {
			$order_id = 0;
		}
				
		switch($data['payment_status']){
			case 'Completed':
				if ($verified) {
					if ($this->order_info['order_status_id'] == '0') {
						$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status']);
					} elseif (isset($data['payment_type']) && $data['payment_type'] == 'echeck') {
						$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status'], TRUE);
					} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
						$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id'), $data['payment_status'], FALSE);
					}
				} else {
					if ($this->order_info['order_status_id'] == '0') {
						$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['payment_status']);
					} elseif ($this->order_info['order_status_id'] != $this->config->get('pp_standard_order_status_id')) {
						$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['payment_status'], FALSE);
					}
					if (!isset($data['payment_type']) || (isset($data['payment_type']) && $data['payment_type'] != 'echeck')) {
						mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), ($this->language->get('error_verify') . "\r\n\r\n" . $p_msg . "\r\n\r\n" . $g_msg));
					}
				}
				break;
			case 'Canceled_Reversal':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_canceled_reversal'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_canceled_reversal'), $data['reason_code'], FALSE);
				}
				break;
			case 'Denied':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_denied'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_denied'), $data['reason_code'], FALSE);
				}
				break;
			case 'Failed':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_failed'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_failed'), $data['reason_code'], FALSE);
				}
				break;
			case 'Pending':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['pending_reason']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_pending'), $data['pending_reason'], TRUE);
				}
				break;
			case 'Refunded':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_refunded'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_refunded'), $data['reason_code'], FALSE);
				}
				break;
			case 'Reversed':
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_reversed'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_reversed'), $data['reason_code'], FALSE);
				}
				break;
			default:
				if ($this->order_info['order_status_id'] == '0') {
					$this->model_checkout_order->confirm($order_id, $this->config->get('pp_standard_order_status_id_unspecified'), $data['reason_code']);
				} else {
					$this->model_checkout_order->update($order_id, $this->config->get('pp_standard_order_status_id_unspecified'), $data['reason_code'], FALSE);
				}
				break;
		}

		if ($data['payment_status'] != 'Completed') {
			if (!isset($data['payment_type']) || (isset($data['payment_type']) && $data['payment_type'] == 'echeck')) {
				mail($this->config->get('config_email'), sprintf($this->language->get('text_attn_email'), $order_id), ($this->language->get('error_non_complete') . "\r\n\r\n" . $p_msg . "\r\n\r\n" . $g_msg));
			}
		}

		$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/success');
	}
	
	function file_get_contents_curl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		
		$data = curl_exec($ch);
		
		curl_close($ch);
		
		return $data;
	}
	  
	private function validate($data = array()) {
		$this->load->language('payment/pp_standard');
		
		// verify there was some response
		if (empty($data)) {
			$this->error = $this->language->get('error_no_data');
		}
		
		// verify totals match
		if (isset($data['cc'])) { // PDT
			$currency = $data['cc'];
		} elseif (isset($data['mc_currency'])) { // IPN
			$currency = $data['mc_currency'];
		} else { // Default
			$currency = $this->order_info['currency'];
		}
		
		if (isset($data['payment_gross']) && $data['payment_gross']) {
			$amount = $data['payment_gross'];
		} elseif (isset($data['mc_gross']) && $data['mc_gross']) {
			$amount = $data['mc_gross'];
		}
		
        if (isset($data['payment_status']) && $data['payment_status'] != 'Refunded' && ((float)floor($amount) != (float)floor($this->currency->format($this->order_info['total'], $currency, False, False)))) {
			$this->error = sprintf($this->language->get('error_amount_mismatch'), $amount, $this->order_info['total']);
		}

		// verify paypal email matches
		if (isset($data['receiver_email']) && strtolower($data['receiver_email']) != strtolower($this->config->get('pp_standard_email'))) {
			if (isset($data['business']) && strtolower($data['business']) != strtolower($this->config->get('pp_standard_email'))) {
				$this->error = $this->language->get('error_email_mismatch');
			}
		}

    	if (!$this->error) {
			return TRUE;
    	} else {
    		$this->log->write("PP_STANDARD :: VALIDATION FAILED : $this->error");
      		return FALSE;
    	}
	}

}
?>