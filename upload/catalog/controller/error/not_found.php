<?php   
class ControllerErrorNotFound extends Controller {
	public function index() {		
		$this->load->language('error/not_found');
		
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->document->addBreadcrumb( $this->language->get('text_home'), $this->url->link('common/home') );	
		
		if (isset($this->request->get['route'])) {
			$url_data = $this->request->get;
			
			unset($url_data['_route_']);
			
			$route = $url_data['route'];
			
			unset($url_data['route']);
			
			$url = '';
			
			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}	
			
			if ($this->request->server['HTTPS']) {
				$connection = 'SSL';
			} else {
				$connection = 'NONSSL';
			}
											
       		$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link($route, $url, $connection)
      		);	   	
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_error'] = $this->language->get('text_error');
		
		$data['button_continue'] = $this->language->get('button_continue');
		
		$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
		
		$data['continue'] = $this->url->link('common/home');
		
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
		}
  	}
}