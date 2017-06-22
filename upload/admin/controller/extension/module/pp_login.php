<?php
class ControllerExtensionModulePPLogin extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/pp_login');

		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_pp_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['client_id'])) {
			$data['error_client_id'] = $this->error['client_id'];
		} else {
			$data['error_client_id'] = '';
		}

		if (isset($this->error['secret'])) {
			$data['error_secret'] = $this->error['secret'];
		} else {
			$data['error_secret'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/pp_login', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/pp_login', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->post['module_pp_login_client_id'])) {
			$data['module_pp_login_client_id'] = $this->request->post['module_pp_login_client_id'];
		} else {
			$data['module_pp_login_client_id'] = $this->config->get('module_pp_login_client_id');
		}

		if (isset($this->request->post['module_pp_login_secret'])) {
			$data['module_pp_login_secret'] = $this->request->post['module_pp_login_secret'];
		} else {
			$data['module_pp_login_secret'] = $this->config->get('module_pp_login_secret');
		}

		if (isset($this->request->post['module_pp_login_sandbox'])) {
			$data['module_pp_login_sandbox'] = $this->request->post['module_pp_login_sandbox'];
		} else {
			$data['module_pp_login_sandbox'] = $this->config->get('module_pp_login_sandbox');
		}

		if (isset($this->request->post['module_pp_login_debug'])) {
			$data['module_pp_login_debug'] = $this->request->post['module_pp_login_debug'];
		} else {
			$data['module_pp_login_debug'] = $this->config->get('module_pp_login_debug');
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['module_pp_login_customer_group_id'])) {
			$data['module_pp_login_customer_group_id'] = $this->request->post['module_pp_login_customer_group_id'];
		} else {
			$data['module_pp_login_customer_group_id'] = $this->config->get('module_pp_login_customer_group_id');
		}

		if (isset($this->request->post['module_pp_login_button_colour'])) {
			$data['module_pp_login_button_colour'] = $this->request->post['module_pp_login_button_colour'];
		} elseif ($this->config->get('module_pp_login_button_colour')) {
			$data['module_pp_login_button_colour'] = $this->config->get('module_pp_login_button_colour');
		} else {
			$data['module_pp_login_button_colour'] = 'blue';
		}

		if (isset($this->request->post['module_pp_login_seamless'])) {
			$data['module_pp_login_seamless'] = $this->request->post['module_pp_login_seamless'];
		} else {
			$data['module_pp_login_seamless'] = $this->config->get('module_pp_login_seamless');
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['locales'] = array();

		$data['locales'][] = array(
			'value' => 'en-gb',
			'text' => 'English (Great Britain)'
		);

		$data['locales'][] = array(
			'value' => 'zh-cn',
			'text' => 'Chinese (People\'s Republic of China)'
		);

		$data['locales'][] = array(
			'value' => 'zh-hk',
			'text' => 'Chinese (Hong Kong)',
		);

		$data['locales'][] = array(
			'value' => 'zh-tw',
			'text' => 'Chinese (Taiwan)'
		);

		$data['locales'][] = array(
			'value' => 'zh-xc',
			'text' => 'Chinese (US)'
		);

		$data['locales'][] = array(
			'value' => 'da-dk',
			'text' => 'Danish'
		);

		$data['locales'][] = array(
			'value' => 'nl-nl',
			'text' => 'Dutch'
		);

		$data['locales'][] = array(
			'value' => 'en-au',
			'text' => 'English (Australia)'
		);

		$data['locales'][] = array(
			'value' => 'en-us',
			'text' => 'English (US)',
		);

		$data['locales'][] = array(
			'value' => 'fr-fr',
			'text' => 'French'
		);

		$data['locales'][] = array(
			'value' => 'fr-ca',
			'text' => 'French (Canada)'
		);

		$data['locales'][] = array(
			'value' => 'fr-xc',
			'text' => 'French (international)'
		);

		$data['locales'][] = array(
			'value' => 'de-de',
			'text' => 'German'
		);

		$data['locales'][] = array(
			'value' => 'he-il',
			'text' => 'Hebrew (Israel)'
		);

		$data['locales'][] = array(
			'value' => 'id-id',
			'text' => 'Indonesian'
		);

		$data['locales'][] = array(
			'value' => 'it-il',
			'text' => 'Italian'
		);

		$data['locales'][] = array(
			'value' => 'ja-jp' ,
			'text' => 'Japanese'
		);

		$data['locales'][] = array(
			'value' => 'no-no',
			'text' => 'Norwegian'
		);

		$data['locales'][] = array(
			'value' => 'pl-pl',
			'text' => 'Polish');

		$data['locales'][] = array(
			'value' => 'pt-pt',
			'text' => 'Portuguese'
		);

		$data['locales'][] = array(
			'value' => 'pt-br',
			'text' => 'Portuguese (Brazil)'
		);

		$data['locales'][] = array(
			'value' => 'ru-ru',
			'text' => 'Russian'
		);

		$data['locales'][] = array(
			'value' => 'es-es',
			'text'  => 'Spanish'
		);

		$data['locales'][] = array(
			'value' => 'es-xc',
			'text'  => 'Spanish (Mexico)'
		);

		$data['locales'][] = array(
			'value' => 'sv-se',
			'text'  => 'Swedish'
		);

		$data['locales'][] = array(
			'value' => 'th-th',
			'text'  => 'Thai'
		);

		$data['locales'][] = array(
			'value' => 'tr-tr',
			'text'  => 'Turkish'
		);

		if (isset($this->request->post['module_pp_login_locale'])) {
			$data['module_pp_login_locale'] = $this->request->post['module_pp_login_locale'];
		} else {
			$data['module_pp_login_locale'] = $this->config->get('module_pp_login_locale');
		}

		$data['return_url'] = HTTPS_CATALOG . 'index.php?route=extension/module/pp_login/login';

		if (isset($this->request->post['module_pp_login_status'])) {
			$data['module_pp_login_status'] = $this->request->post['module_pp_login_status'];
		} else {
			$data['module_pp_login_status'] = $this->config->get('module_pp_login_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/pp_login', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/pp_login')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['module_pp_login_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['module_pp_login_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('marketplace/event');

		$this->model_setting_event->addEvent('pp_login', 'catalog/controller/account/logout/after', 'extension/module/pp_login/logout');
	}

	public function uninstall() {
		$this->load->model('marketplace/event');

		$this->model_setting_event->deleteEventByCode('pp_login');
	}
}