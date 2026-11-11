<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Newsletter
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Newsletter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/newsletter');

		$json = [];

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Customer
			$this->load->model('account/customer');

			$this->model_account_customer->editNewsletter($this->customer->getId(), !zempty($this->request->post['newsletter']));

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
