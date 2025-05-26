<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Compare
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Compare extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('product/compare');

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = [];
		}

		if (isset($this->request->get['remove'])) {
			$key = array_search($this->request->get['remove'], $this->session->data['compare']);

			if ($key !== false) {
				unset($this->session->data['compare'][$key]);

				$this->session->data['success'] = $this->language->get('text_remove');
			}

			$this->response->redirect($this->url->link('product/compare', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('product/compare', 'language=' . $this->config->get('config_language'))
		];

		$data['cart_add'] = $this->url->link('checkout/cart.add', 'language=' . $this->config->get('config_language'));
		$data['cart'] = $this->url->link('common/cart.info', 'language=' . $this->config->get('config_language'));

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Attribute Group
		$data['attribute_groups'] = [];

		// Product
		$data['products'] = [];

		$this->load->model('catalog/product');

		// Manufacturer
		$this->load->model('catalog/manufacturer');

		// Stock Status
		$this->load->model('localisation/stock_status');

		// Image
		$this->load->model('tool/image');

		foreach ($this->session->data['compare'] as $key => $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$description = trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')));

				if (oc_strlen($description) > $this->config->get('config_product_description_length')) {
					$description = oc_substr($description, 0, $this->config->get('config_product_description_length')) . '..';
				}

				if ($product_info['image'] && is_file(DIR_IMAGE . html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'))) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_compare_width'), $this->config->get('config_image_compare_height'));
				} else {
					$image = '';
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special = false;
				}

				$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);

				if ($manufacturer_info) {
					$manufacturer = $manufacturer_info['name'];
				} else {
					$manufacturer = '';
				}

				if ($product_info['quantity'] <= 0) {
					$stock_status_id = $product_info['stock_status_id'];
				} elseif (!$this->config->get('config_stock_display')) {
					$stock_status_id = (int)$this->config->get('stock_status_id');
				} else {
					$stock_status_id = 0;
				}

				$stock_status_info = $this->model_localisation_stock_status->getStockStatus($stock_status_id);

				if ($stock_status_info) {
					$availability = $stock_status_info['name'];
				} else {
					$availability = $product_info['quantity'];
				}

				$attribute_data = [];

				$attribute_groups = $this->model_catalog_product->getAttributes($product_id);

				foreach ($attribute_groups as $attribute_group) {
					foreach ($attribute_group['attribute'] as $attribute) {
						$attribute_data[$attribute['attribute_id']] = $attribute['text'];
					}
				}

				$data['products'][$product_id] = [
					'description'  => $description,
					'thumb'        => $image,
					'price'        => $price,
					'special'      => $special,
					'manufacturer' => $manufacturer,
					'availability' => $availability,
					'minimum'      => $product_info['minimum'] > 0 ? $product_info['minimum'] : 1,
					'rating'       => (int)$product_info['rating'],
					'reviews'      => sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']),
					'weight'       => $this->weight->format($product_info['weight'], $product_info['weight_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point')),
					'length'       => $this->length->format($product_info['length'], $product_info['length_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point')),
					'width'        => $this->length->format($product_info['width'], $product_info['length_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point')),
					'height'       => $this->length->format($product_info['height'], $product_info['length_class_id'], $this->language->get('decimal_point'), $this->language->get('thousand_point')),
					'attribute'    => $attribute_data,
					'href'         => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id),
					'remove'       => $this->url->link('product/compare', 'language=' . $this->config->get('config_language') . '&remove=' . $product_id)
				] + $product_info;

				foreach ($attribute_groups as $attribute_group) {
					$data['attribute_groups'][$attribute_group['attribute_group_id']]['name'] = $attribute_group['name'];

					foreach ($attribute_group['attribute'] as $attribute) {
						$data['attribute_groups'][$attribute_group['attribute_group_id']]['attribute'][$attribute['attribute_id']]['name'] = $attribute['name'];
					}
				}
			} else {
				unset($this->session->data['compare'][$key]);
			}
		}

		$data['review_status'] = $this->config->get('config_review_status');

		$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

		$data['currency'] = $this->session->data['currency'];

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('product/compare', $data));
	}

	/**
	 * Add
	 *
	 * @return void
	 */
	public function add(): void {
		$this->load->language('product/compare');

		$json = [];

		if (!isset($this->session->data['compare'])) {
			$this->session->data['compare'] = [];
		}

		if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if (!$product_info) {
			$json['error'] = $this->language->get('error_product');
		}

		if (!$json) {
			// If already in array remove the product_id so it will be added to the back of the array
			$key = array_search($product_info['product_id'], $this->session->data['compare']);

			if ($key !== false) {
				unset($this->session->data['compare'][$key]);
			}

			// If we count a numeric value that is greater than 4 products, we remove the first one
			if (count($this->session->data['compare']) >= 4) {
				array_shift($this->session->data['compare']);
			}

			$this->session->data['compare'][] = $product_info['product_id'];

			$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('product/compare', 'language=' . $this->config->get('config_language')));

			$json['total'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
