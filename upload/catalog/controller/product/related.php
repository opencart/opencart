<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Related
 *
 * Can be loaded using $this->load->controller('product/related');
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Related extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('product/related');

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$data['products'] = [];

		$results = $this->model_catalog_product->getRelated($product_id);

		foreach ($results as $result) {
			$description = trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')));

			if (oc_strlen($description) > $this->config->get('config_product_description_length')) {
				$description = oc_substr($description, 0, $this->config->get('config_product_description_length')) . '..';
			}

			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $result['image'];
			} else {
				$image = 'placeholder.png';
			}

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
				'thumb'       => $this->model_tool_image->resize($image, $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height')),
				'description' => $description,
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $result['product_id'])
			] + $result;

			$data['products'][] = $this->load->controller('product/thumb', $product_data);
		}

		return $this->load->view('product/related', $data);
	}
}
