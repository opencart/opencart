<?php  
class ControllerInformationSitemap extends Controller {
	public function index() {
    	$this->language->load('information/sitemap');
 
		$this->document->title = $this->language->get('heading_title'); 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=information/sitemap',
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_edit'] = $this->language->get('text_edit');
    	$this->data['text_password'] = $this->language->get('text_password');
    	$this->data['text_address'] = $this->language->get('text_address');
    	$this->data['text_history'] = $this->language->get('text_history');
    	$this->data['text_download'] = $this->language->get('text_download');
    	$this->data['text_cart'] = $this->language->get('text_cart');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');
    	$this->data['text_search'] = $this->language->get('text_search');
    	$this->data['text_information'] = $this->language->get('text_information');
    	$this->data['text_contact'] = $this->language->get('text_contact');
		
		$this->load->model('catalog/category');
		
		$this->load->model('tool/seo_url');
		
		$this->data['category'] = $this->getCategories(0);
		
		$this->data['special'] = HTTPS_SERVER . 'index.php?route=product/special';
		$this->data['account'] = HTTPS_SERVER . 'index.php?route=account/account';
    	$this->data['edit'] = HTTPS_SERVER . 'index.php?route=account/edit';
    	$this->data['password'] = HTTPS_SERVER . 'index.php?route=account/password';
    	$this->data['address'] = HTTPS_SERVER . 'index.php?route=account/address';
    	$this->data['history'] = HTTPS_SERVER . 'index.php?route=account/history';
    	$this->data['download'] = HTTPS_SERVER . 'index.php?route=account/download';
    	$this->data['cart'] = HTTP_SERVER . 'index.php?route=checkout/cart';
    	$this->data['checkout'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
    	$this->data['search'] = HTTP_SERVER . 'index.php?route=product/search';
    	$this->data['contact'] = HTTP_SERVER . 'index.php?route=information/contact';
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();
    	
		foreach ($this->model_catalog_information->getInformations() as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
        		'href'  => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=information/information&information_id=' . $result['information_id'])
      		);
    	}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/information/sitemap.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/information/sitemap.tpl';
		} else {
			$this->template = 'default/template/information/sitemap.tpl';
		}
		
		$this->children = array(
			'common/column_right',
			'common/footer',
			'common/column_left',
			'common/header'
		);
		
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));		
	}
	
	protected function getCategories($parent_id, $current_path = '') {
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
			
			$output .= '<a href="' . $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/category&path=' . $new_path)  . '">' . $result['name'] . '</a>';
			
        	$output .= $this->getCategories($result['category_id'], $new_path);
        
        	$output .= '</li>'; 
		}
 
		if ($results) {
			$output .= '</ul>';
		}
		
		return $output;
	}	
}
?>