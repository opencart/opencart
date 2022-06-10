<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
class Total extends \Opencart\System\Engine\Model {
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		$this->load->language('extension/opencart/total/total');

		$totals[] = [
			'extension'  => 'opencart',
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => $total,
			'sort_order' => (int)$this->config->get('total_total_sort_order')
		];
	}
}