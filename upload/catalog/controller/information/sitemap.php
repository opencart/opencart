<?php  
class ControllerInformationSitemap extends Controller {
	public function index() {
    	$this->load->language('information/sitemap');
 
		$this->document->title = $this->language->get('heading_title'); 

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	);

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('information/sitemap'),
        	'text'      => $this->language->get('heading_title'),
        	'separator' => $this->language->get('text_separator')
      	);	
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

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
		
		$this->data['category'] = $this->getCategories(0);
		
    	$this->data['account'] = $this->url->https('account/account');
    	$this->data['edit'] = $this->url->https('account/edit');
    	$this->data['password'] = $this->url->https('account/password');
    	$this->data['address'] = $this->url->https('account/address');
    	$this->data['history'] = $this->url->https('account/history');
    	$this->data['download'] = $this->url->https('account/download');
    	$this->data['cart'] = $this->url->http('checkout/cart');
    	$this->data['checkout'] = $this->url->https('checkout/shipping');
    	$this->data['search'] = $this->url->http('product/search');
    	$this->data['contact'] = $this->url->http('information/contact');
		
		$this->load->model('catalog/information');
		
		$this->data['informations'] = array();
    	
		foreach ($this->model_catalog_information->getInformations() as $result) {
      		$this->data['informations'][] = array(
        		'title' => $result['title'],
        		'href'  => $this->url->http('information/information&information_id=' . $result['information_id'])
      		);
    	}
		
  		$this->id       = 'content';
		$this->template = 'information/sitemap.tpl';
		$this->layout   = 'module/layout';
		
 		$this->render();		
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
			
			$output .= '<a href="' . $this->url->http('product/category&path=' . $new_path)  . '">' . $result['name'] . '</a>';
			
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