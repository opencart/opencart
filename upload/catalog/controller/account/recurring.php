<?php
class ControllerAccountRecurring extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/recurring', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->language('account/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/recurring', 'language=' . $this->config->get('config_language') . $url)
		);

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['recurrings'] = array();

		$this->load->model('account/recurring');

		$recurring_total = $this->model_account_recurring->getTotalOrderRecurrings();

		$results = $this->model_account_recurring->getOrderRecurrings(($page - 1) * 10, 10);

		foreach ($results as $result) {
			if ($result['status']) {
				$status = $this->language->get('text_status_' . $result['status']);
			} else {
				$status = '';
			}

			$data['recurrings'][] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'product'            => $result['product_name'],
				'status'             => $status,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'               => $this->url->link('account/recurring/info', 'language=' . $this->config->get('config_language') . '&order_recurring_id=' . $result['order_recurring_id']),
			);
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $recurring_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('account/recurring', 'language=' . $this->config->get('config_language') . '&page={page}')
		));

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language'));

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/recurring_list', $data));
	}

	public function info() {
		$this->load->language('account/recurring');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/recurring/info', 'language=' . $this->config->get('config_language') . '&order_recurring_id=' . $order_recurring_id);

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('account/recurring');

		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);

		if ($recurring_info) {
			$this->document->setTitle($this->language->get('text_recurring'));

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/recurring', 'language=' . $this->config->get('config_language') . $url)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_recurring'),
				'href' => $this->url->link('account/recurring/info', 'language=' . $this->config->get('config_language') . '&order_recurring_id=' . $this->request->get['order_recurring_id'] . $url)
			);

			$data['order_recurring_id'] = (int)$this->request->get['order_recurring_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($recurring_info['date_added']));

			if ($recurring_info['status']) {
				$data['status'] = $this->language->get('text_status_' . $recurring_info['status']);
			} else {
				$data['status'] = '';
			}

			$data['payment_method'] = $recurring_info['payment_method'];

			$data['order_id'] = $recurring_info['order_id'];
			$data['product_name'] = $recurring_info['product_name'];
			$data['product_quantity'] = $recurring_info['product_quantity'];
			$data['recurring_description'] = $recurring_info['recurring_description'];
			$data['reference'] = $recurring_info['reference'];

			// Transactions
			$data['transactions'] = array();

			$results = $this->model_account_recurring->getOrderRecurringTransactions($this->request->get['order_recurring_id']);

			foreach ($results as $result) {
				$data['transactions'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'type'       => $result['type'],
					'amount'     => $this->currency->format($result['amount'], $recurring_info['currency_code'])
				);
			}

			$data['order'] = $this->url->link('account/order/info', 'language=' . $this->config->get('config_language') . '&order_id=' . $recurring_info['order_id']);
			$data['product'] = $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $recurring_info['product_id']);

			$data['recurring'] = $this->load->controller('extension/recurring/' . $recurring_info['payment_code']);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/recurring_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_recurring'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/recurring', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_recurring'),
				'href' => $this->url->link('account/recurring/info', 'language=' . $this->config->get('config_language') . '&order_recurring_id=' . $order_recurring_id)
			);

			$data['continue'] = $this->url->link('account/recurring', 'language=' . $this->config->get('config_language'));

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}
}
