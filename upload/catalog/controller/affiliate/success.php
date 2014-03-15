<?php 
class ControllerAffiliateSuccess extends Controller {  
	public function index() {
		$this->load->language('affiliate/success');
  
		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addBreadcrumb( $this->language->get('text_home'), $this->url->link('common/home') );
		$this->document->addBreadcrumb( $this->language->get('text_account'), $this->url->link('affiliate/account', '', 'SSL') );
		$this->document->addBreadcrumb( $this->language->get('text_success'), $this->url->link('affiliate/success') );

		$data['heading_title'] = $this->language->get('heading_title');

		if (!$this->config('config_affiliate_approval')) {
			$data['text_message'] = sprintf($this->language->get('text_message'), $this->config->get('config_name'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
		}
				
		$data['button_continue'] = $this->language->get('button_continue');
		
		$data['continue'] = $this->url->link('affiliate/account', '', 'SSL');
		
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}					
	}
}