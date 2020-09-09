<?php
namespace Opencart\Application\Model\Extension\Opencart\Total;
class Total extends \Opencart\System\Engine\Model {
	public function getTotal(&$totals, &$taxes, &$total) {
		$this->load->language('extension/opencart/total/total');

		$totals[] = [
			'code' => 'total',
			'title' => $this->language->get('text_total'),
			'value' => max(0, $total),
			'sort_order' => $this->config->get('total_total_sort_order')
		];
	}
}