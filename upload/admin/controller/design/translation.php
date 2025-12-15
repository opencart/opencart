<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class Translation
 *
 * @package Opencart\Admin\Controller\Design
 */
class Translation extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return array
	 */
	public function index(): void {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/translation.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/translation.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('design/translation.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('design/translation.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['filter_store_id'] = $filter_store_id;
		$data['filter_language_id'] = $filter_language_id;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/translation');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('design/translation.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Language
		$this->load->model('localisation/language');

		// Translations
		$data['translations'] = [];

		$filter_data = [
			'filter_store_id'    => $filter_store_id,
			'filter_language_id' => $filter_language_id,
			'filter_status'      => $filter_status,
			'start'              => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'              => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/translation');

		$results = $this->model_design_translation->getTranslations($filter_data);

		foreach ($results as $result) {
			$language_info = $this->model_localisation_language->getLanguage($result['language_id']);

			if ($language_info) {
				$code = $language_info['code'];
				$image = $language_info['image'];
			} else {
				$code = '';
				$image = '';
			}

			$data['translations'][] = [
				'store'    => ($result['store_id'] ? $result['store'] : $this->language->get('text_default')),
				'image'    => $image,
				'language' => $code,
				'edit'     => $this->url->link('design/translation.form', 'user_token=' . $this->session->data['user_token'] . '&translation_id=' . $result['translation_id'])
			] + $result;
		}

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Translations
		$translation_total = $this->model_design_translation->getTotalTranslations();

		// Pagination
		$data['total'] = $translation_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('design/translation.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($translation_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($translation_total - $this->config->get('config_pagination_admin'))) ? $translation_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $translation_total, ceil($translation_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('design/translation_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('design/translation');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['translation_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/translation.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/translation', 'user_token=' . $this->session->data['user_token'] . $url);

		// Translation
		if (isset($this->request->get['translation_id'])) {
			$this->load->model('design/translation');

			$translation_info = $this->model_design_translation->getTranslation($this->request->get['translation_id']);
		}

		if (!empty($translation_info)) {
			$data['translation_id'] = $translation_info['translation_id'];
		} else {
			$data['translation_id'] = 0;
		}

		// Setting
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		if (!empty($translation_info)) {
			$data['store_id'] = $translation_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		// Languages
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

		if (!empty($translation_info)) {
			$data['status'] = $translation_info['status'];
		} else {
			$data['status'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/translation_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/translation');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!oc_validate_length($this->request->post['key'], 3, 64)) {
			$json['error']['key'] = $this->language->get('error_key');
		}

		if (!$json) {
			// Translation
			$this->load->model('design/translation');

			if (!$this->request->post['translation_id']) {
				$this->model_design_translation->addTranslation($this->request->post);
			} else {
				$this->model_design_translation->editTranslation($this->request->post['translation_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/translation');

			foreach ($selected as $translation_id) {
				$this->model_design_translation->editStatus((int)$translation_id, true);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/translation');

			foreach ($selected as $translation_id) {
				$this->model_design_translation->editStatus((int)$translation_id, false);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/translation')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Translation
			$this->load->model('design/translation');

			foreach ($selected as $translation_id) {
				$this->model_design_translation->deleteTranslation($translation_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Path
	 *
	 * @return void
	 */
	public function path(): void {
		$this->load->language('design/translation');

		$json = [];

		if (isset($this->request->get['language_id'])) {
			$language_id = (int)$this->request->get['language_id'];
		} else {
			$language_id = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		if (!empty($language_info)) {
			$directory = DIR_CATALOG . 'language/' . $language_info['code'] . '/';

			$files = oc_directory_read($directory, true, '/.+\.php$/');

			foreach ($files as $file) {
				$template = substr(substr($file, 0, strrpos($file, '.')), strlen($directory));

				if ($template) {
					$json[] = $template;
				}
			}

			$directories = oc_directory_read(DIR_EXTENSION, false);

			foreach ($directories as $directory) {
				$extension = basename($directory);

				$path = DIR_EXTENSION . $extension . '/catalog/language/' . $language_info['code'] . '/';

				$files = oc_directory_read($path, true, '/.+\.php/');

				foreach ($files as $file) {
					$language = substr(substr($file, 0, strrpos($file, '.')), strlen($path));

					if ($language) {
						$json[] = 'extension/' . $extension . '/' . $language;
					}
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Translation
	 *
	 * @return void
	 */
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

		// Language
		$this->load->model('localisation/language');

		$language_info = $this->model_localisation_language->getLanguage($language_id);

		$part = explode('/', $route);

		if ($part[0] != 'extension') {
			$directory = DIR_CATALOG . 'language/';
		} else {
			$directory = DIR_EXTENSION . $part[1] . '/catalog/language/';

			array_shift($part);
			// Don't remove. Required for extension route.
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
