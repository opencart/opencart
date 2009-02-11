<?php
class ControllerGoogleOptimizer extends Controller {
	private $error = array();
 
	public function index() {
		$this->load->language('google/optimizer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('google/optimizer');
		
		$this->getList();
	} 

	public function insert() {
		$this->load->language('google/optimizer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('google/optimizer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_google_optimizer->addExperiment($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect($this->url->https('google/optimizer' . $url));
		}

		$this->getForm();
	}

	public function update() {
		$this->load->language('google/optimizer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('google/optimizer');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validateForm())) {
			$this->model_google_optimizer->editExperiment($this->request->get['google_optimizer_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect($this->url->https('google/optimizer' . $url));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->load->language('google/optimizer');

		$this->document->title = $this->language->get('heading_title');
		
		$this->load->model('google/optimizer');

		if ((isset($this->request->post['delete'])) && ($this->validateDelete())) {
			foreach ($this->request->post['delete'] as $google_optimizer_id) {
				$this->model_google_optimizer->deleteExperiment($google_optimizer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
						
			$this->redirect($this->url->https('google/optimizer' . $url));
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
       		'href'      => $this->url->https('google/optimizer' . $url),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
							
		$this->data['insert'] = $this->url->https('google/optimizer/insert' . $url);
		$this->data['delete'] = $this->url->https('google/optimizer/delete' . $url);	

		$this->data['experiments'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$experiment_total = $this->model_google_optimizer->getTotalExperiments();
	
		$results = $this->model_google_optimizer->getExperiments($data);
 
    	foreach ($results as $result) {
			$action = array();
						
			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->https('google/optimizer/update&google_optimizer_id=' . $result['google_optimizer_id'] . $url)
			);
						
			$this->data['experiments'][] = array(
				'google_optimizer_id' => $result['google_optimizer_id'],
				'name'                => $result['name'],
				'status'              => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'date_added'          => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'delete'              => in_array($result['google_optimizer_id'], (array)@$this->request->post['delete']),
				'action'              => $action
			);
		}	
	
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');		
		
		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
 
		$this->data['error_warning'] = @$this->error['warning'];
		
		$this->data['success'] = @$this->session->data['success'];
		
		unset($this->session->data['success']);

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=' .  'DESC';
		} else {
			$url .= '&order=' .  'ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$this->data['sort_product'] = $this->url->https('google/optimizer&sort=name' . $url);
		$this->data['sort_status'] = $this->url->https('google/optimizer&sort=status' . $url);
		$this->data['sort_date_added'] = $this->url->https('google/optimizer&sort=date_added' . $url);
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $experiment_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->https('google/optimizer' . $url . '&page=%s');
			
		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->id       = 'content';
		$this->template = 'google/optimizer_list.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}

	private function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_url'] = $this->language->get('entry_url');
		$this->data['entry_variation'] = $this->language->get('entry_variation');
		$this->data['entry_control'] = $this->language->get('entry_control');
		$this->data['entry_tracking'] = $this->language->get('entry_tracking');
		$this->data['entry_conversion'] = $this->language->get('entry_conversion');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_variation'] = $this->language->get('button_add_variation');
		$this->data['button_remove'] = $this->language->get('button_remove');

		$this->data['tab_general'] = $this->language->get('tab_general');

		$this->data['error_warning'] = @$this->error['warning'];
		$this->data['error_name'] = @$this->error['name'];
		$this->data['error_url'] = @$this->error['url'];
		$this->data['error_control'] = @$this->error['control'];
		$this->data['error_tracking'] = @$this->error['tracking'];
		$this->data['error_conversion'] = @$this->error['conversion'];

   		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('common/home'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => $this->url->https('google/optimizer'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$url = '';
			
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
										
		if (!isset($this->request->get['google_optimizer_id'])) { 
			$this->data['action'] = $this->url->https('google/optimizer/insert' . $url);
		} else {
			$this->data['action'] = $this->url->https('google/optimizer/update&google_optimizer_id=' . $this->request->get['google_optimizer_id'] . $url);
		}
		
		$this->data['cancel'] = $this->url->https('google/optimizer' . $url);

		if ((isset($this->request->get['google_optimizer_id'])) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$experiment_info = $this->model_google_optimizer->getExperiment($this->request->get['google_optimizer_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} else {
			$this->data['name'] = @$experiment_info['name'];
		}
		
		if (isset($this->request->post['url'])) {
			$this->data['url'] = $this->request->post['url'];
		} else {
			$this->data['url'] = @$experiment_info['url'];
		}

		if (isset($this->request->post['control'])) {
			$this->data['control'] = $this->request->post['control'];
		} else {
			$this->data['control'] = @$experiment_info['control'];
		}

		if (isset($this->request->post['tracking'])) {
			$this->data['tracking'] = $this->request->post['tracking'];
		} else {
			$this->data['tracking'] = @$experiment_info['tracking'];
		}

		if (isset($this->request->post['conversion'])) {
			$this->data['conversion'] = $this->request->post['conversion'];
		} else {
			$this->data['conversion'] = @$experiment_info['conversion'];
		}

		if (isset($this->request->post['variation'])) {
			$this->data['variations'] = $this->request->post['variation'];
		} elseif (isset($experiment_info)) {
			$this->data['variations'] = $this->model_google_optimizer->getVariations($this->request->get['google_optimizer_id']);
		} else {
			$this->data['variations'] = array();
		}
		
		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} else {
			$this->data['status'] = @$experiment_info['status'];
		}
		
		$this->id       = 'content';
		$this->template = 'google/optimizer_form.tpl';
		$this->layout   = 'module/layout';
				
		$this->render();
	}
 
	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'google/optimizer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
      	if ((strlen($this->request->post['name']) < 3) || (strlen($this->request->post['name']) > 64)) {
        	$this->error['name'] = $this->language->get('error_name');
      	}

		if (!$this->request->post['url']) {
			$this->error['url'] = $this->language->get('error_url');
		}
		
		if (!$this->request->post['control']) {
			$this->error['control'] = $this->language->get('error_control');
		}

		if (!$this->request->post['tracking']) {
			$this->error['tracking'] = $this->language->get('error_tracking');
		}

		if (!$this->request->post['conversion']) {
			$this->error['conversion'] = $this->language->get('error_conversion');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'google/optimizer')) {
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