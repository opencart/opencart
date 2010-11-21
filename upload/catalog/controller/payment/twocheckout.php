    <?php
    class ControllerPaymentTwoCheckout extends Controller {
       protected function index() {
           $this->data['button_confirm'] = $this->language->get('button_confirm');
          $this->data['button_back'] = $this->language->get('button_back');

          $this->load->model('checkout/order');
          
          $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
          
          /* $this->data['action'] = 'https://www.2checkout.com/2co/buyer/purchase'; */      
          $this->data['action'] = 'https://www.2checkout.com/checkout/spurchase';  // New single page checkout

          $this->data['sid'] = $this->config->get('twocheckout_account');
          $this->data['total'] = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);
          $this->data['cart_order_id'] = $this->session->data['order_id'];
          $this->data['card_holder_name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
          $this->data['street_address'] = $order_info['payment_address_1'];
          $this->data['city'] = $order_info['payment_city'];
          if ($order_info['payment_country'] == "United States" || $order_info['payment_country'] == "Canada") {
             $this->data['state'] = $order_info['payment_zone'];
          } else {
             $this->data['state'] = "XX";
          }
          $this->data['zip'] = $order_info['payment_postcode'];
          $this->data['country'] = $order_info['payment_country'];
          $this->data['email'] = $order_info['email'];
          $this->data['phone'] = $order_info['telephone'];
          
          if ($this->cart->hasShipping()) {
             $this->data['ship_street_address'] = $order_info['shipping_address_1'];
             $this->data['ship_city'] = $order_info['shipping_city'];
             $this->data['ship_state'] = $order_info['shipping_zone'];
             $this->data['ship_zip'] = $order_info['shipping_postcode'];
             $this->data['ship_country'] = $order_info['shipping_country'];
          } else {
             $this->data['ship_street_address'] = $order_info['payment_address_1'];
             $this->data['ship_city'] = $order_info['payment_city'];
             $this->data['ship_state'] = $order_info['payment_zone']; // was: $this->data['ship_state'] = $order_info['payment_zone'];
             $this->data['ship_zip'] = $order_info['payment_postcode'];
             $this->data['ship_country'] = $order_info['payment_country'];         
          }
          
          $this->data['products'] = array();
          
          $products = $this->cart->getProducts();

          foreach ($products as $product) {
             $this->data['products'][] = array(
                'product_id'  => $product['product_id'],
                'name'        => $product['name'],
                'description' => $product['name'],
                'quantity'    => $product['quantity'],
                'price'        => $this->currency->format($product['price'], $order_info['currency'], $order_info['value'], FALSE)
             );
          }

          if ($this->config->get('twocheckout_test')) {
             $this->data['demo'] = 'Y';
          }   
          
          $this->data['lang'] = $this->session->data['language'];

          if ($this->request->get['route'] != 'checkout/guest_step_3') {
             $this->data['return_url'] = HTTPS_SERVER . 'index.php?route=checkout/confirm';
          } else {
             $this->data['return_url'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_3';
          }
          
          if ($this->request->get['route'] != 'checkout/guest_step_3') {
             $this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
          } else {
             $this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
          }

          $this->data['payment_callback'] = HTTPS_SERVER . 'index.php?route=payment/twocheckout/callback';
          
          $this->id = 'payment';

          if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/twocheckout.tpl')) {
             $this->template = $this->config->get('config_template') . '/template/payment/twocheckout.tpl';
          } else {
             $this->template = 'default/template/payment/twocheckout.tpl';
          }   
          
          $this->render();
       }
       
       public function callback() {
          $this->load->model('checkout/order');

          $order_info = $this->model_checkout_order->getOrder($this->request->post['cart_order_id']);
          $our_total = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], FALSE);

          $hash_order_number = $this->request->post['order_number'];
          if ($this->config->get('twocheckout_test')) {
             // Checl "MD5 Hash in Demo Mode" section here:
             // https://www.2checkout.com/documentation/UsersGuide2/chapter6/md5-hash.html
             $hash_order_number = "1";
          }

          $hash = md5($this->config->get('twocheckout_secret') . $this->config->get('twocheckout_account') . $hash_order_number . $this->request->post['total']);
          $hash = strtoupper($hash);
          if ($hash == $this->request->post['key']) {

             if ($our_total != $this->request->post['total']) {
                // Ugh. Some one've faked the sum. What should we do? Probably drop a mail to the shop owner?
                // As a first step we block the order.
                $this->model_checkout_order->confirm($this->request->post['cart_order_id'], 8 /*Denied*/);
             } else {
                $this->model_checkout_order->confirm($this->request->post['cart_order_id'], $this->config->get('twocheckout_order_status_id'));
             }

             // We can't use $this->redirect() here, because of 2CO behavior. It fetches this page
             // on behalf of the user and thus user (and his browser) see this as located at 2checkout.com
             // domain. So user's cookies are not here and he will see empty basket and probably other
             // weird things.
    //         $this->redirect(HTTP_SERVER . 'index.php?route=checkout/success');
             echo "<html><head>\n";
             echo "  <meta http-equiv=\"Refresh\" content=\"0; url=".HTTP_SERVER.'index.php?route=checkout/success'."\">\n";
             echo "</head><body>\n";
             echo "  <p>Please follow <a href=\"".HTTP_SERVER.'index.php?route=checkout/success'."\">link</a>!</p>\n";
             echo "</body></html>\n";
             exit();
          } else {
             echo "We response from 2checkout.com can't be parsed. Contact site adimistrator, please!";
    //         $this->redirect(HTTP_SERVER . 'index.php?route=checkout/failure');

    //OLD (before patch)
    //      $order_info = $this->model_checkout_order->getOrder($this->request->post['order_number']);
    //
    //      if (md5($this->config->get('twocheckout_secret') . $this->config->get('twocheckout_account') . $this->request->post['order_number'] . $this->request->post['total']) == $this->request->post['key']) {
    //         $this->model_checkout_order->confirm($this->request->post['order_number'], $this->config->get('twocheckout_order_status_id'));
    //   
    //         $this->redirect(HTTP_SERVER . 'index.php?route=checkout/success');   
          }
       }
    }
    ?>
