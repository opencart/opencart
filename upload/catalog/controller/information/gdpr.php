<?php
namespace Opencart\Catalog\Controller\Information;
/**
 * Class Gdpr
 *
 * @package Opencart\Catalog\Controller\Information
 */
class Gdpr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function index() {
		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_gdpr_id'));

		if ($information_info) {
			$this->load->language('information/gdpr');

			$this->document->setTitle($this->language->get('heading_title'));

			$this->document->addScript('catalog/view/javascript/gdpr.js');

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

	/**
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
	/**
	 * Action
	 *
	 * @return void
	 */
	public function action(): void {
		$this->load->language('information/gdpr');

		$json = [];

		// Add keys for missing post vars
		$required = [
			'email'  => '',
			'action' => '',
		];

		$post_info = $this->request->post + $required;

		// Validate E-Mail
		if (!oc_validate_email($post_info['email'])) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		// Validate Action
		$allowed = [
			'export',
			'remove'
		];

		if (!in_array($post_info['action'], $allowed)) {
			$json['error']['action'] = $this->language->get('error_action');
		}

		if (!$json) {
			// Added additional check so people are not spamming requests
			$status = true;

			// GDPR
			$this->load->model('account/gdpr');

			$results = $this->model_account_gdpr->getGdprsByEmail($post_info['email']);

			foreach ($results as $result) {
				if ($result['action'] == $post_info['action']) {
					$status = false;

					break;
				}
			}

			if ($status) {
				$this->model_account_gdpr->addGdpr(oc_token(32), $post_info['email'], $post_info['action']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Success
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function success() {
		if (isset($this->request->get['code'])) {
			$code = (string)$this->request->get['code'];
		} else {
			$code = '';
		}

		// GDPR
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
				$this->model_account_gdpr->editStatus($gdpr_info['gdpr_id'], 1);
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
