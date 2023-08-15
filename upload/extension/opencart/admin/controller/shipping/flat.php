<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Shipping;
/**
 * Class Flat
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Shipping
 */
class Flat extends \Opencart\System\Engine\Controller {
	/**
	 * index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/shipping/flat');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = ['text' => $this->language->get('text_home'), 'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])];

		$data['breadcrumbs'][] = ['text' => $this->language->get('text_extension'), 'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping')];

		$data['breadcrumbs'][] = ['text' => $this->language->get('heading_title'), 'href' => $this->url->link('extension/opencart/shipping/flat', 'user_token=' . $this->session->data['user_token'])];

		$data['save'] = $this->url->link('extension/opencart/shipping/flat.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping');

		$data['shipping_flat_cost'] = $this->config->get('shipping_flat_cost');
		$data['shipping_flat_tax_class_id'] = $this->config->get('shipping_flat_tax_class_id');

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$data['shipping_flat_geo_zone_id'] = $this->config->get('shipping_flat_geo_zone_id');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['shipping_flat_status'] = $this->config->get('shipping_flat_status');
		$data['shipping_flat_sort_order'] = $this->config->get('shipping_flat_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/shipping/flat', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/shipping/flat');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/shipping/flat')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('shipping_flat', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}