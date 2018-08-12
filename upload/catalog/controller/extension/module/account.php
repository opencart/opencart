<?php
class ControllerExtensionModuleAccount extends Controller {
	public function index() {
		$this->load->language('extension/module/account');

		$data['logged'] = $this->customer->isLogged();
		$data['register'] = $this->url->link('account/register', 'language=' . $this->config->get('config_language'));
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));
		$data['logout'] = $this->url->link('account/logout', 'language=' . $this->config->get('config_language'));
		$data['forgotten'] = $this->url->link('account/forgotten', 'language=' . $this->config->get('config_language'));
		$data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		$data['edit'] = $this->url->link('account/edit', 'language=' . $this->config->get('config_language'));
		$data['password'] = $this->url->link('account/password', 'language=' . $this->config->get('config_language'));
		$data['address'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));
		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language'));
		$data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language'));
		$data['download'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));
		$data['reward'] = $this->url->link('account/reward', 'language=' . $this->config->get('config_language'));
		$data['return'] = $this->url->link('account/return', 'language=' . $this->config->get('config_language'));
		$data['transaction'] = $this->url->link('account/transaction', 'language=' . $this->config->get('config_language'));
		$data['newsletter'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'));
		$data['recurring'] = $this->url->link('account/recurring', 'language=' . $this->config->get('config_language'));

		return $this->load->view('extension/module/account', $data);
	}
}