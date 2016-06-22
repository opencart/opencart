<?php
class ControllerExtensionShippingParcelforce48 extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/parcelforce_48');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('parcelforce_48', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_rate'] = $this->language->get('entry_rate');
		$data['entry_insurance'] = $this->language->get('entry_insurance');
		$data['entry_display_weight'] = $this->language->get('entry_display_weight');
		$data['entry_display_insurance'] = $this->language->get('entry_display_insurance');
		$data['entry_display_time'] = $this->language->get('entry_display_time');
		$data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_rate'] = $this->language->get('help_rate');
		$data['help_insurance'] = $this->language->get('help_insurance');
		$data['help_display_weight'] = $this->language->get('help_display_weight');
		$data['help_display_insurance'] = $this->language->get('help_display_insurance');
		$data['help_display_time'] = $this->language->get('help_display_time');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/parcelforce_48', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/parcelforce_48', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

		if (isset($this->request->post['parcelforce_48_rate'])) {
			$data['parcelforce_48_rate'] = $this->request->post['parcelforce_48_rate'];
		} elseif ($this->config->get('parcelforce_48_rate')) {
			$data['parcelforce_48_rate'] = $this->config->get('parcelforce_48_rate');
		} else {
			$data['parcelforce_48_rate'] = '10:15.99,12:19.99,14:20.99,16:21.99,18:21.99,20:21.99,22:26.99,24:30.99,26:34.99,28:38.99,30:42.99,35:52.99,40:62.99,45:72.99,50:82.99,55:92.99,60:102.99,65:112.99,70:122.99,75:132.99,80:142.99,85:152.99,90:162.99,95:172.99,100:182.99';
		}

		if (isset($this->request->post['parcelforce_48_insurance'])) {
			$data['parcelforce_48_insurance'] = $this->request->post['parcelforce_48_insurance'];
		} elseif ($this->config->get('parcelforce_48_insurance')) {
			$data['parcelforce_48_insurance'] = $this->config->get('parcelforce_48_insurance');
		} else {
			$data['parcelforce_48_insurance'] = '150:0,500:12,1000:24,1500:36,2000:48,2500:60';
		}

		if (isset($this->request->post['parcelforce_48_display_weight'])) {
			$data['parcelforce_48_display_weight'] = $this->request->post['parcelforce_48_display_weight'];
		} else {
			$data['parcelforce_48_display_weight'] = $this->config->get('parcelforce_48_display_weight');
		}

		if (isset($this->request->post['parcelforce_48_display_insurance'])) {
			$data['parcelforce_48_display_insurance'] = $this->request->post['parcelforce_48_display_insurance'];
		} else {
			$data['parcelforce_48_display_insurance'] = $this->config->get('parcelforce_48_display_insurance');
		}

		if (isset($this->request->post['parcelforce_48_display_time'])) {
			$data['parcelforce_48_display_time'] = $this->request->post['parcelforce_48_display_time'];
		} else {
			$data['parcelforce_48_display_time'] = $this->config->get('parcelforce_48_display_time');
		}

		if (isset($this->request->post['parcelforce_48_tax_class_id'])) {
			$data['parcelforce_48_tax_class_id'] = $this->request->post['parcelforce_48_tax_class_id'];
		} else {
			$data['parcelforce_48_tax_class_id'] = $this->config->get('parcelforce_48_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['parcelforce_48_geo_zone_id'])) {
			$data['parcelforce_48_geo_zone_id'] = $this->request->post['parcelforce_48_geo_zone_id'];
		} else {
			$data['parcelforce_48_geo_zone_id'] = $this->config->get('parcelforce_48_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['parcelforce_48_status'])) {
			$data['parcelforce_48_status'] = $this->request->post['parcelforce_48_status'];
		} else {
			$data['parcelforce_48_status'] = $this->config->get('parcelforce_48_status');
		}

		if (isset($this->request->post['parcelforce_48_sort_order'])) {
			$data['parcelforce_48_sort_order'] = $this->request->post['parcelforce_48_sort_order'];
		} else {
			$data['parcelforce_48_sort_order'] = $this->config->get('parcelforce_48_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/parcelforce_48', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/parcelforce_48')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}