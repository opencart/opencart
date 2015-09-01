<?php
class ControllerModuleFeaturedmanufacturer extends Controller {
	public function index($setting) {
		$this->load->language('module/featuredmanufacturer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');

		$this->load->model('catalog/product');
        $this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');

		$data['products'] = array();

        $products = explode(',', $this->config->get('featuredmanufacturer_product'));

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		$products = array_slice($setting['product'], 0, (int)$setting['limit']);

		foreach ($products as $manufacturer_id) {
			$product_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}


				$data['products'][] = array(
                    'manufacturer_id' => $product_info['manufacturer_id'],
					'thumb'   	      => $image,
					'name'            => $product_info['name'],
				   	'href'            => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id'])
				);
			}
		}

		if ($data['products']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredmanufacturer.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featuredmanufacturer.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featuredmanufacturer.tpl', $data);
			}
		}
	}
}