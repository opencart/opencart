<?php
namespace Opencart\Admin\Controller\Marketplace;
class Event extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('marketplace/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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

	public function list(): void {
		$this->load->language('marketplace/event');

		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'code';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['events'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('setting/event');

		$event_total = $this->model_setting_event->getTotalEvents();

		$results = $this->model_setting_event->getEvents($filter_data);

		foreach ($results as $result) {
			$data['events'][] = [
				'event_id'    => $result['event_id'],
				'code'        => $result['code'],
				'description' => $result['description'],
				'trigger'     => $result['trigger'],
				'action'      => $result['action'],
				'status'      => $result['status'],
				'sort_order'  => $result['sort_order'],
				'enable'      => $this->url->link('marketplace/event.enable', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $result['event_id']),
				'disable'     => $this->url->link('marketplace/event.disable', 'user_token=' . $this->session->data['user_token'] . '&event_id=' . $result['event_id'])
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_code'] = $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url);
		$data['sort_sort_order'] = $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $event_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/event.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($event_total - $this->config->get('config_pagination_admin'))) ? $event_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $event_total, ceil($event_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('marketplace/event_list', $data);
	}

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
			$this->load->model('setting/event');

			$this->model_setting_event->editStatus($event_id, 1);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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
			$this->load->model('setting/event');

			$this->model_setting_event->editStatus($event_id, 0);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

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
