<?php
class ControllerExtensionExtension extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');

		$data['tab_store'] = $this->language->get('tab_store');
		$data['tab_downloaded'] = $this->language->get('tab_downloaded');
		$data['tab_installer'] = $this->language->get('tab_installer');

		$data['token'] = $this->session->data['token'];
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$data['categories'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/analytics/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_analytics') . ' (' . count($files) .')',
			'value' => 'analytics'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/captcha/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_captcha') . ' (' . count($files) .')',
			'value' => 'captcha'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');
				
		$data['categories'][] = array(
			'text'  => $this->language->get('text_feed') . ' (' . count($files) .')',
			'value' => 'feed'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/fraud/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_fraud') . ' (' . count($files) .')',
			'value' => 'fraud'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/module/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_module') . ' (' . count($files) .')',
			'value' => 'module'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_payment') . ' (' . count($files) .')',
			'value' => 'payment'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_shipping') . ' (' . count($files) .')',
			'value' => 'shipping'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/theme/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_theme') . ' (' . count($files) .')',
			'value' => 'theme'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/menu/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_menu') . ' (' . count($files) .')',
			'value' => 'menu'
		);
		
		$files = glob(DIR_APPLICATION . 'controller/total/*.php');
		
		$data['categories'][] = array(
			'text'  => $this->language->get('text_total') . ' (' . count($files) .')',
			'value' => 'total'
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/extension', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$allowed = array(
			'analytics',
			'captcha',
			'feed',
			'fraud',
			'module',
			'payment',
			'shipping',
			'theme',
			'menu',
			'total'				
		);

		if (!in_array($this->request->get['type'], $allowed)) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$this->load->language('extension/extension');

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install($this->request->get['type'], $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->request->get['type'] . '/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->request->get['type'] . '/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller($this->request->get['type'] . '/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');
		}
	}

	public function uninstall() {
		$this->load->language('extension/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->uninstall($this->request->get['type'], $this->request->get['extension']);

			// Call uninstall method if it exsits
			$this->load->controller($this->request->get['type'] . '/' . $this->request->get['extension'] . '/uninstall');

			$this->session->data['success'] = $this->language->get('text_success');
		}
	}
	
	public function analytics() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
				
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		$this->load->model('setting/store');
		$this->load->model('extension/extension');
		
		$stores = $this->model_setting_store->getStores();
				
		// Analytics
		$extensions = $this->model_extension_extension->getInstalled('analytics');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/analytics/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('analytics', $value);

				unset($extensions[$key]);
			}
		}
		
		$data['analytics'] = array();
		
		$files = glob(DIR_APPLICATION . 'controller/analytics/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('analytics/' . $extension);

				$store_data = array();
				
				$store_data[] = array(
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('analytics/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=0', true),
					'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
				);
									
				foreach ($stores as $store) {
					$store_data[] = array(
						'name'   => $store['name'],
						'edit'   => $this->url->link('analytics/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=' . $store['store_id'], true),
						'status' => $this->model_setting_setting->getSettingValue($extension . '_status', $store['store_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					);
				}
				
				$data['analytics'][] = array(
					'name'      => $this->language->get('heading_title'),
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=analytics&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=analytics&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'store'     => $store_data
				);
			}
		}
		
		$sort_order = array();

		foreach ($data['analytics'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['analytics']);
				
		$this->response->setOutput($this->load->view('extension/extension_analytics', $data));			
	}
	
	public function captcha() {
		$this->load->language('extension/extension');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled('captcha');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/captcha/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('captcha', $value);

				unset($extensions[$key]);
			}
		}
						
		$data['captchas'] = array();

		$files = glob(DIR_APPLICATION . 'controller/captcha/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('captcha/' . $extension);

				$data['captchas'][] = array(
					'name'      => $this->language->get('heading_title') . (($extension == $this->config->get('config_captcha')) ? $this->language->get('text_default') : null),
					'status'    => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=captcha&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=captcha&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('captcha/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}

		$sort_order = array();

		foreach ($data['captchas'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['captchas']);
		
		$this->response->setOutput($this->load->view('extension/extension_captcha', $data));	
	}
		
	public function feed() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');

		$this->load->model('extension/extension');
		
		$extensions = $this->model_extension_extension->getInstalled('feed');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/feed/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('feed', $value);

				unset($extensions[$key]);
			}
		}
		
		$data['feeds'] = array();

		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('feed/' . $extension);

				$data['feeds'][] = array(
					'name'      => $this->language->get('heading_title'),
					'status'    => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=feed&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=feed&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('feed/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}

		$sort_order = array();

		foreach ($data['feeds'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['feeds']);
				
		$this->response->setOutput($this->load->view('extension/extension_feed', $data));		
	}
	
	public function fraud() {
		$this->load->language('extension/extension');
	
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled('fraud');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/fraud/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('fraud', $value);

				unset($extensions[$key]);
			}
		}
				
		$data['frauds'] = array();

		$files = glob(DIR_APPLICATION . 'controller/fraud/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('fraud/' . $extension);

				$data['frauds'][] = array(
					'name'      => $this->language->get('heading_title'),
					'status'    => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=fraud&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=fraud&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('fraud/' . $extension, 'token=' . $this->session->data['token'] . '&type=fraud', true)
				);
			}
		}

		$sort_order = array();

		foreach ($data['frauds'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['frauds']);
		
		$this->response->setOutput($this->load->view('extension/extension_fraud', $data));	
	}
	
	public function menu() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');
	
		$extensions = $this->model_extension_extension->getInstalled('menu');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/menu/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('menu', $value);

				unset($extensions[$key]);
			}
		}

		$data['menus'] = array();

		$files = glob(DIR_APPLICATION . 'controller/menu/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('menu/' . $extension);

				$data['menus'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=menu&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=menu&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('menu/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}		
			
		$sort_order = array();

		foreach ($data['menus'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['menus']);	
		
		$this->response->setOutput($this->load->view('extension/extension_menu', $data));		
	}
	
	public function module() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');
		$this->load->model('extension/module');
		
		$extensions = $this->model_extension_extension->getInstalled('module');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/module/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('module', $value);

				unset($extensions[$key]);

				$this->model_extension_module->deleteModulesByCode($value);
			}
		}

		$data['modules'] = array();

		$files = glob(DIR_APPLICATION . 'controller/module/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('module/' . $extension);

				$module_data = array();

				$modules = $this->model_extension_module->getModulesByCode($extension);

				foreach ($modules as $module) {
					$module_data[] = array(
						'module_id' => $module['module_id'],
						'name'      => $module['name'],
						'edit'      => $this->url->link('module/' . $extension, 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], true),
						'delete'    => $this->url->link('extension/extension/delete', 'token=' . $this->session->data['token'] . '&module_id=' . $module['module_id'], true)
					);
				}

				$data['modules'][] = array(
					'name'      => $this->language->get('heading_title'),
					'module'    => $module_data,
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=module&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=module&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'edit'      => $this->url->link('module/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}

		$sort_order = array();

		foreach ($data['modules'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['modules']);
		
		$this->response->setOutput($this->load->view('extension/extension_module', $data));	
	}
	
	public function payment() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');
		
		$extensions = $this->model_extension_extension->getInstalled('payment');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/payment/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('payment', $value);

				unset($extensions[$key]);
			}
		}

		$data['payments'] = array();

		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('payment/' . $extension);

				$text_link = $this->language->get('text_' . $extension);

				if ($text_link != 'text_' . $extension) {
					$link = $this->language->get('text_' . $extension);
				} else {
					$link = '';
				}

				$data['payments'][] = array(
					'name'       => $this->language->get('heading_title'),
					'link'       => $link,
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=payment&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=payment&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('payment/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}	
		
		$sort_order = array();

		foreach ($data['payments'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['payments']);
		
		$this->response->setOutput($this->load->view('extension/extension_payment', $data));		
	}

	public function shipping() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');
		
		$extensions = $this->model_extension_extension->getInstalled('shipping');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('shipping', $value);

				unset($extensions[$key]);
			}
		}

		$data['shippings'] = array();

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('shipping/' . $extension);

				$data['shippings'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=shipping&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=shipping&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('shipping/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}		
		
		$sort_order = array();

		foreach ($data['shippings'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['shippings']);
		
		$this->response->setOutput($this->load->view('extension/extension_shipping', $data));					
	}
	
	public function theme() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		
		$this->load->model('extension/extension');
		
		$extensions = $this->model_extension_extension->getInstalled('theme');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/theme/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('theme', $value);

				unset($extensions[$key]);
			}
		}

		$data['themes'] = array();

		$files = glob(DIR_APPLICATION . 'controller/theme/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');
				
				$this->load->language('theme/' . $extension);
					
				$store_data = array();
				
				$store_data[] = array(
					'name'   => $this->config->get('config_name'),
					'edit'   => $this->url->link('theme/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=0', true),
					'status' => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
				);
									
				foreach ($stores as $store) {
					$store_data[] = array(
						'name'   => $store['name'],
						'edit'   => $this->url->link('theme/' . $extension, 'token=' . $this->session->data['token'] . '&store_id=' . $store['store_id'], true),
						'status' => $this->model_setting_setting->getSettingValue($extension . '_status', $store['store_id']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled')
					);
				}
				
				$data['themes'][] = array(
					'name'      => $this->language->get('heading_title'),
					'install'   => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=theme&extension=' . $extension, true),
					'uninstall' => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=theme&extension=' . $extension, true),
					'installed' => in_array($extension, $extensions),
					'store'     => $store_data
				);
			}
		}	
		
		$sort_order = array();

		foreach ($data['themes'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['themes']);	
		
		$this->response->setOutput($this->load->view('extension/extension_theme', $data));	
	}
	
	public function total() {
		$this->load->language('extension/extension');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
	
		$this->load->model('extension/extension');
		
		$extensions = $this->model_extension_extension->getInstalled('total');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('total', $value);

				unset($extensions[$key]);
			}
		}

		$data['totals'] = array();

		$files = glob(DIR_APPLICATION . 'controller/total/*.php');

		if ($files) {
			foreach ($files as $file) {
				$extension = basename($file, '.php');

				$this->load->language('total/' . $extension);

				$data['totals'][] = array(
					'name'       => $this->language->get('heading_title'),
					'status'     => $this->config->get($extension . '_status') ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
					'sort_order' => $this->config->get($extension . '_sort_order'),
					'install'    => $this->url->link('extension/extension/install', 'token=' . $this->session->data['token'] . '&type=total&extension=' . $extension, true),
					'uninstall'  => $this->url->link('extension/extension/uninstall', 'token=' . $this->session->data['token'] . '&type=total&extension=' . $extension, true),
					'installed'  => in_array($extension, $extensions),
					'edit'       => $this->url->link('total/' . $extension, 'token=' . $this->session->data['token'], true)
				);
			}
		}		
			
		$sort_order = array();

		foreach ($data['totals'] as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $data['totals']);
		
		$this->response->setOutput($this->load->view('extension/extension_total', $data));		
	}								
}
