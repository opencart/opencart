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

                if(is_string($key) && str_starts_with($key, 'multiple_')){
                    $tax_rate_id = intval(str_replace('multiple_', '', $key));
                    $total_tax_amount = 0;
                    foreach ($value as $shipping_method_name => $tax_amount){
                        $totals[] = [
                            'extension'  => 'opencart',
                            'code'       => 'tax',
                            'title'      => $shipping_method_name . ' - ' . $this->tax->getRateName($tax_rate_id),
                            'value'      => $tax_amount,
                            'sort_order' => (int)$this->config->get('total_tax_sort_order')
                        ];
                        $total_tax_amount += $tax_amount;
                    }
                    $value = $total_tax_amount;
                }else{
                    $totals[] = [
                        'extension'  => 'opencart',
                        'code'       => 'tax',
                        'title'      => $this->tax->getRateName($key),
                        'value'      => $value,
                        'sort_order' => (int)$this->config->get('total_tax_sort_order')
                    ];
                }

				$total += $value;
			}
		}
	}
}
