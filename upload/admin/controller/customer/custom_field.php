<?php
namespace Opencart\Admin\Controller\Customer;
/**
 * Class Custom Field
 *
 * @package Opencart\Admin\Controller\Customer
 */
class CustomField extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('customer/custom_field');

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
			'href' => $this->url->link('customer/custom_field', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('customer/custom_field.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('customer/custom_field.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/custom_field', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('customer/custom_field');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'cfd.name';
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

		$data['action'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['custom_fields'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('customer/custom_field');

		$custom_field_total = $this->model_customer_custom_field->getTotalCustomFields();

		$results = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($results as $result) {
			$type = '';

			switch ($result['type']) {
				case 'select':
					$type = $this->language->get('text_select');
					break;
				case 'radio':
					$type = $this->language->get('text_radio');
					break;
				case 'checkbox':
					$type = $this->language->get('text_checkbox');
					break;
				case 'input':
					$type = $this->language->get('text_input');
					break;
				case 'text':
					$type = $this->language->get('text_text');
					break;
				case 'textarea':
					$type = $this->language->get('text_textarea');
					break;
				case 'file':
					$type = $this->language->get('text_file');
					break;
				case 'date':
					$type = $this->language->get('text_date');
					break;
				case 'datetime':
					$type = $this->language->get('text_datetime');
					break;
				case 'time':
					$type = $this->language->get('text_time');
					break;
			}

			$data['custom_fields'][] = [
				'custom_field_id' => $result['custom_field_id'],
				'name'            => $result['name'],
				'location'        => $this->language->get('text_' . $result['location']),
				'type'            => $type,
				'status'          => $result['status'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->url->link('customer/custom_field.form', 'user_token=' . $this->session->data['user_token'] . '&custom_field_id=' . $result['custom_field_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . '&sort=cfd.name' . $url);
		$data['sort_location'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . '&sort=cf.location' . $url);
		$data['sort_type'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . '&sort=cf.type' . $url);
		$data['sort_status'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . '&sort=cf.status' . $url);
		$data['sort_sort_order'] = $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . '&sort=cf.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $custom_field_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('customer/custom_field.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($custom_field_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($custom_field_total - $this->config->get('config_pagination_admin'))) ? $custom_field_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $custom_field_total, ceil($custom_field_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('customer/custom_field_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('customer/custom_field');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['custom_field_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('customer/custom_field', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('customer/custom_field.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('customer/custom_field', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['custom_field_id'])) {
			$this->load->model('customer/custom_field');

			$custom_field_info = $this->model_customer_custom_field->getCustomField($this->request->get['custom_field_id']);
		}

		if (isset($this->request->get['custom_field_id'])) {
			$data['custom_field_id'] = (int)$this->request->get['custom_field_id'];
		} else {
			$data['custom_field_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['custom_field_id'])) {
			$data['custom_field_description'] = $this->model_customer_custom_field->getDescriptions($this->request->get['custom_field_id']);
		} else {
			$data['custom_field_description'] = [];
		}

		if (!empty($custom_field_info)) {
			$data['location'] = $custom_field_info['location'];
		} else {
			$data['location'] = '';
		}

		if (!empty($custom_field_info)) {
			$data['type'] = $custom_field_info['type'];
		} else {
			$data['type'] = '';
		}

		if (!empty($custom_field_info)) {
			$data['value'] = $custom_field_info['value'];
		} else {
			$data['value'] = '';
		}

		if (!empty($custom_field_info)) {
			$data['validation'] = $custom_field_info['validation'];
		} else {
			$data['validation'] = '';
		}

		if (!empty($custom_field_info)) {
			$data['status'] = $custom_field_info['status'];
		} else {
			$data['status'] = '';
		}

		if (!empty($custom_field_info)) {
			$data['sort_order'] = $custom_field_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if (isset($this->request->get['custom_field_id'])) {
			$custom_field_values = $this->model_customer_custom_field->getValueDescriptions($this->request->get['custom_field_id']);
		} else {
			$custom_field_values = [];
		}

		$data['custom_field_values'] = [];

		foreach ($custom_field_values as $custom_field_value) {
			$data['custom_field_values'][] = [
				'custom_field_value_id'          => $custom_field_value['custom_field_value_id'],
				'custom_field_value_description' => $custom_field_value['custom_field_value_description'],
				'sort_order'                     => $custom_field_value['sort_order']
			];
		}

		if (isset($this->request->get['custom_field_id'])) {
			$custom_field_customer_groups = $this->model_customer_custom_field->getCustomerGroups($this->request->get['custom_field_id']);
		} else {
			$custom_field_customer_groups = [];
		}

		$data['custom_field_customer_group'] = [];

		foreach ($custom_field_customer_groups as $custom_field_customer_group) {
			if (isset($custom_field_customer_group['customer_group_id'])) {
				$data['custom_field_customer_group'][] = $custom_field_customer_group['customer_group_id'];
			}
		}

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['custom_field_required'] = [];

		foreach ($custom_field_customer_groups as $custom_field_customer_group) {
			if (isset($custom_field_customer_group['required']) && $custom_field_customer_group['required'] && isset($custom_field_customer_group['customer_group_id'])) {
				$data['custom_field_required'][] = $custom_field_customer_group['customer_group_id'];
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/custom_field_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('customer/custom_field');

		$json = [];

		if (!$this->user->hasPermission('modify', 'customer/custom_field')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['custom_field_description'] as $language_id => $value) {
			if ((oc_strlen($value['name']) < 1) || (oc_strlen($value['name']) > 128)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox')) {
			if (!isset($this->request->post['custom_field_value'])) {
				$json['error']['warning'] = $this->language->get('error_type');
			}

			if (isset($this->request->post['custom_field_value'])) {
				foreach ($this->request->post['custom_field_value'] as $custom_field_value_id => $custom_field_value) {
					foreach ($custom_field_value['custom_field_value_description'] as $language_id => $custom_field_value_description) {
						if ((oc_strlen($custom_field_value_description['name']) < 1) || (oc_strlen($custom_field_value_description['name']) > 128)) {
							$json['error']['custom_field_value_' . $custom_field_value_id . '_' . $language_id] = $this->language->get('error_custom_value');
						}
					}
				}
			}
		}

		if ($this->request->post['type'] == 'text' && $this->request->post['validation'] && @preg_match(html_entity_decode($this->request->post['validation'], ENT_QUOTES, 'UTF-8'), null) === false) {
			$json['error']['validation'] = $this->language->get('error_validation');
		}

		if (!$json) {
			$this->load->model('customer/custom_field');

			if (!$this->request->post['custom_field_id']) {
				$json['custom_field_id'] = $this->model_customer_custom_field->addCustomField($this->request->post);
			} else {
				$this->model_customer_custom_field->editCustomField($this->request->post['custom_field_id'], $this->request->post);
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
		$this->load->language('customer/custom_field');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'customer/custom_field')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('customer/custom_field');

			foreach ($selected as $custom_field_id) {
				$this->model_customer_custom_field->deleteCustomField($custom_field_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
