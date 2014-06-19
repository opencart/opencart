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
		$data = $this->request->post;

		// validation
		if (!isset($data['title']) || empty($data['title']) || strlen($data['title']) > 255) {
			if (strlen($data['title']) > 255) {
				$this->error['title'] = 'Title is too long';
			} else {
				$this->error['title'] = 'Title is missing';
			}
		}

		if (!isset($data['description']) || empty($data['description'])) {
			$this->error['title'] = 'Description is missing';
		}

		if (!isset($data['price']) || empty($data['price'])) {
			$this->error['price'] = 'Price is missing';
		}

		if (!isset($data['category_id']) || empty($data['category_id']) || $data['category_id'] == 0) {
			$this->error['category_id'] = 'Category is not selected';
		}

		if (isset($data['style_1']) && !empty($data['style_1'])) {
			if (preg_match('/[^\p{L}\p{Nd}\p{Zs}]/u', $data['style_1']) == 1) {
				$this->error['style_1'] = 'Style 1 tag is not valid';
			}
		}

		if (isset($data['style_2']) && !empty($data['style_2'])) {
			if (preg_match('/[^\p{L}\p{Nd}\p{Zs}]/u', $data['style_2']) == 1) {
				$this->error['style_2'] = 'Style 2 tag is not valid';
			}
		}

		if (!$this->error) {
			// process the request
			$response = $this->openbay->etsy->call('product/listing/create', 'POST', $data);

			if (isset($response['data']['error'])) {
				$this->response->setOutput(json_encode($response['data']));
			} else {
				$this->response->setOutput(json_encode($response['results'][0]));
			}
		} else {
			$this->response->setOutput(json_encode(array('error' => $this->error)));
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

	public function edit() {

	}
}