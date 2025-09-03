<?php
/*
Upgrade Process

1. Check for latest version

2. Download a copy of the latest version

3. Add and replace the files with the ones from latest version

4. Redirect to upgrade page
*/
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Upgrade
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Upgrade extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'])
		];

		$data['current_version'] = VERSION;
		$data['upgrade'] = false;

		$curl = curl_init(OPENCART_SERVER . 'index.php?route=api/upgrade');

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, true);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

		$response = curl_exec($curl);

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		if ($status == 200) {
			$response_info = json_decode($response, true);
		} else {
			$response_info = [];
		}

		if ($response_info) {
			$data['latest_version'] = $response_info['version'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($response_info['date_added']));
			$data['log'] = nl2br($response_info['log']);

			if (!version_compare(VERSION, $response_info['version'], '>=')) {
				$data['upgrade'] = true;
			}
		} else {
			$data['latest_version'] = '';
			$data['date_added'] = '';
			$data['log'] = '';
		}

		// For testing
		//$data['latest_version'] = 'master';

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upgrade', $data));
	}

	/**
	 * Install
	 *
	 * @return void
	 */
	public function install(): void {
		$this->load->language('tool/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (version_compare($version, VERSION, '>') && preg_match('/^(\d+\.\d+\.\d+\.\d+)$/', $version)) {
			$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

			if (!is_file($file)) {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_version');
		}

		if (!$json) {

			$json['text'] = $this->language->get('text_patch');

			$json['next'] = HTTP_CATALOG . 'install/index.php?route=upgrade/upgrade_1&version=' . $version . '&admin=' . rtrim(substr(DIR_APPLICATION, strlen(DIR_OPENCART), -1));

			$task_data = [
				'code'   => 'upgrade',
				'action' => 'task/system/upgrade',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
