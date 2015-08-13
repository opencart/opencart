<?php
class ControllerTotalVoucher extends Controller {
	public function index() {
		if ($this->config->get('voucher_status')) {
			$this->load->language('total/coupon');

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_loading'] = $this->language->get('text_loading');

			$data['entry_voucher'] = $this->language->get('entry_voucher');

			$data['button_voucher'] = $this->language->get('button_voucher');

			if (isset($this->session->data['voucher'])) {
				$data['voucher'] = $this->session->data['voucher'];
			} else {
				$data['voucher'] = '';
			}

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/total/voucher.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/total/voucher.tpl', $data);
			} else {
				return $this->load->view('default/template/total/coupon.tpl', $data);
			}
		}
	}

	public function voucher() {
		$this->load->language('total/coupon');

		$json = array();

		$this->load->model('total/coupon');

		if (isset($this->request->post['voucher'])) {
			$voucher = $this->request->post['voucher'];
		} else {
			$voucher = '';
		}

		$voucher_info = $this->model_total_voucher->getVoucher($voucher);

		if (empty($this->request->post['voucher'])) {
			$json['error'] = $this->language->get('error_empty');
		} elseif ($voucher_info) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('checkout/cart');
		} else {
			$json['error'] = $this->language->get('error_voucher');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
