<?php
class ControllerOpenbayPlay extends Controller {
	public function install() {
		$this->load->language('play/install');
		$this->load->model('openbay/play');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_play->install();

		$this->model_setting_extension->install('openbay', $this->request->get['extension']);
	}

	public function uninstall() {
		$this->load->language('play/install');
		$this->load->model('openbay/play');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_play->uninstall();

		$this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

	public function index() {
		$this->data = array_merge($this->data, $this->load->language('openbay/play_main'));

		$this->document->setTitle('OpenBay Pro for Play.com');
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text' => 'OpenBay Pro',
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'],
			'text' => $this->data['lang_heading'],
			'separator' => ' :: '
		);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['validation'] = $this->openbay->play->validate();
		$this->data['links_settings'] = HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'];
		$this->data['links_pricing'] = HTTPS_SERVER . 'index.php?route=openbay/play/pricingReport&token=' . $this->session->data['token'];
		$this->data['image']['icon1'] = HTTPS_SERVER . 'view/image/openbay/openbay_icon1.png';
		$this->data['image']['icon13'] = HTTPS_SERVER . 'view/image/openbay/openbay_icon13.png';

		$this->template = 'openbay/play_main.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function settings() {
		$this->data = array_merge($this->data, $this->load->language('openbay/play_settings'));

		$this->load->model('setting/setting');
		$this->load->model('openbay/play');
		$this->load->model('localisation/currency');
		$this->load->model('sale/customer_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('play', $this->request->post);

			$this->session->data['success'] = $this->language->get('lang_text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token']);
		}

		$this->document->setTitle($this->language->get('lang_heading_title'));
		$this->document->addScript('view/javascript/openbay/faq.js');
		$this->document->addStyle('view/stylesheet/openbay.css');

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_obp'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_obp_play'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'],
			'text' => $this->language->get('text_settings'),
			'separator' => ' :: '
		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=openbay/play/settings&token=' . $this->session->data['token'];
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=openbay/play&token=' . $this->session->data['token'];
		$this->data['token'] = $this->session->data['token'];

		/*
		 * Error warnings
		 */
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		/*
		 *  Currency Import
		 */
		if (isset($this->request->post['obp_play_def_currency'])) {
			$this->data['obp_play_def_currency'] = $this->request->post['obp_play_def_currency'];
		} else {
			$this->data['obp_play_def_currency'] = $this->config->get('obp_play_def_currency');
		}
		$this->data['currency_list'] = $this->model_localisation_currency->getCurrencies();

		/*
		 *  Customer Import
		 */
		if (isset($this->request->post['obp_play_def_customer_grp'])) {
			$this->data['obp_play_def_customer_grp'] = $this->request->post['obp_play_def_customer_grp'];
		} else {
			$this->data['obp_play_def_customer_grp'] = $this->config->get('obp_play_def_customer_grp');
		}
		$this->data['customer_grp_list'] = $this->model_sale_customer_group->getCustomerGroups();

		/*
		 * Extension status
		 */
		if (isset($this->request->post['play_status'])) {
			$this->data['play_status'] = $this->request->post['play_status'];
		} else {
			$this->data['play_status'] = $this->config->get('play_status');
		}

		if (isset($this->request->post['obp_play_token'])) {
			$this->data['obp_play_token'] = $this->request->post['obp_play_token'];
		} else {
			$this->data['obp_play_token'] = $this->config->get('obp_play_token');
		}

		if (isset($this->request->post['obp_play_secret'])) {
			$this->data['obp_play_secret'] = $this->request->post['obp_play_secret'];
		} else {
			$this->data['obp_play_secret'] = $this->config->get('obp_play_secret');
		}

		if (isset($this->request->post['obp_play_key'])) {
			$this->data['obp_play_key'] = $this->request->post['obp_play_key'];
		} else {
			$this->data['obp_play_key'] = $this->config->get('obp_play_key');
		}

		if (isset($this->request->post['obp_play_key2'])) {
			$this->data['obp_play_key2'] = $this->request->post['obp_play_key2'];
		} else {
			$this->data['obp_play_key2'] = $this->config->get('obp_play_key2');
		}

		if (isset($this->request->post['obp_play_logging'])) {
			$this->data['obp_play_logging'] = $this->request->post['obp_play_logging'];
		} else {
			$this->data['obp_play_logging'] = $this->config->get('obp_play_logging');
		}

		if (isset($this->request->post['obp_play_import_id'])) {
			$this->data['obp_play_import_id'] = $this->request->post['obp_play_import_id'];
		} else {
			$this->data['obp_play_import_id'] = $this->config->get('obp_play_import_id');
		}
		if (isset($this->request->post['obp_play_paid_id'])) {
			$this->data['obp_play_paid_id'] = $this->request->post['obp_play_paid_id'];
		} else {
			$this->data['obp_play_paid_id'] = $this->config->get('obp_play_paid_id');
		}
		if (isset($this->request->post['obp_play_shipped_id'])) {
			$this->data['obp_play_shipped_id'] = $this->request->post['obp_play_shipped_id'];
		} else {
			$this->data['obp_play_shipped_id'] = $this->config->get('obp_play_shipped_id');
		}
		if (isset($this->request->post['obp_play_cancelled_id'])) {
			$this->data['obp_play_cancelled_id'] = $this->request->post['obp_play_cancelled_id'];
		} else {
			$this->data['obp_play_cancelled_id'] = $this->config->get('obp_play_cancelled_id');
		}
		if (isset($this->request->post['obp_play_refunded_id'])) {
			$this->data['obp_play_refunded_id'] = $this->request->post['obp_play_refunded_id'];
		} else {
			$this->data['obp_play_refunded_id'] = $this->config->get('obp_play_refunded_id');
		}
		if (isset($this->request->post['obp_play_def_shipto'])) {
			$this->data['obp_play_def_shipto'] = $this->request->post['obp_play_def_shipto'];
		} else {
			$this->data['obp_play_def_shipto'] = $this->config->get('obp_play_def_shipto');
		}
		if (isset($this->request->post['obp_play_def_shipfrom'])) {
			$this->data['obp_play_def_shipfrom'] = $this->request->post['obp_play_def_shipfrom'];
		} else {
			$this->data['obp_play_def_shipfrom'] = $this->config->get('obp_play_def_shipfrom');
		}
		if (isset($this->request->post['obp_play_def_itemcond'])) {
			$this->data['obp_play_def_itemcond'] = $this->request->post['obp_play_def_itemcond'];
		} else {
			$this->data['obp_play_def_itemcond'] = $this->config->get('obp_play_def_itemcond');
		}
		if (isset($this->request->post['obp_play_order_update_notify'])) {
			$this->data['obp_play_order_update_notify'] = $this->request->post['obp_play_order_update_notify'];
		} else {
			$this->data['obp_play_order_update_notify'] = $this->config->get('obp_play_order_update_notify');
		}
		if (isset($this->request->post['obp_play_order_new_notify'])) {
			$this->data['obp_play_order_new_notify'] = $this->request->post['obp_play_order_new_notify'];
		} else {
			$this->data['obp_play_order_new_notify'] = $this->config->get('obp_play_order_new_notify');
		}
		if (isset($this->request->post['obp_play_order_new_notify_admin'])) {
			$this->data['obp_play_order_new_notify_admin'] = $this->request->post['obp_play_order_new_notify_admin'];
		} else {
			$this->data['obp_play_order_new_notify_admin'] = $this->config->get('obp_play_order_new_notify_admin');
		}
		if (isset($this->request->post['obp_play_default_tax'])) {
			$this->data['obp_play_default_tax'] = $this->request->post['obp_play_default_tax'];
		} else {
			$this->data['obp_play_default_tax'] = $this->config->get('obp_play_default_tax');
		}

		$this->data['dispatch_to']      = $this->openbay->play->getDispatchTo();
		$this->data['dispatch_from']    = $this->openbay->play->getDispatchFrom();
		$this->data['item_conditions']  = $this->openbay->play->getItemCondition();

		$this->template = 'openbay/play_settings.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function pricingReport() {
	if ($this->checkConfig() == true) {
		//load the language
		$this->data = array_merge($this->data, $this->load->language('openbay/play_reportprice'));

		//load the models
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('catalog/manufacturer');
		$this->load->model('openbay/play');
		$this->load->model('openbay/play_product');

		//set the title and page info
		$this->document->setTitle($this->data['lang_page_title']);
		$this->document->addScript('view/javascript/openbay/faq.js');
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->template         = 'openbay/play_report_price.tpl';
		$this->children         = array('common/header','common/footer');
		$this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
		$this->data['token']    = $this->session->data['token'];
		$this->data['pricing']  = $this->model_openbay_play_product->getPricingReport();

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['product_id_types']     = $this->openbay->play->getProductIdType();

		$this->data['product_conditions']   = $this->openbay->play->getItemCondition();

		$this->data['product_dispatch_to']  = $this->openbay->play->getDispatchTo();

		if(isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		}else{
			$page = 1;
		}

		$pagination = new Pagination();
		$pagination->total = $this->data['pricing']['total'];
		$pagination->page = $page;
		$pagination->limit = 20;
		//$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('play/pricingReport', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	} else {
		$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
	}
}

	public function newProduct() {
		if ($this->checkConfig() == true) {

			$this->load->model('openbay/play_product');

			//load the language
			$this->data = array_merge($this->data, $this->load->language('openbay/play_product'));

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->model_openbay_play_product->add($this->request->post);

				$this->session->data['success'] = $this->language->get('lang_text_success');

				$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
			}

			if (!empty($this->request->get['product_id'])) {

				//load the models
				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->load->model('catalog/manufacturer');
				$this->load->model('openbay/play');

				//set the title and page info
				$this->document->setTitle($this->data['lang_page_title']);
				$this->document->addScript('view/javascript/openbay/faq.js');
				$this->document->addStyle('view/stylesheet/openbay.css');
				$this->template         = 'openbay/play_listing.tpl';
				$this->children         = array('common/header','common/footer');
				$this->data['action']   = HTTPS_SERVER . 'index.php?route=openbay/play/newProduct&token=' . $this->session->data['token'];
				$this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
				$this->data['token']    = $this->session->data['token'];
				$product_info           = $this->model_catalog_product->getProduct($this->request->get['product_id']);

				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				} else {
					$this->data['error_warning'] = '';
				}

				$this->data['product']              = $product_info;

				$this->data['actionCode']           = 'a';

				$this->data['product_id_types']     = $this->openbay->play->getProductIdType();

				$this->data['product_conditions']   = $this->openbay->play->getItemCondition();

				$this->data['product_dispatch_to']  = $this->openbay->play->getDispatchTo();

				$this->data['product_dispatch_fr']  = $this->openbay->play->getDispatchFrom();

				//check if product has isbn db column
				if($this->openbay->testDbColumn('product', 'isbn') != true) {
					$this->data['product']['isbn'] = '';
				}

				//check if product has ean db column
				$this->data['has_ean'] = false;
				if($this->openbay->testDbColumn('product', 'ean') != true) {
					$this->data['product']['ean'] = '';
				}

				//check if product has upc db column
				if($this->openbay->testDbColumn('product', 'upc') != true) {
					$this->data['product']['upc'] = '';
				}

				$this->data['defaults']['shipfrom'] = $this->config->get('obp_play_def_shipfrom');
				$this->data['defaults']['shipto'] = $this->config->get('obp_play_def_shipto');
				$this->data['defaults']['condition'] = $this->config->get('obp_play_def_itemcond');

				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			} else {
				$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
			}
		} else {
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
		}
	}

	public function editProduct() {
		if ($this->checkConfig() == true) {

			$this->load->model('openbay/play_product');

			$this->data = array_merge($this->data, $this->load->language('openbay/play_product'));

			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$this->model_openbay_play_product->edit($this->request->post);
				$this->session->data['success'] = $this->language->get('lang_text_success_updated');
				$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
			}

			if (!empty($this->request->get['product_id'])) {

				$this->load->model('catalog/product');
				$this->load->model('tool/image');
				$this->load->model('catalog/manufacturer');
				$this->load->model('openbay/play');

				$this->document->setTitle($this->data['lang_page_title_edit']);
				$this->document->addScript('view/javascript/openbay/faq.js');
				$this->document->addStyle('view/stylesheet/openbay.css');
				$this->template         = 'openbay/play_listing.tpl';
				$this->children         = array('common/header','common/footer');
				$this->data['action']   = HTTPS_SERVER . 'index.php?route=openbay/play/editProduct&token=' . $this->session->data['token'];
				$this->data['delete']   = HTTPS_SERVER . 'index.php?route=openbay/play/deleteProduct&token=' . $this->session->data['token'] .'&product_id='.$this->request->get['product_id'];
				$this->data['cancel']   = HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token'];
				$this->data['token']    = $this->session->data['token'];

				$product_info           = $this->model_catalog_product->getProduct($this->request->get['product_id']);
				$listing_info           = $this->model_openbay_play_product->getListing($this->request->get['product_id']);
				$listing_info['errors'] = $this->model_openbay_play_product->getListingErrors($this->request->get['product_id']);

				if (isset($this->error['warning'])) {
					$this->data['error_warning'] = $this->error['warning'];
				} else {
					$this->data['error_warning'] = '';
				}

				$this->data['product']              = $product_info;
				$this->data['listing']              = $listing_info;
				$this->data['actionCode']           = '';
				$this->data['product_id_types']     = $this->openbay->play->getProductIdType();
				$this->data['product_conditions']   = $this->openbay->play->getItemCondition();
				$this->data['product_dispatch_to']  = $this->openbay->play->getDispatchTo();
				$this->data['product_dispatch_fr']  = $this->openbay->play->getDispatchFrom();

				//check if product has isbn db column
				if($this->openbay->testDbColumn('product', 'isbn') != true) {
					$this->data['product']['isbn'] = '';
				}

				//check if product has ean db column
				$this->data['has_ean'] = false;
				if($this->openbay->testDbColumn('product', 'ean') != true) {
					$this->data['product']['ean'] = '';
				}

				//check if product has upc db column
				if($this->openbay->testDbColumn('product', 'upc') != true) {
					$this->data['product']['upc'] = '';
				}

				$this->data['defaults']['shipfrom'] = $this->config->get('obp_play_def_shipfrom');
				$this->data['defaults']['shipto'] = $this->config->get('obp_play_def_shipto');
				$this->data['defaults']['condition'] = $this->config->get('obp_play_def_itemcond');

				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			} else {
				$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
			}
		} else {
			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
		}
	}

	public function deleteProduct() {
		if ($this->checkConfig() == true) {
			$this->load->model('openbay/play_product');

			$this->data = array_merge($this->data, $this->load->language('openbay/play_product'));

			$this->model_openbay_play_product->delete($this->request->get['product_id']);

			$this->session->data['success'] = $this->language->get('lang_text_success_deleted');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/openbay/itemList&token=' . $this->session->data['token']);
		}
	}

	private function checkConfig() {
		$this->token = $this->config->get('obp_play_token');
		$this->secret = $this->config->get('obp_play_secret');

		if ($this->token == '' || $this->secret == '') {
			return false;
		} else {
			return true;
		}
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'openbay/play')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>