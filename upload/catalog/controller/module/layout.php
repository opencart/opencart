<?php  
class ControllerModuleLayout extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->title;
		$this->data['base'] = (@$this->request->server['HTTPS'] != 'on') ? HTTP_SERVER : HTTPS_SERVER;
		$this->data['charset'] = $this->language->get('charset');
		$this->data['language'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['links'] = $this->document->links;	
		$this->data['styles'] = $this->document->styles;
		$this->data['scripts'] = $this->document->scripts;		
		$this->data['breadcrumbs'] = $this->document->breadcrumbs;
		
		/*
		$google_optimizer = $this->getGoogleOptimizer($this->request->server['product_id']);
			
		if ($google_optimizer) {
			$this->data['control'] = html_entity_decode($google_optimizer['control'], ENT_QUOTES); 
			$this->data['tracking'] = html_entity_decode($google_optimizer['tracking'], ENT_QUOTES);
		} else {
			$this->data['control'] = '';
			$this->data['tracking'] = '';
		}
		*/
		
		$this->template = $this->config->get('config_template') . 'module/layout.tpl';
		$this->children = array(
			'module/header',
			'module/footer',
			'module/category',
			'module/popular',
			'module/cart',
			'module/currency',
			'module/manufacturer',
			'module/information'
		);
		
		$this->render();
	}
	
	
	public function getGoogleOptimizer($product_id) {
		$query = $this->db->query("SELECT * FROM google_optimizer WHERE product_id = '" . (int)$product_id . "' AND status = '1'");
			
		return $query->row;
	}	
}
?>