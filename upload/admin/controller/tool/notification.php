<?php
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Notification
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Notification extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/notification');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/notification', 'user_token=' . $this->session->data['user_token'])
		];

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/notification', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('tool/notification');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		// Notifications
		$data['notifications'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('tool/notification');

		$results = $this->model_tool_notification->getNotifications($filter_data);

		foreach ($results as $result) {
			$second = time() - strtotime($result['date_added']);

			$ranges = [
				'second' => $second,
				'minute' => floor($second / 60),
				'hour'   => floor($second / 3600),
				'day'    => floor($second / 86400),
				'week'   => floor($second / 604800),
				'month'  => floor($second / 2629743),
				'year'   => floor($second / 31556926)
			];

			$date_added = 0;
			$code = 'seconds';

			foreach ($ranges as $range => $value) {
				if ($value) {
					$code = ($value > 1) ? $range . 's' : $range;
					$date_added = $value;
				}
			}

			$data['notifications'][] = [
				'date_added' => sprintf($this->language->get('text_' . $code . '_ago'), $date_added),
				'view'       => $this->url->link('tool/notification.info', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'] . $url),
				'delete'     => $this->url->link('tool/notification.delete', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'] . $url)
			] + $result;
		}

		// Total Notifications
		$notification_total = $this->model_tool_notification->getTotalNotifications();

		// Pagination
		$data['total'] = $notification_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('tool/notification.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($notification_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($notification_total - $this->config->get('config_pagination_admin'))) ? $notification_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $notification_total, ceil($notification_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('tool/notification_list', $data);
	}

	/**
	 * Info
	 *
	 * @return void
	 */
	public function info(): void {
		// Notification
		if (isset($this->request->get['notification_id'])) {
			$notification_id = $this->request->get['notification_id'];
		} else {
			$notification_id = 0;
		}

		$this->load->model('tool/notification');

		$notification_info = $this->model_tool_notification->getNotification($notification_id);

		if ($notification_info) {
			$this->load->language('tool/notification');

			$data['title'] = $notification_info['title'];

			$data['text'] = html_entity_decode($notification_info['text'], ENT_QUOTES, 'UTF-8');

			$this->model_tool_notification->editStatus($notification_id, true);

			$this->response->setOutput($this->load->view('tool/notification_info', $data));
		}
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('tool/notification');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'tool/notification')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Notification
			$this->load->model('tool/notification');

			foreach ($selected as $notification_id) {
				$this->model_tool_notification->deleteNotification($notification_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
