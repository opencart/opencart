<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerSearchSearch extends Controller {
	public function index() {
		if(empty($this->session->data['token'])) {
			return;
		}
	
		$this->load->language('search/search');

        $data = array();
		
		$data['text_search_options'] = $this->language->get('text_search_options');
		$data['text_catalog'] = $this->language->get('text_catalog');
		$data['text_customers'] = $this->language->get('text_customers');
		$data['text_orders'] = $this->language->get('text_orders');
		$data['text_catalog_placeholder'] = $this->language->get('text_catalog_placeholder');
		$data['text_customers_placeholder'] = $this->language->get('text_customers_placeholder');
		$data['text_orders_placeholder'] = $this->language->get('text_orders_placeholder');
		$data['text_search_placeholder'] = $this->language->get('text_search_placeholder');
		
		

		$data['search_link'] = $this->url->link('search/search/search', 'token=' . $this->session->data['token'], true);
		
		return $this->load->view('search/search.tpl', $data);
	}

    public function search(){
        $this->load->language('search/search');
        
		$this->load->language('search/search');
        $data['text_products'] = $this->language->get('text_products');
		$data['text_categories'] = $this->language->get('text_categories');
		$data['text_manufacturers'] = $this->language->get('text_manufacturers');
		$data['text_orders'] = $this->language->get('text_orders');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_customers'] = $this->language->get('text_customers');
		$data['text_no_result'] = $this->language->get('text_no_result');

        $data['token'] = $this->session->data['token'];

        if(!empty($this->request->get['query'])) {
            $_data['query'] = $this->request->get['query'];
        }
        else{
            $json['error'] = $this->language->get('text_empty_query');
        }

        if(!empty($this->request->get['search-option'])) {
            $search_option = $this->request->get['search-option'];
        }
        else{
            $search_option = 'catalog';
        }

        if(!empty($json['error'])) {
            $this->response->setOutput(json_encode($json));
            return;
        }

        $this->load->model('tool/image');
        $data['no_image'] = $this->model_tool_image->resize('no_image.png', 30, 30);

        $this->load->model('search/search');

        switch($search_option) {
            case 'catalog':
                // Get products
                $data['products'] = $this->model_search_search->getProducts($_data);

                foreach($data['products'] as $key => $product){
                    if(!empty($product['image'])) {
                        $data['products'][$key]['image'] = $this->model_tool_image->resize($product['image'], 30, 30);
                    }
                    else{
                        $data['products'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['products'][$key]['url'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'], true);
                }

                // Get categories
                $data['categories'] = $this->model_search_search->getCategories($_data);

                foreach($data['categories'] as $key => $category){
                    if(!empty($category['image'])) {
                        $data['categories'][$key]['image'] = $this->model_tool_image->resize($category['image'], 30, 30);
                    }
                    else{
                        $data['categories'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['categories'][$key]['url'] = $this->url->link('catalog/category/edit', 'token=' . $this->session->data['token'] . '&category_id=' . $category['category_id'], true);
                }

                // Get manufacturers
                $data['manufacturers'] = $this->model_search_search->getManufacturers($_data);

                foreach($data['manufacturers'] as $key => $manufacturer){
                    if(!empty($category['image'])) {
                        $data['manufacturers'][$key]['image'] = $this->model_tool_image->resize($manufacturer['image'], 30, 30);
                    }
                    else{
                        $data['manufacturers'][$key]['image'] = $this->model_tool_image->resize('no_image.png', 30, 30);
                    }

                    $data['manufacturers'][$key]['url'] = $this->url->link('catalog/manufacturer/edit', 'token=' . $this->session->data['token'] . '&manufacturer_id=' . $manufacturer['manufacturer_id'], true);
                }

                $json['result'] = $this->load->view('search/catalog_result.tpl', $data);

                break;
            case 'customers':
                $data['customers'] = $this->model_search_search->getCustomers($_data);

                foreach($data['customers'] as $key => $customer){
                    $data['customers'][$key]['url'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer['customer_id'], true);
                }

                $json['result'] = $this->load->view('search/customers_result.tpl', $data);
                break;
            case 'orders':
                $data['orders'] = $this->model_search_search->getOrders($_data);

                foreach($data['orders'] as $key => $order){
                    $data['orders'][$key]['url'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $order['order_id'], true);
                }

                $json['result'] = $this->load->view('search/orders_result.tpl', $data);
                break;
            default:
                break;
        }

        $this->response->setOutput(json_encode($json));
    }
}
