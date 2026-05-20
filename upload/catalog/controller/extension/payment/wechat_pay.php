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
			$this->log->write('WechatPay QRCode: No order_id in session');
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

		$this->log->write('WechatPay QRCode Debug: order_id=' . $order_id . ', out_trade_no=' . $out_trade_no . ', currency=' . $currency . ', total=' . $order_info['total'] . ', total_amount=' . $total_amount);

		$this->load->model('extension/payment/wechat_pay');

		$orderData = [
			'body' => $subject,
			'out_trade_no' => $out_trade_no,
			'total_fee' => $total_amount,
			'notify_url' => $notify_url,
			'trade_type' => 'NATIVE',
		];

		$result = $this->model_extension_payment_wechat_pay->unifiedOrder($orderData);

		$this->log->write('WechatPay QRCode Result: ' . ($result === false ? 'false' : json_encode($result)));

		$data['error'] = '';
		$data['code_url'] = '';
		if ($result === false) {
			$errMsg = $this->model_extension_payment_wechat_pay->getErrMsg();
			$this->log->write('WechatPay QRCode Error: ' . $errMsg);
			$data['error_warning'] = $errMsg;
		} else {
			if (isset($result['code_url']) && !empty($result['code_url'])) {
				$data['code_url'] = $result['code_url'];
			} else {
				$data['error_warning'] = 'API returned success but no code_url. Response: ' . json_encode($result);
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
		$this->load->model('extension/payment/wechat_pay');

		$jsonData = file_get_contents('php://input');

		$notifyInfo = $this->model_extension_payment_wechat_pay->parseCallback($jsonData);

		if ($notifyInfo === false) {
			$this->log->write('Wechat Pay Error: ' . $this->model_extension_payment_wechat_pay->getErrMsg());
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput($this->model_extension_payment_wechat_pay->buildCallbackResponse(false));
		} else {
			if ($notifyInfo['trade_state'] == 'SUCCESS') {
				$order_id = $notifyInfo['out_trade_no'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				if ($order_info) {
					$order_status_id = $order_info["order_status_id"];
					if (!$order_status_id) {
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_wechat_pay_completed_status_id'));
					}
				}
				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput($this->model_extension_payment_wechat_pay->buildCallbackResponse(true));
			}
		}
	}
}
