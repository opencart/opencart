<?php
class ControllerShippingCitylink extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/citylink');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('citylink', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');

		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_rate'] = $this->language->get('help_rate');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
			'href' => $this->url->link('shipping/citylink', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('shipping/citylink', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['citylink_rate'])) {
			$data['citylink_rate'] = $this->request->post['citylink_rate'];
		} elseif ($this->config->get('citylink_rate')) {
			$data['citylink_rate'] = $this->config->get('citylink_rate');
		} else {
			$data['citylink_rate'] = '10:11.6,15:14.1,20:16.60,25:19.1,30:21.6,35:24.1,40:26.6,45:29.1,50:31.6,55:34.1,60:36.6,65:39.1,70:41.6,75:44.1,80:46.6,100:56.6,125:69.1,150:81.6,200:106.6';
		}

		if (isset($this->request->post['citylink_tax_class_id'])) {
			$data['citylink_tax_class_id'] = $this->request->post['citylink_tax_class_id'];
		} else {
			$data['citylink_tax_class_id'] = $this->config->get('citylink_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['citylink_geo_zone_id'])) {
			$data['citylink_geo_zone_id'] = $this->request->post['citylink_geo_zone_id'];
		} else {
			$data['citylink_geo_zone_id'] = $this->config->get('citylink_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['citylink_status'])) {
			$data['citylink_status'] = $this->request->post['citylink_status'];
		} else {
			$data['citylink_status'] = $this->config->get('citylink_status');
		}

		if (isset($this->request->post['citylink_sort_order'])) {
			$data['citylink_sort_order'] = $this->request->post['citylink_sort_order'];
		} else {
			$data['citylink_sort_order'] = $this->config->get('citylink_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/citylink.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/citylink')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}