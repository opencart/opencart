<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Cart
 *
 * Can be called using $this->load->model('checkout/cart');
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Cart extends \Opencart\System\Engine\Model {
	/**
	 * Get Products
	 *
	 * @return array<int, array<string, mixed>> product records
	 *
	 * @example
	 *
	 * $this->load->model('checkout/cart');
	 *
	 * $cart = $this->model_checkout_cart->getProducts();
	 */
	public function getProducts(): array {
		$this->load->language('checkout/cart');

		// Upload
		$this->load->model('tool/upload');

		// Products
		$product_data = [];

		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if ($product['image'] && is_file(DIR_IMAGE . html_entity_decode($product['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $product['image'];
			} else {
				$image = 'placeholder.png';
			}

			$option_data = [];

			foreach ($product['option'] as $option) {
				$value = $option['value'];

				if ($option['type'] == 'date') {
					$value = date('Y-m-d', strtotime($option['value']));
				}

				if ($option['type'] == 'time') {
					$value = date('H:i:s', strtotime($option['value']));
				}

				if ($option['type'] == 'datetime') {
					$value = date('Y-m-d H:i:s', strtotime($option['value']));
				}

				if ($option['type'] == 'file') {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['code'];
					}
				}

				$option_data[] = ['value' => $value] + $option;
			}

			$subscription_data = [];

			if ($product['subscription']) {
				$subscription_data = [
					'trial_frequency_text' => $this->language->get('text_' . $product['subscription']['trial_frequency']),
					'trial_price'          => $this->tax->calculate($product['subscription']['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')),
					'frequency_text'       => $this->language->get('text_' . $product['subscription']['frequency']),
					'price'                => $this->tax->calculate($product['subscription']['price'], $product['tax_class_id'], $this->config->get('config_tax'))
				] + $product['subscription'];
			}

			$product_data[] = [
				'image'        => $image,
				'subscription' => $subscription_data,
				'option'       => $option_data,
				'price'        => $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')),
				'total'        => $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']
			] + $product;
		}

		return $product_data;
	}

	/**
	 * Get Totals
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param array<int, float>                $taxes
	 * @param float                            $total
	 *
	 * @return void
	 */
	public function getTotals(array &$totals, array &$taxes, float &$total): void {
		$sort_order = [];

		// Extensions
		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensionsByType('total');

		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if ($this->config->get('total_' . $result['code'] . '_status')) {
				$this->load->model('extension/' . $result['extension'] . '/total/' . $result['code']);

				// __call magic method cannot pass-by-reference so PHP calls it as an anonymous function.
				($this->{'model_extension_' . $result['extension'] . '_total_' . $result['code']}->getTotal)($totals, $taxes, $total);
			}
		}

		$sort_order = [];

		foreach ($totals as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $totals);
	}
}
