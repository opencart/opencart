<?php
namespace Opencart\Application\Controller\Extension\Opencart\Report;
class ProductViewed extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('extension/opencart/report/product_viewed');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('report_product_viewed', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/report/product_viewed', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/report/product_viewed', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=report');

		if (isset($this->request->post['report_product_viewed_status'])) {
			$data['report_product_viewed_status'] = $this->request->post['report_product_viewed_status'];
		} else {
			$data['report_product_viewed_status'] = $this->config->get('report_product_viewed_status');
		}

		if (isset($this->request->post['report_product_viewed_sort_order'])) {
			$data['report_product_viewed_sort_order'] = $this->request->post['report_product_viewed_sort_order'];
		} else {
			$data['report_product_viewed_sort_order'] = $this->config->get('report_product_viewed_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/report/product_viewed_form', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/report/product_viewed')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
		
	public function report() {
		$this->load->language('extension/opencart/report/product_viewed');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$data['reset'] = $this->url->link('extension/opencart/report/product_viewed/reset', 'user_token=' . $this->session->data['user_token']);

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination'),
			'limit' => $this->config->get('config_pagination')
		];

		$data['products'] = [];

		$this->load->model('extension/opencart/report/product');

		$product_viewed_total = $this->model_extension_opencart_report_product->getTotalProductViews();

		$product_total = $this->model_extension_opencart_report_product->getTotalProductsViewed();

		$results = $this->model_extension_opencart_report_product->getProductsViewed($filter_data);

		foreach ($results as $result) {
			if ($result['viewed']) {
				$percent = round($result['viewed'] / $product_viewed_total * 100, 2);
			} else {
				$percent = 0;
			}

			$data['products'][] = [
				'name'    => $result['name'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent . '%'
			];
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $product_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination'),
			'url'   => $this->url->link('extension/opencart/report/product_viewed/report', 'user_token=' . $this->session->data['user_token'] . '&code=product_viewed&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($product_total - $this->config->get('config_pagination'))) ? $product_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $product_total, ceil($product_total / $this->config->get('config_pagination')));

		$this->response->setOutput($this->load->view('extension/opencart/report/product_viewed', $data));
	}

	public function reset() {
		$this->load->language('extension/opencart/report/product_viewed');

		if (!$this->user->hasPermission('modify', 'extension/report/product_viewed')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('extension/opencart/report/product');

			$this->model_extension_opencart_report_product->reset();

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$this->response->redirect($this->url->link('extension/opencart/report/product_viewed/report', 'user_token=' . $this->session->data['user_token'] . '&code=product_viewed'));
	}
}