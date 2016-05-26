<?php
class ControllerExtensionExtension extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		$this->getList();
	}
	
	public function install() {
		$this->load->language('extension/extension');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');

		if ($this->validate()) {
			$this->model_extension_extension->install($this->request->get['type'], $this->request->get['extension']);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $this->request->get['type'] . '/' . $this->request->get['extension']);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $this->request->get['type'] . '/' . $this->request->get['extension']);

			// Call install method if it exsits
			$this->load->controller($this->request->get['type'] . '/' . $this->request->get['extension'] . '/install');

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		}

		$this->getList();
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

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		}
	}
		
	public function getList() {
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
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_analytics'] = $this->language->get('text_analytics');
		$data['text_captcha'] = $this->language->get('text_captcha');
		$data['text_feed'] = $this->language->get('text_feed');
		$data['text_fraud'] = $this->language->get('text_fraud');
		$data['text_module'] = $this->language->get('text_module');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_shipping'] = $this->language->get('text_shipping');
		$data['text_theme'] = $this->language->get('text_theme');
		$data['text_menu'] = $this->language->get('text_menu');
		$data['text_total'] = $this->language->get('text_total');
				
		$data['column_name'] = $this->language->get('column_name');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_upload'] = $this->language->get('entry_upload');
		$data['entry_overwrite'] = $this->language->get('entry_overwrite');
		$data['entry_progress'] = $this->language->get('entry_progress');
	
		$data['help_upload'] = $this->language->get('help_upload');
		
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_install'] = $this->language->get('button_install');
		$data['button_uninstall'] = $this->language->get('button_uninstall');
		$data['button_upload'] = $this->language->get('button_upload');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_continue'] = $this->language->get('button_continue');

		$data['tab_available'] = $this->language->get('tab_available');
		$data['tab_downloaded'] = $this->language->get('tab_downloaded');
		$data['tab_installer'] = $this->language->get('tab_installer');

		$data['token'] = $this->session->data['token'];

		$directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} elseif ($directories) {
			$data['error_warning'] = $this->language->get('error_temporary');
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$this->load->model('setting/setting');
		$this->load->model('setting/store');
		$this->load->model('extension/module');

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

		$data['analytics_total'] = count($files);

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
				
		// Captcha
		$extensions = $this->model_extension_extension->getInstalled('captcha');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/captcha/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('captcha', $value);

				unset($extensions[$key]);
			}
		}

		$data['captchas'] = array();

		$files = glob(DIR_APPLICATION . 'controller/captcha/*.php');

		$data['captcha_total'] = count($files);

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
				
		// Feed
		$extensions = $this->model_extension_extension->getInstalled('feed');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/feed/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('feed', $value);

				unset($extensions[$key]);
			}
		}
		
		$data['feeds'] = array();

		$files = glob(DIR_APPLICATION . 'controller/feed/*.php');

		$data['feed_total'] = count($files);

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
				
		// Fraud
		$extensions = $this->model_extension_extension->getInstalled('fraud');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/fraud/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('fraud', $value);

				unset($extensions[$key]);
			}
		}

		$data['frauds'] = array();

		$files = glob(DIR_APPLICATION . 'controller/fraud/*.php');

		$data['fraud_total'] = count($files);

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
		
		// Module
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

		$data['module_total'] = count($files);

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
	
		// Payment
		$extensions = $this->model_extension_extension->getInstalled('payment');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/payment/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('payment', $value);

				unset($extensions[$key]);
			}
		}

		$data['payments'] = array();

		$files = glob(DIR_APPLICATION . 'controller/payment/*.php');

		$data['payment_total'] = count($files);

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
		
		// Shipping
		$extensions = $this->model_extension_extension->getInstalled('shipping');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/shipping/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('shipping', $value);

				unset($extensions[$key]);
			}
		}

		$data['shippings'] = array();

		$files = glob(DIR_APPLICATION . 'controller/shipping/*.php');

		$data['shipping_total'] = count($files);

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
		
		// Theme
		$extensions = $this->model_extension_extension->getInstalled('theme');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/theme/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('theme', $value);

				unset($extensions[$key]);
			}
		}

		$data['themes'] = array();

		$files = glob(DIR_APPLICATION . 'controller/theme/*.php');

		$data['theme_total'] = count($files);

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
	
		// Menu
		$extensions = $this->model_extension_extension->getInstalled('menu');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/menu/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('menu', $value);

				unset($extensions[$key]);
			}
		}

		$data['menus'] = array();

		$files = glob(DIR_APPLICATION . 'controller/menu/*.php');

		$data['menu_total'] = count($files);
		
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
						
		// Total
		$extensions = $this->model_extension_extension->getInstalled('total');

		foreach ($extensions as $key => $value) {
			if (!is_file(DIR_APPLICATION . 'controller/total/' . $value . '.php')) {
				$this->model_extension_extension->uninstall('total', $value);

				unset($extensions[$key]);
			}
		}

		$data['totals'] = array();

		$files = glob(DIR_APPLICATION . 'controller/total/*.php');

		$data['total_total'] = count($files);

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
	
	public function store() {
		$this->load->language('extension/extension');

		$json = array();
				
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
		
		$curl = curl_init('https://extension.opencart.com');
		
		$request  = 'api_key=' . $this->config->get('config_api_key'); 
		$request .= 'api_key=' . $this->config->get('config_api_key'); 
		
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
				
		$response = curl_exec($curl);

		curl_close($curl);

		if (!$response) {
			$json['error'] = curl_error($curl) . '(' . curl_errno($curl) . ')';
		}
		
		
		
		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}	
	
	public function upload() {
		$this->load->language('extension/extension');

		$json = array();

		// Check user has permission
		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name'])) {
				if (substr($this->request->files['file']['name'], -10) != '.ocmod.zip' && substr($this->request->files['file']['name'], -10) != '.ocmod.xml') {
					$json['error'] = $this->language->get('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			// If no temp directory exists create it
			$path = 'temp-' . token(32);

			if (!is_dir(DIR_UPLOAD . $path)) {
				mkdir(DIR_UPLOAD . $path, 0777);
			}

			// Set the steps required for installation
			$json['step'] = array();
			$json['overwrite'] = array();

			if (strrchr($this->request->files['file']['name'], '.') == '.xml') {
				$file = DIR_UPLOAD . $path . '/install.xml';

				// If xml file copy it to the temporary directory
				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				if (file_exists($file)) {
					$json['step'][] = array(
						'text' => $this->language->get('text_xml'),
						'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/xml', 'token=' . $this->session->data['token'], true)),
						'path' => $path
					);

					// Clear temporary files
					$json['step'][] = array(
						'text' => $this->language->get('text_remove'),
						'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/remove', 'token=' . $this->session->data['token'], true)),
						'path' => $path
					);
				} else {
					$json['error'] = $this->language->get('error_file');
				}
			}

			// If zip file copy it to the temp directory
			if (strrchr($this->request->files['file']['name'], '.') == '.zip') {
				$file = DIR_UPLOAD . $path . '/upload.zip';

				move_uploaded_file($this->request->files['file']['tmp_name'], $file);

				if (file_exists($file)) {
					$zip = zip_open($file);

					if ($zip) {
						// Zip
						$json['step'][] = array(
							'text' => $this->language->get('text_unzip'),
							'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/unzip', 'token=' . $this->session->data['token'], true)),
							'path' => $path
						);

						// FTP
						$json['step'][] = array(
							'text' => $this->language->get('text_ftp'),
							'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/ftp', 'token=' . $this->session->data['token'], true)),
							'path' => $path
						);

						// Send make and array of actions to carry out
						while ($entry = zip_read($zip)) {
							$zip_name = zip_entry_name($entry);

							// SQL
							if (substr($zip_name, 0, 11) == 'install.sql') {
								$json['step'][] = array(
									'text' => $this->language->get('text_sql'),
									'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/sql', 'token=' . $this->session->data['token'], true)),
									'path' => $path
								);
							}

							// XML
							if (substr($zip_name, 0, 11) == 'install.xml') {
								$json['step'][] = array(
									'text' => $this->language->get('text_xml'),
									'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/xml', 'token=' . $this->session->data['token'], true)),
									'path' => $path
								);
							}

							// PHP
							if (substr($zip_name, 0, 11) == 'install.php') {
								$json['step'][] = array(
									'text' => $this->language->get('text_php'),
									'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/php', 'token=' . $this->session->data['token'], true)),
									'path' => $path
								);
							}

							// Compare admin files
							$file = DIR_APPLICATION . substr($zip_name, 13);

							if (is_file($file) && substr($zip_name, 0, 13) == 'upload/admin/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare catalog files
							$file = DIR_CATALOG . substr($zip_name, 15);

							if (is_file($file) && substr($zip_name, 0, 15) == 'upload/catalog/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare image files
							$file = DIR_IMAGE . substr($zip_name, 13);

							if (is_file($file) && substr($zip_name, 0, 13) == 'upload/image/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}

							// Compare system files
							$file = DIR_SYSTEM . substr($zip_name, 14);

							if (is_file($file) && substr($zip_name, 0, 14) == 'upload/system/') {
								$json['overwrite'][] = substr($zip_name, 7);
							}
						}

						// Clear temporary files
						$json['step'][] = array(
							'text' => $this->language->get('text_remove'),
							'url'  => str_replace('&amp;', '&', $this->url->link('extension/extension/remove', 'token=' . $this->session->data['token'], true)),
							'path' => $path
						);

						zip_close($zip);
					} else {
						$json['error'] = $this->language->get('error_unzip');
					}
				} else {
					$json['error'] = $this->language->get('error_file');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function unzip() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Sanitize the filename
		$file = DIR_UPLOAD . $this->request->post['path'] . '/upload.zip';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			// Unzip the files
			$zip = new ZipArchive();

			if ($zip->open($file)) {
				$zip->extractTo(DIR_UPLOAD . $this->request->post['path']);
				$zip->close();
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}

			// Remove Zip
			unlink($file);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function ftp() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Check FTP status
		if (!$this->config->get('config_ftp_status')) {
			$json['error'] = $this->language->get('error_ftp_status');
		}

		$directory = DIR_UPLOAD . $this->request->post['path'] . '/upload/';

		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory . '*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					$files[] = $file;
				}
			}

			// Connect to the site via FTP
			$connection = ftp_connect($this->config->get('config_ftp_hostname'), $this->config->get('config_ftp_port'));

			if ($connection) {
				$login = ftp_login($connection, $this->config->get('config_ftp_username'), $this->config->get('config_ftp_password'));

				if ($login) {
					if ($this->config->get('config_ftp_root')) {
						$root = ftp_chdir($connection, $this->config->get('config_ftp_root'));
					} else {
						$root = ftp_chdir($connection, '/');
					}

					if ($root) {
						foreach ($files as $file) {
							$destination = substr($file, strlen($directory));

							// Upload everything in the upload directory
							// Many people rename their admin folder for security purposes which I believe should be an option during installation just like setting the db prefix.
							// the following code would allow you to change the name of the following directories and any extensions installed will still go to the right directory.
							if (substr($destination, 0, 5) == 'admin') {
								$destination = basename(DIR_APPLICATION) . substr($destination, 5);
							}

							if (substr($destination, 0, 7) == 'catalog') {
								$destination = basename(DIR_CATALOG) . substr($destination, 7);
							}

							if (substr($destination, 0, 5) == 'image') {
								$destination = basename(DIR_IMAGE) . substr($destination, 5);
							}

							if (substr($destination, 0, 6) == 'system') {
								$destination = basename(DIR_SYSTEM) . substr($destination, 6);
							}

							if (is_dir($file)) {
								$lists = ftp_nlist($connection, substr($destination, 0, strrpos($destination, '/')));

								// Basename all the directories because on some servers they don't return the fulll paths.
								$list_data = array();

								foreach ($lists as $list) {
									$list_data[] = basename($list);
								}

								if (!in_array(basename($destination), $list_data)) {
									if (!ftp_mkdir($connection, $destination)) {
										$json['error'] = sprintf($this->language->get('error_ftp_directory'), $destination);
									}
								}
							}

							if (is_file($file)) {
								if (!ftp_put($connection, $destination, $file, FTP_BINARY)) {
									$json['error'] = sprintf($this->language->get('error_ftp_file'), $file);
								}
							}
						}
					} else {
						$json['error'] = sprintf($this->language->get('error_ftp_root'), $root);
					}
				} else {
					$json['error'] = sprintf($this->language->get('error_ftp_login'), $this->config->get('config_ftp_username'));
				}

				ftp_close($connection);
			} else {
				$json['error'] = sprintf($this->language->get('error_ftp_connection'), $this->config->get('config_ftp_hostname'), $this->config->get('config_ftp_port'));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function sql() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.sql';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$lines = file($file);

			if ($lines) {
				try {
					$sql = '';

					foreach ($lines as $line) {
						if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
							$sql .= $line;

							if (preg_match('/;\s*$/', $line)) {
								$sql = str_replace(" `oc_", " `" . DB_PREFIX, $sql);

								$this->db->query($sql);

								$sql = '';
							}
						}
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function xml() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.xml';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			$this->load->model('extension/modification');

			// If xml file just put it straight into the DB
			$xml = file_get_contents($file);

			if ($xml) {
				try {
					$dom = new DOMDocument('1.0', 'UTF-8');
					$dom->loadXml($xml);

					$name = $dom->getElementsByTagName('name')->item(0);

					if ($name) {
						$name = $name->nodeValue;
					} else {
						$name = '';
					}

					$code = $dom->getElementsByTagName('code')->item(0);

					if ($code) {
						$code = $code->nodeValue;

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_extension_modification->getModificationByCode($code);

						if ($modification_info) {
							$json['error'] = sprintf($this->language->get('error_exists'), $modification_info['name']);
						}
					} else {
						$json['error'] = $this->language->get('error_code');
					}

					$author = $dom->getElementsByTagName('author')->item(0);

					if ($author) {
						$author = $author->nodeValue;
					} else {
						$author = '';
					}

					$version = $dom->getElementsByTagName('version')->item(0);

					if ($version) {
						$version = $version->nodeValue;
					} else {
						$version = '';
					}

					$link = $dom->getElementsByTagName('link')->item(0);

					if ($link) {
						$link = $link->nodeValue;
					} else {
						$link = '';
					}

					$modification_data = array(
						'name'    => $name,
						'code'    => $code,
						'author'  => $author,
						'version' => $version,
						'link'    => $link,
						'xml'     => $xml,
						'status'  => 1
					);

					if (!$json) {
						$this->model_extension_modification->addModification($modification_data);
					}
				} catch(Exception $exception) {
					$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function php() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$file = DIR_UPLOAD . $this->request->post['path'] . '/install.php';

		if (!is_file($file) || substr(str_replace('\\', '/', realpath($file)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_file');
		}

		if (!$json) {
			try {
				include($file);
			} catch(Exception $exception) {
				$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function remove() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$directory = DIR_UPLOAD . $this->request->post['path'];
		
		if (!is_dir($directory) || substr(str_replace('\\', '/', realpath($directory)), 0, strlen(DIR_UPLOAD)) != DIR_UPLOAD) {
			$json['error'] = $this->language->get('error_directory');
		}

		if (!$json) {
			// Get a list of files ready to upload
			$files = array();

			$path = array($directory);

			while (count($path) != 0) {
				$next = array_shift($path);

				// We have to use scandir function because glob will not pick up dot files.
				foreach (array_diff(scandir($next), array('.', '..')) as $file) {
					$file = $next . '/' . $file;

					if (is_dir($file)) {
						$path[] = $file;
					}

					$files[] = $file;
				}
			}

			rsort($files);

			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);

				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}

			if (file_exists($directory)) {
				rmdir($directory);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function clear() {
		$this->load->language('extension/extension');

		$json = array();

		if (!$this->user->hasPermission('modify', 'extension/extension')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$directories = glob(DIR_UPLOAD . 'temp-*', GLOB_ONLYDIR);

			foreach ($directories as $directory) {
				// Get a list of files ready to upload
				$files = array();

				$path = array($directory);

				while (count($path) != 0) {
					$next = array_shift($path);

					// We have to use scandir function because glob will not pick up dot files.
					foreach (array_diff(scandir($next), array('.', '..')) as $file) {
						$file = $next . '/' . $file;

						if (is_dir($file)) {
							$path[] = $file;
						}

						$files[] = $file;
					}
				}

				rsort($files);

				foreach ($files as $file) {
					if (is_file($file)) {
						unlink($file);
					} elseif (is_dir($file)) {
						rmdir($file);
					}
				}

				if (file_exists($directory)) {
					rmdir($directory);
				}
			}

			$json['success'] = $this->language->get('text_clear');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
