<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Task
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Task extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/task');

		if (isset($this->request->get['filter_code'])) {
			$filter_code = $this->request->get['filter_code'];
		} else {
			$filter_code = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'filter_code',
			'filter_status',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/task', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['delete'] = $this->url->link('marketplace/task.delete', 'user_token=' . $this->session->data['user_token']);
		$data['pending'] = $this->url->link('marketplace/task.status', 'user_token=' . $this->session->data['user_token'] . '&status=pending');
		$data['processing'] = $this->url->link('marketplace/task.status', 'user_token=' . $this->session->data['user_token'] . '&status=processing');
		$data['paused'] = $this->url->link('marketplace/task.status', 'user_token=' . $this->session->data['user_token'] . '&status=paused');
		$data['complete'] = $this->url->link('marketplace/task.status', 'user_token=' . $this->session->data['user_token'] . '&status=complete');
		$data['failed'] = $this->url->link('marketplace/task.status', 'user_token=' . $this->session->data['user_token'] . '&status=failed');

		$data['statues'] = [];

		$statuses = [
			'pending',
			'processing',
			'paused',
			'complete',
			'failed'
		];

		foreach ($statuses as $status) {
			$data['statuses'][] = [
				'text'  => $this->language->get('text_' . $status),
				'value' => $status
			];
		}

		$data['list'] = $this->getList();
		$data['log'] = $this->getLog();

		$data['filter_code'] = $filter_code;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/task', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketplace/task');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_code'])) {
			$filter_code = (string)$this->request->get['filter_code'];
		} else {
			$filter_code = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (string)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_code',
			'filter_status',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('marketplace/task.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['tasks'] = [];

		$filter_data = [
			'filter_code'   => $filter_code,
			'filter_status' => $filter_status,
			'start'         => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'         => $this->config->get('config_pagination_admin')
		];

		$this->load->model('setting/task');

		$results = $this->model_setting_task->getTasks($filter_data);

		foreach ($results as $result) {
			$data['tasks'][] = ['args' => json_encode($result['args'])] + $result;
		}

		$allowed = [
			'filter_code',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Tasks
		$task_total = $this->model_setting_task->getTotalTasks($filter_data);

		// Pagination
		$data['total'] = $task_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('marketplace/task.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($task_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($task_total - $this->config->get('config_pagination_admin'))) ? $task_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $task_total, ceil($task_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketplace/task_list', $data);
	}

	/**
	 * Start
	 *
	 * @return void
	 */
	public function start() {
		$this->load->language('marketplace/task');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/task')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		//if (!$task_total) {
		//}

		if (!$json) {
			if (strtoupper(substr(php_uname(), 0, 3)) == 'WIN') {
				pclose(popen('start /B php ' . DIR_APPLICATION . 'index.php start', 'r'));
			} else {
				shell_exec('php ' . DIR_APPLICATION . 'index.php start > /dev/null 2>&1 &');
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Command Line
	 *
	 * Called from
	 *
	 * @return void
	 */
	public function cli() {
		$this->load->model('setting/task');

		$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

		if ($task_total) {
			return;
		}

		$filter_data = [
			'filter_status' => 'pending',
			'start'         => 0,
			'limit'         => 1
		];

		$results = $this->model_setting_task->getTasks($filter_data);

		while ($results) {
			$task = array_shift($results);

			$this->model_setting_task->editStatus($task['task_id'], 'processing');

			try {
				$output = $this->load->controller($task['action'], $task['args']);
			} catch (\Exception $e) {
				$output = $e;
			}

			if ($output instanceof \Exception) {
				$output = ['error' => $output->getMessage() . ' in ' . $output->getFile() . ' on line ' . $output->getLine()];
			}

			// If task does not exist
			if (isset($output['error'])) {
				$this->model_setting_task->editStatus($task['task_id'], 'failed', $output['error']);

				$this->model_setting_task->addLog($task['code'], $output['error'], false);
			}

			if (isset($output['success'])) {
				$this->model_setting_task->editStatus($task['task_id'], 'complete', $output['success']);

				$this->model_setting_task->deleteTask($task['task_id']);

				$this->model_setting_task->addLog($task['code'], $output['success'], true);
			}

			$next = $this->model_setting_task->getTasks($filter_data);

			if ($next) {
				array_push($results, $next[0]);
			}

			usleep(2000);
		}
	}

	/**
	 * Pause
	 *
	 * @return void
	 */
	public function pause() {
		$this->load->language('marketplace/task');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/task')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/task');

			$results = $this->model_setting_task->getTasks(['filter_status' => 'pending']);

			foreach ($results as $result) {
				$this->model_setting_task->editStatus($result['task_id'], 'paused');
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Clear
	 *
	 * @return void
	 */
	public function clear() {
		$this->load->language('marketplace/task');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketplace/task')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/task');

			$this->model_setting_task->clear();

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('marketplace/task');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/task')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/task');

			foreach ($selected as $task_id) {
				$this->model_setting_task->deleteTask((int)$task_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Status
	 *
	 * @return void
	 */
	public function status(): void {
		$this->load->language('marketplace/task');

		$json = [];

		if (isset($this->request->get['status'])) {
			$status = (string)$this->request->get['status'];
		} else {
			$status = '';
		}

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/task')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$allowed = [
			'pending',
			'processing',
			'paused',
			'complete',
			'failed'
		];

		if (!in_array($status, $allowed)) {
			$json['error'] = $this->language->get('error_status');
		}

		if (!$json) {
			$this->load->model('setting/task');

			foreach ($selected as $task_id) {
				$this->model_setting_task->editStatus((int)$task_id, $status);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function log(): void {
		$this->load->language('marketplace/task');

		$this->response->setOutput($this->getLog());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getLog(): string {
		if (isset($this->request->get['page']) && $this->request->get['route'] == 'marketplace/task.log') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		// Histories
		$this->load->model('setting/task');

		$data['logs'] = $this->model_setting_task->getLogs(($page - 1) * $limit, $limit);

		// Total Histories
		$log_total = $this->model_setting_task->getTotalLogs();

		// Pagination
		$data['total'] = $log_total;
		$data['page'] = $page;
		$data['limit'] = $limit;
		$data['pagination'] = $this->url->link('marketplace/task.log', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($log_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($log_total - $limit)) ? $log_total : ((($page - 1) * $limit) + $limit), $log_total, ceil($log_total / $limit));

		return $this->load->view('marketplace/task_log', $data);
	}
}
