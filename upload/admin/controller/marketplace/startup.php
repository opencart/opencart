<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Startup
 *
 * @package Opencart\Admin\Controller\Marketplace
 */
class Startup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/startup');

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
			'href' => $this->url->link('marketplace/startup', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['delete'] = $this->url->link('marketplace/startup.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('marketplace/startup.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('marketplace/startup.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/startup', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketplace/startup');

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

		$data['action'] = $this->url->link('marketplace/startup.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('setting/startup');

		$data['startups'] = $this->model_setting_startup->getStartups($filter_data);

		// Total Startups
		$startup_total = $this->model_setting_startup->getTotalStartups();

		// Pagination
		$data['total'] = $startup_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('marketplace/startup.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($startup_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($startup_total - $this->config->get('config_pagination_admin'))) ? $startup_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $startup_total, ceil($startup_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketplace/startup_list', $data);
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('marketplace/startup');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/startup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Startup
			$this->load->model('setting/startup');

			foreach ($selected as $startup_id) {
				$this->model_setting_startup->editStatus((int)$startup_id, true);
			}

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
		$this->load->language('marketplace/startup');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/startup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Startup
			$this->load->model('setting/startup');

			foreach ($selected as $startup_id) {
				$this->model_setting_startup->editStatus((int)$startup_id, false);
			}

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
		$this->load->language('marketplace/startup');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/startup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Startup
			$this->load->model('setting/startup');

			foreach ($selected as $startup_id) {
				$this->model_setting_startup->deleteStartup((int)$startup_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
