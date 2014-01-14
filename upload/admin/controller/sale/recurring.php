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

		if (isset($this->request->get['filter_payment_reference'])) {
			$filter_payment_reference = $this->request->get['filter_payment_reference'];
		} else {
			$filter_payment_reference = null;
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = null;
		}

		if (isset($this->request->get['filter_created'])) {
			$filter_created = $this->request->get['filter_created'];
		} else {
			$filter_created = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'order_recurring_id';
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

		if (isset($this->request->get['filter_payment_reference'])) {
			$url .= '&filter_payment_reference=' . $this->request->get['filter_payment_reference'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_created'])) {
			$url .= '&filter_created=' . $this->request->get['filter_created'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		$filter_data = array(
			'filter_order_recurring_id' => $filter_order_recurring_id,
			'filter_order_id' => $filter_order_id,
			'filter_payment_reference' => $filter_payment_reference,
			'filter_customer' => $filter_customer,
			'filter_created' => $filter_created,
			'filter_status' => $filter_status,
			'order' => $order,
			'sort' => $sort,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit'),
		);

		$profiles_total = $this->model_sale_recurring->getTotalProfiles($filter_data);

		$results = $this->model_sale_recurring->getProfiles($filter_data);

		$data['profiles'] = array();

		foreach ($results as $result) {
			$date_created = date($this->language->get('date_format_short'), strtotime($result['created']));

			$data['profiles'][] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'order_link'         => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL'),
				'profile_reference'  => $result['profile_reference'],
				'customer'           => $result['customer'],
				'status'             => $result['status'],
				'date_created'       => $date_created,
				'view'               => $this->url->link('sale/recurring/info', 'token=' . $this->session->data['token'] . '&order_recurring_id=' . $result['order_recurring_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_filter'] = $this->language->get('text_filter');
		$data['text_view'] = $this->language->get('text_view');

		$data['entry_order_id'] = $this->language->get('entry_order_id');
		$data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
		$data['entry_payment_reference'] = $this->language->get('entry_payment_reference');
		$data['entry_customer'] = $this->language->get('entry_customer');
		$data['entry_date_created'] = $this->language->get('entry_date_created');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_action'] = $this->language->get('entry_action');

		$data['token'] = $this->session->data['token'];

		$data['statuses'] = array(
			'0' => '',
			'1' => $this->language->get('text_status_inactive'),
			'2' => $this->language->get('text_status_active'),
			'3' => $this->language->get('text_status_suspended'),
			'4' => $this->language->get('text_status_cancelled'),
			'5' => $this->language->get('text_status_expired'),
			'6' => $this->language->get('text_status_pending'),
		);

		$url = '';

		if (isset($this->request->get['filter_order_recurring_id'])) {
			$url .= '&filter_order_recurring_id=' . $this->request->get['filter_order_recurring_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_payment_reference'])) {
			$url .= '&filter_payment_reference=' . urlencode(html_entity_decode($this->request->get['filter_payment_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_created'])) {
			$url .= '&filter_created=' . $this->request->get['filter_created'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
		$data['sort_payment_reference'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.profile_reference' . $url, 'SSL');
		$data['sort_customer'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$data['sort_created'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.created' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.status' . $url, 'SSL');

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

		if (isset($this->request->get['filter_payment_reference'])) {
			$url .= '&filter_payment_reference=' . urlencode(html_entity_decode($this->request->get['filter_payment_reference'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_created'])) {
			$url .= '&filter_created=' . $this->request->get['filter_created'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['filter_order_recurring_id'] = $filter_order_recurring_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_payment_reference'] = $filter_payment_reference;
		$data['filter_customer'] = $filter_customer;
		$data['filter_created'] = $filter_created;
		$data['filter_status'] = $filter_status;

		$pagination = new Pagination();
		$pagination->total = $profiles_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&page={page}' . $url, 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($profiles_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($profiles_total - $this->config->get('config_limit_admin'))) ? $profiles_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $profiles_total, ceil($profiles_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/recurring_list.tpl', $data));
	}

	public function info() {
		$this->load->model('sale/recurring');
		$this->load->model('sale/order');
		$this->load->model('catalog/product');

		$this->language->load('sale/recurring');

		$order_recurring = $this->model_sale_recurring->getProfile($this->request->get['order_recurring_id']);

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

			if (isset($this->request->get['filter_payment_reference'])) {
				$url .= '&filter_payment_reference=' . $this->request->get['filter_payment_reference'];
			}

			if (isset($this->request->get['filter_customer'])) {
				$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_created'])) {
				$url .= '&filter_created=' . $this->request->get['filter_created'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
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
			$data['entry_order_id'] = $this->language->get('entry_order_id');
			$data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
			$data['entry_payment_reference'] = $this->language->get('entry_payment_reference');
			$data['entry_customer'] = $this->language->get('entry_customer');
			$data['entry_date_created'] = $this->language->get('entry_date_created');
			$data['entry_status'] = $this->language->get('entry_status');
			$data['entry_type'] = $this->language->get('entry_type');
			$data['entry_email'] = $this->language->get('entry_email');
			$data['entry_profile_description'] = $this->language->get('entry_profile_description');
			$data['entry_product'] = $this->language->get('entry_product');
			$data['entry_quantity'] = $this->language->get('entry_quantity');
			$data['entry_amount'] = $this->language->get('entry_amount');
			$data['entry_cancel_payment'] = $this->language->get('entry_cancel_payment');
			$data['entry_profile'] = $this->language->get('entry_profile');
			$data['entry_payment_type'] = $this->language->get('entry_payment_type');
			$data['text_transactions'] = $this->language->get('text_transactions');
			$data['text_cancel'] = $this->language->get('text_cancel');
			$data['text_return'] = $this->language->get('text_return');
			$data['text_cancel_confirm'] = $this->language->get('text_cancel_confirm');

			$data['order_recurring_id'] = $order_recurring['order_recurring_id'];
			$data['product'] = $order_recurring['product_name'];
			$data['quantity'] = $order_recurring['product_quantity'];
			$data['status'] = $order_recurring['status'];
			$data['profile_reference'] = $order_recurring['profile_reference'];
			$data['profile_description'] = $order_recurring['profile_description'];
			$data['profile_name'] = $order_recurring['profile_name'];

			$data['order_id'] = $order['order_id'];
			$data['order_href'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'], 'SSL');

			$data['customer'] = $order['customer'];
			$data['email'] = $order['email'];
			$data['payment_method'] = $order['payment_method'];
			$data['date_created'] = date($this->language->get('date_format_short'), strtotime($order['date_added']));

			$data['options'] = array();

			if ($order['customer_id']) {
				$data['customer_href'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $order['customer_id'], 'SSL');
			} else {
				$data['customer_href'] = '';
			}

			if ($order_recurring['profile_id'] != '0') {
				$data['profile'] = $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $order_recurring['profile_id'], 'SSL');
			} else {
				$data['profile'] = '';
			}

			$data['transactions'] = array();
			$transactions = $this->model_sale_recurring->getProfileTransactions($order_recurring['order_recurring_id']);

			foreach ($transactions as $transaction) {
				$data['transactions'][] = array(
					'created' => $transaction['created'],
					'type' => $transaction['type'],
					'amount' => $this->currency->format($transaction['amount'], $order['currency_code'], $order['currency_value'])
				);
			}

			$data['return'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->request->get['token'];

			$data['buttons'] = $this->load->controller('payment/' . $order['payment_code'] . '/recurringButtons');
			$data['header'] = $this->load->controller('common/header');
			$data['menu'] = $this->load->controller('common/menu');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/recurring_info.tpl', $data));
		} else {
			return $this->response->forward('error/not_found');
		}
	}
}