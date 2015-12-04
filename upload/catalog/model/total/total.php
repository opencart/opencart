<?php
class ModelTotalTotal extends Model {
	public function getTotal($totals) {
		extract($totals);
		
		$this->load->language('total/total');

		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => max(0, $total),
			'sort_order' => $this->config->get('total_sort_order')
		);
	}
}