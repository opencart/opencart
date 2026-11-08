<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Edit
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Edit extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/edit');

		$json = [];

		$required = [
			'firstname' => '',
			'lastname'  => '',
			'email'     => '',
			'telephone' => ''
		];

		$post_info = $this->request->post + $required;

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/edit', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			if (!oc_validate_length($post_info['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_email($post_info['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			// Customer
			$this->load->model('account/customer');

			if (($this->customer->getEmail() != $post_info['email']) && $this->model_account_customer->getTotalCustomersByEmail($post_info['email'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}

			if ($this->config->get('config_telephone_required') && !oc_validate_length($post_info['telephone'], 3, 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}
		}

		if (!$json) {
			// Update customer in db
			$this->model_account_customer->editCustomer($this->customer->getId(), $post_info);

			$json['success'] = $this->language->get('text_success');

			// Update customer session details
			$this->session->data['customer'] = [
				'customer_id'       => $this->customer->getId(),
				'customer_group_id' => $this->customer->getGroupId(),
				'firstname'         => $post_info['firstname'],
				'lastname'          => $post_info['lastname'],
				'email'             => $post_info['email'],
				'telephone'         => $post_info['telephone']
			];

			unset($this->session->data['order_id']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
