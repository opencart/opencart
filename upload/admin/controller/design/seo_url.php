<?php
class ControllerDesignSeoUrl extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_seo_url->addSeoUrl($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}
			
			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
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

			$this->response->redirect($this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_seo_url->editSeoUrl($this->request->get['seo_url_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}
						
			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
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

			$this->response->redirect($this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/seo_url');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $seo_url_id) {
				$this->model_design_seo_url->deleteSeoUrl($seo_url_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['filter_query'])) {
				$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
			}
						
			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_store_id'])) {
				$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
			}

			if (isset($this->request->get['filter_language_id'])) {
				$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
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

			$this->response->redirect($this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_query'])) {
			$filter_query = $this->request->get['filter_query'];
		} else {
			$filter_query = '';
		}		
		
		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = $this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'keyword';
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
			
		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}
					
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('design/seo_url/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('design/seo_url/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['seo_urls'] = array();

		$filter_data = array(
			'filter_query'	     => $filter_query,
			'filter_keyword'	 => $filter_keyword,
			'filter_store_id'	 => $filter_store_id,
			'filter_language_id' => $filter_language_id,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'              => $this->config->get('config_limit_admin')
		);

		$seo_url_total = $this->model_design_seo_url->getTotalSeoUrls($filter_data);

		$results = $this->model_design_seo_url->getSeoUrls($filter_data);

		foreach ($results as $result) {
			$data['seo_urls'][] = array(
				'seo_url_id' => $result['seo_url_id'],
				'query'      => $result['query'],
				'keyword'    => $result['keyword'],
				'store'      => $result['store_id'] ? $result['store'] : $this->language->get('text_default'),
				'language'   => $result['language'],
				'edit'       => $this->url->link('design/seo_url/edit', 'user_token=' . $this->session->data['user_token'] . '&seo_url_id=' . $result['seo_url_id'] . $url, true)
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
			
		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}
					
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
			
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_query'] = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . '&sort=query' . $url, true);
		$data['sort_keyword'] = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . '&sort=keyword' . $url, true);
		$data['sort_store'] = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . '&sort=store' . $url, true);
		$data['sort_language'] = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . '&sort=language' . $url, true);

		$url = '';
		
		if (isset($this->request->get['filter_query'])) {
			$url .= '&filter_query=' . urlencode(html_entity_decode($this->request->get['filter_query'], ENT_QUOTES, 'UTF-8'));
		}
					
		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_store_id'])) {
			$url .= '&filter_store_id=' . $this->request->get['filter_store_id'];
		}

		if (isset($this->request->get['filter_language_id'])) {
			$url .= '&filter_language_id=' . $this->request->get['filter_language_id'];
		}
			
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $seo_url_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($seo_url_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($seo_url_total - $this->config->get('config_limit_admin'))) ? $seo_url_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $seo_url_total, ceil($seo_url_total / $this->config->get('config_limit_admin')));

		$data['filter_query'] = $filter_query;
		$data['filter_keyword'] = $filter_keyword;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_language_id'] = $filter_language_id;

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_url_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['seo_url_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['query'])) {
			$data['error_query'] = $this->error['query'];
		} else {
			$data['error_query'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['seo_url_id'])) {
			$data['action'] = $this->url->link('design/seo_url/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('design/seo_url/edit', 'user_token=' . $this->session->data['user_token'] . '&seo_url_id=' . $this->request->get['seo_url_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('design/seo_url', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['seo_url_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$seo_url_info = $this->model_design_seo_url->getSeoUrl($this->request->get['seo_url_id']);
		}
		
		if (isset($this->request->post['query'])) {
			$data['query'] = $this->request->post['query'];
		} elseif (!empty($seo_url_info)) {
			$data['query'] = $seo_url_info['query'];
		} else {
			$data['query'] = '';
		}

		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($seo_url_info)) {
			$data['keyword'] = $seo_url_info['keyword'];
		} else {
			$data['keyword'] = '';
		}
				
		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
				
		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($seo_url_info)) {
			$data['store_id'] = $seo_url_info['store_id'];
		} else {
			$data['store_id'] = '';
		}			
				
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['language_id'])) {
			$data['language_id'] = $this->request->post['language_id'];
		} elseif (!empty($seo_url_info)) {
			$data['language_id'] = $seo_url_info['language_id'];
		} else {
			$data['language_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/seo_url_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['query']) {
			$this->error['query'] = $this->language->get('error_query');
		}
		
		$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($this->request->post['keyword']);

		foreach ($seo_urls as $seo_url) {
			if ($seo_url['store_id'] == $this->request->post['store_id'] && $seo_url['query'] != $this->request->post['query']) {
				$this->error['keyword'] = $this->language->get('error_exists');
				
				break;
			}
		}
		
		if (!$this->request->post['keyword']) {
			$this->error['keyword'] = $this->language->get('error_keyword');
		}
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}