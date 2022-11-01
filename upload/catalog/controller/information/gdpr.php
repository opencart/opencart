<?php
namespace Opencart\Catalog\Controller\Information;
class Gdpr extends \Opencart\System\Engine\Controller {
	public function index(): object|null {
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation($this->config->get('config_gdpr_id'));

		if ($information_info) {
			$this->load->language('information/gdpr');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('information/gdpr', 'language=' . $this->config->get('config_language'))
			];

			$data['action'] = $this->url->link('information/gdpr.action', 'language=' . $this->config->get('config_language'));

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

			return null;
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}
	}

	/*
	 *  Action Statuses
	 *
	 *	EXPORT
	 *
	 *  unverified = 0
	 *	pending    = 1
	 *	complete   = 3
	 *
	 *	REMOVE
	 *
	 *  unverified = 0
	 *	pending    = 1
	 *	processing = 2
	 *	delete     = 3
	 *
	 *	DENY
	 *
	 *  unverified = 0
	 *	pending    = 1
	 *	processing = 2
	 *	denied     = -1
	*/
	public function action(): void {
		$this->load->language('information/gdpr');

		$json = [];

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
		if ((oc_strlen($email) > 96) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		// Validate Action
		$allowed = [
			'export',
			'remove'
		];

		if (!in_array($action, $allowed)) {
			$json['error']['action'] = $this->language->get('error_action');
		}

		if (!$json) {
			// Added additional check so people are not spamming requests
			$status = true;

			$this->load->model('account/gdpr');

			$results = $this->model_account_gdpr->getGdprsByEmail($email);

			foreach ($results as $result) {
				if ($result['action'] == $action) {
					$status = false;

					break;
				}
			}

			if ($status) {
				$this->model_account_gdpr->addGdpr(oc_token(32), $email, $action);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function success(): object|null {
		if (isset($this->request->get['code'])) {
			$code = $this->request->get['code'];
		} else {
			$code = '';
		}

		$this->load->model('account/gdpr');

		$gdpr_info = $this->model_account_gdpr->getGdprByCode($code);

		if ($gdpr_info) {
			$this->load->language('information/gdpr_success');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('information/gdpr', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('information/gdpr.success', 'language=' . $this->config->get('config_language'))
			];

			if ($gdpr_info['status'] == 0) {
				$this->model_account_gdpr->editStatus($code, 1);
			}

			if ($gdpr_info['action'] == 'export') {
				$data['text_message'] = $this->language->get('text_export');
			} else {
				$data['text_message'] = sprintf($this->language->get('text_remove'), $this->config->get('config_gdpr_limit'));
			}

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('common/success', $data));

			return null;
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}
	}
}