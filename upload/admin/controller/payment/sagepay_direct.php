<?php 
class ControllerPaymentSagepayDirect extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('payment/sagepay_direct');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay_direct', $this->request->post);				

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_sim'] = $this->language->get('text_sim');
		$this->data['text_test'] = $this->language->get('text_test');
		$this->data['text_live'] = $this->language->get('text_live');
		$this->data['text_payment'] = $this->language->get('text_payment');
		$this->data['text_defered'] = $this->language->get('text_defered');
		$this->data['text_authenticate'] = $this->language->get('text_authenticate');

		$this->data['entry_vendor'] = $this->language->get('entry_vendor');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$this->data['error_vendor'] = $this->error['vendor'];
		} else {
			$this->data['error_vendor'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/sagepay_direct', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/sagepay_direct', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['sagepay_direct_vendor'])) {
			$this->data['sagepay_direct_vendor'] = $this->request->post['sagepay_direct_vendor'];
		} else {
			$this->data['sagepay_direct_vendor'] = $this->config->get('sagepay_direct_vendor');
		}

		if (isset($this->request->post['sagepay_direct_password'])) {
			$this->data['sagepay_direct_password'] = $this->request->post['sagepay_direct_password'];
		} else {
			$this->data['sagepay_direct_password'] = $this->config->get('sagepay_direct_password');
		}


		if (isset($this->request->post['sagepay_direct_test'])) {
			$this->data['sagepay_direct_test'] = $this->request->post['sagepay_direct_test'];
		} else {
			$this->data['sagepay_direct_test'] = $this->config->get('sagepay_direct_test');
		}

		if (isset($this->request->post['sagepay_direct_transaction'])) {
			$this->data['sagepay_direct_transaction'] = $this->request->post['sagepay_direct_transaction'];
		} else {
			$this->data['sagepay_direct_transaction'] = $this->config->get('sagepay_direct_transaction');
		}

		if (isset($this->request->post['sagepay_direct_total'])) {
			$this->data['sagepay_direct_total'] = $this->request->post['sagepay_direct_total'];
		} else {
			$this->data['sagepay_direct_total'] = $this->config->get('sagepay_direct_total'); 
		} 

		if (isset($this->request->post['sagepay_direct_order_status_id'])) {
			$this->data['sagepay_direct_order_status_id'] = $this->request->post['sagepay_direct_order_status_id'];
		} else {
			$this->data['sagepay_direct_order_status_id'] = $this->config->get('sagepay_direct_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_direct_geo_zone_id'])) {
			$this->data['sagepay_direct_geo_zone_id'] = $this->request->post['sagepay_direct_geo_zone_id'];
		} else {
			$this->data['sagepay_direct_geo_zone_id'] = $this->config->get('sagepay_direct_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_direct_status'])) {
			$this->data['sagepay_direct_status'] = $this->request->post['sagepay_direct_status'];
		} else {
			$this->data['sagepay_direct_status'] = $this->config->get('sagepay_direct_status');
		}

		if (isset($this->request->post['sagepay_direct_sort_order'])) {
			$this->data['sagepay_direct_sort_order'] = $this->request->post['sagepay_direct_sort_order'];
		} else {
			$this->data['sagepay_direct_sort_order'] = $this->config->get('sagepay_direct_sort_order');
		}

		$this->template = 'payment/sagepay_direct.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay_direct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['sagepay_direct_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>