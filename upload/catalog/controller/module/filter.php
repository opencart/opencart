<?php  
class ControllerModuleFilter extends Controller {
	protected function index($setting) {
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}
		
		$category_id = end($parts);
		
		$this->load->model('catalog/category');
		
		$category_info = $this->model_catalog_category->getCategory($category_id);
		
		if ($category_info) {
			$this->language->load('module/filter');
		
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->load->model('catalog/filter');

			$this->load->model('catalog/product');

			$this->data['filter_groups'] = array();

			$filter_groups = $this->model_catalog_filter->getFilterGroupsByCategoryId($category_id);

			foreach ($filter_groups as $filter_group) {
				$filter_data = array();

				$filters = $this->model_catalog_filter->getFilters($filter_group['filter_id']);

				foreach ($filters as $filter) {
					$data = array(
						'filter_category_id' => $category_id,
						'filter_sub_filter'  => true,
						'filter_filter_id'   => $filter_value['filter_id']
					);

					$product_total = $this->model_catalog_product->getTotalProducts($data);

					$total += $product_total;

					$filter_data[] = array(
						'filter_id' => $child['filter_id'],
						'name'      => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'      => $this->url->link('product/filter', 'path=' . $filter['filter_id'] . '_' . $child['filter_id'])	
					);		
				}
				
				if ($filter_data) {
					$this->data['filter_groups'][] = array(
						'name'   => $filter_group['name'],
						'filter' => $filter_data
					);
				}
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