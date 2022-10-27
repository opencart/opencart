<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
use \Opencart\System\Helper AS Helper;
class Featured extends \Opencart\System\Engine\Controller {
	public function index(array $setting): string {
		$this->load->language('extension/opencart/module/featured');

		$data['axis'] = $setting['axis'];

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['products'] = [];

		if (!empty($setting['product'])) {
			$products = [];
			$product_data = [];

			foreach ($setting['product'] as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					$products[] = $product_info;
				}
			}

			foreach ($products as $product) {
				if ($product['image']) {
					$image = $this->model_tool_image->resize(html_entity_decode($product['image'], ENT_QUOTES, 'UTF-8'), $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				$product_data = [
					'product_id'  => $product['product_id'],
					'thumb'       => $image,
					'name'        => $product['name'],
					'description' => oc_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
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
