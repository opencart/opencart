<?php
class ModelExtensionOpenBayEbayOrder extends Model{
	public function importOrders($data) {
		$this->default_shipped_id         = $this->config->get('ebay_status_shipped_id');
		$this->default_paid_id            = $this->config->get('ebay_status_paid_id');
		$this->default_refunded_id        = $this->config->get('ebay_status_refunded_id');
		$this->default_pending_id         = $this->config->get('ebay_status_import_id');

		$this->default_part_refunded_id   = $this->config->get('ebay_status_partial_refund_id');
		if ($this->default_part_refunded_id == null) {
			$this->default_part_refunded_id = $this->default_paid_id;
		}

		$this->tax                        = ($this->config->get('ebay_tax') == '') ? '1' : (($this->config->get('ebay_tax') / 100) + 1);
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
		$this->load->model('extension/openbay/ebay_order');

		$this->load->language('extension/openbay/ebay_order');

		if ($this->lockExists($order->smpId) == true) {
			return;
		}

		if (!is_array($order->txn)) {
			$order->txn = array($order->txn);
		}

		$ebay_order = $this->openbay->ebay->getOrderBySmpId($order->smpId);

		if (isset($ebay_order['order_id'])) {
			$order_id = $ebay_order['order_id'];
		} else {
			$order_id = false;
		}

		$created_hours = (int)$this->config->get('ebay_created_hours');
		if ($created_hours == 0 || $created_hours == '') {
			$created_hours = 24;
		}

		$from = date("Y-m-d H:i:00", mktime(date("H")-(int)$created_hours, date("i"), date("s"), date("m"), date("d"), date("y")));
		$this->openbay->ebay->log('Accepting orders newer than: ' . $from);

		if ($order_id != false) {
			$order_loaded   = $this->model_checkout_order->getOrder($order_id);
			$order_history  = $this->getHistory($order_id);

			$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updating');

			/* check user details to see if we have now been passed the user info */
			/* if we have these details then we have the rest of the delivery info */
			if (!empty($order->address->name) && !empty($order->address->street1)) {
				$this->openbay->ebay->log('User info found');
				if ($this->hasAddress($order_id) == false) {
					$this->updateOrderWithConfirmedData($order_id, $order);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updated with user info');
				}
			} else {
				$this->openbay->ebay->log('No user info');
			}

			if ($order->shipping->status == 'Shipped' && ($order_loaded['order_status_id'] != $this->default_shipped_id) && $order->payment->status == 'Paid') {
				$this->update($order_id, $this->default_shipped_id);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Shipped');
			} elseif ($order->payment->status == 'Paid' && isset($order->payment->date) && $order->shipping->status != 'Shipped' && ($order_loaded['order_status_id'] != $this->default_paid_id)) {
				$this->update($order_id, $this->default_paid_id);
				$this->updatePaymentDetails($order_id, $order);

				if ($this->config->get('ebay_stock_allocate') == 1) {
					$this->openbay->ebay->log('Stock allocation is set to allocate stock when an order is paid');
					$this->addOrderLines($order, $order_id);

					$this->event->trigger('model/checkout/order/addOrderHistory/after', array('model/checkout/order/addOrderHistory/after', array($order_id, $this->default_paid_id)));
				}

				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Paid');
			} elseif (($order->payment->status == 'Refunded' || $order->payment->status == 'Unpaid') && ($order_loaded['order_status_id'] != $this->default_refunded_id) && in_array($this->default_paid_id, $order_history)) {
				$this->update($order_id, $this->default_refunded_id);
				$this->cancel($order_id);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Refunded');

				$this->event->trigger('model/checkout/order/addOrderHistory/after', array('model/checkout/order/addOrderHistory/after', array($order_id, $this->default_refunded_id)));
			} elseif ($order->payment->status == 'Part-Refunded' && ($order_loaded['order_status_id'] != $this->default_part_refunded_id) && in_array($this->default_paid_id, $order_history)) {
				$this->update($order_id, $this->default_part_refunded_id);
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
			 * FOLLOWING ORDER STATE TESTS REQUIRED
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
				$this->orderLinkCreate((int)$order_id, (int)$order->smpId);

				/* check user details to see if we have now been passed the user info, if we have these details then we have the rest of the delivery info */
				if (!empty($order->address->name) && !empty($order->address->street1)) {
					$this->openbay->ebay->log('User info found . ');
					if ($this->hasAddress($order_id) == false) {
						$this->updateOrderWithConfirmedData($order_id, $order);
						$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Updated with user info . ');
					}
				} else {
					$this->openbay->ebay->log('No user information.');
				}

				$default_import_message = $this->language->get('text_smp_id') . (int)$order->smpId . "\r\n";
				$default_import_message .= $this->language->get('text_buyer') . (string)$order->user->userid . "\r\n";

				//new order, set to pending initially.
				$this->confirm($order_id, $this->default_pending_id, $default_import_message);
				$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Pending');
				$order_status_id = $this->default_pending_id;

				//order has been paid
				if ($order->payment->status == 'Paid') {
					$this->update($order_id, $this->default_paid_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Paid');
					$order_status_id = $this->default_paid_id;

					if ($this->config->get('ebay_stock_allocate') == 1) {
						$this->openbay->ebay->log('Stock allocation is set to allocate stock when an order is paid');

						$this->addOrderLines($order, $order_id);
					}
				}

				//order has been refunded
				if ($order->payment->status == 'Refunded') {
					$this->update($order_id, $this->default_refunded_id);
					$this->cancel($order_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Refunded');
					$order_status_id = $this->default_refunded_id;

					$this->event->trigger('model/checkout/order/addOrderHistory/after', array('model/checkout/order/addOrderHistory/after', array($order_id, $order_status_id)));
				}

				//order is part refunded
				if ($order->payment->status == 'Part-Refunded') {
					$this->update($order_id, $this->default_part_refunded_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Part Refunded');
					$order_status_id = $this->default_part_refunded_id;
				}

				//order payment is clearing
				if ($order->payment->status == 'Clearing') {
					$this->update($order_id, $this->default_pending_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Clearing');
					$order_status_id = $this->default_pending_id;
				}

				//order is marked shipped
				if ($order->shipping->status == 'Shipped') {
					$this->update($order_id, $this->default_shipped_id);
					$this->openbay->ebay->log('Order ID: ' . $order_id . ' -> Shipped');
					$order_status_id = $this->default_shipped_id;
				}

				// Admin Alert Mail
				if ($this->config->get('ebay_confirmadmin_notify') == 1) {
					$this->openbay->newOrderAdminNotify($order_id, $order_status_id);
				}
			}

			if ($this->config->get('ebay_stock_allocate') == 0) {
				$this->openbay->ebay->log('Stock allocation is set to allocate stock when an item is bought');
				$this->addOrderLines($order, $order_id);

				$this->event->trigger('model/checkout/order/addOrderHistory/after', array('model/checkout/order/addOrderHistory/after', array($order_id, $order_status_id)));
			}
		}

		if (!empty($order->cancelled)) {
			$this->openbay->ebay->log('There are cancelled items in the order');
			$this->removeOrderLines($order->cancelled, $order_id);
		}

		//remove the lock.
		$this->lockDelete($order->smpId);
	}

	private function create($order) {
		if ($this->openbay->addonLoad('openstock')) {
			$openstock = true;
		} else {
			$openstock = false;
		}

		$this->load->model('localisation/currency');
		$this->load->model('catalog/product');

		if (isset($order->order->currency_id) && !empty($order->order->currency_id)) {
			$currency = $this->model_localisation_currency->getCurrencyByCode($order->order->currency_id);
		}

		if (empty($currency)) {
			$currency = $this->model_localisation_currency->getCurrencyByCode($this->config->get('ebay_def_currency'));
		}

		if ($this->config->get('ebay_create_date') == 1) {
			$created_date_obj = new DateTime((string)$order->order->created);
			$offset = ($this->config->get('ebay_time_offset') != '') ? (int)$this->config->get('ebay_time_offset') : (int)0;
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

				$tax = number_format((double)$txn->item->tax->item, 4, '.', '');
				$this->openbay->ebay->log('create() - Tax: ' . $tax);
			} else {
				//use the store pre-set tax-rate for everything
				$this->openbay->ebay->log('create() - Using tax rates from store');

				$price_net = $price / $this->tax;
				$this->openbay->ebay->log('create() - Net price: ' . $price_net);

				$total_net = $price_net * $qty;
				$this->openbay->ebay->log('create() - Total net price: ' . $total_net);

				$tax = number_format(($price - $price_net), 4, '.', '');
				$this->openbay->ebay->log('create() - Tax: ' . $tax);
			}

			$txn->item->name            = stripslashes($txn->item->name);
			$txn->item->varianttitle    = stripslashes($txn->item->varianttitle);
			$txn->item->sku             = stripslashes($txn->item->sku);
			$txn->item->variantsku      = stripslashes($txn->item->variantsku);

			if (isset($txn->item->varianttitle) && !empty($txn->item->varianttitle)) {
				$order_product_name = $txn->item->varianttitle;
			} else {
				if ($txn->item->sku != '') {
					$order_product_name = $txn->item->name . ' [' . $txn->item->sku . ']';
				} else {
					$order_product_name = $txn->item->name;
				}
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_product` SET
					`order_id`            = '" . (int)$order_id . "',
					`product_id`          = '" . (int)$product_id . "',
					`name`                = '" . $this->db->escape($order_product_name) . "',
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

	private function updateOrderWithConfirmedData($order_id, $order) {
		$this->load->model('localisation/currency');
		$this->load->model('catalog/product');
		$totals_language = $this->load->language('extension/openbay/ebay_order');

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

		$currency           = $this->model_localisation_currency->getCurrencyByCode($this->config->get('ebay_def_currency'));
		$address_format     = $this->getCountryAddressFormat((string)$order->address->iso2);

		//try to get zone id - this will only work if the zone name and country id exist in the DB.
		$zone_id = $this->openbay->getZoneId($order->address->state, $user['country_id']);

		if (empty($address_format)) {
			$address_format = (string)$this->config->get('ebay_default_addressformat');
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

				$total_tax   += number_format($line_tax, 4, '.', '');
				$total_net   += $line_net;
			}
		}

		if ($this->tax_type == 1) {
			$discount_net    = (double)$order->order->discount;
			$shipping_net    = (double)$order->shipping->cost;

			$tax = number_format($total_tax, 4, '.', '');
		} else {
			$discount_net    = (double)$order->order->discount / $this->tax;
			$discount_tax    = (double)$order->order->discount - $discount_net;
			$shipping_net    = (double)$order->shipping->cost / $this->tax;
			$shipping_tax    = (double)$order->shipping->cost - $shipping_net;

			$tax = number_format($shipping_tax + $total_tax + $discount_tax, 4, '.', '');
		}

		$totals = number_format((double)$total_net + (double)$shipping_net + (double)$tax + (double)$discount_net, 4, '.', '');

		$data = array();

		$data['totals'][0] = array(
			'code'          => 'sub_total',
			'title'         => $totals_language['text_total_sub'],
			'value'         => number_format((double)$total_net, 4, '.', ''),
			'sort_order'    => '1'
		);

		$data['totals'][1] = array(
			'code'          => 'shipping',
			'title'         => $totals_language['text_total_shipping'],
			'value'         => number_format((double)$shipping_net, 4, '.', ''),
			'sort_order'    => '3'
		);

		if ($discount_net != 0.00) {
			$data['totals'][2] = array(
				'code'          => 'coupon',
				'title'         => $totals_language['text_total_discount'],
				'value'         => number_format((double)$discount_net, 4, '.', ''),
				'sort_order'    => '4'
			);
		}

		$data['totals'][3] = array(
			'code'          => 'tax',
			'title'         => $totals_language['text_total_tax'],
			'value'         => number_format((double)$tax, 3, '.', ''),
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

	public function addOrderLine($data, $order_id, $created) {
		$order_line = $this->getOrderLine($data['txn_id'], $data['item_id']);

		$created_hours = (int)$this->config->get('ebay_created_hours');
		if ($created_hours == 0 || $created_hours == '') {
			$created_hours = 24;
		}
		$from = date("Y-m-d H:i:00", mktime(date("H")-$created_hours, date("i"), date("s"), date("m"), date("d"), date("y")));

		if ($order_line === false) {
			if ($created >= $from) {
				$this->openbay->ebay->log('addOrderLine() - New line');
				$product_id = $this->openbay->ebay->getProductId($data['item_id']);
				/* add to the transaction table */
				$this->db->query("
					INSERT INTO `" . DB_PREFIX . "ebay_transaction`
					SET
					`order_id`                  = '" . (int)$order_id . "',
					`txn_id`                    = '" . $this->db->escape($data['txn_id']) . "',
					`item_id`                   = '" . $this->db->escape($data['item_id']) . "',
					`product_id`                = '" . (int)$product_id . "',
					`containing_order_id`       = '" . $data['containing_order_id'] . "',
					`order_line_id`             = '" . $this->db->escape($data['order_line_id']) . "',
					`qty`                       = '" . (int)$data['qty'] . "',
					`smp_id`                    = '" . (int)$data['smp_id'] . "',
					`sku`                       = '" . $this->db->escape($data['sku']) . "',
					`created`                   = now(),
					`modified`                  = now()
				");

				if (!empty($product_id)) {
					$this->openbay->ebay->log('Link found');
					$this->modifyStock($product_id, $data['qty'], '-', $data['sku']);
				}
			} else {
				$this->openbay->ebay->log('addOrderLine() - Transaction is older than ' . $this->config->get('ebay_created_hours') . ' hours');
			}
		} else {
			$this->openbay->ebay->log('addOrderLine() - Line existed');

			if ($order_id != $order_line['order_id']) {
				$this->openbay->ebay->log('addOrderLine() - Order ID has changed from "' . $order_line['order_id'] . '" to "' . $order_id . '"');
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `order_id` = '" . (int)$order_id . "', `modified` = now() WHERE `txn_id` = '" . $this->db->escape((string)$data['txn_id']) . "' AND `item_id` = '" . $this->db->escape((string)$data['item_id']) . "' LIMIT 1");

				//if the order id has changed then remove the old order details
				$this->delete($order_line['order_id']);
			}

			if ($order_line['smp_id'] != $data['smp_id']) {
				$this->openbay->ebay->log('addOrderLine() - SMP ID for orderLine has changed from "' . $order_line['smp_id'] . '" to "' . $data['smp_id'] . '"');
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `smp_id` = '" . $data['smp_id'] . "', `modified` = now() WHERE `txn_id` = '" . $this->db->escape((string)$data['txn_id']) . "' AND `item_id` = '" . $this->db->escape((string)$data['item_id']) . "' LIMIT 1");
			}

			if ($order_line['containing_order_id'] != $data['containing_order_id']) {
				$this->openbay->ebay->log('addOrderLine() - Containing order ID for orderLine has changed from "' . $order_line['containing_order_id'] . '" to "' . $data['containing_order_id'] . '"');
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_transaction` SET `containing_order_id` = '" . $this->db->escape($data['containing_order_id']) . "', `modified` = now() WHERE `txn_id` = '" . $this->db->escape((string)$data['txn_id']) . "' AND `item_id` = '" . $this->db->escape((string)$data['item_id']) . "' LIMIT 1");
			}
		}
		$this->openbay->ebay->log('addOrderLine() - Done');
	}

	public function addOrderLines($order, $order_id) {
		$this->openbay->ebay->log('Adding order lines');

		foreach ($order->txn as $txn) {
			$this->model_extension_openbay_ebay_order->addOrderLine(array(
				'txn_id'                => (string)$txn->item->txn,
				'item_id'               => (string)$txn->item->id,
				'containing_order_id'   => (string)$order->order->id,
				'order_line_id'         => (string)$txn->item->line,
				'qty'                   => (int)$txn->item->qty,
				'smp_id'                => (string)$order->smp->id,
				'sku'                   => (string)$txn->item->variantsku
			), (int)$order_id, $order->order->created);
		}
	}

	public function getOrderLine($txn_id, $item_id) {
		$this->openbay->ebay->log('getOrderLine() - Testing for order line txn: ' . $txn_id . ', item: ' . $item_id);
		$res = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_transaction` WHERE `txn_id` = '" . $this->db->escape($txn_id) . "' AND `item_id` = '" . $this->db->escape($item_id) . "' LIMIT 1");

		if ($res->num_rows == 0) {
			return false;
		} else {
			return $res->row;
		}
	}

	public function getOrderLines($order_id) {
		$this->openbay->ebay->log('getOrderLines() - Testing for order lines id: ' . $order_id);

		$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		$lines = array();

		foreach ($result->rows as $line) {
			$lines[] = $line;
		}

		return $lines;
	}

	public function removeOrderLines($canceling) {

		foreach ($canceling as $cancel) {
			$line = $this->getOrderLine($cancel['txn'], $cancel['id']);

			if ($line === false) {
				$this->openbay->ebay->log('No line needs cancelling');
			} else {
				$this->openbay->ebay->log('Found order line to cancel');
				$this->removeOrderLine($cancel['txn'], $cancel['id'], $line);
			}
		}
	}

	private function removeOrderLine($txn_id, $item_id, $line) {
		$this->openbay->ebay->log('Removing order line, txn: ' . $txn_id . ',item id: ' . $item_id);

		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_transaction` WHERE `txn_id` = '" . $this->db->escape($txn_id) . "' AND `item_id` = '" . $this->db->escape($item_id) . "' LIMIT 1");

		if ($this->db->countAffected() > 0) {
			$this->modifyStock($line['product_id'], $line['qty'], '+', $line['sku']);
		}
	}

	public function cancel($order_id) {
		$order_lines = $this->getOrderLines($order_id);

		foreach ($order_lines as $line) {
			$this->modifyStock($line['product_id'], $line['qty'], '+', $line['sku']);
		}
	}

	public function updatePaymentDetails($order_id, $order) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = '" . $this->db->escape($order->payment->method) . "', `total` = '" . (double)$order->order->total . "', `date_modified` = NOW() WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function getHistory($order_id) {
		$this->openbay->ebay->log('Getting order history for ID: ' . $order_id);

		$query = $this->db->query("SELECT `order_status_id` FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$order_id . "'");

		$status = array();

		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$status[] = $row['order_status_id'];
			}
		}

		return $status;
	}

	public function hasAddress($order_id) {
		// check if the first name, address 1 and country are set
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "' AND `payment_firstname` != '' AND `payment_address_1` != '' AND `payment_country` != ''");

		if ($query->num_rows == 0) {
			return false;
		} else {
			return true;
		}
	}

	public function update($order_id, $order_status_id, $comment = '') {
		$order_info = $this->model_checkout_order->getOrder($order_id);

		$notify = $this->config->get('ebay_update_notify');

		if ($order_info) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");

			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            if ($notify) {
                // send the update order email using the pre-built controller class
                $args = array(
                    $order_info,
                    $order_status_id,
                    $comment,
                );

                // send the add order email using the pre-built controller class
                $update_order_email_action = new Action('mail/order/edit');

                $update_order_email_action->execute($this->registry, $args);
            }
		}
	}

	public function confirm($order_id, $order_status_id, $comment = '') {
		$order_info = $this->model_checkout_order->getOrder($order_id);
		$notify = $this->config->get('ebay_confirm_notify');

		if ($order_info && !$order_info['order_status_id']) {
			$this->db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" . (int)$order_status_id . "', date_modified = NOW() WHERE order_id = '" . (int)$order_id . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_history SET order_id = '" . (int)$order_id . "', order_status_id = '" . (int)$order_status_id . "', notify = '" . (int)$notify . "', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");

            if (isset($order_info['email']) && !empty($order_info['email']) && $notify == 1){
                $args = array(
                    $order_info,
                    $order_status_id,
                    $comment,
                    $notify,
                );

                // send the add order email using the pre-built controller class
                $add_order_email_action = new Action('mail/order/add');

                $add_order_email_action->execute($this->registry, $args);
            }
		}
	}

	private function modifyStock($product_id, $qty, $symbol = '-', $sku = '') {
		$this->openbay->ebay->log('modifyStock() - Updating stock. Product id: ' . $product_id . ' qty: ' . $qty . ', symbol: ' . $symbol . ' sku: ' . $sku);

		$item_id = $this->openbay->ebay->getEbayItemId($product_id);

		if ($this->openbay->addonLoad('openstock') && !empty($sku)) {
			$this->db->query("UPDATE `" . DB_PREFIX . "product_option_variant` SET `stock` = (`stock` " . $this->db->escape((string)$symbol) . " " . (int)$qty . ") WHERE `sku` = '" . (string)$sku . "' AND `product_id` = '" . (int)$product_id . "' AND `subtract` = '1'");

			$stock = $this->openbay->ebay->getProductStockLevel($product_id, $sku);

			$this->openbay->ebay->log('modifyStock() /variant  - Stock is now set to: ' . $stock['quantity']);

			$this->openbay->ebay->putStockUpdate($item_id, $stock['quantity'], $sku);
		} else {
			$this->db->query("UPDATE `" . DB_PREFIX . "product` SET `quantity` = (`quantity` " . $this->db->escape((string)$symbol) . " " . (int)$qty . ") WHERE `product_id` = '" . (int)$product_id . "' AND `subtract` = '1'");

			$stock = $this->openbay->ebay->getProductStockLevel($product_id);

			$this->openbay->ebay->log('modifyStock() - Stock is now set to: ' . $stock['quantity']);

			//send back stock update to eBay incase of a reserve product level
			$this->openbay->ebay->putStockUpdate($item_id, $stock['quantity']);
		}
	}

	public function getCountryAddressFormat($iso2) {
		$this->openbay->ebay->log('getCountryAddressFormat() - Getting country from ISO2: ' . $iso2);

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($iso2) . "' LIMIT 1");

		if (!isset($query->row['address_format']) || $query->row['address_format'] == '') {
			$this->openbay->ebay->log('getCountryAddressFormat() - No country found, default');
			return false;
		} else {
			$this->openbay->ebay->log('getCountryAddressFormat() - found country: ' . $query->row['address_format']);
			return $query->row['address_format'];
		}
	}

	public function orderLinkCreate($order_id, $smp_id) {
		$this->openbay->ebay->log('orderLinkCreate() - order_id: ' . $order_id . ', smp_id: ' . $smp_id);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_order` SET `order_id` = '" . (int)$order_id . "', `smp_id` = '" . (int)$smp_id . "', `parent_ebay_order_id` = 0");

		return $this->db->getLastId();
	}

	public function delete($order_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_option` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_history` WHERE `order_id` = '" . (int)$order_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . (int)$order_id . "'");
	}

	public function lockAdd($smp_id) {
		$this->openbay->ebay->log('lockAdd() - Added lock, smp_id: ' . $smp_id);
		$this->db->query("INSERT INTO`" . DB_PREFIX . "ebay_order_lock` SET `smp_id` = '" . (int)$smp_id . "'");
	}

	public function lockDelete($smp_id) {
		$this->openbay->ebay->log('lockDelete() - Delete lock, smp_id: ' . $smp_id);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "ebay_order_lock` WHERE `smp_id` = '" . (int)$smp_id . "'");
	}

	public function lockExists($smp_id) {
		$this->openbay->ebay->log('lockExists() - Check lock, smp_id: ' . (int)$smp_id);
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_order_lock` WHERE `smp_id` = '" . (int)$smp_id . "' LIMIT 1");

		if ($query->num_rows > 0) {
			$this->openbay->ebay->log('lockExists() - Lock found, stopping order . ');
			return true;
		} else {
			$this->lockAdd($smp_id);
			return false;
		}
	}

	public function addOrderHistory($order_id) {
		$this->openbay->ebay->log('addOrderHistory() - Order id:' . $order_id . ' passed');
		if (!$this->openbay->ebay->getOrder($order_id)) {
			$order_products = $this->openbay->getOrderProducts($order_id);

			foreach($order_products as $order_product) {
				$product = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$order_product['product_id'] . "' LIMIT 1")->row;

				if ($this->openbay->addonLoad('openstock') && (isset($product['has_option']) && $product['has_option'] == 1)) {
					$order_product_variant = $this->openbay->getOrderProductVariant($order_id, $order_product['product_id'], $order_product['order_product_id']);

					if (isset($order_product_variant['sku']) && $order_product_variant['sku'] != '') {
						$this->openbay->ebay->ebaySaleStockReduce((int)$order_product['product_id'], (string)$order_product_variant['sku']);
					}
				} else {
					$this->openbay->ebay->ebaySaleStockReduce($order_product['product_id']);
				}
			}
		}
	}
}
