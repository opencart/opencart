<?php
class ModelTotalSubTotal extends Model {
	public function getTotal($data) {
		$this->load->language('total/sub_total');

		$data['sub_total'] = $this->cart->getSubTotal();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$data['sub_total'] += $voucher['amount'];
			}
		}

		$data['totals'][] = array(
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'value'      => $data['sub_total'],
			'sort_order' => $this->config->get('sub_total_sort_order')
		);

		$data['total'] += $data['sub_total'];
	}
}
