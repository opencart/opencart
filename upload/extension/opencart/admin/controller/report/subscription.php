<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * Class Subscription
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Report
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/subscription');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/subscription', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_subscription_discount_report'),
			'href' => $this->url->link('extension/opencart/report/subscription.report_subscription_discount', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/subscription.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_subscription_status'] = $this->config->get('report_subscription_status');
		$data['report_subscription_sort_order'] = $this->config->get('report_subscription_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/subscription_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/subscription');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_subscription', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Report
	 *
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/report/subscription');

		$data['list'] = $this->getReport();

		$this->load->model('localisation/subscription_status');

		$data['subscription_statuses'] = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		$data['groups'] = [];

		$data['groups'][] = [
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		];

		$data['groups'][] = [
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		];

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/subscription', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/subscription');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * Get Report
	 *
	 * @return string
	 */
	public function getReport(): string {
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$filter_subscription_status_id = (int)$this->request->get['filter_subscription_status_id'];
		} else {
			$filter_subscription_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['subscriptions'] = [];

		$filter_data = [
			'filter_date_start'             => $filter_date_start,
			'filter_date_end'               => $filter_date_end,
			'filter_group'                  => $filter_group,
			'filter_subscription_status_id' => $filter_subscription_status_id,
			'start'                         => ($page - 1) * $this->config->get('config_pagination'),
			'limit'                         => $this->config->get('config_pagination')
		];

		$this->load->model('extension/opencart/report/subscription');

		$subscription_total = $this->model_extension_opencart_report_subscription->getTotalSubscriptions($filter_data);

		$results = $this->model_extension_opencart_report_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			$data['subscriptions'][] = [
				'date_start'    => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'      => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'subscriptions' => $result['subscriptions'],
				'products'      => $result['products'],
				'tax'           => $this->currency->format((float)$result['tax'], $this->config->get('config_currency')),
				'total'         => $this->currency->format((float)$result['total'], $this->config->get('config_currency'))
			];
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$url .= '&filter_subscription_status_id=' . $this->request->get['filter_subscription_status_id'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/subscription', 'user_token=' . $this->session->data['user_token'] . '&code=subscription' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($subscription_total - $this->config->get('config_pagination'))) ? $subscription_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $subscription_total, ceil($subscription_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_subscription_status_id'] = $filter_subscription_status_id;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/subscription_list', $data);
	}

	/**
	 * Report Subscription Discount
	 *
	 * @return void
	 */
	public function report_subscription_discount(): void {
		$this->load->language('extension/opencart/report/subscription');

		$data['list'] = $this->getReportSubscriptionDiscount();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/subscription_discount', $data));
	}

	/**
	 * List Subscription Discount
	 *
	 * @return void
	 */
	public function list_subscription_discount(): void {
		$this->load->language('extension/opencart/report/subscription');

		$this->response->setOutput($this->getReportSubscriptionDiscount());
	}

	/**
	 * Get Report Subscription Discount
	 *
	 * @return string
	 */
	public function getReportSubscriptionDiscount(): string {
		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['subscription_discounts'] = [];

		$filter_data = [
			'filter_date_start' => $filter_date_start,
			'filter_date_end'   => $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_pagination'),
			'limit'             => $this->config->get('config_pagination')
		];

		$this->load->model('extension/opencart/report/subscription');

		$subscription_discount_total = $this->model_extension_opencart_report_subscription->getTotalSubscriptionDiscounts($filter_data);

		$results = $this->model_extension_opencart_report_subscription->getSubscriptionDiscounts($filter_data);

		foreach ($results as $result) {
			$data['subscription_discounts'][] = [
				'name'            => $result['name'],
				'code'            => $result['code'],
				'orders'          => $result['orders'],
				'total'           => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'total_installed' => $this->config->get('total_subscription_status') ?? false,
				'edit'            => $this->url->link('extension/opencart/total/subscription.form', 'user_token=' . $this->session->data['user_token'] . '&subscription_discount_id=' . $result['subscription_discount_id'])
			];
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_discount_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/subscription.report_subscription_discount', 'user_token=' . $this->session->data['user_token'] . '&code=subscription' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_discount_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($subscription_discount_total - $this->config->get('config_pagination'))) ? $subscription_discount_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $subscription_discount_total, ceil($subscription_discount_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/subscription_discount_list', $data);
	}
}
