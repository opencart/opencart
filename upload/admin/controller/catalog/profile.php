<?php 
class ControllerCatalogProfile extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/profile');

		$this->getList();
	}

	protected function getList() {

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'),       		
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_copy'] = $this->language->get('button_copy');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['profiles'] = array();

		$profiles = $this->model_catalog_profile->getProfiles();

		foreach ($profiles as $profile) {
			$action = array();

			$action[] = array(
				'href' => $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $profile['profile_id'], 'SSL'),
				'name' => $this->language->get('text_edit'),
			);

			$this->data['profiles'][] = array(
				'profile_id' => $profile['profile_id'],
				'name' => $profile['name'],
				'sort_order' => $profile['sort_order'],
				'action' => $action,
			);
		}

		$this->data['insert'] = $this->url->link('catalog/profile/insert', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['copy'] = $this->url->link('catalog/profile/copy', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['delete'] = $this->url->link('catalog/profile/delete', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['pagination'] = '';

		$this->template = 'catalog/profile_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function insert() {
		$this->language->load('catalog/profile');
		$this->load->model('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_profile->addProfile($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
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

			$this->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getForm();
	}

	protected function getForm() {
		$this->load->model('localisation/language');

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		if (!isset($this->request->get['profile_id'])) {
			$this->data['action'] = $this->url->link('catalog/profile/insert', 'token=' . $this->session->data['token'], 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $this->request->get['profile_id'], 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['token'] = $this->session->data['token'];

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		$this->data['sort_order'] = '0';

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_duration'] = $this->language->get('entry_duration');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_cycle'] = $this->language->get('entry_cycle');
		$this->data['entry_frequency'] = $this->language->get('entry_frequency');
		$this->data['entry_trial_price'] = $this->language->get('entry_trial_price');
		$this->data['entry_trial_duration'] = $this->language->get('entry_trial_duration');
		$this->data['entry_trial_status'] = $this->language->get('entry_trial_status');
		$this->data['entry_trial_cycle'] = $this->language->get('entry_trial_cycle');
		$this->data['entry_trial_frequency'] = $this->language->get('entry_trial_frequency');

		$this->data['button_add_profile'] = $this->language->get('button_add_profile');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['text_recurring_help'] = $this->language->get('text_recurring_help');

		$this->data['frequencies'] = $this->model_catalog_profile->getFrequencies();

		if (isset($this->request->get['profile_id'])) {
			$profile = $this->model_catalog_profile->getProfile($this->request->get['profile_id']);
		} else {
			$profile = array();
		}

		if (isset($this->request->post['profile_description'])) {
			$this->data['profile_description'] = $this->request->post['profile_description'];
		} elseif (!empty($profile)) {
			$this->data['profile_description'] = $this->model_catalog_profile->getProfileDescription($profile['profile_id']);
		} else {
			$this->data['profile_description'] = array();
		}

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($profile)) {
			$this->data['sort_order'] = $profile['sort_order'];
		} else {
			$this->data['sort_order'] = 0;
		}

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($profile)) {
			$this->data['status'] = $profile['status'];
		} else {
			$this->data['status'] = 0;
		}

		if (isset($this->request->post['price'])) {
			$this->data['price'] = $this->request->post['price'];
		} elseif (!empty($profile)) {
			$this->data['price'] = $profile['price'];
		} else {
			$this->data['price'] = 0;
		}

		if (isset($this->request->post['frequency'])) {
			$this->data['frequency'] = $this->request->post['frequency'];
		} elseif (!empty($profile)) {
			$this->data['frequency'] = $profile['frequency'];
		} else {
			$this->data['frequency'] = '';
		}

		if (isset($this->request->post['duration'])) {
			$this->data['duration'] = $this->request->post['duration'];
		} elseif (!empty($profile)) {
			$this->data['duration'] = $profile['duration'];
		} else {
			$this->data['duration'] = 0;
		}

		if (isset($this->request->post['cycle'])) {
			$this->data['cycle'] = $this->request->post['cycle'];
		} elseif (!empty($profile)) {
			$this->data['cycle'] = $profile['cycle'];
		} else {
			$this->data['cycle'] = 1;
		}

		if (isset($this->request->post['trial_status'])) {
			$this->data['trial_status'] = $this->request->post['trial_status'];
		} elseif (!empty($profile)) {
			$this->data['trial_status'] = $profile['trial_status'];
		} else {
			$this->data['trial_status'] = 0;
		}

		if (isset($this->request->post['trial_price'])) {
			$this->data['trial_price'] = $this->request->post['trial_price'];
		} elseif (!empty($profile)) {
			$this->data['trial_price'] = $profile['trial_price'];
		} else {
			$this->data['trial_price'] = 0.00;
		}

		if (isset($this->request->post['trial_frequency'])) {
			$this->data['trial_frequency'] = $this->request->post['trial_frequency'];
		} elseif (!empty($profile)) {
			$this->data['trial_frequency'] = $profile['trial_frequency'];
		} else {
			$this->data['trial_frequency'] = '';
		}

		if (isset($this->request->post['trial_duration'])) {
			$this->data['trial_duration'] = $this->request->post['trial_duration'];
		} elseif (!empty($profile)) {
			$this->data['trial_duration'] = $profile['trial_duration'];
		} else {
			$this->data['trial_duration'] = '0';
		}

		if (isset($this->request->post['trial_cycle'])) {
			$this->data['trial_cycle'] = $this->request->post['trial_cycle'];
		} elseif (!empty($profile)) {
			$this->data['trial_cycle'] = $profile['trial_cycle'];
		} else {
			$this->data['trial_cycle'] = '1';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

		$this->template = 'catalog/profile_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function delete() {
		$this->language->load('catalog/profile');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/profile');

		if (isset($this->request->post['profile_ids']) && $this->validateDelete()) {
			foreach ($this->request->post['profile_ids'] as $profile_id) {
				$this->model_catalog_profile->deleteProfile($profile_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
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

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('catalog/profile', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->getList();
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
?>