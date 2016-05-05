<?php
class ControllerExtensionEvent extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/event');

		$this->getList();
	}

	public function enable() {
		$this->load->language('extension/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/event');

		if (isset($this->request->get['event_id']) && $this->validate()) {
			$this->model_extension_event->enableEvent($this->request->get['event_id']);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/event', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function disable() {
		$this->load->language('extension/event');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/event');

		if (isset($this->request->get['event_id']) && $this->validate()) {
			$this->model_extension_event->disableEvent($this->request->get['event_id']);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/event', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}
	
	public function getList() {
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
			$page = $this->request->get['page'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/event', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['events'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$event_total = $this->model_extension_event->getTotalEvents();

		$results = $this->model_extension_event->getEvents($filter_data);

		foreach ($results as $result) {
			$data['events'][] = array(
				'event_id'   => $result['event_id'],
				'code'       => $result['code'],
				'trigger'    => $result['trigger'],
				'action'     => $result['action'],
				'status'     => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'enable'     => $this->url->link('extension/event/enable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'], true),
				'disable'    => $this->url->link('extension/event/disable', 'token=' . $this->session->data['token'] . '&event_id=' . $result['event_id'], true),
				'enabled'    => $result['status']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_event'] = $this->language->get('text_event');

		$data['column_code'] = $this->language->get('column_code');
		$data['column_trigger'] = $this->language->get('column_trigger');
		$data['column_action'] = $this->language->get('column_action');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');

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
			$data['selected'] = array();
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

		$data['sort_code'] = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . '&sort=code' . $url, true);
		$data['sort_trigger'] = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . '&sort=trigger' . $url, true);
		$data['sort_action'] = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . '&sort=action' . $url, true);
		$data['sort_status'] = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $event_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/event', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($event_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($event_total - $this->config->get('config_limit_admin'))) ? $event_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $event_total, ceil($event_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/event', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/event')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}