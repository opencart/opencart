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
		
		$category_id = end($parts);
		
		$this->load->model('catalog/category');
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
		
		if ($category_info) {
			$this->load->model('catalog/filter');

			$this->load->model('catalog/product');

			$this->data['filters'] = array();

			$filters = $this->model_catalog_filter->getFilterGroupsByCategoryId($category_id);

			foreach ($filters as $filter) {
				$filter_value_data = array();

				$filter_values = $this->model_catalog_filter->getFilters($filter['filter_id']);

				foreach ($filter_values as $filter_value) {
					$data = array(
						'filter_category_id'     => $category_id,
						'filter_sub_filter'      => true,
						'filter_filter_value_id' => $filter_value['filter_value_id']
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					$total += $product_total;

					$filter_value_data[] = array(
						'filter_id' => $child['filter_id'],
						'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'        => $this->url->link('product/filter', 'path=' . $filter['filter_id'] . '_' . $child['filter_id'])	
					);		
				}

				$this->data['filters'][] = array(
					'filter_id'     => $filter['filter_id'],
					'name'          => $filter['name'],
					'cfilter_value' => $filter_value_data
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
}
?>