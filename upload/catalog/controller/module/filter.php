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
			
			$this->data['button_filter'] = $this->language->get('button_filter');
			
			if (isset($this->session->data['filter'][$category_id])) {
				$this->data['filter_category'] = $this->session->data['filter'][$category_id];
			} else {
				$this->data['filter_category'] = array();
			}
			
			$this->load->model('catalog/filter');

			$this->load->model('catalog/product');

			$this->data['filter_groups'] = array();

			$filter_groups = $this->model_catalog_filter->getFilterGroupsByCategoryId($category_id);

			foreach ($filter_groups as $filter_group) {
				$filter_data = array();

				$filters = $this->model_catalog_filter->getFiltersByFilterGroupId($filter_group['filter_group_id']);

				foreach ($filters as $filter) {
					$data = array(
						'filter_category_id' => $category_id,
						'filter_sub_filter'  => true,
						'filter_filter'      => array($filter['filter_id'])
					);
										
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($data) . ')' : '')	
					);
				}
				
				if ($filter_data) {
					$this->data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
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
	
	public function filter() {
		$this->session->data['filter_category'][] = $this->request->post['filter'];	
		
		print_r($this->request->post);
	}
}
?>