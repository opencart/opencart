<?php
class ControllerReportReport extends Controller {
	public function index() {
		$this->load->language('report/report');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['code'])) {
			$data['code'] = $this->request->get['code'];
		} else {
			$data['code'] = '';
		}

		// Reports
		$data['reports'] = array();
		
		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getInstalled('report');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if ($this->config->get('report_' . $code . '_status') && $this->user->hasPermission('access', 'extension/report/' . $code)) {
				$this->load->language('extension/report/' . $code, 'extension');
				
				$data['reports'][] = array(
					'text'       => $this->language->get('extension')->get('heading_title'),
					'code'       => $code,
					'sort_order' => $this->config->get('report_' . $code . '_sort_order'),
					'href'       => $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'] . '&code=' . $code, true)
				);
			}
		}
		
		$sort_order = array();

		foreach ($data['reports'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $data['reports']);	
		
		if (isset($this->request->get['code'])) {
			$data['report'] = $this->load->controller('extension/report/' . $this->request->get['code'] . '/report');
		} elseif (isset($data['reports'][0])) {
			$data['report'] = $this->load->controller('extension/report/' . $data['reports'][0]['code'] . '/report');
		} else {
			$data['report'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/report', $data));
	}
}