<?php
class ControllerModulePPLogin extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('module/pp_login');

		$this->load->model('setting/setting');
		$this->load->model('design/layout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_login', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_content_top'] = $this->language->get('text_content_top');
		$data['text_content_bottom'] = $this->language->get('text_content_bottom');		
		$data['text_column_left'] = $this->language->get('text_column_left');
		$data['text_column_right'] = $this->language->get('text_column_right');
		$data['text_grey_button'] = $this->language->get('text_grey_button');
		$data['text_blue_button'] = $this->language->get('text_blue_button');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
		$data['text_return_url'] = $this->language->get('text_return_url');

        $data['entry_client_id'] = $this->language->get('entry_client_id');
        $data['entry_secret'] = $this->language->get('entry_secret');
        $data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$data['entry_logging'] = $this->language->get('entry_logging');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_button'] = $this->language->get('entry_button');
		$data['entry_seamless'] = $this->language->get('entry_seamless');
        $data['entry_status'] = $this->language->get('entry_status');
		$data['entry_layout'] = $this->language->get('entry_layout');
		$data['entry_position'] = $this->language->get('entry_position');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
        
        $data['help_sandbox'] = $this->language->get('help_sandbox');
		$data['help_customer_group'] = $this->language->get('help_customer_group');
		$data['help_seamless'] = $this->language->get('help_seamless');
		$data['help_debug_logging'] = $this->language->get('help_debug_logging');
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
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/pp_login', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('module/pp_login', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

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
		
		if (isset($this->request->post['pp_login_logging'])) {
			$data['pp_login_logging'] = $this->request->post['pp_login_logging'];
		} else {
			$data['pp_login_logging'] = $this->config->get('pp_login_logging');
		}
		
		$this->load->model('sale/customer_group');

		$data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['customer_group_id'])) {
			$data['customer_group_id'] = $this->request->post['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
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
        
		if (isset($this->request->post['pp_login_status'])) {
			$data['pp_login_status'] = $this->request->post['pp_login_status'];
		} else {
			$data['pp_login_status'] = $this->config->get('pp_login_status');
		}
        
		$data['modules'] = array();
		
		if (isset($this->request->post['pp_login_module'])) {
			$data['modules'] = $this->request->post['pp_login_module'];
		} elseif ($this->config->get('pp_login_module')) { 
			$data['modules'] = $this->config->get('pp_login_module');
		}
		
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		
		$data['languages'] = array();
		foreach ($languages as $language_code => $language) {
			$data['languages'][$language_code] = $language;
			$data['languages'][$language_code]['entry_locale'] = sprintf($this->language->get('entry_locale'), $language['name']);
			$data['languages'][$language_code]['help_locale'] = sprintf($this->language->get('help_locale'), $language['name']);
		}
		
		$data['pp_login_locale_all'] = array(
			'en-gb' => 'English (Great Britain)',
			'zh-cn' => 'Chinese (People\'s Republic of China)',
			'zh-hk' => 'Chinese (Hong Kong)',
			'zh-tw' => 'Chinese (Taiwan)',
			'zh-xc' => 'Chinese (US)',
			'da-dk' => 'Danish',
			'nl-nl' => 'Dutch',
			'en-au' => 'English (Australia)',
			'en-us' => 'English (US)',
			'fr-fr' => 'French',
			'fr-ca' => 'French (Canada)',
			'fr-xc' => 'French (international)',
			'de-de' => 'German',
			'he-il' => 'Hebrew (Israel)',
			'id-id' => 'Indonesian',
			'it-il' => 'Italian',
			'ja-jp' => 'Japanese',
			'no-no' => 'Norwegian',
			'pl-pl' => 'Polish',
			'pt-pt' => 'Portuguese',
			'pt-br' => 'Portuguese (Brazil)',
			'ru-ru' => 'Russian',
			'es-es' => 'Spanish',
			'es-xc' => 'Spanish (Mexico)',
			'sv-se' => 'Swedish',
			'th-th' => 'Thai',
			'tr-tr' => 'Turkish'
		);
		
		$data['pp_login_locale_saved'] = $this->config->get('pp_login_locale');
		
		$data['pp_login_return_url'] = HTTPS_CATALOG . 'index.php?route=module/pp_login/login';
				
		$this->load->model('design/layout');
		
		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
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

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
	public function install() {
		$this->load->model('tool/event');
		
		$this->model_tool_event->setHandler('customer_logout', array(
			'type'   => 'module', 
			'code'   => 'pp_login', 
			'method' => 'logout')
		);
	}
	
	public function uninstall() {
		$this->load->model('tool/event');
		
		$this->model_tool_event->removeHandler('customer_logout', array(
			'type'   => 'module', 
			'code'   => 'pp_login', 
			'method' => 'logout')
		);
	}
}