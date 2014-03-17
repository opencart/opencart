<?php 
class ControllerAffiliateLogout extends Controller {
	public function index() {
		if ($this->affiliate->isLogged()) {
			$this->affiliate->logout();
			
			$this->response->redirect($this->url->link('affiliate/logout', '', 'SSL'));
		}
 
		$this->load->language('affiliate/logout');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addBreadcrumb( $this->language->get('text_home'), $this->url->link('common/home') );
		$this->document->addBreadcrumb( $this->language->get('text_account'), $this->url->link('affiliate/account', '', 'SSL') );
		$this->document->addBreadcrumb( $this->language->get('text_logout'), $this->url->link('affiliate/logout', '', 'SSL') );

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_message'] = $this->language->get('text_message');

		$data['button_continue'] = $this->language->get('button_continue');

		$data['continue'] = $this->url->link('common/home');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
						
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/success.tpl', $data));
		}
	}
}