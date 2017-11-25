<?php
/**
 * Платежная система Wallet One (Единая касса)
 * 
 * @cms       ocStore 3.0
 * @author    OcTeam
 * @support   https://opencartforum.com/profile/3463-shoputils/
 * @version   1.0
 * @copyright  Copyright (c) 2017 OcStore Team (https://ocstore.com , https://opencartforum.com)
 */
class ControllerExtensionPaymentOcstoreW1 extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('extension/payment/ocstore_w1');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->model('setting/setting');

			$this->request->post['payment_ocstore_w1_shop_id'] = trim($this->request->post['payment_ocstore_w1_shop_id']);
			$this->request->post['payment_ocstore_w1_sign'] = trim($this->request->post['payment_ocstore_w1_sign']);

			$this->model_setting_setting->editSetting('payment_ocstore_w1', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL'));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['shop_id'])) {
			$data['error_shop_id'] = $this->error['shop_id'];
		} else {
			$data['error_shop_id'] = '';
		}
		
		if (isset($this->error['sign'])) {
			$data['error_sign'] = $this->error['sign'];
		} else {
			$data['error_sign'] = '';
		}
		
   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL')
   	);

   	$data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/ocstore_w1', 'user_token=' . $this->session->data['user_token'], 'SSL')
   	);
				
		$data['action'] = $this->url->link('extension/payment/ocstore_w1', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('marketplace/extension', 'type=payment&user_token=' . $this->session->data['user_token'], 'SSL');

		if (isset($this->request->post['payment_ocstore_w1_shop_id'])) {
			$data['payment_ocstore_w1_shop_id'] = $this->request->post['payment_ocstore_w1_shop_id'];
		} else {
			$data['payment_ocstore_w1_shop_id'] = $this->config->get('payment_ocstore_w1_shop_id');
		}
		
		if (isset($this->request->post['payment_ocstore_w1_sign'])) {
			$data['payment_ocstore_w1_sign'] = $this->request->post['payment_ocstore_w1_sign'];
		} else {
			$data['payment_ocstore_w1_sign'] = $this->config->get('payment_ocstore_w1_sign');
		}

		
		if (isset($this->request->post['payment_ocstore_w1_currency'])) {
			$data['payment_ocstore_w1_currency'] = $this->request->post['payment_ocstore_w1_currency'];
		} else {
			$data['payment_ocstore_w1_currency'] = $this->config->get('payment_ocstore_w1_currency');
		}
		
		$this->load->model('localisation/currency');
		$data['currencies'] = $this->model_localisation_currency->getCurrencies();
		
		$server = isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1')) ? HTTPS_CATALOG : HTTP_CATALOG;

		$data['payment_ocstore_w1_result_url'] = $server . 'index.php?route=extension/payment/ocstore_w1/callback';
		
		
		if (isset($this->request->post['payment_ocstore_w1_order_confirm_status_id'])) {
			$data['payment_ocstore_w1_order_confirm_status_id'] = $this->request->post['payment_ocstore_w1_order_confirm_status_id'];
		} else {
			$data['payment_ocstore_w1_order_confirm_status_id'] = $this->config->get('payment_ocstore_w1_order_confirm_status_id'); 
		}

		if (isset($this->request->post['payment_ocstore_w1_order_status_id'])) {
			$data['payment_ocstore_w1_order_status_id'] = $this->request->post['payment_ocstore_w1_order_status_id'];
		} else {
			$data['payment_ocstore_w1_order_status_id'] = $this->config->get('payment_ocstore_w1_order_status_id'); 
		}

		if (isset($this->request->post['payment_ocstore_w1_order_fail_status_id'])) {
			$data['payment_ocstore_w1_order_fail_status_id'] = $this->request->post['payment_ocstore_w1_order_fail_status_id'];
		} else {
			$data['payment_ocstore_w1_order_fail_status_id'] = $this->config->get('payment_ocstore_w1_order_fail_status_id'); 
		}
		
		$this->load->model('localisation/order_status');
		
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['payment_ocstore_w1_geo_zone_id'])) {
			$data['payment_ocstore_w1_geo_zone_id'] = $this->request->post['payment_ocstore_w1_geo_zone_id'];
		} else {
			$data['payment_ocstore_w1_geo_zone_id'] = $this->config->get('payment_ocstore_w1_geo_zone_id'); 
		}
		
		$this->load->model('localisation/geo_zone');
		
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['payment_ocstore_w1_status'])) {
			$data['payment_ocstore_w1_status'] = $this->request->post['payment_ocstore_w1_status'];
		} else {
			$data['payment_ocstore_w1_status'] = $this->config->get('payment_ocstore_w1_status');
		}
		
		if (isset($this->request->post['payment_ocstore_w1_sort_order'])) {
			$data['payment_ocstore_w1_sort_order'] = $this->request->post['payment_ocstore_w1_sort_order'];
		} else {
			$data['payment_ocstore_w1_sort_order'] = $this->config->get('payment_ocstore_w1_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/payment/ocstore_w1', $data));
	}

	protected function validate() {
		if (!$this->validatePermission()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!trim($this->request->post['payment_ocstore_w1_shop_id'])) {
			$this->error['warning'] = $this->error['shop_id'] = $this->language->get('error_shop_id');
		}
		
		if (!trim($this->request->post['payment_ocstore_w1_sign'])) {
			$this->error['warning'] = $this->error['sign'] = $this->language->get('error_sign');
		}
		
		return !$this->error;
	}

  protected function validatePermission() {
    return $this->user->hasPermission('modify', 'extension/payment/ocstore_w1');
  }
}
?>