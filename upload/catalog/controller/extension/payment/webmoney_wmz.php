<?php
class ControllerExtensionPaymentWebmoneyWMZ extends Controller {
	private $log;
	private static $LOG_OFF = 0;
	private static $LOG_SHORT = 1;
	private static $LOG_FULL = 2;

	public function __construct($registry) {
		parent::__construct($registry);
		$this->load->language('extension/payment/webmoney_wmz');
	}

	public function index() {
		$data['action'] = 'https://merchant.webmoney.ru/lmi/payment.asp';
		$data['confirm'] = $this->url->link('extension/payment/webmoney_wmz/confirm', '', 'SSL');

		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		
		// Переменные
		$rur_code = 'USD';
		$rur_order_total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $rur_code);
		$amount = $this->currency->format($rur_order_total, $rur_code, $order_info['currency_value'], false);
		$LMI_PAYMENT_DESC_BASE64 = base64_encode(html_entity_decode(sprintf($this->language->get('text_desc'), $this->config->get('config_name'), $this->session->data['order_id']), ENT_QUOTES, 'UTF-8'));
		
		$data['params'] = array(
			'LMI_PAYEE_PURSE'         => $this->config->get('payment_webmoney_wmz_merch_r'),  // Номер кошелька
			'LMI_PAYMENT_AMOUNT'      => $amount,
			'LMI_PAYMENT_NO'          => $this->session->data['order_id']
		);

		$LMI_PAYMENTFORM_SIGN = implode(';', array_values($data['params'])) . ';' . $this->config->get('payment_webmoney_wmz_secret_key_x20') . ';';
		
		$data['params']['LMI_PAYMENT_DESC_BASE64'] = $LMI_PAYMENT_DESC_BASE64;
		$data['params']['LMI_PAYMENTFORM_SIGN'] = strtoupper(hash('sha256', $LMI_PAYMENTFORM_SIGN));

		$this->logWrite('Make payment form: ', self::$LOG_FULL);
		$this->logWrite('  DATA: ' . var_export($data['params'], true), self::$LOG_FULL);
		
		return $this->load->view('extension/payment/webmoney_wmz', $data);
	}

	public function confirm() {
		if (!empty($this->session->data['order_id']) && ($this->session->data['payment_method']['code'] == 'webmoney_wmz')) {
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_webmoney_wmz_order_confirm_status_id'));
		}
	}
	
	public function fail() {
		$this->logWrite('FailURL: ', self::$LOG_FULL);
		$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
		$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') exit($this->sendForbidden($this->language->get('error_post')));

		$LMI_PAYMENT_NO = isset($this->request->post['LMI_PAYMENT_NO']) ? (int)$this->request->post['LMI_PAYMENT_NO'] : 0;
		
		if($LMI_PAYMENT_NO) {
			$this->load->model('checkout/order');
			$this->model_checkout_order->addOrderHistory($LMI_PAYMENT_NO, $this->config->get('payment_webmoney_wmz_order_fail_status_id'),'Webmoney wmz Fail',true);
		}

		$this->response->redirect($this->url->link('checkout/failure', '', 'SSL'));
	}
	
	public function success() {
		$this->logWrite('SuccessURL: ', self::$LOG_FULL);
		$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
		$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') exit($this->sendForbidden($this->language->get('error_post')));

		$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
	}
	
	public function callback() {
		$this->logWrite('CallbackURL: ', self::$LOG_FULL);
		$this->logWrite('  POST:' . var_export($this->request->post, true), self::$LOG_FULL);
		$this->logWrite('  GET:' . var_export($this->request->get, true), self::$LOG_FULL);

		if ($this->request->server['REQUEST_METHOD'] != 'POST') exit($this->sendForbidden($this->language->get('error_post')));

		if(isset($this->request->post['LMI_PREREQUEST'])) {
			$this->logWrite('CallbackURL: PREREQUEST SUCCESS', self::$LOG_FULL);
			echo 'YES';
			exit;
		}
		
		// Обязательные параметры
		if(!isset($this->request->post['LMI_PAYEE_PURSE'])) exit($this->sendForbidden($this->language->get('error_payee_purse')));
		
		$LMI_PAYEE_PURSE 		= $this->request->post['LMI_PAYEE_PURSE'];		// Кошелек продавца
		$LMI_PAYMENT_AMOUNT 	= $this->request->post['LMI_PAYMENT_AMOUNT']; 	// Сумма перевода
		$LMI_PAYMENT_NO			= $this->request->post['LMI_PAYMENT_NO'];
		$LMI_MODE 				= $this->request->post['LMI_MODE'];
		$LMI_SYS_INVS_NO 		= $this->request->post['LMI_SYS_INVS_NO'];
		$LMI_SYS_TRANS_NO 		= $this->request->post['LMI_SYS_TRANS_NO'];
		$LMI_SYS_TRANS_DATE 	= $this->request->post['LMI_SYS_TRANS_DATE'];
		$LMI_PAYER_PURSE 		= $this->request->post['LMI_PAYER_PURSE'];
		$LMI_PAYER_WM 			= $this->request->post['LMI_PAYER_WM'];
		$LMI_HASH 				= $this->request->post['LMI_HASH'];
		
		
		$LMI_SECRET_KEY = $this->config->get('payment_webmoney_wmz_secret_key');
		
		$md5_crc = strtoupper(md5($LMI_PAYEE_PURSE . $LMI_PAYMENT_AMOUNT . $LMI_PAYMENT_NO . $LMI_MODE . $LMI_SYS_INVS_NO . $LMI_SYS_TRANS_NO . $LMI_SYS_TRANS_DATE . $LMI_SECRET_KEY . $LMI_PAYER_PURSE . $LMI_PAYER_WM));
		$sha256_crc = strtoupper(hash('sha256', $LMI_PAYEE_PURSE . $LMI_PAYMENT_AMOUNT . $LMI_PAYMENT_NO . $LMI_MODE . $LMI_SYS_INVS_NO . $LMI_SYS_TRANS_NO . $LMI_SYS_TRANS_DATE . $LMI_SECRET_KEY . $LMI_PAYER_PURSE . $LMI_PAYER_WM));
		 
		if (($LMI_HASH != $md5_crc) && ($LMI_HASH != $sha256_crc)) {
			$this->sendForbidden(sprintf($this->language->get('error_crc'), $md5_crc, $sha256_crc));
			return 0;
		}
		
		
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($LMI_PAYMENT_NO);
		
		if (!$order_info) {
			$this->sendForbidden($this->language->get('error_order_not_found'));
			return 0;
		}

    // Fraud Detection
    $amount_order = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
    $amount_order = number_format(ceil($amount_order), 2, '.', '');
    $amount_payment = ceil($LMI_PAYMENT_AMOUNT);
 
    if ($amount_order != $amount_payment) {
			$this->sendForbidden(sprintf($this->language->get('error_fraud'), $amount_payment, 'USD', $amount_order, $order_info['currency_code']));
			$this->model_checkout_order->addOrderHistory($LMI_PAYMENT_NO, $this->config->get('payment_webmoney_wmz_order_fail_status_id'),'Webmoney wmz Fraud: ' . $LMI_PAYER_PURSE, true);
      return 0;
    }
		
		if($LMI_PAYEE_PURSE != $this->config->get('payment_webmoney_wmz_merch_r')) {
			$this->sendForbidden(sprintf($this->language->get('error_merch_r'), $LMI_PAYEE_PURSE));
			return 0;
		}

		if($order_info['order_status_id'] != $this->config->get('payment_webmoney_wmz_order_status_id')) {
			$this->model_checkout_order->addOrderHistory($LMI_PAYMENT_NO, $this->config->get('payment_webmoney_wmz_order_status_id'),'Webmoney wmz Success: ' . $LMI_PAYER_PURSE, true);
		}
		
		return true;
		
	}

  protected function sendForbidden($error) {
    $this->logWrite('ERROR: ' . $error, self::$LOG_SHORT);
    echo $error;
  }

  protected function logWrite($message, $type) {
      switch ($this->config->get('payment_webmoney_wmz_log')) {
          case self::$LOG_OFF:
              return;
          case self::$LOG_SHORT:
              if ($type == self::$LOG_FULL) {
                  return;
              }
      }

      if (!$this->log) {
          $this->log = new Log($this->config->get('payment_webmoney_wmz_log_filename'));
      }

      $this->log->Write($message);
  }
}
?>