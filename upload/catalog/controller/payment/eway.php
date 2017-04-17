<?php ob_start();

class ControllerPaymentEway extends Controller {
	protected function index() {

	    $this->id = 'payment';

		$this->load->language('payment/eway');
    	$this->data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->data['item_name'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8');
		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

        if ($this->config->get('eway_test')){ $this->data['text_testing'] = $this->language->get('text_testing'); }

//        error_reporting(E_ALL);
//        ini_set("display_errors", 1);

        require_once(realpath(dirname(__FILE__).'/eWAY/RapidAPI.php'));
        $username = $this->config->get('eway_username');
        $password = $this->config->get('eway_password');
        $livemode = $this->config->get('eway_test') ? false : true;
        $eway_service = new RapidAPI($livemode, $username, $password);

        // Create AccessCode Request Object
        $request = new CreateAccessCodeRequest();

        $request->Customer->Reference = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
        $request->Customer->Title = 'Mr.';
        $request->Customer->FirstName = strval($order_info['payment_firstname']);
        $request->Customer->LastName  = strval($order_info['payment_lastname']);
        $request->Customer->CompanyName = strval($order_info['payment_company']);
        $request->Customer->JobDescription = '';
        $request->Customer->Street1 = strval($order_info['payment_address_1']);
        $request->Customer->Street2 = strval($order_info['payment_address_2']);
        $request->Customer->City = strval($order_info['payment_city']);
        $request->Customer->State = strval($order_info['payment_zone']);
        $request->Customer->PostalCode = strval($order_info['payment_postcode']);
        $request->Customer->Country = strtolower($order_info['payment_iso_code_2']);
        $request->Customer->Email = $order_info['email'];
        $request->Customer->Phone = $order_info['telephone'];
        $request->Customer->Mobile = '';

        // require field
        $request->ShippingAddress->FirstName = strval($order_info['shipping_firstname']);
        $request->ShippingAddress->LastName  = strval($order_info['shipping_lastname']);
        $request->ShippingAddress->Street1 = strval($order_info['shipping_address_1']);
        $request->ShippingAddress->Street2 = strval($order_info['shipping_address_2']);
        $request->ShippingAddress->City = strval($order_info['shipping_city']);
        $request->ShippingAddress->State = strval($order_info['shipping_zone']);
        $request->ShippingAddress->PostalCode = strval($order_info['shipping_postcode']);
        $request->ShippingAddress->Country = strtolower($order_info['shipping_iso_code_2']);
        $request->ShippingAddress->Email = $order_info['email'];
        $request->ShippingAddress->Phone = $order_info['telephone'];
        $request->ShippingAddress->ShippingMethod = "Unknown";

        $invoiceDesc = '';
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
            $item = new EwayLineItem();
            $item->SKU = $product['product_id'];
            $item->Description = $product['name'];
            $request->Items->LineItem[] = $item;
            $invoiceDesc .= $product['name'] . ', ';
        }
        $invoiceDesc = substr($invoiceDesc, 0, -2);
        if(strlen($invoiceDesc) > 64) $invoiceDesc = substr($invoiceDesc , 0 , 61) . '...';

        $opt1 = new EwayOption();
        $opt1->Value = $this->session->data['order_id'];
        $request->Options->Option[0]= $opt1;

        $request->Payment->TotalAmount = number_format($amount, 2, '.', '') * 100;
        $request->Payment->InvoiceNumber = $this->session->data['order_id'];
        $request->Payment->InvoiceDescription = $invoiceDesc;
        $request->Payment->InvoiceReference = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
        $request->Payment->CurrencyCode = $order_info['currency_code'];

        $request->RedirectUrl = $this->url->link('payment/eway/callback', '', 'SSL');
        $request->Method = 'ProcessPayment';

        //Call RapidAPI
        $result = $eway_service->CreateAccessCode($request);
        $this->template = 'default/template/payment/eway.tpl';

        if (isset($result->Errors)) {
            //Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
            $ErrorArray = explode(",", $result->Errors);
            $lblError = "";
            foreach ( $ErrorArray as $error ) {
                if (isset($eway_service->APIConfig[$error]))
                    $lblError .= $error." ".$eway_service->APIConfig[$error]."<br>";
                else
                    $lblError .= $error;
            }
        }

        if (isset($lblError)) {
            $this->data['error'] = $lblError;
        } else {
            $this->data['action'] = $result->FormActionURL;
            $this->data['AccessCode'] = $result->AccessCode;
        }
        $this->render();

	} //end index function

	public function callback() {
	    // fix stupid issue after redirect
	    // index.php?route=payment/eway/callback&amp;AccessCode=blabla
        if (isset($this->request->get['AccessCode']) || isset($this->request->get['amp;AccessCode'])) {
            require_once(realpath(dirname(__FILE__).'/eWAY/RapidAPI.php'));
            $username = $this->config->get('eway_username');
            $password = $this->config->get('eway_password');
            $livemode = $this->config->get('eway_test') ? false : true;
            $eway_service = new RapidAPI($livemode, $username, $password);

            $isError = false;
            $request = new GetAccessCodeResultRequest();
            if ( isset($this->request->get['amp;AccessCode']) ) {
                $request->AccessCode = $this->request->get['amp;AccessCode'];
            } else {
                $request->AccessCode = $this->request->get['AccessCode'];
            }

            //Call RapidAPI to get the result
            $result = $eway_service->GetAccessCodeResult($request);

            // Check if any error returns
            if(isset($result->Errors)) {
                // Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
                $ErrorArray = explode(",", $result->Errors);
                $lblError = "";
                $isError = true;
                foreach ( $ErrorArray as $error ) {
                    $error = trim($error);
                    $lblError .= $eway_service->APIConfig[$error]."<br>";
                }
            }

            if (! $isError) {
                if (! $result->TransactionStatus) {
                    $isError = true;
                    $lblError = "Payment Declined - " . $result->ResponseCode;
                }
            }

            if ($isError) {
                $this->language->load('payment/eway');

		        $this->data['breadcrumbs'] = array();
                $this->data['breadcrumbs'][] = array(
                    'href'      => $this->url->link('common/home'),
                    'text'      => $this->language->get('text_home'),
                    'separator' => false
                );
                $this->data['breadcrumbs'][] = array(
                    'href'      => $this->url->link('checkout/cart'),
                    'text'      => $this->language->get('text_basket'),
                    'separator' => $this->language->get('text_separator')
                );
                $this->data['breadcrumbs'][] = array(
                    'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
                    'text'      => $this->language->get('text_checkout'),
                    'separator' => $this->language->get('text_separator')
                );

                $this->data['heading_title'] = 'Transaction Failed';
                $this->data['text_message'] = '<div class="content">' . $lblError . '</div>';
                $this->data['button_continue'] = $this->language->get('button_continue');
                $this->data['continue'] = $this->url->link('checkout/checkout');

			    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
                } else {
                    $this->template = 'default/template/common/success.tpl';
                }

                $this->children = array(
                    'common/column_left',
                    'common/column_right',
                    'common/content_top',
                    'common/content_bottom',
                    'common/footer',
                    'common/header'
                );

                $this->response->setOutput($this->render());
            } else {
                $order_id = $result->Options->Option[0]->Value;
                $this->load->model('checkout/order');
                $order_info = $this->model_checkout_order->getOrder($order_id);
                $this->model_checkout_order->confirm($order_id, $this->config->get('eway_order_status_id'));
                header('location:' . $this->url->link('checkout/success', '', 'SSL'));
            }
        }
    }

} //end Controller class

?>