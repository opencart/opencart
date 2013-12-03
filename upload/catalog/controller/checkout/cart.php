<?php 
class ControllerCheckoutCart extends Controller {
	private $error = array();
	
	public function index() {
		$this->load->language('checkout/cart');

		// Update
		if (!empty($this->request->post['quantity'])) {
			foreach ($this->request->post['quantity'] as $key => $value) {
				$this->cart->update($key, $value);
			}
			
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']); 
			unset($this->session->data['reward']);
			
			$this->response->redirect($this->url->link('checkout/cart'));  			
		}
       	
		// Remove
		if (isset($this->request->get['remove'])) {
			$this->cart->remove($this->request->get['remove']);
			
			unset($this->session->data['vouchers'][$this->request->get['remove']]);
			
			$this->session->data['success'] = $this->language->get('text_remove');
		
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']); 
			unset($this->session->data['reward']);  
								
			$this->response->redirect($this->url->link('checkout/cart'));
		}
		
		$this->document->setTitle($this->language->get('heading_title'));
			
      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'href' => $this->url->link('common/home'),
        	'text' => $this->language->get('text_home')
      	); 

      	$data['breadcrumbs'][] = array(
        	'href' => $this->url->link('checkout/cart'),
        	'text' => $this->language->get('heading_title')
      	);
			
    	if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {
      		$data['heading_title'] = $this->language->get('heading_title');
			
			$data['text_next'] = $this->language->get('text_next');
			$data['text_next_choice'] = $this->language->get('text_next_choice');
			
			$data['column_image'] = $this->language->get('column_image');
      		$data['column_name'] = $this->language->get('column_name');
      		$data['column_model'] = $this->language->get('column_model');
      		$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
      		$data['column_total'] = $this->language->get('column_total');
			
			$data['button_update'] = $this->language->get('button_update');
			$data['button_remove'] = $this->language->get('button_remove');
			
      		$data['button_shopping'] = $this->language->get('button_shopping');
      		$data['button_checkout'] = $this->language->get('button_checkout');
			
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} elseif (!$this->cart->hasStock() && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
      			$data['error_warning'] = $this->language->get('error_stock');		
			} else {
				$data['error_warning'] = '';
			}
			
			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$data['attention'] = '';
			}
						
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
			
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
			
			$data['action'] = $this->url->link('checkout/cart');   
						
			if ($this->config->get('config_cart_weight')) {
				$data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$data['weight'] = '';
			}
						 
			$this->load->model('tool/image');
			
      		$data['products'] = array();
			
			$products = $this->cart->getProducts();

      		foreach ($products as $product) {
				$product_total = 0;
					
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}			
				
				if ($product['minimum'] > $product_total) {
					$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}				
					
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

        		foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];	
					} else {
						$filename = $this->encryption->decrypt($option['value']);
						
						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}
					
					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
        		}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$total = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity']);
				} else {
					$total = false;
				}
				
        		$data['products'][] = array(
          			'key'      => $product['key'],
          			'thumb'    => $image,
					'name'     => $product['name'],
          			'model'    => $product['model'],
          			'option'   => $option_data,
          			'quantity' => $product['quantity'],
          			'stock'    => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'reward'   => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
					'price'    => $price,
					'total'    => $total,
					'href'     => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'remove'   => $this->url->link('checkout/cart', 'remove=' . $product['key'])
				);
      		}
			
			// Gift Voucher
			$data['vouchers'] = array();
		
			if (!isset($this->session->data['vouchers'])) {
				$vouchers = $this->session->data['vouchers'] = array();
			} else {
				$vouchers = array();
			}

			foreach ($vouchers as $key => $voucher) {
				$data['vouchers'][] = array(
					'key'         => $key,
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount']),
					'remove'      => $this->url->link('checkout/cart', 'remove=' . $key)   
				);
			}
						
			// Totals
			$this->load->model('setting/extension');
			
			$total_data = array();					
			$total = 0;
			$taxes = $this->cart->getTaxes();
			
			// Display prices
			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				$sort_order = array(); 
				
				$results = $this->model_setting_extension->getExtensions('total');
				
				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}
				
				array_multisort($sort_order, SORT_ASC, $results);
				
				foreach ($results as $result) {
					if ($this->config->get($result['code'] . '_status')) {
						$this->load->model('total/' . $result['code']);
			
						$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
					}
				}
				
				$sort_order = array(); 
			  
				foreach ($total_data as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}
	
				array_multisort($sort_order, SORT_ASC, $total_data);				
			}
			
			
			$data['totals'] = array();
	
			foreach ($total_data as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value']),
				);				
			}
						
			$data['continue'] = $this->url->link('common/home');
						
			$data['checkout'] = $this->url->link('checkout/checkout');

			$data['coupon'] = $this->load->controller('module/coupon');
			$data['voucher'] = $this->load->controller('module/voucher');
			$data['reward'] = $this->load->controller('module/reward');
			$data['shipping'] = $this->load->controller('module/shipping');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
								
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/checkout/cart.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/checkout/cart.tpl', $data));
			}					
    	} else {
      		$data['heading_title'] = $this->language->get('heading_title');

      		$data['text_error'] = $this->language->get('text_empty');

      		$data['button_continue'] = $this->language->get('button_continue');
			
      		$data['continue'] = $this->url->link('common/home');

			unset($this->session->data['success']);
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
					
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}			
    	}
  	}
								
	public function add() {
		$this->load->language('checkout/cart');
		
		$json = array();
		
		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
		
		$this->load->model('catalog/product');
						
		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}
														
			if (isset($this->request->post['option'])) {
				$option = array_filter($this->request->post['option']);
			} else {
				$option = array();	
			}
			
			$product_options = $this->model_catalog_product->getProductOptions($this->request->post['product_id']);
			
			foreach ($product_options as $product_option) {
				if ($product_option['required'] && empty($option[$product_option['product_option_id']])) {
					$json['error']['option'][$product_option['product_option_id']] = sprintf($this->language->get('error_required'), $product_option['name']);
				}
			}
			
			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, $option);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']), $product_info['name'], $this->url->link('checkout/cart'));
				
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);
				
				// Totals
				$this->load->model('setting/extension');
				
				$total_data = array();					
				$total = 0;
				$taxes = $this->cart->getTaxes();
				
				// Display prices
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$sort_order = array(); 
					
					$results = $this->model_setting_extension->getExtensions('total');
					
					foreach ($results as $key => $value) {
						$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
					}
					
					array_multisort($sort_order, SORT_ASC, $results);
					
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);
				
							$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
						}
					}
					
					$sort_order = array(); 
				  
					foreach ($total_data as $key => $value) {
						$sort_order[$key] = $value['sort_order'];
					}
		
					array_multisort($sort_order, SORT_ASC, $total_data);								
				}
				
				$json['total'] = sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));
			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}
		
		$this->response->setOutput(json_encode($json));		
	}
}