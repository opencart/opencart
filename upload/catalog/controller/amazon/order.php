<?php
class ControllerAmazonOrder extends Controller {
	public function index() {
		if ($this->config->get('amazon_status') != '1') {
			return;
		}

		$this->load->library('log');
		$this->load->model('checkout/order');
		$this->load->model('openbay/amazon_order');
		$this->language->load('openbay/amazon_order');

		$logger = new Log('amazon.log');
		$logger->write('amazon/order - started');

		$token = $this->config->get('openbay_amazon_token');

		$incoming_token = isset($this->request->post['token']) ? $this->request->post['token'] : '';

		if ($incoming_token !== $token) {
			$logger->write('amazon/order - Incorrect token: ' . $incoming_token);
			return;
		}

		$decrypted = $this->openbay->amazon->decryptArgs($this->request->post['data']);

		if (!$decrypted) {
			$logger->write('amazon/order Failed to decrypt data');
			return;
		}

		$order_xml = simplexml_load_string($decrypted);

		$amazon_order_status = trim(strtolower((string)$order_xml->Status));

		$amazon_order_id = (string)$order_xml->AmazonOrderId;
		$order_status = $this->model_openbay_amazon_order->getMappedStatus((string)$order_xml->Status);

		$logger->write('Received order ' . $amazon_order_id);

		$order_id = $this->model_openbay_amazon_order->getOrderId($amazon_order_id);

		// If the order already exists on opencart, ignore it.
		if ($order_id) {
			$logger->write("Duplicate order $amazon_order_id. Terminating.");
			$this->response->setOutput('Ok');
			return;
		}

		/* Check if order comes from subscribed marketplace */

		$currency_to = $this->config->get('config_currency');
		$order_currency = (string)$order_xml->Payment->CurrencyCode;

		$products = array();

		$products_total = 0;
		$products_shipping = 0;
		$products_tax = 0;
		$products_shipping_tax = 0;
		$gift_wrap = 0;
		$gift_wrap_tax = 0;

		$product_count = 0;

		$amazon_order_id = (string)$order_xml->AmazonOrderId;

		/* SKU => ORDER_ITEM_ID */
		$product_mapping = array();

		foreach ($order_xml->Items->Item as $item) {

			$total_price = $this->currency->convert((double)$item->Totals->Price, $order_currency, $currency_to);
			$tax_total = (double)$item->Totals->Tax;

			if ($tax_total == 0 && $this->config->get('openbay_amazon_order_tax') > 0) {
				$tax_total = (double)$item->Totals->Price * ($this->config->get('openbay_amazon_order_tax') / 100) / (1 + $this->config->get('openbay_amazon_order_tax') / 100);
			}

			$tax_total = $this->currency->convert($tax_total, $order_currency, $currency_to);

			$products_total += $total_price;
			$products_tax += $tax_total;

			$products_shipping += $this->currency->convert((double)$item->Totals->Shipping, $order_currency, $currency_to);

			$shipping_tax = (double)$item->Totals->ShippingTax;

			if ($shipping_tax == 0 && $this->config->get('openbay_amazon_order_tax') > 0) {
				$shipping_tax = (double)$item->Totals->Shipping * ($this->config->get('openbay_amazon_order_tax') / 100) / (1 + $this->config->get('openbay_amazon_order_tax') / 100);
			}

			$products_shipping_tax += $this->currency->convert($shipping_tax, $order_currency, $currency_to);

			$gift_wrap += $this->currency->convert((double)$item->Totals->GiftWrap, $order_currency, $currency_to);

			$item_gift_wrap_tax = (double)$item->Totals->GiftWrapTax;

			if ($item_gift_wrap_tax == 0 && $this->config->get('openbay_amazon_order_tax') > 0) {
				$item_gift_wrap_tax = (double)$item->Totals->GiftWrap * ($this->config->get('openbay_amazon_order_tax') / 100) / (1 + $this->config->get('openbay_amazon_order_tax') / 100);
			}

			$gift_wrap_tax += $this->currency->convert($item_gift_wrap_tax, $order_currency, $currency_to);

			$product_count += (int)$item->Ordered;

			if ((int)$item->Ordered == 0) {
				continue;
			}

			$product_id = $this->model_openbay_amazon_order->getProductId((string)$item->Sku);
			$product_var = $this->model_openbay_amazon_order->getProductVar((string)$item->Sku);

			$products[] = array(
				'product_id' => $product_id,
				'var' => $product_var,
				'sku' => (string)$item->Sku,
				'asin' => (string)$item->Asin,
				'order_item_id' => (string)$item->OrderItemId,
				'name' => (string)$item->Title,
				'model' => (string)$item->Sku,
				'quantity' => (int)$item->Ordered,
				'price' => sprintf('%.4f', ($total_price - $tax_total) / (int)$item->Ordered),
				'total' => sprintf('%.4f', $total_price - $tax_total),
				'tax' => $tax_total / (int)$item->Ordered,
				'reward' => '0',
				'option' => $this->model_openbay_amazon_order->getProductOptionsByVar($product_var),
				'download' => array(),
			);

			$product_mapping[(string)$item->Sku] = (string)$item->OrderItemId;
		}

		$total = sprintf('%.4f', $this->currency->convert((double)$order_xml->Payment->Amount, $order_currency, $currency_to));

		$address_line_2 = (string)$order_xml->Shipping->AddressLine2;
		if ((string)$order_xml->Shipping->AddressLine3 != '') {
			$address_line_2 .= ', ' . (string)$order_xml->Shipping->AddressLine3;
		}

		$customer_info = $this->db->query("SELECT `customer_id` FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape((string)$order_xml->Payment->Email) . "'")->row;
		$customer_id = '0';

		if(isset($customer_info['customer_id'])) {
			$customer_id = $customer_info['customer_id'];
		} else {
			/* Add a new customer */
			$customer_data = array(
				'firstname' => (string)$order_xml->Shipping->Name,
				'lastname' => '',
				'email' => (string)$order_xml->Payment->Email,
				'telephone' => (string)$order_xml->Shipping->Phone,
				'fax' => '',
				'newsletter' => '0',
				'customer_group_id' => $this->config->get('openbay_amazon_order_customer_group'),
				'password' => '',
				'status' => '0',
			);

			$this->db->query("
				INSERT INTO " . DB_PREFIX . "customer
				SET firstname = '" . $this->db->escape($customer_data['firstname']) . "',
					lastname = '" . $this->db->escape($customer_data['lastname']) . "',
					email = '" . $this->db->escape($customer_data['email']) . "',
					telephone = '" . $this->db->escape($customer_data['telephone']) . "',
					fax = '" . $this->db->escape($customer_data['fax']) . "',
					newsletter = '" . (int)$customer_data['newsletter'] . "',
					customer_group_id = '" . (int)$customer_data['customer_group_id'] . "',
					password = '',
					status = '" . (int)$customer_data['status'] . "',
					date_added = NOW()");

			$customer_id = $this->db->getLastId();
		}

		$shipping_first_name = (string)$order_xml->Shipping->FirstName;
		$shipping_last_name = (string)$order_xml->Shipping->LastName;

		if (empty($shipping_first_name) || empty($shipping_last_name)) {
			$shipping_first_name = (string)$order_xml->Shipping->Name;
			$shipping_last_name = '';
		}

		$order = array(
			'invoice_prefix' => $this->config->get('config_invoice_prefix'),
			'store_id' => $this->config->get('config_store_id'),
			'store_name' => $this->config->get('config_name') . ' / Amazon',
			'store_url' => $this->config->get('config_url'),
			'customer_id' => (int)$customer_id,
			'customer_group_id' => $this->config->get('openbay_amazon_order_customer_group'),
			'firstname' => $shipping_first_name,
			'lastname' => $shipping_last_name,
			'email' => (string)$order_xml->Payment->Email,
			'telephone' => (string)$order_xml->Shipping->Phone,
			'fax' => '',
			'shipping_firstname' => $shipping_first_name,
			'shipping_lastname' => $shipping_last_name,
			'shipping_company' => '',
			'shipping_address_1' => (string)$order_xml->Shipping->AddressLine1,
			'shipping_address_2' => $address_line_2,
			'shipping_city' => (string)$order_xml->Shipping->City,
			'shipping_postcode' => (string)$order_xml->Shipping->PostCode,
			'shipping_country' => $this->model_openbay_amazon_order->getCountryName((string)$order_xml->Shipping->CountryCode),
			'shipping_country_id' => $this->model_openbay_amazon_order->getCountryId((string)$order_xml->Shipping->CountryCode),
			'shipping_zone' => (string)$order_xml->Shipping->State,
			'shipping_zone_id' => $this->model_openbay_amazon_order->getZoneId((string)$order_xml->Shipping->State),
			'shipping_address_format' => '',
			'shipping_method' => (string)$order_xml->Shipping->Type,
			'shipping_code' => 'amazon.' . (string)$order_xml->Shipping->Type,
			'payment_firstname' => $shipping_first_name,
			'payment_lastname' => $shipping_last_name,
			'payment_company' => '',
			'payment_address_1' => (string)$order_xml->Shipping->AddressLine1,
			'payment_address_2' => $address_line_2,
			'payment_city' => (string)$order_xml->Shipping->City,
			'payment_postcode' => (string)$order_xml->Shipping->PostCode,
			'payment_country' => $this->model_openbay_amazon_order->getCountryName((string)$order_xml->Shipping->CountryCode),
			'payment_country_id' => $this->model_openbay_amazon_order->getCountryId((string)$order_xml->Shipping->CountryCode),
			'payment_zone' => (string)$order_xml->Shipping->State,
			'payment_zone_id' => $this->model_openbay_amazon_order->getZoneId((string)$order_xml->Shipping->State),
			'payment_address_format' => '',
			'payment_method' => $this->language->get('text_paid_amazon'),
			'payment_code' => 'amazon.amazon',
			'payment_company_id' => 0,
			'payment_tax_id' => 0,
			'comment' => '',
			'total' => $total,
			'affiliate_id' => '0',
			'commission' => '0.00',
			'language_id' => (int)$this->config->get('config_language_id'),
			'currency_id' => $this->currency->getId($order_currency),
			'currency_code' => (string)$order_currency,
			'currency_value' => $this->currency->getValue($order_currency),
			'ip' => '',
			'forwarded_ip' => '',
			'user_agent' => 'OpenBay Pro for Amazon',
			'accept_language' => '',
			'products' => $products,
			'vouchers' => array(),
			'totals' => array(
				array(
					'code' => 'sub_total',
					'title' => $this->language->get('text_total_sub'),
					'value' => sprintf('%.4f', $products_total),
					'sort_order' => '1',
				),
				array(
					'code' => 'shipping',
					'title' => $this->language->get('text_total_shipping'),
					'value' => sprintf('%.4f', $products_shipping),
					'sort_order' => '3',
				),
				array(
					'code' => 'tax',
					'title' => $this->language->get('text_tax'),
					'value' => sprintf('%.4f', $products_tax),
					'sort_order' => '4',
				),
				array(
					'code' => 'shipping_tax',
					'title' => $this->language->get('text_total_shipping_tax'),
					'value' => sprintf('%.4f', $products_shipping_tax),
					'sort_order' => '6',
				),
				array(
					'code' => 'gift_wrap',
					'title' => $this->language->get('text_total_giftwrap'),
					'value' => sprintf('%.4f', $gift_wrap),
					'sort_order' => '2',
				),
				array(
					'code' => 'gift_wrap_tax',
					'title' => $this->language->get('text_total_giftwrap_tax'),
					'value' => sprintf('%.4f', $gift_wrap_tax),
					'sort_order' => '5',
				),
				array(
					'code' => 'total',
					'title' => $this->language->get('text_total'),
					'value' => sprintf('%.4f', $total),
					'sort_order' => '7',
				),
			),
		);

		$order_id = $this->model_checkout_order->addOrder($order);

		$this->model_openbay_amazon_order->updateOrderStatus($order_id, $order_status);
		$this->model_openbay_amazon_order->addAmazonOrder($order_id, $amazon_order_id);
		$this->model_openbay_amazon_order->addAmazonOrderProducts($order_id, $product_mapping);

		foreach($products as $product) {
			if($product['product_id'] != 0) {
				$this->model_openbay_amazon_order->decreaseProductQuantity($product['product_id'], $product['quantity'], $product['var']);
			}
		}

		$logger->write('Order ' . $amazon_order_id . ' was added to the database (ID: ' . $order_id . ')');
		$logger->write("Finished processing the order");

		$logger->write("Notifying Openbay::addOrder($order_id)");
		$this->openbay->addOrder($order_id);
		$logger->write("Openbay notified");

		$this->model_openbay_amazon_order->acknowledgeOrder($order_id);

		if($this->config->get('openbay_amazon_notify_admin') == 1){
			$this->openbay->newOrderAdminNotify($order_id, $order_status);
		}

		$logger->write("Ok");
		$this->response->setOutput('Ok');
	}
}