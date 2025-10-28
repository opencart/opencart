<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Affiliate
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Affiliate extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/affiliate');

		if (!$this->load->controller('account/login.validate')) {
			$this->customer->logout();

			$this->session->data['redirect'] = $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/affiliate.js');

		$data['error_upload_size'] = sprintf($this->language->get('error_upload_size'), $this->config->get('config_file_max_size'));

		$data['config_file_max_size'] = ((int)$this->config->get('config_file_max_size') * 1024 * 1024);

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_affiliate'),
			'href' => $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['save'] = $this->url->link('account/affiliate.save', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$this->session->data['upload_token'] = oc_token(32);

		$data['upload'] = $this->url->link('tool/upload', 'language=' . $this->config->get('config_language') . '&upload_token=' . $this->session->data['upload_token']);

		// Affiliate
		$this->load->model('account/affiliate');

		$affiliate_info = $this->model_account_affiliate->getAffiliate($this->customer->getId());

		if (!empty($affiliate_info)) {
			$data['company'] = $affiliate_info['company'];
		} else {
			$data['company'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['website'] = $affiliate_info['website'];
		} else {
			$data['website'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['tax'] = $affiliate_info['tax'];
		} else {
			$data['tax'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['payment_method'] = $affiliate_info['payment_method'];
		} else {
			$data['payment_method'] = 'cheque';
		}

		if (!empty($affiliate_info)) {
			$data['cheque'] = $affiliate_info['cheque'];
		} else {
			$data['cheque'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['paypal'] = $affiliate_info['paypal'];
		} else {
			$data['paypal'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_name'] = $affiliate_info['bank_name'];
		} else {
			$data['bank_name'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_branch_number'] = $affiliate_info['bank_branch_number'];
		} else {
			$data['bank_branch_number'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_swift_code'] = $affiliate_info['bank_swift_code'];
		} else {
			$data['bank_swift_code'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_account_name'] = $affiliate_info['bank_account_name'];
		} else {
			$data['bank_account_name'] = '';
		}

		if (!empty($affiliate_info)) {
			$data['bank_account_number'] = $affiliate_info['bank_account_number'];
		} else {
			$data['bank_account_number'] = '';
		}

		// Custom Fields
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'affiliate') {
				$data['custom_fields'][] = $custom_field;
			}
		}

		if (!empty($affiliate_info)) {
			$data['affiliate_custom_field'] = $affiliate_info['custom_field'];
		} else {
			$data['affiliate_custom_field'] = [];
		}

		$affiliate_info = $this->model_account_affiliate->getAffiliate($this->customer->getId());

		if (!$affiliate_info && $this->config->get('config_affiliate_id')) {
			// Information
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_affiliate_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information.info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_affiliate_id')), $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['language'] = $this->config->get('config_language');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/affiliate', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('account/affiliate');

		$json = [];

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/affiliate', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$this->config->get('config_affiliate_status')) {
			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
		}

		if (!$json) {
			$required = [
				'payment_method'      => '',
				'cheque'              => '',
				'paypal'              => '',
				'bank_account_name'   => '',
				'bank_account_number' => '',
				'agree'               => 0
			];

			$post_info = $this->request->post + $required;

			// Payment validation
			if (empty($post_info['payment_method'])) {
				$json['error']['payment_method'] = $this->language->get('error_payment_method');
			}

			if ($post_info['payment_method'] == 'cheque' && !$post_info['cheque']) {
				$json['error']['cheque'] = $this->language->get('error_cheque');
			} elseif ($post_info['payment_method'] == 'paypal' && ((oc_strlen($post_info['paypal']) > 96) || !filter_var($post_info['paypal'], FILTER_VALIDATE_EMAIL))) {
				$json['error']['paypal'] = $this->language->get('error_paypal');
			} elseif ($post_info['payment_method'] == 'bank') {
				if ($post_info['bank_account_name'] == '') {
					$json['error']['bank_account_name'] = $this->language->get('error_bank_account_name');
				}

				if ($post_info['bank_account_number'] == '') {
					$json['error']['bank_account_number'] = $this->language->get('error_bank_account_number');
				}
			}

			// Custom fields validation
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields((int)$this->config->get('config_customer_group_id'));

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['location'] == 'affiliate') {
					if ($custom_field['required'] && empty($post_info['custom_field'][$custom_field['custom_field_id']])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text') && !empty($custom_field['validation']) && !oc_validate_regex($post_info['custom_field'][$custom_field['custom_field_id']], $custom_field['validation'])) {
						$json['error']['custom_field_' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_regex'), $custom_field['name']);
					}
				}
			}

			// Validate agree only if customer not already an affiliate
			$this->load->model('account/affiliate');

			$affiliate_info = $this->model_account_affiliate->getAffiliate($this->customer->getId());

			if (!$affiliate_info) {
				// Information
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_affiliate_id'));

				if ($information_info && !$post_info['agree']) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}

		if (!$json) {
			if (!$affiliate_info) {
				$this->model_account_affiliate->addAffiliate($this->customer->getId(), $post_info);
			} else {
				$this->model_account_affiliate->editAffiliate($this->customer->getId(), $post_info);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$json['redirect'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'], true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
