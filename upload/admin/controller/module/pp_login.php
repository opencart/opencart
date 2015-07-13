<?php
class ControllerModulePPLogin extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('module/pp_login');

		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_button_grey'] = $this->language->get('text_button_grey');
		$data['text_button_blue'] = $this->language->get('text_button_blue');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_client_id'] = $this->language->get('entry_client_id');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_button'] = $this->language->get('entry_button');
		$data['entry_seamless'] = $this->language->get('entry_seamless');
		$data['entry_locale'] = $this->language->get('entry_locale');
		$data['entry_return_url'] = $this->language->get('entry_return_url');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_sandbox'] = $this->language->get('help_sandbox');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_seamless'] = $this->language->get('help_seamless');
		$data['help_debug_logging'] = $this->language->get('help_debug_logging');
		$data['help_locale'] = $this->language->get('help_locale');
		$data['help_return_url'] = $this->language->get('help_return_url');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_module_add'] = $this->language->get('button_module_add');
		$data['button_remove'] = $this->language->get('button_remove');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/pp_login', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('module/pp_login', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_login_client_id'])) {
			$data['pp_login_client_id'] = $this->request->post['pp_login_client_id'];
		} else {
			$data['pp_login_client_id'] = $this->config->get('pp_login_client_id');
		}

		if (isset($this->request->post['pp_login_secret'])) {
			$data['pp_login_secret'] = $this->request->post['pp_login_secret'];
		} else {
			$data['pp_login_secret'] = $this->config->get('pp_login_secret');
		}

		if (isset($this->request->post['pp_login_sandbox'])) {
			$data['pp_login_sandbox'] = $this->request->post['pp_login_sandbox'];
		} else {
			$data['pp_login_sandbox'] = $this->config->get('pp_login_sandbox');
		}

		if (isset($this->request->post['pp_login_debug'])) {
			$data['pp_login_debug'] = $this->request->post['pp_login_debug'];
		} else {
			$data['pp_login_debug'] = $this->config->get('pp_login_debug');
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($this->request->post['pp_login_customer_group_id'])) {
			$data['pp_login_customer_group_id'] = $this->request->post['pp_login_customer_group_id'];
		} else {
			$data['pp_login_customer_group_id'] = $this->config->get('pp_login_customer_group_id');
		}

		if (isset($this->request->post['pp_login_button_colour'])) {
			$data['pp_login_button_colour'] = $this->request->post['pp_login_button_colour'];
		} elseif ($this->config->get('pp_login_button_colour')) {
			$data['pp_login_button_colour'] = $this->config->get('pp_login_button_colour');
		} else {
			$data['pp_login_button_colour'] = 'blue';
		}

		if (isset($this->request->post['pp_login_seamless'])) {
			$data['pp_login_seamless'] = $this->request->post['pp_login_seamless'];
		} else {
			$data['pp_login_seamless'] = $this->config->get('pp_login_seamless');
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

		if (isset($this->request->post['pp_login_locale'])) {
			$data['pp_login_locale'] = $this->request->post['pp_login_locale'];
		} else {
			$data['pp_login_locale'] = $this->config->get('pp_login_locale');
		}

		$data['return_url'] = HTTPS_CATALOG . 'index.php?route=module/pp_login/login';

		if (isset($this->request->post['pp_login_status'])) {
			$data['pp_login_status'] = $this->request->post['pp_login_status'];
		} else {
			$data['pp_login_status'] = $this->config->get('pp_login_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('module/pp_login.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/pp_login')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_login_client_id']) {
			$this->error['client_id'] = $this->language->get('error_client_id');
		}

		if (!$this->request->post['pp_login_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('extension/event');

		$this->model_extension_event->addEvent('pp_login', 'post.customer.logout', 'module/pp_login/logout');
	}

	public function uninstall() {
		$this->load->model('extension/event');

		$this->model_extension_event->deleteEvent('pp_login');
	}
}