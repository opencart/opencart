<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class Popup
 *
 * @package Opencart\Admin\Controller\Design
 */
class Popup extends \Opencart\System\Engine\Controller
{

	/**
	 *
	 */
	public function index(): void
	{
		$this->load->language('design/popup');

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
			'href' => $this->url->link('design/popup', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/popup.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/popup.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/popup', $data));
	}


	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/popup');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
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

		$data['action'] = $this->url->link('design/popup.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['popups'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/popup');

		$popup_total = $this->model_design_popup->getTotalPopups();

		$results = $this->model_design_popup->getPopups($filter_data);

		foreach ($results as $result) {
			$data['popups'][] = [
				'popup_id'   => $result['popup_id'],
				'title'      => $result['title'],
				'store_name' => $result['store_name'],
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'       => $this->url->link('design/popup.form', 'user_token=' . $this->session->data['user_token'] . '&popup_id=' . $result['popup_id'] . $url)
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

		$data['sort_name'] = $this->url->link('design/popup.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_status'] = $this->url->link('design/popup.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $popup_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/popup.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($popup_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($popup_total - $this->config->get('config_pagination_admin'))) ? $popup_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $popup_total, ceil($popup_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('design/popup_list', $data);
	}


	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('design/popup');

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['popup_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('design/popup', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/popup.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/popup', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['popup_id'])) {
			$this->load->model('design/popup');

			$popup_info = $this->model_design_popup->getPopup($this->request->get['popup_id']);
		}

		if (isset($this->request->get['popup_id'])) {
			$data['popup_id'] = (int)$this->request->get['popup_id'];
		} else {
			$data['popup_id'] = 0;
		}

		if(!empty($popup_info)){
			$data['title'] = $popup_info['title'];
			$data['initial_delay'] = $popup_info['initial_delay'];
			$data['time_to_close'] = $popup_info['time_to_close'];
			$data['show_everytime'] = $popup_info['show_everytime'];
			$data['width'] = $popup_info['width'];
		}else{
			$data['title'] = '';
			$data['initial_delay'] = 0;
			$data['time_to_close'] = 3;
			$data['show_everytime'] = 0;
			$data['width'] = 0;
		}

		if (!empty($popup_info)) {
			$data['status'] = $popup_info['status'];
		} else {
			$data['status'] = true;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		if (!empty($popup_info)) {
			$popup_contents = $this->model_design_popup->getContents($this->request->get['popup_id']);
		} else {
			$popup_contents = [];
		}

		$data['popup_contents'] = [];

		foreach ($popup_contents as $language_id => $popup_content) {
			$data['popup_contents'][$language_id] = [
				'header'      => $popup_content['header'],
				'content'     => $popup_content['content']
			];
		}


		$this->load->model('setting/store');
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/popup_form', $data));
	}


	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/popup');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/popup')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['title']) < 3) || (oc_strlen($this->request->post['title']) > 255)) {
			$json['error']['title'] = $this->language->get('error_title');
		}

		$this->request->post['initial_delay'] = max(0, intval($this->request->post['initial_delay']));
		$this->request->post['time_to_close'] = max(1, intval($this->request->post['time_to_close']));
		$this->request->post['width'] = max(0, intval($this->request->post['width']));
		$this->request->post['show_everytime'] = intval($this->request->post['show_everytime']) === 1 ? '1' : '0';

		if (!$json) {
			$this->load->model('design/popup');

			if (!$this->request->post['popup_id']) {
				$json['popup_id'] = $this->model_design_popup->addPopup($this->request->post);
			} else {
				$this->model_design_popup->editPopup($this->request->post['popup_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('design/popup');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/popup')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/popup');

			foreach ($selected as $popup_id) {
				$this->model_design_popup->deletePopup($popup_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
