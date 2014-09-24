<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		$this->load->model('design/layout');
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('catalog/information');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_left');

		foreach ($modules as $module) {
			if ($this->config->get($module['code'] . '_status')) {
				if ($this->config->has($module['code'] . '_module')) {
					$setting = $this->config->get($module['code'] . '_module');
				
					if (isset($setting[$module['key']])) {
						$config = $setting[$module['key']];
					} else {
						$config = array();
					}
					
					$data['modules'][] = $this->load->controller('module/' . $module['code'], $config);
				} else {
					$data['modules'][] = $this->load->controller('module/' . $module['code']);
				}
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/column_left.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/column_left.tpl', $data);
		} else {
			return $this->load->view('default/template/common/column_left.tpl', $data);
		}
	}
}