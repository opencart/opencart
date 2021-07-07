<?php
namespace Opencart\Admin\Controller\Design;
class Translation extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/translation|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/translation|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation', $data));
	}

	public function list(): void {
		$this->load->language('design/translation');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
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

		$data['action'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('localisation/language');

		$data['translations'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/translation');

		$translation_total = $this->model_design_translation->getTotalTranslations();

		$results = $this->model_design_translation->getTranslations($filter_data);

		foreach ($results as $result) {
			$language_info = $this->model_localisation_language->getLanguage($result['language_id']);

			if ($language_info) {
				$code = $language_info['code'];
			} else {
				$code = '';
			}

			$data['translations'][] = [
				'translation_id' => $result['translation_id'],
				'store'          => ($result['store_id'] ? $result['store'] : $this->language->get('text_default')),
				'route'          => $result['route'],
				'language'       => $code,
				'key'            => $result['key'],
				'value'          => $result['value'],
				'edit'           => $this->url->link('design/translation|form', 'user_token=' . $this->session->data['user_token'] . '&translation_id=' . $result['translation_id'])
			];
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

		$data['sort_store'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . '&sort=store' . $url);
		$data['sort_language'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . '&sort=language' . $url);
		$data['sort_route'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . '&sort=route' . $url);
		$data['sort_key'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . '&sort=key' . $url);
		$data['sort_value'] = $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . '&sort=value' . $url);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $translation_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('design/translation|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($translation_total - $this->config->get('config_pagination_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $translation_total, ceil($translation_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('design/translation_list', $data);
	}

	public function form(): void {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['translation_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/translation|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['translation_id'])) {
			$this->load->model('design/translation');

			$translation_info = $this->model_design_translation->getTranslation($this->request->get['translation_id']);
		}

		if (isset($this->request->get['translation_id'])) {
			$data['translation_id'] = (int)$this->request->get['translation_id'];
		} else {
			$data['translation_id'] = 0;
		}

		$this->load->model('setting/store');

		$data['stores'] = $this->model_setting_store->getStores();

		if (!empty($translation_info)) {
			$data['store_id'] = $translation_info['store_id'];
		} else {
			$data['store_id'] = '';
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($translation_info)) {
			$data['language_id'] = $translation_info['language_id'];
		} else {
			$data['language_id'] = '';
		}

		if (!empty($translation_info)) {
			$data['route'] = $translation_info['route'];
		} else {
			$data['route'] = '';
		}

		if (!empty($translation_info)) {
			$data['key'] = $translation_info['key'];
		} else {
			$data['key'] = '';
		}

		if (!empty($translation_info)) {
			$data['value'] = $translation_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_form', $data));
	}

	public function save(): void {
		$this->load->language('design/translation');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen(trim($this->request->post['key'])) < 3) || (utf8_strlen($this->request->post['key']) > 64)) {
			$json['error']['key'] = $this->language->get('error_key');
		}

		if (!$json) {
			$this->load->model('design/translation');

			if (!$this->request->post['translation_id']) {
				$json['translation_id'] = $this->model_design_translation->addTranslation($this->request->post);
			} else {
				$this->model_design_translation->editTranslation($this->request->post['translation_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/translation');

			foreach ($selected as $translation_id) {
				$this->model_design_translation->deleteTranslation($translation_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function path(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->get['language_id'])) {
			$language_id = (int)$this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		if (!empty($language_info)) {
			$path = glob(DIR_CATALOG . 'language/' . $language_info['code'] . '/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					if (substr($file, -4) == '.php') {
						$json[] = substr(substr($file, strlen(DIR_CATALOG . 'language/' . $language_info['code'] . '/')), 0, -4);
					}
				}
			}

			$path = glob(DIR_EXTENSION . '*/catalog/language/' . $language_info['code'] . '/*');

			while (count($path) != 0) {
				$next = array_shift($path);

				foreach ((array)glob($next) as $file) {
					if (is_dir($file)) {
						$path[] = $file . '/*';
					}

					if (substr($file, -4) == '.php') {
						$new_path = substr($file, strlen(DIR_EXTENSION));

						$code = substr($new_path, 0, strpos($new_path, '/'));

						$length = strlen(DIR_EXTENSION . $code . '/catalog/language/' . $language_info['code'] . '/');

						$route = substr(substr($file, $length), 0, -4);

						$json[] = 'extension/' . $code . '/' . $route;
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function translation(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		if (isset($this->request->get['language_id'])) {
			$language_id = (int)$this->request->get['language_id'];
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

		$part = explode('/', $route);

		if ($part[0] != 'extension') {
			$directory = DIR_CATALOG . 'language/';
		} else {
			$directory = DIR_EXTENSION . $part[1] . '/catalog/language/';

			array_shift($part);
			array_shift($part);

			$route = implode('/', $part);
		}

		if ($language_info && is_file($directory . $language_info['code'] . '/' . $route . '.php') && substr(str_replace('\\', '/', realpath($directory . $language_info['code'] . '/' . $route . '.php')), 0, strlen($directory)) == str_replace('\\', '/', $directory)) {
			$_ = [];

			include($directory . $language_info['code'] . '/' . $route . '.php');

			foreach ($_ as $key => $value) {
				$json[] = [
					'key'   => $key,
					'value' => $value
				];
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
