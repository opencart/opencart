<?php
class ControllerExtensionShippingECShip extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/ec_ship');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_ec_ship', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['api_username'])) {
			$data['error_api_username'] = $this->error['entry_api_username'];
		} else {
			$data['error_api_username'] = '';
		}

		if (isset($this->error['api_key'])) {
			$data['error_api_key'] = $this->error['api_key'];
		} else {
			$data['error_api_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/ec_ship', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/shipping/ec_ship', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping');

		if (isset($this->request->post['shipping_ec_ship_api_key'])) {
			$data['shipping_ec_ship_api_key'] = $this->request->post['shipping_ec_ship_api_key'];
		} else {
			$data['shipping_ec_ship_api_key'] = $this->config->get('shipping_ec_ship_api_key');
		}

		if (isset($this->request->post['shipping_ec_ship_username'])) {
			$data['shipping_ec_ship_username'] = $this->request->post['shipping_ec_ship_username'];
		} else {
			$data['shipping_ec_ship_username'] = $this->config->get('shipping_ec_ship_username');
		}

		if (isset($this->request->post['shipping_ec_ship_api_username'])) {
			$data['shipping_ec_ship_api_username'] = $this->request->post['shipping_ec_ship_api_username'];
		} else {
			$data['shipping_ec_ship_api_username'] = $this->config->get('shipping_ec_ship_api_username');
		}

		if (isset($this->request->post['shipping_ec_ship_test'])) {
			$data['shipping_ec_ship_test'] = $this->request->post['shipping_ec_ship_test'];
		} else {
			$data['shipping_ec_ship_test'] = $this->config->get('shipping_ec_ship_test');
		}

		if (isset($this->request->post['shipping_ec_ship_air_registered_mail'])) {
			$data['shipping_ec_ship_air_registered_mail'] = $this->request->post['shipping_ec_ship_air_registered_mail'];
		} else {
			$data['shipping_ec_ship_air_registered_mail'] = $this->config->get('shipping_ec_ship_air_registered_mail');
		}

		if (isset($this->request->post['shipping_ec_ship_air_parcel'])) {
			$data['shipping_ec_ship_air_parcel'] = $this->request->post['shipping_ec_ship_air_parcel'];
		} else {
			$data['shipping_ec_ship_air_parcel'] = $this->config->get('shipping_ec_ship_air_parcel');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_to_us'])) {
			$data['shipping_ec_ship_e_express_service_to_us'] = $this->request->post['shipping_ec_ship_e_express_service_to_us'];
		} else {
			$data['shipping_ec_ship_e_express_service_to_us'] = $this->config->get('shipping_ec_ship_e_express_service_to_us');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_to_canada'])) {
			$data['shipping_ec_ship_e_express_service_to_canada'] = $this->request->post['shipping_ec_ship_e_express_service_to_canada'];
		} else {
			$data['shipping_ec_ship_e_express_service_to_canada'] = $this->config->get('shipping_ec_ship_e_express_service_to_canada');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_to_united_kingdom'])) {
			$data['shipping_ec_ship_e_express_service_to_united_kingdom'] = $this->request->post['shipping_ec_ship_e_express_service_to_united_kingdom'];
		} else {
			$data['shipping_ec_ship_e_express_service_to_united_kingdom'] = $this->config->get('shipping_ec_ship_e_express_service_to_united_kingdom');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_to_russia'])) {
			$data['shipping_ec_ship_e_express_service_to_russia'] = $this->request->post['shipping_ec_ship_e_express_service_to_russia'];
		} else {
			$data['shipping_ec_ship_e_express_service_to_russia'] = $this->config->get('shipping_ec_ship_e_express_service_to_russia');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_one'])) {
			$data['shipping_ec_ship_e_express_service_one'] = $this->request->post['shipping_ec_ship_e_express_service_one'];
		} else {
			$data['shipping_ec_ship_e_express_service_one'] = $this->config->get('shipping_ec_ship_e_express_service_one');
		}

		if (isset($this->request->post['shipping_ec_ship_e_express_service_two'])) {
			$data['shipping_ec_ship_e_express_service_two'] = $this->request->post['shipping_ec_ship_e_express_service_two'];
		} else {
			$data['shipping_ec_ship_e_express_service_two'] = $this->config->get('shipping_ec_ship_e_express_service_two');
		}

		if (isset($this->request->post['shipping_ec_ship_speed_post'])) {
			$data['shipping_ec_ship_speed_post'] = $this->request->post['shipping_ec_ship_speed_post'];
		} else {
			$data['shipping_ec_ship_speed_post'] = $this->config->get('shipping_ec_ship_speed_post');
		}

		if (isset($this->request->post['shipping_ec_ship_smart_post'])) {
			$data['shipping_ec_ship_smart_post'] = $this->request->post['shipping_ec_ship_smart_post'];
		} else {
			$data['shipping_ec_ship_smart_post'] = $this->config->get('shipping_ec_ship_smart_post');
		}

		if (isset($this->request->post['shipping_ec_ship_local_courier_post'])) {
			$data['shipping_ec_ship_local_courier_post'] = $this->request->post['shipping_ec_ship_local_courier_post'];
		} else {
			$data['shipping_ec_ship_local_courier_post'] = $this->config->get('shipping_ec_ship_local_courier_post');
		}

		if (isset($this->request->post['shipping_ec_ship_local_parcel'])) {
			$data['shipping_ec_ship_local_parcel'] = $this->request->post['shipping_ec_ship_local_parcel'];
		} else {
			$data['shipping_ec_ship_local_parcel'] = $this->config->get('shipping_ec_ship_local_parcel');
		}

		if (isset($this->request->post['shipping_ec_ship_weight_class_id'])) {
			$data['shipping_ec_ship_weight_class_id'] = $this->request->post['shipping_ec_ship_weight_class_id'];
		} else {
			$data['shipping_ec_ship_weight_class_id'] = $this->config->get('shipping_ec_ship_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['shipping_ec_ship_tax_class_id'])) {
			$data['shipping_ec_ship_tax_class_id'] = $this->request->post['shipping_ec_ship_tax_class_id'];
		} else {
			$data['shipping_ec_ship_tax_class_id'] = $this->config->get('shipping_ec_ship_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_ec_ship_geo_zone_id'])) {
			$data['shipping_ec_ship_geo_zone_id'] = $this->request->post['shipping_ec_ship_geo_zone_id'];
		} else {
			$data['shipping_ec_ship_geo_zone_id'] = $this->config->get('shipping_ec_ship_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_ec_ship_status'])) {
			$data['shipping_ec_ship_status'] = $this->request->post['shipping_ec_ship_status'];
		} else {
			$data['shipping_ec_ship_status'] = $this->config->get('shipping_ec_ship_status');
		}

		if (isset($this->request->post['shipping_ec_ship_sort_order'])) {
			$data['shipping_ec_ship_sort_order'] = $this->request->post['shipping_ec_ship_sort_order'];
		} else {
			$data['shipping_ec_ship_sort_order'] = $this->config->get('shipping_ec_ship_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/ec_ship', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/ec_ship')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shipping_ec_ship_api_key']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		if (!$this->request->post['shipping_ec_ship_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['shipping_ec_ship_api_username']) {
			$this->error['api_username'] = $this->language->get('error_api_username');
		}

		return !$this->error;
	}
}
