<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Report;
/**
 * CLass SaleCoupon
 *
 * @package  Opencart\Admin\Controller\Extension\Opencart\Report
 */
class SaleCoupon extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/report/sale_coupon');

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
			'href' => $this->url->link('extension/opencart/report/sale_coupon', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/report/sale_coupon.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		$data['report_sale_coupon_status'] = $this->config->get('report_sale_coupon_status');
		$data['report_sale_coupon_sort_order'] = $this->config->get('report_sale_coupon_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_coupon_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/report/sale_coupon');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/report/sale_coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('report_sale_coupon', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function report(): void {
		$this->load->language('extension/opencart/report/sale_coupon');

		$data['list'] = $this->getReport();

		$data['user_token'] = $this->session->data['user_token'];

		$this->response->setOutput($this->load->view('extension/opencart/report/sale_coupon', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('extension/opencart/report/sale_coupon');

		$this->response->setOutput($this->getReport());
	}

	/**
	 * @return string
	 */
	public function getReport(): string {
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

		$data['coupons'] = [];

		$filter_data = [
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_pagination'),
			'limit'             => $this->config->get('config_pagination')
		];

		$this->load->model('extension/opencart/report/coupon');

		$coupon_total = $this->model_extension_opencart_report_coupon->getTotalCoupons($filter_data);

		$results = $this->model_extension_opencart_report_coupon->getCoupons($filter_data);

		foreach ($results as $result) {
			$data['coupons'][] = [
				'name'   => $result['name'],
				'code'   => $result['code'],
				'orders' => $result['orders'],
				'total'  => $this->currency->format($result['total'], $this->config->get('config_currency')),
				'edit'   => $this->url->link('marketing/coupon.edit', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $result['coupon_id'])
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
			'total' => $coupon_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/sale_coupon.report', 'user_token=' . $this->session->data['user_token'] . '&code=sale_coupon' . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($coupon_total - $this->config->get('config_pagination'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $coupon_total, ceil($coupon_total / $this->config->get('config_pagination')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('extension/opencart/report/sale_coupon_list', $data);
	}
}
