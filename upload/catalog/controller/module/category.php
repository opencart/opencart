<?php  
class ControllerModuleCategory extends Controller {
	protected $category_id = 0;
	protected $path = array();
	
	protected function index() {
		$this->load->language('module/category');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/category');
		
		if (isset($this->request->get['path'])) {
			$this->path = explode('_', $this->request->get['path']);
			$this->category_id = end($this->path);
		}
		
		$this->data['category'] = $this->getCategories(0);
												
		$this->id       = 'category';
		$this->template = $this->config->get('config_template') . 'module/category.tpl';
		
		$this->render();
  	}
	
	protected function getCategories($parent_id, $current_path = '') {
		$category_id = array_shift($this->path);
		
		$output = '';
		
		$results = $this->model_catalog_category->getCategories($parent_id);
		
		if ($results) { 
			$output .= '<ul>';
    	}
		
		foreach ($results as $result) {	
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}
			
			$output .= '<li>';
			
			$children = '';
			
			if ($category_id == $result['category_id']) {
				$children = $this->getCategories($result['category_id'], $new_path);
			}
			
			if ($this->category_id == $result['category_id']) {
				$output .= '<a href="' . $this->url->http('product/category&path=' . $new_path)  . '"><b>' . $result['name'] . '</b></a>';
			} else {
				$output .= '<a href="' . $this->url->http('product/category&path=' . $new_path)  . '">' . $result['name'] . '</a>';
			}
			
        	$output .= $children;
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}		
}
?>