<?php
class ControllerExtensionShippingParcelforce48 extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/parcelforce_48');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_parcelforce_48', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/parcelforce_48', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/parcelforce_48', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_parcelforce_48_rate'])) {
			$data['shipping_parcelforce_48_rate'] = $this->request->post['shipping_parcelforce_48_rate'];
		} elseif ($this->config->get('shipping_parcelforce_48_rate')) {
			$data['shipping_parcelforce_48_rate'] = $this->config->get('shipping_parcelforce_48_rate');
		} else {
			$data['shipping_parcelforce_48_rate'] = '10:15.99,12:19.99,14:20.99,16:21.99,18:21.99,20:21.99,22:26.99,24:30.99,26:34.99,28:38.99,30:42.99,35:52.99,40:62.99,45:72.99,50:82.99,55:92.99,60:102.99,65:112.99,70:122.99,75:132.99,80:142.99,85:152.99,90:162.99,95:172.99,100:182.99';
		}

		if (isset($this->request->post['shipping_parcelforce_48_insurance'])) {
			$data['shipping_parcelforce_48_insurance'] = $this->request->post['shipping_parcelforce_48_insurance'];
		} elseif ($this->config->get('shipping_parcelforce_48_insurance')) {
			$data['shipping_parcelforce_48_insurance'] = $this->config->get('shipping_parcelforce_48_insurance');
		} else {
			$data['shipping_parcelforce_48_insurance'] = '150:0,500:12,1000:24,1500:36,2000:48,2500:60';
		}

		if (isset($this->request->post['shipping_parcelforce_48_display_weight'])) {
			$data['shipping_parcelforce_48_display_weight'] = $this->request->post['shipping_parcelforce_48_display_weight'];
		} else {
			$data['shipping_parcelforce_48_display_weight'] = $this->config->get('shipping_parcelforce_48_display_weight');
		}

		if (isset($this->request->post['shipping_parcelforce_48_display_insurance'])) {
			$data['shipping_parcelforce_48_display_insurance'] = $this->request->post['shipping_parcelforce_48_display_insurance'];
		} else {
			$data['shipping_parcelforce_48_display_insurance'] = $this->config->get('shipping_parcelforce_48_display_insurance');
		}

		if (isset($this->request->post['shipping_parcelforce_48_display_time'])) {
			$data['shipping_parcelforce_48_display_time'] = $this->request->post['shipping_parcelforce_48_display_time'];
		} else {
			$data['shipping_parcelforce_48_display_time'] = $this->config->get('shipping_parcelforce_48_display_time');
		}

		if (isset($this->request->post['shipping_parcelforce_48_tax_class_id'])) {
			$data['shipping_parcelforce_48_tax_class_id'] = $this->request->post['shipping_parcelforce_48_tax_class_id'];
		} else {
			$data['shipping_parcelforce_48_tax_class_id'] = $this->config->get('shipping_parcelforce_48_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_parcelforce_48_geo_zone_id'])) {
			$data['shipping_parcelforce_48_geo_zone_id'] = $this->request->post['shipping_parcelforce_48_geo_zone_id'];
		} else {
			$data['shipping_parcelforce_48_geo_zone_id'] = $this->config->get('shipping_parcelforce_48_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_parcelforce_48_status'])) {
			$data['shipping_parcelforce_48_status'] = $this->request->post['shipping_parcelforce_48_status'];
		} else {
			$data['shipping_parcelforce_48_status'] = $this->config->get('shipping_parcelforce_48_status');
		}

		if (isset($this->request->post['shipping_parcelforce_48_sort_order'])) {
			$data['shipping_parcelforce_48_sort_order'] = $this->request->post['shipping_parcelforce_48_sort_order'];
		} else {
			$data['shipping_parcelforce_48_sort_order'] = $this->config->get('shipping_parcelforce_48_sort_order');
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