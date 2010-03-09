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
		    	
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {  
			$this->model_sale_order->editOrder($this->request->get['order_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
	  		
			$url = '';
				
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
														
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
    	}
    
    	$this->getForm();
  	}
	  
  	public function delete() {
		$this->load->language('sale/order');
	
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('sale/order');
			
    	if (isset($this->request->post['selected']) && ($this->validate())) {
			foreach ($this->request->post['selected'] as $order_id) {
				$this->model_sale_order->deleteOrder($order_id);
			}	
						
			$this->session->data['success'] = $this->language->get('text_success');
	  		
			$url = '';
				
			if (isset($this->request->get['filter_order_id'])) {
				$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
			}
		
			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}

			if (isset($this->request->get['filter_order_status_id'])) {
				$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
			}

			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
			}

			if (isset($this->request->get['filter_total'])) {
				$url .= '&filter_total=' . $this->request->get['filter_total'];
			}
														
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->redirect($this->url->https('sale/order' . $url));
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
				
		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
								
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sale/order' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		$this->data['invoice'] = $this->url->https('sale/order/invoices');	
		$this->data['delete'] = $this->url->https('sale/order/delete' . $url);	

		$this->data['orders'] = array();

		$data = array(
			'filter_order_id'        => $filter_order_id,
			'filter_name'	         => $filter_name, 
			'filter_order_status_id' => $filter_order_status_id, 
			'filter_date_added'      => $filter_date_added,
			'filter_total'           => $filter_total,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * 10,
			'limit'                  => 10
		);
		
		$order_total = $this->model_sale_order->getTotalOrders($data);

		$results = $this->model_sale_order->getOrders($data);
 
    	foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('sale/order/update&order_id=' . $result['order_id'] . $url)
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
		
		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_invoices'] = $this->language->get('button_invoices');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_order'] = $this->url->https('sale/order&sort=o.order_id' . $url);
		$this->data['sort_name'] = $this->url->https('sale/order&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('sale/order&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('sale/order&sort=o.date_added' . $url);
		$this->data['sort_total'] = $this->url->https('sale/order&sort=o.total' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('sale/order' . $url . '&page=%s');
			
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
  
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
		
    	$this->data['text_order_details'] = $this->language->get('text_order_details');
		$this->data['text_contact_details'] = $this->language->get('text_contact_details');
		$this->data['text_address_details'] = $this->language->get('text_address_details');
		$this->data['text_products'] = $this->language->get('text_products');
		$this->data['text_downloads'] = $this->language->get('text_downloads');
		$this->data['text_order_history'] = $this->language->get('text_order_history');
		$this->data['text_update'] = $this->language->get('text_update');
		$this->data['text_order'] = $this->language->get('text_order');
		$this->data['text_date_added'] = $this->language->get('text_date_added');
		$this->data['text_email'] = $this->language->get('text_email');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');
		$this->data['text_shipping_address'] = $this->language->get('text_shipping_address');
    	$this->data['text_shipping_method'] = $this->language->get('text_shipping_method');
    	$this->data['text_payment_address'] = $this->language->get('text_payment_address');
    	$this->data['text_payment_method'] = $this->language->get('text_payment_method');
		$this->data['text_order_comment'] = $this->language->get('text_order_comment');
		$this->data['text_comment'] = $this->language->get('text_comment');
		$this->data['text_status'] = $this->language->get('text_status');
		$this->data['text_notify'] = $this->language->get('text_notify');
		$this->data['text_close'] = $this->language->get('text_close');
  	    	
    	$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_download'] = $this->language->get('column_download');
    	$this->data['column_filename'] = $this->language->get('column_filename');
    	$this->data['column_remaining'] = $this->language->get('column_remaining');
		
    	$this->data['entry_status'] = $this->language->get('entry_status');
    	$this->data['entry_comment'] = $this->language->get('entry_comment');
    	$this->data['entry_notify'] = $this->language->get('entry_notify');

    	$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->data['button_invoice'] = $this->language->get('button_invoice');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['filter_total'])) {
			$url .= '&filter_total=' . $this->request->get['filter_total'];
		}
					
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('sale/order'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}
		
		$order_info = $this->model_sale_order->getOrder($order_id);
		
		if ($order_info) {
			$this->data['action'] = $this->url->https('sale/order/update&order_id=' . (int)$this->request->get['order_id'] . $url);
			$this->data['cancel'] = $this->url->https('sale/order' . $url);
			$this->data['invoice'] = $this->url->https('sale/order/invoice&order_id=' . (int)$this->request->get['order_id']);
			
			$this->data['order_id'] = $order_info['order_id'];
			$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added'])); 
			$this->data['email'] = $order_info['email'];
			$this->data['telephone'] = $order_info['telephone'];
			$this->data['fax'] = $order_info['fax'];
			$this->data['order_comment'] = nl2br($order_info['comment']);
	
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
				'country'   => $order_info['shipping_country']  
			);
		
			$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	  
			$this->data['shipping_method'] = $order_info['shipping_method'];
	
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
				'country'   => $order_info['payment_country']  
			);
		
			$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
	
			$this->data['payment_method'] = $order_info['payment_method'];
			
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
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
					'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
				);
			}
	
			$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
	
			$this->data['historys'] = array();
	
			$results = $this->model_sale_order->getOrderHistory($this->request->get['order_id']);
	
			foreach ($results as $result) {
				$this->data['historys'][] = array(
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
	
			$this->load->model('localisation/order_status');
			
			$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
			
			if (isset($this->request->post['order_status_id'])) {
				$this->data['order_status_id'] = $this->request->post['order_status_id'];
			} elseif (isset($order_info['order_status_id'])) {
				$this->data['order_status_id'] = $order_info['order_status_id'];
			} else {
				$this->data['order_status_id'] = 0;
			}
			
			if (isset($this->request->post['comment'])) {
				$this->data['comment'] = $this->request->post['comment'];
			} else {
				$this->data['comment'] = '';
			}
			
			if (isset($this->request->post['notify'])) {
				$this->data['notify'] = $this->request->post['notify'];
			} else {
				$this->data['notify'] = '';
			}
		
			$this->template = 'sale/order_form.tpl';
			$this->children = array(
				'common/header',	
				'common/footer'
			);
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression')); 
		} 
  	}
	
	public function invoice() {
		$this->load->language('sale/order');

		$this->data['title'] = $this->language->get('heading_title') . ' #' . $this->request->get['order_id'];
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	
		
		$this->data['text_invoice'] = $this->language->get('text_invoice');
    	$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
     	
		$this->data['column_product'] = $this->language->get('column_product');
    	$this->data['column_model'] = $this->language->get('column_model');
    	$this->data['column_quantity'] = $this->language->get('column_quantity');
    	$this->data['column_price'] = $this->language->get('column_price');
    	$this->data['column_total'] = $this->language->get('column_total');
				
		$this->load->model('sale/order');
		
    	$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);
		
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));    	

		$this->data['store'] = $this->config->get('config_store');
		$this->data['address'] = nl2br($this->config->get('config_address'));
		$this->data['telephone'] = $this->config->get('config_telephone');
		$this->data['fax'] = $this->config->get('config_fax');
		$this->data['email'] = $this->config->get('config_email');
		$this->data['website'] = trim(HTTP_CATALOG, '/');

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
      		'country'   => $order_info['shipping_country']  
		);
	
		$this->data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
  
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
      		'country'   => $order_info['payment_country']  
		);
	
		$this->data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
		
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
          		'name'     => $product['name'],
          		'model'    => $product['model'],
          		'option'   => $option_data,
          		'quantity' => $product['quantity'],
          		'price'    => $this->currency->format($product['price'], $order_info['currency'], $order_info['value']),
				'total'    => $this->currency->format($product['total'], $order_info['currency'], $order_info['value'])
        	);
    	}

    	$this->data['totals'] = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);
		
		$this->template = 'sale/order_invoice.tpl';
		
 		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
	}
	
  	public function invoices() {
		$this->load->language('sale/order');
		
		$this->data['title'] = $this->language->get('heading_title');
			
		if (isset($this->request->server['HTTPS']) && ($this->request->server['HTTPS'] == 'on')) {
			$this->data['base'] = HTTPS_SERVER;
		} else {
			$this->data['base'] = HTTP_SERVER;
		}
		
		$this->data['direction'] = $this->language->get('direction');
		$this->data['language'] = $this->language->get('code');	

		$this->data['text_invoice'] = $this->language->get('text_invoice');
		$this->data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$this->data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$this->data['text_telephone'] = $this->language->get('text_telephone');
		$this->data['text_fax'] = $this->language->get('text_fax');		
		$this->data['text_to'] = $this->language->get('text_to');
		$this->data['text_ship_to'] = $this->language->get('text_ship_to');
		
		$this->data['column_product'] = $this->language->get('column_product');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_total'] = $this->language->get('column_total');	

		$this->load->model('sale/order');
	
		$this->data['orders'] = array();
		
		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} else {
			$orders = array();
		}
		
		foreach ($orders as $order_id) {
			$order_info = $this->model_sale_order->getOrder($order_id);
			
			if ($order_info) {
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
					'order_id'	       => $order_info['order_id'],
					'date_added'       => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store'            => $this->config->get('config_store'),
					'address'          => nl2br($this->config->get('config_address')),
					'telephone'        => $this->config->get('config_telephone'),
					'fax'              => $this->config->get('config_fax'),
					'email'            => $this->config->get('config_email'),
					'website'          => trim(HTTP_CATALOG, '/'),
					'shipping_address' => $shipping_address,
					'payment_address'  => $payment_address,
					'product'          => $product_data,
					'total'            => $total_data
				);
			}
		}
		
		$this->template = 'sale/order_invoices.tpl';
			
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}
	  		
	private function validate() {
    	if (!$this->user->hasPermission('modify', 'sale/order')) {
      		$this->error['warning'] = $this->language->get('error_permission'); 
    	}
	
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}
}
?>