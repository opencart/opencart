<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerExtensionModuleCategory4level extends Controller {
	public function index() {
		$this->load->language('extension/module/category4level');

		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}
		
		if (isset($parts[2])) {
			$data['child_id2'] = $parts[2];
		} else {
			$data['child_id2'] = 0;
		}		
		
		if (isset($parts[3])) {
			$data['child_id3'] = $parts[3];
		} else {
			$data['child_id3'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {

			$children_data = array();

			$children = $this->model_catalog_category->getCategories($category['category_id']);

			foreach ($children as $child) {
			
			$children2_data = array();
			$children2 = $this->model_catalog_category->getCategories($child['category_id']);
			
				foreach ($children2 as $child2) {
				
					$children3_data = array();
					$children3 = $this->model_catalog_category->getCategories($child2['category_id']);
					
						foreach ($children3 as $child3) {
							
							$filter_data3 = array(
								'filter_category_id'  => $child3['category_id'],
							);				

							$children3_data[] = array(
								'category_id' => $child3['category_id'],
								'name'        => $child3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data3) . ')' : ''),
								'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']. '_' . $child2['category_id']. '_' . $child3['category_id'])	
							);		
						
						
						}
				
					$filter_data2 = array(
						'filter_category_id'  => $child2['category_id'],
					);				

					$children2_data[] = array(
						'category_id' => $child2['category_id'],
						'name'        => $child2['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data2) . ')' : ''),
						'children3'    => $children3_data,
						'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id']. '_' . $child2['category_id'])	
					);		
				
				
				}
				
				$filter_data1 = array(
						'filter_category_id'  => $child['category_id'],
					);	

				$children_data[] = array(
					'category_id' => $child['category_id'],
					'name'        => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data1) . ')' : ''),
					'children2'    => $children2_data,
					'href'        => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])	
				);		
			}
			
			$filter_data = array(
				'filter_category_id'  => $category['category_id'],
			);

			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);	
		}

		return $this->load->view('extension/module/category4level', $data);
	}
}