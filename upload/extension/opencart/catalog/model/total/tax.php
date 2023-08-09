<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Tax
 *
 * @package
 */
class Tax extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		foreach ($taxes as $key => $value) {
			if ($value > 0) {
				$totals[] = [
					'extension'  => 'opencart',
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key),
					'value'      => $value,
					'sort_order' => (int)$this->config->get('total_tax_sort_order')
				];

				$total += $value;
			}
		}
	}
}