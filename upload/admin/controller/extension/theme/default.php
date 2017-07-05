<?php
class ControllerExtensionThemeDefault extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/theme/default');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('theme_default', $this->request->post, $this->request->get['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['product_limit'])) {
			$data['error_product_limit'] = $this->error['product_limit'];
		} else {
			$data['error_product_limit'] = '';
		}

		if (isset($this->error['product_description_length'])) {
			$data['error_product_description_length'] = $this->error['product_description_length'];
		} else {
			$data['error_product_description_length'] = '';
		}

		if (isset($this->error['image_category'])) {
			$data['error_image_category'] = $this->error['image_category'];
		} else {
			$data['error_image_category'] = '';
		}

		if (isset($this->error['image_thumb'])) {
			$data['error_image_thumb'] = $this->error['image_thumb'];
		} else {
			$data['error_image_thumb'] = '';
		}

		if (isset($this->error['image_popup'])) {
			$data['error_image_popup'] = $this->error['image_popup'];
		} else {
			$data['error_image_popup'] = '';
		}

		if (isset($this->error['image_product'])) {
			$data['error_image_product'] = $this->error['image_product'];
		} else {
			$data['error_image_product'] = '';
		}

		if (isset($this->error['image_additional'])) {
			$data['error_image_additional'] = $this->error['image_additional'];
		} else {
			$data['error_image_additional'] = '';
		}

		if (isset($this->error['image_related'])) {
			$data['error_image_related'] = $this->error['image_related'];
		} else {
			$data['error_image_related'] = '';
		}

		if (isset($this->error['image_compare'])) {
			$data['error_image_compare'] = $this->error['image_compare'];
		} else {
			$data['error_image_compare'] = '';
		}

		if (isset($this->error['image_wishlist'])) {
			$data['error_image_wishlist'] = $this->error['image_wishlist'];
		} else {
			$data['error_image_wishlist'] = '';
		}

		if (isset($this->error['image_cart'])) {
			$data['error_image_cart'] = $this->error['image_cart'];
		} else {
			$data['error_image_cart'] = '';
		}

		if (isset($this->error['image_location'])) {
			$data['error_image_location'] = $this->error['image_location'];
		} else {
			$data['error_image_location'] = '';
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/theme/default', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true)
		);

		$data['action'] = $this->url->link('extension/theme/default', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true);

		if (isset($this->request->get['store_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$setting_info = $this->model_setting_setting->getSetting('theme_default', $this->request->get['store_id']);
		}
		
		if (isset($this->request->post['theme_default_directory'])) {
			$data['theme_default_directory'] = $this->request->post['theme_default_directory'];
		} elseif (isset($setting_info['theme_default_directory'])) {
			$data['theme_default_directory'] = $setting_info['theme_default_directory'];
		} else {
			$data['theme_default_directory'] = 'default';
		}		

		$data['directories'] = array();

		$directories = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);

		foreach ($directories as $directory) {
			$data['directories'][] = basename($directory);
		}

		if (isset($this->request->post['theme_default_product_limit'])) {
			$data['theme_default_product_limit'] = $this->request->post['theme_default_product_limit'];
		} elseif (isset($setting_info['theme_default_product_limit'])) {
			$data['theme_default_product_limit'] = $setting_info['theme_default_product_limit'];
		} else {
			$data['theme_default_product_limit'] = 15;
		}		
		
		if (isset($this->request->post['theme_default_status'])) {
			$data['theme_default_status'] = $this->request->post['theme_default_status'];
		} elseif (isset($setting_info['theme_default_status'])) {
			$data['theme_default_status'] = $setting_info['theme_default_status'];
		} else {
			$data['theme_default_status'] = '';
		}
		
		if (isset($this->request->post['theme_default_product_description_length'])) {
			$data['theme_default_product_description_length'] = $this->request->post['theme_default_product_description_length'];
		} elseif (isset($setting_info['theme_default_product_description_length'])) {
			$data['theme_default_product_description_length'] = $setting_info['theme_default_product_description_length'];
		} else {
			$data['theme_default_product_description_length'] = 100;
		}
		
		if (isset($this->request->post['theme_default_image_category_width'])) {
			$data['theme_default_image_category_width'] = $this->request->post['theme_default_image_category_width'];
		} elseif (isset($setting_info['theme_default_image_category_width'])) {
			$data['theme_default_image_category_width'] = $setting_info['theme_default_image_category_width'];
		} else {
			$data['theme_default_image_category_width'] = 80;		
		}
		
		if (isset($this->request->post['theme_default_image_category_height'])) {
			$data['theme_default_image_category_height'] = $this->request->post['theme_default_image_category_height'];
		} elseif (isset($setting_info['theme_default_image_category_height'])) {
			$data['theme_default_image_category_height'] = $setting_info['theme_default_image_category_height'];
		} else {
			$data['theme_default_image_category_height'] = 80;
		}
		
		if (isset($this->request->post['theme_default_image_thumb_width'])) {
			$data['theme_default_image_thumb_width'] = $this->request->post['theme_default_image_thumb_width'];
		} elseif (isset($setting_info['theme_default_image_thumb_width'])) {
			$data['theme_default_image_thumb_width'] = $setting_info['theme_default_image_thumb_width'];
		} else {
			$data['theme_default_image_thumb_width'] = 228;
		}
		
		if (isset($this->request->post['theme_default_image_thumb_height'])) {
			$data['theme_default_image_thumb_height'] = $this->request->post['theme_default_image_thumb_height'];
		} elseif (isset($setting_info['theme_default_image_thumb_height'])) {
			$data['theme_default_image_thumb_height'] = $setting_info['theme_default_image_thumb_height'];
		} else {
			$data['theme_default_image_thumb_height'] = 228;		
		}
		
		if (isset($this->request->post['theme_default_image_popup_width'])) {
			$data['theme_default_image_popup_width'] = $this->request->post['theme_default_image_popup_width'];
		} elseif (isset($setting_info['theme_default_image_popup_width'])) {
			$data['theme_default_image_popup_width'] = $setting_info['theme_default_image_popup_width'];
		} else {
			$data['theme_default_image_popup_width'] = 500;
		}
		
		if (isset($this->request->post['theme_default_image_popup_height'])) {
			$data['theme_default_image_popup_height'] = $this->request->post['theme_default_image_popup_height'];
		} elseif (isset($setting_info['theme_default_image_popup_height'])) {
			$data['theme_default_image_popup_height'] = $setting_info['theme_default_image_popup_height'];
		} else {
			$data['theme_default_image_popup_height'] = 500;
		}
		
		if (isset($this->request->post['theme_default_image_product_width'])) {
			$data['theme_default_image_product_width'] = $this->request->post['theme_default_image_product_width'];
		} elseif (isset($setting_info['theme_default_image_product_width'])) {
			$data['theme_default_image_product_width'] = $setting_info['theme_default_image_product_width'];
		} else {
			$data['theme_default_image_product_width'] = 228;
		}
		
		if (isset($this->request->post['theme_default_image_product_height'])) {
			$data['theme_default_image_product_height'] = $this->request->post['theme_default_image_product_height'];
		} elseif (isset($setting_info['theme_default_image_product_height'])) {
			$data['theme_default_image_product_height'] = $setting_info['theme_default_image_product_height'];
		} else {
			$data['theme_default_image_product_height'] = 228;
		}
		
		if (isset($this->request->post['theme_default_image_additional_width'])) {
			$data['theme_default_image_additional_width'] = $this->request->post['theme_default_image_additional_width'];
		} elseif (isset($setting_info['theme_default_image_additional_width'])) {
			$data['theme_default_image_additional_width'] = $setting_info['theme_default_image_additional_width'];
		} else {
			$data['theme_default_image_additional_width'] = 74;
		}
		
		if (isset($this->request->post['theme_default_image_additional_height'])) {
			$data['theme_default_image_additional_height'] = $this->request->post['theme_default_image_additional_height'];
		} elseif (isset($setting_info['theme_default_image_additional_height'])) {
			$data['theme_default_image_additional_height'] = $setting_info['theme_default_image_additional_height'];
		} else {
			$data['theme_default_image_additional_height'] = 74;
		}
		
		if (isset($this->request->post['theme_default_image_related_width'])) {
			$data['theme_default_image_related_width'] = $this->request->post['theme_default_image_related_width'];
		} elseif (isset($setting_info['theme_default_image_related_width'])) {
			$data['theme_default_image_related_width'] = $setting_info['theme_default_image_related_width'];
		} else {
			$data['theme_default_image_related_width'] = 80;
		}
		
		if (isset($this->request->post['theme_default_image_related_height'])) {
			$data['theme_default_image_related_height'] = $this->request->post['theme_default_image_related_height'];
		} elseif (isset($setting_info['theme_default_image_related_height'])) {
			$data['theme_default_image_related_height'] = $setting_info['theme_default_image_related_height'];
		} else {
			$data['theme_default_image_related_height'] = 80;
		}
		
		if (isset($this->request->post['theme_default_image_compare_width'])) {
			$data['theme_default_image_compare_width'] = $this->request->post['theme_default_image_compare_width'];
		} elseif (isset($setting_info['theme_default_image_compare_width'])) {
			$data['theme_default_image_compare_width'] = $setting_info['theme_default_image_compare_width'];
		} else {
			$data['theme_default_image_compare_width'] = 90;
		}
		
		if (isset($this->request->post['theme_default_image_compare_height'])) {
			$data['theme_default_image_compare_height'] = $this->request->post['theme_default_image_compare_height'];
		} elseif (isset($setting_info['theme_default_image_compare_height'])) {
			$data['theme_default_image_compare_height'] = $setting_info['theme_default_image_compare_height'];
		} else {
			$data['theme_default_image_compare_height'] = 90;
		}
		
		if (isset($this->request->post['theme_default_image_wishlist_width'])) {
			$data['theme_default_image_wishlist_width'] = $this->request->post['theme_default_image_wishlist_width'];
		} elseif (isset($setting_info['theme_default_image_wishlist_width'])) {
			$data['theme_default_image_wishlist_width'] = $setting_info['theme_default_image_wishlist_width'];
		} else {
			$data['theme_default_image_wishlist_width'] = 47;
		}
		
		if (isset($this->request->post['theme_default_image_wishlist_height'])) {
			$data['theme_default_image_wishlist_height'] = $this->request->post['theme_default_image_wishlist_height'];
		} elseif (isset($setting_info['theme_default_image_wishlist_height'])) {
			$data['theme_default_image_wishlist_height'] = $setting_info['theme_default_image_wishlist_height'];
		} else {
			$data['theme_default_image_wishlist_height'] = 47;
		}
		
		if (isset($this->request->post['theme_default_image_cart_width'])) {
			$data['theme_default_image_cart_width'] = $this->request->post['theme_default_image_cart_width'];
		} elseif (isset($setting_info['theme_default_image_cart_width'])) {
			$data['theme_default_image_cart_width'] = $setting_info['theme_default_image_cart_width'];
		} else {
			$data['theme_default_image_cart_width'] = 47;
		}
		
		if (isset($this->request->post['theme_default_image_cart_height'])) {
			$data['theme_default_image_cart_height'] = $this->request->post['theme_default_image_cart_height'];
		} elseif (isset($setting_info['theme_default_image_cart_height'])) {
			$data['theme_default_image_cart_height'] = $setting_info['theme_default_image_cart_height'];
		} else {
			$data['theme_default_image_cart_height'] = 47;
		}
		
		if (isset($this->request->post['theme_default_image_location_width'])) {
			$data['theme_default_image_location_width'] = $this->request->post['theme_default_image_location_width'];
		} elseif (isset($setting_info['theme_default_image_location_width'])) {
			$data['theme_default_image_location_width'] = $setting_info['theme_default_image_location_width'];
		} else {
			$data['theme_default_image_location_width'] = 268;
		}
		
		if (isset($this->request->post['theme_default_image_location_height'])) {
			$data['theme_default_image_location_height'] = $this->request->post['theme_default_image_location_height'];
		} elseif (isset($setting_info['theme_default_image_location_height'])) {
			$data['theme_default_image_location_height'] = $setting_info['theme_default_image_location_height'];
		} else {
			$data['theme_default_image_location_height'] = 50;
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/theme/default', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/theme/default')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['theme_default_product_limit']) {
			$this->error['product_limit'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['theme_default_product_description_length']) {
			$this->error['product_description_length'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['theme_default_image_category_width'] || !$this->request->post['theme_default_image_category_height']) {
			$this->error['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['theme_default_image_thumb_width'] || !$this->request->post['theme_default_image_thumb_height']) {
			$this->error['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['theme_default_image_popup_width'] || !$this->request->post['theme_default_image_popup_height']) {
			$this->error['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['theme_default_image_product_width'] || !$this->request->post['theme_default_image_product_height']) {
			$this->error['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['theme_default_image_additional_width'] || !$this->request->post['theme_default_image_additional_height']) {
			$this->error['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['theme_default_image_related_width'] || !$this->request->post['theme_default_image_related_height']) {
			$this->error['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['theme_default_image_compare_width'] || !$this->request->post['theme_default_image_compare_height']) {
			$this->error['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['theme_default_image_wishlist_width'] || !$this->request->post['theme_default_image_wishlist_height']) {
			$this->error['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['theme_default_image_cart_width'] || !$this->request->post['theme_default_image_cart_height']) {
			$this->error['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['theme_default_image_location_width'] || !$this->request->post['theme_default_image_location_height']) {
			$this->error['image_location'] = $this->language->get('error_image_location');
		}

		return !$this->error;
	}
}
