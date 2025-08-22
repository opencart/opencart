<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class Featured
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Featured extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, mixed> $setting array of data
	 *
	 * @return string
	 */
	public function index(array $setting): string {
		$this->load->language('extension/opencart/module/featured');

		$data['axis'] = $setting['axis'];

		$data['products'] = [];

		// Product
		$this->load->model('catalog/product');

		// Image
		$this->load->model('tool/image');

		if (!empty($setting['product'])) {
			$products = [];

			foreach ($setting['product'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$products[] = $product_info;
				}
			}

			foreach ($products as $product) {
				if ($product['image']) {
					$image = $product['image'];
				} else {
					$image = 'placeholder.png';
				}

				$thumb = $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = (float)$product['special'] ? $product['special'] : $product['price'];
				} else {
					$tax = false;
				}

				$product_data = [
					'product_id'  => $product['product_id'],
					'thumb'       => $thumb,
					'name'        => $product['name'],
					'description' => oc_substr(trim(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $product['minimum'] > 0 ? $product['minimum'] : 1,
					'rating'      => (int)$product['rating'],
					'href'        => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id'])
				];

				$data['products'][] = $this->load->controller('product/thumb', $product_data);
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/opencart/module/featured', $data);
		} else {
			return '';
		}
	}
}
