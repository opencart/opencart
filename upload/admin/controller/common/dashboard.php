<?php
namespace Opencart\Admin\Controller\Common;
/**
 * Class Dashboard
 *
 * Can be loaded using $this->load->controller('common/dashboard');
 *
 * @package Opencart\Admin\Controller\Common
 */
class Dashboard extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/dashboard.js');

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		// Dashboard Extensions
		$dashboards = [];

		$this->load->model('setting/extension');

		// Get a list of installed modules
		$extensions = $this->model_setting_extension->getExtensionsByType('dashboard');

		// Add all the modules which have multiple settings for each module
		foreach ($extensions as $extension) {
			if ($this->config->get('dashboard_' . $extension['code'] . '_status') && $this->user->hasPermission('access', 'extension/' . $extension['extension'] . '/dashboard/' . $extension['code'])) {
				$output = $this->load->controller('extension/' . $extension['extension'] . '/dashboard/' . $extension['code'] . '.dashboard');

				if (!$output instanceof \Exception) {
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

		if ($column) {
			$data['rows'][] = $column;
		}

		if ($this->user->hasPermission('access', 'common/developer')) {
			$data['developer_status'] = true;
		} else {
			$data['developer_status'] = false;
		}

		$data['security'] = $this->load->controller('common/security');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}
