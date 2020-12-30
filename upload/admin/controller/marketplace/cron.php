<?php
namespace Opencart\Application\Controller\Marketplace;
class Cron extends \Opencart\System\Engine\Controller {
	private $error = [];
	
	public function index() {
		$this->load->language('marketplace/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		$this->getList();
	}
	public function add() {
		$this->load->language('marketplace/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_cron->addCron($this->request->post['code'], $this->request->post['cycle'], $this->request->post['action'], $this->request->post['status']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('marketplace/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_cron->editCron($this->request->get['cron_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('marketplace/cron');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/cron');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $cron_id) {
				$this->model_setting_cron->deleteCron($cron_id);
			}

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

			$this->response->redirect($this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . $url));
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('marketplace/cron|add', 'user_token=' . $this->session->data['user_token']);
		$data['delete'] = $this->url->link('marketplace/cron|delete', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['cron'] = $this->url->link('common/cron');

		$data['crons'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination'),
			'limit' => $this->config->get('config_pagination')
		];

		$cron_total = $this->model_setting_cron->getTotalCrons();

		$results = $this->model_setting_cron->getCrons($filter_data);

		foreach ($results as $result) {
			$data['crons'][] = [
				'cron_id'       => $result['cron_id'],
				'code'          => $result['code'],
				'cycle'         => $this->language->get('text_' . $result['cycle']),
				'action'        => $result['action'],
				'status'        => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'    => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'date_modified' => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
				'enabled'       => $result['status'],
				'edit'          => $this->url->link('marketplace/cron|edit', 'user_token=' . $this->session->data['user_token'] . '&cron_id=' . $result['cron_id'], true)

			];
		}

		$data['user_token'] = $this->session->data['user_token'];

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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_code'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url);
		$data['sort_cycle'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=cycle' . $url);
		$data['sort_action'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=action' . $url);
		$data['sort_status'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_date_added'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);
		$data['sort_date_modified'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . '&sort=date_modified' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $cron_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($cron_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($cron_total - $this->config->get('config_pagination'))) ? $cron_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $cron_total, ceil($cron_total / $this->config->get('config_pagination')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/cron', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'marketplace/cron')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->error['warning'])) {
			if (empty($this->request->post['code'])) {
				$this->error['warning'] = $this->language->get('error_code');
			} elseif (empty($this->request->post['action'])) {
				$this->error['warning'] = $this->language->get('error_action');
			} elseif (empty($this->request->post['cycle'])) {
				$this->error['warning'] = $this->language->get('error_cycle');
			}
		}

		return !$this->error;
	}

	public function run() {
		$this->load->language('marketplace/cron');

		$json = [];

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$cron_info = $this->model_setting_cron->getCron($cron_id);

			if ($cron_info) {
				$this->load->controller($cron_info['action'], $cron_id, $cron_info['code'], $cron_info['cycle'], $cron_info['date_added'], $cron_info['date_modified'], $cron_info['status']);

				$this->model_setting_cron->editCron($cron_info['cron_id'], $cron_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function enable() {
		$this->load->language('marketplace/cron');

		$json = [];

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 1);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function disable() {
		$this->load->language('marketplace/cron');

		$json = [];

		if (isset($this->request->get['cron_id'])) {
			$cron_id = (int)$this->request->get['cron_id'];
		} else {
			$cron_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/cron')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('setting/cron');

			$this->model_setting_cron->editStatus($cron_id, 0);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getForm() {
		if (isset($this->error)) {
			foreach($this->error as $key => $error) {
				$data['error_' . $key] = $error;
			}
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['cron_id'])) {
			$data['form_action'] = $this->url->link('marketplace/cron|add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['form_action'] = $this->url->link('marketplace/cron|edit', 'user_token=' . $this->session->data['user_token'] . '&cron_id=' . $this->request->get['cron_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('marketplace/cron', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['cron_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$cron_info = $this->model_setting_cron->getCron($this->request->get['cron_id']);
		}

		if (!empty($cron_info)) {
			$data['cron_id'] = $cron_info['cron_id'];
		} else {
			$data['cron_id'] = '';
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($cron_info)) {
			$data['code'] = $cron_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['action'])) {
			$data['action'] = $this->request->post['action'];
		} elseif (!empty($cron_info)) {
			$data['action'] = $cron_info['action'];
		} else {
			$data['action'] = '';
		}

		if (isset($this->request->post['cycle'])) {
			$data['cycle'] = $this->request->post['cycle'];
		} elseif (!empty($cron_info)) {
			$data['cycle'] = $cron_info['cycle'];
		} else {
			$data['cycle'] = 'day';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($cron_info)) {
			$data['status'] = $cron_info['status'];
		} else {
			$data['status'] = 1;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/cron_form', $data));


	}
}