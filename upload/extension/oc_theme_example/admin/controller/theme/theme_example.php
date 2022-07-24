<?php
namespace Opencart\Admin\Controller\Extension\OcThemeExample\Theme;
class ThemeExample extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/oc_theme_example/theme/theme_example');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/oc_theme_example/theme/theme_example', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/oc_theme_example/theme/theme_example|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme');

		$data['theme_example_status'] = $this->config->get('theme_example_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/oc_theme_example/theme/theme_example', $data));
	}

	public function save(): void {
		$this->load->language('extension/oc_theme_example/theme/theme_example');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/oc_theme_example/theme/theme_example')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('theme_example', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/oc_theme_example/theme/theme_example')) {
			// Add startup to catalog
			$startup_data = [
				'code'        => 'theme_example',
				'description' => 'Example theme extension',
				'action'      => 'catalog/extension/oc_theme_example/startup/theme_example',
				'status'      => 1,
				'sort_order'  => 2
			];

			// Add startup for admin
			$this->load->model('setting/startup');

			$this->model_setting_startup->addStartup($startup_data);
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/oc_theme_example/theme/theme_example')) {
			$this->load->model('setting/startup');

			$this->model_setting_startup->deleteStartupByCode('theme_example');
		}
	}
}