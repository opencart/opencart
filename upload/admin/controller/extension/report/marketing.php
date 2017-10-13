<?php
class ControllerExtensionReportMarketing extends Controller {
	public function index() {
		$this->load->language('extension/report/marketing');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_marketing', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/report/marketing', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/report/marketing', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true);

		if (isset($this->request->post['report_marketing_status'])) {
			$data['report_marketing_status'] = $this->request->post['report_marketing_status'];
		} else {
			$data['report_marketing_status'] = $this->config->get('report_marketing_status');
		}

		if (isset($this->request->post['report_marketing_sort_order'])) {
			$data['report_marketing_sort_order'] = $this->request->post['report_marketing_sort_order'];
		} else {
			$data['report_marketing_sort_order'] = $this->config->get('report_marketing_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/report/marketing_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report/marketing')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function report() {
		$this->load->language('extension/report/marketing');

		if (isset($this->request->get['filter_marketing_id'])) {
			$filter_marketing_id = $this->request->get['filter_marketing_id'];
		} else {
			$filter_marketing_id = 0;
		}

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

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}

		if (isset($this->request->get['filter_country_id'])){
			$filter_country_id = $this->request->get['filter_country_id'];
		} else {
			$filter_country_id = 0 ;
		}

		if (isset($this->request->get['filter_group']) && !empty($this->request->get['filter_group'])){
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->load->model('extension/report/marketing');

		$data['marketing'] = array();

		$filter_data = array(
			'filter_marketing_id'		 => $filter_marketing_id,
			'filter_date_start'	     => $filter_date_start,
			'filter_date_end'	     	 => $filter_date_end,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_country_id'			 => $filter_country_id,
			'filter_group'					 => $filter_group,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$marketing_clicks_total = $this->model_extension_report_marketing->getTotalmarketingClicks($filter_data);

		$clicks_results = $this->model_extension_report_marketing->getmarketingClicks($filter_data);

		foreach ($clicks_results as $clicks_result){
			$data['marketing_clicks'][] = array(
				'campaign' 		=> $clicks_result['campaign'],
				'code'     		=> $clicks_result['code'],
				'date_start' 	=> date($this->language->get('date_format_short'), strtotime($clicks_result['date_start'])),
				'date_end'		=> date($this->language->get('date_format_short'), strtotime($clicks_result['date_end'])),
				'clicks'   		=> $clicks_result['clicks'],
				'action'   		=> $this->url->link('marketing/marketing/edit', 'user_token=' . $this->session->data['user_token'] . '&marketing_id=' . $clicks_result['marketing_id'], true)
			);
		}

		$marketing_sales_total = $this->model_extension_report_marketing->getTotalmarketingSales($filter_data);

		$sales_results = $this->model_extension_report_marketing->getmarketingSales($filter_data);

		foreach ($sales_results as $sales_result){
			$data['marketing_sales'][] = array(
				'campaign' 		=> $sales_result['campaign'],
				'code'     		=> $sales_result['code'],
				'date_start' 	=> date($this->language->get('date_format_short'), strtotime($sales_result['date_start'])),
				'date_end'		=> date($this->language->get('date_format_short'), strtotime($sales_result['date_end'])),
				'orders'			=> $sales_result['orders'],
				'total'				=> $this->currency->format($sales_result['total'], $this->config->get('config_currency')),
				'action'   		=> $this->url->link('marketing/marketing/edit', 'user_token=' . $this->session->data['user_token'] . '&marketing_id=' . $sales_result['marketing_id'], true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('marketing/marketing');

		$data['marketing_list'] = $this->model_marketing_marketing->getMarketings();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

	 	$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$data['groups'] = array();

		$data['groups'][] = array(
			'text'  => $this->language->get('text_not_group'),
			'value' => '',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_year'),
			'value' => 'year',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_month'),
			'value' => 'month',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_week'),
			'value' => 'week',
		);

		$data['groups'][] = array(
			'text'  => $this->language->get('text_day'),
			'value' => 'day',
		);

		$url = '';

		if (isset($this->request->get['filter_marketing_id'])) {
			$url .= '&filter_marketing_id=' . $this->request->get['filter_marketing_id'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_contry_id'])){
			$url .= '&filter_country_id=' . $this->request->get['filter_contry_id'];
		}

		if (isset($this->request->get['filter_group'])){
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		$pagination_total = ( $marketing_clicks_total > $marketing_sales_total ) ? $marketing_clicks_total : $marketing_sales_total;

		$pagination = new Pagination();
		$pagination->total = $pagination_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=marketing' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		if( count($clicks_results) ){
			$data['clicks_results'] = sprintf($this->language->get('text_pagination'), ($marketing_clicks_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($marketing_clicks_total - $this->config->get('config_limit_admin'))) ? $pagination_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $marketing_clicks_total, ceil($marketing_clicks_total / $this->config->get('config_limit_admin')));
		}

		if( count($sales_results) ) {
			$data['sales_results'] = sprintf($this->language->get('text_pagination'), ($marketing_sales_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($marketing_sales_total - $this->config->get('config_limit_admin'))) ? $marketing_sales_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $marketing_sales_total, ceil($marketing_sales_total / $this->config->get('config_limit_admin')));
		}

		$data['filter_marketing_id'] = $filter_marketing_id;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_country_id'] = $filter_country_id;
		$data['filter_group'] = $filter_group;

		return $this->load->view('extension/report/marketing_info', $data);
	}
}
