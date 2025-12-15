<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/topic');

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
			'sort',
			'order',
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
			'href' => $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/topic.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/topic.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('cms/topic.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('cms/topic.disable', 'user_token=' . $this->session->data['user_token']);

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

		$this->response->setOutput($this->load->view('cms/topic', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/topic');

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

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
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
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Topics
		$data['topics'] = [];

		$filter_data = [
			'filter_store_id' => $filter_store_id,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'           => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/topic');

		$results = $this->model_cms_topic->getTopics($filter_data);

		foreach ($results as $result) {
			$data['topics'][] = ['edit' => $this->url->link('cms/topic.form', 'user_token=' . $this->session->data['user_token'] . '&topic_id=' . $result['topic_id'] . $url)] + $result;
		}

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_status'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_sort_order'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Topics
		$topic_total = $this->model_cms_topic->getTotalTopics($filter_data);

		// Pagination
		$data['total'] = $topic_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($topic_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($topic_total - $this->config->get('config_pagination_admin'))) ? $topic_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $topic_total, ceil($topic_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('cms/topic_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('cms/topic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['topic_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order',
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
			'href' => $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('cms/topic.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url);

		// Topic
		if (isset($this->request->get['topic_id'])) {
			$this->load->model('cms/topic');

			$topic_info = $this->model_cms_topic->getTopic($this->request->get['topic_id']);
		}

		if (!empty($topic_info)) {
			$data['topic_id'] = $topic_info['topic_id'];
		} else {
			$data['topic_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		// Image
		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		$data['topic_description'] = [];

		if (!empty($topic_info)) {
			$results = $this->model_cms_topic->getDescriptions($topic_info['topic_id']);

			foreach ($results as $key => $result) {
				$data['topic_description'][$key] = $result;

				if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['topic_description'][$key]['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
				} else {
					$data['topic_description'][$key]['thumb'] = $data['placeholder'];
				}
			}
		}

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		if (!empty($topic_info)) {
			$data['topic_store'] = $this->model_cms_topic->getStores($topic_info['topic_id']);
		} else {
			$data['topic_store'] = [0];
		}

		if (!empty($topic_info)) {
			$data['sort_order'] = $topic_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (!empty($topic_info)) {
			$data['status'] = $topic_info['status'];
		} else {
			$data['status'] = true;
		}

		// SEO
		if (!empty($topic_info)) {
			$this->load->model('design/seo_url');

			$data['topic_seo_url'] = $this->model_design_seo_url->getSeoUrlsByKeyValue('topic_id', $topic_info['topic_id']);
		} else {
			$data['topic_seo_url'] = [];
		}

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (!empty($topic_info)) {
			$data['topic_layout'] = $this->model_cms_topic->getLayouts($topic_info['topic_id']);
		} else {
			$data['topic_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/topic_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/topic');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'topic_id'          => 0,
			'topic_description' => [],
			'sort_order'        => 0,
			'status'            => 1,
			'topic_store'       => [],
			'topic_seo_url'     => [],
			'topic_layout'      => []
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['topic_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if (!oc_validate_length($value['meta_title'], 1, 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		// SEO
		if ($post_info['topic_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($post_info['topic_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!oc_validate_length($keyword, 1, 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (!oc_validate_path($keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!$post_info['topic_id'] || $seo_url_info['key'] != 'topic_id' || $seo_url_info['value'] != (int)$post_info['topic_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Topic
			$this->load->model('cms/topic');

			if (!$post_info['topic_id']) {
				$json['topic_id'] = $this->model_cms_topic->addTopic($post_info);
			} else {
				$this->model_cms_topic->editTopic($post_info['topic_id'], $post_info);
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
		$this->load->language('cms/topic');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/topic');

			foreach ($selected as $topic_id) {
				$this->model_cms_topic->editStatus((int)$topic_id, true);
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
		$this->load->language('cms/topic');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/topic');

			foreach ($selected as $topic_id) {
				$this->model_cms_topic->editStatus((int)$topic_id, false);
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
		$this->load->language('cms/topic');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Topic
			$this->load->model('cms/topic');

			foreach ($selected as $topic_id) {
				$this->model_cms_topic->deleteTopic($topic_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
