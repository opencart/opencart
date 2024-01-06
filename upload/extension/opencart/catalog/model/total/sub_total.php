<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class SubTotal
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class SubTotal extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		$this->load->language('extension/opencart/total/sub_total');

		$sub_total = $this->cart->getSubTotal();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}

		$counter->totals[] = [
			'extension'  => 'opencart',
			'code'       => 'sub_total',
			'title'      => $this->language->get('text_sub_total'),
			'value'      => $sub_total,
			'sort_order' => (int)$this->config->get('total_sub_total_sort_order')
		];

		$counter->total += $sub_total;
	}
}
