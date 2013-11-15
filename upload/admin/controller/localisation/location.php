<?php
class ControllerLocalisationLocation extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('localisation/location');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/location');
		
		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/location');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/location');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {   
			$this->model_localisation_location->addLocation($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();   
	}

	public function update() {
		$this->language->load('localisation/location');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/location');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_location->editLocation($this->request->get['location_id'], $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
		
			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
		
			$this->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}
		
		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/location');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('localisation/location');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach($this->request->post['selected'] as $location_id) {
				$this->model_localisation_location->deleteLocation($location_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			$this->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));  
		}
		
		$this->getList();
	}

	protected function getList() {                  
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'l.name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		
		$url = '';
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] =   array();
		
		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		$data['insert'] = $this->url->link('localisation/location/insert',  'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/location/delete',  'token=' . $this->session->data['token'] . $url, 'SSL');
		
		$data['location'] = array();
		
		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);
		     
		$location_total = $this->model_localisation_location->getTotalLocations();
		
		$results = $this->model_localisation_location->getLocations($filter_data);
		
		foreach($results as $result) {
			$data['location'][] =   array(
				'location_id' => $result['location_id'],
				'name'        => $result['name'],
				'address_1'   => $result['address_1'], 
				'zone'        => $result['zone'],    
				'country'     => $result['country'],                   
				'edit'        => $this->url->link('localisation/location/update', 'token=' . $this->session->data['token'] . '&location_id=' . $result['location_id'] . $url, 'SSL')
			);
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		
		$data['column_name'] = $this->language->get('column_name');
		$data['column_address_1'] = $this->language->get('column_address_1');
		$data['column_zone'] = $this->language->get('column_zone');
		$data['column_country'] = $this->language->get('column_country');
		$data['column_action'] = $this->language->get('column_action');
		
		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		
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
		
		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_name'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=l.name' . $url, 'SSL');
		$data['sort_address_1'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=l.address_1' . $url, 'SSL');
		$data['sort_zone'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=z.name' . $url, 'SSL');
		$data['sort_country'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=c.name' . $url, 'SSL');
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
		
		$data['pagination'] = $pagination->render();
		
		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_admin_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_admin_limit')) > ($location_total - $this->config->get('config_admin_limit'))) ? $location_total : ((($page - 1) * $this->config->get('config_admin_limit')) + $this->config->get('config_admin_limit')), $location_total, ceil($location_total / $this->config->get('config_admin_limit')));
		
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
				
		$this->response->setOutput($this->load->view('localisation/location_list.tpl', $data));
	}
	
	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_geocode'] = $this->language->get('text_geocode'); 
		
		$data['entry_name'] = $this->language->get('entry_name');		
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_address_1'] = $this->language->get('entry_address_1');
		$data['entry_address_2'] = $this->language->get('entry_address_2');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_country'] = $this->language->get('entry_country');
		$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_geocode'] = $this->language->get('entry_geocode');
		$data['entry_open'] = $this->language->get('entry_open');        
		$data['entry_comment'] = $this->language->get('entry_comment');
	
		$data['help_geocode'] = $this->language->get('help_geocode');
		$data['help_open'] = $this->language->get('help_open');
		$data['help_comment'] = $this->language->get('help_comment');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');        
		$data['button_geocode'] = $this->language->get('button_geocode');
				
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}
				
		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}
		
		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}   
		
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}   
		
		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}
		
		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
		                
		$url = '';
		
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
			'href' => $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);
		
		if (!isset($this->request->get['location_id'])) {
			$data['action'] = $this->url->link('localisation/location/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/location/update', 'token=' . $this->session->data['token'] .  '&location_id=' . $this->request->get['location_id'] . $url, 'SSL');
		}
		
		$data['cancel'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL');
		
		if (isset($this->request->get['location_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$location_info = $this->model_localisation_location->getLocation($this->request->get['location_id']);
		}
		
		$data['token'] = $this->session->data['token'];  

		$this->load->model('setting/store');
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($location_info)) {
			$data['name'] = $location_info['name'];
		} else {
			$data['name'] =   '';
		}
		
		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($location_info)) {
			$data['telephone'] = $location_info['telephone'];
		} else {
			$data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($location_info)) {
			$data['fax'] = $location_info['fax'];
		} else {
			$data['fax'] = '';
		}
				
		if (isset($this->request->post['address_1'])) {
			$data['address_1'] = $this->request->post['address_1'];
		} elseif (!empty($location_info)) {
			$data['address_1'] = $location_info['address_1'];
		} else {
			$data['address_1'] = '';
		}
		
		if (isset($this->request->post['address_2'])) {
			$data['address_2'] = $this->request->post['address_2'];
		} elseif (!empty($location_info)) {
			$data['address_2'] = $location_info['address_2'];
		} else {
			$data['address_2'] = '';
		}
		
		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($location_info)) {
			$data['city'] = $location_info['city'];
		} else {
			$data['city'] = '';
		}
		
		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (!empty($location_info)) {
			$data['postcode'] = $location_info['postcode'];
		} else {
			$data['postcode'] = '';
		}
		
		if (isset($this->request->post['country_id'])) {
      		$data['country_id'] = $this->request->post['country_id'];
    	} elseif (!empty($location_info)) { 
			$data['country_id'] = $location_info['country_id'];
		} else {
      		$data['country_id'] = '';
    	}
		
		$this->load->model('localisation/country');
		
		$data['countries'] = $this->model_localisation_country->getCountries();
				
		if (isset($this->request->post['zone_id'])) {
      		$data['zone_id'] = $this->request->post['zone_id'];
    	} elseif (!empty($location_info)) { 
			$data['zone_id'] = $location_info['zone_id'];
		} else {
      		$data['zone_id'] = '';
    	}
				
		if (isset($this->request->post['geocode'])) {
			$data['geocode'] = $this->request->post['geocode'];
		} elseif (!empty($location_info)) {
			$data['geocode'] = $location_info['geocode'];
		} else {
			$data['geocode'] = '';
		}
						
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($location_info)) {
			$data['image'] = $location_info['image'];
		} else {
			$data['image'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($location_info) && $location_info['image'] && is_file(DIR_IMAGE . $location_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($location_info['image'], 100, 100);
		} else {
			$data['thumb'] = '';
		}
				
		if (isset($this->request->post['open'])) {
			$data['open'] = $this->request->post['open'];
		} elseif (!empty($location_info)) {
			$data['open'] = $location_info['open'];        
		} else {
			$data['open'] = '';
		}
		
		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($location_info)) {
			$data['comment'] = $location_info['comment'];
		} else {
			$data['comment'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('localisation/location_form.tpl', $data));         
	}           
	
	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/location')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

    	if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
      		$this->error['telephone'] = $this->language->get('error_telephone');
    	}
				
		if ((utf8_strlen($this->request->post['address_1']) < 3) || (utf8_strlen($this->request->post['address_1']) > 128)) {
			$this->error['address_1'] = $this->language->get('error_address_1');
		}
		
    	if ((utf8_strlen($this->request->post['city']) < 2) || (utf8_strlen($this->request->post['city']) > 128)) {
			$this->error['city'] = $this->language->get('city'); 
		}
		
		$this->load->model('localisation/country');
		
		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		
		if ($country_info && $country_info['postcode_required'] && (utf8_strlen($this->request->post['postcode']) < 2 || utf8_strlen($this->request->post['postcode']) > 10)) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}
		
    	if ($this->request->post['country_id'] == '') {
      		$this->error['country'] = $this->language->get('error_country');
    	}
		
    	if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
      		$this->error['zone'] = $this->language->get('error_zone');
    	}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	
  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'localisation/location')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}	
	  	 
		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}  
  	}
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}
}
?>