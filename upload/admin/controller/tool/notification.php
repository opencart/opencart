<?php
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Notification
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Notification extends \Opencart\System\Engine\Controller {
	/**
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
	 * @return void
	 */
	public function list(): void {
		$this->load->language('tool/notification');

		$this->response->setOutput($this->getList());
	}

	/**
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

		$data['notifications'] = [];

		$this->load->model('tool/notification');

		$notification_total = $this->model_tool_notification->getTotalNotifications();

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$results = $this->model_tool_notification->getNotifications($filter_data);

		foreach ($results as $result) {
			$second = time() - strtotime($result['date_added']);
			
			$ranges = [
				'second'	=> $second,
				'minute'	=> floor($second / 60),
				'hour'		=> floor($second / 3600),
				'day'		=> floor($second / 86400),
				'week'		=> floor($second / 604800),
				'month'		=> floor($second / 2629743),
				'year'		=> floor($second / 31556926)
			];

			foreach ($ranges as $range => $value) {
				if ($value) {
					$date_added = $value;
					$code = $range . ($value > 1) ? 's' : '';
				}
			}

			$data['notifications'][] = [
				'notification_id' => $result['notification_id'],
				'title'           => $result['title'],
				'status'          => $result['status'],
				'date_added'      => sprintf($this->language->get('text_' . $code . '_ago'), $date_added),
				'view'            => $this->url->link('tool/notification.info', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'] . $url),
				'delete'          => $this->url->link('tool/notification.delete', 'user_token=' . $this->session->data['user_token'] . '&notification_id=' . $result['notification_id'] . $url)
			];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $notification_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('tool/notification.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($notification_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($notification_total - $this->config->get('config_pagination_admin'))) ? $notification_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $notification_total, ceil($notification_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('tool/notification_list', $data);
	}

	/**
	 * @return void
	 */
	public function info(): void {
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

			$data['text'] = oc_bbcode_decode($notification_info['text']);

			$this->model_tool_notification->editStatus($notification_id, 1);

			$this->response->setOutput($this->load->view('tool/notification_info', $data));
		}
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('tool/notification');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'tool/notification')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
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
