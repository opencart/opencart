<?php
namespace Opencart\Admin\Controller\Design;
/**
 * Class Banner
 *
 * @package Opencart\Admin\Controller\Design
 */
class Banner extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
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
			'href' => $this->url->link('design/banner', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('design/banner.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('design/banner.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('design/banner.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('design/banner.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/banner', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('design/banner');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('design/banner.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Banners
		$data['banners'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('design/banner');

		$results = $this->model_design_banner->getBanners($filter_data);

		foreach ($results as $result) {
			$data['banners'][] = ['edit' => $this->url->link('design/banner.form', 'user_token=' . $this->session->data['user_token'] . '&banner_id=' . $result['banner_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sort
		$data['sort_name'] = $this->url->link('design/banner.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);

		$allowed = [
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Banners
		$banner_total = $this->model_design_banner->getTotalBanners();

		// Pagination
		$data['total'] = $banner_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('design/banner.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($banner_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($banner_total - $this->config->get('config_pagination_admin'))) ? $banner_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $banner_total, ceil($banner_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('design/banner_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('design/banner');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['banner_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
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
			'href' => $this->url->link('design/banner', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('design/banner.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('design/banner', 'user_token=' . $this->session->data['user_token'] . $url);

		// Banner
		if (isset($this->request->get['banner_id'])) {
			$this->load->model('design/banner');

			$banner_info = $this->model_design_banner->getBanner($this->request->get['banner_id']);
		}

		if (!empty($banner_info)) {
			$data['banner_id'] = $banner_info['banner_id'];
		} else {
			$data['banner_id'] = 0;
		}

		if (!empty($banner_info)) {
			$data['name'] = $banner_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($banner_info)) {
			$data['status'] = $banner_info['status'];
		} else {
			$data['status'] = true;
		}

		// Image
		$this->load->model('tool/image');

		$data['banner_images'] = [];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($data['languages'] as $language) {
			$banner_images = $this->model_design_banner->getImages($this->request->get['banner_id'], $language['language_id']);

			foreach ($banner_images as $banner_image) {
				if ($banner_image['image'] && is_file(DIR_IMAGE . html_entity_decode($banner_image['image'], ENT_QUOTES, 'UTF-8'))) {
					$image = $banner_image['image'];
					$thumb = $banner_image['image'];
				} else {
					$image = '';
					$thumb = 'no_image.png';
				}

				$data['banner_images'][$banner_image['language_id']][] = [
					'title'      => $banner_image['title'],
					'link'       => $banner_image['link'],
					'image'      => $image,
					'thumb'      => $this->model_tool_image->resize($thumb, $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height')),
					'sort_order' => $banner_image['sort_order']
				];
			}
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('design/banner_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('design/banner');

		$json = [];

		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'banner_id'    => 0,
			'banner_image' => [],
			'name'         => '',
			'status'       => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (isset($post_info['banner_image'])) {
			foreach ($post_info['banner_image'] as $language_id => $banner_image) {
				foreach ($banner_image as $key => $value) {
					if (!oc_validate_length($value['title'], 2, 64)) {
						$json['error']['image_' . $language_id . '_' . $key . '_title'] = $this->language->get('error_title');
					}

					if (!empty($value['link']) && !oc_validate_url($value['link'])) {
						$json['error']['image_' . $language_id . '_' . $key . '_link'] = $this->language->get('error_link');
					}
				}
			}
		}

		if (!$json) {
			// Banner
			$this->load->model('design/banner');

			if (!$post_info['banner_id']) {
				$json['banner_id'] = $this->model_design_banner->addBanner($post_info);
			} else {
				$this->model_design_banner->editBanner($post_info['banner_id'], $post_info);
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
		$this->load->language('design/banner');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/banner');

			foreach ($selected as $banner_id) {
				$this->model_design_banner->editStatus((int)$banner_id, true);
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
		$this->load->language('design/banner');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/banner');

			foreach ($selected as $banner_id) {
				$this->model_design_banner->editStatus((int)$banner_id, false);
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
		$this->load->language('design/banner');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'design/banner')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('design/banner');

			foreach ($selected as $banner_id) {
				$this->model_design_banner->deleteBanner($banner_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
