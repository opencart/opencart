<?php    
class ControllerCustomerCustomer extends Controller { 
	private $error = array();
  
  	public function index() {
		$this->load->language('customer/customer');
		 
		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
		
    	$this->getList();
  	}
  
  	public function insert() {
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
      	  	$this->model_customer_customer->addCustomer($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
		  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
		}
    	
    	$this->getForm();
  	} 
   
  	public function update() {
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
		
    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_customer_customer->editCustomer($this->request->get['customer_id'], $this->request->post);
	  		
			$this->session->data['success'] = $this->language->get('text_success');
	  
			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
		}
    
    	$this->getForm();
  	}   

  	public function delete() {
		$this->load->language('customer/customer');

    	$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('customer/customer');
			
    	if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $customer_id) {
				$this->model_customer_customer->deleteCustomer($customer_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . $this->request->get['filter_name'];
			}
		
			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}
		
			if (isset($this->request->get['filter_date_added'])) {
				$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
			
			$this->redirect($this->url->https('customer/customer' . $url));
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
			$sort = 'name'; 
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
       		'href'      => $this->url->https('customer/customer' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('customer/customer/insert' . $url);
		$this->data['delete'] = $this->url->https('customer/customer/delete' . $url);

		$this->data['customers'] = array();

		$data = array(
			'name'       => @$this->request->get['filter_name'], 
			'status'     => @$this->request->get['filter_status'], 
			'date_added' => @$this->request->get['filter_date_added'],
			'sort'       => $sort,
			'order'      => $order,
			'start'      => ($page - 1) * 10,
			'limit'      => 10
		);
		
		$customer_total = $this->model_customer_customer->getTotalCustomers($data);
	
		$results = $this->model_customer_customer->getCustomers($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('customer/customer/update&customer_id=' . $result['customer_id'] . $url)
			);
						
			$this->data['customers'][] = array(
				'customer_id' => $result['customer_id'],
				'name'        => $result['name'],
				'status'      => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'delete'      => in_array($result['customer_id'], (array)@$this->request->post['delete']),
				'action'      => $action
			);
		}	
					
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_name'] = $this->url->https('customer/customer&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('customer/customer&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('customer/customer&sort=date_added' . $url);
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
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
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('customer/customer' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = @$this->request->get['filter_name'];
		$this->data['filter_status'] = @$this->request->get['filter_status'];
		$this->data['filter_date_added'] = @$this->request->get['filter_date_added'];
		
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'customer/customer_list.tpl';
		$this->layout   = 'common/layout';
				
		$this->render();
  	}
  
  	private function getForm() {
    	$this->data['heading_title'] = $this->language->get('heading_title');
 
    	$this->data['text_enabled'] = $this->language->get('text_enabled');
    	$this->data['text_disabled'] = $this->language->get('text_disabled');

    	$this->data['entry_firstname'] = $this->language->get('entry_firstname');
    	$this->data['entry_lastname'] = $this->language->get('entry_lastname');
    	$this->data['entry_email'] = $this->language->get('entry_email');
    	$this->data['entry_telephone'] = $this->language->get('entry_telephone');
    	$this->data['entry_fax'] = $this->language->get('entry_fax');
    	$this->data['entry_password'] = $this->language->get('entry_password');
    	$this->data['entry_confirm'] = $this->language->get('entry_confirm');
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$this->data['entry_status'] = $this->language->get('entry_status');
  
    	$this->data['button_save'] = $this->language->get('button_save');
    	$this->data['button_cancel'] = $this->language->get('button_cancel');
	
		$this->data['tab_general'] = $this->language->get('tab_general');
	  
    	$this->data['error_warning'] = @$this->error['warning'];
    	$this->data['error_firstname'] = @$this->error['firstname'];
    	$this->data['error_lastname'] = @$this->error['lastname'];
    	$this->data['error_email'] = @$this->error['email'];
    	$this->data['error_telephone'] = @$this->error['telephone'];
    	$this->data['error_password'] = @$this->error['password'];
    	$this->data['error_confirm'] = @$this->error['confirm'];
		    
		$url = '';
		
		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . $this->request->get['filter_name'];
		}
		
		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}
		
		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
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
       		'href'      => $this->url->https('customer/customer' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
		
		if (!isset($this->request->get['customer_id'])) {
			$this->data['action'] = $this->url->https('customer/customer/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('customer/customer/update&customer_id=' . $this->request->get['customer_id'] . $url);
		}
		  
    	$this->data['cancel'] = $this->url->https('customer/customer' . $url);

    	if ((isset($this->request->get['customer_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$customer_info = $this->model_customer_customer->getCustomer($this->request->get['customer_id']);
    	}

    	if (isset($this->request->post['firstname'])) {
      		$this->data['firstname'] = $this->request->post['firstname'];
    	} else {
      		$this->data['firstname'] = @$customer_info['firstname'];
    	}

    	if (isset($this->request->post['lastname'])) {
      		$this->data['lastname'] = $this->request->post['lastname'];
    	} else {
      		$this->data['lastname'] = @$customer_info['lastname'];
    	}

    	if (isset($this->request->post['email'])) {
      		$this->data['email'] = $this->request->post['email'];
    	} else {
      		$this->data['email'] = @$customer_info['email'];
    	}

    	if (isset($this->request->post['telephone'])) {
      		$this->data['telephone'] = $this->request->post['telephone'];
    	} else {
      		$this->data['telephone'] = @$customer_info['telephone'];
    	}

    	if (isset($this->request->post['fax'])) {
      		$this->data['fax'] = $this->request->post['fax'];
    	} else {
      		$this->data['fax'] = @$customer_info['fax'];
    	}

    	if (isset($this->request->post['newsletter'])) {
      		$this->data['newsletter'] = $this->request->post['newsletter'];
    	} else {
      		$this->data['newsletter'] = @$customer_info['newsletter'];
    	}
		
    	if (isset($this->request->post['status'])) {
      		$this->data['status'] = $this->request->post['status'];
    	} else {
      		$this->data['status'] = @$customer_info['status'];
    	}

    	$this->data['password'] = @$this->request->post['password'];

    	$this->data['confirm'] = @$this->request->post['confirm'];

		$this->id       = 'content';
		$this->template = 'customer/customer_form.tpl';
		$this->layout   = 'common/layout';
		
 		$this->render();	
	}  
	 
  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'customer/customer')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['firstname'])) < 3) || (strlen(utf8_decode($this->request->post['firstname'])) > 32)) {
      		$this->error['firstname'] = $this->language->get('error_firstname');
    	}

    	if ((strlen(utf8_decode($this->request->post['lastname'])) < 3) || (strlen(utf8_decode($this->request->post['lastname'])) > 32)) {
      		$this->error['lastname'] = $this->language->get('error_lastname');
    	}

    	if ((strlen(utf8_decode($this->request->post['email'])) > 32) || (!eregi('^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$', $this->request->post['email']))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if ((strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}

    	if (($this->request->post['password']) || (!isset($this->request->get['customer_id']))) {
      		if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
        		$this->error['password'] = $this->language->get('error_password');
      		}
	
	  		if ($this->request->post['password'] != $this->request->post['confirm']) {
	    		$this->error['confirm'] = $this->language->get('error_confirm');
	  		}
    	}

		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}    

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'customer/customer')) {
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
