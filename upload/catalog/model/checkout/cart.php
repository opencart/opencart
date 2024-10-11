<?php
namespace Opencart\Catalog\Model\Checkout;
/**
 * Class Cart
 *
 * @package Opencart\Catalog\Model\Checkout
 */
class Cart extends \Opencart\System\Engine\Model {
	/**
	 * Get Products
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getProducts(): array {
		$this->load->language('checkout/cart');

		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$price_status = true;
		} else {
			$price_status = false;
		}

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

				$option_data[] = [
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],
					'name'                    => $option['name'],
					'value'                   => $value,
					'type'                    => $option['type']
				];
			}

			if ($product['subscription']) {
				$description = '';

				if ($product['subscription']['trial_status']) {
					$trial_price = $this->currency->format($this->tax->calculate($product['subscription']['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_cycle = $product['subscription']['trial_cycle'];
					$trial_frequency = $this->language->get('text_' . $product['subscription']['trial_frequency']);
					$trial_duration = $product['subscription']['trial_duration'];

					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($this->tax->calculate($product['subscription']['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$cycle = $product['subscription']['cycle'];
				$frequency = $this->language->get('text_' . $product['subscription']['frequency']);
				$duration = $product['subscription']['duration'];

				if ($duration) {
					$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}

				$product['subscription']['description'] = $description;
			}

			$product_data[] = [
				'image'      => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
				'option'     => $option_data,
				'price_text' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'total_text' => $this->currency->format($this->tax->calculate($product['total'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
			] + $product;
		}

		return $product_data;
	}

	/**
	 * Get Totals
	 *
	 * @param array<int, array<string, mixed>> $totals
	 * @param array<int, float>                $taxes
	 * @param int                              $total
	 *
	 * @return void
	 */
	public function getTotals(array &$totals, array &$taxes, int &$total): void {
		$sort_order = [];

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
