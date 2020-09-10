<?php
namespace Opencart\Application\Controller\Extension\Opencart\Shipping;
class Flat extends \Opencart\System\Engine\Controller {
	private $error = [];

	public function index() {
		$this->load->language('extension/opencart/shipping/flat');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_flat', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/shipping/flat', 'user_token=' . $this->session->data['user_token'])
		];

		$data['action'] = $this->url->link('extension/opencart/shipping/flat', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping');

		if (isset($this->request->post['shipping_flat_cost'])) {
			$data['shipping_flat_cost'] = $this->request->post['shipping_flat_cost'];
		} else {
			$data['shipping_flat_cost'] = $this->config->get('shipping_flat_cost');
		}

		if (isset($this->request->post['shipping_flat_tax_class_id'])) {
			$data['shipping_flat_tax_class_id'] = $this->request->post['shipping_flat_tax_class_id'];
		} else {
			$data['shipping_flat_tax_class_id'] = $this->config->get('shipping_flat_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_flat_geo_zone_id'])) {
			$data['shipping_flat_geo_zone_id'] = $this->request->post['shipping_flat_geo_zone_id'];
		} else {
			$data['shipping_flat_geo_zone_id'] = $this->config->get('shipping_flat_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_flat_status'])) {
			$data['shipping_flat_status'] = $this->request->post['shipping_flat_status'];
		} else {
			$data['shipping_flat_status'] = $this->config->get('shipping_flat_status');
		}

		if (isset($this->request->post['shipping_flat_sort_order'])) {
			$data['shipping_flat_sort_order'] = $this->request->post['shipping_flat_sort_order'];
		} else {
			$data['shipping_flat_sort_order'] = $this->config->get('shipping_flat_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/shipping/flat', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/opencart/shipping/flat')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}