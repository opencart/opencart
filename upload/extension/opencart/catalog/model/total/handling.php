<?php
namespace Opencart\Catalog\Model\Extension\Opencart\Total;
/**
 * Class Handling
 *
 * @package
 */
class Handling extends \Opencart\System\Engine\Model {
	/**
	 * @param array $totals
	 * @param array $taxes
	 * @param float $total
	 *
	 * @return void
	 */
	public function getTotal(array &$totals, array &$taxes, float &$total): void {
		if (($this->cart->getSubTotal() > (float)$this->config->get('total_handling_total')) && ($this->cart->getSubTotal() > 0)) {
			$this->load->language('extension/opencart/total/handling');

            $list_taxes_separately = $this->config->get('config_list_taxes_separately');
            $list_taxes_separately = !empty($list_taxes_separately) && intval($list_taxes_separately) === 1;

            $title = $this->language->get('text_handling');

			$totals[] = [
				'extension'  => 'opencart',
				'code'       => 'handling',
				'title'      => $this->language->get('text_handling'),
				'value'      => (float)$this->config->get('total_handling_fee'),
				'sort_order' => (int)$this->config->get('total_handling_sort_order')
			];

			if ($this->config->get('total_handling_tax_class_id')) {
				$tax_rates = $this->tax->getRates((float)$this->config->get('total_handling_fee'), (int)$this->config->get('total_handling_tax_class_id'));

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

			$total += (float)$this->config->get('total_handling_fee');
		}
	}
}
