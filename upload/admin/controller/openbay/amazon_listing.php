<?php
class ControllerOpenbayAmazonListing extends Controller {
	public function create() {
		$this->load->language('openbay/amazon_listing');
		$this->load->model('openbay/amazon_listing');
		$this->load->model('openbay/amazon');
		$this->load->model('catalog/product');
		$this->load->model('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error_warning'] = '';
		}

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

			if ($result['status'] === 1) {
				$this->session->data['success'] = $this->language->get('text_product_sent');
				$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			} else {
				$this->session->data['error'] = sprintf($this->language->get('text_product_not_sent'), $result['message']);
				$this->response->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->post['product_id'] . $url, 'SSL'));
			}
		}

		if (isset($this->request->get['product_id'])) {
			$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

			if (empty($product_info)) {
				$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
			}

			$listing_status = $this->model_openbay_amazon->getProductStatus($this->request->get['product_id']);

			if ($listing_status === 'processing' || $listing_status === 'ok') {
				$this->response->redirect($this->url->link('openbay/amazon_listing/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
			} else if ($listing_status === 'error_advanced' || $listing_status === 'saved' || $listing_status === 'error_few') {
				$this->response->redirect($this->url->link('openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL'));
			}
		} else {
			$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$data['url_return']  = $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['url_search']  = $this->url->link('openbay/amazon_listing/search', 'token=' . $this->session->data['token'], 'SSL');
		$data['url_advanced']  = $this->url->link('openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $this->request->get['product_id'] . $url, 'SSL');

		$data['button_search'] = $this->language->get('button_search');
		$data['button_new'] = $this->language->get('button_new');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_amazon_price'] = $this->language->get('button_amazon_price');
		$data['button_list'] = $this->language->get('button_list');
		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_not_in_catalog'] = $this->language->get('text_not_in_catalog');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['button_view_on_amazon'] = $this->language->get('button_view_on_amazon');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_placeholder_search'] = $this->language->get('text_placeholder_search');
		$data['text_placeholder_condition'] = $this->language->get('text_placeholder_condition');
		$data['column_image'] = $this->language->get('column_image');
		$data['column_asin'] = $this->language->get('column_asin');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_action'] = $this->language->get('column_action');
		$data['entry_sku'] = $this->language->get('entry_sku');
		$data['entry_condition'] = $this->language->get('entry_condition');
		$data['entry_condition_note'] = $this->language->get('entry_condition_note');
		$data['entry_price'] = $this->language->get('entry_price');
		$data['entry_sale_price'] = $this->language->get('entry_sale_price');
		$data['entry_sale_date'] = $this->language->get('entry_sale_date');
		$data['entry_quantity'] = $this->language->get('entry_quantity');
		$data['entry_start_selling'] = $this->language->get('entry_start_selling');
		$data['entry_restock_date'] = $this->language->get('entry_restock_date');
		$data['entry_from'] = $this->language->get('entry_from');
		$data['entry_to'] = $this->language->get('entry_to');
		$data['help_restock_date'] = $this->language->get('help_restock_date');
		$data['help_sku'] = $this->language->get('help_sku');
		$data['help_sale_price'] = $this->language->get('help_sale_price');
		$data['tab_required'] = $this->language->get('tab_required');
		$data['tab_additional'] = $this->language->get('tab_additional');
		$data['error_price'] = $this->language->get('error_price');
		$data['error_sku'] = $this->language->get('error_sku');
		$data['error_stock'] = $this->language->get('error_stock');

		$data['form_action'] = $this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'], 'SSL');

		$data['sku'] = trim($product_info['sku']);

		if ($this->config->get('openbay_amazon_listing_tax_added')) {
			$data['price'] = $product_info['price'] * (1 + $this->config->get('openbay_amazon_listing_tax_added') / 100);
		} else {
			$data['price'] = $product_info['price'];
		}

		$data['listing_errors'] = array();

		if ($listing_status == 'error_quick') {
			$data['listing_errors'] = $this->model_openbay_amazon->getProductErrors($product_info['product_id'], 3);
		}

		$data['price'] = number_format($data['price'], 2, '.', '');
		$data['quantity'] = $product_info['quantity'];
		$data['product_id'] = $product_info['product_id'];

		$data['conditions'] = array(
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

		$data['marketplaces'] = array(
			'uk' => $this->language->get('text_united_kingdom'),
			'de' => $this->language->get('text_germany'),
			'fr' => $this->language->get('text_france'),
			'it' => $this->language->get('text_italy'),
			'es' => $this->language->get('text_spain'),
		);

		$data['default_marketplace'] = $this->config->get('openbay_amazon_default_listing_marketplace');
		$data['default_condition'] = $this->config->get('openbay_amazon_listing_default_condition');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_amazon'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/amazon_listing.tpl', $data));
	}

	public function edit() {
		$this->load->model('openbay/amazon_listing');
		$this->load->model('openbay/amazon');
		$this->load->language('openbay/amazon_listing');

		$this->document->setTitle($this->language->get('text_edit_heading'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

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
			$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_amazon'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/amazon_listing/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'),
			'text' => $this->language->get('text_edit_heading'),
		);

		$status = $this->model_openbay_amazon->getProductStatus($product_id);

		if ($status === false) {
			$this->response->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
			return;
		}

		$data['product_links'] = $this->model_openbay_amazon->getProductLinks($product_id);
		$data['url_return']  = $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if ($status == 'ok' || $status == 'linked') {
			$data['url_create_new']  = $this->url->link('openbay/amazon_listing/createNew', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
			$data['url_delete_links']  = $this->url->link('openbay/amazon_listing/deleteLinks', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL');
		}

		if ($status == 'saved') {
			$data['has_saved_listings'] = true;
		} else {
			$data['has_saved_listings'] = false;
		}

		$data['url_saved_listings']  = $this->url->link('openbay/amazon/savedListings', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id, 'SSL');

		$data['token'] = $this->session->data['token'];

		$data['text_edit_heading'] = $this->language->get('text_edit_heading');
		$data['text_product_links'] = $this->language->get('text_product_links');
		$data['text_has_saved_listings'] = $this->language->get('text_has_saved_listings');
		$data['button_create_new_listing'] = $this->language->get('button_create_new_listing');
		$data['button_remove_links'] = $this->language->get('button_remove_links');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_saved_listings'] = $this->language->get('button_saved_listings');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_combination'] = $this->language->get('column_combination');
		$data['column_sku'] = $this->language->get('column_sku');
		$data['column_amazon_sku'] = $this->language->get('column_amazon_sku');
		$data['column_sku_variant'] = $this->language->get('column_sku_variant');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/amazon_listing_edit.tpl', $data));
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
			$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('openbay/amazon');
		$this->model_openbay_amazon->deleteProduct($product_id);
		$this->response->redirect($this->url->link('openbay/amazon_listing/create', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, 'SSL'));
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
			$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		$this->load->model('openbay/amazon');

		$links = $this->model_openbay_amazon->getProductLinks($product_id);
		foreach ($links as $link) {
			$this->model_openbay_amazon->removeProductLink($link['amazon_sku']);
		}
		$this->model_openbay_amazon->deleteProduct($product_id);
		$this->session->data['success'] = $this->language->get('text_links_removed');

		$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, 'SSL'));
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
			$json = array(
				'data' => '',
				'error' => $error,
			);
		} else {
			$json = array(
				'data' => $this->model_openbay_amazon_listing->search($this->request->post['search_string'], $this->request->post['marketplace']),
				'error' => '',
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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
			$json = array(
				'data' => '',
				'error' => $error,
			);
		} else {
			$best_price = $this->model_openbay_amazon_listing->getBestPrice($this->request->post['asin'], $this->request->post['condition'], $this->request->post['marketplace']);

			if ($best_price) {
				$json = array(
					'data' => $best_price,
					'error' => '',
				);
			} else {
				$json = array(
					'data' => '',
					'error' => $this->language->get('error_amazon_price'),
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getProductByAsin() {
		$this->load->model('openbay/amazon_listing');

		$data = $this->model_openbay_amazon_listing->getProductByAsin($this->request->post['asin'], $this->request->post['market']);

		$json = array(
			'title' => (string)$data['ItemAttributes']['Title'],
			'img' => (!isset($data['ItemAttributes']['SmallImage']['URL']) ? '' : $data['ItemAttributes']['SmallImage']['URL'])
		);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
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