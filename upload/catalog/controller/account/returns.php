<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Returns
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Returns extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/returns');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/returns', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$limit = 10;

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/returns', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . $url)
		];

		// Returns
		$data['returns'] = [];

		$this->load->model('account/returns');

		$results = $this->model_account_returns->getReturns(($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['returns'][] = [
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'       => $this->url->link('account/returns.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&return_id=' . $result['return_id'] . $url)
			] + $result;
		}

		// Total Returns
		$return_total = $this->model_account_returns->getTotalReturns();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/returns', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($return_total - $limit)) ? $return_total : ((($page - 1) * $limit) + $limit), $return_total, ceil($return_total / $limit));

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/returns_list', $data));
	}

	/**
	 * Info
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function info(): ?\Opencart\System\Engine\Action {
		$this->load->language('account/returns');

		if (isset($this->request->get['return_id'])) {
			$return_id = (int)$this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/returns.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		// Returns
		$this->load->model('account/returns');

		$return_info = $this->model_account_returns->getReturn($return_id);

		if ($return_info) {
			$this->document->setTitle($this->language->get('text_return'));

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
			];

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/returns', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . $url)
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_return'),
				'href' => $this->url->link('account/returns.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&return_id=' . $return_id . $url)
			];

			$data['return_id'] = $return_info['return_id'];
			$data['order_id'] = $return_info['order_id'];
			$data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($return_info['date_added']));

			$data['customer_id'] = $return_info['customer_id'];
			$data['firstname'] = $return_info['firstname'];
			$data['lastname'] = $return_info['lastname'];
			$data['email'] = $return_info['email'];
			$data['telephone'] = $return_info['telephone'];

			$data['product'] = $return_info['product'];
			$data['model'] = $return_info['model'];
			$data['quantity'] = $return_info['quantity'];
			$data['reason'] = $return_info['reason'];
			$data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
			$data['action'] = $return_info['action'];
			$data['comment'] = nl2br($return_info['comment']);

			// History
			$data['history'] = $this->getHistory();

			$data['continue'] = $this->url->link('account/returns', 'language=' . $this->config->get('config_language') . $url . '&customer_token=' . $this->session->data['customer_token']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/returns_info', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}

	/**
	 * Add
	 *
	 * @return void
	 */
	public function add(): void {
		$this->load->language('account/returns');

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
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/returns.add', 'language=' . $this->config->get('config_language'))
		];

		$data['config_telephone_display'] = $this->config->get('config_telephone_display');
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$this->session->data['return_token'] = oc_token(26);

		$data['save'] = $this->url->link('account/returns.save', 'language=' . $this->config->get('config_language') . '&return_token=' . $this->session->data['return_token']);

		// Order
		$this->load->model('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->get['order_id']);
		}

		// Product
		$this->load->model('catalog/product');

		if (isset($this->request->get['product_id'])) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $order_info['order_id'];
		} else {
			$data['order_id'] = '';
		}

		if (!empty($product_info)) {
			$data['product_id'] = $product_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (!empty($order_info)) {
			$data['date_ordered'] = date('Y-m-d', strtotime($order_info['date_added']));
		} else {
			$data['date_ordered'] = '';
		}

		if (!empty($order_info)) {
			$data['firstname'] = $order_info['firstname'];
		} else {
			$data['firstname'] = $this->customer->getFirstName();
		}

		if (!empty($order_info)) {
			$data['lastname'] = $order_info['lastname'];
		} else {
			$data['lastname'] = $this->customer->getLastName();
		}

		if (!empty($order_info)) {
			$data['email'] = $order_info['email'];
		} else {
			$data['email'] = $this->customer->getEmail();
		}

		if (!empty($order_info)) {
			$data['telephone'] = $order_info['telephone'];
		} else {
			$data['telephone'] = $this->customer->getTelephone();
		}

		if (!empty($product_info)) {
			$data['product'] = $product_info['name'];
		} else {
			$data['product'] = '';
		}

		if (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		// Return Reasons
		$this->load->model('localisation/return_reason');

		$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('returns', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		// Information
		$this->load->model('catalog/information');

		$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_return_id'));

		if ($information_info) {
			$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information.info', 'language=' . $this->config->get('config_language') . '&information_id=' . $this->config->get('config_return_id')), $information_info['title']);
		} else {
			$data['text_agree'] = '';
		}

		$data['back'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/returns_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('account/returns');

		$json = [];

		if (!isset($this->request->get['return_token']) || !isset($this->session->data['return_token']) || ($this->request->get['return_token'] != $this->session->data['return_token'])) {
			$json['redirect'] = $this->url->link('account/returns.add', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			$required = [
				'order_id'         => 0,
				'firstname'        => '',
				'lastname'         => '',
				'email'            => '',
				'telephone'        => '',
				'product'          => '',
				'model'            => '',
				'return_reason_id' => 0,
				'agree'            => 0
			];

			$post_info = $this->request->post + $required;

			if (!$post_info['order_id']) {
				$json['error']['order_id'] = $this->language->get('error_order_id');
			}

			if (!oc_validate_length($post_info['firstname'], 1, 32)) {
				$json['error']['firstname'] = $this->language->get('error_firstname');
			}

			if (!oc_validate_length($post_info['lastname'], 1, 32)) {
				$json['error']['lastname'] = $this->language->get('error_lastname');
			}

			if (!oc_validate_email($post_info['email'])) {
				$json['error']['email'] = $this->language->get('error_email');
			}

			if ($this->config->get('config_telephone_required') && !oc_validate_length($post_info['telephone'], 3, 32)) {
				$json['error']['telephone'] = $this->language->get('error_telephone');
			}

			if (!oc_validate_length($post_info['product'], 1, 255)) {
				$json['error']['product'] = $this->language->get('error_product');
			}

			if (!oc_validate_length($post_info['model'], 1, 64)) {
				$json['error']['model'] = $this->language->get('error_model');
			}

			if (empty($post_info['return_reason_id'])) {
				$json['error']['reason'] = $this->language->get('error_reason');
			}

			// Captcha
			$this->load->model('setting/extension');

			$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

			if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('returns', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

				if ($captcha) {
					$json['error']['captcha'] = $captcha;
				}
			}

			if ($this->config->get('config_return_id')) {
				// Information
				$this->load->model('catalog/information');

				$information_info = $this->model_catalog_information->getInformation((int)$this->config->get('config_return_id'));

				if ($information_info && !isset($post_info['agree'])) {
					$json['error']['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
				}
			}
		}

		if (!$json) {
			// Return
			$this->load->model('account/returns');

			$this->model_account_returns->addReturn($post_info);

			// Remove form token
			unset($this->session->data['return_token']);

			$json['redirect'] = $this->url->link('account/returns.success', 'language=' . $this->config->get('config_language'), true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Success
	 *
	 * @return void
	 */
	public function success(): void {
		$this->load->language('account/returns');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/returns.add', 'language=' . $this->config->get('config_language'))
		];

		$data['continue'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function history(): void {
		$this->load->language('account/return');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/returns', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	protected function getHistory(): string {
		if (isset($this->request->get['return_id'])) {
			$return_id = (int)$this->request->get['return_id'];
		} else {
			$return_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'account/return.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		if (!$this->load->controller('account/login.validate')) {
			return '';
		}

		// Histories
		$data['histories'] = [];

		$this->load->model('account/returns');

		$results = $this->model_account_returns->getHistories($return_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Histories
		$return_total = $this->model_account_returns->getTotalHistories($return_id);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $return_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/return.history', 'customer_token=' . $this->session->data['customer_token'] . '&return_id=' . $return_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($return_total - $limit)) ? $return_total : ((($page - 1) * $limit) + $limit), $return_total, ceil($return_total / $limit));

		return $this->load->view('account/returns_history', $data);
	}
}
