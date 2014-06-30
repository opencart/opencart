<?php
class ControllerOpenbayEtsyProduct extends Controller {
	private $error;

	public function create() {
		$data = $this->load->language('openbay/etsy_create');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$this->document->setTitle($this->language->get('text_title'));
		$this->document->addScript('view/javascript/openbay/faq.js');

		$data['action']   = $this->url->link('openbay/etsy_product/create', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel']   = $this->url->link('extension/openbay/itemList', 'token=' . $this->session->data['token'], 'SSL');
		$data['token']    = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_title'),
		);

		$product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);

		$this->load->model('tool/image');

		if (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$product_info['image_url'] = $this->model_tool_image->resize($product_info['image'], 800, 800);
			$product_info['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$product_info['image_url'] = '';
			$product_info['thumb'] = '';
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

		$setting = array();

		$setting['who_made'] = $this->openbay->etsy->getSetting('who_made');
		if(is_array($setting['who_made'])) { ksort($setting['who_made']); }

		$setting['when_made'] = $this->openbay->etsy->getSetting('when_made');
		if(is_array($setting['when_made'])) { ksort($setting['when_made']); }

		$setting['recipient'] = $this->openbay->etsy->getSetting('recipient');
		if(is_array($setting['recipient'])) { ksort($setting['recipient']); }

		$setting['occasion'] = $this->openbay->etsy->getSetting('occasion');
		if(is_array($setting['occasion'])) { ksort($setting['occasion']); }

		$setting['top_categories'] = $this->openbay->etsy->getSetting('top_categories');
		if(is_array($setting['top_categories'])) { ksort($setting['top_categories']); }

		$setting['state'] = array('active', 'draft');

		$data['setting'] = $setting;

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/etsy_create.tpl', $data));

	}

	public function createSubmit() {
		$this->load->model('openbay/etsy_product');

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

		if (!isset($data['category_id']) || empty($data['category_id']) || $data['category_id'] == 0) {
			$this->error['category_id'] = $this->language->get('error_category');
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

		if (!$this->error) {
			// process the request
			$response = $this->openbay->etsy->call('product/listing/create', 'POST', $data);

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['data']['results'][0]));
				$this->model_openbay_etsy_product->addLink($data['product_id'], $response['data']['results'][0]['listing_id']);
			}
		} else {
			$this->response->setOutput(json_encode(array('error' => $this->error)));
		}
	}

	public function addImage() {
		$this->load->language('openbay/etsy_create');

		$data = $this->request->post;

		if (!isset($data['image']) || empty($data['image'])) {
			$this->error['image'] = $this->language->get('error_no_img_url');
		}

		if (!isset($data['listing_id']) || empty($data['listing_id'])) {
			$this->error['listing_id'] = $this->language->get('error_no_listing_id');
		}

		if (!$this->error) {
			$response = $this->openbay->etsy->call('product/listing/'.(int)$data['listing_id'].'/image', 'POST', $data);

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['data']['results'][0]));
			}
		}
	}

	public function getCategory() {
		$data = $this->request->post;

		$categories = $this->openbay->etsy->call('product/category/getCategory?tag='.$data['tag'], 'GET');

		$this->response->setOutput(json_encode($categories));
	}

	public function getSubCategory() {
		$data = $this->request->post;

		$categories = $this->openbay->etsy->call('product/category/findAllTopCategoryChildren?tag='.$data['tag'], 'GET');

		$this->response->setOutput(json_encode($categories));
	}

	public function getSubSubCategory() {
		$data = $this->request->post;

		$categories = $this->openbay->etsy->call('product/category/findAllSubCategoryChildren?sub_tag='.$data['sub_tag'], 'GET');

		$this->response->setOutput(json_encode($categories));
	}

	public function addLink() {
		$this->load->language('openbay/etsy_links');
		$this->load->model('openbay/etsy_product');

		$data = $this->request->post;

		if (!isset($data['product_id'])) {
			echo json_encode(array('error' => $this->language->get('error_product_id')));
			die();
		}

		if (!isset($data['etsy_id'])) {
			echo json_encode(array('error' => $this->language->get('error_etsy_id')));
			die();
		}

		$this->model_openbay_etsy_product->addLink($data['product_id'], $data['etsy_id'], 1);

		$this->response->setOutput(json_encode(array('error' => false)));
	}

	public function deleteLink() {
		$this->load->language('openbay/etsy_links');
		$this->load->model('openbay/etsy_product');

		$data = $this->request->post;

		if (!isset($data['etsy_link_id'])) {
			echo json_encode(array('error' => $this->language->get('error_link_id')));
			die();
		}

		$this->model_openbay_etsy_product->deleteLink($data['etsy_link_id']);

		$this->response->setOutput(json_encode(array('error' => false)));
	}

	public function itemLinks() {
		$this->load->model('openbay/etsy_product');

		$data = $this->load->language('openbay/etsy_links');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_etsy'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/etsy_product/itemLinks', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
		);

		$data['return']       = $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL');
		//$data['edit_url']     = $this->url->link('openbay/ebay/edit', 'token=' . $this->session->data['token'] . '&product_id=', 'SSL');
		//$data['validation']   = $this->openbay->ebay->validate();
		$data['token']        = $this->session->data['token'];

		$total_linked = $this->model_openbay_etsy_product->totalLinked();

		if(isset($this->request->get['page'])){
			$page = (int)$this->request->get['page'];
		}else{
			$page = 1;
		}

		if(isset($this->request->get['limit'])){
			$limit = (int)$this->request->get['limit'];
		}else{
			$limit = 100;
		}

		$pagination = new Pagination();
		$pagination->total = $total_linked;
		$pagination->page = $page;
		$pagination->limit = 100;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('openbay/etsy/itemLinks', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['items'] = $this->model_openbay_etsy_product->loadLinked($limit, $page);

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/etsy_links.tpl', $data));
	}
}