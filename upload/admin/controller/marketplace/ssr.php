<?php
namespace Opencart\Admin\Controller\Marketplace;
/**
 * Class Static Site Rendering
 *
 * @package Opencart\Admin\Controller\Design
 */
class Ssr extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/ssr');

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
			'href' => $this->url->link('marketplace/ssr', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['delete'] = $this->url->link('marketplace/ssr.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketplace/ssr', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketplace/ssr');

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

		$data['action'] = $this->url->link('marketplace/ssr.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Cron
		$data['ssrs'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('setting/ssr');

		$results = $this->model_setting_ssr->getSsrs($filter_data);

		foreach ($results as $result) {
			if ($this->user->hasPermission('access', $result['action'])) {
				$data['ssrs'][] = [
					'date_modified' => date($this->language->get('datetime_format'), strtotime($result['date_modified'])),
					'run'           => $this->url->link($result['action'], 'user_token=' . $this->session->data['user_token']),
					'clear'         => $this->url->link($result['action'] . '.clear', 'user_token=' . $this->session->data['user_token']),
					'enable'        => $this->url->link('marketplace/ssr.enable', 'user_token=' . $this->session->data['user_token'] . '&ssr_id=' . $result['ssr_id']),
					'disable'       => $this->url->link('marketplace/ssr.disable', 'user_token=' . $this->session->data['user_token'] . '&ssr_id=' . $result['ssr_id'])
				] + $result;
			}
		}

		// Total SSRs
		$ssr_total = $this->model_setting_ssr->getTotalSsrs();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $ssr_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketplace/ssr.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ssr_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($ssr_total - $this->config->get('config_pagination_admin'))) ? $ssr_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $ssr_total, ceil($ssr_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketplace/ssr_list', $data);
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('marketplace/ssr');

		$json = [];

		if (isset($this->request->get['ssr_id'])) {
			$ssr_id = (int)$this->request->get['ssr_id'];
		} else {
			$ssr_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/ssr')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// SSR
			$this->load->model('setting/ssr');

			$this->model_setting_ssr->editStatus($ssr_id, true);

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
		$this->load->language('marketplace/ssr');

		$json = [];

		if (isset($this->request->get['ssr_id'])) {
			$ssr_id = (int)$this->request->get['ssr_id'];
		} else {
			$ssr_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/ssr')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/ssr');

			$this->model_setting_ssr->editStatus($ssr_id, false);

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
		$this->load->language('marketplace/ssr');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketplace/ssr')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/ssr');

			foreach ($selected as $ssr_id) {
				$this->model_setting_ssr->deleteSsr($ssr_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
