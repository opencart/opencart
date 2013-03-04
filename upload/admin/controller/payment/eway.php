<?php
class ControllerPaymentEway extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/eway');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('eway', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');

		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_transaction'] = $this->language->get('entry_transaction');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_username'] = $this->language->get('entry_username');
		$this->data['entry_password'] = $this->language->get('entry_password');

        //Help array here
        $helplist = array('help_testmode', 'help_username', 'help_password', 'help_ewaystatus', 'help_setorderstatus', 'help_sort_order' );
        $this->getlanguagestuff($helplist);

		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		} else {
			$this->data['error_username'] = '';
		}
		if (isset($this->error['password'])) {
			$this->data['error_password'] = $this->error['password'];
		} else {
			$this->data['error_password'] = '';
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
			'href'      => $this->url->link('payment/eway', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

        $this->data['action'] = $this->url->link('payment/eway', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['eway_payment_gateway'])) {
			$this->data['eway_payment_gateway'] = $this->request->post['eway_payment_gateway'];
		} else {
			$this->data['eway_payment_gateway'] = $this->config->get('eway_payment_gateway');
		}

		if (isset($this->request->post['eway_test'])) {
			$this->data['eway_test'] = $this->request->post['eway_test'];
		} else {
			$this->data['eway_test'] = $this->config->get('eway_test');
		}

		if (isset($this->request->post['eway_transaction'])) {
			$this->data['eway_transaction'] = $this->request->post['eway_transaction'];
		} else {
			$this->data['eway_transaction'] = $this->config->get('eway_transaction');
		}
        
		if (isset($this->request->post['eway_standard_geo_zone_id'])) {
			$this->data['eway_standard_geo_zone_id'] = $this->request->post['eway_standard_geo_zone_id'];
		} else {
			$this->data['eway_standard_geo_zone_id'] = $this->config->get('eway_standard_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['eway_order_status_id'])) {
			$this->data['eway_order_status_id'] = $this->request->post['eway_order_status_id'];
		} else {
			$this->data['eway_order_status_id'] = $this->config->get('eway_order_status_id');
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['eway_username'])) {
			$this->data['eway_username'] = $this->request->post['eway_username'];
		} else {
			$this->data['eway_username'] = $this->config->get('eway_username');
		}
		if (isset($this->request->post['eway_password'])) {
			$this->data['eway_password'] = $this->request->post['eway_password'];
		} else {
			$this->data['eway_password'] = $this->config->get('eway_password');
		}

		if (isset($this->request->post['eway_status'])) {
			$this->data['eway_status'] = $this->request->post['eway_status'];
		} else {
			$this->data['eway_status'] = $this->config->get('eway_status');
		}

		if (isset($this->request->post['eway_sort_order'])) {
			$this->data['eway_sort_order'] = $this->request->post['eway_sort_order'];
		} else {
			$this->data['eway_sort_order'] = $this->config->get('eway_sort_order');
		}

		$this->template = 'payment/eway.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

    //JD function to quickly loop through arrays from language files instead of
    //writing out all those lines over and over
    private function getlanguagestuff($helplist) {
        foreach( $helplist as $key ) {
            $this->data[$key] = $this->language->get($key);
        }
    }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/eway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['eway_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}
		if (!$this->request->post['eway_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>