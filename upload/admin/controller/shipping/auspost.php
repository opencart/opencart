<?php
class ControllerShippingAusPost extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/auspost');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('auspost', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_postcode'] = $this->language->get('entry_postcode');
		$data['entry_standard'] = $this->language->get('entry_standard');
		$data['entry_express'] = $this->language->get('entry_express');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_display_time'] = $this->language->get('help_display_time');
		$data['help_weight_class'] = $this->language->get('help_weight_class');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_shipping'),
			'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('shipping/auspost', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/auspost', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['auspost_postcode'])) {
			$data['auspost_postcode'] = $this->request->post['auspost_postcode'];
		} else {
			$data['auspost_postcode'] = $this->config->get('auspost_postcode');
		}

		if (isset($this->request->post['auspost_standard'])) {
			$data['auspost_standard'] = $this->request->post['auspost_standard'];
		} else {
			$data['auspost_standard'] = $this->config->get('auspost_standard');
		}

		if (isset($this->request->post['auspost_express'])) {
			$data['auspost_express'] = $this->request->post['auspost_express'];
		} else {
			$data['auspost_express'] = $this->config->get('auspost_express');
		}

		if (isset($this->request->post['auspost_display_time'])) {
			$data['auspost_display_time'] = $this->request->post['auspost_display_time'];
		} else {
			$data['auspost_display_time'] = $this->config->get('auspost_display_time');
		}

		if (isset($this->request->post['auspost_weight_class_id'])) {
			$data['auspost_weight_class_id'] = $this->request->post['auspost_weight_class_id'];
		} else {
			$data['auspost_weight_class_id'] = $this->config->get('auspost_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['auspost_tax_class_id'])) {
			$data['auspost_tax_class_id'] = $this->request->post['auspost_tax_class_id'];
		} else {
			$data['auspost_tax_class_id'] = $this->config->get('auspost_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['auspost_geo_zone_id'])) {
			$data['auspost_geo_zone_id'] = $this->request->post['auspost_geo_zone_id'];
		} else {
			$data['auspost_geo_zone_id'] = $this->config->get('auspost_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['auspost_status'])) {
			$data['auspost_status'] = $this->request->post['auspost_status'];
		} else {
			$data['auspost_status'] = $this->config->get('auspost_status');
		}

		if (isset($this->request->post['auspost_sort_order'])) {
			$data['auspost_sort_order'] = $this->request->post['auspost_sort_order'];
		} else {
			$data['auspost_sort_order'] = $this->config->get('auspost_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/auspost.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/auspost')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!preg_match('/^[0-9]{4}$/', $this->request->post['auspost_postcode'])) {
			$this->error['postcode'] = $this->language->get('error_postcode');
		}

		return !$this->error;
	}
}