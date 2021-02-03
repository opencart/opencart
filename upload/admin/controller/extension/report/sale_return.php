<?php
class ControllerExtensionReportSaleReturn extends Controller {
	public function index() {
		$this->load->language('extension/report/sale_return');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_sale_return', $this->request->post);

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
			'href' => $this->url->link('extension/report/sale_return', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/report/sale_return', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report', true);

		if (isset($this->request->post['report_sale_return_status'])) {
			$data['report_sale_return_status'] = $this->request->post['report_sale_return_status'];
		} else {
			$data['report_sale_return_status'] = $this->config->get('report_sale_return_status');
		}

		if (isset($this->request->post['report_sale_return_sort_order'])) {
			$data['report_sale_return_sort_order'] = $this->request->post['report_sale_return_sort_order'];
		} else {
			$data['report_sale_return_sort_order'] = $this->config->get('report_sale_return_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/report/sale_return_form', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/report/sale_return')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function report() {
		$this->load->language('extension/report/sale_return');

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

		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$filter_return_status_id = $this->request->get['filter_return_status_id'];
		} else {
			$filter_return_status_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$this->load->model('extension/report/return');

		$data['returns'] = array();

		$filter_data = array(
			'filter_date_start'	      => $filter_date_start,
			'filter_date_end'	      => $filter_date_end,
			'filter_group'            => $filter_group,
			'filter_return_status_id' => $filter_return_status_id,
			'start'                   => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                   => $this->config->get('config_limit_admin')
		);

		$return_total = $this->model_extension_report_return->getTotalReturns($filter_data);

		$results = $this->model_extension_report_return->getReturns($filter_data);

		foreach ($results as $result) {
			$data['returns'][] = array(
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'returns'    => $result['returns']
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$data['groups'] = array();

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

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}

		if (isset($this->request->get['filter_return_status_id'])) {
			$url .= '&filter_return_status_id=' . $this->request->get['filter_return_status_id'];
		}

		$pagination = new Pagination();
		$pagination->total = $return_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=sale_return' . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($return_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($return_total - $this->config->get('config_limit_admin'))) ? $return_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $return_total, ceil($return_total / $this->config->get('config_limit_admin')));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;
		$data['filter_group'] = $filter_group;
		$data['filter_return_status_id'] = $filter_return_status_id;
		
		return $this->load->view('extension/report/sale_return_info', $data);
	}
}