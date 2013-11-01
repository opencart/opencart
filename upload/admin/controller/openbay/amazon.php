<?php
class ControllerOpenbayAmazon extends Controller {
	public function stockUpdates() {
		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_stockupdates'));

		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');


		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/overview&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/stockUpdates&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_stock_updates'),
			'separator' => ' :: '
		);

		$this->template = 'openbay/amazon_stock_updates.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');

		$requestArgs = array();

		if (isset($this->request->get['filter_date_start'])) {
			$requestArgs['date_start'] = date("Y-m-d", strtotime($this->request->get['filter_date_start']));
		} else {
			$requestArgs['date_start'] = date("Y-m-d");
		}

		if (isset($this->request->get['filter_date_end'])) {
			$requestArgs['date_end'] = date("Y-m-d", strtotime($this->request->get['filter_date_end']));
		} else {
			$requestArgs['date_end'] = date("Y-m-d");
		}

		$this->data['date_start'] = $requestArgs['date_start'];
		$this->data['date_end'] = $requestArgs['date_end'];

		$xml = $this->openbay->amazon->getStockUpdatesStatus($requestArgs);
		$simpleXmlObj = simplexml_load_string($xml);
		$this->data['tableData'] = array();

		if($simpleXmlObj !== false) {

			$tableData = array();

			foreach($simpleXmlObj->update as $updateNode) {
				$row = array('date_requested' => (string)$updateNode->date_requested,
					'date_updated' => (string)$updateNode->date_updated,
					'status' => (string)$updateNode->status,
					);
				$data = array();
				foreach($updateNode->data->product as $productNode) {
					$data[] = array('sku' => (string)$productNode->sku,
						'stock' => (int)$productNode->stock
						);
				}
				$row['data'] = $data;
				$tableData[(int)$updateNode->ref] = $row;
			}

			$this->data['tableData'] = $tableData;

		} else {
			$this->data['error'] = 'Could not connect to OpenBay PRO API.';
		}

		$this->data['token'] = $this->session->data['token'];

		$this->response->setOutput($this->render());


	}

	public function index() {
		$this->redirect($this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL'));
		return;
	}

	public function overview() {
		$this->load->model('setting/setting');
		$this->load->model('localisation/order_status');
		$this->load->model('openbay/amazon');
		$this->load->model('sale/customer_group');

		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_overview'));

		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->data['validation'] = $this->openbay->amazon->validate();
		$this->data['link_settings'] = $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_subscription'] = $this->url->link('openbay/amazon/subscription', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_item_link'] = $this->url->link('openbay/amazon/itemLinks', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_stock_updates'] = $this->url->link('openbay/amazon/stockUpdates', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_saved_listings'] = $this->url->link('openbay/amazon/savedListings', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_bulk_listing'] = $this->url->link('openbay/amazon/bulkListProducts', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['link_bulk_linking'] = $this->url->link('openbay/amazon/bulkLinking', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'openbay/amazon_overview.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function subscription() {
		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_subscription'));

		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/overview&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/subscription&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_my_account'),
			'separator' => ' :: '
		);

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');

		$response = simplexml_load_string($this->openbay->amazon->callWithResponse('plans/getPlans'));

		$plans = array();

		if ($response) {
			foreach ($response->Plan as $plan) {
				$plans[] = array(
					'title' => (string)$plan->Title,
					'description' => (string)$plan->Description,
					'order_frequency' => (string)$plan->OrderFrequency,
					'product_listings' => (string)$plan->ProductListings,
					'bulk_listing' => (string)$plan->BulkListing,
					'price' => (string)$plan->Price,
				);
			}
		}

		$this->data['plans'] = $plans;

		$response = simplexml_load_string($this->openbay->amazon->callWithResponse('plans/getUsersPlans'));

		$plan = false;

		if ($response) {
			$plan = array(
				'merchant_id' => (string)$response->MerchantId,
				'user_status' => (string)$response->UserStatus,
				'title' => (string)$response->Title,
				'description' => (string)$response->Description,
				'price' => (string)$response->Price,
				'order_frequency' => (string)$response->OrderFrequency,
				'product_listings' => (string)$response->ProductListings,
				'listings_remain' => (string)$response->ListingsRemain,
				'listings_reserved' => (string)$response->ListingsReserved,
				'bulk_listing' => (string)$response->BulkListing,
			);
		}

		$this->data['user_plan'] = $plan;
		$this->data['server'] = $this->openbay->amazon->getServer();
		$this->data['token'] = $this->config->get('openbay_amazon_token');

		$this->template = 'openbay/amazon_subscription.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function settings() {
		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_settings'));
		$this->load->language('openbay/amazon_listing');
		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->load->model('setting/setting');
		$this->load->model('localisation/order_status');
		$this->load->model('openbay/amazon');
		$this->load->model('sale/customer_group');

		$settings = $this->model_setting_setting->getSetting('openbay_amazon');

		if (isset($settings['openbay_amazon_orders_marketplace_ids'])) {
			$settings['openbay_amazon_orders_marketplace_ids'] = $this->is_serialized($settings['openbay_amazon_orders_marketplace_ids']) ? (array)unserialize($settings['openbay_amazon_orders_marketplace_ids']) : $settings['openbay_amazon_orders_marketplace_ids'];
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			if (!isset($this->request->post['openbay_amazon_orders_marketplace_ids'])) {
				$this->request->post['openbay_amazon_orders_marketplace_ids'] = array();
			}

			$settings = array_merge($settings, $this->request->post);
			$this->model_setting_setting->editSetting('openbay_amazon', $settings);

			$this->config->set('openbay_amazon_token', $this->request->post['openbay_amazon_token']);
			$this->config->set('openbay_amazon_enc_string1', $this->request->post['openbay_amazon_enc_string1']);
			$this->config->set('openbay_amazon_enc_string2', $this->request->post['openbay_amazon_enc_string2']);

			$this->model_openbay_amazon->scheduleOrders($settings);

			$this->session->data['success'] = $this->language->get('lang_setttings_updated');
			$this->redirect($this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL'));
			return;
		}

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/overview&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/settings&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_settings'),
			'separator' => ' :: '
		);

		$this->data['marketplace_ids']                  = (isset($settings['openbay_amazon_orders_marketplace_ids']) ? (array)$settings['openbay_amazon_orders_marketplace_ids'] : array() );
		$this->data['default_listing_marketplace_ids']  = ( isset($settings['openbay_amazon_default_listing_marketplace_ids']) ? (array)$settings['openbay_amazon_default_listing_marketplace_ids'] : array() );

		$this->data['marketplaces'] = array(
			array('name' => $this->language->get('lang_de'), 'id' => 'A1PA6795UKMFR9', 'code' => 'de'),
			array('name' => $this->language->get('lang_fr'), 'id' => 'A13V1IB3VIYZZH', 'code' => 'fr'),
			array('name' => $this->language->get('lang_it'), 'id' => 'APJ6JRA9NG5V4', 'code' => 'it'),
			array('name' => $this->language->get('lang_es'), 'id' => 'A1RKKUPIHCS9HS', 'code' => 'es'),
			array('name' => $this->language->get('lang_uk'), 'id' => 'A1F83G8C2ARO7P', 'code' => 'uk'),
		);

		$this->data['conditions'] = array(
			'New' => $this->language->get('text_new'),
			'UsedLikeNew' => $this->language->get('text_used_like_new'),
			'UsedVeryGood' => $this->language->get('text_used_very_good'),
			'UsedGood' => $this->language->get('text_used_good'),
			'UsedAcceptable' => $this->language->get('text_used_acceptable'),
			'CollectibleLikeNew' => $this->language->get('text_collectible_like_new'),
			'CollectibleVeryGood' => $this->language->get('text_collectible_very_good'),
			'CollectibleGood' => $this->language->get('text_collectible_good'),
			'CollectibleAcceptable' => $this->language->get('text_collectible_acceptable'),
			'Refurbished' => $this->language->get('text_refurbished'),
		);

		$this->data['is_enabled'] = isset($settings['amazon_status']) ? $settings['amazon_status'] : '';
		$this->data['openbay_amazon_token'] = isset($settings['openbay_amazon_token']) ? $settings['openbay_amazon_token'] : '';
		$this->data['openbay_amazon_enc_string1'] = isset($settings['openbay_amazon_enc_string1']) ? $settings['openbay_amazon_enc_string1'] : '';
		$this->data['openbay_amazon_enc_string2'] = isset($settings['openbay_amazon_enc_string2']) ? $settings['openbay_amazon_enc_string2'] : '';
		$this->data['openbay_amazon_listing_tax_added'] = isset($settings['openbay_amazon_listing_tax_added']) ? $settings['openbay_amazon_listing_tax_added'] : '0.00';
		$this->data['openbay_amazon_order_tax'] = isset($settings['openbay_amazon_order_tax']) ? $settings['openbay_amazon_order_tax'] : '00';
		$this->data['openbay_amazon_default_listing_marketplace'] = isset($settings['openbay_amazon_default_listing_marketplace']) ? $settings['openbay_amazon_default_listing_marketplace'] : '';
		$this->data['openbay_amazon_listing_default_condition'] = isset($settings['openbay_amazon_listing_default_condition']) ? $settings['openbay_amazon_listing_default_condition'] : '';

		$unshippedStatusId = isset($settings['openbay_amazon_order_status_unshipped']) ? $settings['openbay_amazon_order_status_unshipped'] : '';
		$partiallyShippedStatusId = isset($settings['openbay_amazon_order_status_partially_shipped']) ? $settings['openbay_amazon_order_status_partially_shipped'] : '';
		$shippedStatusId = isset($settings['openbay_amazon_order_status_shipped']) ? $settings['openbay_amazon_order_status_shipped'] : '';
		$canceledStatusId = isset($settings['openbay_amazon_order_status_canceled']) ? $settings['openbay_amazon_order_status_canceled'] : '';

		$amazonOrderStatuses = array(
			'unshipped' => array('name' => $this->language->get('lang_unshipped'), 'order_status_id' => $unshippedStatusId),
			'partially_shipped' => array('name' => $this->language->get('lang_partially_shipped'), 'order_status_id' => $partiallyShippedStatusId),
			'shipped' => array('name' => $this->language->get('lang_shipped'), 'order_status_id' => $shippedStatusId),
			'canceled' => array('name' => $this->language->get('lang_canceled'), 'order_status_id' => $canceledStatusId),
		);

		$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();
		$this->data['openbay_amazon_order_customer_group'] = isset($settings['openbay_amazon_order_customer_group']) ? $settings['openbay_amazon_order_customer_group'] : '';

		$this->data['amazon_order_statuses'] = $amazonOrderStatuses;
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['subscription_url'] = $this->url->link('openbay/amazon/subscription', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['itemLinks_url'] = $this->url->link('openbay/amazon_product/linkItems', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['openbay_amazon_notify_admin'] = isset($settings['openbay_amazon_notify_admin']) ? $settings['openbay_amazon_notify_admin'] : '';


		$this->template = 'openbay/amazon_settings.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$pingInfo = simplexml_load_string($this->openbay->amazon->callWithResponse('ping/info'));

		$api_status = false;
		$api_auth = false;
		if($pingInfo) {
			$api_status = ((string)$pingInfo->Api_status == 'ok') ? true : false;
			$api_auth = ((string)$pingInfo->Auth == 'true') ? true : false;
		}

		$this->data['API_status'] = $api_status;
		$this->data['API_auth'] = $api_auth;

		$this->response->setOutput($this->render());

	}

	private function is_serialized( $data ) {
		// if it isn't a string, it isn't serialized
		if (!is_string($data)) {
			return false;
		}
		$data = trim($data);
		if ('N;' == $data) {
			return true;
		}
		if (!preg_match('/^([adObis]):/', $data, $badions)) {
			return false;
		}
		switch ($badions[1]) {
			case 'a' :
			case 'O' :
			case 's' :
				if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
					return true;
				}
				break;
			case 'b' :
			case 'i' :
			case 'd' :
				if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
					return true;
				}
				break;
		}
		return false;
	}

	public function itemLinks() {
		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_links'));
		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/overview&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/itemLinks&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_item_links'),
			'separator' => ' :: '
		);

		$this->data['token'] = $this->session->data['token'];

		$this->data['addItemLinkAjax'] = $this->url->link('openbay/amazon/addItemLinkAjax', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['removeItemLinkAjax'] = $this->url->link('openbay/amazon/removeItemLinkAjax', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['getItemLinksAjax'] = $this->url->link('openbay/amazon/getItemLinksAjax', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['getUnlinkedItemsAjax'] = $this->url->link('openbay/amazon/getUnlinkedItemsAjax', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['getOpenstockOptionsAjax'] = $this->url->link('openbay/amazon/getOpenstockOptionsAjax', 'token=' . $this->session->data['token'], 'SSL');

		$this->template = 'openbay/amazon_item_links.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function savedListings() {

		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_listingsaved'));

		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=extension/openbay&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/overview&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => HTTPS_SERVER . 'index.php?route=openbay/amazon/savedListings&token=' . $this->session->data['token'],
			'text'      => $this->language->get('lang_saved_listings'),
			'separator' => ' :: '
		);

		$this->template = 'openbay/amazon_saved_listings.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->data['token'] = $this->session->data['token'];
		$this->load->model('openbay/amazon');
		$saved_products = $this->model_openbay_amazon->getSavedProducts();

		$this->data['saved_products'] = array();

		foreach($saved_products as $saved_product) {
			$this->data['saved_products'][] = array(
				'product_id' => $saved_product['product_id'],
				'product_name' => $saved_product['product_name'],
				'product_model' => $saved_product['product_model'],
				'product_sku' => $saved_product['product_sku'],
				'amazon_sku' => $saved_product['amazon_sku'],
				'var' => $saved_product['var'],
				'edit_link' => $this->url->link('openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $saved_product['product_id'] . '&var=' . $saved_product['var'], 'SSL'),
			);
		}

		$this->data['deleteSavedAjax'] = $this->url->link('openbay/amazon/deleteSavedAjax', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['uploadSavedAjax'] = $this->url->link('openbay/amazon_product/uploadSavedAjax', 'token=' . $this->session->data['token'], 'SSL');

		$this->response->setOutput($this->render());
	}

	public function install() {
		$this->load->model('openbay/amazon');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_amazon->install();
		$this->model_setting_extension->install('openbay', $this->request->get['extension']);
	}

	public function uninstall() {
		$this->load->model('openbay/amazon');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_amazon->uninstall();
		$this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'openbay/amazon')) {
			$this->error = $this->language->get('error_permission');
		}

		if (empty($this->error)) {
			return true;
		}

		return false;
	}

	public function getOpenstockOptionsAjax() {
		$options = array();
		if($this->openbay->addonLoad('openstock') && isset($this->request->get['product_id'])) {
			$this->load->model('openstock/openstock');
			$this->load->model('tool/image');
			$options = $this->model_openstock_openstock->getProductOptionStocks($this->request->get['product_id']);
		}
		if(empty($options)) {
			$options = false;
		}
		$this->response->setOutput(json_encode($options));
	}

	public function addItemLinkAjax() {
		if(isset($this->request->get['product_id']) && isset($this->request->get['amazon_sku'])) {
			$amazon_sku = $this->request->get['amazon_sku'];
			$product_id = $this->request->get['product_id'];
			$var = isset($this->request->get['var']) ? $this->request->get['var'] : '';

		} else {
			$result = json_encode('error');
			$this->response->setOutput($result);
			return;
		}
		$this->load->model('openbay/amazon');
		$this->load->library('amazon');
		$this->model_openbay_amazon->linkProduct($amazon_sku, $product_id, $var);
		$logger = new Log('amazon_stocks.log');
		$logger->write('addItemLink() called for product id: ' . $product_id . ', amazon sku: ' . $amazon_sku . ', var: ' . $var);

		if($var != '' && $this->openbay->addonLoad('openstock')) {
			$logger->write('Using openStock');
			$this->load->model('tool/image');
			$this->load->model('openstock/openstock');
			$optionStocks = $this->model_openstock_openstock->getProductOptionStocks($product_id);
			$quantityData = array();
			foreach($optionStocks as $optionStock) {
				if(isset($optionStock['var']) && $optionStock['var'] == $var) {
					$quantityData[$amazon_sku] = $optionStock['stock'];
					break;
				}
			}
			if(!empty($quantityData)) {
				$logger->write('Updating quantities with data: ' . print_r($quantityData, true));
				$this->openbay->amazon->updateQuantities($quantityData);
			} else {
				$logger->write('No quantity data will be posted.');
			}
		} else {
			$this->openbay->amazon->putStockUpdateBulk(array($product_id));
		}

		$result = json_encode('ok');
		$this->response->setOutput($result);
		$logger->write('addItemLink() exiting');
	}

	public function removeItemLinkAjax() {
		if(isset($this->request->get['amazon_sku'])) {
			$amazon_sku = $this->request->get['amazon_sku'];
		} else {
			$result = json_encode('error');
			$this->response->setOutput($result);
			return;
		}
		$this->load->model('openbay/amazon');

		$this->model_openbay_amazon->removeProductLink($amazon_sku);

		$result = json_encode('ok');
		$this->response->setOutput($result);
	}

	public function getItemLinksAjax() {
		$this->load->model('openbay/amazon');
		$this->load->model('catalog/product');

		$itemLinks = $this->model_openbay_amazon->getProductLinks();
		$result = json_encode($itemLinks);
		$this->response->setOutput($result);
	}

	public function getUnlinkedItemsAjax() {
		$this->load->model('openbay/amazon');
		$this->load->model('catalog/product');

		$unlinkedProducts = $this->model_openbay_amazon->getUnlinkedProducts();
		$result = json_encode($unlinkedProducts);
		$this->response->setOutput($result);
	}

	public function deleteSavedAjax() {
		if(!isset($this->request->get['product_id']) || !isset($this->request->get['var'])) {
			return;
		}

		$this->load->model('openbay/amazon');
		$this->model_openbay_amazon->deleteSaved($this->request->get['product_id'], $this->request->get['var']);
	}

	public function doBulkList() {
		$this->load->language('openbay/amazon_listing');
		$this->load->model('openbay/amazon_listing');

		$delete_search_results = array();

		$bulk_list_products = array();

		foreach ($this->request->post['products'] as $product_id => $asin) {
			$delete_search_results[] = $product_id;

			if (!empty($asin)) {
				$bulk_list_products[$product_id] = $asin;
			}
		}

		$status = false;

		if ($bulk_list_products) {
			$data = array();

			$data['products'] = $bulk_list_products;
			$data['marketplace'] = $this->request->post['marketplace'];

			if (!empty($this->request->post['start_selling'])) {
				$data['start_selling'] = $this->request->post['start_selling'];
			}

			if (!empty($this->request->post['condition']) && !empty($this->request->post['condition_note'])) {
				$data['condition'] = $this->request->post['condition'];
				$data['condition_note'] = $this->request->post['condition_note'];
			}

			$status = $this->model_openbay_amazon_listing->doBulkListing($data);

			if ($status) {
				$message = $this->language->get('text_products_sent');

				if ($delete_search_results) {
					$this->model_openbay_amazon_listing->deleteSearchResults($this->request->post['marketplace'], $delete_search_results);
				}
			} else {
				$message = $this->language->get('error_sending_products');
			}
		} else {
			$message = $this->language->get('error_no_products_selected');
		}

		$json = array(
			'status' => $status,
			'message' => $message,
		);

		$this->response->setOutput(json_encode($json));
	}

	public function doBulkSearch() {
		$this->load->model('catalog/product');
		$this->load->model('openbay/amazon_listing');
		$this->load->language('openbay/amazon_bulk');

		$json = array();
		$search_data = array();

		if (!empty($this->request->post['product_ids'])) {
			foreach ($this->request->post['product_ids'] as $product_id) {
				$product = $this->model_catalog_product->getProduct($product_id);

				if (empty($product['sku'])) {
					$json[$product_id] = array(
						'error' => $this->language->get('error_product_sku')
					);
				}

				$key = '';

				$id_types = array('isbn', 'upc', 'ean', 'jan');

				foreach ($id_types as $id_type) {
					if (!empty($product[$id_type])) {
						$key = $id_type;
						break;
					}
				}

				if (!$key) {
					$json[$product_id] = array(
						'error' => $this->language->get('error_product_no_searchable_fields')
					);
				}

				if (!isset($json[$product_id])) {
					$search_data[$key][] = array(
						'product_id' => $product['product_id'],
						'value' => trim($product[$id_type]),
						'marketplace' => $this->request->post['marketplace'],
					);

					$json[$product_id] = array(
						'success' => $this->language->get('text_searching')
					);
				}
			}
		}

		if ($search_data) {
			$this->model_openbay_amazon_listing->doBulkSearch($search_data);
		}

		$this->response->setOutput(json_encode($json));
	}

	public function bulkListProducts() {
		$this->load->model('openbay/amazon');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_bulk'));

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('openbay/amazon/bulkListProducts', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_bulk_listing'),
			'separator' => ' :: '
		);

		$pingInfo = simplexml_load_string($this->openbay->amazon->callWithResponse('ping/info'));

		$bulk_listing_status = false;
		if ($pingInfo) {
			$bulk_listing_status = ((string)$pingInfo->BulkListing == 'true') ? true : false;
		}

		if (!empty($this->request->get['filter_marketplace'])) {
			$filter_marketplace = $this->request->get['filter_marketplace'];
		} else {
			$filter_marketplace = $this->config->get('openbay_amazon_default_listing_marketplace');
		}

		$this->data['filter_marketplace'] = $filter_marketplace;

		$this->data['bulk_listing_status'] = $bulk_listing_status;

		$this->data['link_overview'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		if ($bulk_listing_status) {
			$this->data['link_search'] = $this->url->link('openbay/amazon/doBulkSearch', 'token=' . $this->session->data['token'], 'SSL');

			$this->data['default_condition'] = $this->config->get('openbay_amazon_listing_default_condition');
			$this->data['conditions'] = array(
				'New' => $this->language->get('text_new'),
				'UsedLikeNew' => $this->language->get('text_used_like_new'),
				'UsedVeryGood' => $this->language->get('text_used_very_good'),
				'UsedGood' => $this->language->get('text_used_good'),
				'UsedAcceptable' => $this->language->get('text_used_acceptable'),
				'CollectibleLikeNew' => $this->language->get('text_collectible_like_new'),
				'CollectibleVeryGood' => $this->language->get('text_collectible_very_good'),
				'CollectibleGood' => $this->language->get('text_collectible_good'),
				'CollectibleAcceptable' => $this->language->get('text_collectible_acceptable'),
				'Refurbished' => $this->language->get('text_refurbished'),
			);

			$this->data['marketplaces'] = array(
				array('name' => $this->language->get('text_de'), 'code' => 'de'),
				array('name' => $this->language->get('text_fr'), 'code' => 'fr'),
				array('name' => $this->language->get('text_it'), 'code' => 'it'),
				array('name' => $this->language->get('text_es'), 'code' => 'es'),
				array('name' => $this->language->get('text_uk'), 'code' => 'uk'),
			);

			if (!empty($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}

			$data = array();

			$data['filter_marketplace'] = $filter_marketplace;
			$data['start'] = ($page - 1) * $this->config->get('config_admin_limit');
			$data['limit'] = $this->config->get('config_admin_limit');

			$results = $this->model_openbay_amazon->getProductSearch($data);
			$product_total = $this->model_openbay_amazon->getProductSearchTotal($data);

			$this->data['products'] = array();

			foreach ($results as $result) {
				$product = $this->model_catalog_product->getProduct($result['product_id']);

				if ($product['image'] && file_exists(DIR_IMAGE . $product['image'])) {
					$image = $this->model_tool_image->resize($product['image'], 40, 40);
				} else {
					$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
				}

				if ($result['status'] == 'searching') {
					$search_status = $this->language->get('text_searching');
				} else if ($result['status'] == 'finished') {
					$search_status = $this->language->get('text_finished');
				} else {
					$search_status = '-';
				}

				$href = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], 'SSL');

				$search_results = array();

				if ($result['data']) {
					foreach ($result['data'] as $search_result) {
						$link = $this->model_openbay_amazon->getAsinLink($search_result['asin'], $result['marketplace']);

						$search_results[] = array(
							'title' => $search_result['title'],
							'asin' => $search_result['asin'],
							'href' => $link,
						);
					}
				}

				$this->data['products'][] = array(
					'product_id' => $product['product_id'],
					'href' => $href,
					'name' => $product['name'],
					'model' => $product['model'],
					'image' => $image,
					'matches' => $result['matches'],
					'search_status' => $search_status,
					'search_results' => $search_results,
				);
			}

			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $this->config->get('config_admin_limit');
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('openbay/amazon/bulkListProducts', 'token=' . $this->session->data['token'] . '&page={page}&filter_marketplace=' . $filter_marketplace, 'SSL');

			$this->data['pagination'] = $pagination->render();
		}

		$this->template = 'openbay/amazon_bulk_listing.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function bulkLinking() {
		$this->load->model('openbay/amazon');

		$this->data = array_merge($this->data, $this->load->language('openbay/amazon_bulk_linking'));

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->data['breadcrumbs'] = array();
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_openbay'),
			'separator' => ' :: '
		);
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_overview'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('openbay/amazon/bulkLinking', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_bulk_linking'),
			'separator' => ' :: '
		);

		$this->template = 'openbay/amazon_bulk_linking.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$pingInfo = simplexml_load_string($this->openbay->amazon->callWithResponse('ping/info'));

		$bulk_linking_status = false;
		if ($pingInfo) {
			$bulk_linking_status = ((string)$pingInfo->BulkLinking == 'true') ? true : false;
		}

		$this->data['bulk_linking_status'] = $bulk_linking_status;

		$marketplaces = array(
			array(
				'name' => $this->language->get('text_uk'),
				'code' => 'uk',
				'href_load_listings' => $this->url->link('openbay/amazon/loadListingReport', 'token=' . $this->session->data['token'] . '&marketplace=uk', 'SSL'),
			),
			array(
				'name' => $this->language->get('text_de'),
				'code' => 'de',
				'href_load_listings' => $this->url->link('openbay/amazon/loadListingReport', 'token=' . $this->session->data['token'] . '&marketplace=de', 'SSL'),
			),
			array(
				'name' => $this->language->get('text_fr'),
				'code' => 'fr',
				'href_load_listings' => $this->url->link('openbay/amazon/loadListingReport', 'token=' . $this->session->data['token'] . '&marketplace=fr', 'SSL'),
			),
			array(
				'name' => $this->language->get('text_it'),
				'code' => 'it',
				'href_load_listings' => $this->url->link('openbay/amazon/loadListingReport', 'token=' . $this->session->data['token'] . '&marketplace=it', 'SSL'),
			),
			array(
				'name' => $this->language->get('text_es'),
				'code' => 'es',
				'href_load_listings' => $this->url->link('openbay/amazon/loadListingReport', 'token=' . $this->session->data['token'] . '&marketplace=es', 'SSL'),
			),
		);

		foreach ($marketplaces as &$marketplace) {
			$results = $this->model_openbay_amazon->getUnlinkedItemsFromReport($marketplace['code']);

			$products = array();

			foreach ($results as $result) {
				$products[] = array(
					'asin' => $result['asin'],
					'href_amazon' => $this->model_openbay_amazon->getAsinLink($result['asin'], $marketplace['code']),
					'amazon_sku' => $result['amazon_sku'],
					'amazon_quantity' => $result['amazon_quantity'],
					'amazon_price' => $result['amazon_price'],
					'name' => $result['name'],
					'sku' => $result['sku'],
					'quantity' => $result['quantity'],
					'combination' => $result['combination'],
					'product_id' => $result['product_id'],
					'var' => $result['var'],
					'href_product' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'], 'SSL'),
				);
			}

			$marketplace['unlinked_products'] = $products;
		}

		$this->data['marketplaces'] = $marketplaces;

		$this->data['marketplaces_processing'] = $this->config->get('openbay_amazon_processing_listing_reports');
		$this->data['href_return'] = $this->url->link('openbay/amazon/overview', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['href_do_bulk_linking'] = $this->url->link('openbay/amazon/doBulkLinking', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['token'] = $this->session->data['token'];

		$this->response->setOutput($this->render());
	}

	public function loadListingReport() {
		$this->load->model('openbay/amazon');
		$this->load->model('setting/setting');
		$this->load->language('openbay/amazon_bulk_linking');

		$marketplace = $this->request->get['marketplace'];

		$this->model_openbay_amazon->deleteListingReports($marketplace);

		$request_data = array(
			'marketplace' => $marketplace,
			'response_url' => HTTPS_CATALOG . 'index.php?route=amazon/listing_report',
		);

		$response = $this->openbay->amazon->callWithResponse('report/listing', $request_data);
		$response = json_decode($response, 1);

		$json = array();
		$json['status'] = $response['status'];

		if ($json['status']) {
			$json['message'] = $this->language->get('text_report_requested');

			$settings = $this->model_setting_setting->getSetting('openbay_amazon');
			$settings['openbay_amazon_processing_listing_reports'][] = $marketplace;

			$this->model_setting_setting->editSetting('openbay_amazon', $settings);
		} else {
			$json['message'] = $this->language->get('text_report_request_failed');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function doBulkLinking() {
		$this->load->model('openbay/amazon');

		$links = array();
		$amazonSkus = array();

		if (!empty($this->request->post['link'])) {
			foreach ($this->request->post['link'] as $link) {
				if (!empty($link['product_id'])) {
					$links[] = $link;
					$amazonSkus[] = $link['amazon_sku'];
				}
			}
		}

		if (!empty($links)) {
			foreach ($links as $link) {
				$this->model_openbay_amazon->linkProduct($link['amazon_sku'], $link['product_id'], $link['var']);
			}

			//$this->model_openbay_amazon->updateAmazonSkusQuantities($amazonSkus);
		}
	}
}

?>