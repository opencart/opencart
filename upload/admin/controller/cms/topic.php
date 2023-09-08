<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Topic
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Topic extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/topic');

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
			'href' => $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/topic.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/topic.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/topic', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/topic');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 't.sort_order';
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

		$data['action'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['topics'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/topic');

		$topic_total = $this->model_cms_topic->getTotalTopics();

		$results = $this->model_cms_topic->getTopics($filter_data);

		foreach ($results as $result) {
			$data['topics'][] = [
				'topic_id'   => $result['topic_id'],
				'name'       => $result['name'],
				'status'     => $result['status'],
				'sort_order' => $result['sort_order'],
				'edit'       => $this->url->link('cms/topic.form', 'user_token=' . $this->session->data['user_token'] . '&topic_id=' . $result['topic_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . '&sort=bcd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . '&sort=bc.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $topic_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('cms/topic.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($topic_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($topic_total - $this->config->get('config_pagination_admin'))) ? $topic_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $topic_total, ceil($topic_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('cms/topic_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('cms/topic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['topic_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('cms/topic.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('cms/topic', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['topic_id'])) {
			$this->load->model('cms/topic');

			$topic_info = $this->model_cms_topic->getTopic($this->request->get['topic_id']);
		}

		if (isset($this->request->get['topic_id'])) {
			$data['topic_id'] = (int)$this->request->get['topic_id'];
		} else {
			$data['topic_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['topic_description'] = [];

		if (isset($this->request->get['topic_id'])) {
			$results = $this->model_cms_topic->getDescriptions($this->request->get['topic_id']);

			foreach ($results as $key => $result) {
				$data['topic_description'][$key] = $result;

				if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['topic_description'][$key]['thumb'] = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
				} else {
					$data['topic_description'][$key]['thumb'] = $data['placeholder'];
				}
			}
		}

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		if (isset($this->request->get['topic_id'])) {
			$data['topic_store'] = $this->model_cms_topic->getStores($this->request->get['topic_id']);
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

		if (isset($this->request->get['topic_id'])) {
			$data['topic_seo_url'] = $this->model_cms_topic->getSeoUrls($this->request->get['topic_id']);
		} else {
			$data['topic_seo_url'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/topic_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/topic');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['topic_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if ((oc_strlen(trim($value['meta_title'])) < 1) || (oc_strlen($value['meta_title']) > 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($this->request->post['topic_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['topic_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ((oc_strlen(trim($keyword)) < 1) || (oc_strlen($keyword) > 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (preg_match('/[^a-zA-Z0-9\/_-]|[\p{Cyrillic}]+/u', $keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($this->request->post['topic_id']) || $seo_url_info['key'] != 'topic_id' || $seo_url_info['value'] != (int)$this->request->post['topic_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('cms/topic');

			if (!$this->request->post['topic_id']) {
				$json['topic_id'] = $this->model_cms_topic->addTopic($this->request->post);
			} else {
				$this->model_cms_topic->editTopic($this->request->post['topic_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('cms/topic');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/topic')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
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
