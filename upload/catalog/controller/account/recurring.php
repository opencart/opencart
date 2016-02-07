<?php
class ControllerAccountRecurring extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
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
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/recurring', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_empty'] = $this->language->get('text_empty');
		
		$data['column_recurring_id'] = $this->language->get('column_recurring_id');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

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
				'product'            => $result['product'],
				'status'             => $status,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'               => $this->url->link('account/recurring/info', 'order_recurring_id=' . $result['order_recurring_id'], true),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $recurring_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('account/recurring', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['continue'] = $this->url->link('account/account', '', true);

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
			$this->session->data['redirect'] = $this->url->link('account/recurring/info', 'order_recurring_id=' . $order_recurring_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}
		
		$this->load->model('account/recurring');

		$recurring_info = $this->model_account_recurring->getOrderRecurring($this->request->get['order_recurring_id']);

		if ($recurring_info) {
			$this->document->setTitle($this->language->get('text_recurring'));
			
			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home'),
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true),
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/recurring', $url, true),
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_recurring'),
				'href' => $this->url->link('account/recurring/info', 'order_recurring_id=' . $this->request->get['order_recurring_id'] . $url, true),
			);

			$data['heading_title'] = $this->language->get('text_recurring');

			$data['text_recurring_detail'] = $this->language->get('text_recurring_detail');
			$data['text_order_recurring_id'] = $this->language->get('text_order_recurring_id');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_status'] = $this->language->get('text_status');
			$data['text_reference'] = $this->language->get('text_reference');
			$data['text_product'] = $this->language->get('text_product');
			
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_transaction'] = $this->language->get('text_transaction');
			$data['text_recurring_description'] = $this->language->get('text_recurring_description');
			
			$data['text_no_results'] = $this->language->get('text_no_results');
			
			
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_type'] = $this->language->get('column_type');
			$data['column_amount'] = $this->language->get('column_amount');

			$data['order_recurring_id'] = $this->request->get['order_recurring_id'];
			$data['order_id'] = $recurring_info['order_id'];
			$data['recurring_description'] = $recurring_info['recurring_description'];
			$data['recurring_status'] = $recurring_info['recurring_status'];
			$data['payment_method'] = $recurring_info['payment_method'];
			$data['product_name'] = $recurring_info['product_name'];
			$data['quantity'] = $recurring_info['quantity'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($recurring_info['date_added']));
			
			$data['reference'] = $recurring_info['reference'];
			
			$data['product'] = $this->url->link('product/product', 'product_id=' . $recurring['product_id'], true);
			$data['order'] = $this->url->link('account/order/info', 'order_id=' . $recurring['order_id'], true);
			
			// Transactions
			$data['transactions'] = array();
			
			$results = $this->model_account_recurring->getOrderRecurringTransactions($this->request->get['order_recurring_id']);

			foreach ($results as $result) {
				$data['transactions'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'type'       => $result['type'],
					'amount'     =>  $this->currency->format($result['amount'], $recurring_info['currency'])
				);
			}
			
			//$data['buttons'] = $this->load->controller('payment/' . $recurring['payment_code'] . '/recurringButtons');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/recurring_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_recurring'));

			$data['heading_title'] = $this->language->get('text_recurring');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/recurring', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_recurring'),
				'href' => $this->url->link('account/recurring/info', 'order_recurring_id=' . $order_recurring_id, true)
			);

			$data['continue'] = $this->url->link('account/recurring', '', true);

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