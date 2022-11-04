<?php
namespace Opencart\Admin\Controller\Localisation;
class SubscriptionStatus extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('localisation/subscription_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/subscription_status', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/subscription_status.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/subscription_status.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/subscription_status', $data));
	}

	public function list(): void {
		$this->load->language('localisation/subscription_status');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('localisation/subscription_status.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['subscription_statuses'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/subscription_status');

		$subscription_status_total = $this->model_localisation_subscription_status->getTotalSubscriptionStatuses();

		$results = $this->model_localisation_subscription_status->getSubscriptionStatuses($filter_data);

		foreach ($results as $result) {
			$data['subscription_statuses'][] = [
				'subscription_status_id' => $result['subscription_status_id'],
				'name'                   => $result['name'] . (($result['subscription_status_id'] == $this->config->get('config_subscription_status_id')) ? $this->language->get('text_default') : ''),
				'edit'                   => $this->url->link('localisation/subscription_status.form', 'user_token=' . $this->session->data['user_token'] . '&subscription_status_id=' . $result['subscription_status_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('localisation/subscription_status.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_status_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/subscription_status.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_status_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($subscription_status_total - $this->config->get('config_pagination_admin'))) ? $subscription_status_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $subscription_status_total, ceil($subscription_status_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/subscription_status_list', $data);
	}

	public function form(): void {
		$this->load->language('localisation/subscription_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['subscription_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/subscription_status', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/subscription_status.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/subscription_status', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['subscription_status_id'])) {
			$data['subscription_status_id'] = (int)$this->request->get['subscription_status_id'];
		} else {
			$data['subscription_status_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['subscription_status_id'])) {
			$this->load->model('localisation/subscription_status');

			$data['subscription_status'] = $this->model_localisation_subscription_status->getDescriptions($this->request->get['subscription_status_id']);
		} else {
			$data['subscription_status'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/subscription_status_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/subscription_status');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/subscription_status')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['subscription_status'] as $language_id => $value) {
			if ((oc_strlen($value['name']) < 3) || (oc_strlen($value['name']) > 32)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (!$json) {
			$this->load->model('localisation/subscription_status');

			if (!$this->request->post['subscription_status_id']) {
				$json['subscription_status_id'] = $this->model_localisation_subscription_status->addSubscriptionStatus($this->request->post);
			} else {
				$this->model_localisation_subscription_status->editSubscriptionStatus($this->request->post['subscription_status_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/subscription_status');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/subscription_status')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('sale/subscription');

		foreach ($selected as $subscription_status_id) {
			if ($this->config->get('config_subscription_status_id') == $subscription_status_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$subscription_total = $this->model_sale_subscription->getTotalSubscriptionsBySubscriptionStatusId($subscription_status_id);

			if ($subscription_total) {
				$json['error'] = sprintf($this->language->get('error_subscription'), $subscription_total);
			}

			$subscription_total = $this->model_sale_subscription->getTotalHistoriesBySubscriptionStatusId($subscription_status_id);

			if ($subscription_total) {
				$json['error'] = sprintf($this->language->get('error_subscription'), $subscription_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/subscription_status');

			foreach ($selected as $subscription_status_id) {
				$this->model_localisation_subscription_status->deleteSubscriptionStatus($subscription_status_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
