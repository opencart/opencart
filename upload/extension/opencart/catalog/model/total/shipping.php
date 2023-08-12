<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Shipping
 *
 * @package
 */
class Shipping extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
            $list_taxes_separately = $this->config->get('config_list_taxes_separately');
            $list_taxes_separately = !empty($list_taxes_separately) && intval($list_taxes_separately) === 1;

            $shipping_method_name = $this->session->data['shipping_method']['name'];
            $shipping_method_cost = $this->session->data['shipping_method']['cost'];

			$totals[] = [
				'extension'  => 'opencart',
				'code'       => 'shipping',
				'title'      => $shipping_method_name,
				'value'      => $shipping_method_cost,
				'sort_order' => (int)$this->config->get('total_shipping_sort_order')
			];

			if (isset($this->session->data['shipping_method']['tax_class_id'])) {
				$tax_rates = $this->tax->getRates($shipping_method_cost, $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
                    if($list_taxes_separately){
                        $tax_rate_id = $tax_rate['tax_rate_id'];
                        $new_tax_rate_id = 'multiple_'.$tax_rate_id;
                        if (!isset($taxes[$new_tax_rate_id])) {
                            $taxes[$new_tax_rate_id] = [];
                        }
                        if(!isset($taxes[$new_tax_rate_id][$shipping_method_name])){
                            $taxes[$new_tax_rate_id][$shipping_method_name] = $tax_rate['amount'];
                        }else{
                            $taxes[$new_tax_rate_id][$shipping_method_name] += $tax_rate['amount'];
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

			$total += $shipping_method_cost;
		}
	}
}
