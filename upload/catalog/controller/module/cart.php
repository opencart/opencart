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
				'href'     => $this->url->http('product/product&product_id=' . $result['product_id']),
      		);
    	}

    	$this->data['subtotal'] = $this->currency->format($this->cart->getTotal());
		
		$this->data['ajax'] = $this->config->get('cart_ajax');

		$this->id       = 'cart';
		$this->template = $this->config->get('config_template') . 'module/cart.tpl';
		
		$this->render();
	}
	
	public function callback() {
		$this->load->language('module/cart');

		$ouput = '<table cellpadding="2" cellspacing="0" style="width: 100%;">';
		
    	foreach ($this->cart->getProducts() as $product) {
      		$ouput .= '<tr>';
        	$ouput .= '<td valign="top" align="right">' . $product['quantity'] . '&nbsp;x&nbsp;</td>';
        	$ouput .= '<td align="left" valign="top"><a href="' . $this->url->http('product/product&product_id=' . $product['product_id']) . '">' . $product['name'] . '</a>';
          	$ouput .= '<div>';
            
			foreach ($product['option'] as $option) {
            	$ouput .= ' - <small style="color: #999;">' . $option['name'] . ' ' . $option['value'] . '</small><br />';
            }
			
			$ouput .= '</div></td>';
			$ouput .= '</tr>';
      	}
		
		$ouput .= '</table>';
    	$ouput .= '<br />';
    	$ouput .= '<div style="text-align: right;">' . $this->language->get('text_subtotal') . '&nbsp;' .  $this->currency->format($this->cart->getTotal()) . '</div>';
		
		$this->response->setOutput($ouput);
	} 	
}
?>