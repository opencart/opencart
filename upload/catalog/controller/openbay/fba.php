<?php
class ControllerOpenbayFba extends Controller {
	public function eventAddOrderHistory($order_id) {
		if (!empty($order_id)) {
			$this->load->model('openbay/fba_order');
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$this->load->model('catalog/product');

			$this->openbay->fba->log('Event fired for order ID: ' . $order_id);

			$order = $this->model_checkout_order->getOrder($order_id);

			if ($this->config->get('openbay_fba_order_trigger_status') == $order['order_status_id']) {
				$order_products = $this->model_account_order->getOrderProducts($order_id);

				$fulfillment_items = array();

				foreach ($order_products as $order_product) {
					$product = $this->model_catalog_product->getProduct($order_product['product_id']);

					$this->openbay->fba->log(print_r($product, true));

					// check for fba
					if ($product['location'] == 'FBA') {
						$fulfillment_items[] = array(
							'order_product' => $order_product,
							'product' => $product,
						);
					}
				}

				// are any fba items?
				if (!empty($fulfillment_items)) {
					$request = array();

					$datetime = new DateTime($order['date_added']);
					$request['displayable_order_datetime'] = $datetime->format(DateTime::ISO8601);

					$request['seller_fulfillment_order_id'] = $this->config->get('openbay_fba_order_prefix').$order_id;
					$request['displayable_order_id'] = $order_id;
					$request['displayable_order_comment'] = '';
					$request['shipping_speed_category'] = $this->config->get('openbay_fba_shipping_speed');
					$request['fulfillment_action'] = ($this->config->get('openbay_fba_send_orders') == 1 ? 'Ship' : 'Hold');
					$request['fulfillment_policy'] = $this->config->get('openbay_fba_fulfill_policy');

					$request['destination_address'] = array(
						'name' => $order['shipping_firstname'] . ' ' . $order['shipping_lastname'],
						'line_1' => $order['shipping_address_1'],
						'line_2' => $order['shipping_address_2'],
						'line_3' => '',
						'state_or_province_code' => $order['shipping_zone'],
						'city' => $order['shipping_city'],
						'country_code' => $order['shipping_iso_code_2'],
						'postal_code' => $order['shipping_postcode'],
					);

					$request['items'] = array();

					

					// create the payload to send over to FBA

					// validation?

					// check for any settings about partially filled orders?

					// try the request

					// catch errors

					// sned email to admin about errors or confirmation
				}


			}

			if ($this->config->get('openbay_fba_cancel_order_trigger_status') != 0) {
				if ($this->config->get('openbay_fba_cancel_order_trigger_status') == $order['order_status_id']) {
					$response = $this->openbay->fba->call("v1/fba/fulfillments/" . $this->request->post['seller_fulfillment_order_id'] . "/cancel/", array(), 'POST');

					/**
					 * @todo check the response for any errors and sent alert to admin if there is a problem
					 **/

				}
			} else {
				$this->openbay->fba->log('FBA is not configured to automatically cancel fulfillments');
			}

			// how about notifications? does the merchant want a notification that here is a new fulfillment ready to be checked over?
			// alert of any missing products that were not in FBA?
			// any errors returned by FBA?

			$this->model_openbay_fba_order->addOrderHistory($order_id);
		}
	}
}