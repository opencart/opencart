<?php
class ControllerAffiliatePassword extends Controller {
	private $error = array();

	public function index() {
		if (!$this->affiliate->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('affiliate/password', '', true);

			$this->response->redirect($this->url->link('affiliate/login', '', true));
		}

		$this->load->language('affiliate/password');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('affiliate/affiliate');

			$this->model_affiliate_affiliate->editPassword($this->affiliate->getEmail(), $this->request->post['password']);

			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			if ($this->config->get('config_customer_activity')) {
				$this->load->model('affiliate/activity');

				$activity_data = array(
					'affiliate_id' => $this->affiliate->getId(),
					'name'         => $this->affiliate->getFirstName() . ' ' . $this->affiliate->getLastName()
				);

				$this->model_affiliate_activity->addActivity('password', $activity_data);
			}

			$this->response->redirect($this->url->link('affiliate/account', '', true));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('affiliate/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('affiliate/password', '', true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_password'] = $this->language->get('text_password');

		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_confirm'] = $this->language->get('entry_confirm');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}

		$data['action'] = $this->url->link('affiliate/password', '', true);

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}

		$data['back'] = $this->url->link('affiliate/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('affiliate/password', $data));
	}

	protected function validate() {
		if ((utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, "UTF-8")) < 4) || (utf8_strlen(html_entity_decode($this->request->post['password'], ENT_QUOTES, "UTF-8")) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}

		return !$this->error;
	}
}