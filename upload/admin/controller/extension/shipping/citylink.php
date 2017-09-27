\<?php
class ControllerExtensionShippingCitylink extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/citylink');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_citylink', $this->request->post);

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
			'href' => $this->url->link('extension/shipping/citylink', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/citylink', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_citylink_rate'])) {
			$data['shipping_citylink_rate'] = $this->request->post['shipping_citylink_rate'];
		} elseif ($this->config->get('shipping_citylink_rate')) {
			$data['shipping_citylink_rate'] = $this->config->get('shipping_citylink_rate');
		} else {
			$data['shipping_citylink_rate'] = '10:11.6,15:14.1,20:16.60,25:19.1,30:21.6,35:24.1,40:26.6,45:29.1,50:31.6,55:34.1,60:36.6,65:39.1,70:41.6,75:44.1,80:46.6,100:56.6,125:69.1,150:81.6,200:106.6';
		}

		if (isset($this->request->post['shipping_citylink_tax_class_id'])) {
			$data['shipping_citylink_tax_class_id'] = $this->request->post['shipping_citylink_tax_class_id'];
		} else {
			$data['shipping_citylink_tax_class_id'] = $this->config->get('shipping_citylink_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_citylink_geo_zone_id'])) {
			$data['shipping_citylink_geo_zone_id'] = $this->request->post['shipping_citylink_geo_zone_id'];
		} else {
			$data['shipping_citylink_geo_zone_id'] = $this->config->get('shipping_citylink_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_citylink_status'])) {
			$data['shipping_citylink_status'] = $this->request->post['shipping_citylink_status'];
		} else {
			$data['shipping_citylink_status'] = $this->config->get('shipping_citylink_status');
		}

		if (isset($this->request->post['shipping_citylink_sort_order'])) {
			$data['shipping_citylink_sort_order'] = $this->request->post['shipping_citylink_sort_order'];
		} else {
			$data['shipping_citylink_sort_order'] = $this->config->get('shipping_citylink_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/citylink', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/citylink')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}