<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$this->load->language('common/header');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_order'] = $this->language->get('text_order');
		$data['text_processing_status'] = $this->language->get('text_processing_status');
		$data['text_complete_status'] = $this->language->get('text_complete_status');
		$data['text_return'] = $this->language->get('text_return');
		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_approval'] = $this->language->get('text_approval');
		$data['text_product'] = $this->language->get('text_product');
		$data['text_stock'] = $this->language->get('text_stock');
		$data['text_review'] = $this->language->get('text_review');
		$data['text_affiliate'] = $this->language->get('text_affiliate');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_front'] = $this->language->get('text_front');
		$data['text_help'] = $this->language->get('text_help');
		$data['text_homepage'] = $this->language->get('text_homepage');
		$data['text_documentation'] = $this->language->get('text_documentation');
		$data['text_support'] = $this->language->get('text_support');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_logout'] = $this->language->get('text_logout');
		
		$data['text_search_options'] = $this->language->get('text_search_options');
		$data['text_new'] = $this->language->get('text_new');
		$data['text_new_category'] = $this->language->get('text_new_category');
		$data['text_new_customer'] = $this->language->get('text_new_customer');
		$data['text_new_download'] = $this->language->get('text_new_download');
		$data['text_new_manufacturer'] = $this->language->get('text_new_manufacturer');
		$data['text_new_product'] = $this->language->get('text_new_product');
		
		$data['button_clearallcache'] = $this->language->get('button_clearallcache');
		$data['button_clearcache'] = $this->language->get('button_clearcache');
		$data['button_clearsystemcache'] = $this->language->get('button_clearsystemcache');

		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/dashboard', '', true);
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);
			
			//CacheManager
			$data['clearallcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearallcache&token=' . $this->session->data['token']);
			$data['clearcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearcache&token=' . $this->session->data['token']);
			$data['clearsystemcache'] = (HTTPS_SERVER . 'index.php?route=extension/module/cachemanager/clearsystemcache&token=' . $this->session->data['token']);

			
			// fast add menu
			$data['new_category'] = $this->url->link('catalog/category/add', 'token=' . $this->session->data['token'], true);
			$data['new_customer'] = $this->url->link('user/user/add', 'token=' . $this->session->data['token'], true);
			$data['new_download'] = $this->url->link('catalog/download/add', 'token=' . $this->session->data['token'], true);
			$data['new_manufacturer'] = $this->url->link('catalog/manufacturer/add', 'token=' . $this->session->data['token'], true);
			$data['new_product'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'], true);

			// Orders
			$this->load->model('sale/order');

			// Processing Orders
			$data['processing_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_processing_status'))));
			$data['processing_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_processing_status')), true);

			// Complete Orders
			$data['complete_status_total'] = $this->model_sale_order->getTotalOrders(array('filter_order_status' => implode(',', $this->config->get('config_complete_status'))));
			$data['complete_status'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'] . '&filter_order_status=' . implode(',', $this->config->get('config_complete_status')), true);

			// Returns
			$this->load->model('sale/return');

			$return_total = $this->model_sale_return->getTotalReturns(array('filter_return_status_id' => $this->config->get('config_return_status_id')));

			$data['return_total'] = $return_total;

			$data['return'] = $this->url->link('sale/return', 'token=' . $this->session->data['token'], true);

			// Customers
			$this->load->model('report/customer');

			$data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

			$data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);

			$this->load->model('customer/customer');

			$customer_total = $this->model_customer_customer->getTotalCustomers(array('filter_approved' => false));

			$data['customer_total'] = $customer_total;
			$data['customer_approval'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', true);

			// Products
			$this->load->model('catalog/product');

			$product_total = $this->model_catalog_product->getTotalProducts(array('filter_quantity' => 0));

			$data['product_total'] = $product_total;

			$data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_quantity=0', true);

			// Reviews
			$this->load->model('catalog/review');

			$review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => 0));

			$data['review_total'] = $review_total;

			$data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', true);

			// Affliate
			$this->load->model('marketing/affiliate');

			$affiliate_total = $this->model_marketing_affiliate->getTotalAffiliates(array('filter_approved' => false));

			$data['affiliate_total'] = $affiliate_total;
			$data['affiliate_approval'] = $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'] . '&filter_approved=1', true);

			$data['alerts'] = $customer_total + $product_total + $review_total + $return_total + $affiliate_total;

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}
		
		$data['search'] = $this->load->controller('search/search');

		return $this->load->view('common/header', $data);
	}
}
