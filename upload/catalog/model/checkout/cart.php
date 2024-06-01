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
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['code'];
					} else {
						$value = '';
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

			$product_data[] = [
				'image'  => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
				'option' => $option_data
			] + $product;
		}

		return $product_data;
	}

	/**
	 * Get Vouchers
	 *
	 * @return array<string, array<string, mixed>>
	 */
	public function getVouchers(): array {
		$voucher_data = [];

		if (!empty($this->session->data['vouchers'])) {
			$voucher_data = $this->session->data['vouchers'];
		}

		return $voucher_data;
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
