<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Tax
 *
 * @package Opencart\Catalog\Model\Extension\Opencart\Total
 */
class Tax extends \Opencart\System\Engine\Model {
	/**
	 * @param \Opencart\System\Engine\Counter $counter
	 *
	 * @return void
	 */
	public function getTotal(\Opencart\System\Engine\Counter $counter): void {
		foreach ($counter->taxes as $key => $value) {
			if ($value > 0) {
				$counter->totals[] = [
					'extension'  => 'opencart',
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key),
					'value'      => $value,
					'sort_order' => (int)$this->config->get('total_tax_sort_order')
				];

				$counter->total += $value;
			}
		}
	}
}
