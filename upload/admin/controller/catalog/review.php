<?php
class ControllerCatalogReview extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->addReview($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/review'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_review->editReview($this->request->get['review_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('review_id');

			$this->response->redirect($this->provider->link('catalog/review'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/review');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/review'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('filter_product' => '', 'filter_author' => '', 'filter_status' => '', 'filter_date_added' => '', 'sort' => 'r.date_added', 'order' => 'DESC'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$review_total = $this->model_catalog_review->getTotalReviews($filter_data);

		$results = $this->model_catalog_review->getReviews($filter_data);

		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/review/add');
		$data['delete'] = $this->provider->link('catalog/review/delete');

		$data['reviews'] = array();

		foreach ($results as $result) {
			$data['reviews'][] = array(
				'review_id'  => $result['review_id'],
				'name'       => $result['name'],
				'author'     => $result['author'],
				'rating'     => $result['rating'],
				'status'     => ($result['status']) ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->provider->link('catalog/review/edit', array('review_id' => $result['review_id']))
			);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if ($this->request->hasPost('selected')) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$sorts = array('sort_product' => 'pd.name', 'sort_author' => 'r.author', 'sort_rating' => 'r.rating', 'sort_status' => 'r.status', 'sort_date_added' => 'r.date_added');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/review', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $review_total);

		$data['results'] = $this->load->controller('common/pagination/results', $review_total);

		foreach ($this->provider->getParams() as $key => $value) {
			$data[$key] = $value;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_list', $data));
	}

	protected function getForm() {
		$this->provider->detach('review_id');

		$data['text_form'] = $this->request->hasGet('review_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

		$this->breadcrumbs->setDefaults();

		$this->breadcrumbs->set((string)$data['review_id']);

		$errors = array('warning', 'product', 'author', 'text', 'rating');

		foreach ($errors as $index) {
			$data['error_' . $index] = isset($this->error[$index]) ? $this->error[$index] : '';
		}

		if ($this->request->hasGet('review_id')) {
			$data['action'] = $this->provider->link('catalog/review/edit', array('review_id' => $this->request->get['review_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/review/add');
		}

		$data['cancel'] = $this->provider->link('catalog/review');

		if ($this->request->hasGet('review_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$review_info = $this->model_catalog_review->getReview($this->request->get['review_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		$this->load->model('catalog/product');

		$variables = array(
			'product_id',
			'product',
			'author',
			'text',
			'rating',
			'status'
		);

		foreach ($variables as $index) {
			if ($this->request->hasPost($index)) {
				$data[$index] = $this->request->post[$index];
			} elseif (!empty($review_info)) {
				$data[$index] = $review_info[$index];
			} else {
				$data[$index] = '';
			}
		}

		if ($this->request->hasPost('date_added')) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($review_info)) {
			$data['date_added'] = ($review_info['date_added'] != '0000-00-00 00:00' ? $review_info['date_added'] : date('Y-m-d'));
		} else {
			$data['date_added'] = date('Y-m-d');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['product_id']) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['author']) < 3) || (utf8_strlen($this->request->post['author']) > 64)) {
			$this->error['author'] = $this->language->get('error_author');
		}

		if (utf8_strlen($this->request->post['text']) < 1) {
			$this->error['text'] = $this->language->get('error_text');
		}

		if (!$this->request->hasPost('rating') || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
			$this->error['rating'] = $this->language->get('error_rating');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}