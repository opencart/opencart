<?php
class ModelTotalSubTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language('total/sub_total');
		
		$sub_total = $this->cart->getSubTotal();
		
		if (isset($this->session->data['voucher']) && $this->session->data['voucher']) {
			foreach ($this->session->data['voucher'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		} 
		
		$total_data[] = array( 
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'text'       => $this->currency->format($sub_total),
			'value'      => $sub_total,
			'sort_order' => $this->config->get('sub_total_sort_order')
		);
		
		$total += $sub_total;
	}
}
?>