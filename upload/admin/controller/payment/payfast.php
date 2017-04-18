<?php
/**
 * admin/controller/payment/payfast.php
 *
 * Copyright (c) 2009-2012 PayFast (Pty) Ltd
 * 
 * LICENSE:
 * 
 * This payment module is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version.
 * 
 * This payment module is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
 * License for more details.
 * 
 * @author     Ron Darby
 * @copyright  2009-2012 PayFast (Pty) Ltd
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    1.1.1
 */

class ControllerPaymentPayFast extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/payfast');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payfast', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_sale'] = $this->language->get('text_sale');

		$this->data['entry_sandbox'] = $this->language->get('entry_sandbox');
		$this->data['entry_debug'] = $this->language->get('entry_debug');
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_cancelled_status'] = $this->language->get('entry_cancelled_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
        $this->data['entry_merchant_key'] = $this->language->get('entry_merchant_key');
        $this->data['text_debug'] = $this->language->get('text_debug');
        
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
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
			'href'      => $this->url->link('payment/payfast', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = $this->url->link('payment/payfast', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['payfast_merchant_id'])) {
			$this->data['payfast_merchant_id'] = $this->request->post['payfast_merchant_id'];
		} else {
			$this->data['payfast_merchant_id'] = $this->config->get('payfast_merchant_id');
		}
        if (isset($this->request->post['payfast_merchant_key'])) {
			$this->data['payfast_merchant_key'] = $this->request->post['payfast_merchant_key'];
		} else {
			$this->data['payfast_merchant_key'] = $this->config->get('payfast_merchant_key');
		}

		if (isset($this->request->post['payfast_sandbox'])) {
			$this->data['payfast_sandbox'] = $this->request->post['payfast_sandbox'];
		} else {
			$this->data['payfast_sandbox'] = $this->config->get('payfast_sandbox');
		}

		if (isset($this->request->post['payfast_transaction'])) {
			$this->data['payfast_transaction'] = $this->request->post['payfast_transaction'];
		} else {
			$this->data['payfast_transaction'] = $this->config->get('payfast_transaction');
		}

		if (isset($this->request->post['payfast_debug'])) {
			$this->data['payfast_debug'] = $this->request->post['payfast_debug'];
		} else {
			$this->data['payfast_debug'] = $this->config->get('payfast_debug');
		}
		
		if (isset($this->request->post['payfast_total'])) {
			$this->data['payfast_total'] = $this->request->post['payfast_total'];
		} else {
			$this->data['payfast_total'] = $this->config->get('payfast_total'); 
		} 
		
		if (isset($this->request->post['payfast_completed_status_id'])) {
			$this->data['payfast_completed_status_id'] = $this->request->post['payfast_completed_status_id'];
		} else {
			$this->data['payfast_completed_status_id'] = $this->config->get('payfast_completed_status_id');
		}	
						
		if (isset($this->request->post['payfast_failed_status_id'])) {
			$this->data['payfast_failed_status_id'] = $this->request->post['payfast_failed_status_id'];
		} else {
			$this->data['payfast_failed_status_id'] = $this->config->get('payfast_failed_status_id');
		}	
								
		if (isset($this->request->post['payfast_cancelled_status_id'])) {
			$this->data['payfast_cancelled_status_id'] = $this->request->post['payfast_cancelled_status_id'];
		} else {
			$this->data['payfast_cancelled_status_id'] = $this->config->get('payfast_cancelled_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payfast_geo_zone_id'])) {
			$this->data['payfast_geo_zone_id'] = $this->request->post['payfast_geo_zone_id'];
		} else {
			$this->data['payfast_geo_zone_id'] = $this->config->get('payfast_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payfast_status'])) {
			$this->data['payfast_status'] = $this->request->post['payfast_status'];
		} else {
			$this->data['payfast_status'] = $this->config->get('payfast_status');
		}
		
		if (isset($this->request->post['payfast_sort_order'])) {
			$this->data['payfast_sort_order'] = $this->request->post['payfast_sort_order'];
		} else {
			$this->data['payfast_sort_order'] = $this->config->get('payfast_sort_order');
		}
        
        
		$this->template = 'payment/payfast.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/payfast')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>