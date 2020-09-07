<?php
namespace Opencart\Application\Controller\Common;
class Dashboard extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['user_token'] = $this->session->data['user_token'];

		// Check install directory exists
		if (is_dir(DIR_CATALOG . '../install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		// Dashboard Extensions
		$dashboards = [];

		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getExtensionsByType('dashboard');

		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $extension) {
			if ($this->config->get('dashboard_' . $extension['code'] . '_status') && $this->user->hasPermission('access', 'extension/' . $extension['extension'] . '/dashboard/' . $extension['code'])) {
				$output = $this->load->controller('extension/' . $extension['extension'] . '/dashboard/' . $extension['code'] . '/dashboard');

				if ($output) {
					$dashboards[] = [
						'code'       => $extension['code'],
						'width'      => $this->config->get('dashboard_' . $extension['code'] . '_width'),
						'sort_order' => $this->config->get('dashboard_' . $extension['code'] . '_sort_order'),
						'output'     => $output
					];
				}
			}
		}

		$sort_order = [];

		foreach ($dashboards as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $dashboards);

		// Split the array so the columns width is not more than 12 on each row.
		$width = 0;
		$column = [];
		$data['rows'] = [];

		foreach ($dashboards as $dashboard) {
			$column[] = $dashboard;

			$width = ($width + $dashboard['width']);

			if ($width >= 12) {
				$data['rows'][] = $column;

				$width = 0;
				$column = [];
			}
		}

		if (!empty($column)) {
			$data['rows'][] = $column;
		}

		if (DIR_STORAGE == DIR_SYSTEM . 'storage/') {
			$data['security'] = $this->load->controller('common/security');
		} else {
			$data['security'] = '';
		}

		if ($this->user->hasPermission('access', 'common/developer')) {
			$data['developer_status'] = true;
		} else {
			$data['developer_status'] = false;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}
