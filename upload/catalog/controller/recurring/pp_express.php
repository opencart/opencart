<?php
class ControllerRecurringPPExpress extends Controller {
	public function index() {
		$this->load->language('payment/pp_express');
		
		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}
		
		$this->load->model('account/recurring');

		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);
		
		if ($recurring_info) {
			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_cancel'] = $this->language->get('button_cancel');
			
			$data['continue'] = $this->url->link('account/recurring', '', true);	
			
			if ($recurring_info['status'] == 2 || $recurring_info['status'] == 3) {
				$data['cancel'] = $this->url->link('payment/pp_express/recurringCancel', 'order_recurring_id=' . $order_recurring_id, true);
			} else {
				$data['cancel'] = '';
			}

			return $this->load->view('recurring/pp_express', $data);
		}
	}
	
	public function cancel() {
		$this->load->language('account/recurring');
		
		//cancel an active recurring
		$this->load->model('account/recurring');

		$recurring_info = $this->model_account_recurring->getOrderRecurring($this->request->get['order_recurring_id']);

		if ($recurring_info && $recurring_info['reference']) {
			$this->load->model('payment/pp_express');
			
			$result = $this->model_payment_pp_express->recurringCancel($recurring_info['reference']);

			if (isset($result['PROFILEID'])) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '5'");
				$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");

				$this->session->data['success'] = $this->language->get('text_cancelled');
			} else {
				$this->session->data['error'] = sprintf($this->language->get('error_not_cancelled'), $result['L_LONGMESSAGE0']);
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_not_found');
		}

		$this->response->redirect($this->url->link('account/recurring/info', 'order_recurring_id=' . $this->request->get['order_recurring_id'], true));
	}	
}