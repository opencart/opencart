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

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$order_id = trim($order_info['order_id']);
		$data['order_id'] = $order_id;
		$subject = trim($this->config->get('config_name'));
		$currency = $this->config->get('payment_wechat_pay_currency');
		$total_amount = trim($this->currency->format($order_info['total'], $currency, '', false));
		$notify_url = HTTPS_SERVER . "payment_callback/wechat_pay"; //$this->url->link('wechat_pay/callback');

		$options = array(
			'appid'			 =>  $this->config->get('payment_wechat_pay_app_id'),
			'appsecret'		 =>  $this->config->get('payment_wechat_pay_app_secret'),
			'mch_id'			=>  $this->config->get('payment_wechat_pay_mch_id'),
			'partnerkey'		=>  $this->config->get('payment_wechat_pay_api_secret')
		);

		\Wechat\Loader::config($options);
		$pay = new \Wechat\WechatPay();

		$result = $pay->getPrepayId(NULL, $subject, $order_id, $total_amount * 100, $notify_url, $trade_type = "NATIVE", NULL, $currency);

		$data['error'] = '';
		$data['code_url'] = '';
		if($result === FALSE){
			$data['error_warning'] = $pay->errMsg;
		} else {
			$data['code_url'] = $result;
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
		$options = array(
			'appid'			 =>  $this->config->get('payment_wechat_pay_app_id'),
			'appsecret'		 =>  $this->config->get('payment_wechat_pay_app_secret'),
			'mch_id'			=>  $this->config->get('payment_wechat_pay_mch_id'),
			'partnerkey'		=>  $this->config->get('payment_wechat_pay_api_secret')
		);

		\Wechat\Loader::config($options);
		$pay = new \Wechat\WechatPay();
		$notifyInfo = $pay->getNotify();

		if ($notifyInfo === FALSE) {
			$this->log->write('Wechat Pay Error: ' . $pay->errMsg);
		} else {
			if ($notifyInfo['result_code'] == 'SUCCESS' && $notifyInfo['return_code'] == 'SUCCESS') {
				$order_id = $notifyInfo['out_trade_no'];
				$this->load->model('checkout/order');
				$order_info = $this->model_checkout_order->getOrder($order_id);
				if ($order_info) {
					$order_status_id = $order_info["order_status_id"];
					if (!$order_status_id) {
						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_wechat_pay_completed_status_id'));
					}
				}
				return xml(['return_code' => 'SUCCESS', 'return_msg' => 'DEAL WITH SUCCESS']);
			}
		}
	}
}
