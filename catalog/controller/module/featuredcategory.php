<?php
class ControllerModuleFeaturedcategory extends Controller {
	public function index($setting) {
		$this->load->language('module/featuredcategory');

		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_view'] = $this->language->get('text_view'); 

		$this->load->model('catalog/product');
        $this->load->model('catalog/category');

		$this->load->model('tool/image');

		$data['products'] = array();

        $products = explode(',', $this->config->get('featuredcategory_product'));

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		$products = array_slice($setting['product'], 0, (int)$setting['limit']);

		foreach ($products as $category_id) {
			$product_info = $this->model_catalog_category->getCategory($category_id);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}


				$data['products'][] = array(
					'category_id' => $product_info['category_id'],
					'thumb'   	 => $image,
                    'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, 30) . '..',     
					'name'    	 => $product_info['name'],
					'href'    	 => $this->url->link('product/category', 'path=' . $product_info['category_id'])
				);
			}
		}

		if ($data['products']) {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featuredcategory.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/module/featuredcategory.tpl', $data);
			} else {
				return $this->load->view('default/template/module/featuredcategory.tpl', $data);
			}
		}
	}
}