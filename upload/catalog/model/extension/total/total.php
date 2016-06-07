<?php
class ModelTotalTotal extends Model {
	public function getTotal($total) {
		$this->load->language('total/total');

		$total['totals'][] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => max(0, $total['total']),
			'sort_order' => $this->config->get('total_sort_order')
		);
	}
}