<?php
class ControllerLocalisationSeoUrl extends Controller {
	private $error = array();

	private $types = array(
		'product_id' => 'Product',
		'category_id' => 'Category',
		'information_id' => 'Information',
		'manufacturer_id' => 'Manufacturer',
		'route' => 'Page'
	);

	public function index() {
		$this->language->load('localisation/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/seo_url');

		$this->getList();
	}

	public function insert() {
		$this->language->load('localisation/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_seo_url->addSeoUrl($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
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

			$this->redirect($this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('localisation/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/seo_url');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_seo_url->editSeoUrl($this->request->get['url_alias_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
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

			$this->redirect($this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('localisation/seo_url');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/seo_url');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $url_alias_id) {
				$this->model_localisation_seo_url->deleteSeoUrl($url_alias_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_type'])) {
				$url .= '&filter_type=' . $this->request->get['filter_type'];
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

			$this->redirect($this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = null;
		}

		if (isset($this->request->get['filter_type'])) {
			$filter_type = $this->request->get['filter_type'];
		} else {
			$filter_type = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'u.keyword';
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

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
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
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$this->data['insert'] = $this->url->link('localisation/seo_url/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('localisation/seo_url/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['seo_urls'] = array();

		$data = array(
			'filter_keyword'  => $filter_keyword,
			'filter_type'     => $filter_type,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');

		$config_language_id = $this->config->get('config_language_id');

		$this->data['types'] = $this->types;

		$seo_url_total = $this->model_localisation_seo_url->getTotalSeoUrls($data);

		$results = $this->model_localisation_seo_url->getSeoUrls($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('localisation/seo_url/update', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $result['url_alias_id'] . $url, 'SSL')
			);

			$parts = explode('=', $result['query']);

			if ($parts[0] == 'product_id') {
				$link = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $parts[1], 'SSL');
			} elseif ($parts[0] == 'category_id') {
				$link = $this->url->link('catalog/category/update', 'token=' . $this->session->data['token'] . '&category_id=' . $parts[1], 'SSL');
			} elseif ($parts[0] == 'information_id') {
				$link = $this->url->link('catalog/information/update', 'token=' . $this->session->data['token'] . '&information_id=' . $parts[1], 'SSL');
			} elseif ($parts[0] == 'manufacturer_id') {
				$link = $this->url->link('catalog/manufacturer/update', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $parts[1], 'SSL');
			} else {
				$link = false;
			}

			if ($parts[0] == 'product_id') {
				$this->load->model('catalog/product');

				$product = $this->model_catalog_product->getProduct($parts[1]);

				if (!empty($product)) {
					$name = $product['name'];
				} else {
					$name = '';
				}
			} elseif ($parts[0] == 'category_id') {
				$this->load->model('catalog/category');

				$category = $this->model_catalog_category->getCategory($parts[1]);

				if (!empty($category)) {
					$name = $category['name'];
				} else {
					$name = '';
				}
			} elseif ($parts[0] == 'information_id') {
				$this->load->model('catalog/information');

				$information_description = $this->model_catalog_information->getInformationDescriptions($parts[1]);

				if (!empty($information_description) && isset($information_description[$config_language_id])) {
					$name = $information_description[$config_language_id]['title'];
				} else {
					$name = '';
				}
			} elseif ($parts[0] == 'manufacturer_id') {
				$this->load->model('catalog/manufacturer');

				$manufacturer = $this->model_catalog_manufacturer->getManufacturer($parts[1]);

				if (!empty($manufacturer)) {
					$name = $manufacturer['name'];
				} else {
					$name = '';
				}
			} elseif ($parts[0] == 'route') {
				$name = $parts[1];
			} else {
				$name = '';
			}

			if (isset($this->types[$parts[0]])) {
				$type = $this->types[$parts[0]];
			} else {
				$type = '';
			}

			$this->data['seo_urls'][] = array(
				'url_alias_id' => $result['url_alias_id'],
				'keyword'      => $result['keyword'],
				'name'         => $name,
				'link'         => $link,
				'type'         => $type,
				'selected'     => isset($this->request->post['selected']) && in_array($result['url_alias_id'], $this->request->post['selected']),
				'action'       => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_keyword'] = $this->language->get('column_keyword');
		$this->data['column_link'] = $this->language->get('column_link');
		$this->data['column_type'] = $this->language->get('column_type');
		$this->data['column_action'] = $this->language->get('column_action');

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

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_keyword'] = $this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . '&sort=u.keyword' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
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
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->url = $this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['results'] = sprintf($this->language->get('text_pagination'), ($seo_url_total) ? (($page - 1) * $this->config->get('config_admin_limit')) + 1 : 0, ((($page - 1) * $this->config->get('config_admin_limit')) > ($seo_url_total - $this->config->get('config_admin_limit'))) ? $seo_url_total : ((($page - 1) * $this->config->get('config_admin_limit')) + $this->config->get('config_admin_limit')), $seo_url_total, ceil($seo_url_total / $this->config->get('config_admin_limit')));

		$this->data['filter_keyword'] = $filter_keyword;
		$this->data['filter_type'] = $filter_type;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'localisation/seo_url_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['types'] = $this->types;

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');

		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		$this->data['entry_type'] = $this->language->get('entry_type');
		$this->data['entry_route'] = $this->language->get('entry_route');

		$this->data['help_keyword'] = $this->language->get('help_keyword');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['keyword'])) {
			$this->data['error_keyword'] = $this->error['keyword'];
		} else {
			$this->data['error_keyword'] = array();
		}

		if (isset($this->error['type'])) {
			$this->data['error_type'] = $this->error['type'];
		} else {
			$this->data['error_type'] = array();
		}

		if (isset($this->error['route'])) {
			$this->data['error_route'] = $this->error['route'];
		} else {
			$this->data['error_route'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_type'])) {
			$url .= '&filter_type=' . $this->request->get['filter_type'];
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
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['url_alias_id'])) {
			$this->data['action'] = $this->url->link('localisation/seo_url/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('localisation/seo_url/update', 'token=' . $this->session->data['token'] . '&url_alias_id=' . $this->request->get['url_alias_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('localisation/seo_url', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['url_alias_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$seo_url_info = $this->model_localisation_seo_url->getSeoUrl($this->request->get['url_alias_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (isset($seo_url_info)) {
			$this->data['keyword'] = $seo_url_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['type'])) {
			$this->data['type'] = $this->request->post['type'];
		} elseif (isset($seo_url_info)) {
			$parts = explode('=', $seo_url_info['query']);

			$this->data['type'] = $parts[0];
		} else {
			$this->data['type'] = '';
		}

		if (isset($this->request->post['route'])) {
			$this->data['route'] = $this->request->post['route'];
		} elseif (isset($seo_url_info)) {
			$parts = explode('=', $seo_url_info['query']);

			$this->data['route'] = $parts[1];
		} else {
			$this->data['route'] = '';
		}

		$this->template = 'localisation/seo_url_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['keyword']) < 1) || (utf8_strlen($this->request->post['keyword']) > 255)) {
			$this->error['keyword'] = $this->language->get('error_keyword');
		}

		if (!isset($this->types[$this->request->post['type']])) {
			$this->error['type'] = $this->language->get('error_type');
		}

		if ((utf8_strlen($this->request->post['route']) < 1) || (utf8_strlen($this->request->post['route']) > 200)) {
			$this->error['route'] = $this->language->get('error_route');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/seo_url')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>