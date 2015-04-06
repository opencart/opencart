<?php
class ControllerReportProductViewed extends Controller {
	public function index() {
		$this->load->language('report/product_viewed');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->load->model('report/product');

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$data['products'] = array();

		$product_viewed_total = $this->model_report_product->getTotalProductViews();

		$product_total = $this->model_report_product->getTotalProductsViewed();

		$results = $this->model_report_product->getProductsViewed($filter_data);

		foreach ($results as $result) {
			if ($result['viewed']) {
				$percent = round($result['viewed'] / $product_viewed_total * 100, 2);
			} else {
				$percent = 0;
			}

			$data['products'][] = array(
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent . '%'
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_viewed'] = $this->language->get('column_viewed');
		$data['column_percent'] = $this->language->get('column_percent');

		$data['button_reset'] = $this->language->get('button_reset');

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['reset'] = $this->url->link('report/product_viewed/reset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($product_total - $this->config->get('config_limit_admin'))) ? $product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $product_total, ceil($product_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/product_viewed.tpl', $data));
	}

	public function reset() {
		$this->load->language('report/product_viewed');

		if (!$this->user->hasPermission('modify', 'report/product_viewed')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('report/product');

			$this->model_report_product->reset();

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], 'SSL'));
	}
}