<?php
class ControllerSaleOrder extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('sale/order');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/order');

    	$this->getList();
  	}

  	public function update() {
		$this->load->language('sale/order');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/order');

    	$this->getForm();
  	}

  	public function delete() {
		$this->load->language('sale/order');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('sale/order');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			$filters = array(
				'filter_order_id',
				'filter_name',
				'filter_order_status_id',
				'filter_date_added',
				'filter_total',
				'page',
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}

			$this->redirect(HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url);
    	}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.order_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = NULL;
		}

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = NULL;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = NULL;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = NULL;
		}

		if (isset($this->request->get['filter_total'])) {
			$filter_total = $this->request->get['filter_total'];
		} else {
			$filter_total = NULL;
		}

		$url = '';

		$filters = array(
			'filter_order_id',
			'filter_name',
			'filter_order_status_id',
			'filter_date_added',
			'filter_total',
			'page',
			'sort',
			'order'
		);
		
		foreach($filters as $filter) {
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . $this->request->get[$filter];
			}
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url,
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['invoice'] = HTTPS_SERVER . 'index.php?route=sale/order/invoice&token=' . $this->session->data['token'];
		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=sale/order/insert&token=' . $this->session->data['token'] . $url;
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=sale/order/delete&token=' . $this->session->data['token'] . $url;

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_name'	         => $filter_name,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_date_added'      => $filter_date_added,
			'filter_total'           => $filter_total,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);

		$order_total = $this->model_sale_order->getTotalOrders($data);

		$results = $this->model_sale_order->getOrders($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=sale/order/update&token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url
			);

			$this->data['orders'][] = array(
				'order_id'   => $result['order_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'total'      => $this->currency->format($result['total'], $result['currency'], $result['value']),
				'selected'   => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_missing_orders'] = $this->language->get('text_missing_orders');

		$this->data['column_order'] = $this->language->get('column_order');
    	$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_invoices'] = $this->language->get('button_invoices');
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

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

		$filters = array(
			'filter_order_id',
			'filter_name',
			'filter_order_status_id',
			'filter_date_added',
			'filter_total',
			'page',
		);
		
		foreach($filters as $filter) {
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . $this->request->get[$filter];
			}
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}
		$this->data['sort_order'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=o.order_id&token=' . $this->session->data['token'] . $url;
		$this->data['sort_name'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=name&token=' . $this->session->data['token'] . $url;
		$this->data['sort_status'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=status&token=' . $this->session->data['token'] . $url;
		$this->data['sort_date_added'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=o.date_added&token=' . $this->session->data['token'] . $url;
		$this->data['sort_total'] = HTTPS_SERVER . 'index.php?route=sale/order&sort=o.total&token=' . $this->session->data['token'] . $url;

		$url = '';

		$filters = array(
			'filter_order_id',
			'filter_name',
			'filter_order_status_id',
			'filter_date_added',
			'filter_total',
			'sort',
			'order'
		);
		
		foreach($filters as $filter) {
			if (isset($this->request->get[$filter])) {
				$url .= '&' . $filter . '=' . $this->request->get[$filter];
			}
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url . '&page={page}';

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_order_id'] = $filter_order_id;
		$this->data['filter_name'] = $filter_name;
		$this->data['filter_order_status_id'] = $filter_order_status_id;
		$this->data['filter_date_added'] = $filter_date_added;
		$this->data['filter_total'] = $filter_total;

		$this->load->model('localisation/order_status');

    	$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'sale/order_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
  	}

  	public function getForm() {
		$this->load->model('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_info) {
			$this->load->language('sale/order');

			$this->document->title = $this->language->get('heading_title');

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_wait'] = $this->language->get('text_wait');

			$this->data['column_product'] = $this->language->get('column_product');
			$this->data['column_model'] = $this->language->get('column_model');
			$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
			$this->data['column_total'] = $this->language->get('column_total');
			$this->data['column_download'] = $this->language->get('column_download');
			$this->data['column_filename'] = $this->language->get('column_filename');
			$this->data['column_remaining'] = $this->language->get('column_remaining');
			$this->data['column_date_added'] = $this->language->get('column_date_added');
			$this->data['column_status'] = $this->language->get('column_status');
			$this->data['column_notify'] = $this->language->get('column_notify');
			$this->data['column_comment'] = $this->language->get('column_comment');
			$this->data['column_add_product'] = $this->language->get('column_add_product');

			$this->data['entry_order_id'] = $this->language->get('entry_order_id');
			$this->data['entry_invoice_id'] = $this->language->get('entry_invoice_id');
			$this->data['entry_customer'] = $this->language->get('entry_customer');
			$this->data['entry_customer_group'] = $this->language->get('entry_customer_group');
			$this->data['entry_firstname'] = $this->language->get('entry_firstname');
			$this->data['entry_lastname'] = $this->language->get('entry_lastname');
			$this->data['entry_email'] = $this->language->get('entry_email');
			$this->data['entry_telephone'] = $this->language->get('entry_telephone');
			$this->data['entry_fax'] = $this->language->get('entry_fax');
			$this->data['entry_ip'] = $this->language->get('entry_ip');
			$this->data['entry_store_name'] = $this->language->get('entry_store_name');
			$this->data['entry_store_url'] = $this->language->get('entry_store_url');
			$this->data['entry_date_added'] = $this->language->get('entry_date_added');
			$this->data['entry_comment'] = $this->language->get('entry_comment');
			$this->data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
			$this->data['entry_payment_method'] = $this->language->get('entry_payment_method');
			$this->data['entry_total'] = $this->language->get('entry_total');
			$this->data['entry_order_status'] = $this->language->get('entry_order_status');
			$this->data['entry_company'] = $this->language->get('entry_company');
			$this->data['entry_address_1'] = $this->language->get('entry_address_1');
			$this->data['entry_address_2'] = $this->language->get('entry_address_2');
			$this->data['entry_city'] = $this->language->get('entry_city');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_zone_code'] = $this->language->get('entry_zone_code');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_status'] = $this->language->get('entry_status');
			$this->data['entry_append'] = $this->language->get('entry_append');
			$this->data['entry_notify'] = $this->language->get('entry_notify');
            $this->data['entry_category'] = $this->language->get('entry_category');
			$this->data['entry_product'] = $this->language->get('entry_product');
			$this->data['entry_option'] = $this->language->get('entry_option');
			$this->data['entry_quantity'] = $this->language->get('entry_quantity');
			$this->data['entry_tax'] = $this->language->get('entry_tax');
			
			$this->data['text_none'] = $this->language->get('text_none');

			$this->data['button_invoice'] = $this->language->get('button_invoice');
			$this->data['button_cancel'] = $this->language->get('button_cancel');
    		$this->data['button_generate'] = $this->language->get('button_generate');
			$this->data['button_add_history'] = $this->language->get('button_add_history');
			$this->data['button_update_address'] = $this->language->get('button_update_address');

			$this->data['tab_order'] = $this->language->get('tab_order');
			$this->data['tab_product'] = $this->language->get('tab_product');
			$this->data['tab_history'] = $this->language->get('tab_history');
			$this->data['tab_payment'] = $this->language->get('tab_payment');
			$this->data['tab_shipping'] = $this->language->get('tab_shipping');

			$this->data['token'] = $this->session->data['token'];

			$this->data['language_id'] = $this->config->get('config_language_id');

			$url = '';

			$filters = array(
				'filter_order_id',
				'filter_name',
				'filter_order_status_id',
				'filter_date_added',
				'filter_total',
				'page',
				'sort',
				'order'
			);
			
			foreach($filters as $filter) {
				if (isset($this->request->get[$filter])) {
					$url .= '&' . $filter . '=' . $this->request->get[$filter];
				}
			}

			$this->document->breadcrumbs = array();

			$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text'      => $this->language->get('text_home'),
				'separator' => FALSE
			);

			$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'],
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
			);

			$this->data['invoice'] = HTTPS_SERVER . 'index.php?route=sale/order/invoice&token=' . $this->session->data['token'] . '&order_id=' . (int)$this->request->get['order_id'];
			$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=sale/order&token=' . $this->session->data['token'] . $url;

			$this->data['order_id'] = $this->request->get['order_id'];

			if ($order_info['invoice_id']) {
				$this->data['invoice_id'] = $order_info['invoice_prefix'] . $order_info['invoice_id'];
			} else {
				$this->data['invoice_id'] = '';
			}
			
			if ($order_info['invoice_date']) {
				$this->data['invoice_date'] = $order_info['invoice_date'];
			} else {
				$this->data['invoice_date'] = '';
			}

			$this->data['firstname'] = $order_info['firstname'];
			$this->data['lastname'] = $order_info['lastname'];

			if ($order_info['customer_id']) {
				$this->data['customer'] = HTTPS_SERVER . 'index.php?route=sale/customer/update&token=' . $this->session->data['token'] . '&customer_id=' . $order_info['customer_id'];
			} else {
				$this->data['customer'] = '';
			}

			$this->load->model('sale/customer_group');

			$customer_group_info = $this->model_sale_customer_group->getCustomerGroup($order_info['customer_group_id']);

			if ($customer_group_info) {
				$this->data['customer_group'] = $customer_group_info['name'];
			} else {
				$this->data['customer_group'] = '';
			}
			
			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];
			$this->data['fax'] = $order_info['fax'];
			$this->data['ip'] = $order_info['ip'];
			
			$this->data['store_name'] = $order_info['store_name'];
			$this->data['store_url'] = $order_info['store_url'];
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
			$this->data['comment'] = nl2br($order_info['comment']);
			$this->data['shipping_method'] = $order_info['shipping_method'];
			$this->data['payment_method'] = $order_info['payment_method'];

			$this->load->model('localisation/country');

			$this->data['countries'] = $this->model_localisation_country->getCountries();

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);

			if ($order_status_info) {
				$this->data['order_status'] = $order_status_info['name'];
			} else {
				$this->data['order_status'] = 0;
			}

			$this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value']);

			$this->data['shipping_firstname'] = $order_info['shipping_firstname'];
			$this->data['shipping_lastname'] = $order_info['shipping_lastname'];
			$this->data['shipping_company'] = $order_info['shipping_company'];
			$this->data['shipping_address_1'] = $order_info['shipping_address_1'];
			$this->data['shipping_address_2'] = $order_info['shipping_address_2'];
			$this->data['shipping_city'] = $order_info['shipping_city'];
			$this->data['shipping_postcode'] = $order_info['shipping_postcode'];
			$this->data['shipping_zone'] = $order_info['shipping_zone'];
			$this->data['shipping_zone_code'] = $order_info['shipping_zone_code'];
			$this->data['shipping_zone_id'] = $order_info['shipping_zone_id'];
			$this->data['shipping_country'] = $order_info['shipping_country'];
			$this->data['shipping_country_id'] = $order_info['shipping_country_id'];
			$this->data['payment_firstname'] = $order_info['payment_firstname'];
			$this->data['payment_lastname'] = $order_info['payment_lastname'];
			$this->data['payment_company'] = $order_info['payment_company'];
			$this->data['payment_address_1'] = $order_info['payment_address_1'];
			$this->data['payment_address_2'] = $order_info['payment_address_2'];
			$this->data['payment_city'] = $order_info['payment_city'];
			$this->data['payment_postcode'] = $order_info['payment_postcode'];
			$this->data['payment_zone'] = $order_info['payment_zone'];
			$this->data['payment_zone_code'] = $order_info['payment_zone_code'];
			$this->data['payment_zone_id'] = $order_info['payment_zone_id'];
			$this->data['payment_country'] = $order_info['payment_country'];
			$this->data['payment_country_id'] = $order_info['payment_country_id'];

		    $this->load->model('catalog/category');

			$this->data['categories'] = $this->model_catalog_category->getCategories(0);

			$this->data['products'] = array();

			$products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_sale_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => $option['value']
					);
				}

				$this->data['products'][] = array(
					'order_product_id'  => $product['order_product_id'],
					'product_id'     	=> $product['product_id'],
					'name'    	 		=> $product['name'],
					'model'    			=> $product['model'],
					'option'   			=> $option_data,
					'quantity'			=> $product['quantity'],
					'price'    			=> $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'total'    			=> $this->currency->format($product['total'], $order_info['currency'], $order_info['value']),
					'href'     			=> HTTPS_SERVER . 'index.php?route=catalog/product/update&token=' . $this->session->data['token'] . '&product_id=' . $product['product_id']
				);
			}

			$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

			$this->data['histories'] = array();

			$results = $this->model_sale_order->getOrderHistory($this->request->get['order_id']);

			foreach ($results as $result) {
				$this->data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => nl2br($result['comment']),
					'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no')
				);
			}

			$this->data['downloads'] = array();

			$results = $this->model_sale_order->getOrderDownloads($this->request->get['order_id']);

			foreach ($results as $result) {
				$this->data['downloads'][] = array(
					'name'      => $result['name'],
					'filename'  => $result['mask'],
					'remaining' => $result['remaining']
				);
			}

			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

			if (isset($order_info['order_status_id'])) {
				$this->data['order_status_id'] = $order_info['order_status_id'];
			} else {
				$this->data['order_status_id'] = 0;
			}

			$this->template = 'sale/order_form.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		} else {
			$this->load->language('error/not_found');

			$this->document->title = $this->language->get('heading_title');

			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_not_found'] = $this->language->get('text_not_found');

			$this->document->breadcrumbs = array();

			$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
				'text'      => $this->language->get('text_home'),
				'separator' => FALSE
			);

			$this->document->breadcrumbs[] = array(
				'href'      => HTTPS_SERVER . 'index.php?route=error/not_found&token=' . $this->session->data['token'],
				'text'      => $this->language->get('heading_title'),
				'separator' => ' :: '
			);

			$this->template = 'error/not_found.tpl';
			$this->children = array(
				'common/header',
				'common/footer'
			);

			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		}
  	}

	public function generate() {
		$this->load->model('sale/order');

		$json = array();

		if (isset($this->request->get['order_id'])) {
			$json['invoice_id'] = $this->model_sale_order->generateInvoiceId($this->request->get['order_id']);
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}

	public function history() {
		$this->language->load('sale/order');

		$this->load->model('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = $this->language->get('error_permission');
    	} else {
			$this->model_sale_order->addOrderHistory($this->request->get['order_id'], $this->request->post);

			$json['success'] = $this->language->get('text_success');

			$json['date_added'] = date($this->language->get('date_format_short'));

			$this->load->model('localisation/order_status');

			$order_status_info = $this->model_localisation_order_status->getOrderStatus($this->request->post['order_status_id']);

			if ($order_status_info) {
				$json['order_status'] = $order_status_info['name'];
			} else {
				$json['order_status'] = '';
			}

			if ($this->request->post['notify']) {
				$json['notify'] = $this->language->get('text_yes');
			} else {
				$json['notify'] = $this->language->get('text_no');
			}

			if (isset($this->request->post['comment'])) {
				$json['comment'] = $this->request->post['comment'];
			} else {
				$json['comment'] = '';
			}
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}

	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

  	public function invoice() {
		$this->load->language('sale/order');

		$this->data['title'] = $this->language->get('heading_title');

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}

		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');

		$this->data['text_invoice'] = $this->language->get('text_invoice');

		$this->data['text_order_id'] = $this->language->get('text_order_id');
		$this->data['text_invoice_id'] = $this->language->get('text_invoice_id');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');

		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_comment'] = $this->language->get('column_comment');

		$this->data['logo'] = DIR_IMAGE . $this->config->get('config_logo');

		$this->load->model('sale/order');

		$this->data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}

		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				if ($order_info['invoice_id']) {
					$invoice_id = $order_info['invoice_prefix'] . $order_info['invoice_id'];
				} else {
					$invoice_id = '';
				}
				
				if ($order_info['invoice_date']) {
					$invoice_date = $order_info['invoice_date'];
				} else {
					$invoice_date = '';
				}

				if ($order_info['shipping_address_format']) {
					$format = $order_info['shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if ($order_info['payment_address_format']) {
					$format = $order_info['payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				$product_data = array();

				$products = $this->model_sale_order->getOrderProducts($order_id);

				foreach ($products as $product) {
					$option_data = array();

					$options = $this->model_sale_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $option['value']
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
						'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
					);
				}

				$total_data = $this->model_sale_order->getOrderTotals($order_id);

				$this->data['orders'][] = array(
					'order_id'	       	=> $order_id,
					'invoice_id'       	=> $invoice_id,
					'invoice_date'      => $invoice_date,
					'date_added'       	=> date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       	=> $order_info['store_name'],
					'store_url'        	=> rtrim($order_info['store_url'], '/'),
					'address'          	=> nl2br($this->config->get('config_address')),
					'telephone'        	=> $this->config->get('config_telephone'),
					'fax'              	=> $this->config->get('config_fax'),
					'email'            	=> $this->config->get('config_email'),
					'shipping_address' 	=> $shipping_address,
					'payment_address'  	=> $payment_address,
					'customer_email'   	=> $order_info['email'],
					'ip'   				=> $order_info['ip'],
					'customer_telephone'=> $order_info['telephone'],
					'comment'   	  	=> $order_info['comment'],
					'product'          	=> $product_data,
					'total'            	=> $total_data
				);
			}
		}

		$this->template = 'sale/order_invoice.tpl';

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function category() {
		$this->load->model('catalog/product');

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$product_data = array();

		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);

		foreach ($results as $result) {
			$product_data[] = array(
				'product_id' => $result['product_id'],
				'name'       => $result['name'],
				'model'      => $result['model'],
				'price'      => $this->currency->format($result['price'], $order_info['currency'], False, True)
			);
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($product_data));
	}

    public function product() {
		$this->load->model('catalog/product');

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$option_data = array();

        $this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

		$results = $this->model_catalog_product->getProductOptions($product_id);

		$option_value_data = array();

		foreach ($results as $result) {

			foreach ($result['product_option_value'] as $option_value) {
				$option_value_data[] = array(
					'product_option_value_id'	=> $option_value['product_option_value_id'],
					'language' 					=> $option_value['language'],
					'price'						=> $this->currency->format($option_value['price'], $order_info['currency'], False, True),
					'prefix' 					=> $option_value['prefix']
				);
			}

			$option_data[] = array(
				'product_option_id'	 	=> $result['product_option_id'],
				'language'       		=> $result['language'],
				'product_option_value'  => $option_value_data,
			);
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($option_data));
	}

	public function addProduct() {
		$this->language->load('sale/order');

		$this->load->model('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = $this->language->get('error_permission');
    	} else {

			$this->load->model('catalog/product');

            if (isset($this->request->get['order_id'])) {
				$order_id = (int)$this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

			if (isset($this->request->post['product_id'])) {
				$product_id = (int)$this->request->post['product_id'];
			} else {
				$product_id = 0;
			}

			if (isset($this->request->post['option'])) {
				$options = explode('|', trim($this->request->post['option'], '|'));
			} else {
				$options = array();
			}

			$option_data = array();

			$option_price = 0;

			foreach ($options as $product_option_value_id) {

				$option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "product_option_value_description povd ON (pov.product_option_value_id = povd.product_option_value_id) WHERE pov.product_option_value_id = '" . (int)$product_option_value_id . "' AND pov.product_id = '" . (int)$product_id . "' AND povd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY pov.sort_order");

				if ($option_value_query->num_rows) {
					$option_query = $this->db->query("SELECT pod.name FROM " . DB_PREFIX . "product_option po LEFT JOIN " . DB_PREFIX . "product_option_description pod ON (po.product_option_id = pod.product_option_id) WHERE po.product_option_id = '" . (int)$option_value_query->row['product_option_id'] . "' AND po.product_id = '" . (int)$product_id . "' AND pod.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY po.sort_order");

        			if ($option_value_query->row['prefix'] == '+') {
          				$option_price = $option_price + $option_value_query->row['price'];
        			} elseif ($option_value_query->row['prefix'] == '-') {
          				$option_price = $option_price - $option_value_query->row['price'];
        			}

        			$option_data[] = array(
          				'product_option_value_id' => $product_option_value_id,
          				'name'                    => $option_query->row['name'],
          				'value'                   => $option_value_query->row['name'],
          				'prefix'                  => $option_value_query->row['prefix'],
          				'price'                   => $option_value_query->row['price']
        			);
				}
      		}

            if (isset($this->request->post['quantity'])) {
				$quantity = (int)$this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			/*if (isset($this->request->post['tax']) && $this->request->post['tax']) { // TU
				$tax = (float)$this->request->post['tax'];
			} else {
				$tax = 0;
			}*/

            $result = $this->model_catalog_product->getProduct($product_id);

			if ($result) {
				$tax = $this->model_sale_order->getOrderTax($product_id, $order_id); // TU

				$price = $this->model_sale_order->getProductPrice($order_id, $product_id, $quantity, $result['price']); // TU
				$price = ((float)$price + (float)$option_price); // TU

		        $subtotal = $price * $quantity;

				//if ($taxes) { // TU
					$total = (($tax['rate']/100) * $subtotal) + $subtotal; // TU
				//} else { // TU
					//$total = $subtotal; // TU
				//} // TU

				$order_info = $this->model_sale_order->getOrder($order_id);

				$order_products = $this->model_sale_order->getOrderProducts($order_id);

				$order_total = 0;

				foreach ($order_products as $order_product) {
					//$order_total += $order_product['total'] * $order_product['tax'] / 100; // TU
					$order_total += $order_product['price'] * $order_product['quantity']; // TU
				}

				$product_data = array(
					'product_id'			=> $result['product_id'],
					'name'					=> $result['name'],
					'model'					=> $result['model'],
					'sku'					=> $result['sku'],
					'stock'					=> $result['quantity'],
					'minimum'				=> $result['minimum'],
					'tax_class_id'			=> $result['tax_class_id'],
					'price'					=> $price, //$subtotal, // TU
					'formatted_price'		=> $this->currency->format($price/*$subtotal // TU */, $order_info['currency'], $order_info['value'], True),
					'quantity'				=> $quantity,
					'tax'					=> $tax, // TU
					'currency'              => $order_info['currency'], // TU
					'currency_value'        => $order_info['value'], // TU
					'total'					=> $price * $quantity, // $total, // TU
					'order_total'			=> $this->currency->format($order_total + $price * $quantity/*$total // TU */, $order_info['currency'], $order_info['value'], False),
					'formatted_order_total'	=> $this->currency->format($order_total + $price * $quantity/*$total // TU */, $order_info['currency'], $order_info['value'], True),
					'formatted_total'		=> $this->currency->format($price * $quantity/*$total // TU */, $order_info['currency'], $order_info['value'], True),
					'new_grand_total'		=> $this->currency->format($order_info['total'] + $total, $order_info['currency'], $order_info['value'], False),
					'formatted_grand_total'	=> $this->currency->format($order_info['total'] + $total, $order_info['currency'], $order_info['value'], True),
					'options'				=> $option_data,
					'href'					=> HTTPS_SERVER . 'index.php?route=catalog/product/update&token=' . $this->session->data['token'] . '&product_id=' . $result['product_id']
				);

				$product_data['order_product_id'] = $this->model_sale_order->addProduct($this->request->get['order_id'], $product_data);

				$json['product_data'] = $product_data;

				$json['taxes_data'] = array_slice($this->model_sale_order->getOrderTotals($order_id), 1, -1); // TU

				$json['success'] = $this->language->get('text_success');

			} else {
				$json['error'] = 'no results';
			}

		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}

  	public function removeProduct() {
		$this->language->load('sale/order');

		$this->load->model('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$json['error'] = $this->language->get('error_permission');
    	} else {

			$this->load->model('catalog/product');

			if (isset($this->request->get['order_id'])) {
				$order_id = (int)$this->request->get['order_id'];
			} else {
				$order_id = 0;
			}

            if (isset($this->request->get['order_product_id'])) {
				$order_product_id = (int)$this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

            $order_info = $this->model_sale_order->getOrder($order_id);

			$order_products = $this->model_sale_order->getOrderProducts($order_id);

			$order_total = 0;

			foreach ($order_products as $order_product) {
				if ($order_product['order_product_id'] == $order_product_id) {
					$total = $order_product['total'];
					$tax   = $order_product['tax']; // TU
				}
				$order_total += $order_product['total'];
			}

			$product_data = array(
				'order_product_id'		=> $order_product_id,
				'order_total'			=> $this->currency->format($order_total - $total, $order_info['currency'], $order_info['value'], False),
				'formatted_order_total' => $this->currency->format($order_total - $total, $order_info['currency'], $order_info['value'], True),
				'new_grand_total'		=> $this->currency->format($order_info['total'] - $total * (1 + $tax / 100), $order_info['currency'], $order_info['value'], False), // TU
				'formatted_grand_total'	=> $this->currency->format($order_info['total'] - $total * (1 + $tax / 100), $order_info['currency'], $order_info['value'], True), // TU
				'currency'              => $order_info['currency'], // TU
				'currency_value'        => $order_info['value'] // TU
			);

			$this->model_sale_order->removeProduct($this->request->get['order_id'], $product_data);

			$json['success'] = $this->language->get('text_success');

			$json['product_data'] = $product_data;

			$json['taxes_data'] = array_slice($this->model_sale_order->getOrderTotals($order_id), 1, -1); // TU
		}

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));
  	}

	public function address() {
		$this->language->load('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			return;
		}

		if (isset($this->request->get['type'])) {
			$type = $this->request->get['type'];
		} else {
			return;
		}

		$json = array();

		$this->load->model('sale/order');

		$this->load->model('localisation/country');

		$result = $this->model_localisation_country->getCountry($this->request->post[$type . '_country_id']);

		$this->request->post[$type . '_country'] = $result['name'];

		$this->load->model('localisation/zone');

		$result = $this->model_localisation_zone->getZone($this->request->post[$type . '_zone_id']);

		$this->request->post[$type . '_zone'] = $result['name'];

        if (isset($this->request->get['type']) && $this->request->get['type'] == 'shipping') {
			$this->model_sale_order->updateShippingAddress($order_id, $this->request->post);
		} elseif (isset($this->request->get['type']) && $this->request->get['type'] == 'payment') {
			$this->model_sale_order->updatePaymentAddress($order_id, $this->request->post);
		}

		$json['success'] = $this->language->get('text_success_address');

		$this->load->library('json');

		$this->response->setOutput(Json::encode($json));

	}

	public function zone() {
		$output = '<select name="' . $this->request->get['type'] . '_id">';

		$this->load->model('localisation/zone');

		$results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);

		$selected_name = '';

		foreach ($results as $result) {
			$output .= '<option value="' . $result['zone_id'] . '"';

			if (isset($this->request->get['zone_id']) && ($this->request->get['zone_id'] == $result['zone_id'])) {
				$output .= ' selected="selected"';
				$selected_name = $result['name'];
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$output .= '</select>';
		$output .= '<input type="hidden" id="' .  $this->request->get['type'] . '_name" name="' . $this->request->get['type'] . '" value="' . $selected_name . '" />';

		$this->response->setOutput($output, $this->config->get('config_compression'));
	}

}
?>