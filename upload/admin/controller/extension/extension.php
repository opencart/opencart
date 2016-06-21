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
		$data['text_type'] = $this->language->get('text_type');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['type'])) {
			$data['type'] = $this->request->get['type'];
		} else {
			$data['type'] = '';
		}

		$data['categories'] = array();
		
		if ($this->user->hasPermission('access', 'extension/analytics')) {
			$files = glob(DIR_APPLICATION . 'controller/{extension/analytics,analytics}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'analytics',
				'text' => $this->language->get('text_analytics') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/captcha')) {
			$files = glob(DIR_APPLICATION . 'controller/{extension/captcha,captcha}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'captcha',
				'text' => $this->language->get('text_captcha') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/dashboard')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/dashboard/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'dashboard',
				'text' => $this->language->get('text_dashboard') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/dashboard', 'token=' . $this->session->data['token'], true)
			);
		}
			
		if ($this->user->hasPermission('access', 'extension/feed')) {
			$files = glob(DIR_APPLICATION . 'controller/{extension/feed,feed}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'feed',
				'text' => $this->language->get('text_feed') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/feed', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/fraud')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/fraud,fraud}/*.php', GLOB_BRACE);
			
			$data['categories'][] = array(
				'code' => 'fraud',
				'text' => $this->language->get('text_fraud') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/module')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/module,module}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'module',
				'text' => $this->language->get('text_module') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/payment')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/payment,payment}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'payment',
				'text' => $this->language->get('text_payment') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/shipping')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/shipping,shipping}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'shipping',
				'text' => $this->language->get('text_shipping') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/theme')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/theme,theme}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'theme',
				'text' => $this->language->get('text_theme') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/theme', 'token=' . $this->session->data['token'], true)
			);
		}
		
		if ($this->user->hasPermission('access', 'extension/total')) {
			$files = glob(DIR_APPLICATION . 'controller/extension/{extension/total,total}/*.php', GLOB_BRACE);
	
			$data['categories'][] = array(
				'code' => 'total',
				'text' => $this->language->get('text_total') . ' (' . count($files) .')',
				'href' => $this->url->link('extension/total', 'token=' . $this->session->data['token'], true)
			);
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/extension', $data));
	}
}