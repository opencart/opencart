<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Event
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Event extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/event');

		$this->document->setTitle($this->language->get('heading_title'));

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
			'href' => $this->url->link('marketplace/event', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['delete'] = $this->url->link('marketplace/event.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/event', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketplace/event');

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

		$data['action'] = $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Events
		$data['events'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('setting/event');

		$results = $this->model_setting_event->getEvents($filter_data);

		foreach ($results as $result) {
			$data['events'][] = [
				'enable'  => $this->url->link('marketplace/event.enable', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $result['event_id']),
				'disable' => $this->url->link('marketplace/event.disable', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $result['event_id'])
			] + $result;
		}

		// Total Events
		$event_total = $this->model_setting_event->getTotalEvents();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $event_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($event_total - $this->config->get('config_pagination_admin'))) ? $event_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $event_total, ceil($event_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketplace/event_list', $data);
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('marketplace/event');

		$json = [];

		if (isset($this->request->get['event_id'])) {
			$event_id = (int)$this->request->get['event_id'];
		} else {
			$event_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/event')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Event
			$this->load->model('setting/event');

			$this->model_setting_event->editStatus($event_id, true);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('marketplace/event');

		$json = [];

		if (isset($this->request->get['event_id'])) {
			$event_id = (int)$this->request->get['event_id'];
		} else {
			$event_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/event')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Event
			$this->load->model('setting/event');

			$this->model_setting_event->editStatus($event_id, false);

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
		$this->load->language('marketplace/event');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/event')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Event
			$this->load->model('setting/event');

			foreach ($selected as $event_id) {
				$this->model_setting_event->deleteEvent($event_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
