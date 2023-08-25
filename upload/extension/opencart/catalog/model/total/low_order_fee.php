<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class LowOrderFee
 *
 * @package
 */
class LowOrderFee extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->getSubTotal() && ($this->cart->getSubTotal() < (float)$this->config->get('total_low_order_fee_total'))) {
			$this->load->language('extension/opencart/total/low_order_fee');

            $list_taxes_separately = $this->config->get('config_list_taxes_separately');
            $list_taxes_separately = !empty($list_taxes_separately) && intval($list_taxes_separately) === 1;

            $title = $this->language->get('text_low_order_fee');

			$totals[] = [
				'extension'  => 'opencart',
				'code'       => 'low_order_fee',
				'title'      => $title,
				'value'      => (float)$this->config->get('total_low_order_fee_fee'),
				'sort_order' => (int)$this->config->get('total_low_order_fee_sort_order')
			];

			if ($this->config->get('total_low_order_fee_tax_class_id')) {
				$tax_rates = $this->tax->getRates($this->config->get('total_low_order_fee_fee'), $this->config->get('total_low_order_fee_tax_class_id'));

				foreach ($tax_rates as $tax_rate) {
                    if($list_taxes_separately){
                        $tax_rate_id = $tax_rate['tax_rate_id'];
                        $new_tax_rate_id = 'multiple_'.$tax_rate_id;
                        if (!isset($taxes[$new_tax_rate_id])) {
                            $taxes[$new_tax_rate_id] = [];
                        }
                        if(!isset($taxes[$new_tax_rate_id][$title])){
                            $taxes[$new_tax_rate_id][$title] = $tax_rate['amount'];
                        }else{
                            $taxes[$new_tax_rate_id][$title] += $tax_rate['amount'];
                        }
                    }else{
                        if (!isset($taxes[$tax_rate['tax_rate_id']])) {
                            $taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
                        } else {
                            $taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
                        }
                    }

				}
			}

			$total += $this->config->get('total_low_order_fee_fee');
		}
	}
}
