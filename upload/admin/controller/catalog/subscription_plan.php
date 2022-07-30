<?php
namespace Opencart\Admin\Controller\Catalog;
use \Opencart\System\Helper as Helper;
class SubscriptionPlan extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('catalog/subscription_plan');

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
			'href' => $this->url->link('catalog/subscription_plan', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/subscription_plan|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['copy'] = $this->url->link('catalog/subscription_plan|copy', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/subscription_plan|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/subscription_plan', $data));
	}

	public function list(): void {
		$this->load->language('catalog/subscription_plan');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'rd.name';
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

		$data['action'] = $this->url->link('catalog/subscription_plan|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['subscription_plans'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/subscription_plan');

		$subscription_plan_total = $this->model_catalog_subscription_plan->getTotalSubscriptionPlans();

		$results = $this->model_catalog_subscription_plan->getSubscriptionPlans($filter_data);

		foreach ($results as $result) {
			$data['subscription_plans'][] = [
				'subscription_plan_id' => $result['subscription_plan_id'],
				'name'                 => $result['name'],
				'sort_order'           => $result['sort_order'],
				'edit'                 => $this->url->link('catalog/subscription_plan|form', 'user_token=' . $this->session->data['user_token'] . '&subscription_plan_id=' . $result['subscription_plan_id'] . $url)
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

		$data['sort_name'] = $this->url->link('catalog/subscription_plan|list', 'user_token=' . $this->session->data['user_token'] . '&sort=spd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/subscription_plan|list', 'user_token=' . $this->session->data['user_token'] . '&sort=sp.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_plan_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/subscription_plan|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_plan_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($subscription_plan_total - $this->config->get('config_pagination_admin'))) ? $subscription_plan_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $subscription_plan_total, ceil($subscription_plan_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/subscription_plan_list', $data);
	}

	public function form(): void {
		$this->load->language('catalog/subscription_plan');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['subscription_plan_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/subscription_plan', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/subscription_plan|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/subscription_plan', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['subscription_plan_id'])) {
			$this->load->model('catalog/subscription_plan');

			$subscription_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($this->request->get['subscription_plan_id']);
		}

		if (isset($this->request->get['subscription_plan_id'])) {
			$data['subscription_plan_id'] = (int)$this->request->get['subscription_plan_id'];
		} else {
			$data['subscription_plan_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['subscription_plan_id'])) {
			$data['subscription_plan_description'] = $this->model_catalog_subscription_plan->getDescription($this->request->get['subscription_plan_id']);
		} else {
			$data['subscription_plan_description'] = [];
		}

		$data['frequencies'] = [];

		$data['frequencies'][] = [
			'text'  => $this->language->get('text_day'),
			'value' => 'day'
		];

		$data['frequencies'][] = [
			'text'  => $this->language->get('text_week'),
			'value' => 'week'
		];

		$data['frequencies'][] = [
			'text'  => $this->language->get('text_semi_month'),
			'value' => 'semi_month'
		];

		$data['frequencies'][] = [
			'text'  => $this->language->get('text_month'),
			'value' => 'month'
		];

		$data['frequencies'][] = [
			'text'  => $this->language->get('text_year'),
			'value' => 'year'
		];

		if (!empty($subscription_info)) {
			$data['trial_price'] = $subscription_info['trial_price'];
		} else {
			$data['trial_price'] = 0.00;
		}

		if (!empty($subscription_info)) {
			$data['trial_frequency'] = $subscription_info['trial_frequency'];
		} else {
			$data['trial_frequency'] = '';
		}

		if (!empty($subscription_info)) {
			$data['trial_duration'] = $subscription_info['trial_duration'];
		} else {
			$data['trial_duration'] = '0';
		}

		if (!empty($subscription_info)) {
			$data['trial_cycle'] = $subscription_info['trial_cycle'];
		} else {
			$data['trial_cycle'] = '1';
		}

		if (!empty($subscription_info)) {
			$data['trial_status'] = $subscription_info['trial_status'];
		} else {
			$data['trial_status'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['price'] = $subscription_info['price'];
		} else {
			$data['price'] = 0.00;
		}

		if (!empty($subscription_info)) {
			$data['frequency'] = $subscription_info['frequency'];
		} else {
			$data['frequency'] = '';
		}

		if (!empty($subscription_info)) {
			$data['duration'] = $subscription_info['duration'];
		} else {
			$data['duration'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['cycle'] = $subscription_info['cycle'];
		} else {
			$data['cycle'] = 1;
		}

		if (!empty($subscription_info)) {
			$data['status'] = $subscription_info['status'];
		} else {
			$data['status'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['sort_order'] = $subscription_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/subscription_plan_form', $data));
	}

	public function save(): void {
		$this->load->language('catalog/subscription_plan');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/subscription_plan')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['subscription_plan_description'] as $language_id => $value) {
			if ((Helper\Utf8\strlen(trim($value['name'])) < 3) || (Helper\Utf8\strlen($value['name']) > 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if ((int)$this->request->post['trial_duration'] < 1) {
			$json['error']['trial_duration'] = $this->language->get('error_trial_duration');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/subscription_plan');

			if (!$this->request->post['subscription_plan_id']) {
				$json['subscription_plan_id'] = $this->model_catalog_subscription_plan->addSubscriptionPlan($this->request->post);
			} else {
				$this->model_catalog_subscription_plan->editSubscriptionPlan($this->request->post['subscription_plan_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function copy(): void {
		$this->load->language('catalog/subscription_plan');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/subscription_plan')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('catalog/subscription_plan');

			foreach ($selected as $subscription_plan_id) {
				$this->model_catalog_subscription_plan->copySubscriptionPlan($subscription_plan_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('catalog/subscription_plan');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/subscription_plan')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($selected as $subscription_id) {
			$product_total = $this->model_catalog_product->getTotalProductsBySubscriptionPlanId($subscription_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/subscription_plan');

			foreach ($selected as $subscription_plan_id) {
				$this->model_catalog_subscription_plan->deleteSubscriptionPlan($subscription_plan_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
