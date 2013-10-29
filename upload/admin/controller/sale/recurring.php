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

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' :: '
		);

		$data = array(
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

		$profiles_total = $this->model_sale_recurring->getTotalProfiles($data);

		$results = $this->model_sale_recurring->getProfiles($data);

		$this->data['profiles'] = array();

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_view'),
				'href' => $this->url->link('sale/recurring/info', 'token=' . $this->session->data['token'] . '&order_recurring_id=' . $result['order_recurring_id'] . $url, 'SSL')
			);

			$date_created = date($this->language->get('date_format_short'), strtotime($result['created']));
			$order_link = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'], 'SSL');

			$this->data['profiles'][] = array(
				'order_recurring_id' => $result['order_recurring_id'],
				'order_id'           => $result['order_id'],
				'order_link'         => $order_link,
				'profile_reference'  => $result['profile_reference'],
				'customer'           => $result['customer'],
				'status'             => $result['status'],
				'date_created'       => $date_created,
				'action'             => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_filter'] = $this->language->get('text_filter');

		$this->data['entry_order_id'] = $this->language->get('entry_order_id');
		$this->data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
		$this->data['entry_payment_reference'] = $this->language->get('entry_payment_reference');
		$this->data['entry_customer'] = $this->language->get('entry_customer');
		$this->data['entry_date_created'] = $this->language->get('entry_date_created');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_action'] = $this->language->get('entry_action');

		$this->data['token'] = $this->session->data['token'];

		$this->data['statuses'] = array(
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

		$this->data['sort_order_recurring'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_recurring_id' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.order_id' . $url, 'SSL');
		$this->data['sort_payment_reference'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.profile_reference' . $url, 'SSL');
		$this->data['sort_customer'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, 'SSL');
		$this->data['sort_created'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.created' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&sort=or.status' . $url, 'SSL');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
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

		$this->data['filter_order_recurring_id'] = $filter_order_recurring_id;
		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_payment_reference'] = $filter_payment_reference;
		$this->data['filter_customer'] = $filter_customer;
		$this->data['filter_created'] = $filter_created;
		$this->data['filter_status'] = $filter_status;

		$pagination = new Pagination();
		$pagination->total = $profiles_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . '&page={page}' . $url, 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/recurring_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function info() {
		$this->load->model('sale/recurring');
		$this->load->model('sale/order');
		$this->load->model('catalog/product');
		$this->language->load('sale/recurring');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		$order_recurring = $this->model_sale_recurring->getProfile($order_recurring_id);

		if ($order_recurring) {

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

			$this->data['breadcrumbs'] = array();

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);

			$this->data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL'),
				'separator' => ' :: '
			);

			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];
			} else {
				$this->data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$this->data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$this->data['success'] = '';
			}

			$order = $this->model_sale_order->getOrder($order_recurring['order_id']);

			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['entry_order_id'] = $this->language->get('entry_order_id');
			$this->data['entry_order_recurring'] = $this->language->get('entry_order_recurring');
			$this->data['entry_payment_reference'] = $this->language->get('entry_payment_reference');
			$this->data['entry_customer'] = $this->language->get('entry_customer');
			$this->data['entry_date_created'] = $this->language->get('entry_date_created');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_type'] = $this->language->get('entry_type');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_profile_description'] = $this->language->get('entry_profile_description');
			$this->data['entry_product'] = $this->language->get('entry_product');
			$this->data['entry_quantity'] = $this->language->get('entry_quantity');
			$this->data['entry_amount'] = $this->language->get('entry_amount');
			$this->data['entry_cancel_payment'] = $this->language->get('entry_cancel_payment');
			$this->data['entry_profile'] = $this->language->get('entry_profile');
			$this->data['entry_payment_type'] = $this->language->get('entry_payment_type');
			$this->data['text_transactions'] = $this->language->get('text_transactions');
			$this->data['text_cancel'] = $this->language->get('text_cancel');
			$this->data['text_return'] = $this->language->get('text_return');
			$this->data['text_cancel_confirm'] = $this->language->get('text_cancel_confirm');

			$this->data['return'] = $this->url->link('sale/recurring', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$this->data['transactions'] = $this->model_sale_recurring->getProfileTransactions($order_recurring['order_recurring_id']);

			$this->data['order_recurring_id'] = $order_recurring['order_recurring_id'];
			$this->data['order_id'] = $order['order_id'];
			$this->data['order_href'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'], 'SSL');
			$this->data['customer'] = $order['customer'];

			$this->data['token'] = $this->request->get['token'];

			$this->data['product'] = $order_recurring['product_name'];
			$this->data['quantity'] = $order_recurring['product_quantity'];
			$this->data['options'] = array();

			if ($order['customer_id']) {
				$this->data['customer_href'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $order['customer_id'], 'SSL');
			} else {
				$this->data['customer_href'] = '';
			}

			$this->data['email'] = $order['email'];
			$this->data['status'] = $order_recurring['status'];
			$this->data['date_created'] = date($this->language->get('date_format_short'), strtotime($order['date_added']));
			$this->data['profile_reference'] = $order_recurring['profile_reference'];
			$this->data['profile_description'] = $order_recurring['profile_description'];
			$this->data['profile_name'] = $order_recurring['profile_name'];
			$this->data['payment_method'] = $order['payment_method'];

			if($order_recurring['status_id'] == 1 || $order_recurring['status_id'] == 2){
				if(!empty($order['payment_code']) && $this->hasAction('payment/' . $order['payment_code'] . '/recurringCancel') == true){
					$this->data['cancel_link'] = $this->url->link('payment/'.$order['payment_code'].'/recurringCancel', 'order_recurring_id='.$this->request->get['order_recurring_id'].'&token='.$this->request->get['token'], 'SSL');
				}else{
					$this->data['cancel_link'] = '';
				}
			}else{
				$this->data['cancel_link'] = '';
			}

			if ($order_recurring['profile_id'] != '0') {
				$this->data['profile'] = $this->url->link('catalog/profile/update', 'token=' . $this->session->data['token'] . '&profile_id=' . $order_recurring['profile_id'], 'SSL');
			} else {
				$this->data['profile'] = '';
			}

			$this->data['transactions'] = array();
			$transactions = $this->model_sale_recurring->getProfileTransactions($order_recurring['order_recurring_id']);

			foreach ($transactions as $transaction) {
				$this->data['transactions'][] = array(
					'created' => $transaction['created'],
					'type' => $transaction['type'],
					'amount' => $this->currency->format($transaction['amount'], $order['currency_code'], $order['currency_value'])
				);
			}

			$this->template = 'sale/recurring_info.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render());
		} else {
			return $this->forward('error/not_found');
		}
	}
}
?>