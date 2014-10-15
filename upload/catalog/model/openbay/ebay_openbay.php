<?php
class ModelOpenbayEbayOpenbay extends Model{
	public function importOrders($data) {
		$this->default_shipped_id         = $this->config->get('EBAY_DEF_SHIPPED_ID');
		$this->default_paid_id            = $this->config->get('EBAY_DEF_PAID_ID');
		$this->default_refunded_id        = $this->config->get('EBAY_DEF_REFUNDED_ID');
		$this->default_pending_id         = $this->config->get('EBAY_DEF_IMPORT_ID');

		$this->default_part_refunded_id   = $this->config->get('EBAY_DEF_PARTIAL_REFUND_ID');
		if ($this->default_part_refunded_id == null) {
			$this->default_part_refunded_id = $this->default_paid_id;
		}

		$this->tax                        = ($this->config->get('tax') == '') ? '1' : (($this->config->get('tax') / 100) + 1);
		$this->tax_type                   = $this->config->get('ebay_tax_listing');
		$data                             = unserialize($data);

		if (isset($data->ordersV2)) {
			if (!empty($data->ordersV2)) {
				if (is_array($data->ordersV2)) {
					foreach ($data->ordersV2 as $order) {
						if (isset($order->smpId) && (int)$order->smpId != 0) {
							$this->orderHandle($order);
						}
					}
				} else {
					if (isset($data->ordersV2->smpId) && (int)$data->ordersV2->smpId != 0) {
						$this->orderHandle($data->ordersV2);
					}
				}
			} else {
				$this->openbay->ebay->log('Order object empty - no orders');
			}
		} else {
			$this->openbay->ebay->log('Data failed to unserialize');
		}
	}

	public function orderHandle($order) {
		$this->load->model('checkout/order');
		$this->load->model('openbay/ebay_order');

		if ($this->model_openbay_ebay_order->lockExists($order->smpId) == true) {
			return;
		}

		if (!is_array($order->txn)) {
			$order->txn = array($order->txn);
		}

		$order_id = $this->model_openbay_ebay_order->find($order->smpId);

		$created_hours = (int)$this->config->get('openbaypro_created_hours');
		if ($created_hours == 0 || $created_hours == '') {
			$created_hours = 24;
		}

		$from = date("Y-m-d H:i:00", mktime(date("H")-(int)$created_hours, date("i"), date("s"), date("m"), date("d"), date("y")));
		$this->openbay->ebay->log('Accepting orders newer than: ' . $from);

		if ($order_id != false) {
			$order_loaded   = $this->model_checkout_order->getOrder($order_id);
			$order_history  = $this->model_openbay_ebay_order->getHistory($order_id);

			$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updating');

			/* check user details to see if we have now been passed the user info */
			/* if we have these details then we have the rest of the delivery info */
			if (!empty($order->address->name) && !empty($order->address->street1)) {
				$this->openbay->ebay->log('User info found');
				if ($this->model_openbay_ebay_order->hasUser($order_id) == false) {
					$user       = $this->handleUserAccount($order);
					/* update if the user details have not been assigned to the order */
					$this->updateOrderWithConfirmedData($order_id, $order, $user);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updated with user info');
				}
			} else {
				$this->openbay->ebay->log('No user info');
			}

			if ($order->shipping->status == 'Shipped' && ($order_loaded['order_status_id'] != $this->default_shipped_id) && $order->payment->status == 'Paid') {
				$this->model_openbay_ebay_order->update($order_id, $this->default_shipped_id);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Shipped');
			} elseif ($order->payment->status == 'Paid' && isset($order->payment->date) && $order->shipping->status != 'Shipped' && ($order_loaded['order_status_id'] != $this->default_paid_id)) {
				$this->model_openbay_ebay_order->update($order_id, $this->default_paid_id);
				$this->model_openbay_ebay_order->updatePaymentDetails($order_id, $order);

				if ($this->config->get('openbaypro_stock_allocate') == 1) {
					$this->openbay->ebay->log('Stock allocation is set to allocate stock when an order is paid');
					$this->model_openbay_ebay_order->addOrderLines($order, $order_id);
					$this->externalApplicationNotify($order_id);
				}

				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Paid');
			} elseif (($order->payment->status == 'Refunded' || $order->payment->status == 'Unpaid') && ($order_loaded['order_status_id'] != $this->default_refunded_id) && in_array($this->default_paid_id, $order_history)) {
				/* @todo what happens if the order has never been paid? - need to find a cancelled in ebay flag*/
				$this->model_openbay_ebay_order->update($order_id, $this->default_refunded_id);
				$this->model_openbay_ebay_order->cancel($order_id);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Refunded');
			} elseif ($order->payment->status == 'Part-Refunded' && ($order_loaded['order_status_id'] != $this->default_part_refunded_id) && in_array($this->default_paid_id, $order_history)) {
				$this->model_openbay_ebay_order->update($order_id, $this->default_part_refunded_id);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Part Refunded');
			} else {
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> No Update');
			}
		} else {
			$this->openbay->ebay->log('Created: ' . $order->order->created);

			if (isset($order->order->checkoutstatus)) {
				$this->openbay->ebay->log('Checkout: ' . $order->order->checkoutstatus);
			}
			if (isset($order->payment->date)) {
				$this->openbay->ebay->log('Paid date: ' . $order->payment->date);
			}
			/**
			 * @TODO - FOLLOWING ORDER STATE TESTS REQUIRED
			 *
			 * - single item order, not checked out but then marked as paid. i.e. user wants to pay by manual method such as cheque
			 * - multi item order, same as above. Is this possible? i dont think the order will combine if checkout not done.
			 */

			if ($this->config->get('ebay_import_unpaid') == 1) {
				$this->openbay->ebay->log('Set to import unpaid orders');
			} else {
				$this->openbay->ebay->log('Ignore unpaid orders');
			}

			if (($order->order->created >= $from || (isset($order->payment->date) && $order->payment->date >= $from)) && (isset($order->payment->date) || $this->config->get('ebay_import_unpaid') == 1)) {

				$this->openbay->ebay->log('Creating new order');

				/* need to create the order without creating customer etc */
				$order_id = $this->create($order);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Created . ');

				/* add link */
				$this->model_openbay_ebay_order->orderLinkCreate((int)$order_id, (int)$order->smpId);

				/* check user details to see if we have now been passed the user info, if we have these details then we have the rest of the delivery info */
				if (!empty($order->address->name) && !empty($order->address->street1)) {
					$this->openbay->ebay->log('User info found . ');
					if ($this->model_openbay_ebay_order->hasUser($order_id) == false) {
						$user = $this->handleUserAccount($order);
						/* update if the user details have not been assigned to the order */
						$this->updateOrderWithConfirmedData($order_id, $order, $user);
						$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updated with user info . ');
					}
				} else {
					$this->openbay->ebay->log('No user information . ');
				}

				//new order, set to pending initially.
				$this->model_openbay_ebay_order->confirm($order_id, $this->default_pending_id, '[eBay Import:' . (int)$order->smpId . ']');
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Pending');
				$order_status_id = $this->default_pending_id;

				//order has been paid
				if ($order->payment->status == 'Paid') {
					$this->model_openbay_ebay_order->update($order_id, $this->default_paid_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Paid');
					$order_status_id = $this->default_paid_id;

					if ($this->config->get('openbaypro_stock_allocate') == 1) {
						$this->openbay->ebay->log('Stock allocation is set to allocate stock when an order is paid');
						$this->model_openbay_ebay_order->addOrderLines($order, $order_id);
						$this->externalApplicationNotify($order_id);
					}
				}

				//order has been refunded
				if ($order->payment->status == 'Refunded') {
					$this->model_openbay_ebay_order->update($order_id, $this->default_refunded_id);
					$this->model_openbay_ebay_order->cancel($order_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Refunded');
					$order_status_id = $this->default_refunded_id;
				}

				//order is part refunded
				if ($order->payment->status == 'Part-Refunded') {
					$this->model_openbay_ebay_order->update($order_id, $this->default_part_refunded_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Part Refunded');
					$order_status_id = $this->default_part_refunded_id;
				}

				//order payment is clearing
				if ($order->payment->status == 'Clearing') {
					$this->model_openbay_ebay_order->update($order_id, $this->default_pending_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Clearing');
					$order_status_id = $this->default_pending_id;
				}

				//order is marked shipped
				if ($order->shipping->status == 'Shipped') {
					$this->model_openbay_ebay_order->update($order_id, $this->default_shipped_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Shipped');
					$order_status_id = $this->default_shipped_id;
				}

				// Admin Alert Mail
				if ($this->config->get('openbaypro_confirmadmin_notify') == 1) {
					$this->openbay->newOrderAdminNotify($order_id, $order_status_id);
				}
			}
		}

		if ($this->config->get('openbaypro_stock_allocate') == 0) {
			$this->openbay->ebay->log('Stock allocation is set to allocate stock when an item is bought');
			$this->model_openbay_ebay_order->addOrderLines($order, $order_id);
			$this->externalApplicationNotify($order_id);
		}

		if (!empty($order->cancelled)) {
			$this->openbay->ebay->log('There are cancelled items in the order');
			$this->model_openbay_ebay_order->removeOrderLines($order->cancelled, $order_id);
		}

		//remove the lock.
		$this->model_openbay_ebay_order->lockDelete($order->smpId);
	}

	private function create($order) {
		if ($this->openbay->addonLoad('openstock')) {
			$openstock = true;
		} else {
			$openstock = false;
		}

		$this->load->model('localisation/currency');
		$this->load->model('catalog/product');

		$currency = $this->model_localisation_currency->getCurrencyByCode($this->config->get('openbay_def_currency'));

		if ($this->config->get('openbaypro_create_date') == 1) {
			$created_date_obj = new DateTime((string)$order->order->created);
			$offset = ($this->config->get('openbaypro_time_offset') != '') ? (int)$this->config->get('openbaypro_time_offset') : (int)0;
			$created_date_obj->modify($offset . ' hour');
			$created_date = $created_date_obj->format('Y-m-d H:i:s');
		} else {
			$created_date = date("Y-m-d H:i:s");
			$offset = 0;
		}

		$this->openbay->ebay->log('create() - Order date: ' . $created_date);
		$this->openbay->ebay->log('create() - Original date: ' . (string)$order->order->created);
		$this->openbay->ebay->log('create() - Offset: ' . $offset);
		$this->openbay->ebay->log('create() - Server time: ' . date("Y-m-d H:i:s"));

		$this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET
		   `store_id`                 = '" . (int)$this->config->get('config_store_id') . "',
		   `store_name`               = '" . $this->db->escape($this->config->get('config_name') . ' / eBay') . "',
		   `store_url`                = '" . $this->db->escape($this->config->get('config_url')) . "',
		   `invoice_prefix`           = '" . $this->db->escape($this->config->get('config_invoice_prefix')) . "',
		   `comment`                  = '" . $this->db->escape((string)$order->order->message) . "',
		   `total`                    = '" . (double)$order->order->total . "',
		   `affiliate_id`             = '0',
		   `commission`               = '0',
		   `language_id`              = '" . (int)$this->config->get('config_language_id') . "',
		   `currency_id`              = '" . (int)$currency['currency_id'] . "',
		   `currency_code`            = '" . $this->db->escape($currency['code']) . "',
		   `currency_value`           = '" . (double)$currency['value'] . "',
		   `ip`                       = '',
		   `date_added`               = '" . $this->db->escape($created_date) . "',
		   `date_modified`            = NOW(),
		   `customer_id`              = 0
		");

		$order_id = $this->db->getLastId();

		foreach ($order->txn as $txn) {
			$product_id = $this->openbay->ebay->getProductId($txn->item->id);

			if ($product_id != false) {
				$this->openbay->ebay->log('create() - Product ID: "' . $product_id . '" from ebay item: ' . $txn->item->id . ' was returned');

				if (!empty($txn->item->variantsku) && $openstock == true) {
					$model_number = $this->openbay->getProductModelNumber($product_id, $txn->item->variantsku);
				} else {
					$model_number = $this->openbay->getProductModelNumber($product_id);
				}
			} else {
				$this->openbay->ebay->log('create() - No product ID from ebay item: ' . $txn->item->id . ' was returned');
				$model_number = '';
			}

			$qty = (int)$txn->item->qty;
			$price = (double)$txn->item->price;
			$this->openbay->ebay->log('create() - Item price: ' . $price);

			if ($this->tax_type == 1) {
				//calculate taxes that come in from eBay
				$this->openbay->ebay->log('create() - Using tax rates from eBay');

				$price_net = $price;
				$this->openbay->ebay->log('create() - Net price: ' . $price_net);

				$total_net = $price * $qty;
				$this->openbay->ebay->log('create() - Total net price: ' . $total_net);

				$tax = number_format((double)$txn->item->tax->item, 4, ' . ', '');
				$this->openbay->ebay->log('create() - Tax: ' . $tax);
			} else {
				//use the store pre-set tax-rate for everything
				$this->openbay->ebay->log('create() - Using tax rates from store');

				$price_net = $price / $this->tax;
				$this->openbay->ebay->log('create() - Net price: ' . $price_net);

				$total_net = $price_net * $qty;
				$this->openbay->ebay->log('create() - Total net price: ' . $total_net);

				$tax = number_format(($price - $price_net), 4, ' . ', '');
				$this->openbay->ebay->log('create() - Tax: ' . $tax);
			}

			$txn->item->name            = stripslashes($txn->item->name);
			$txn->item->varianttitle    = stripslashes($txn->item->varianttitle);
			$txn->item->sku             = stripslashes($txn->item->sku);
			$txn->item->variantsku      = stripslashes($txn->item->variantsku);

			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET
					`order_id`            = '" . (int)$order_id . "',
					`product_id`          = '" . (int)$product_id . "',
					`name`                = '" . $this->db->escape((isset($txn->item->varianttitle) && !empty($txn->item->varianttitle)) ? $txn->item->varianttitle : $txn->item->name) . "',
					`model`               = '" . $this->db->escape($model_number) . "',
					`quantity`            = '" . (int)$qty . "',
					`price`               = '" . (double)$price_net . "',
					`total`               = '" . (double)$total_net . "',
					`tax`                 = '" . (double)$tax . "'
				");

			$order_product_id = $this->db->getLastId();

			$this->openbay->ebay->log('create() - Added order product id ' . $order_product_id);

			if ($openstock == true) {
				$this->openbay->ebay->log('create() - OpenStock enabled');
				if (!empty($txn->item->variantsku)) {
					$this->openbay->ebay->log($txn->item->variantsku);

					if ($product_id != false) {
						$sku_parts = explode(':', $txn->item->variantsku);
						$p_options = array();

						foreach ($sku_parts as $part) {
							$sql = "SELECT
									`pv`.`product_option_id`,
									`pv`.`product_option_value_id`,
									`od`.`name`,
									`ovd`.`name` as `value`,
									`o`.`option_id`,
									`o`.`type`
									FROM `" . DB_PREFIX . "product_option_value` `pv`
									LEFT JOIN `" . DB_PREFIX . "option_value` `ov` ON (`pv`.`option_value_id` = `ov`.`option_value_id`)
									LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
									LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`ov`.`option_id` = `od`.`option_id`)
									LEFT JOIN `" . DB_PREFIX . "option` `o` ON (`o`.`option_id` = `od`.`option_id`)
									WHERE `pv`.`product_option_value_id` = '" . (int)$part . "'
									AND `pv`.`product_id` = '" . (int)$product_id . "'";
							$option_qry = $this->db->query($sql);

							if (!empty($option_qry->row)) {
								$p_options[] = array(
									'product_option_id'         => $option_qry->row['product_option_id'],
									'product_option_value_id'   => $option_qry->row['product_option_value_id'],
									'name'                      => $option_qry->row['name'],
									'value'                     => $option_qry->row['value'],
									'type'                      => $option_qry->row['type'],
								);
							}
						}

						//insert into order_option table
						foreach ($p_options as $option) {
							$this->db->query("
								INSERT INTO `" . DB_PREFIX . "order_option`
								SET
								`order_id`                  = '" . (int)$order_id . "',
								`order_product_id`          = '" . (int)$order_product_id . "',
								`product_option_id`         = '" . (int)$option['product_option_id'] . "',
								`product_option_value_id`   = '" . (int)$option['product_option_value_id'] . "',
								`name`                      = '" . $this->db->escape($option['name']) . "',
								`value`                     = '" . $this->db->escape($option['value']) . "',
								`type`                      = '" . $this->db->escape($option['type']) . "'
							");
						}
					}
				} else {
					$this->openbay->ebay->log('create() - No variant sku');
				}
			}
		}

		return $order_id;
	}

	private function updateOrderWithConfirmedData($order_id, $order, $user) {
		$this->load->model('localisation/currency');
		$this->load->model('catalog/product');
		$totals_language = $this->language->load('openbay/ebay_order');

		$currency           = $this->model_localisation_currency->getCurrencyByCode($this->config->get('openbay_def_currency'));
		$address_format     = $this->model_openbay_ebay_order->getCountryAddressFormat((string)$order->address->iso2);

		//try to get zone id - this will only work if the zone name and country id exist in the DB.
		$zone_id = $this->openbay->getZoneId($order->address->state, $user['country_id']);

		if (empty($address_format)) {
			$address_format = (string)$this->config->get('openbay_default_addressformat');
		}

		//try to get the friendly name for the shipping service
		$shipping_service = $this->openbay->ebay->getShippingServiceInfo($order->shipping->service);

		if ($shipping_service != false) {
			$shipping_service_name = $shipping_service['description'];
		} else {
			$shipping_service_name = $order->shipping->service;
		}

		$this->db->query("
			UPDATE `" . DB_PREFIX . "order`
			SET
			   `customer_id`              = '" . (int)$user['id'] . "',
			   `customer_group_id`        = '" . (int)$this->config->get('openbay_def_customer_grp') . "',
			   `firstname`                = '" . $this->db->escape($user['fname']) . "',
			   `lastname`                 = '" . $this->db->escape($user['lname']) . "',
			   `email`                    = '" . $this->db->escape($order->user->email) . "',
			   `telephone`                = '" . $this->db->escape($order->address->phone) . "',
			   `shipping_firstname`       = '" . $this->db->escape($user['fname']) . "',
			   `shipping_lastname`        = '" . $this->db->escape($user['lname']) . "',
			   `shipping_address_1`       = '" . $this->db->escape($order->address->street1) . "',
			   `shipping_address_2`       = '" . $this->db->escape($order->address->street2) . "',
			   `shipping_city`            = '" . $this->db->escape($order->address->city) . "',
			   `shipping_postcode`        = '" . $this->db->escape($order->address->postcode) . "',
			   `shipping_country`         = '" . $this->db->escape($user['country']) . "',
			   `shipping_country_id`      = '" . (int)$user['country_id'] . "',
			   `shipping_zone`            = '" . $this->db->escape($order->address->state) . "',
			   `shipping_zone_id`         = '" . (int)$zone_id . "',
			   `shipping_method`          = '" . $this->db->escape($shipping_service_name) . "',
			   `shipping_address_format`  = '" . $this->db->escape($address_format) . "',
			   `payment_firstname`        = '" . $this->db->escape($user['fname']) . "',
			   `payment_lastname`         = '" . $this->db->escape($user['lname']) . "',
			   `payment_address_1`        = '" . $this->db->escape($order->address->street1) . "',
			   `payment_address_2`        = '" . $this->db->escape($order->address->street2) . "',
			   `payment_city`             = '" . $this->db->escape($order->address->city) . "',
			   `payment_postcode`         = '" . $this->db->escape($order->address->postcode) . "',
			   `payment_country`          = '" . $this->db->escape($user['country']) . "',
			   `payment_country_id`       = '" . (int)$user['country_id'] . "',
			   `payment_zone`             = '" . $this->db->escape($order->address->state) . "',
			   `payment_zone_id`          = '" . (int)$zone_id . "',
			   `comment`                  = '" . $this->db->escape($order->order->message) . "',
			   `payment_method`           = '" . $this->db->escape($order->payment->method) . "',
			   `payment_address_format`   = '" . $address_format . "',
			   `total`                    = '" . (double)$order->order->total . "',
			   `date_modified`            = NOW()
		   WHERE `order_id` = '" . $order_id . "'
		   ");

		$total_tax = 0;
		$total_net = 0;

		/* force array type */
		if (!is_array($order->txn)) {
			$order->txn = array($order->txn);
		}

		foreach ($order->txn as $txn) {
			$qty        = (int)$txn->item->qty;
			$price      = (double)$txn->item->price;

			if ($this->tax_type == 1) {
				//calculate taxes that come in from eBay
				$this->openbay->ebay->log('updateOrderWithConfirmedData() - Using tax rates from eBay');

				$total_tax   += (double)$txn->item->tax->total;
				$total_net   += $price * $qty;
			} else {
				//use the store pre-set tax-rate for everything
				$this->openbay->ebay->log('updateOrderWithConfirmedData() - Using tax rates from store');

				$item_net     = $price / $this->tax;
				$item_tax     = $price - $item_net;
				$line_net     = $item_net * $qty;
				$line_tax     = $item_tax * $qty;

				$total_tax   += number_format($line_tax, 4, ' . ', '');
				$total_net   += $line_net;
			}
		}

		if ($this->tax_type == 1) {
			$discount_net    = (double)$order->order->discount;
			$shipping_net    = (double)$order->shipping->cost;

			$tax = number_format($total_tax, 4, ' . ', '');
		} else {
			$discount_net    = (double)$order->order->discount / $this->tax;
			$discount_tax    = (double)$order->order->discount - $discount_net;
			$shipping_net    = (double)$order->shipping->cost / $this->tax;
			$shipping_tax    = (double)$order->shipping->cost - $shipping_net;

			$tax = number_format($shipping_tax + $total_tax + $discount_tax, 4, ' . ', '');
		}

		$totals = number_format((double)$total_net + (double)$shipping_net + (double)$tax + (double)$discount_net, 4, ' . ', '');

		$data = array();

		$data['totals'][0] = array(
			'code'          => 'sub_total',
			'title'         => $totals_language['text_total_sub'],
			'value'         => number_format((double)$total_net, 4, ' . ', ''),
			'sort_order'    => '1'
		);

		$data['totals'][1] = array(
			'code'          => 'shipping',
			'title'         => $totals_language['text_total_shipping'],
			'value'         => number_format((double)$shipping_net, 4, ' . ', ''),
			'sort_order'    => '3'
		);

		if ($discount_net != 0.00) {
			$data['totals'][2] = array(
				'code'          => 'coupon',
				'title'         => $totals_language['text_total_discount'],
				'value'         => number_format((double)$discount_net, 4, ' . ', ''),
				'sort_order'    => '4'
			);
		}

		$data['totals'][3] = array(
			'code'          => 'tax',
			'title'         => $totals_language['text_total_tax'],
			'value'         => number_format((double)$tax, 3, ' . ', ''),
			'sort_order'    => '5'
		);

		$data['totals'][4] = array(
			'code'          => 'total',
			'title'         => $totals_language['text_total'],
			'value'         => $totals,
			'sort_order'    => '6'
		);

		foreach ($data['totals'] as $total) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_total` SET `order_id` = '" . (int)$order_id . "', `code` = '" . $this->db->escape($total['code']) . "', `title` = '" . $this->db->escape($total['title']) . "', `value` = '" . (double)$total['value'] . "', `sort_order` = '" . (int)$total['sort_order'] . "'");
		}
	}

	private function handleUserAccount($order) {
		$name_parts     = $this->openbay->splitName((string)$order->address->name);
		$user           = array();
		$user['fname']  = $name_parts['firstname'];
		$user['lname']  = $name_parts['surname'];

		/** get the iso2 code from the data and pull out the correct country for the details. */
		if (!empty($order->address->iso2)) {
			$country_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($order->address->iso2) . "'");
		}

		if (!empty($country_qry->num_rows)) {
			$user['country']      = $country_qry->row['name'];
			$user['country_id']   = $country_qry->row['country_id'];
		} else {
			$user['country']      = (string)$order->address->iso2;
			$user['country_id']   = '';
		}

		$user['email']  = (string)$order->user->email;
		$user['id']     = $this->openbay->getUserByEmail($user['email']);

		if ($user['id'] != false) {
			$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET
								`firstname`             = '" . $this->db->escape($name_parts['firstname']) . "',
								`lastname`              = '" . $this->db->escape($name_parts['surname']) . "',
								`telephone`             = '" . $this->db->escape(str_replace(array(' ', '+', '-'), '', $order->address->phone)) . "',
								`status`                = '1'
							 WHERE `customer_id`        = '" . (int)$user['id'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer` SET
								`store_id`              = '" . (int)$this->config->get('config_store_id') . "',
								`firstname`             = '" . $this->db->escape($name_parts['firstname']) . "',
								`lastname`              = '" . $this->db->escape($name_parts['surname']) . "',
								`email`                 = '" . $this->db->escape($user['email']) . "',
								`telephone`             = '" . $this->db->escape(str_replace(array(' ', '+', '-'), '', $order->address->phone)) . "',
								`password`              = '" . $this->db->escape(md5($order->user->userid)) . "',
								`newsletter`            = '0',
								`customer_group_id`     = '" . (int)$this->config->get('openbay_def_customer_grp') . "',
								`approved`              = '1',
								`status`                = '1',
								`date_added`            = NOW()");
			$user['id'] = $this->db->getLastId();
		}

		return $user;
	}

	private function externalApplicationNotify($order_id) {
		/* This is used by the Mosaic Fullfilment solutions @ www.mosaic-fs.co.uk */
		if ($this->openbay->addonLoad('mosaic') && !$this->mosaic->isOrderAdded($order_id)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `shipping_code` = 'ebay.STD' WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
			$this->mosaic->sendOrder($order_id, 'PP', '');
			$this->openbay->ebay->log('Mosaic module has been notified about order ID: ' . $order_id);
		}

		/* send the new order notification to openbay so the other markets can update the stock */
		/* @todo */
		/* improve this to update when products are subtracted, NOT just when they are paid */
		$this->openbay->addOrder($order_id);
	}

	public function outputLog() {
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/force-download');
		header('Content-Length: ' . filesize(DIR_LOGS . "ebaylog.log"));
		header('Content-Disposition: attachment; filename="ebaylog.log"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile(DIR_LOGS . "ebaylog.log");
		exit();
	}

	public function updateLog() {
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: application/force-download');
		header('Content-Length: ' . filesize(DIR_LOGS . "update.log"));
		header('Content-Disposition: attachment; filename="update.log"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');
		readfile(DIR_LOGS . "update.log");
		exit();
	}
}