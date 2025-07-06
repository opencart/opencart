<?php
class ControllerExtensionPaymentCardinity extends Controller
{

	private $external_mode_only;

	public function index()
	{


		$this->load->language('extension/payment/cardinity');

		//if using old version
		if (version_compare(phpversion(), '7.2.5', '<') == true) {
			$this->external_mode_only = true;
		}


		/**
		 * Check if external payment option is available,
		 * if so, then proceed with external checkout options.
		 */
		if ($this->config->get('payment_cardinity_external') == 1) {

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);


			if ($order_info) {
				$this->load->model('extension/payment/cardinity');
				$data['amount'] = number_format($order_info['total'], 2, '.', '');
				$data['currency'] = $order_info['currency_code'];
				$data['country'] = $order_info['shipping_iso_code_2'];
				//our gateway wont accept id less than 3 digit
				$orderId = $this->session->data['order_id'];
				if ($orderId < 100) {
					$formattedOrderId = str_pad($orderId, 3, '0', STR_PAD_LEFT);
				} else {
					$formattedOrderId = $orderId;
				}
				$data['order_id'] = $formattedOrderId; //$this->session->data['order_id'];
				$data['description'] = $this->session->getId(); //  'OC' . $this->session->data['order_id'];
				$data['return_url'] = $this->url->link('extension/payment/cardinity/externalPaymentCallback', '', true);
                $data['notification_url'] = $this->url->link('extension/payment/cardinity/externalPaymentNotification', '', true);

                if (!empty($order_info['email'])){
                    $data['email_address'] =  $order_info['email'];
                }
                if (!empty($order_info['telephone'])){
                    $data['mobile_phone_number'] =  $order_info['telephone'];
                }

				$attributes = $this->model_extension_payment_cardinity->createExternalPayment($this->config->get('payment_cardinity_project_key'), $this->config->get('payment_cardinity_project_secret'), $data);

				//these two are for website not for api
				$attributes['button_confirm'] = $this->language->get('button_confirm');
				$attributes['text_loading'] = $this->language->get('text_loading');

				//for external callback
				$this->setSession();

				return $this->load->view('extension/payment/cardinity_external', $attributes);
			}
			return $this->load->view('extension/payment/cardinity_external_error');
		}

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => date('F', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['years'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['years'][] = array(
				'text'  => date('Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => date('Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		//these two are for website not for api
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');

        return $this->load->view('extension/payment/cardinity', $data);
	}

	private function setSession()
	{

		$rawSessionData = $this->session->data; // $_SESSION[$this->session->getId()];

		//$rawSessionData['current_order_id'] = $order_id;
		////data serialized
		$serializedSession = json_encode($rawSessionData);

		//add signature to raw data, (signature generated from serialized session)
		$rawSessionData['signature'] = hash_hmac('sha256', $serializedSession, $this->config->get('payment_cardinity_project_secret'));

		//new datra *(generated from serialized session + signature)
		$serializedSessionWithSignature  = json_encode($rawSessionData);

		//$sessionDataValue = base64_encode($serializedSessionWithSignature);
		$sessionDataValue = $serializedSessionWithSignature;

		try {
			$this->model_extension_payment_cardinity->storeSession(array(
				'session_id' => $this->session->getId(),
				'session_data' => $sessionDataValue,
			));
        } catch (Exception $e) {
			$this->testLog("db error" . $e->getMessage());
		}

	}

	private function getSession($sessionId)
	{

		$this->load->model('extension/payment/cardinity');

		$sessionDataOnDB = $this->model_extension_payment_cardinity->fetchSession($sessionId);
		//$sessionDataOnDB = json_decode(base64_decode($sessionDataOnDB['session_data']), true);
		$sessionDataOnDB = json_decode(($sessionDataOnDB['session_data']), true);

		//pluck target hash
		$targetHash = $sessionDataOnDB['signature'];
		unset($sessionDataOnDB['signature']);

		//generate hash
		$serializedSession = json_encode($sessionDataOnDB);
		$foundHash = hash_hmac('sha256', $serializedSession, $this->config->get('payment_cardinity_project_secret'));

		$this->session->data = $sessionDataOnDB;

        $this->testLog("Getting session ID ".$sessionId);
        $this->testLog("Getting session DATA ".substr($serializedSession, 0, 10).".....".substr($serializedSession, -10));

        return $this->session->data['order_id']; //$sessionDataOnDB['order_id'];

	}

    private function finishProcessExternalPayment($redirect = true){
        $this->load->language('extension/payment/cardinity');
        $this->load->model('extension/payment/cardinity');
        $this->load->model('checkout/order');

        //restore session based on sessionId from description
        $this->session->start($_POST['description']);
        $this->getSession($_POST['description']);

        $message = '';
        ksort($_POST);

        foreach ($_POST as $key => $value) {
            if ($key == 'signature') continue;
            $message .= $key . $value;
        }

        error_reporting(null);
        $signature = hash_hmac('sha256', $message, $this->config->get('payment_cardinity_project_secret'));
        error_reporting(E_ALL);

        $orderInfo = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($signature == $_POST['signature'] && $_POST['status'] == 'approved') {
            if ($orderInfo['order_status_id'] != $this->config->get('payment_cardinity_order_status_id')){
                $this->model_extension_payment_cardinity->addOrder(array(
                    'order_id'   => $_POST['order_id'],
                    'payment_id' => $_POST['id'],
                ));
                $this->model_extension_payment_cardinity->updateOrder(array(
                    'payment_status' => 'approved_external',
                    'payment_id' => $_POST['id'],
                ));

                $this->logTransaction(array(
                    'orderId' => $_POST['order_id'],
                    'transactionId' =>  $_POST['id'],
                    '3dsVersion' => 'unknown (external)',
                    'amount' => $_POST['amount'] . " " . $_POST['currency'],
                    'status' => 'approved'
                ));

                $this->finalizeOrder($_POST);
            }
            if($redirect){
                $this->response->redirect($this->url->link('checkout/success', '', true));
            }else{
                $this->model_extension_payment_cardinity->log("Payment finalized by notification");
            }
        } else {
            $this->model_extension_payment_cardinity->addOrder(array(
                'order_id'   => $_POST['order_id'],
                'payment_id' => $_POST['id'],
            ));
            $this->model_extension_payment_cardinity->updateOrder(array(
                'payment_status' => 'failed_external',
                'payment_id' => $_POST['id'],
            ));

            $this->failedOrder("Card was declined", $this->language->get("error_payment_declined"));

            if($redirect){
                $this->response->redirect($this->url->link('checkout/checkout', '', true));
            }
        }
    }
	public function externalPaymentCallback()
	{
        $this->finishProcessExternalPayment(true);
	}

    public function externalPaymentNotification()
    {
        $this->finishProcessExternalPayment(false);
        echo json_encode([
            "message" => "Oc3 received notification",
            "website" => $this->config->get('config_url'),
        ]);
    }

	public function send()
	{
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/cardinity');

		$this->load->language('extension/payment/cardinity');

		$json = array();

		$json['error'] = $json['success'] = $json['3ds'] = '';

		$payment = false;

		$error = $this->validate();

		if (!$error) {
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			$order_id = $order_info['order_id'];
			if ($order_id < 100) {
				$order_id = str_pad($order_id, 3, '0', STR_PAD_LEFT);
			} else {
				$order_id = $order_id;
			}

			if (!empty($order_info['payment_iso_code_2'])) {
				$order_country = $order_info['payment_iso_code_2'];
			} else {
				$order_country = $order_info['shipping_iso_code_2'];
			}

            $cardholder_info = [];
            if (!empty($order_info['email'])){
               $cardholder_info['email_address'] =  $order_info['email'];
            }
            if (!empty($order_info['telephone'])){
                $cardholder_info['mobile_phone_number'] =  $order_info['telephone'];
            }

            $payment_data = array(
				'amount'			 => (float)$this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false),
				'currency'			 => $order_info['currency_code'],
				'order_id'			 => $order_id,
				'description' 		 => $this->session->getId(),
				'country'            => $order_country,
				'payment_method'     => 'card',
				'payment_instrument' => array(
					'pan'		=> preg_replace('!\s+!', '', $this->request->post['pan']),
					'exp_year'	=> (int)$this->request->post['exp_year'],
					'exp_month' => (int)$this->request->post['exp_month'],
					'cvc'		=> $this->request->post['cvc'],
					'holder'	=> $this->request->post['holder']
				),
				'threeds2_data' =>  [
					"notification_url" => $this->url->link('extension/payment/cardinity/threeDSecureCallbackV2', '', true),
					"browser_info" => [
						"accept_header" => "text/html",
						"browser_language" => $this->request->post['browser_language'],
						"screen_width" => (int)$this->request->post['screen_width'],
						"screen_height" => (int)$this->request->post['screen_height'],
						'challenge_window_size' => "full-screen",
						"user_agent" => $_SERVER['HTTP_USER_AGENT'],
						"color_depth" => (int)$this->request->post['color_depth'],
						"time_zone" => (int)$this->request->post['time_zone']
					],
                    'cardholder_info' => $cardholder_info
				],
			);



			//check if payment repeat
			$cardinity_order = $this->model_extension_payment_cardinity->getOrder($this->session->data['order_id']);
			$paid_already = false;

			if ($cardinity_order &&  $cardinity_order['payment_id']  && $cardinity_order['payment_status'] !='failed_3dsv1' ) {

				$this->testLog("repeating order");
				//payment was already created				
				try {
					$payment_exist = $this->model_extension_payment_cardinity->getPayment($this->config->get('payment_cardinity_key'), $this->config->get('payment_cardinity_secret'), $cardinity_order['payment_id']);

					if ($payment_exist->getStatus() == 'approved') {
						$paid_already = true;
					}
				} catch (Cardinity\Exception\Declined $exception) {
					$this->failedOrder($this->language->get('error_payment_declined'), $this->language->get('error_payment_declined'));

					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				} catch (Exception $exception) {
					$this->failedOrder();

					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				}
			}


			if ($paid_already && $payment_exist) {
				$this->testLog("repeating payed");
				$payment = $payment_exist;
			} else {
				try {
					$payment = $this->model_extension_payment_cardinity->createPayment($this->config->get('payment_cardinity_key'), $this->config->get('payment_cardinity_secret'), $payment_data);
				} catch (Cardinity\Exception\Declined $exception) {
					$this->failedOrder($this->language->get('error_payment_declined'), $this->language->get('error_payment_declined'));

					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				} catch (Exception $exception) {
					$this->failedOrder();

					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				}
			}


			$successful_order_statuses = array(
				'approved',
				'pending'
			);

			if ($payment) {

				if (!in_array($payment->getStatus(), $successful_order_statuses)) {
					$this->failedOrder($payment->getStatus());

					$json['redirect'] = $this->url->link('checkout/checkout', '', true);
				} else {

					$this->model_extension_payment_cardinity->addOrder(array(
						'order_id'   => $this->session->data['order_id'],
						'payment_id' => $payment->getId()
					));

					if ($payment->getStatus() == 'pending') {

						$this->testLog("is v2 " . $payment->isThreedsV2());
						//exit();

						if ($payment->isThreedsV2() && !$payment->isThreedsV1()) {
							//3dsv2
							$authorization_information = $payment->getThreeDS2Data();

							//setSession
							$this->setSession();

							$this->testLog("Session set to database");


							$json['3dsv2'] = array(
								'acs_url'   => $authorization_information->getAcsUrl(),
								'creq'   	=> $authorization_information->getCreq(),
								'session_id' => $this->session->getId(),
							);

							$this->model_extension_payment_cardinity->updateOrder(array(
								'payment_status'   => 'pending_3dsv2',
								'payment_id' => $payment->getId()
							));
						} else {
							//3ds
							$authorization_information = $payment->getAuthorizationInformation();

							//setSession
							$this->setSession();
							$this->testLog("Session set to database");


							$json['3ds'] = array(
								'url'     => $authorization_information->getUrl(),
								'PaReq'   => $authorization_information->getData(),
								'TermUrl' => $this->url->link('extension/payment/cardinity/threeDSecureCallback', '', true),
								'session_id' => $this->session->getId(),
							);

							$this->model_extension_payment_cardinity->updateOrder(array(
								'payment_status'   => 'pending_3dsv1',
								'payment_id' => $payment->getId()
							));
						}
					} elseif ($payment->getStatus() == 'approved') {

						$this->model_extension_payment_cardinity->updateOrder(array(
							'payment_status'   => 'approved_non3ds',
							'payment_id' => $payment->getId()
						));

						$this->logTransaction(array(
							'orderId' => $this->session->data['order_id'],
							'transactionId' =>  $payment->getId(),
							'3dsVersion' => 'none',
							'amount' => $payment->getAmount() . " " . $payment->getCurrency(),
							'status' => 'approved'
						));


						$this->finalizeOrder($payment);

						$json['redirect'] = $this->url->link('checkout/success', '', true);
					}
				}
			}
		} else {
			$json['error'] = $error;
		}

		$this->testLog("attempting response");

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function threeDSecureForm()
	{
		$this->load->model('extension/payment/cardinity');

		$this->load->language('extension/payment/cardinity');

		$data['url'] = $this->request->post['url'];
		$data['PaReq'] = $this->request->post['PaReq'];
		$data['TermUrl'] = $this->request->post['TermUrl'];
		$data['MD'] = $this->request->post['session_id'];
		$data['success'] = true;

		$this->response->setOutput($this->load->view('extension/payment/cardinity_3ds', $data));
	}

	public function threeDSecureCallback()
	{

		//restore session based on sessionId from MD
		$this->session->start($_POST['MD']);
		$currentOrderId = $this->getSession($_POST['MD']);

		$this->load->model('extension/payment/cardinity');
		$this->load->language('extension/payment/cardinity');

		$success = false;

		$error = '';

		$order = $this->model_extension_payment_cardinity->getOrder($currentOrderId);

		if ($order && $order['payment_id']) {
			$payment = $this->model_extension_payment_cardinity->finalizePayment($this->config->get('payment_cardinity_key'), $this->config->get('payment_cardinity_secret'), $order['payment_id'], $this->request->post['PaRes']);

			if ($payment && $payment->getStatus() == 'approved') {
				$success = true;
			} else {

				$this->model_extension_payment_cardinity->updateOrder(array(
					'payment_status'   => 'failed_3dsv1',
					'payment_id' => $order['payment_id']
				));

				$error = $this->language->get('error_finalizing_payment');
			}
		} else {
			$error = $this->language->get('error_unknown_order_id');
		}


		if ($success) {

			$this->model_extension_payment_cardinity->updateOrder(array(
				'payment_status'   => 'approved_3dsv1',
				'payment_id' => $payment->getId()
			));

			$this->logTransaction(array(
				'orderId' => $this->session->data['order_id'],
				'transactionId' =>  $payment->getId(),
				'3dsVersion' => 'v1',
				'amount' => $payment->getAmount() . " " . $payment->getCurrency(),
				'status' => 'approved'
			));

			$this->finalizeOrder($payment);

			$this->response->redirect($this->url->link('checkout/success', '', true));
		} else {


			$this->failedOrder($error);

			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
	}


	public function threeDSecureFormV2()
	{
		$this->load->model('extension/payment/cardinity');

		$this->load->language('extension/payment/cardinity');

			$data['acs_url'] = $this->request->post['acs_url'];
			$data['creq'] = $this->request->post['creq'];
			$data['threeDSSessionData'] = $this->session->getId();

		$data['success'] = true;

		$this->response->setOutput($this->load->view('extension/payment/cardinity_3dsv2', $data));
	}

	public function threeDSecureCallbackV2()
	{
		$this->load->model('extension/payment/cardinity');
		$this->load->language('extension/payment/cardinity');

		//restore session based on sessionId
		$this->session->start($_POST['threeDSSessionData']);
		$currentOrderId = $this->getSession($_POST['threeDSSessionData']);

		$success = false;

		$order = $this->model_extension_payment_cardinity->getOrder($currentOrderId);

			if ($order && $order['payment_id']) {

				if ($order && strpos($order['payment_status'], 'approved') !== false) {
					//payment already finalized
					$success = true;
				} else {

					$payment = $this->model_extension_payment_cardinity->finalize3dv2Payment($this->config->get('payment_cardinity_key'), $this->config->get('payment_cardinity_secret'), $order['payment_id'], $this->request->post['cres']);

					if ($payment && $payment->getStatus() == 'approved') {

						$this->model_extension_payment_cardinity->updateOrder(array(
							'payment_status'   => 'approved_3dsv2',
							'payment_id' => $payment->getId()
						));

						$this->logTransaction(array(
							'orderId' => $this->session->data['order_id'],
							'transactionId' =>  $payment->getId(),
							'3dsVersion' => 'v2',
							'amount' => $payment->getAmount() . " " . $payment->getCurrency(),
							'status' => 'approved'
						));

						$success = true;
					} elseif ($payment && $payment->getStatus() == 'pending') {
						//3dsv2 failed but v1 is pending

						$this->model_extension_payment_cardinity->updateOrder(array(
							'payment_status'   => 'fallback_3dsv1',
							'payment_id' => $payment->getId()
						));

						//3ds v1 retry
						$authorization_information = $payment->getAuthorizationInformation();

						//setSession
						$this->setSession();




						$data['url'] = $authorization_information->getUrl();
						$data['PaReq'] = $authorization_information->getData();
						$data['TermUrl'] = $this->url->link('extension/payment/cardinity/threeDSecureCallback', '', true);
						$data['MD'] = $this->session->getId();
						$data['success'] = true;
						$data['redirect'] = false;


						echo '
						<h3>Threeds v2 validation failed, retrying for v1 in 3 seconds.</h3>
						<p>If browser does not redirect press "Proceed"</p>
						<form id="ThreeDForm" name="ThreeDForm" method="POST" action="' . $data['url'] . '">
							<input type="hidden" name="PaReq" value="' . $data['PaReq'] . '" />
							<input type="hidden" name="TermUrl" value="' . $data['TermUrl'] . '" />
							<input type="hidden" name="MD" value="' . $data['MD'] . '" />
							<input type="submit" value="Proceed" />
						</form>
						<script type="text/javascript">
							window.onload=function(){
								window.setTimeout(document.ThreeDForm.submit.bind(document.ThreeDForm), 3000);
							};
						</script>';
						exit();
					} else {
						$error = $this->language->get('error_finalizing_payment');
					}
				}
			} else {
				$this->testLog("invalid order id");
				$error = $this->language->get('error_unknown_order_id');
			}

		if ($success) {

			$this->finalizeOrder($payment);

			$this->response->redirect($this->url->link('checkout/success', '', true));
		} else {

			$this->failedOrder($error);

			$this->response->redirect($this->url->link('checkout/checkout', '', true));
		}
	}



	private function finalizeOrder($payment)
	{
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/cardinity');
		$this->load->language('extension/payment/cardinity');

		$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_cardinity_order_status_id'));

		$this->model_extension_payment_cardinity->log($this->language->get('text_payment_success'));
	}

	private function failedOrder($log = null, $alert = null)
	{
		$this->load->language('extension/payment/cardinity');
		$this->load->model('extension/payment/cardinity');
		$this->model_extension_payment_cardinity->log($this->language->get('text_payment_failed'));

		//either alert or log or seomthing general
		$this->session->data['error'] = $alert ?? $log ?? $this->language->get('error_process_order');

		//if log set use it, or use whatever error has
		$this->model_extension_payment_cardinity->log($log ?? $this->session->data['error']);

		/*
		if ($log) {
			$this->model_extension_payment_cardinity->log($log);
		}

		if ($alert) {
			$this->session->data['error'] = $alert;
		} else {
			$this->session->data['error'] = $this->language->get('error_process_order');
		}*/
	}

	private function validate()
	{
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/cardinity');

		$error = array();

		if (!$this->session->data['order_id']) {
			$error['warning'] = $this->language->get('error_process_order');
		}

		if (!$error) {
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

			if (!$order_info) {
				$error['warning'] = $this->language->get('error_process_order');
			}
		}

		if (!isset($this->request->post['holder']) || utf8_strlen($this->request->post['holder']) < 1 || utf8_strlen($this->request->post['holder']) > 32) {
			$error['holder'] = true;
		}

		if (!isset($this->request->post['pan']) || utf8_strlen($this->request->post['pan']) < 1 || utf8_strlen($this->request->post['pan']) > 19) {
			$error['pan'] = true;
		}

		if (!isset($this->request->post['pan']) || !is_numeric(preg_replace('!\s+!', '', $this->request->post['pan']))) {
			$error['pan'] = true;
		}

		if (!isset($this->request->post['exp_month']) || !isset($this->request->post['exp_year'])) {
			$error['expiry_date'] = true;
		} else {
			$expiry = new DateTime();
			$expiry->setDate($this->request->post['exp_year'], $this->request->post['exp_month'], '1');
			$expiry->modify('+1 month');
			$expiry->modify('-1 day');

			$now = new DateTime();

			if ($expiry < $now) {
				$error['expiry_date'] = true;
			}
		}

		if (!isset($this->request->post['cvc']) || utf8_strlen($this->request->post['cvc']) < 1 || utf8_strlen($this->request->post['cvc']) > 4) {
			$error['cvc'] = true;
		}

		return $error;
	}

	//debugging tool
	public function testLog($string)
	{
		$this->load->model('extension/payment/cardinity');
		$this->model_extension_payment_cardinity->log($string . "");
	}

	//debugging tool
	public function logTransaction($array)
	{
		$this->testLog("Logging transaction");
		$this->load->model('extension/payment/cardinity');
		$this->model_extension_payment_cardinity->logTransaction($array);
	}
}
