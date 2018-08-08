<?php
final class Openbay {
	private $registry;
	private $installed_modules = array();
	public $installed_markets = array();
	private $logging = 1;

	public function __construct($registry) {
		// OpenBay Pro
		$this->registry = $registry;

		if ($this->db != null) {
			$this->getInstalled();

			foreach ($this->installed_markets as $market) {
				$class = '\openbay\\'. ucfirst($market);

				$this->{$market} = new $class($registry);
			}
		}

		$this->logger = new \Log('openbay.log');
	}

	public function __get($name) {
		return $this->registry->get($name);
	}

	public function log($data, $write = true) {
		if ($this->logging == 1) {
			if (function_exists('getmypid')) {
				$process_id = getmypid();
				$data = $process_id . ' - ' . $data;
			}

			if ($write == true) {
				$this->logger->write($data);
			}
		}
	}

	public function encrypt($value, $key, $iv, $json = true) {
		if ($json == true) {
		    $value = json_encode($value);
        }

	    return strtr(base64_encode(openssl_encrypt($value, 'aes-128-cbc', hash('sha256', hex2bin($key), true), 0, hex2bin($iv))), '+/=', '-_,');
	}

	public function decrypt($value, $key, $iv, $json = true) {
		$response = trim(openssl_decrypt(base64_decode(strtr($value, '-_,', '+/=')), 'aes-128-cbc', hash('sha256', hex2bin($key), true), 0, hex2bin($iv)));

		if ($json == true) {
		    $response =  json_decode($response, true);
        }

        return $response;
	}

	private function getInstalled() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE `type` = 'openbay'");

		foreach ($query->rows as $result) {
			$this->installed_markets[] = $result['code'];
		}
	}

	public function getInstalledMarkets() {
		return $this->installed_markets;
	}

	public function putStockUpdateBulk($product_id_array, $end_inactive = false) {
		/**
		 * putStockUpdateBulk
		 *
		 * Takes an array of product id's where stock has been modified
		 *
		 * @param $product_id_array
		 */

		foreach ($this->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1 || $this->config->get('openbay_' .$market . '_status') == 1) {
				$this->{$market}->putStockUpdateBulk($product_id_array, $end_inactive);
			}
		}
	}

	public function testDbColumn($table, $column) {
		$res = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . $table . "` LIKE '" . $column . "'");
		if($res->num_rows != 0) {
			return true;
		} else {
			return false;
		}
	}

	public function testDbTable($table) {
		$res = $this->db->query("SELECT `table_name` AS `c` FROM `information_schema`.`tables` WHERE `table_schema` = DATABASE()");

		$tables = array();

		foreach($res->rows as $row) {
			$tables[] = $row['c'];
		}

		if(in_array($table, $tables)) {
			return true;
		} else {
			return false;
		}
	}

	public function splitName($name) {
		$name = explode(' ', $name);
		$fname = $name[0];
		unset($name[0]);
		$lname = implode(' ', $name);

		return array(
			'firstname' => $fname,
			'surname'   => $lname
		);
	}

	public function getTaxRates($tax_class_id) {
		$tax_rates = array();

		$tax_query = $this->db->query("SELECT
					tr2.tax_rate_id,
					tr2.name,
					tr2.rate,
					tr2.type,
					tr1.priority
				FROM " . DB_PREFIX . "tax_rule tr1
				LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id)
				INNER JOIN " . DB_PREFIX . "tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id)
				LEFT JOIN " . DB_PREFIX . "zone_to_geo_zone z2gz ON (tr2.geo_zone_id = z2gz.geo_zone_id)
				LEFT JOIN " . DB_PREFIX . "geo_zone gz ON (tr2.geo_zone_id = gz.geo_zone_id)
				WHERE tr1.tax_class_id = '" . (int)$tax_class_id . "'
				AND tr1.based = 'shipping'
				AND tr2cg.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "'
				AND z2gz.country_id = '" . (int)$this->config->get('config_country_id') . "'
				AND (z2gz.zone_id = '0' OR z2gz.zone_id = '" . (int)$this->config->get('config_zone_id') . "')
				ORDER BY tr1.priority ASC");

		foreach ($tax_query->rows as $result) {
			$tax_rates[$result['tax_rate_id']] = array(
				'tax_rate_id' => $result['tax_rate_id'],
				'name'        => $result['name'],
				'rate'        => $result['rate'],
				'type'        => $result['type'],
				'priority'    => $result['priority']
			);
		}

		return $tax_rates;
	}

	public function getTaxRate($class_id) {
		$rates = $this->getTaxRates($class_id);
		$percentage = 0.00;
		foreach($rates as $rate) {
			if($rate['type'] == 'P') {
				$percentage += $rate['rate'];
			}
		}

		return $percentage;
	}

	public function getZoneId($name, $country_id) {
		$query = $this->db->query("SELECT `zone_id` FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '" . (int)$country_id . "' AND status = '1' AND `name` = '" . $this->db->escape($name) . "'");

		if($query->num_rows > 0) {
			return $query->row['zone_id'];
		} else {
			return 0;
		}
	}

    public function newOrderAdminNotify($order_id, $order_status_id) {
        $order_info = $this->model_checkout_order->getOrder($order_id);

        if ($order_info && !$order_info['order_status_id'] && $order_status_id && in_array('order', (array)$this->config->get('config_mail_alert'))) {
            $this->load->language('mail/order_alert');

            // HTML Mail
            $data['text_received'] = $this->language->get('text_received');
            $data['text_order_id'] = $this->language->get('text_order_id');
            $data['text_date_added'] = $this->language->get('text_date_added');
            $data['text_order_status'] = $this->language->get('text_order_status');
            $data['text_product'] = $this->language->get('text_product');
            $data['text_total'] = $this->language->get('text_total');
            $data['text_comment'] = $this->language->get('text_comment');

            $data['order_id'] = $order_info['order_id'];
            $data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

            $order_status_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$order_status_id . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");

            if ($order_status_query->num_rows) {
                $data['order_status'] = $order_status_query->row['name'];
            } else {
                $data['order_status'] = '';
            }

            $data['store_url'] = HTTP_SERVER;
            $data['store'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');

            $this->load->model('tool/image');

            if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
                $data['logo'] = $this->model_tool_image->resize($this->config->get('config_logo'), $this->config->get('theme_default_image_location_width'), $this->config->get('theme_default_image_cart_height'));
            } else {
                $data['logo'] = '';
            }

            $this->load->model('tool/upload');

            $data['products'] = array();

            $order_products = $this->model_checkout_order->getOrderProducts($order_id);

            foreach ($order_products as $order_product) {
                $option_data = array();

                $order_options = $this->model_checkout_order->getOrderOptions($order_info['order_id'], $order_product['order_product_id']);

                foreach ($order_options as $order_option) {
                    if ($order_option['type'] != 'file') {
                        $value = $order_option['value'];
                    } else {
                        $upload_info = $this->model_tool_upload->getUploadByCode($order_option['value']);

                        if ($upload_info) {
                            $value = $upload_info['name'];
                        } else {
                            $value = '';
                        }
                    }

                    $option_data[] = array(
                        'name'  => $order_option['name'],
                        'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
                    );
                }

                $data['products'][] = array(
                    'name'     => $order_product['name'],
                    'model'    => $order_product['model'],
                    'quantity' => $order_product['quantity'],
                    'option'   => $option_data,
                    'total'    => html_entity_decode($this->currency->format($order_product['total'] + ($this->config->get('config_tax') ? ($order_product['tax'] * $order_product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
                );
            }

            $data['vouchers'] = array();

            $order_vouchers = $this->model_checkout_order->getOrderVouchers($order_id);

            foreach ($order_vouchers as $order_voucher) {
                $data['vouchers'][] = array(
                    'description' => $order_voucher['description'],
                    'amount'      => html_entity_decode($this->currency->format($order_voucher['amount'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
                );
            }

            $data['totals'] = array();

            $order_totals = $this->model_checkout_order->getOrderTotals($order_id);

            foreach ($order_totals as $order_total) {
                $data['totals'][] = array(
                    'title' => $order_total['title'],
                    'value' => html_entity_decode($this->currency->format($order_total['value'], $order_info['currency_code'], $order_info['currency_value']), ENT_NOQUOTES, 'UTF-8')
                );
            }

            $data['comment'] = strip_tags($order_info['comment']);

            $mail = new Mail($this->config->get('config_mail_engine'));
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($this->config->get('config_email'));
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender(html_entity_decode($order_info['store_name'], ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), $this->config->get('config_name'), $order_info['order_id']), ENT_QUOTES, 'UTF-8'));
            $mail->setText($this->load->view('mail/order_alert', $data));
            $mail->send();

            // Send to additional alert emails
            $emails = explode(',', $this->config->get('config_mail_alert_email'));

            foreach ($emails as $email) {
                if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mail->setTo($email);
                    $mail->send();
                }
            }
        }
    }

	public function orderDelete($order_id) {
		/**
		 * Called when an order is deleted in the admin
		 * Use it to add stock back to the marketplaces
		 */
		foreach ($this->installed_markets as $market) {
			if ($this->config->get($market . '_status') == 1 || $this->config->get('openbay_' .$market . '_status') == 1) {
				$this->{$market}->orderDelete($order_id);
			}
		}
	}

	public function getProductModelNumber($product_id, $sku = null) {
		if($sku != null) {
			$qry = $this->db->query("SELECT `sku` FROM `" . DB_PREFIX . "product_option_variant` WHERE `product_id` = '" . (int)$product_id . "' AND `sku` = '" . $this->db->escape($sku) . "'");

			if($qry->num_rows > 0) {
				return $qry->row['sku'];
			} else {
				return false;
			}
		} else {
			$qry = $this->db->query("SELECT `model` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");

			if($qry->num_rows > 0) {
				return $qry->row['model'];
			} else {
				return false;
			}
		}
	}

	public function getProductTaxClassId($product_id) {
		$qry = $this->db->query("SELECT `tax_class_id` FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return $qry->row['tax_class_id'];
		} else {
			return false;
		}
	}

	public function addonLoad($addon) {
		$addon = strtolower((string)$addon);

		if (empty($this->installed_modules)) {
			$this->installed_modules = array();

			$rows = $this->db->query("SELECT `code` FROM " . DB_PREFIX . "extension")->rows;

			foreach ($rows as $row) {
				$this->installed_modules[] = strtolower($row['code']);
			}
		}

		return in_array($addon, $this->installed_modules);
	}

	public function getUserByEmail($email) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE `email` = '" . $this->db->escape($email) . "'");

		if($qry->num_rows){
			return $qry->row['customer_id'];
		} else {
			return false;
		}
	}

	public function getProductOptions($product_id) {
		$product_option_data = array();

		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id) WHERE po.product_id = '" . (int)$product_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY o.sort_order");

		foreach ($product_option_query->rows as $product_option) {
			if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY ov.sort_order");

				foreach ($product_option_value_query->rows as $product_option_value) {
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id'         => $product_option_value['option_value_id'],
						'name'                    => $product_option_value['name'],
						'image'                   => $product_option_value['image'],
						'quantity'                => $product_option_value['quantity'],
						'subtract'                => $product_option_value['subtract'],
						'price'                   => $product_option_value['price'],
						'price_prefix'            => $product_option_value['price_prefix'],
						'points'                  => $product_option_value['points'],
						'points_prefix'           => $product_option_value['points_prefix'],
						'weight'                  => $product_option_value['weight'],
						'weight_prefix'           => $product_option_value['weight_prefix']
					);
				}

				$product_option_data[] = array(
					'product_option_id'    => $product_option['product_option_id'],
					'option_id'            => $product_option['option_id'],
					'name'                 => $product_option['name'],
					'type'                 => $product_option['type'],
					'product_option_value' => $product_option_value_data,
					'required'             => $product_option['required']
				);
			} else {
				$product_option_data[] = array(
					'product_option_id' => $product_option['product_option_id'],
					'option_id'         => $product_option['option_id'],
					'name'              => $product_option['name'],
					'type'              => $product_option['type'],
					'option_value'      => $product_option['value'],
					'required'          => $product_option['required']
				);
			}
		}

		return $product_option_data;
	}

	public function getOrderProducts($order_id) {
		$order_products = $this->db->query("SELECT `product_id`, `order_product_id` FROM `" . DB_PREFIX . "order_product` WHERE `order_id` = '" . (int)$order_id . "'");

		if($order_products->num_rows > 0) {
			return $order_products->rows;
		} else {
			return array();
		}
	}

	public function getOrderProductVariant($order_id, $product_id, $order_product_id) {
		$this->load->model('extension/module/openstock');

		$order_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_option WHERE order_id = '" . (int)$order_id . "' AND order_product_id = '" . (int)$order_product_id . "'");

		if ($order_option_query->num_rows) {
			$options = array();

			foreach ($order_option_query->rows as $option) {
				$options[] = $option['product_option_value_id'];
			}

			return $this->model_extension_module_openstock->getVariantByOptionValues($options, $product_id);
		}
	}
}
