<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Module;
/**
 * Class Latest
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Module
 */
class Latest extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @param array<string, mixed> $setting array of filters
	 *
	 * @return string
	 */
	public function index(array $setting): string {
		$this->load->language('extension/opencart/module/latest');

		$data['axis'] = $setting['axis'];

		$data['products'] = [];

		// Extension
		$this->load->model('extension/opencart/module/latest');

		// Image
		$this->load->model('tool/image');

		$results = $this->model_extension_opencart_module_latest->getLatest($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'placeholder.png';
				}

				$thumb = $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = (float)$result['special'] ? $result['special'] : $result['price'];
				} else {
					$tax = false;
				}

				$product_data = [
					'product_id'  => $result['product_id'],
					'thumb'       => $thumb,
					'name'        => $result['name'],
					'description' => oc_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					'href'        => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $result['product_id'])
				];

				$data['products'][] = $this->load->controller('product/thumb', $product_data);
			}

			return $this->load->view('extension/opencart/module/latest', $data);
		} else {
			return '';
		}
	}
}
