<?php
// Nochex via form will work for both simple "Seller" account and "Merchant" account holders
// Nochex via APC maybe only avaiable to "Merchant" account holders only - site docs a bit vague on this point
class ControllerPaymentNochex extends Controller {
	protected function index() {
    	$this->data['button_confirm']      = $this->language->get('button_confirm');
		$this->data['button_back']          = $this->language->get('button_back');

        $this->data['action']               = 'https://secure.nochex.com/'; // This is a constant for both test and live

		$this->load->model('checkout/order');

		$order_info                         = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        // Nochex minimum requirements
        // The merchant ID is usually your Nochex registered email address but can be altered for "Merchant" accounts see below
        $this->data['merchant_id']          = $this->config->get('nochex_email');
       
	   if ($this->config->get('nochex_email') != $this->config->get('nochex_merchant')){ // This MUST be changed on your Nochex account!!!!
            $this->data['merchant_id']      = $this->config->get('nochex_merchant');
        }
        $this->data['amount']               = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
        // End minimum requirements

        $this->data['order_id']             = $this->session->data['order_id'];
        $this->data['description']          = $this->config->get('config_name');

        $this->data['billing_fullname']     = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

        if ($order_info['shipping_address_2']) {
            $this->data['billing_address']  = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_address_2'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
        } else {
            $this->data['billing_address']  = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
        }

        $this->data['billing_postcode']     = $order_info['shipping_postcode'];

        $this->data['delivery_fullname']    = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

        if ($order_info['shipping_address_2']) {
            $this->data['delivery_address'] = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_address_2'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
        } else {
            $this->data['delivery_address'] = $order_info['shipping_address_1'] . "\r\n" . $order_info['shipping_city'] . "\r\n" . $order_info['shipping_zone'] . "\r\n";
        }

        $this->data['delivery_postcode']    = $order_info['shipping_postcode'];
        $this->data['email_address']        = $order_info['email'];
        $this->data['customer_phone_number'] = $order_info['telephone'];

        //$this->data['optional_1']         = ''; // Customer notes could go here
        //$this->data['optional_2']         = ''; // Customer notes could go here
        //$this->data['optional_3']         = ''; // Customer notes could go here
        //$this->data['optional_4']         = ''; // Customer notes could go here
        //$this->data['optional_5']         = ''; // Customer notes could go here

        $this->data['hide_billing_details'] = 'true'; // So customer can't change address settings

        //$this->data['header_html']        = $this->config->get('nochex_logo'); // Send your logo
        // Example:
        //<table border="0" width="640" cellspacing="2" cellpadding="2" align="center">
            //<tr class="header">
                //<td align="center">
                    //<img src="http://www.yourwebsite.com/images/header.gif" alt="Alternative Text"/>
                //</td>
            //</tr>
        //</table>
        //$this->data['footer_html']        = $this->config->get('nochex_footer'); // Send your footer
        // There are more options available if you wish to implement them

        $this->data['success_url']        = HTTPS_SERVER . 'index.php?route=checkout/success';
        $this->data['cancel_url']         = HTTPS_SERVER . 'index.php?route=checkout/payment';
        $this->data['declined_url']       = HTTPS_SERVER . 'index.php?route=checkout/failure';
        //$this->data['callback_url']       = HTTPS_SERVER . 'index.php?route=checkout/payment'; // ???Not sure about this

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}
		
		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/nochex.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/nochex.tpl';
		} else {
			$this->template = 'default/template/payment/nochex.tpl';
		}	
		
		$this->render();
	}

	public function confirm() {
		$this->load->model('checkout/order');

		$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
	}
}
?>