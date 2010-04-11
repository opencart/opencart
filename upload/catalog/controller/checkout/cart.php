<?php 
class ControllerCheckoutCart extends Controller {
	private $error = array();
	
	public function index() {
		$this->language->load('checkout/cart');
		
    	if ($this->request->server['REQUEST_METHOD'] == 'POST') {
      		if (isset($this->request->post['quantity'])) {
				if (!is_array($this->request->post['quantity'])) {
					if (isset($this->request->post['option'])) {
						$option = $this->request->post['option'];
					} else {
						$option = array();	
					}
			
      				$this->cart->add($this->request->post['product_id'], $this->request->post['quantity'], $option);
				} else {
					foreach ($this->request->post['quantity'] as $key => $value) {
	      				$this->cart->update($key, $value);
					}
				}
				
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);
      		}

      		if (isset($this->request->post['remove'])) {
	    		foreach (array_keys($this->request->post['remove']) as $key) {
          			$this->cart->remove($key);
				}
      		}
			
			if (isset($this->request->post['redirect'])) {
				$this->session->data['redirect'] = $this->request->post['redirect'];
			}	
			
			if (isset($this->request->post['quantity']) || isset($this->request->post['remove'])) {
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['payment_methods']);
				unset($this->session->data['payment_method']);	
				
				$this->redirect(HTTPS_SERVER . 'index.php?route=checkout/cart');
			}
    	}

    	$this->document->title = $this->language->get('heading_title');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=common/home',
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => HTTP_SERVER . 'index.php?route=checkout/cart',
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	if ($this->cart->hasProducts()) {
      		$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_select'] = $this->language->get('text_select');
      		$this->data['text_sub_total'] = $this->language->get('text_sub_total');
			$this->data['text_discount'] = $this->language->get('text_discount');
			$this->data['text_weight'] = $this->language->get('text_weight');
		
     		$this->data['column_remove'] = $this->language->get('column_remove');
      		$this->data['column_image'] = $this->language->get('column_image');
      		$this->data['column_name'] = $this->language->get('column_name');
      		$this->data['column_model'] = $this->language->get('column_model');
      		$this->data['column_quantity'] = $this->language->get('column_quantity');
			$this->data['column_price'] = $this->language->get('column_price');
      		$this->data['column_total'] = $this->language->get('column_total');

      		$this->data['button_update'] = $this->language->get('button_update');
      		$this->data['button_shopping'] = $this->language->get('button_shopping');
      		$this->data['button_checkout'] = $this->language->get('button_checkout');
			
			if (isset($this->error['warning'])) {
				$this->data['error_warning'] = $this->error['warning'];			
			} elseif (!$this->cart->hasStock() && $this->config->get('config_stock_check')) {
      			$this->data['error_warning'] = $this->language->get('error_stock');
			} else {
				$this->data['error_warning'] = '';
			}
		
			$this->data['action'] = HTTP_SERVER . 'index.php?route=checkout/cart';
			
			$this->load->model('tool/seo_url'); 
			$this->load->model('tool/image');
			
      		$this->data['products'] = array();

      		foreach ($this->cart->getProducts() as $result) {
        		$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value']
          			);
        		}

				if ($result['image']) {
					$image = $result['image'];
				} else {
					$image = 'no_image.jpg';
				}

        		$this->data['products'][] = array(
          			'key'      => $result['key'],
          			'name'     => $result['name'],
          			'model'    => $result['model'],
          			'thumb'    => $this->model_tool_image->resize($image, $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')),
          			'option'   => $option_data,
          			'quantity' => $result['quantity'],
          			'stock'    => $result['stock'],
					'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'total'    => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'     => $this->model_tool_seo_url->rewrite(HTTP_SERVER . 'index.php?route=product/product&product_id=' . $result['product_id'])
        		);
      		}
			
			if ($this->config->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class'));
			} else {
				$this->data['weight'] = FALSE;
			}
			
      		$this->data['sub_total'] = $this->currency->format($this->cart->getTotal());
			
			if (isset($this->session->data['redirect'])) {
      			$this->data['continue'] = $this->session->data['redirect'];
				
				unset($this->session->data['redirect']);
			} else {
				$this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';
			}
			
			$this->data['checkout'] = HTTPS_SERVER . 'index.php?route=checkout/shipping';
			
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);		
			
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));					
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = HTTP_SERVER . 'index.php?route=common/home';

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/header',
				'common/footer',
				'common/column_left',
				'common/column_right'
			);
		
			$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));			
    	}
  	}
}
?>