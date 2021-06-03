<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Total;
class LowOrderFee extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/total/low_order_fee');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/total/low_order_fee', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/total/low_order_fee|save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=total');

		$data['total_low_order_fee_total'] = $this->config->get('total_low_order_fee_total');
		$data['total_low_order_fee_fee'] = $this->config->get('total_low_order_fee_fee');
		$data['total_low_order_fee_tax_class_id'] = $this->config->get('total_low_order_fee_tax_class_id');

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$data['total_low_order_fee_status'] = $this->config->get('total_low_order_fee_status');
		$data['total_low_order_fee_sort_order'] = $this->config->get('total_low_order_fee_sort_order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/total/low_order_fee', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/total/low_order_fee');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/total/low_order_fee')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('total_low_order_fee', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}