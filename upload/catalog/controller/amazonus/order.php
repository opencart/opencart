<?php

class ControllerAmazonusOrder extends Controller {

    public function index() {
        if ($this->config->get('amazonus_status') != '1') {
            return;
        }
        
        $this->load->library('log');
        $this->load->library('amazonus');
        $this->load->model('checkout/order');
        $this->load->model('amazonus/order');
        $this->language->load('amazonus/order');
        
        $logger = new Log('amazonus.log');
        $logger->write('amazonus/order - started');
        
        $token = $this->config->get('openbay_amazonus_token');
        
        $incomingToken = isset($this->request->post['token']) ? $this->request->post['token'] : '';
        
        if ($incomingToken !== $token) {
            $logger->write('amazonus/order - Incorrect token: ' . $incomingToken);
            return;
        }
        
        $decrypted = $this->amazonus->decryptArgs($this->request->post['data']);
        
        if (!$decrypted) {
            $logger->write('amazonus/order Failed to decrypt data');
            return;
        }
        
        $orderXml = simplexml_load_string($decrypted);
        
        $amazonusOrderStatus = trim(strtolower((string) $orderXml->Status));
        
        $amazonusOrderId = (string)$orderXml->AmazonusOrderId;
        $orderStatus = $this->model_amazonus_order->getMappedStatus((string) $orderXml->Status);
        
        $logger->write('Received order ' . $amazonusOrderId);
        
        $orderId = $this->model_amazonus_order->getOrderId($amazonusOrderId);
        
        // If the order already exists on opencart, ignore it.
        if ($orderId) {            
            $logger->write("Duplicate order $amazonusOrderId. Terminating.");
            $this->response->setOutput('Ok');
            return;
        }
        
        /* Check if order comes from subscribed marketplace */
        
        $currencyTo = $this->config->get('config_currency');
        $orderCurrency = (string) $orderXml->Payment->CurrencyCode;

        $products = array();

        $productsTotal = 0;
        $productsShipping = 0;
        $productsTax = 0;
        $productsShippingTax = 0;
        $giftWrap = 0;
        $giftWrapTax = 0;
        
        $productCount = 0;

        $amazonusOrderId = (string)$orderXml->AmazonOrderId;

        /* SKU => ORDER_ITEM_ID */
        $productMapping = array();
        
        foreach ($orderXml->Items->Item as $item) {
            
            $totalPrice = $this->currency->convert((double)$item->Totals->Price, $orderCurrency, $currencyTo);
            $taxTotal = (double) $item->Totals->Tax;
            
            if ($taxTotal == 0 && $this->config->get('openbay_amazonus_order_tax') > 0) {
                $taxTotal = (double)$item->Totals->Price * ($this->config->get('openbay_amazonus_order_tax') / 100) / (1 + $this->config->get('openbay_amazonus_order_tax') / 100);
            }
            
            $taxTotal = $this->currency->convert($taxTotal, $orderCurrency, $currencyTo);

            $productsTotal += $totalPrice;
            $productsTax += $taxTotal;
            
            $productsShipping += $this->currency->convert((double) $item->Totals->Shipping, $orderCurrency, $currencyTo);
            
            $shippingTax = (double) $item->Totals->ShippingTax;
            
            if ($shippingTax == 0 && $this->config->get('openbay_amazonus_order_tax') > 0) {
                $shippingTax = (double) $item->Totals->Shipping * ($this->config->get('openbay_amazonus_order_tax') / 100) / (1 + $this->config->get('openbay_amazonus_order_tax') / 100);
            }
            
            $productsShippingTax += $this->currency->convert($shippingTax, $orderCurrency, $currencyTo);
            
            $giftWrap += $this->currency->convert((double) $item->Totals->GiftWrap, $orderCurrency, $currencyTo);
            
            $itemGiftWrapTax = (double) $item->Totals->GiftWrapTax;
            
            if ($itemGiftWrapTax == 0 && $this->config->get('openbay_amazonus_order_tax') > 0) {
                $itemGiftWrapTax = (double) $item->Totals->GiftWrap * ($this->config->get('openbay_amazonus_order_tax') / 100) / (1 + $this->config->get('openbay_amazonus_order_tax') / 100);
            }
            
            $giftWrapTax += $this->currency->convert($itemGiftWrapTax, $orderCurrency, $currencyTo);
            
            $productCount += (int) $item->Ordered;

            if ((int) $item->Ordered == 0) {
                continue;
            }
            
            $productId = $this->model_amazonus_order->getProductId((string) $item->Sku);
            $productVar = $this->model_amazonus_order->getProductVar((string) $item->Sku);
            
            $products[] = array(
                'product_id' => $productId,
                'var' => $productVar,
                'sku' => (string) $item->Sku,
                'asin' => (string) $item->Asin,
                'order_item_id' => (string) $item->OrderItemId, 
                'name' => (string) $item->Title,
                'model' => (string) $item->Sku,
                'quantity' => (int) $item->Ordered,
                'price' => sprintf('%.4f', ($totalPrice - $taxTotal) / (int) $item->Ordered),
                'total' => sprintf('%.4f', $totalPrice - $taxTotal),
                'tax' => $taxTotal / (int) $item->Ordered,
                'reward' => '0',
                'option' => $this->model_amazonus_order->getProductOptionsByVar($productVar),
                'download' => array(),
            );
            
            $productMapping[(string) $item->Sku] = (string) $item->OrderItemId;
        }

        $total = sprintf('%.4f', $this->currency->convert((double) $orderXml->Payment->Amount, $orderCurrency, $currencyTo));

        $addressLine2 = (string) $orderXml->Shipping->AddressLine2;
        if ((string) $orderXml->Shipping->AddressLine3 != '') {
            $addressLine2 .= ', ' . (string) $orderXml->Shipping->AddressLine3;
        }
        
        $customer_info = $this->db->query("SELECT `customer_id` FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape((string) $orderXml->Payment->Email) . "'")->row;
        $customer_id = '0';
        
        if(isset($customer_info['customer_id'])) {
            $customer_id = $customer_info['customer_id'];
        } else {            
            /* Add a new customer */
            $customerData = array(
                'firstname' => (string) $orderXml->Shipping->Name,
                'lastname' => '',
                'email' => (string) $orderXml->Payment->Email,
                'telephone' => (string) $orderXml->Shipping->Phone,
                'fax' => '',
                'newsletter' => '0',
                'customer_group_id' => $this->config->get('openbay_amazonus_order_customer_group'),
                'password' => '',
                'status' => '0',
            );
            
            $this->db->query("
                INSERT INTO " . DB_PREFIX . "customer 
                SET firstname = '" . $this->db->escape($customerData['firstname']) . "', 
                    lastname = '" . $this->db->escape($customerData['lastname']) . "', 
                    email = '" . $this->db->escape($customerData['email']) . "', 
                    telephone = '" . $this->db->escape($customerData['telephone']) . "', 
                    fax = '" . $this->db->escape($customerData['fax']) . "', 
                    newsletter = '" . (int)$customerData['newsletter'] . "', 
                    customer_group_id = '" . (int)$customerData['customer_group_id'] . "', 
                    password = '', 
                    status = '" . (int)$customerData['status'] . "', 
                    date_added = NOW()");
            
            $customer_id = $this->db->getLastId();
        }
        
        $shippingFirstName = (string) $orderXml->Shipping->FirstName;
        $shippingLastName = (string) $orderXml->Shipping->LastName;
        
        if (empty($shippingFirstName) || empty($shippingLastName)) {
            $shippingFirstName = (string) $orderXml->Shipping->Name;
            $shippingLastName = '';
        }
        
        $order = array(
            'invoice_prefix' => $this->config->get('config_invoice_prefix'),
            'store_id' => $this->config->get('config_store_id'),
            'store_name' => $this->config->get('config_name') . ' / Amazon US',
            'store_url' => $this->config->get('config_url'),
            'customer_id' => (int) $customer_id,
            'customer_group_id' => $this->config->get('openbay_amazonus_order_customer_group'),
            'firstname' => $shippingFirstName,
            'lastname' => $shippingLastName,
            'email' => (string) $orderXml->Payment->Email,
            'telephone' => (string) $orderXml->Shipping->Phone,
            'fax' => '',
            'shipping_firstname' => $shippingFirstName,
            'shipping_lastname' => $shippingLastName,
            'shipping_company' => '',
            'shipping_address_1' => (string) $orderXml->Shipping->AddressLine1,
            'shipping_address_2' => $addressLine2,
            'shipping_city' => (string) $orderXml->Shipping->City,
            'shipping_postcode' => (string) $orderXml->Shipping->PostCode,
            'shipping_country' => $this->model_amazonus_order->getCountryName((string) $orderXml->Shipping->CountryCode),
            'shipping_country_id' => $this->model_amazonus_order->getCountryId((string) $orderXml->Shipping->CountryCode),
            'shipping_zone' => (string) $orderXml->Shipping->State,
            'shipping_zone_id' => $this->model_amazonus_order->getZoneId((string) $orderXml->Shipping->State),
            'shipping_address_format' => '',
            'shipping_method' => (string) $orderXml->Shipping->Type,
            'shipping_code' => 'amazonus.' . (string) $orderXml->Shipping->Type,
            'payment_firstname' => $shippingFirstName,
            'payment_lastname' => $shippingLastName,
            'payment_company' => '',
            'payment_address_1' => (string) $orderXml->Shipping->AddressLine1,
            'payment_address_2' => $addressLine2,
            'payment_city' => (string) $orderXml->Shipping->City,
            'payment_postcode' => (string) $orderXml->Shipping->PostCode,
            'payment_country' => $this->model_amazonus_order->getCountryName((string) $orderXml->Shipping->CountryCode),
            'payment_country_id' => $this->model_amazonus_order->getCountryId((string) $orderXml->Shipping->CountryCode),
            'payment_zone' => (string) $orderXml->Shipping->State,
            'payment_zone_id' => $this->model_amazonus_order->getZoneId((string) $orderXml->Shipping->State),
            'payment_address_format' => '',
            'payment_method' => $this->language->get('paid_on_amazonus_text'),
            'payment_code' => 'amazonus.amazonus',
            'payment_company_id' => 0,
            'payment_tax_id' => 0,
            'comment' => '',
            'total' => $total,
            'affiliate_id' => '0',
            'commission' => '0.00',
            'language_id' => (int)$this->config->get('config_language_id'),
            'currency_id' => $this->currency->getId($orderCurrency),
            'currency_code' => (string) $orderCurrency,
            'currency_value' => $this->currency->getValue($orderCurrency),
            'ip' => '',
            'forwarded_ip' => '',
            'user_agent' => 'OpenBay Pro for Amazon US',
            'accept_language' => '',
            'products' => $products,
            'vouchers' => array(),
            'totals' => array(
                array(
                    'code' => 'sub_total',
                    'title' => $this->language->get('sub_total_text'),
                    'text' => $this->currency->format($productsTotal, $orderCurrency),
                    'value' => sprintf('%.4f', $productsTotal),
                    'sort_order' => '1',
                ),
                array(
                    'code' => 'shipping',
                    'title' => $this->language->get('shipping_text'),
                    'text' => $this->currency->format($productsShipping, $orderCurrency),
                    'value' => sprintf('%.4f', $productsShipping),
                    'sort_order' => '3',
                ),
                array(
                    'code' => 'tax',
                    'title' => $this->language->get('tax_text'),
                    'text' => $this->currency->format($productsTax, $orderCurrency),
                    'value' => sprintf('%.4f', $productsTax),
                    'sort_order' => '4',
                ),
                array(
                    'code' => 'shipping_tax',
                    'title' => $this->language->get('shipping_tax_text'),
                    'text' => $this->currency->format($productsShippingTax, $orderCurrency),
                    'value' => sprintf('%.4f', $productsShippingTax),
                    'sort_order' => '6',
                ),
                array(
                    'code' => 'gift_wrap',
                    'title' => $this->language->get('gift_wrap_text'),
                    'text' => $this->currency->format($giftWrap, $orderCurrency),
                    'value' => sprintf('%.4f', $giftWrap),
                    'sort_order' => '2',
                ),
                array(
                    'code' => 'gift_wrap_tax',
                    'title' => $this->language->get('gift_wrap_tax_text'),
                    'text' => $this->currency->format($giftWrapTax, $orderCurrency),
                    'value' => sprintf('%.4f', $giftWrapTax),
                    'sort_order' => '5',
                ),
                array(
                    'code' => 'total',
                    'title' => $this->language->get('total_text'),
                    'text' => $this->currency->format($total, $orderCurrency),
                    'value' => sprintf('%.4f', $total),
                    'sort_order' => '7',
                ),
            ),
        );
        
        $addOrderMethod = 'addOrder';
        
        if (version_compare(VERSION, '1.5.1.3', '<=')) {
            $addOrderMethod = 'create';
        }
        
        $orderId = $this->model_checkout_order->$addOrderMethod($order);

        $this->model_amazonus_order->updateOrderStatus($orderId, $orderStatus);
        $this->model_amazonus_order->addAmazonusOrder($orderId, $amazonusOrderId);
        $this->model_amazonus_order->addAmazonusOrderProducts($orderId, $productMapping);
        
        foreach($products as $product) {
           if($product['product_id'] != 0) {
               $this->model_amazonus_order->decreaseProductQuantity($product['product_id'], $product['quantity'], $product['var']);
           }
        }
        
        $logger->write('Order ' . $amazonusOrderId . ' was added to the database (ID: ' . $orderId . ')');
        $logger->write("Finished processing the order");
        
        $logger->write("Notifying Openbay::orderNew($orderId)");
        $this->openbay->orderNew($orderId);
        $logger->write("Openbay notified");
        
        $this->model_amazonus_order->acknowledgeOrder($orderId);

        //send an email to the administrator about the sale
        if($this->config->get('openbay_amazonus_notify_admin') == 1){
            $this->openbay->newOrderAdminNotify($orderId, $orderStatus);
        }

        $logger->write("Ok");
        $this->response->setOutput('Ok');
    }

}