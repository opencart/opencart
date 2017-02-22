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

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_group'] = $this->language->get('text_group');
		$data['text_filter'] = $this->language->get('text_filter');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['group'])) {
			$data['group'] = $this->request->get['group'];
		} else {
			$data['group'] = '';
		}


		// Reports
		$reports = array();

		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getInstalled('report');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $code) {
			if ($this->config->get('report_' . $code . '_status') && $this->user->hasPermission('access', 'extension/report/' . $code)) {
				$this->load->language('extension/report/' . $code);
				
				$reports[] = array(
					'name'       => $this->language->get('heading_title'),
					'code'       => $code,
					'group'      => $this->language->get('text_' . $this->config->get('report_' . $code . '_group')),
					'sort_order' => $this->config->get('report_' . $code . '_sort_order')
				);
			}
		}
		
		foreach ($reports as $report) {
			$data['menus'][] = array(
				'id'       => 'menu-report',
				'icon'	   => 'fa-cog', 
				'name'	   => $this->language->get('text_report'),
				'href'     => '',
				'children' => $system
			);
		}
		
		$sort_order = array();

		foreach ($reports as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $reports);	






		$data['categories'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/extension/extension/*.php', GLOB_BRACE);
		
		foreach ($files as $file) {
			$extension = basename($file, '.php');
			
			// Compatibility code for old extension folders
			$this->load->language('extension/extension/' . $extension);
		
			if ($this->user->hasPermission('access', 'extension/extension/' . $extension)) {
				$files = glob(DIR_APPLICATION . 'controller/extension/' . $extension . '/*.php', GLOB_BRACE);
		
				$data['categories'][] = array(
					'code' => $extension,
					'text' => $this->language->get('heading_title') . ' (' . count($files) .')',
					'href' => $this->url->link('extension/extension/' . $extension, 'user_token=' . $this->session->data['user_token'], true)
				);
			}			
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/report', $data));
	}
}