<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');

		$data['analytics'] = array();

		$analytics = $this->model_setting_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get('analytics_' . $analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('extension/analytics/' . $analytic['code'], $this->config->get('analytics_' . $analytic['code'] . '_status'));
			}
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($this->config->get('config_url') . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $this->config->get('config_url');
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');
                
                $data['gdpr'] = $this->config->get('module_gdpr_status');
                $data['module_gdpr_policy_url'] = $this->config->get('module_gdpr_policy_url');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		// Wishlist
		if ($this->customer->isLogged()) {
			$this->load->model('account/wishlist');

			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), $this->model_account_wishlist->getTotalWishlist());
		} else {
			$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', 'language=' . $this->config->get('config_language')), $this->customer->getFirstName(), $this->url->link('account/logout', 'language=' . $this->config->get('config_language')));

		$data['home'] = $this->url->link('common/home', 'language=' . $this->config->get('config_language'));
		$data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language'));
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));
		$data['register'] = $this->url->link('account/register', 'language=' . $this->config->get('config_language'));
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));
		$data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language'));
		$data['transaction'] = $this->url->link('account/transaction', 'language=' . $this->config->get('config_language'));
		$data['download'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));
		$data['logout'] = $this->url->link('account/logout', 'language=' . $this->config->get('config_language'));
		$data['shopping_cart'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		$data['checkout'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
		$data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
		$data['telephone'] = $this->config->get('config_telephone');

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		$data['menu'] = $this->load->controller('common/menu');

		return $this->load->view('common/header', $data);
	}
}
