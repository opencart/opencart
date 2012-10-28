<?php  
class ControllerModuleFilter extends Controller {
	protected function index($setting) {
		$this->language->load('module/filter');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		if (isset($parts[0])) {
			$this->data['filter_id'] = $parts[0];
		} else {
			$this->data['filter_id'] = 0;
		}
		
		if (isset($parts[1])) {
			$this->data['child_id'] = $parts[1];
		} else {
			$this->data['child_id'] = 0;
		}
							
		$this->load->model('catalog/filter');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_filter->getCategories(0);

		foreach ($categories as $filter) {
			$total = $this->model_catalog_product->getTotalProducts(array('filter_filter_id' => $filter['filter_id']));

			$children_data = array();

			$children = $this->model_catalog_filter->getCategories($filter['filter_id']);

			foreach ($children as $child) {
				$data = array(
					'filter_filter_id'  => $child['filter_id'],
					'filter_sub_filter' => true
				);

				$product_total = $this->model_catalog_product->getTotalProducts($data);

				$total += $product_total;

				$children_data[] = array(
					'filter_id' => $child['filter_id'],
					'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
					'href'        => $this->url->link('product/filter', 'path=' . $filter['filter_id'] . '_' . $child['filter_id'])	
				);		
			}

			$this->data['categories'][] = array(
				'filter_id' => $filter['filter_id'],
				'name'        => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $total . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('product/filter', 'path=' . $filter['filter_id'])
			);	
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/filter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/filter.tpl';
		} else {
			$this->template = 'default/template/module/filter.tpl';
		}
		
		$this->render();
  	}

}
?>