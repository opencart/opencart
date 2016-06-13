<?php
class ControllerSaleRecurring extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('sale/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/recurring');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_order_recurring_id'])) {
			$filter_order_recurring_id = $this->request->get['filter_order_recurring_id'];
		} else {
			$filter_order_recurring_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_reference'])) {
			$filter_reference = $this->request->get['filter_reference'];
		} else {
			$filter_reference = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_recurring_id';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . $this->request->get['filter_reference'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['recurrings'] = array();

		$filter_data = array(
			'filter_order_recurring_id' => $filter_order_recurring_id,
			'filter_order_id'           => $filter_order_id,
			'filter_reference'          => $filter_reference,
			'filter_customer'           => $filter_customer,
			'filter_status'             => $filter_status,
			'filter_date_added'         => $filter_date_added,
			'order'                     => $order,
			'sort'                      => $sort,
			'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                     => $this->config->get('config_limit_admin')
		);

		$recurrings_total = $this->model_sale_recurring->getTotalRecurrings($filter_data);

		$results = $this->model_sale_recurring->getRecurrings($filter_data);

		foreach ($results as $result) {
			if ($result['status']) {
				$status	= $this->language->get('text_status_' . $result['status']);
			} else {
				$status = '';
			}
			
			$data['recurrings'][] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'reference'          => $result['reference'],
				'customer'           => $result['customer'],
				'status'             => $status,
				'date_added'         => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'               => $this->url->link('sale/recurring/info', 'token=' . $this->session->data['token'] . '&order_recurring_id=' . $result['order_recurring_id'] . $url, true),
				'order'              => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		
		$data['column_order_recurring_id'] = $this->language->get('column_order_recurring_id');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_reference'] = $this->language->get('column_reference');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['entry_order_recurring_id'] = $this->language->get('entry_order_recurring_id');
		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_reference'] = $this->language->get('entry_reference');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_date_modified'] = $this->language->get('entry_date_modified');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_order_recurring'] = $this->language->get('button_order_recurring');
		$data['button_order'] = $this->language->get('button_order');

		$data['token'] = $this->session->data['token'];

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
		
		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . urlencode(html_entity_decode($this->request->get['filter_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_recurring_id' . $url, true);
		$data['sort_order'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_id' . $url, true);
		$data['sort_reference'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.reference' . $url, true);
		$data['sort_customer'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_status'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.status' . $url, true);
		$data['sort_date_added'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.date_added' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_reference'])) {
			$url .= '&filter_reference=' . urlencode(html_entity_decode($this->request->get['filter_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $recurrings_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&page={page}' . $url, true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($recurrings_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recurrings_total - $this->config->get('config_limit_admin'))) ? $recurrings_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recurrings_total, ceil($recurrings_total / $this->config->get('config_limit_admin')));

		$data['filter_order_recurring_id'] = $filter_order_recurring_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_reference'] = $filter_reference;
		$data['filter_customer'] = $filter_customer;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['recurring_statuses'] = array();
		
		$data['recurring_statuses'][0] = array(
			'text'  => '',
			'value' => 0
		);
			
		for ($i = 1; $i <= 6; $i++) {
			$data['recurring_statuses'][$i] = array(
				'text'  => $this->language->get('text_status_' . $i),
				'value' => 1
			);		
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/recurring_list', $data));
	}

	public function info() {
		$this->load->model('sale/recurring');
		
		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}
		
		$order_recurring_info = $this->model_sale_recurring->getRecurring($order_recurring_id);

		if ($order_recurring_info) {
			$this->load->language('sale/recurring');
		
			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_recurring_detail'] = $this->language->get('text_recurring_detail');
			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_product_detail'] = $this->language->get('text_product_detail');
			$data['text_transaction'] = $this->language->get('text_transaction');
			$data['text_order_recurring_id'] = $this->language->get('text_order_recurring_id');
			$data['text_reference'] = $this->language->get('text_reference');
			$data['text_recurring_name'] = $this->language->get('text_recurring_name');
			$data['text_recurring_description'] = $this->language->get('text_recurring_description');
			$data['text_recurring_status'] = $this->language->get('text_recurring_status');			
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_customer'] = $this->language->get('text_customer');
			$data['text_email'] = $this->language->get('text_email');
			$data['text_order_status'] = $this->language->get('text_order_status');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_product'] = $this->language->get('text_product');
			$data['text_quantity'] = $this->language->get('text_quantity');
			$data['text_no_results'] = $this->language->get('text_no_results');
			
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_amount'] = $this->language->get('column_amount');
			$data['column_type'] = $this->language->get('column_type');

			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['token'] = $this->request->get['token'];
			
			$url = '';

			if (isset($this->request->get['filter_order_recurring_id'])) {
				$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
			}

			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}

			if (isset($this->request->get['filter_reference'])) {
				$url .= '&filter_reference=' . $this->request->get['filter_reference'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, true)
			);

			$data['cancel'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, true);
			
			// Recurring
			$data['order_recurring_id'] = $order_recurring_info['order_recurring_id'];
			$data['reference'] = $order_recurring_info['reference'];
			$data['recurring_name'] = $order_recurring_info['recurring_name'];
			
			if ($order_recurring_info['recurring_id']) {
				$data['recurring'] = $this->url->link('catalog/recurring/edit', 'token=' . $this->session->data['token'] . '&recurring_id=' . $order_recurring_info['recurring_id'], true);
			} else {
				$data['recurring'] = '';
			}			
			
			$data['recurring_description'] = $order_recurring_info['recurring_description'];
			
			if ($order_recurring_info['status']) {
				$data['recurring_status']= $this->language->get('text_status_' . $order_recurring_info['status']);
			} else {
				$data['recurring_status'] = '';
			}
			
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_recurring_info['order_id']);
			
			$data['payment_method'] = $order_info['payment_method'];
			
			// Order
			$data['order_id'] = $order_info['order_id'];
			$data['order'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order_info['order_id'], true);
			$data['firstname'] = $order_info['firstname'];
			$data['lastname'] = $order_info['lastname'];
			
			if ($order_info['customer_id']) {
				$data['customer'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'], true);
			} else {
				$data['customer'] = '';
			}
		
			$data['email'] = $order_info['email'];
			$data['order_status'] = $order_info['order_status'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			
			// Product
			$data['product'] = $order_recurring_info['product_name'];
			$data['quantity'] = $order_recurring_info['product_quantity'];

			// Transactions
			$data['transactions'] = array();
			
			$transactions = $this->model_sale_recurring->getRecurringTransactions($order_recurring_info['order_recurring_id']);

			foreach ($transactions as $transaction) {
				$data['transactions'][] = array(
					'date_added' => $transaction['date_added'],
					'type'       => $transaction['type'],
					'amount'     => $this->currency->format($transaction['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			$data['buttons'] = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '/recurringButtons');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/recurring_info', $data));
		} else {
			return new Action('error/not_found');
		}
	}
}