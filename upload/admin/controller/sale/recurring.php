<?php
class ControllerSaleRecurring extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('sale/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/recurring');

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_order_recurring_id'])) {
			$filter_order_recurring_id = $this->request->get['filter_order_recurring_id'];
		} else {
			$filter_order_recurring_id = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_reference'])) {
			$filter_reference = $this->request->get['filter_reference'];
		} else {
			$filter_reference = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
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
			$filter_date_added = null;
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
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		$filter_data = array(
			'filter_order_recurring_id' => $filter_order_recurring_id,
			'filter_order_id'           => $filter_order_id,
			'filter_reference'  => $filter_reference,
			'filter_customer'           => $filter_customer,
			'filter_status'             => $filter_status,
			'filter_date_added'         => $filter_date_added,
			'order'                     => $order,
			'sort'                      => $sort,
			'start'                     => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                     => $this->config->get('config_limit_admin'),
		);

		$recurrings_total = $this->model_sale_recurring->getTotalRecurrings($filter_data);

		$results = $this->model_sale_recurring->getRecurrings($filter_data);

		$data['recurrings'] = array();

		foreach ($results as $result) {
			$date_added = date($this->language->get('date_format_short'), strtotime($result['date_added']));

			$data['recurrings'][] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'order_link'         => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
				'reference'          => $result['reference'],
				'customer'           => $result['customer'],
				'status'             => $result['status'],
				'date_added'         => $date_added,
				'view'               => $this->url->link('sale/recurring/info', 'token=' . $this->session->data['token'] . '&order_recurring_id=' . $result['order_recurring_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
		$data['entry_reference'] = $this->language->get('entry_reference');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_action'] = $this->language->get('entry_action');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_view'] = $this->language->get('button_view');

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

		$data['sort_order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_recurring_id' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_id' . $url, 'SSL');
		$data['sort_reference'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.reference' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.date_added' . $url, 'SSL');

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
		$pagination->url = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&page={page}' . $url, 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($recurrings_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($recurrings_total - $this->config->get('config_limit_admin'))) ? $recurrings_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $recurrings_total, ceil($recurrings_total / $this->config->get('config_limit_admin')));

		$data['filter_order_recurring_id'] = $filter_order_recurring_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_reference'] = $filter_reference;
		$data['filter_customer'] = $filter_customer;
		$data['filter_status'] = $filter_status;
		$data['filter_date_added'] = $filter_date_added;

		$data['statuses'] = array(
			'0' => '',
			'1' => $this->language->get('text_status_inactive'),
			'2' => $this->language->get('text_status_active'),
			'3' => $this->language->get('text_status_suspended'),
			'4' => $this->language->get('text_status_cancelled'),
			'5' => $this->language->get('text_status_expired'),
			'6' => $this->language->get('text_status_pending'),
		);

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/recurring_list.tpl', $data));
	}

	public function info() {
		$this->load->model('sale/recurring');
		$this->load->model('sale/order');
		$this->load->model('catalog/product');

		$this->language->load('sale/recurring');

		$order_recurring = $this->model_sale_recurring->getRecurring($this->request->get['order_recurring_id']);

		if ($order_recurring) {
			$order = $this->model_sale_order->getOrder($order_recurring['order_id']);

			$this->document->setTitle($this->language->get('heading_title'));

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
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL')
			);

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

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_transactions'] = $this->language->get('text_transactions');
			$data['text_cancel_confirm'] = $this->language->get('text_cancel_confirm');

			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
			$data['entry_reference'] = $this->language->get('entry_reference');
			$data['entry_customer'] = $this->language->get('entry_customer');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_type'] = $this->language->get('entry_type');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_recurring_description'] = $this->language->get('entry_recurring_description');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			$data['entry_amount'] = $this->language->get('entry_amount');
			$data['entry_cancel_payment'] = $this->language->get('entry_cancel_payment');
			$data['entry_recurring'] = $this->language->get('entry_recurring');
			$data['entry_payment_method'] = $this->language->get('entry_payment_method');
			$data['entry_date_added'] = $this->language->get('entry_date_added');

			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['order_recurring_id'] = $order_recurring['order_recurring_id'];
			$data['product'] = $order_recurring['product_name'];
			$data['quantity'] = $order_recurring['product_quantity'];
			$data['status'] = $order_recurring['status'];
			$data['reference'] = $order_recurring['reference'];
			$data['recurring_description'] = $order_recurring['recurring_description'];
			$data['recurring_name'] = $order_recurring['recurring_name'];

			$data['order_id'] = $order['order_id'];
			$data['order_href'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'], 'SSL');

			$data['customer'] = $order['customer'];
			$data['email'] = $order['email'];
			$data['payment_method'] = $order['payment_method'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order['date_added']));

			$data['options'] = array();

			if ($order['customer_id']) {
				$data['customer_href'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $order['customer_id'], 'SSL');
			} else {
				$data['customer_href'] = '';
			}

			if ($order_recurring['recurring_id'] != '0') {
				$data['recurring'] = $this->url->link('catalog/recurring/edit', 'token=' . $this->session->data['token'] . '&recurring_id=' . $order_recurring['recurring_id'], 'SSL');
			} else {
				$data['recurring'] = '';
			}

			$data['transactions'] = array();
			$transactions = $this->model_sale_recurring->getRecurringTransactions($order_recurring['order_recurring_id']);

			foreach ($transactions as $transaction) {
				$data['transactions'][] = array(
					'date_added' => $transaction['date_added'],
					'type'       => $transaction['type'],
					'amount'     => $this->currency->format($transaction['amount'], $order['currency_code'], $order['currency_value'])
				);
			}

			$data['return'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->request->get['token'];

			$data['buttons'] = $this->load->controller('payment/' . $order['payment_code'] . '/recurringButtons');

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/recurring_info.tpl', $data));
		} else {
			return new Action('error/not_found');
		}
	}
}