<?php
class ControllerDesignTranslation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		$this->getList();
	}

	public function add() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_translation->addTranslation($this->request->post);

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

			$this->response->redirect($this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_design_translation->editTranslation($this->request->get['translation_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('design/translation');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $translation_id) {
				$this->model_design_translation->deleteTranslation($translation_id);
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

			$this->response->redirect($this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'store';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'], true)
		);

		$this->load->model('localisation/language');

		$data['add'] = $this->url->link('design/translation/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('design/translation/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['translations'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$translation_total = $this->model_design_translation->getTotalTranslations();

		$results = $this->model_design_translation->getTranslations($filter_data);

		foreach ($results as $result) {
			$data['translations'][] = array(
				'translation_id' => $result['translation_id'],
				'store'          => ($result['store_id'] ? $result['store'] : $this->language->get('text_default')),
				'route'          => $result['route'],
				'language'       => $result['language'],
				'key'            => $result['key'],
				'value'          => $result['value'],
				'edit'           => $this->url->link('design/translation/edit', 'user_token=' . $this->session->data['user_token'] . '&translation_id=' . $result['translation_id'], true),
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_store'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . '&sort=store' . $url, true);
		$data['sort_language'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . '&sort=language' . $url, true);
		$data['sort_route'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . '&sort=route' . $url, true);
		$data['sort_key'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . '&sort=key' . $url, true);
		$data['sort_value'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . '&sort=value' . $url, true);

		$pagination = new Pagination();
		$pagination->total = $translation_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('design/translation/history', 'user_token=' . $this->session->data['user_token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($translation_total - $this->config->get('config_limit_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $translation_total, ceil($translation_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['translation_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
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
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['translation_id'])) {
			$data['action'] = $this->url->link('design/translation/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('design/translation/edit', 'user_token=' . $this->session->data['user_token'] . '&translation_id=' . $this->request->get['translation_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->get['translation_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$translation_info = $this->model_design_translation->getTranslation($this->request->get['translation_id']);
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		} elseif (!empty($translation_info)) {
			$data['store_id'] = $translation_info['store_id'];
		} else {
			$data['store_id'] = '';
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($translation_info)) {
			$language = $this->model_localisation_language->getLanguage($translation_info['language_id']);
			$code = $language['code'];
		} else {
			$code = $this->config->get('config_language');
			$language = $this->model_localisation_language->getLanguageByCode($code);
		}

		if (isset($this->request->post['language_id'])) {
			$data['language_id'] = $this->request->post['language_id'];
		} elseif (!empty($translation_info)) {
			$data['language_id'] = $translation_info['language_id'];
		} else {
			$data['language_id'] = $language['language_id'];
		}

		if (empty($translation_info)) {
			// Get a list of files ready to upload
			$data['paths'] = array();

			$path = glob(DIR_CATALOG . 'language/'.$code.'/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					if (substr($file, -4) == '.php') {
						$data['paths'][] = substr(substr($file, strlen(DIR_CATALOG . 'language/'.$code.'/')), 0, -4);
					}
				}
			}
		}

		if (isset($this->request->post['route'])) {
			$data['route'] = $this->request->post['route'];
		} elseif (!empty($translation_info)) {
			$data['route'] = $translation_info['route'];
		} else {
			$data['route'] = '';
		}

		if (isset($this->request->post['key'])) {
			$data['key'] = $this->request->post['key'];
		} elseif (!empty($translation_info)) {
			$data['key'] = $translation_info['key'];
		} else {
			$data['key'] = '';
		}

		if (!empty($translation_info)) {
			$directory = DIR_CATALOG . 'language/';

			if (is_file($directory . $code . '/' . $translation_info['route'] . '.php') && substr(str_replace('\\', '/', realpath($directory . $code . '/' . $translation_info['route'] . '.php')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
				$_ = array();

				include($directory . $code . '/' . $translation_info['route'] . '.php');

				foreach ($_ as $key => $value) {
					if ($translation_info['key'] == $key) {
						$data['default'] = $value;
					}
				}

				if (empty($data['default'])) {
					$data['default'] = $translation_info['value'];
				}
			}
		}

		if (isset($this->request->post['value'])) {
			$data['value'] = $this->request->post['value'];
		} elseif (!empty($translation_info)) {
			$data['value'] = $translation_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['key']) < 3) || (utf8_strlen($this->request->post['key']) > 64)) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function path() {
		$this->load->language('design/translation');

		$json = array();

		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		if (!empty($language_info)) {
			$path = glob(DIR_CATALOG . 'language/'.$language_info['code'].'/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					if (substr($file, -4) == '.php') {
						$json[] = substr(substr($file, strlen(DIR_CATALOG . 'language/'.$language_info['code'].'/')), 0, -4);
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function translation() {
		$this->load->language('design/translation');

		$json = array();

		if (isset($this->request->get['store_id'])) {
			$store_id = $this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['language_id'])) {
			$language_id = $this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		if (isset($this->request->get['path'])) {
			$route = $this->request->get['path'];
		} else {
			$route = '';
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		$directory = DIR_CATALOG . 'language/';

		if ($language_info && is_file($directory . $language_info['code'] . '/' . $route . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $route . '.php')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			$_ = array();

			include($directory . $language_info['code'] . '/' . $route . '.php');

			foreach ($_ as $key => $value) {
				$json[] = array(
					'key'   => $key,
					'value' => $value
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
