<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Sub Total
 *
 * Can be called from $this->load->model('extension/opencart/total/sub_total');
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class SubTotal extends \Opencart\System\Engine\Model {
	/**
	 * Get Total
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param  array<int, float>               &$taxes
	 * @param  float                           &$total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		$this->load->language('extension/opencart/total/sub_total');

		$sub_total = $this->cart->getSubTotal();

		$totals[] = [
			'extension'  => 'opencart',
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'value'      => $sub_total,
			'sort_order' => (int)$this->config->get('total_sub_total_sort_order')
		];

		$total += $sub_total;
	}
}
