<?php
class ControllerExtensionOpenbayEtsyProduct extends Controller {
	private $error;

	public function create() {
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data = $this->load->language('extension/openbay/etsy_create');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['action']   = $this->url->link('extension/openbay/etsy_product/create', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']   = $this->url->link('marketplace/openbay/items', 'user_token=' . $this->session->data['user_token'], true);
		$data['user_token']    = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_etsy'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy_product/create', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

		$this->load->model('tool/image');

		if (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$product_info['image_url'] = $this->model_tool_image->resize($product_info['image'], 800, 800);
			$product_info['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$product_info['image_url'] = '';
			$product_info['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		// Images
		if (isset($this->request->get['product_id'])) {
			$product_images = $this->model_catalog_product->getProductImages($this->request->get['product_id']);
		} else {
			$product_images = array();
		}

		$data['product_images'] = array();

		foreach ($product_images as $product_image) {
			if (is_file(DIR_IMAGE . $product_image['image'])) {
				$image = $product_image['image'];
			} else {
				$image = '';
			}

			$product_info['product_images'][] = array(
				'image_url'  => $this->model_tool_image->resize($image, 800, 800),
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $product_image['sort_order']
			);
		}

		$data['product'] = $product_info;
		$data['product']['description_raw'] = trim(strip_tags(html_entity_decode($data['product']['description'], ENT_QUOTES, 'UTF-8')));

		$setting = array();

		$setting['who_made'] = $this->openbay->etsy->getSetting('who_made');
		if (is_array($setting['who_made'])) {
			ksort($setting['who_made']);
		}

		$setting['when_made'] = $this->openbay->etsy->getSetting('when_made');
		if (is_array($setting['when_made'])) {
			ksort($setting['when_made']);
		}

		$setting['recipient'] = $this->openbay->etsy->getSetting('recipient');
		if (is_array($setting['recipient'])) {
			ksort($setting['recipient']);
		}

		$setting['occasion'] = $this->openbay->etsy->getSetting('occasion');
		if (is_array($setting['occasion'])) {
			ksort($setting['occasion']);
		}

		$setting['state'] = array('active', 'draft');

		$data['setting'] = $setting;

		if ($product_info['quantity'] > 999) {
			$this->error['warning'] = sprintf($this->language->get('error_stock_max'), $product_info['quantity']);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if ($this->openbay->addonLoad('openstock') && $product_info['has_option'] == 1) {
			$data['error_variant'] = $this->language->get('error_variant');
		} else {
			$data['error_variant'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy_create', $data));
	}

	public function createSubmit() {
		$this->load->language('extension/openbay/etsy_create');
		$this->load->model('extension/openbay/etsy_product');

		$data = $this->request->post;

		// validation
		if (!isset($data['title']) || empty($data['title']) || strlen($data['title']) > 255) {
			if (strlen($data['title']) > 255) {
				$this->error['title'] = $this->language->get('error_title_length');
			} else {
				$this->error['title'] = $this->language->get('error_title_missing');
			}
		}

		if (!isset($data['description']) || empty($data['description'])) {
			$this->error['title'] = $this->language->get('error_desc_missing');
		}

		if (!isset($data['price']) || empty($data['price'])) {
			$this->error['price'] = $this->language->get('error_price_missing');
		}

		if (!isset($data['taxonomy_id']) || empty($data['taxonomy_id']) || $data['taxonomy_id'] == 0) {
			$this->error['taxonomy_id'] = $this->language->get('error_category');
		}

		if (isset($data['tags']) && count($data['tags']) > 13) {
			$this->error['tags'] = $this->language->get('error_tags');
		}

		if (isset($data['materials']) && count($data['materials']) > 13) {
			$this->error['materials'] = $this->language->get('error_materials');
		}

		if (isset($data['style_1']) && !empty($data['style_1'])) {
			if (preg_match('/[^\p{L}\p{Nd}\p{Zs}]/u', $data['style_1']) == 1) {
				$this->error['style_1'] = $this->language->get('error_style_1_tag');
			}
		}

		if (isset($data['style_2']) && !empty($data['style_2'])) {
			if (preg_match('/[^\p{L}\p{Nd}\p{Zs}]/u', $data['style_2']) == 1) {
				$this->error['style_2'] = $this->language->get('error_style_2_tag');
			}
		}

		if ($data['quantity'] > 999) {
			$this->error['quantity'] = sprintf($this->language->get('error_stock_max'), $data['quantity']);
		}

		if (isset($data['product_image']) && count($data['product_image']) > 4) {
			$this->error['images'] = sprintf($this->language->get('error_image_max'), count($data['product_image'])+1);
		}

		if (!$this->error) {
			// process the request
			$response = $this->openbay->etsy->call('v1/etsy/product/listing/create/', 'POST', $data);

			$this->response->addHeader('Content-Type: application/json');

			if (isset($response['data']['results'][0]['listing_id'])) {
				$this->model_extension_openbay_etsy_product->addLink($data['product_id'], $response['data']['results'][0]['listing_id'], 1);
			}

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['data']['results'][0]));
			}
		} else {
			$this->response->setOutput(json_encode(array('error' => $this->error)));
		}
	}

	public function edit() {
		$data = $this->load->language('extension/openbay/etsy_edit');

		$this->load->model('extension/openbay/etsy_product');
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['action']   = $this->url->link('extension/openbay/etsy_product/editSubmit', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel']   = $this->url->link('marketplace/openbay/items', 'user_token=' . $this->session->data['user_token'], true);
		$data['user_token']    = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_etsy'),
		);
		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy_product/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $this->request->get['product_id'], true),
			'text' => $this->language->get('heading_title'),
		);

		$links = $this->openbay->etsy->getLinks($this->request->get['product_id'], 1, 1);

		$data['listing'] = $this->openbay->etsy->getEtsyItem($links[0]['etsy_item_id']);

		$data['etsy_item_id'] = $links[0]['etsy_item_id'];
		$data['product_id'] = $this->request->get['product_id'];

		$setting['state'] = array('active', 'inactive', 'draft');

		$data['setting'] = $setting;

		if ($data['listing']['state'] == 'edit') {
			$data['listing']['state'] = 'inactive';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy_edit', $data));
	}

	public function editSubmit() {
		$this->load->language('extension/openbay/etsy_edit');
		$this->load->model('extension/openbay/etsy_product');

		$data = $this->request->post;

		// validation
		if (!isset($data['title']) || empty($data['title']) || strlen($data['title']) > 255) {
			if (strlen($data['title']) > 255) {
				$this->error['title'] = $this->language->get('error_title_length');
			} else {
				$this->error['title'] = $this->language->get('error_title_missing');
			}
		}

		if (!isset($data['description']) || empty($data['description'])) {
			$this->error['title'] = $this->language->get('error_desc_missing');
		}

		if (!isset($data['price']) || empty($data['price'])) {
			$this->error['price'] = $this->language->get('error_price_missing');
		}

		if (!isset($data['state']) || empty($data['state'])) {
			$this->error['state'] = $this->language->get('error_state_missing');
		}

		if (!$this->error) {
			// process the request
			$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . $data['etsy_item_id'] . '/update/', 'POST', $data);

			$this->response->addHeader('Content-Type: application/json');

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['data']['results'][0]));
			}
		} else {
			$this->response->setOutput(json_encode(array('error' => $this->error)));
		}
	}

	public function addImage() {
		$this->load->language('extension/openbay/etsy_create');

		$data = $this->request->post;

		if (!isset($data['image']) || empty($data['image'])) {
			$this->error['image'] = $this->language->get('error_no_img_url');
		}

		if (!isset($data['listing_id']) || empty($data['listing_id'])) {
			$this->error['listing_id'] = $this->language->get('error_no_listing_id');
		}

		if (!$this->error) {
			$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . (int)$data['listing_id'] . '/image/', 'POST', $data);

			$this->response->addHeader('Content-Type: application/json');

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['data']['results'][0]));
			}
		}
	}

	public function getCategories() {
		$categories = $this->cache->get('etsy_categories');

		if (!$categories) {
			$response = $this->openbay->etsy->call('v1/etsy/product/taxonomy/', 'GET');

			if (isset($response['header_code']) && $response['header_code'] == 200) {
				$categories = $this->formatCategories($response['data']);

				$this->cache->set('etsy_categories', $categories);
			}
		}

		$response = array();
		$parent_categories = array();
		$last_id = 0;

		if (isset($this->request->get['id_path']) && $this->request->get['id_path'] != '' && $this->request->get['id_path'] != 0) {
			$id_path_parts = explode(',', $this->request->get['id_path']);


			foreach ($id_path_parts as $id_path) {
				$parent_categories[] = $categories[$id_path]['name'];

				$categories = $categories[$id_path]['children'];

				$last_id = $id_path;
			}
		}

		if (empty($categories)) {
			$final_category = true;
		} else {
			foreach ($categories as $id => $category) {
				$response[$id] = array(
					'name' => $category['name'],
					'id_path' => $category['id_path'],
					'children_count' => (is_array($category['children']) ? count($category['children']) : 0),
				);
			}

			$final_category = false;
		}


		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array('data' => $response, 'parent_text' => implode(' > ', $parent_categories), 'final_category' => $final_category, 'last_id' => $last_id)));
	}

	private function formatCategories($category_data) {
		$response = array();

		foreach ($category_data as $category) {
			$response[$category['id']] = array(
				'name' => $category['name'],
				'id_path' => implode(',', $category['full_path_taxonomy_ids']),
				'children' => (isset($category['children']) && !empty($category['children']) ? $this->formatCategories($category['children']) : ''),
			);
		}

		return $response;
	}

	public function addLink() {
		$this->load->language('extension/openbay/etsy_links');
		$this->load->model('extension/openbay/etsy_product');
		$this->load->model('catalog/product');

		$data = $this->request->post;

		if (!isset($data['product_id'])) {
			echo json_encode(array('error' => $this->language->get('error_product_id')));
			die();
		}

		if (!isset($data['etsy_id'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy_id')));
			die();
		}

		$links = $this->openbay->etsy->getLinks($data['product_id'], 1);

		if ($links != false) {
			echo json_encode(array('error' => $this->language->get('error_link_exists')));
			die();
		}

		$product = $this->model_catalog_product->getProduct($data['product_id']);

		if (!$product) {
			echo json_encode(array('error' => $this->language->get('error_product')));
			die();
		}

		if ($product['quantity'] <= 0) {
			echo json_encode(array('error' => $this->language->get('error_stock')));
			die();
		}

		// check the etsy item exists
		$get_response = $this->openbay->etsy->getEtsyItem($data['etsy_id']);

		if (isset($get_response['data']['error'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy') . $get_response['data']['error']));
			die();
		} else {
			if ((int)$get_response['quantity'] != (int)$product['quantity']) {
				// if the stock is different than the item being linked update the etsy stock level
				$update_response = $this->openbay->etsy->updateListingStock($data['etsy_id'], $product['quantity'], $get_response['state']);

				if (isset($update_response['data']['error'])) {
					echo json_encode(array('error' => $this->language->get('error_etsy') . $update_response['data']['error']));
					die();
				}
			}
		}

		$this->model_extension_openbay_etsy_product->addLink($data['product_id'], $data['etsy_id'], 1);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array('error' => false)));
	}

	public function deleteLink() {
		$this->load->language('extension/openbay/etsy_links');

		$data = $this->request->post;

		if (!isset($data['etsy_link_id'])) {
			echo json_encode(array('error' => $this->language->get('error_link_id')));
			die();
		}

		$this->openbay->etsy->deleteLink($data['etsy_link_id']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode(array('error' => false)));
	}

	public function links() {
		$this->load->model('extension/openbay/etsy_product');

		$data = $this->load->language('extension/openbay/etsy_links');

		$data['cancel'] = $this->url->link('marketplace/openbay/items', 'user_token=' . $this->session->data['user_token'], true);

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_etsy'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy_product/itemLinks', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$data['return']       = $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true);
		//$data['edit_url']     = $this->url->link('extension/openbay/ebay/edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=', true);
		//$data['validation']   = $this->openbay->ebay->validate();
		$data['user_token']        = $this->session->data['user_token'];

		$total_linked = $this->model_extension_openbay_etsy_product->totalLinked();

		if (isset($this->request->get['page'])){
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_limit_admin');

		$pagination = new Pagination();
		$pagination->total = $total_linked;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('extension/openbay/etsy/itemLinks', 'user_token=' . $this->session->data['user_token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_linked) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_linked - $limit)) ? $total_linked : ((($page - 1) * $limit) + $limit), $total_linked, ceil($total_linked / $limit));

		$data['items'] = $this->model_extension_openbay_etsy_product->loadLinked($limit, $page);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy_links', $data));
	}

	public function listings() {
		$data = $this->load->language('extension/openbay/etsy_listings');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/js/faq.js');

		$this->load->model('extension/openbay/etsy_product');

		$data['user_token'] = $this->session->data['user_token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('marketplace/openbay', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('text_etsy'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay/etsy_product/itemLinks', 'user_token=' . $this->session->data['user_token'], true),
			'text' => $this->language->get('heading_title'),
		);

		$filter = array();

		if (!isset($this->request->get['status'])) {
			$filter['status'] = 'active';
		} else {
			$filter['status'] = $this->request->get['status'];
		}

		if (!isset($this->request->get['page'])) {
			$filter['page'] = 1;
		} else {
			$filter['page'] = $this->request->get['page'];
		}

		if (isset($this->request->get['keywords'])) {
			$filter['keywords'] = $this->request->get['keywords'];
		}

		$filter['limit'] = (int)$this->config->get('config_limit_admin');

		$data['filter'] = $filter;

		$response = $this->openbay->etsy->call('v1/etsy/product/listing/all/?' . http_build_query($filter), 'GET');

		unset($filter['page']);

		if (isset($response['data']['error'])) {
			$data['listings'] = array();
			$data['pagination'] = '';
			$data['results'] = '';
			$this->error['warning'] = $this->language->get('error_etsy') . $response['data']['error'];
		} else {
			$listings = array();

			foreach($response['data']['results'] as $listing) {
				$product_link = $this->openbay->etsy->getLinkedProduct($listing['listing_id']);

				$actions = array();

				if ($filter['status'] == 'inactive') {
					$actions[] = 'activate_item';
				}

				if ($filter['status'] == 'active') {
					$actions[] = 'end_item';
					$actions[] = 'deactivate_item';
				}

				if ($filter['status'] == 'active' && empty($product_link)) {
					$actions[] = 'add_link';
				}

				if (!empty($product_link)) {
					$actions[] = 'delete_link';
				}

				if ($product_link != false) {
					$listings[] = array('link' => $product_link, 'listing' => $listing, 'actions' => $actions);
				} else {
					$listings[] = array('link' => '', 'listing' => $listing, 'actions' => $actions);
				}
			}

			$data['listings'] = $listings;

			$pagination = new Pagination();
			$pagination->total = $response['data']['count'];
			$pagination->page = $response['data']['pagination']['effective_page'];
			$pagination->limit = $response['data']['pagination']['effective_limit'];
			$pagination->url = $this->url->link('extension/openbay/etsy_product/listings', 'user_token=' . $this->session->data['user_token'] . '&page={page}&' . http_build_query($filter), true);

			$data['pagination'] = $pagination->render();
			$data['results'] = sprintf($this->language->get('text_pagination'), ($response['data']['count']) ? (($response['data']['pagination']['effective_page'] - 1) * $response['data']['pagination']['effective_limit']) + 1 : 0, ((($response['data']['pagination']['effective_page'] - 1) * $response['data']['pagination']['effective_limit']) > ($response['data']['count'] - $response['data']['pagination']['effective_limit'])) ? $response['data']['count'] : ((($response['data']['pagination']['effective_page'] - 1) * $response['data']['pagination']['effective_limit']) + $response['data']['pagination']['effective_limit']), $response['data']['count'], ceil($response['data']['count'] / $response['data']['pagination']['effective_limit']));
		}

		$data['success'] = '';

		if (isset($this->request->get['item_ended'])) {
			$data['success'] = $this->language->get('text_item_ended');
		}

		if (isset($this->request->get['item_activated'])) {
			$data['success'] = $this->language->get('text_item_activated');
		}

		if (isset($this->request->get['item_deactivated'])) {
			$data['success'] = $this->language->get('text_item_deactivated');
		}

		if (isset($this->request->get['link_added'])) {
			$data['success'] = $this->language->get('text_link_added');
		}

		if (isset($this->request->get['link_deleted'])) {
			$data['success'] = $this->language->get('text_link_deleted');
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/openbay/etsy_listings', $data));
	}

	public function endListing() {
		$this->load->language('extension/openbay/etsy_links');

		$data = $this->request->post;

		if (!isset($data['etsy_item_id'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy_id')));
			die();
		}

		$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . (int)$data['etsy_item_id'] . '/delete/', 'POST', array());

		if (isset($response['data']['error'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy') . $response['data']['error']));
			die();
		} else {
			$linked_item = $this->openbay->etsy->getLinkedProduct($data['etsy_item_id']);

			if ($linked_item != false) {
				$this->openbay->etsy->deleteLink($linked_item['etsy_listing_id']);
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode(array('error' => false)));
		}
	}

	public function deactivateListing() {
		$this->load->language('extension/openbay/etsy_links');

		$data = $this->request->post;

		if (!isset($data['etsy_item_id'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy_id')));
			die();
		}

		$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . (int)$data['etsy_item_id'] . '/inactive/', 'POST', array());

		if (isset($response['data']['error'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy') . $response['data']['error']));
			die();
		} else {
			$linked_item = $this->openbay->etsy->getLinkedProduct($data['etsy_item_id']);

			if ($linked_item != false) {
				$this->openbay->etsy->deleteLink($linked_item['etsy_listing_id']);
			}

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode(array('error' => false)));
		}
	}

	public function activateListing() {
		$this->load->language('extension/openbay/etsy_links');

		$data = $this->request->post;

		$this->response->addHeader('Content-Type: application/json');

		if (!isset($data['etsy_item_id'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy_id')));
			die();
		}

		$response = $this->openbay->etsy->call('v1/etsy/product/listing/' . (int)$data['etsy_item_id'] . '/active/', 'POST', array());

		if (isset($response['data']['error'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy') . $response['data']['error']));
			die();
		} else {
			$this->response->setOutput(json_encode(array('error' => false)));
		}
	}
}
