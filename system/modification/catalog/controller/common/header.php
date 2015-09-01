<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		if ($this->config->get('config_google_analytics_status')) {
			$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['google_analytics'] = '';
		}

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}


              if (is_file(DIR_IMAGE . $this->config->get('config_backgroundimage'))) {
			$data['backgroundimage'] = $server . 'image/' . $this->config->get('config_backgroundimage');
		} else {
			$data['backgroundimage'] = '';
		}

        $data['repeatbackground'] = $this->config->get('config_repeatbackground');
        $data['backgroundcolor'] = $this->config->get('config_backgroundcolor');

        $data['text_color'] = $this->config->get('config_text_color');
        $data['link_color'] = $this->config->get('config_link_color');
        $data['link_color_hover'] = $this->config->get('config_link_color_hover');
        $data['title_color'] = $this->config->get('config_title_color');

        $data['top_bar_background'] = $this->config->get('config_top_bar_background');
        $data['top_bar_link_color'] = $this->config->get('config_top_bar_link_color');
        $data['top_bar_link_color_hover'] = $this->config->get('config_top_bar_link_color_hover');
        $data['top_bar_link_icon'] = $this->config->get('config_top_bar_link_icon');

        $data['searchbackground'] = $this->config->get('config_searchbackground');
        $data['searchcolor'] = $this->config->get('config_searchcolor');

        $data['button_searchbackground'] = $this->config->get('config_button_searchbackground');
        $data['button_searchcolor'] = $this->config->get('config_button_searchcolor');

        $data['cartbackground'] = $this->config->get('config_cartbackground');
        $data['cartbackgroundopen'] = $this->config->get('config_cartbackgroundopen');

        $data['cartlink'] = $this->config->get('config_cartlink');
        $data['cartlinkopen'] = $this->config->get('config_cartlinkopen');

        $data['menubackground'] = $this->config->get('config_menubackground');
        $data['menulink'] = $this->config->get('config_menulink');

        $data['menulinkhover'] = $this->config->get('config_menulinkhover');
        $data['menulinkhoverbg'] = $this->config->get('config_menulinkhoverbg');

        $data['menubackgrounddropdown'] = $this->config->get('config_menubackgrounddropdown');
        $data['menulinkdropdown'] = $this->config->get('config_menulinkdropdown');

        $data['menulinkdropdownhover'] = $this->config->get('config_menulinkdropdownhover');
        $data['menulinkdropdownhoverbg'] = $this->config->get('config_menulinkdropdownhoverbg');

        $data['footerbg'] = $this->config->get('config_footerbg');
        $data['footerlink'] = $this->config->get('config_footerlink');
        $data['footerlinkhover'] = $this->config->get('config_footerlinkhover');
        $data['footertitle'] = $this->config->get('config_footertitle');

        $data['productsbg'] = $this->config->get('config_productsbg');
        $data['productsbghover'] = $this->config->get('config_productsbghover');
        $data['productstitlehover'] = $this->config->get('config_productstitlehover');
        $data['productstitle'] = $this->config->get('config_productstitle');

        $data['buttoncartbg'] = $this->config->get('config_buttoncartbg');
        $data['buttoncartbghover'] = $this->config->get('config_buttoncartbghover');
        $data['buttoncarttext'] = $this->config->get('config_buttoncarttext');
        $data['buttoncarttexthover'] = $this->config->get('config_buttoncarttexthover');
        $data['buttoncartlikebg'] = $this->config->get('config_buttoncartlikebg');
        $data['buttoncartlikebghover'] = $this->config->get('config_buttoncartlikebghover');
        $data['buttoncartlike'] = $this->config->get('config_buttoncartlike');
        $data['buttoncartlikehover'] = $this->config->get('config_buttoncartlikehover');
        $data['buttondefault'] = $this->config->get('config_buttondefault');
        $data['buttondefaulthover'] = $this->config->get('config_buttondefaulthover');
        $data['popup_image'] = $this->config->get('config_popup_image');
            
		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {

					$children_lv3_data = array();

					$children_lv3 = $this->model_catalog_category->getCategories($child['category_id']);

					foreach ($children_lv3 as $child_lv3) {
						$filter_data_lv3 = array(
							'filter_category_id'  => $child_lv3['category_id'],
							'filter_sub_category' => true
						);

						$children_lv3_data[] = array(
							'name'  => $child_lv3['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data_lv3) . ')' : ''),
							'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'] . '_' . $child_lv3['category_id'])
						);
					}
            
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),

						'children_lv3' => $children_lv3_data,
						'column'   => $child['column'] ? $child['column'] : 1,
            
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
	}
}