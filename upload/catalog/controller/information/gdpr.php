<?php
class ControllerInformationGdpr extends Controller {
	public function index() {
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($this->config->get('config_gdpr_id'));

		if ($information_info) {
			$this->load->language('information/gdpr');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('information/gdpr', 'language=' . $this->config->get('config_language'))
			);

			$data['title'] = $information_info['title'];

			$data['gdpr'] = $this->url->link('information/information', 'language=' . $this->config->get('config_language') . '&information_id=' . $information_info['information_id']);

			$data['email'] = $this->customer->getEmail();
			$data['store'] = $this->config->get('config_name');
			$data['limit'] = $this->config->get('config_gdpr_limit');

			$data['cancel'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('information/gdpr', $data));
		} else {
			return new Action('error/not_found');
		}
	}

	public function action() {
		$this->load->language('information/gdpr');

		$json = array();

		if (isset($this->request->post['email'])) {
			$email = $this->request->post['email'];
		} else {
			$email = '';
		}

		if (isset($this->request->post['action'])) {
			$action = $this->request->post['action'];
		} else {
			$action = '';
		}

		// Validate E-Mail
		if ((utf8_strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		// Validate Action
		$allowed = array(
			'export',
			'remove'
		);

		if (!in_array($action, $allowed)) {
			$json['error']['action'] = $this->language->get('error_action');
		}

		if (!$json) {
			$this->load->model('account/gdpr');

			$this->model_account_gdpr->addGdpr(token(), $email, $action);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function success() {
		if (isset($this->request->post['code'])) {
			$code = $this->request->post['code'];
		} else {
			$code = '';
		}

		$this->load->model('account/gdpr');

		$gdpr_info = $this->model_account_gdpr->getGdprByCode($code);

		if ($gdpr_info) {
			$this->load->language('account/gdpr_success');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/gdpr/delete', 'language=' . $this->config->get('config_language'))
			);

			$this->model_account_gdpr->editGdpr($code);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/gdpr_success', $data));
		} else {
			return new Action('error/not_found');
		}
	}
}