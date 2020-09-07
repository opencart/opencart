<?php
namespace Opencart\Application\Model\Extension\Opencart\Total;
class Tax extends \Opencart\System\Engine\Model {
	public function getTotal(&$totals, &$taxes, &$total) {
		foreach ($taxes as $key => $value) {
			if ($value > 0) {
				$totals[] = [
					'code'       => 'tax',
					'title'      => $this->tax->getRateName($key),
					'value'      => $value,
					'sort_order' => $this->config->get('total_tax_sort_order')
				];

				$total += $value;
			}
		}
	}
}