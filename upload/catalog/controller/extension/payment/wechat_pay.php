<?php
/**
 * @package		OpenCart
 * @author		Meng Wenbin
 * @copyright	Copyright (c) 2010 - 2017, Chengdu Guangda Network Technology Co. Ltd. (https://www.opencart.cn/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.cn
 */

class ControllerExtensionPaymentWechatPay extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['redirect'] = $this->url->link('extension/payment/wechat_pay/qrcode');

		return $this->load->view('extension/payment/wechat_pay', $data);
	}

	public function qrcode() {
		$this->load->language('extension/payment/wechat_pay');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/qrcode.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_checkout'),
			'href' => $this->url->link('checkout/checkout', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_qrcode'),
			'href' => $this->url->link('extension/payment/wechat_pay/qrcode')
		);

		$this->load->model('checkout/order');

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$order_id = trim($order_info['order_id']);
		$data['order_id'] = $order_id;
		$subject = trim($this->config->get('config_name'));
		$currency = $this->config->get('payment_wechat_pay_currency');
		$total_amount = trim($this->currency->format($order_info['total'], $currency, '', false));
		$notify_url = HTTPS_SERVER . "payment_callback/wechat_pay";

		$out_trade_no = 'OC' . date('YmdHis') . str_pad($order_id, 6, '0', STR_PAD_LEFT);

		$this->load->model('extension/payment/wechat_pay');

		$orderData = [
			'body' => $subject,
			'out_trade_no' => $out_trade_no,
			'total_fee' => $total_amount,
			'notify_url' => $notify_url,
			'trade_type' => 'NATIVE',
		];

		$result = $this->model_extension_payment_wechat_pay->unifiedOrder($orderData);

		$data['error'] = '';
		$data['code_url'] = '';
		if ($result === false) {
			$data['error_warning'] = $this->model_extension_payment_wechat_pay->getErrMsg();
		} else {
			if (isset($result['code_url']) && !empty($result['code_url'])) {
				$data['code_url'] = $result['code_url'];
			} else {
				$data['error_warning'] = 'API returned success but no code_url';
			}
		}

		$data['action_success'] = $this->url->link('checkout/success');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/wechat_pay_qrcode', $data));
	}

	public function isOrderPaid() {
		$json = array();

		$json['result'] = false;

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);

			if ($order_info['order_status_id'] == $this->config->get('payment_wechat_pay_completed_status_id')) {
				$json['result'] = true;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback() {
		try {
			$this->load->model('extension/payment/wechat_pay');

			$jsonData = file_get_contents('php://input');

			$signature = $_SERVER['HTTP_WECHATPAY_SIGNATURE'] ?? '';
			$timestamp = $_SERVER['HTTP_WECHATPAY_TIMESTAMP'] ?? '';
			$nonce = $_SERVER['HTTP_WECHATPAY_NONCE'] ?? '';
			$serial = $_SERVER['HTTP_WECHATPAY_SERIAL'] ?? '';

			$notifyInfo = $this->model_extension_payment_wechat_pay->parseCallback($jsonData, $signature, $timestamp, $nonce, $serial);

			if ($notifyInfo === false) {
				$this->log->write('WechatPay callback error: ' . $this->model_extension_payment_wechat_pay->getErrMsg());
				$this->responseCallback(false);
				return;
			}

			$outTradeNo = $notifyInfo['out_trade_no'];
			$tradeState = $notifyInfo['trade_state'];

			$orderId = $this->parseOrderId($outTradeNo);
			if (!$orderId) {
				$this->responseCallback(true);
				return;
			}

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($orderId);

			if (!$order_info) {
				$this->responseCallback(true);
				return;
			}

			$completedStatusId = $this->config->get('payment_wechat_pay_completed_status_id');
			if ($order_info['order_status_id'] == $completedStatusId) {
				$this->responseCallback(true);
				return;
			}

			if ($tradeState === 'SUCCESS') {
				$this->model_checkout_order->addOrderHistory($order_info['order_id'], $completedStatusId);
			}

			$this->responseCallback(true);
		} catch (Exception $e) {
			$this->log->write('WechatPay callback exception: ' . $e->getMessage());
			$this->responseCallback(false);
		}
	}

	private function parseOrderId($outTradeNo) {
		if (preg_match('/^OC\d{14}(\d+)$/', $outTradeNo, $matches)) {
			return (int)$matches[1];
		}
		if (is_numeric($outTradeNo)) {
			return (int)$outTradeNo;
		}
		return false;
	}

	private function responseCallback($success) {
		if ($success) {
			$this->response->addHeader('HTTP/1.1 204 No Content');
			$this->response->setOutput('');
		} else {
			$this->response->addHeader('HTTP/1.1 500 Internal Server Error');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode(['code' => 'FAIL', 'message' => 'Error']));
		}
	}
}
