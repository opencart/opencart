<?php 
class ControllerCatalogProfile extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/profile');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/profile');
		$this->load->model('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_profile->addProfile($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/profile');
		$this->load->model('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_profile->updateProfile($this->request->get['profile_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'),
		);

		if (!isset($this->request->get['profile_id'])) {
			$data['action'] = $this->url->link('catalog/profile/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $this->request->get['profile_id'], 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_remove'] = $this->language->get('button_remove');

		$data['token'] = $this->session->data['token'];

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_duration'] = $this->language->get('entry_duration');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_cycle'] = $this->language->get('entry_cycle');
		$data['entry_frequency'] = $this->language->get('entry_frequency');
		$data['entry_trial_price'] = $this->language->get('entry_trial_price');
		$data['entry_trial_duration'] = $this->language->get('entry_trial_duration');
		$data['entry_trial_status'] = $this->language->get('entry_trial_status');
		$data['entry_trial_cycle'] = $this->language->get('entry_trial_cycle');
		$data['entry_trial_frequency'] = $this->language->get('entry_trial_frequency');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_profile'] = $this->language->get('tab_profile');
		$data['tab_trial'] = $this->language->get('tab_trial');

		$data['text_recurring_help'] = $this->language->get('text_recurring_help');

		$data['frequencies'] = $this->model_catalog_profile->getFrequencies();

		if (isset($this->request->get['profile_id'])) {
			$profile = $this->model_catalog_profile->getProfile($this->request->get['profile_id']);
		} else {
			$profile = array();
		}

		if (isset($this->request->post['profile_description'])) {
			$data['profile_description'] = $this->request->post['profile_description'];
		} elseif (!empty($profile)) {
			$data['profile_description'] = $this->model_catalog_profile->getProfileDescription($profile['profile_id']);
		} else {
			$data['profile_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($profile)) {
			$data['sort_order'] = $profile['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($profile)) {
			$data['status'] = $profile['status'];
		} else {
			$data['status'] = 0;
		}

		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($profile)) {
			$data['price'] = $profile['price'];
		} else {
			$data['price'] = 0;
		}

		if (isset($this->request->post['frequency'])) {
			$data['frequency'] = $this->request->post['frequency'];
		} elseif (!empty($profile)) {
			$data['frequency'] = $profile['frequency'];
		} else {
			$data['frequency'] = '';
		}

		if (isset($this->request->post['duration'])) {
			$data['duration'] = $this->request->post['duration'];
		} elseif (!empty($profile)) {
			$data['duration'] = $profile['duration'];
		} else {
			$data['duration'] = 0;
		}

		if (isset($this->request->post['cycle'])) {
			$data['cycle'] = $this->request->post['cycle'];
		} elseif (!empty($profile)) {
			$data['cycle'] = $profile['cycle'];
		} else {
			$data['cycle'] = 1;
		}

		if (isset($this->request->post['trial_status'])) {
			$data['trial_status'] = $this->request->post['trial_status'];
		} elseif (!empty($profile)) {
			$data['trial_status'] = $profile['trial_status'];
		} else {
			$data['trial_status'] = 0;
		}

		if (isset($this->request->post['trial_price'])) {
			$data['trial_price'] = $this->request->post['trial_price'];
		} elseif (!empty($profile)) {
			$data['trial_price'] = $profile['trial_price'];
		} else {
			$data['trial_price'] = 0.00;
		}

		if (isset($this->request->post['trial_frequency'])) {
			$data['trial_frequency'] = $this->request->post['trial_frequency'];
		} elseif (!empty($profile)) {
			$data['trial_frequency'] = $profile['trial_frequency'];
		} else {
			$data['trial_frequency'] = '';
		}

		if (isset($this->request->post['trial_duration'])) {
			$data['trial_duration'] = $this->request->post['trial_duration'];
		} elseif (!empty($profile)) {
			$data['trial_duration'] = $profile['trial_duration'];
		} else {
			$data['trial_duration'] = '0';
		}

		if (isset($this->request->post['trial_cycle'])) {
			$data['trial_cycle'] = $this->request->post['trial_cycle'];
		} elseif (!empty($profile)) {
			$data['trial_cycle'] = $profile['trial_cycle'];
		} else {
			$data['trial_cycle'] = '1';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/profile_form.tpl', $data));
	}

	public function delete() {
		$this->language->load('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/profile');

		if (isset($this->request->post['profile_ids']) && $this->validateDelete()) {
			foreach ($this->request->post['profile_ids'] as $profile_id) {
				$this->model_catalog_profile->deleteProfile($profile_id);
			}

			$this->session->data['success'] = $this->language->get('text_removed');

			$this->response->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->language->load('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/profile');

		if (isset($this->request->post['profile_ids']) && $this->validateCopy()) {
			foreach ($this->request->post['profile_ids'] as $profile_id) {
				$this->model_catalog_profile->copyProfile($profile_id);
			}

			$this->session->data['success'] = $this->language->get('text_copied');

			$this->response->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.name';
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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['heading_title'] = $this->language->get('heading_title');
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_copy'] = $this->language->get('button_copy');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['profiles'] = array();

		$filter_data = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$profile_total = $this->model_catalog_profile->getTotalProfiles($filter_data);
		$profiles = $this->model_catalog_profile->getProfiles($filter_data);

		foreach ($profiles as $profile) {
			$action = array();

			$action[] = array(
				'href' => $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $profile['profile_id'], 'SSL'),
				'name' => $this->language->get('text_edit'),
			);

			$data['profiles'][] = array(
				'profile_id' => $profile['profile_id'],
				'name' => $profile['name'],
				'sort_order' => $profile['sort_order'],
				'action' => $action,
			);
		}

		$data['insert'] = $this->url->link('catalog/profile/insert', 'token=' . $this->session->data['token'].$url, 'SSL');
		$data['copy'] = $this->url->link('catalog/profile/copy', 'token=' . $this->session->data['token'].$url, 'SSL');
		$data['delete'] = $this->url->link('catalog/profile/delete', 'token=' . $this->session->data['token'].$url, 'SSL');

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

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$pagination = new Pagination();
		$pagination->total = $profile_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/profile', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($profile_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($profile_total - $this->config->get('config_limit_admin'))) ? $profile_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $profile_total, ceil($profile_total / $this->config->get('config_limit_admin')));

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/profile_list.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['profile_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/profile')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}