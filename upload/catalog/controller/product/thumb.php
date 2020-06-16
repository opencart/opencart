<?php
class ControllerProductThumb extends Controller {
	public function index($product_info, $url = '', $setting = array()) {
		$this->load->language('product/thumb');

		$data['setting'] = $setting;

		$data['product_id'] = $product_info['product_id'];

		$data['name'] = $product_info['name'];

		$data['description'] = utf8_substr(trim(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..';

		$image_width = isset($setting['width']) ? $setting['width'] : $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width');
		$image_height = isset($setting['height']) ? $setting['height'] : $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height');

		if (is_file(DIR_IMAGE . html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($product_info['image'], ENT_QUOTES, 'UTF-8'), $image_width, $image_height);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('placeholder.png', $image_width, $image_height);
		}

		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		} else {
			$data['price'] = false;
		}

		if ((float)$product_info['special']) {
			$data['special'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
		} else {
			$data['special'] = false;
		}

		if ($this->config->get('config_tax')) {
			$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
		} else {
			$data['tax'] = false;
		}

		$data['minimum'] = $product_info['minimum'] > 0 ? $product_info['minimum'] : 1;

		$data['rating'] = $product_info['rating'];

		$data['href'] = $this->url->link('product/product', 'language=' . $this->config->get('config_language') . $url . '&product_id=' . $product_info['product_id']);

		$data['review_status'] = $this->config->get('config_review_status');

		return $this->load->view('product/thumb', $data);
	}
}