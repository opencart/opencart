<?php
namespace Opencart\Application\Controller\Tool;
class Notification extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('tool/notification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/notification');

		$this->getList();
	}

	public function delete() {
		$this->load->language('tool/notification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('tool/notification');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $notification_id) {
				$this->model_tool_notification->deleteNotification($notification_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('tool/notification', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href' => $this->url->link('tool/notification', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['delete'] = $this->url->link('tool/notification|delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['notifications'] = [];

		$notification_total = $this->model_tool_notification->getTotalNotifications();

		$results = $this->model_tool_notification->getNotifications(($page - 1) * $this->config->get('config_pagination'), $this->config->get('config_pagination'));

		foreach ($results as $result) {
			$data['notifications'][] = [
				'notification_id' => $result['notification_id'],
				'title'           => $result['title'],
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'            => $this->url->link('tool/notification|info', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'] . $url)
			];
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = [];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $notification_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('tool/notification', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($notification_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($notification_total - $this->config->get('config_pagination'))) ? $notification_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $notification_total, ceil($notification_total / $this->config->get('config_pagination')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/notification_list', $data));
	}

	public function info() {
		$this->load->model('tool/notification');

		if (isset($this->request->get['notification_id'])) {
			$notification_id = $this->request->get['notification_id'];
		} else {
			$notification_id = 0;
		}

		$notification_info = $this->model_tool_notification->getNotification($notification_id);

		if ($notification_info) {
			$this->load->language('tool/notification');

			$data['name'] = $notification_info['name'];
			$data['message'] = $notification_info['message'];
			$data['status'] = $notification_info['status'];
			$data['status'] = $notification_info['status'];

			$this->response->setOutput($this->load->view('tool/notification_info', $data));
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'tool/notification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
