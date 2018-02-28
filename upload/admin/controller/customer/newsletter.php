<?php
class ControllerCustomerNewsletter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/newsletter');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/newsletter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_newsletter->addNewsletter($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_country'])) {
				$url .= '&filter_country=' . $this->request->get['filter_country'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

			$this->response->redirect($this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/newsletter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_newsletter->editNewsletter($this->request->get['newsletter_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_country'])) {
				$url .= '&filter_country=' . $this->request->get['filter_country'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

			$this->response->redirect($this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/newsletter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/newsletter');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $newsletter_id) {
				$this->model_customer_newsletter->deleteNewsletter($newsletter_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_email'])) {
				$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_country'])) {
				$url .= '&filter_country=' . $this->request->get['filter_country'];
			}

			if (isset($this->request->get['filter_ip'])) {
				$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

			$this->response->redirect($this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}
		
		if (isset($this->request->get['filter_country'])) {
			$filter_country = $this->request->get['filter_country'];
		} else {
			$filter_country = '';
		}

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
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

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		$data['add'] = $this->url->link('customer/newsletter/add', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('customer/newsletter/delete', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['download'] = $this->url->link('customer/newsletter/export', 'user_token=' . $this->session->data['user_token'] . $url);
		
		
		$data['newsletters'] = array();

		$filter_data = array(
			'filter_email'             => $filter_email,
			'filter_country'		   => $filter_country,
			'filter_ip'                => $filter_ip,
			'filter_date_added'        => $filter_date_added,			
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$newsletter_total = $this->model_customer_newsletter->getTotalNewsletters($filter_data);

		$results = $this->model_customer_newsletter->getNewsletters($filter_data);

		foreach ($results as $result) {			
			$data['newsletters'][] = array(
				'newsletter_id'  => $result['newsletter_id'],
				'email'          => $result['email'],
				'country'        => $result['country'],
				'ip'             => $result['ip'],
				'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'           => $this->url->link('customer/newsletter/edit', 'user_token=' . $this->session->data['user_token'] . '&newsletter_id=' . $result['newsletter_id'] . $url)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
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

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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

		$data['sort_email'] = $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . '&sort=c.email' . $url);
		$data['sort_ip'] = $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . '&sort=c.ip' . $url);
		$data['sort_date_added'] = $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . '&sort=c.date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));

		$data['filter_email'] = $filter_email;
		$data['filter_country'] = $filter_country;
		$data['filter_ip'] = $filter_ip;
		$data['filter_date_added'] = $filter_date_added;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/newsletter_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['newsletter_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['newsletter_id'])) {
			$data['newsletter_id'] = (int)$this->request->get['newsletter_id'];
		} else {
			$data['newsletter_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['exists'])) {
			$data['error_exists'] = $this->error['exists'];
		} else {
			$data['error_exists'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . $this->request->get['filter_country'];
		}		
		
		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url)
		);

		if (!isset($this->request->get['newsletter_id'])) {
			$data['action'] = $this->url->link('customer/newsletter/add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('customer/newsletter/edit', 'user_token=' . $this->session->data['user_token'] . '&newsletter_id=' . $this->request->get['newsletter_id'] . $url);
		}

		$data['cancel'] = $this->url->link('customer/newsletter', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['newsletter_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$newsletter_info = $this->model_customer_newsletter->getNewsletter($this->request->get['newsletter_id']);
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($newsletter_info)) {
			$data['email'] = $newsletter_info['email'];
		} else {
			$data['email'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/newsletter_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		$this->load->model('customer/newsletter');

		$newsletter_info = $this->model_customer_newsletter->getNewsletterByEmail($this->request->post['email']);

		if (!isset($this->request->get['newsletter_id'])) {
			if ($newsletter_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {			
			if ($newsletter_info && ($this->request->get['newsletter_id'] != $newsletter_info['newsletter_id'])) {
				$this->error['exists'] = $this->language->get('error_exists');
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/newsletter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = '';
		}

		if ($filter_email) {
			$this->load->model('customer/newsletter');

			$filter_data = array(
				'filter_email'     => $filter_email,
				'start'            => 0,
				'limit'            => 5
			);

			$results = $this->model_customer_newsletter->getNewsletters($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'newsletter_id'  => $result['newsletter_id'],
					'email'          => $result['email'],
					'country'        => $result['country'],
					'ip'             => $result['ip'],
					'date_added'     => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['email'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function import() {
		$json = array();
		
		$this->load->model('customer/newsletter');
		
		if (!is_file($this->request->files['file']['tmp_name'])) {
			$json['error'] = $this->language->get('error_exists');
		}
		
		if (isset($this->request->files['file']['name'])) {
			if (substr($this->request->files['file']['name'], -4) != '.csv') {
				$json['error'] = $this->language->get('error_filetype');
			}

			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
			}
		} else {
			$json['error'] = $this->language->get('error_upload');
		}

		if (!$json) {
			$csv = array();
			
			$this->load->model('localisation/country');
			
			$handle = fopen($this->request->files['file']['tmp_name'], 'r');
			
			$row = 0;
			
			while (($contents = fgetcsv($handle, 1000, ",")) !== FALSE) {
				foreach ($contents as $content) {					
					switch ($content) {
						case filter_var($content, FILTER_VALIDATE_EMAIL):
							$csv[$row]['email'] = $content;
							break;
						case date($content):
							$csv[$row]['date_added'] = $content;
							break;
						default :
						break;
					}
				}
				$row ++;
			}
			
			fclose($handle);

			foreach ($csv as $newsletter) {
				$newsletter_info = $this->model_customer_newsletter->getNewsletterByEmail($newsletter['email']);
				
				if (!$newsletter_info) {
					$this->model_customer_newsletter->addNewsletter($newsletter);
				}
			}
			
			$json['success'] = true;
			
			$json['url'] = 'index.php?route=customer/newsletter&user_token=' . $this->session->data['user_token'];
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function export() {
		if (isset($this->request->post['selected'])) {
			$newsletters = $this->request->post['selected'];
		} else {
			$newsletters = array();
		}
		
		$this->load->model('customer/newsletter');
		
		$csv = '';
		
		if (!empty($newsletters)) {
			foreach ($newsletters as $newsletter_id) {
				$newsletter_info = $this->model_customer_newsletter->getNewsletter($newsletter_id);
	
				$csv .= $newsletter_info['email'] ? $newsletter_info['email'] . ',' :  '';
				$csv .= $newsletter_info['ip'] ?  $newsletter_info['ip'] . ',' : '';
				$csv .= $newsletter_info['country'] ? $newsletter_info['country'] . ',' : '';
				$csv .= $newsletter_info['date_added'] ? $newsletter_info['date_added'] : '';
				$csv .= "\n";			}	
		} else {
			$newsletter_info = $this->model_customer_newsletter->getNewsletters();

			foreach ($newsletter_info as $newsletter) {
				$csv .= $newsletter['email'] ? $newsletter['email'] . ',' :  '';
				$csv .= $newsletter['ip'] ?  $newsletter['ip'] . ',' : '';
				$csv .= $newsletter['country'] ? $newsletter['country'] . ',' : '';
				$csv .= $newsletter['date_added'] ? $newsletter['date_added'] : '';
				$csv .= "\n";
			}
		}		

		if (!headers_sent()) {
			header('Pragma: public');
			header('Expires: 0');
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Transfer-Encoding: binary');
			header('Content-Disposition: attachment; filename="newsletter-' . date('Y-m-d') . '.csv"');
			header('Content-Length: ' . strlen($csv));
		
			print($csv);
		} else {
			exit('Error: Headers already sent out!');
		}
	}
}
