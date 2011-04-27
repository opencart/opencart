<?php  
class ControllerModuleCategory extends Controller {
	protected function index() {
		$this->language->load('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories_1 = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$data = array(
					'filter_category_id'  => $category_2['category_id'],
					'filter_sub_category' => true	
				);		
					
				$product_total = $this->model_catalog_product->getTotalProducts($data);
							
				$level_2_data[] = array(
					'name'     => $category_2['name'] . ' (' . $product_total . ')',
					'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'] . '_' . $category_2['category_id'])	
				);					
			}
			
			$data = array(
				'filter_category_id'  => $category_1['category_id'],
				'filter_sub_category' => true	
			);		
				
			$product_total = $this->model_catalog_product->getTotalProducts($data);
						
			$this->data['categories'][] = array(
				'name'     => $category_1['name'] . ' (' . $product_total . ')',
				'children' => $level_2_data,
				'href'     => $this->url->link('product/category', 'path=' . $category_1['category_id'])
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/category.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/category.tpl';
		} else {
			$this->template = 'default/template/module/category.tpl';
		}
		
		$this->render();
  	}
}
?>