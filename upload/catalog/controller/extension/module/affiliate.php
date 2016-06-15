<?php
class ControllerExtensionModuleAffiliate extends Controller {
	public function index() {
		$this->load->language('extension/module/affiliate');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_forgotten'] = $this->language->get('text_forgotten');
		$data['text_account'] = $this->language->get('text_account');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_password'] = $this->language->get('text_password');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_tracking'] = $this->language->get('text_tracking');
		$data['text_transaction'] = $this->language->get('text_transaction');

		$data['logged'] = $this->affiliate->isLogged();
		$data['register'] = $this->url->link('affiliate/register', '', true);
		$data['login'] = $this->url->link('affiliate/login', '', true);
		$data['logout'] = $this->url->link('affiliate/logout', '', true);
		$data['forgotten'] = $this->url->link('affiliate/forgotten', '', true);
		$data['account'] = $this->url->link('affiliate/account', '', true);
		$data['edit'] = $this->url->link('affiliate/edit', '', true);
		$data['password'] = $this->url->link('affiliate/password', '', true);
		$data['payment'] = $this->url->link('affiliate/payment', '', true);
		$data['tracking'] = $this->url->link('affiliate/tracking', '', true);
		$data['transaction'] = $this->url->link('affiliate/transaction', '', true);

		return $this->load->view('extension/module/affiliate', $data);
	}
}