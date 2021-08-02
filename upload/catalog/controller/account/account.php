<?php
namespace Opencart\Catalog\Controller\Account;
class Account extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/account');

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

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['edit'] = $this->url->link('account/edit', 'language=' . $this->config->get('config_language'));
		$data['password'] = $this->url->link('account/password', 'language=' . $this->config->get('config_language'));
		$data['address'] = $this->url->link('account/address', 'language=' . $this->config->get('config_language'));

		$data['credit_cards'] = [];

		// Get a list of installed modules
		$results = $this->model_setting_extension->getExtensionsByType('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status') && $this->config->get('payment_' . $result['code'] . '_card')) {
				$this->load->language('extension/' . $result['extension'] . '/credit_card/' . $result['code'], 'extension');

				$data['credit_cards'][] = [
					'name' => $this->language->get('heading_title', 'extension'),
					'href' => $this->url->link('extension/' . $result['extension'] . '/credit_card/' . $result['code'], 'language=' . $this->config->get('config_language'))
				];
			}
		}

		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language'));
		$data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language'));
		$data['download'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));

		if ($this->config->get('total_reward_status')) {
			$data['reward'] = $this->url->link('account/reward', 'language=' . $this->config->get('config_language'));
		} else {
			$data['reward'] = '';
		}

		$data['return'] = $this->url->link('account/returns', 'language=' . $this->config->get('config_language'));
		$data['transaction'] = $this->url->link('account/transaction', 'language=' . $this->config->get('config_language'));
		$data['newsletter'] = $this->url->link('account/newsletter', 'language=' . $this->config->get('config_language'));
		$data['recurring'] = $this->url->link('account/recurring', 'language=' . $this->config->get('config_language'));

		if ($this->config->get('config_affiliate_status')) {
			$data['affiliate'] = $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language'));

			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliate($this->customer->getId());

			if ($affiliate_info) {
				$data['tracking'] = $this->url->link('account/tracking', 'language=' . $this->config->get('config_language'));
			} else {
				$data['tracking'] = '';
			}
		} else {
			$data['affiliate'] = false;
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/account', $data));
	}
}
