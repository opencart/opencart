<?php
namespace Opencart\Catalog\Controller\Account;
class Newsletter extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_newsletter'),
			'href' => $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'))
		];

		$data['save'] = $this->url->link('account/newsletter|save', 'language=' . $this->config->get('config_language'));

		$data['newsletter'] = $this->customer->getNewsletter();

		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/newsletter', $data));
	}

	public function save(): void {
		$this->load->language('account/newsletter');

		$json = [];

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));
		}

		if (!$json) {
			$this->load->model('account/customer');

			$this->model_account_customer->editNewsletter($this->request->post['newsletter']);

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}