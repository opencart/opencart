<?php
namespace Opencart\Application\Controller\Report;
class Report extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('report/report');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/report', 'user_token=' . $this->session->data['user_token'])
		];

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['code'])) {
			$data['code'] = $this->request->get['code'];
		} else {
			$data['code'] = '';
		}

		// Reports
		$data['reports'] = [];

		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getExtensionsByType('report');
		
		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $extension) {
			if ($this->config->get('report_' . $extension['code'] . '_status') && $this->user->hasPermission('access', 'extension/' . $extension['extension'] . '/report/' . $extension['code'])) {
				$this->load->language('extension/' . $extension['extension'] . '/report/' . $extension['code'], $extension['code']);
				
				$data['reports'][] = [
					'text'       => $this->language->get($extension['code'] . '_heading_title'),
					'code'       => $extension['code'],
					'sort_order' => $this->config->get('report_' . $extension['code'] . '_sort_order'),
					'href'       => $this->url->link('extension/' . $extension['extension'] . '/report/' . $extension['code'] . '/report', 'user_token=' . $this->session->data['user_token'])
				];
			}
		}
		
		$sort_order = [];

		foreach ($data['reports'] as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $data['reports']);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/report', $data));
	}
}