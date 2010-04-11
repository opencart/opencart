<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		if ($this->config->get('total_status')) {
			$this->load->language('total/total');
		 
		 	$this->load->model('localisation/currency');
		 
			$total_data[] = array(
        		'title'      => $this->language->get('text_total'),
        		'text'       => $this->currency->format(max(0,$total)),
        		'value'      => max(0,$total),
				'sort_order' => $this->config->get('total_sort_order')
			);
		}
	}
}
?>