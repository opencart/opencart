<?php
class ControllerExtensionOpenbayFba extends Controller {
	public function eventAddOrderHistory($route, $data) {
		$this->openbay->fba->log('eventAddOrderHistory Event fired: ' . $route);

		if (isset($data[0]) && !empty($data[0])) {
			$this->load->model('checkout/order');
			$this->load->model('account/order');
			$this->load->model('catalog/product');

			$this->openbay->fba->log('eventAddOrderHistory Event fired for order ID: ' . $data[0]);

			$order = $this->model_checkout_order->getOrder($data[0]);

			if ($order['shipping_method']) {
				if ($this->config->get('openbay_fba_order_trigger_status') == $order['order_status_id']) {
					$fba_fulfillment_id = $this->openbay->fba->createFBAFulfillmentID($data[0], 0);

					$order_products = $this->model_account_order->getOrderProducts($data[0]);

					$total_order_products = count($order_products);

					$fulfillment_items = array();

					foreach ($order_products as $order_product) {
						$product = $this->model_catalog_product->getProduct($order_product['product_id']);

						if ($product['location'] == 'FBA') {
							$fulfillment_items[] = array(
									'seller_sku' => $product['sku'],
									'quantity' => $order_product['quantity'],
									'seller_fulfillment_order_item_id' => $this->config->get('openbay_fba_order_prefix') . $fba_fulfillment_id . '-' . $order_product['order_product_id'],
									'per_unit_declared_value' => array(
											'currency_code' => $order['currency_code'],
											'value' => number_format($order_product['price'], 2)
									),
							);
						}
					}

					$total_fulfillment_items = count($fulfillment_items);

					if (($total_order_products == $total_fulfillment_items) || ($this->config->get('openbay_fba_only_fill_complete') != 1)) {
						if (!empty($fulfillment_items)) {
							$request = array();

							$datetime = new DateTime($order['date_added']);
							$request['displayable_order_datetime'] = $datetime->format(DateTime::ISO8601);

							$request['seller_fulfillment_order_id'] = $this->config->get('openbay_fba_order_prefix') . $data[0] . '-' . $fba_fulfillment_id;
							$request['displayable_order_id'] = $data[0];
							$request['displayable_order_comment'] = 'none';
							$request['shipping_speed_category'] = $this->config->get('openbay_fba_shipping_speed');
							$request['fulfillment_action'] = ($this->config->get('openbay_fba_send_orders') == 1 ? 'Ship' : 'Hold');
							$request['fulfillment_policy'] = $this->config->get('openbay_fba_fulfill_policy');

							$request['destination_address'] = array(
									'name' => $order['shipping_firstname'] . ' ' . $order['shipping_lastname'],
									'line_1' => (!empty($order['shipping_company']) ? $order['shipping_company'] : $order['shipping_address_1']),
									'line_2' => (!empty($order['shipping_company']) ? $order['shipping_address_1'] : $order['shipping_address_2']),
									'line_3' => (!empty($order['shipping_company']) ? $order['shipping_address_2'] : ''),
									'state_or_province_code' => $order['shipping_zone'],
									'city' => $order['shipping_city'],
									'country_code' => $order['shipping_iso_code_2'],
									'postal_code' => $order['shipping_postcode'],
							);

							$request['items'] = $fulfillment_items;

							$response = $this->openbay->fba->call("v1/fba/fulfillments/", $request, 'POST');

							if ($response['response_http'] != 201) {
								/**
								 * @todo notify the admin about any errors
								 */
								$this->openbay->fba->updateFBAOrderStatus($data[0], 1);
							} else {
								if ($this->config->get('openbay_fba_send_orders') == 1) {
									$this->openbay->fba->updateFBAOrderStatus($data[0], 3);
								} else {
									$this->openbay->fba->updateFBAOrderStatus($data[0], 2);
								}

								$this->openbay->fba->updateFBAOrderRef($data[0], $this->config->get('openbay_fba_order_prefix') . $data[0] . '-' . $fba_fulfillment_id);
							}

							$this->openbay->fba->populateFBAFulfillment(json_encode($request), json_encode($response), $response['response_http'], $fba_fulfillment_id);
							$this->openbay->fba->updateFBAOrderFulfillmentID($data[0], $fba_fulfillment_id);
						} else {
							$this->openbay->fba->log('No FBA items found for this order');
						}
					} else {
						$this->openbay->fba->log('Products:' . $total_order_products . ', Fulfillment products: ' . $total_fulfillment_items . ' - settings do not allow incomplete order fulfillment.');
					}
				}
			}

			// how about notifications? does the merchant want a notification that here is a new fulfillment ready to be checked over?
			// alert of any missing products that were not in FBA?
			// any errors returned by FBA?
		}
	}

	public function eventAddOrder($route, $data) {
		$this->openbay->fba->log('eventAddOrder Event fired: ' . $route);

		if (isset($data[0]) && !empty($data[0])) {
			$this->load->model('checkout/order');

			$this->openbay->fba->log('Order ID: ' . (int)$data[0]);

			$order = $this->model_checkout_order->getOrder((int)$data[0]);

			if ($order['shipping_method']) {
				$this->openbay->fba->createFBAOrderID((int)$data[0]);
			}
		}
	}
}