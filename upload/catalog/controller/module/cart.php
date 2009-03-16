<?php 
class ControllerModuleCart extends Controller { 
	protected function index() {
		$this->load->language('module/cart');
		
    	$this->data['heading_title'] = $this->language->get('heading_title');
    	
		$this->data['text_subtotal'] = $this->language->get('text_subtotal');
		$this->data['text_empty'] = $this->language->get('text_empty');
    	
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
        		'name'     => $result['name'],
				'option'   => $option_data,
        		'quantity' => $result['quantity'],
				'stock'    => $result['stock'],
				'price'    => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'))),
				'image'    => HelperImage::resize($result['image'], 38, 38),
				'href'     => $this->url->http('product/product&product_id=' . $result['product_id']),
      		);
    	}

    	$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());

		$this->id       = 'cart';
		$this->template = $this->config->get('config_template') . 'module/cart.tpl';
		
		$this->render();
	}
}
?>