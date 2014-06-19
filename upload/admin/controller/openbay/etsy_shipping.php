<?php
class ControllerOpenbayEtsyShipping extends Controller {
	private $error;

	public function getAll() {
		$shipping_templates = $this->openbay->etsy->call('product/shipping/getAllTemplates', 'GET');

		$data = $this->load->language('openbay/etsy_shipping');

		$data['shipping_templates'] = $shipping_templates['data']['results'];

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_openbay'),
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_etsy'),
			'href' => $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('openbay/etsy_shipping/getAll', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['error_warning'] = '';
		$data['success'] = '';

		$data['insert'] = $this->url->link('openbay/etsy_shipping/create', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/etsy_shipping_list.tpl', $data));
	}

	public function delete() {

	}
}