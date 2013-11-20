<?php
class ControllerOpenbayAmazonListing extends Controller {
	public function create() {
		$this->load->language('openbay/amazon_listing');
		$this->load->model('openbay/amazon_listing');
		$this->load->model('openbay/amazon');
		$this->load->model('catalog/product');
		$this->load->model('localisation/country');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if ($this->request->post) {
			$result = $this->model_openbay_amazon_listing->simpleListing($this->request->post);

			if($result['status'] === 1) {
				$this->session->data['success'] = $this->language->get('text_product_sent');
				$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			} else {
				$this->session->data['error'] = sprintf($this->language->get('text_product_not_sent'), $result['message']);
				$this->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->post['product_id'] . $url, 'SSL'));
			}
		}

		if (isset($this->request->get['product_id'])) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

			if(empty($product_info)) {
				$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$listing_status = $this->model_openbay_amazon->getProductStatus($this->request->get['product_id']);

			if($listing_status === 'processing' || $listing_status === 'ok') {
				$this->redirect($this->url->link('openbay/amazon_listing/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
			} else if ($listing_status === 'error_advanced' || $listing_status === 'saved' || $listing_status === 'error_few') {
				$this->redirect($this->url->link('openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
			}
		} else {
			$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->document->setTitle($this->language->get('lang_title'));
		$this->document->addStyle('view/stylesheet/openbay.css');
		$this->document->addScript('view/javascript/openbay/faq.js');

		$this->template = 'openbay/amazon_listing.tpl';

		$this->children = array(
			'common/header',
			'common/footer'
		);

		if (isset($this->session->data['error'])) {
			$this->data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}

		$this->data['url_return']  = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['url_search']  = $this->url->link('openbay/amazon_listing/search', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['url_advanced']  = $this->url->link('openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id='.$this->request->get['product_id'] . $url, 'SSL');

		$this->data['button_search'] = $this->language->get('button_search');
		$this->data['button_new'] = $this->language->get('button_new');
		$this->data['button_return'] = $this->language->get('button_return');
		$this->data['button_amazon_price'] = $this->language->get('button_amazon_price');
		$this->data['button_list'] = $this->language->get('button_list');

		$this->data['lang_not_in_catalog'] = $this->language->get('lang_not_in_catalog');
		$this->data['lang_title'] = $this->language->get('lang_title');
		$this->data['lang_no_results'] = $this->language->get('lang_no_results');

		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_asin'] = $this->language->get('column_asin');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['entry_sku'] = $this->language->get('entry_sku');
		$this->data['entry_condition'] = $this->language->get('entry_condition');
		$this->data['entry_condition_note'] = $this->language->get('entry_condition_note');
		$this->data['entry_price'] = $this->language->get('entry_price');
		$this->data['entry_sale_price'] = $this->language->get('entry_sale_price');
		$this->data['entry_sale_date'] = $this->language->get('entry_sale_date');
		$this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_start_selling'] = $this->language->get('entry_start_selling');
		$this->data['entry_restock_date'] = $this->language->get('entry_restock_date');
		$this->data['entry_from'] = $this->language->get('entry_from');
		$this->data['entry_to'] = $this->language->get('entry_to');

		$this->data['help_restock_date'] = $this->language->get('help_restock_date');
		$this->data['help_sku'] = $this->language->get('help_sku');
		$this->data['help_sale_price'] = $this->language->get('help_sale_price');

		$this->data['text_view_on_amazon'] = $this->language->get('text_view_on_amazon');
		$this->data['text_list'] = $this->language->get('text_list');

		$this->data['tab_required_info'] = $this->language->get('tab_required_info');
		$this->data['tab_additional_info'] = $this->language->get('tab_additional_info');

		$this->data['lang_placeholder_search'] = $this->language->get('lang_placeholder_search');
		$this->data['lang_placeholder_condition'] = $this->language->get('lang_placeholder_condition');

		$this->data['error_price'] = $this->language->get('error_price');
		$this->data['error_sku'] = $this->language->get('error_sku');
		$this->data['error_stock'] = $this->language->get('error_stock');

		$this->data['form_action'] = $this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['sku'] = trim($product_info['sku']);


		if ($this->config->get('openbay_amazon_listing_tax_added')) {
			$this->data['price'] = $product_info['price'] * (1 + $this->config->get('openbay_amazon_listing_tax_added') / 100);
		} else {
			$this->data['price'] = $product_info['price'];
		}

		$this->data['listing_errors'] = array();

		if ($listing_status == 'error_quick') {
			$this->data['listing_errors'] = $this->model_openbay_amazon->getProductErrors($product_info['product_id'], 3);
		}

		$this->data['price'] = number_format($this->data['price'], 2);
		$this->data['quantity'] = $product_info['quantity'];


		$this->data['product_id'] = $product_info['product_id'];

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
			'uk' => $this->language->get('text_united_kingdom'),
			'de' => $this->language->get('text_germany'),
			'fr' => $this->language->get('text_france'),
			'it' => $this->language->get('text_italy'),
			'es' => $this->language->get('text_spain'),
		);

		$this->data['default_marketplace'] = $this->config->get('openbay_amazon_default_listing_marketplace');
		$this->data['default_condition'] = $this->config->get('openbay_amazon_listing_default_condition');

		$this->data['token'] = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_amazon'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_title'),
			'separator' => ' :: '
		);

		$this->response->setOutput($this->render(true), $this->config->get('config_compression'));
	}

	public function edit() {
		$this->load->model('openbay/amazon_listing');
		$this->load->model('openbay/amazon');
		$this->load->language('openbay/amazon_listing');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$status = $this->model_openbay_amazon->getProductStatus($product_id);

		//If product was not submited/saved for Amazon
		if($status === false) {
			$this->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
			return;
		}

		$this->data['product_links'] = $this->model_openbay_amazon->getProductLinks($product_id);
		$this->data['url_return']  = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL');
		if($status == 'ok' || $status == 'linked') {
			$this->data['url_create_new']  = $this->url->link('openbay/amazon_listing/createNew', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
			$this->data['url_delete_links']  = $this->url->link('openbay/amazon_listing/deleteLinks', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
		}

		if($status == 'saved') {
			$this->data['has_saved_listings'] = true;
		} else {
			$this->data['has_saved_listings'] = false;
		}

		$this->data['url_saved_listings']  = $this->url->link('openbay/amazon/savedListings', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id, 'SSL');


		$this->data['token'] = $this->session->data['token'];

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_openbay'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('lang_amazon'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon_listing/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'),
			'text' => $this->language->get('text_edit_heading'),
			'separator' => ' :: '
		);

		$this->data['text_edit_heading'] = $this->language->get('text_edit_heading');
		$this->data['text_product_links'] = $this->language->get('text_product_links');

		$this->data['text_has_saved_listings'] = $this->language->get('text_has_saved_listings');

		$this->data['button_create_new_listing'] = $this->language->get('button_create_new_listing');
		$this->data['button_remove_links'] = $this->language->get('button_remove_links');
		$this->data['button_return'] = $this->language->get('button_return');
		$this->data['button_saved_listings'] = $this->language->get('button_saved_listings');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_combination'] = $this->language->get('column_combination');
		$this->data['column_sku'] = $this->language->get('column_sku');
		$this->data['column_amazon_sku'] = $this->language->get('column_amazon_sku');
		$this->data['button_create_new_listing'] = $this->language->get('button_create_new_listing');
		$this->data['button_create_new_listing'] = $this->language->get('button_create_new_listing');


		$this->template = 'openbay/amazon_listing_edit.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}

	public function createNew() {
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('openbay/amazon');
		$this->model_openbay_amazon->deleteProduct($product_id);
		$this->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
	}

	public function deleteLinks() {
		$this->load->language('openbay/amazon_listing');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_model'])) {
			$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_price'])) {
			$url .= '&filter_price=' . $this->request->get['filter_price'];
		}

		if (isset($this->request->get['filter_price_to'])) {
			$url .= '&filter_price_to=' . $this->request->get['filter_price_to'];
		}

		if (isset($this->request->get['filter_quantity'])) {
			$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		}

		if (isset($this->request->get['filter_quantity_to'])) {
			$url .= '&filter_quantity_to=' . $this->request->get['filter_quantity_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_sku'])) {
			$url .= '&filter_sku=' . $this->request->get['filter_sku'];
		}

		if (isset($this->request->get['filter_desc'])) {
			$url .= '&filter_desc=' . $this->request->get['filter_desc'];
		}

		if (isset($this->request->get['filter_category'])) {
			$url .= '&filter_category=' . $this->request->get['filter_category'];
		}

		if (isset($this->request->get['filter_manufacturer'])) {
			$url .= '&filter_manufacturer=' . $this->request->get['filter_manufacturer'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('openbay/amazon');

		$links = $this->model_openbay_amazon->getProductLinks($product_id);
		foreach ($links as $link) {
			$this->model_openbay_amazon->removeProductLink($link['amazon_sku']);
		}
		$this->model_openbay_amazon->deleteProduct($product_id);
		$this->session->data['success'] = $this->language->get('text_links_removed');

		$this->redirect($this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function search() {
		$this->load->model('openbay/amazon_listing');
		$this->load->language('openbay/amazon_listing');

		$error = '';


		if (empty($this->request->post['search_string'])) {
			$error = $this->language->get('error_text_missing');
		}

		if (empty($this->request->post['marketplace'])) {
			$error = $this->language->get('error_marketplace_missing');
		}

		if ($error) {
			$response = array(
				'data' => '',
				'error' => $error,
			);
		} else {
			$response = array(
				'data' => $this->model_openbay_amazon_listing->search($this->request->post['search_string'], $this->request->post['marketplace']),
				'error' => '',
			);
		}

		$this->response->setOutput(json_encode($response));
	}

	public function bestPrice() {
		$this->load->model('openbay/amazon_listing');
		$this->load->language('openbay/amazon_listing');

		$error = '';


		if (empty($this->request->post['asin'])) {
			$error = $this->language->get('error_missing_asin');
		}

		if (empty($this->request->post['marketplace'])) {
			$error = $this->language->get('error_marketplace_missing');
		}

		if (empty($this->request->post['condition'])) {
			$error = $this->language->get('error_condition_missing');
		}

		if ($error) {
			$response = array(
				'data' => '',
				'error' => $error,
			);
		} else {
			$bestPrice = $this->model_openbay_amazon_listing->getBestPrice($this->request->post['asin'], $this->request->post['condition'], $this->request->post['marketplace']);

			if ($bestPrice) {
				$response = array(
					'data' => $bestPrice,
					'error' => '',
				);
			} else {
				$response = array(
					'data' => '',
					'error' => $this->language->get('error_amazon_price'),
				);
			}
		}

		$this->response->setOutput(json_encode($response));
	}

	public function getProductByAsin() {
		$this->load->model('openbay/amazon_listing');

		$data = $this->model_openbay_amazon_listing->getProductByAsin($this->request->post['asin'], $this->request->post['market']);

		$response = array(
			'title' => (string)$data['ItemAttributes']['Title'],
			'img' => (!isset($data['ItemAttributes']['SmallImage']['URL']) ? '' : $data['ItemAttributes']['SmallImage']['URL'])
		);

		$this->response->setOutput(json_encode($response));
	}

	public function getBrowseNodes() {
		$this->load->model('openbay/amazon_listing');

		$data = array(
			'marketplaceId' => $this->request->post['marketplaceId'],
			'node' => (isset($this->request->post['node']) ? $this->request->post['node'] : ''),
		);

		$response = $this->model_openbay_amazon_listing->getBrowseNodes($data);

		$this->response->setOutput($response);
	}
}
?>