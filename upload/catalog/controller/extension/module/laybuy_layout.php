<?php
class ControllerExtensionModuleLaybuyLayout extends Controller {
	public function index() {
		$this->load->model('extension/module/laybuy_layout');

		$status = $this->config->get('laybuy_layout_status');

		if ($status && $this->config->get('laybuy_status')) {
			if ($this->customer->isLogged()) {
				if (isset($this->request->get['order_id'])) {
					$order_id = $this->request->get['order_id'];

					if ($this->model_extension_module_laybuy_layout->isLayBuyOrder($order_id)) {
						$this->load->language('extension/module/laybuy_layout');

						$data['heading_title'] = $this->language->get('heading_title');

						$data['text_reference_info'] = $this->language->get('text_reference_info');
						$data['text_laybuy_ref_no'] = $this->language->get('text_laybuy_ref_no');
						$data['text_paypal_profile_id'] = $this->language->get('text_paypal_profile_id');
						$data['text_payment_plan'] = $this->language->get('text_payment_plan');
						$data['text_status'] = $this->language->get('text_status');
						$data['text_amount'] = $this->language->get('text_amount');
						$data['text_downpayment_percent'] = $this->language->get('text_downpayment_percent');
						$data['text_months'] = $this->language->get('text_months');
						$data['text_downpayment_amount'] = $this->language->get('text_downpayment_amount');
						$data['text_payment_amounts'] = $this->language->get('text_payment_amounts');
						$data['text_first_payment_due'] = $this->language->get('text_first_payment_due');
						$data['text_last_payment_due'] = $this->language->get('text_last_payment_due');
						$data['text_downpayment'] = $this->language->get('text_downpayment');
						$data['text_month'] = $this->language->get('text_month');

						$data['column_instalment'] = $this->language->get('column_instalment');
						$data['column_amount'] = $this->language->get('column_amount');
						$data['column_date'] = $this->language->get('column_date');
						$data['column_pp_trans_id'] = $this->language->get('column_pp_trans_id');
						$data['column_status'] = $this->language->get('column_status');

						$transaction_info = $this->model_extension_module_laybuy_layout->getTransactionByOrderId($order_id);

						$data['transaction'] = array(
							'laybuy_ref_no'      => $transaction_info['laybuy_ref_no'],
							'paypal_profile_id'  => $transaction_info['paypal_profile_id'],
							'status'             => $this->model_extension_module_laybuy_layout->getStatusLabel($transaction_info['status']),
							'amount'             => $this->currency->format($transaction_info['amount'], $transaction_info['currency']),
							'downpayment'        => $transaction_info['downpayment'],
							'months'             => $transaction_info['months'],
							'downpayment_amount' => $this->currency->format($transaction_info['downpayment_amount'], $transaction_info['currency']),
							'payment_amounts'    => $this->currency->format($transaction_info['payment_amounts'], $transaction_info['currency']),
							'first_payment_due'  => date($this->language->get('date_format_short'), strtotime($transaction_info['first_payment_due'])),
							'last_payment_due'   => date($this->language->get('date_format_short'), strtotime($transaction_info['last_payment_due'])),
							'report'             => json_decode($transaction_info['report'], true)
						);

						return $this->load->view('extension/module/laybuy_layout', $data);
					}
				}
			}
		}
	}
}