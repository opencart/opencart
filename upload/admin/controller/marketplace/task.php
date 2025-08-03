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

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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

		$url = '';

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . urlencode(html_entity_decode($this->request->get['filter_status'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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
			$data['tasks'][] = [
				'args'     => json_encode($result['args']),
				'response' => json_encode($result['response'])
			] + $result;
		}

		// Total Tasks
		$task_total = $this->model_setting_task->getTotalTasks($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $task_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/task.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

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

		if (!$json) {
			$this->load->model('setting/task');

			$task_total = $this->model_setting_task->getTotalTasks(['filter_status' => 'processing']);

			if (!$task_total) {
				shell_exec('php ' . DIR_APPLICATION . 'index.php start');
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
}
