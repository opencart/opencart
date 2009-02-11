<?php 
class ControllerCheckoutCart extends Controller {
	public function index() {
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
      		}

      		if (isset($this->request->post['remove'])) {
	    		foreach (array_keys($this->request->post['remove']) as $key) {
          			$this->cart->remove($key);
				}
      		}
      		
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['payment_methods']);
			unset($this->session->data['payment_method']);
			
	  		$this->redirect($this->url->https('checkout/cart'));
    	}

		$this->load->language('checkout/cart');

    	$this->document->title = $this->language->get('heading_title');

      	$this->document->breadcrumbs = array();

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('common/home'),
        	'text'      => $this->language->get('text_home'),
        	'separator' => FALSE
      	); 

      	$this->document->breadcrumbs[] = array(
        	'href'      => $this->url->http('checkout/cart'),
        	'text'      => $this->language->get('text_basket'),
        	'separator' => $this->language->get('text_separator')
      	);
			
    	if ($this->cart->hasProducts()) {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_subtotal'] = $this->language->get('text_subtotal');

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
			
			if (!$this->cart->hasStock() && $this->config->get('config_stock_check')) {
      			$this->data['error'] = $this->language->get('error_stock');
			} else {
				$this->data['error'] = FALSE;
			}
      		
			$this->data['action'] = $this->url->http('checkout/cart');
			
			$this->load->helper('image');
			
      		$this->data['products'] = array();

      		foreach ($this->cart->getProducts() as $result) {
        		$option_data = array();

        		foreach ($result['option'] as $option) {
          			$option_data[] = array(
            			'name'  => $option['name'],
            			'value' => $option['value']
          			);
        		}

        		$this->data['products'][] = array(
          			'key'      => $result['key'],
          			'name'     => $result['name'],
          			'model'    => $result['model'],
          			'thumb'    => HelperImage::resize($result['image'], 75, 75),
          			'option'   => $option_data,
          			'quantity' => $result['quantity'],
          			'stock'    => $result['stock'],
					'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
          			'discount' => ($result['discount'] ? $this->currency->format($this->tax->calculate($result['price'] - $result['discount'], $result['tax_class_id'], $this->config->get('config_tax'))) : NULL),
					'total'    => $this->currency->format($this->tax->calculate($result['total'], $result['tax_class_id'], $this->config->get('config_tax'))),
					'href'     => $this->url->http('product/product&product_id=' . $result['product_id'])
        		);
      		}
			
      		$this->data['subtotal'] = $this->currency->format($this->cart->getSubtotal());

      		$this->data['continue'] = $this->url->http('common/home');

      		$this->data['checkout'] = $this->url->http('checkout/shipping');

			$this->id       = 'content';
			$this->template = 'checkout/cart.tpl';
			$this->layout   = 'module/layout';
			
			$this->render();					
    	} else {
      		$this->data['heading_title'] = $this->language->get('heading_title');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->http('common/home');

			$this->id       = 'content';
			$this->template = 'error/not_found.tpl';
			$this->layout   = 'module/layout';
			
			$this->render();			
    	}
  	}
}
?>