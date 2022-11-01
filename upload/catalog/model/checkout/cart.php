<?php
namespace Opencart\Catalog\Model\Checkout;
class Cart extends \Opencart\System\Engine\Model {
	public function getProducts(): array {
		$this->load->model('tool/image');
		$this->load->model('tool/upload');

		// Products
		$product_data = [];

		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			if ($product['image']) {
				$image = $this->model_tool_image->resize(html_entity_decode($product['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
			}

			$option_data = [];

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

					if ($upload_info) {
						$value = $upload_info['name'];
					} else {
						$value = '';
					}
				}

				$option_data[] = [
					'name'  => $option['name'],
					'value' => (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value),
					'type'  => $option['type']
				];
			}

			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$minimum = false;
			} else {
				$minimum = true;
			}

			$option_data = [];

			foreach ($product['option'] as $option) {
				$option_data[] = [
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],
					'name'                    => $option['name'],
					'value'                   => $option['value'],
					'type'                    => $option['type']
				];
			}

			$product_data[] = [
				'cart_id'      => $product['cart_id'],
				'product_id'   => $product['product_id'],
				'master_id'    => $product['master_id'],
				'image'        => $image,
				'name'         => $product['name'],
				'model'        => $product['model'],
				'option'       => $product['option'],
				'subscription' => $product['subscription'],
				'download'     => $product['download'],
				'quantity'     => $product['quantity'],
				'stock'        => $product['stock'],
				'minimum'      => $minimum,
				'shipping'     => $product['shipping'],
				'subtract'     => $product['subtract'],
				'reward'       => $product['reward'],
				'tax_class_id' => $product['tax_class_id'],
				'price'        => $product['price'],
				'total'        => $product['price'] * $product['quantity']
			];
		}

		return $product_data;
	}

	public function getVouchers(): array {
		$voucher_data = [];

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$voucher_data[] = [
					'code'             => $voucher['code'],
					'description'      => $voucher['description'],
					'from_name'        => $voucher['from_name'],
					'from_email'       => $voucher['from_email'],
					'to_name'          => $voucher['to_name'],
					'to_email'         => $voucher['to_email'],
					'voucher_theme_id' => $voucher['voucher_theme_id'],
					'message'          => $voucher['message'],
					'amount'           => $voucher['amount']
				];
			}
		}

		return $voucher_data;
	}

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

				// __call magic method cannot pass-by-reference so we get PHP to call it as an anonymous function.
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
