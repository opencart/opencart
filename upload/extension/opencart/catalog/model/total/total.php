<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Total
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Total extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		$this->load->language('extension/opencart/total/total');

		$counter->totals[] = [
			'extension'  => 'opencart',
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => $counter->total,
			'sort_order' => (int)$this->config->get('total_total_sort_order')
		];
	}
}
