<?php
class ControllerExtensionOpenbayAmazonProduct extends Controller {
	public function index() {
		$this->load->language('catalog/product');
		$this->load->language('extension/openbay/amazon_listing');

		$data = $this->language->all();

		$this->load->model('extension/openbay/amazon');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->document->addScript('view/javascript/openbay/js/openbay.js');
		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/amazon', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_amazon'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/amazon_listing/create', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/amazon_product', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_title_advanced'),
		);

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
			die('No product id');
		}

		if (isset($this->request->get['sku'])) {
			$variation = $this->request->get['sku'];
		} else {
			$variation = '';
		}
		$data['variation'] = $variation;
		$data['errors'] = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$data_array = $this->request->post;

			$this->model_extension_openbay_amazon->saveProduct($product_id, $data_array);

			if ($data_array['upload_after'] === 'true') {
				$upload_result = $this->uploadItems();
				if ($upload_result['status'] == 'ok') {
					$this->session->data['success'] = $this->language->get('text_uploaded');
					$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, true));
				} else {
					$data['errors'][] = Array('message' => $upload_result['error_message']);
				}
			} else {
				$this->session->data['success'] = $this->language->get('text_saved_local');
				$this->response->redirect($this->url->link('extension/openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, true));
			}
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$saved_listing_data = $this->model_extension_openbay_amazon->getProduct($product_id, $variation);
		if (empty($saved_listing_data)) {
			$listing_saved = false;
		} else {
			$listing_saved = true;
		}

		$errors = $this->model_extension_openbay_amazon->getProductErrors($product_id);
		foreach($errors as $error) {
			$error['message'] =  'Error for SKU: "' . $error['sku'] . '" - ' . $this->formatUrlsInText($error['message']);
			$data['errors'][] = $error;
		}
		if (!empty($errors)) {
			$data['has_listing_errors'] = true;
		} else {
			$data['has_listing_errors'] = false;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$data['listing_name'] = $product_info['name'] . " : " . $product_info['model'];
		$data['listing_sku'] = $product_info['sku'];
		$data['listing_url'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, true);

		if ($listing_saved) {
			$data['edit_product_category'] = $saved_listing_data['category'];
		} else {
			$data['edit_product_category'] = '';
		}

		$data['amazon_categories'] = array();

		$amazon_templates = $this->openbay->amazon->getCategoryTemplates();

		foreach($amazon_templates as $template) {
			$template = (array)$template;
			$category_data = array(
				'friendly_name' => $template['friendly_name'],
				'name' => $template['name'],
				'template' => $template['xml']
			);
			$data['amazon_categories'][] = $category_data;
		}

		if ($listing_saved) {
			$data['template_parser_url'] = $this->url->link('extension/openbay/amazon_product/parseTemplateAjax&edit_id=' . $product_id, 'token=' . $this->session->data['token'], true);
		} else {
			$data['template_parser_url'] = $this->url->link('extension/openbay/amazon_product/parseTemplateAjax&product_id=' . $product_id, 'token=' . $this->session->data['token'], true);
		}

		$data['url_remove_errors'] = $this->url->link('extension/openbay/amazon_product/removeErrors', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, true);
		$data['cancel_url'] = $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, true);
		$data['saved_listings_url'] = $this->url->link('extension/openbay/amazon/savedListings', 'token=' . $this->session->data['token'], true);
		$data['main_url'] = $this->url->link('extension/openbay/amazon_product', 'token=' . $this->session->data['token'] . $url, true);
		$data['token'] = $this->session->data['token'];
		$data['no_image'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if ($this->openbay->addonLoad('openstock')) {
			$this->load->model('extension/module/openstock');
			$data['options'] = $this->model_extension_module_openstock->getVariants($product_id);
		} else {
			$data['options'] = array();
		}

		$data['marketplaces'] = array(
			array('name' => $this->language->get('text_germany'), 'id' => 'A1PA6795UKMFR9', 'code' => 'de'),
			array('name' => $this->language->get('text_france'), 'id' => 'A13V1IB3VIYZZH', 'code' => 'fr'),
			array('name' => $this->language->get('text_italy'), 'id' => 'APJ6JRA9NG5V4', 'code' => 'it'),
			array('name' => $this->language->get('text_spain'), 'id' => 'A1RKKUPIHCS9HS', 'code' => 'es'),
			array('name' => $this->language->get('text_united_kingdom'), 'id' => 'A1F83G8C2ARO7P', 'code' => 'uk'),
		);

		$marketplace_mapping = array(
			'uk' => 'A1F83G8C2ARO7P',
			'de' => 'A1PA6795UKMFR9',
			'fr' => 'A13V1IB3VIYZZH',
			'it' => 'APJ6JRA9NG5V4',
			'es' => 'A1RKKUPIHCS9HS',
		);

		if ($this->config->get('openbay_amazon_default_listing_marketplace')) {
			$data['default_marketplaces'] = array($marketplace_mapping[$this->config->get('openbay_amazon_default_listing_marketplace')]);
		} else {
			$data['default_marketplaces'] = array();
		}

		$data['saved_marketplaces'] = isset($saved_listing_data['marketplaces']) ? (array)unserialize($saved_listing_data['marketplaces']) : false;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/amazon_listing_advanced', $data));
	}

	public function removeErrors() {
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
			$this->response->redirect($this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'] . $url, true));
		}
		$this->load->model('extension/openbay/amazon');
		$this->model_extension_openbay_amazon->removeAdvancedErrors($product_id);
		$this->session->data['success'] = 'Errors removed';
		$this->response->redirect($this->url->link('extension/openbay/amazon_product', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . $url, true));
	}

	public function deleteSaved() {
		if (!isset($this->request->get['product_id']) || !isset($this->request->get['var'])) {
			return;
		}

		$this->load->model('extension/openbay/amazon');
		$this->model_extension_openbay_amazon->deleteSaved($this->request->get['product_id'], $this->request->get['var']);
	}

	public function uploadSaved() {
		ob_start();
		$json = json_encode($this->uploadItems());
		ob_clean();

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
	}

	private function uploadItems() {
		$this->load->language('extension/openbay/amazon_listing');
		$this->load->model('extension/openbay/amazon');
		$logger = new Log('amazon_product.log');

		$logger->write('Uploading process started . ');

		$saved_products = $this->model_extension_openbay_amazon->getSavedProductsData();

		if (empty($saved_products)) {
			$logger->write('No saved listings found. Uploading canceled . ');
			$result['status'] = 'error';
			$result['error_message'] = 'No saved listings. Nothing to upload. Aborting . ';
			return $result;
		}

		foreach($saved_products as $saved_product) {
			$product_data_decoded = (array)json_decode($saved_product['data']);

			$catalog = defined(HTTPS_CATALOG) ? HTTPS_CATALOG : HTTP_CATALOG;
			$response_data = array("response_url" => $catalog . 'index.php?route=extension/openbay/amazon/product');
			$category_data = array('category' => (string)$saved_product['category']);
			$fields_data = array('fields' => (array)$product_data_decoded['fields']);

			$mp_array = !empty($saved_product['marketplaces']) ? (array)unserialize($saved_product['marketplaces']) : array();
			$marketplaces_data = array('marketplaces' => $mp_array);

			$product_data = array_merge($category_data, $fields_data, $response_data, $marketplaces_data);
			$insertion_response = $this->openbay->amazon->insertProduct($product_data);

			$logger->write("Uploading product with data:" . print_r($product_data, true) . "
				Got response:" . print_r($insertion_response, true));

			if (!isset($insertion_response['status']) || $insertion_response['status'] == 'error') {
				$details = isset($insertion_response['info']) ? $insertion_response['info'] : 'Unknown';
				$result['error_message'] = sprintf($this->language->get('error_upload_failed'), $saved_product['product_sku'], $details);
				$result['status'] = 'error';
				break;
			}
			$logger->write('Product upload success');
			$this->model_extension_openbay_amazon->setProductUploaded($saved_product['product_id'], $insertion_response['insertion_id'], $saved_product['sku']);
		}

		if (!isset($result['status'])) {
			$result['status'] = 'ok';
			$logger->write('Uploading process completed successfully . ');
		} else {
			$logger->write('Uploading process failed with message: ' . $result['error_message']);
		}
		return $result;
	}

	public function parseTemplateAjax() {
		$this->load->model('tool/image');
		
		$log = new Log('amazon_product.log');

		$json = array();

		if (isset($this->request->get['xml'])) {
			$request = array('template' => $this->request->get['xml'], 'version' => 2);
			$response = $this->openbay->amazon->call("productv2/GetTemplateXml", $request);
			if ($response) {
				$template = $this->openbay->amazon->parseCategoryTemplate($response);
				if ($template) {
					$variation = isset($this->request->get['sku']) ? $this->request->get['sku'] : '';

					if (isset($this->request->get['product_id'])) {
						$template['fields'] = $this->fillDefaultValues($this->request->get['product_id'], $template['fields'], $variation);
					} elseif (isset($this->request->get['edit_id'])) {
						$template['fields'] = $this->fillSavedValues($this->request->get['edit_id'], $template['fields'], $variation);
					}

					foreach($template['fields'] as $key => $field) {
						if ($field['accepted']['type'] == 'image') {
							if (empty($field['value'])) {
								$template['fields'][$key]['thumb'] = '';
							} else {
								$template['fields'][$key]['thumb'] = $this->model_tool_image->resize(str_replace(HTTPS_CATALOG . 'image/', '', $field['value']), 100, 100);
							}
						}
					}

					$result = array(
						"category" => $template['category'],
						"fields" => $template['fields'],
						"tabs" => $template['tabs']
					);
				} else {
					$json_decoded = json_decode($response);
					if ($json_decoded) {
						$result = $json_decoded;
					} else {
						$result = array('status' => 'error');
						$log->write("admin/openbay/amazon_product/parseTemplateAjax failed to parse template response: " . $response);
					}
				}
			} else {
				$log->write("admin/openbay/amazon_product/parseTemplateAjax failed calling productv2/GetTemplateXml with params: " . print_r($request, true));
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($result));
	}

	private function fillDefaultValues($product_id, $fields_array, $var = '') {
		$this->load->model('catalog/product');
		$this->load->model('setting/setting');
		$this->load->model('extension/openbay/amazon');

		$openbay_settings = $this->model_setting_setting->getSetting('openbay_amazon');

		$product_info = $this->model_catalog_product->getProduct($product_id);
		$product_info['description'] = trim(utf8_encode(strip_tags(html_entity_decode($product_info['description']), "<br>")));
		$product_info['image'] = HTTPS_CATALOG . 'image/' . $product_info['image'];

		$tax_added = isset($openbay_settings['openbay_amazon_listing_tax_added']) ? $openbay_settings['openbay_amazon_listing_tax_added'] : 0;
		$default_condition =  isset($openbay_settings['openbay_amazon_listing_default_condition']) ? $openbay_settings['openbay_amazon_listing_default_condition'] : '';
		$product_info['price'] = number_format($product_info['price'] + $tax_added / 100 * $product_info['price'], 2, '.', '');

		$defaults = array(
			'sku' => $product_info['sku'],
			'title' => $product_info['name'],
			'quantity' => $product_info['quantity'],
			'standardprice' => $product_info['price'],
			'description' => $product_info['description'],
			'mainimage' => $product_info['image'],
			'currency' => $this->config->get('config_currency'),
			'shippingweight' => number_format($product_info['weight'], 2, '.', ''),
			'conditiontype' => $default_condition,
		);

		$this->load->model('localisation/weight_class');
		$weight_class = $this->model_localisation_weight_class->getWeightClass($product_info['weight_class_id']);
		if (!empty($weight_class)) {
			$defaults['shippingweightunitofmeasure'] = $weight_class['unit'];
		}

		$this->load->model('catalog/manufacturer');
		$manufacturer = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
		if (!empty($manufacturer)) {
			$defaults['manufacturer'] = $manufacturer['name'];
			$defaults['brand'] = $manufacturer['name'];
		}

		$product_images = $this->model_catalog_product->getProductImages($product_id);
		$image_index = 1;
		foreach($product_images as $product_image) {
			$defaults['pt' . $image_index] = HTTPS_CATALOG . 'image/' . $product_image['image'];
			$image_index ++;
		}

		if (!empty($product_info['upc'])) {
			$defaults['type'] = 'UPC';
			$defaults['value'] = $product_info['upc'];
		} else if (!empty($product_info['ean'])) {
			$defaults['type'] = 'EAN';
			$defaults['value'] = $product_info['ean'];
		}

		$meta_keywords = explode(',', $product_info['meta_keyword']);
		foreach ($meta_keywords as $index => $meta_keyword) {
			$defaults['searchterms' . $index] = trim($meta_keyword);
		}

		if ($var !== '' && $this->openbay->addonLoad('openstock')) {
			$this->load->model('tool/image');
			$this->load->model('extension/module/openstock');
			$option_stocks = $this->model_extension_module_openstock->getVariants($product_id);

			$option = null;
			foreach ($option_stocks as $option_iterator) {
				if ($option_iterator['sku'] === $var) {
					$option = $option_iterator;
					break;
				}
			}

			if ($option != null) {
				$defaults['sku'] = $option['sku'];
				$defaults['quantity'] = $option['stock'];
				$defaults['standardprice'] = number_format($option['price'] + $tax_added / 100 * $option['price'], 2, '.', '');
				$defaults['shippingweight'] = number_format($option['weight'], 2, '.', '');

				if (!empty($option['image'])) {
					$defaults['mainimage'] = HTTPS_CATALOG . 'image/' . $option['image'];
				}
			}
		}

		if ($defaults['shippingweight'] <= 0) {
			unset($defaults['shippingweight']);
			unset($defaults['shippingweightunitofmeasure']);
		}

		$filled_array = array();

		foreach($fields_array as $field) {

			$value_array = array('value' => '');

			if (isset($defaults[strtolower($field['name'])])) {
				$value_array = array('value' => $defaults[strtolower($field['name'])]);
			}

			$filled_item = array_merge($field, $value_array);

			$filled_array[] = $filled_item;
		}
		return $filled_array;
	}

	private function fillSavedValues($product_id, $fields_array, $var = '') {

		$this->load->model('extension/openbay/amazon');
		$saved_listing = $this->model_extension_openbay_amazon->getProduct($product_id, $var);

		$decoded_data = (array)json_decode($saved_listing['data']);
		$saved_fields = (array)$decoded_data['fields'];

		//Show current quantity instead of last uploaded
		$saved_fields['Quantity'] = $this->model_extension_openbay_amazon->getProductQuantity($product_id, $var);

		$filled_array = array();

		foreach($fields_array as $field) {
			$value_array = array('value' => '');

			if (isset($saved_fields[$field['name']])) {
				$value_array = array('value' => $saved_fields[$field['name']]);
			}

			$filled_item = array_merge($field, $value_array);

			$filled_array[] = $filled_item;
		}

		return $filled_array;
	}

	public function resetPending() {
		$this->db->query("UPDATE `" . DB_PREFIX . "amazon_product` SET `status` = 'saved' WHERE `status` = 'uploaded'");
	}

	private function validateForm() {
		return true;
	}

	private function formatUrlsInText($text) {
		$regex_url = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
		preg_match_all($regex_url, $text, $matches);
		$used_patterns = array();
		foreach($matches[0] as $pattern) {
			if (!array_key_exists($pattern, $used_patterns)) {
				$used_patterns[$pattern]=true;
				$text = str_replace($pattern, "<a target='_blank' href=" . $pattern . ">" . $pattern . "</a>", $text);
			}
		}
		return $text;
	}
}