<?php
class ModelOpenbayEbay extends Model{
	public function install() {
		$value                                  = array();
		$value["ebay_token"]              = '';
		$value["ebay_secret"]             = '';
		$value["ebay_string1"]            = '';
		$value["ebay_string2"]            = '';
		$value["ebay_enditems"]           = '0';
		$value["ebay_logging"]            = '1';
		$value["ebay_payment_instruction"]     = '';
		$value["entry_payment_paypal_address"]  = '';
		$value["field_payment_paypal"]          = '0';
		$value["field_payment_cheque"]          = '0';
		$value["field_payment_card"]            = '0';
		$value["tax"]                           = '0';
		$value["postcode"]                      = '';
		$value["dispatch_time"]                 = '1';
		$value["ebay_status_import_id"]            = '1';
		$value["ebay_status_shipped_id"]           = '3';
		$value["ebay_status_paid_id"]              = '2';
		$value["ebay_status_cancelled_id"]         = '7';
		$value["ebay_status_refunded_id"]          = '11';
		$value["ebay_def_currency"]          = 'GBP';
		$value["openbay_admin_directory"]       = 'admin';
		$value["ebay_stock_allocate"]     = '0';
		$value["ebay_update_notify"]      = '1';
		$value["ebay_confirm_notify"]     = '1';
		$value["ebay_confirmadmin_notify"]= '1';
		$value["ebay_created_hours"]      = '48';
		$value["ebay_create_date"]        = '0';
		$value["ebay_itm_link"]      = 'http://www.ebay.com/itm/';
		$value["ebay_relistitems"]        = 0;
		$value["ebay_time_offset"]        = 0;
		$value["ebay_default_addressformat"] = '{firstname} {lastname}
{company}
{address_1}
{address_2}
{city}
{zone}
{postcode}
{country}';

		$this->model_setting_setting->editSetting('ebay', $value);

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_category` (
					  `ebay_category_id` int(11) NOT NULL AUTO_INCREMENT,
					  `CategoryID` int(11) NOT NULL,
					  `CategoryParentID` int(11) NOT NULL,
					  `CategoryLevel` smallint(6) NOT NULL,
					  `CategoryName` char(100) NOT NULL,
					  `BestOfferEnabled` tinyint(1) NOT NULL,
					  `AutoPayEnabled` tinyint(1) NOT NULL,
					  PRIMARY KEY (`ebay_category_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_category_history` (
					  `ebay_category_history_id` int(11) NOT NULL AUTO_INCREMENT,
					  `CategoryID` int(11) NOT NULL,
					  `breadcrumb` varchar(255) NOT NULL,
					  `used` int(6) NOT NULL,
					  PRIMARY KEY (`ebay_category_history_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_listing` (
					  `ebay_listing_id` int(11) NOT NULL AUTO_INCREMENT,
					  `ebay_item_id` char(100) NOT NULL,
					  `product_id` int(11) NOT NULL,
					  `variant` int(11) NOT NULL,
					  `status` SMALLINT(3) NOT NULL DEFAULT '1',
					  PRIMARY KEY (`ebay_listing_id`),
  						KEY `product_id` (`product_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");
		;
		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_listing_pending` (
					  `ebay_listing_pending_id` int(11) NOT NULL AUTO_INCREMENT,
					  `ebay_item_id` char(25) NOT NULL,
					  `product_id` int(11) NOT NULL,
					  `key` char(50) NOT NULL,
					  `variant` int(11) NOT NULL,
					  PRIMARY KEY (`ebay_listing_pending_id`),
  						KEY `product_id` (`product_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_shipping` (
					  `ebay_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
					  `description` varchar(100) NOT NULL,
					  `InternationalService` tinyint(4) NOT NULL,
					  `ShippingService` varchar(100) NOT NULL,
					  `ShippingServiceID` int(11) NOT NULL,
					  `ServiceType` varchar(100) NOT NULL,
					  `ValidForSellingFlow` tinyint(4) NOT NULL,
					  `ShippingCategory` varchar(100) NOT NULL,
					  `ShippingTimeMin` int(11) NOT NULL,
					  `ShippingTimeMax` int(11) NOT NULL,
					  PRIMARY KEY (`ebay_shipping_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_shipping_location` (
					  `ebay_shipping_id` int(11) NOT NULL AUTO_INCREMENT,
					  `description` varchar(100) NOT NULL,
					  `detail_version` varchar(100) NOT NULL,
					  `shipping_location` varchar(100) NOT NULL,
					  `update_time` varchar(100) NOT NULL,
					  PRIMARY KEY (`ebay_shipping_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_payment_method` (
					  `ebay_payment_method_id` int(11) NOT NULL AUTO_INCREMENT,
					  `ebay_name` char(50) NOT NULL,
					  `local_name` char(50) NOT NULL,
					  PRIMARY KEY (`ebay_payment_method_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_transaction` (
						`ebay_transaction_id` int(11) NOT NULL AUTO_INCREMENT,
						`order_id` int(11) NOT NULL,
						`product_id` int(11) NOT NULL,
						`sku` varchar(100) NOT NULL,
						`txn_id` varchar(100) NOT NULL,
						`item_id` varchar(100) NOT NULL,
						`containing_order_id` varchar(100) NOT NULL,
						`order_line_id` varchar(100) NOT NULL,
						`qty` int(11) NOT NULL,
						`smp_id` int(11) NOT NULL,
						`created` DATETIME NOT NULL,
						`modified` DATETIME NOT NULL,
					PRIMARY KEY (`ebay_transaction_id`),
  						KEY `product_id` (`product_id`),
  						KEY `order_id` (`order_id`),
  						KEY `smp_id` (`smp_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_order` (
					  `ebay_order_id` int(11) NOT NULL AUTO_INCREMENT,
					  `parent_ebay_order_id` int(11) NOT NULL,
					  `order_id` int(11) NOT NULL,
					  `smp_id` int(11) NOT NULL,
					  `tracking_no` varchar(100) NOT NULL,
					  `carrier_id` varchar(100) NOT NULL,
					  PRIMARY KEY (`ebay_order_id`),
  						KEY `order_id` (`order_id`),
  						KEY `smp_id` (`smp_id`),
  						KEY `parent_ebay_order_id` (`parent_ebay_order_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
					CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_profile` (
						`ebay_profile_id` int(11) NOT NULL AUTO_INCREMENT,
						`name` varchar(100) NOT NULL,
						`description` text NOT NULL,
						`type` int(11) NOT NULL,
						`default` TINYINT(1) NOT NULL,
						`data` text NOT NULL,
						PRIMARY KEY (`ebay_profile_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_setting_option` (
					`ebay_setting_option_id` INT(11) NOT NULL AUTO_INCREMENT,
					`key` VARCHAR(100) NOT NULL,
					`last_updated` DATETIME NOT NULL,
					`data` TEXT NOT NULL,
					PRIMARY KEY (`ebay_setting_option_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_image_import` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `image_original` text NOT NULL,
				  `image_new` text NOT NULL,
				  `name` text NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `imgcount` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_shipping_location_exclude` (
					`ebay_shipping_exclude_id` int(11) NOT NULL AUTO_INCREMENT,
					`description` varchar(100) NOT NULL,
					`location` varchar(100) NOT NULL,
					`region` varchar(100) NOT NULL,
					PRIMARY KEY (`ebay_shipping_exclude_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_stock_reserve` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `product_id` int(11) NOT NULL,
				  `variant_id` varchar(100) NOT NULL,
				  `item_id` varchar(100) NOT NULL,
				  `reserve` int(11) NOT NULL,
				  PRIMARY KEY (`id`),
  					KEY `product_id` (`product_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_order_lock` (
				  `smp_id` int(11) NOT NULL,
				  PRIMARY KEY (`smp_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		$this->db->query("
				CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_template` (
				  `template_id` INT(11) NOT NULL AUTO_INCREMENT,
				  `name` VARCHAR(100) NOT NULL,
				  `html` MEDIUMTEXT NOT NULL,
				  PRIMARY KEY (`template_id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");

		// register the event triggers
		$this->model_extension_event->addEvent('openbaypro_ebay', 'post.order.add', 'openbay/ebay/eventAddOrder');
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_category`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_category_history`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_listing`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_listing_pending`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_shipping`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_shipping_location`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_payment_method`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_transaction`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "ebay_profile`;");

		// remove the event triggers
		$this->model_extension_event->deleteEvent('openbaypro_ebay');
	}

	public function patch($manual = true) {
		$this->load->model('setting/setting');

		$this->openbay->ebay->updateSettings();

		return true;
	}

	public function totalLinked() {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total
				FROM `" . DB_PREFIX . "ebay_listing` `el`
				LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`el`.`product_id` = `p`.`product_id`)
				LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`)
				WHERE `el`.`status` = '1'
				AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function loadLinked($limit = 100, $page = 1) {
		$this->load->model('tool/image');

		$start = $limit * ($page - 1);

		$has_option = '';
		if ($this->openbay->addonLoad('openstock') ) {
			$this->load->model('openstock/openstock');
			$has_option = '`p`.`has_option`, ';
		}

		$sql = "
		SELECT
			" . $has_option . "
			`el`.`ebay_item_id`,
			`p`.`product_id`,
			`p`.`sku`,
			`p`.`model`,
			`p`.`quantity`,
			`pd`.`name`,
			`esr`.`reserve`
		FROM `" . DB_PREFIX . "ebay_listing` `el`
		LEFT JOIN `" . DB_PREFIX . "product` `p` ON (`el`.`product_id` = `p`.`product_id`)
		LEFT JOIN `" . DB_PREFIX . "product_description` `pd` ON (`p`.`product_id` = `pd`.`product_id`)
		LEFT JOIN `" . DB_PREFIX . "ebay_stock_reserve` `esr` ON (`esr`.`product_id` = `p`.`product_id`)
		WHERE `el`.`status` = '1'
		AND `pd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		$sql .= " LIMIT " . (int)$start . "," . (int)$limit;

		$qry = $this->db->query($sql);

		$data = array();
		if ($qry->num_rows) {
			foreach ($qry->rows as $row) {
				$data[$row['ebay_item_id']] = array(
					'product_id'    => $row['product_id'],
					'sku'           => $row['sku'],
					'model'         => $row['model'],
					'qty'           => $row['quantity'],
					'name'          => $row['name'],
					'link_edit'     => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $row['product_id'], 'SSL'),
					'link_ebay'     => $this->config->get('ebay_itm_link') . $row['ebay_item_id'],
					'reserve'       => (int)$row['reserve'],
				);

				$data[$row['ebay_item_id']]['options'] = 0;

				if ((isset($row['has_option']) && $row['has_option'] == 1) && $this->openbay->addonLoad('openstock')) {
					$data[$row['ebay_item_id']]['options'] = $this->model_openstock_openstock->getProductOptionStocks((int)$row['product_id']);
				}

				//get the allocated stock - items that have been bought but not assigned to an order
				if ($this->config->get('ebay_stock_allocate') == 0) {
					$data[$row['ebay_item_id']]['allocated'] = $this->openbay->ebay->getAllocatedStock($row['product_id']);
				} else {
					$data[$row['ebay_item_id']]['allocated'] = 0;
				}
			}
		}

		return $data;
	}

	public function loadLinkedStatus($item_ids) {
		$this->openbay->ebay->log('loadLinkedStatus() - Get item status from ebay for multiple IDs');
		return $this->openbay->ebay->call('item/getItemsById/', array('item_ids' => $item_ids));
	}

	public function loadUnlinked($limit = 200, $page = 1, $filter = array()) {
		$unlinked = array();
		$current = 1;
		$stop_flag = 0;

		while(count($unlinked) < 5) {
			if ($current > 5) {
				$stop_flag = 1;
				break;
			} else {
				$current++;
			}

			$this->openbay->ebay->log('Checking unlinked page: ' . $page);

			$response = $this->openbay->ebay->getEbayItemList($limit, $page, $filter);

			if ($this->openbay->ebay->lasterror == true) {
				break;
			}

			foreach ($response['items'] as $item_id => $item) {
				if ($this->openbay->ebay->getProductId($item_id, 1) == false) {
					$unlinked[$item_id] = $item;
				}
			}

			$this->openbay->ebay->log('Unlinked count: ' . count($unlinked));

			if ($response['max_page'] == $page || count($unlinked) >= 5) {
				break;
			} else {
				$page++;
			}
		}

		return array(
			'items' => $unlinked,
			'break' => $stop_flag,
			'next_page' => $response['page']+1,
			'max_page' => $response['max_page']
		);
	}

	public function loadItemLinks() {
		$local      = $this->openbay->ebay->getLiveListingArray();
		$response   = $this->openbay->ebay->getEbayActiveListings();

		$data = array(
			'unlinked'  => array(),
			'linked'    => array()
		);

		if (!empty($response)) {
			foreach ($response as $key => $value) {
				if (!in_array($key, $local)) {
					$data['unlinked'][$key] = $value;
				} else {
					$data['linked'][$key] = $value;
				}
			}
		}

		return $data;
	}

	public function saveItemLink($data) {
		$this->openbay->ebay->log('Creating item link.');
		$this->openbay->ebay->createLink($data['pid'], $data['itemId'], $data['variants']);

		if (($data['qty'] != $data['ebayqty']) || $data['variants'] == 1) {
			$this->load->model('catalog/product');
			$this->openbay->ebay->log('Updating eBay with new qty');
			$this->openbay->ebay->productUpdateListen($data['pid'], $this->model_catalog_product->getProduct($data['pid']));
		} else {
			$this->openbay->ebay->log('Qty on eBay is the same as our stock, no update needed');
			return array('msg' => 'ok', 'error' => false);
		}
	}

	public function getSellerStoreCategories() {
		$qry = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "ebay_store_category'");

		if ($qry->num_rows) {
			$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_store_category` WHERE `parent_id` = '0' ORDER BY `CategoryName` ASC");

			if ($qry->num_rows) {
				$cats = array();

				foreach ($qry->rows as $row) {
					$lev1 = $row['CategoryName'];
					$qry2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_store_category` WHERE `parent_id` = '" . $row['ebay_store_category_id'] . "' ORDER BY `CategoryName` ASC");

					if ($qry2->num_rows) {
						foreach ($qry2->rows as $row2) {
							$qry3 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_store_category` WHERE `parent_id` = '" . $row2['ebay_store_category_id'] . "' ORDER BY `CategoryName` ASC");

							if ($qry3->num_rows) {
								foreach ($qry3->rows as $row3) {
									$cats[$row3['CategoryID']] = $lev1  . ' > ' . $row2['CategoryName']  . ' > ' . $row3['CategoryName'];
								}
							} else {
								$cats[$row2['CategoryID']] = $lev1  . ' > ' . $row2['CategoryName'];
							}
						}
					} else {
						$cats[$row['CategoryID']] = $lev1;
					}
				}

				return $cats;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function getCategory($parent) {
		$this->load->language('openbay/ebay_new');

		$json = array();

		if (empty($parent)) {
			$cat_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_category` WHERE `CategoryID` = `CategoryParentID`");
		} else {
			$cat_qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_category` WHERE `CategoryParentID` = '" . $parent . "'");
		}

		if ($cat_qry->num_rows) {
			$json['cats'] = array();
			foreach ($cat_qry->rows as $row) {
				$json['cats'][] = $row;
			}
			$json['items'] = $cat_qry->num_rows;

		} else {
			if (empty($parent)) {
				$json['error'] = $this->language->get('error_category_sync');
			}

			$json['items'] = null;
		}

		return $json;
	}

	public function getSuggestedCategories($qry) {
		$response = array(
			'data' => $this->openbay->ebay->call('listing/getSuggestedCategories/', array('qry' => $qry)),
			'error' => $this->openbay->ebay->lasterror,
			'msg' => $this->openbay->ebay->lastmsg
		);

		return $response;
	}

	public function getShippingService($loc, $type) {
		$json   = array();
		$sql    = "SELECT * FROM `" . DB_PREFIX . "ebay_shipping` WHERE `InternationalService` = '" . $loc . "' AND `ValidForSellingFlow` = '1' AND `ServiceType` LIKE '%" . $this->db->escape($type) . "%'";
		$qry    = $this->db->query($sql);

		if ($qry->num_rows) {
			$json['service'] = array();
			foreach ($qry->rows as $row) {
				$json['service'][$row['ShippingService']] = $row;
			}
		}

		return $json;
	}

	public function getShippingLocations() {
		$sql = "SELECT * FROM `" . DB_PREFIX . "ebay_shipping_location` WHERE `shipping_location` != 'None' AND `shipping_location` != 'Worldwide'";
		$qry = $this->db->query($sql);

		if ($qry->num_rows) {
			$json = array();
			foreach ($qry->rows as $row) {
				$json[] = $row;
			}
			return $json;
		} else {
			return false;
		}
	}

	public function getShippingServiceName($loc, $id) {
		$qry = $this->db->query("SELECT `description` FROM `" . DB_PREFIX . "ebay_shipping` WHERE `ShippingService` = '" . $this->db->escape($id) . "'");
		return $qry->row['description'];
	}

	public function getEbayCategorySpecifics($category_id) {
		$response['data']   = $this->openbay->ebay->call('listing/getEbayCategorySpecifics/', array('id' => $category_id));
		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;
		return $response;
	}

	public function getCategoryFeatures($category_id) {
		$response['data']   = $this->openbay->ebay->call('listing/getCategoryFeatures/', array('id' => $category_id));
		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;
		return $response;
	}

	public function getSellerSummary() {
		$response['data']   = $this->openbay->ebay->call('account/getSellerSummary/');
		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;

		return $response;
	}

	public function getPaymentTypes() {
		$cat_payment    = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_payment_method`");
		$payments       = array();

		foreach ($cat_payment->rows as $row) {
			$payments[] = $row;
		}

		return $payments;
	}

	public function getPopularCategories() {
		$res    = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_category_history` ORDER BY `used` DESC LIMIT 5");
		$cats   = array();

		foreach ($res->rows as $row) {
			$cats[] = $row;
		}

		return $cats;
	}

	private function getCategoryStructure($id) {
		$res = $this->db->query("SELECT * FROM `" . DB_PREFIX . "ebay_category` WHERE `CategoryID` = '" . $this->db->escape($id) . "' LIMIT 1");
		return $res->row;
	}

	public function ebayVerifyAddItem($data, $options) {
		if ($options == 'yes') {
			$response['data'] = $this->openbay->ebay->call('listing/verifyFixedPrice/', $data);
		} else {
			$response['data'] = $this->openbay->ebay->call('listing/ebayVerifyAddItem/', $data);
		}

		$response['error']  = $this->openbay->ebay->lasterror;
		$response['msg']    = $this->openbay->ebay->lastmsg;

		return $response;
	}

	public function ebayAddItem($data, $options) {
		if ($options == 'yes') {
			$response = $this->openbay->ebay->call('listing/addFixedPrice/', $data);
			$variant = 1;
		} else {
			$response = $this->openbay->ebay->call('listing/ebayAddItem/', $data);
			$variant = 0;
		}

		$data2           = array();
		$data2['data']   = $response;
		$data2['error']  = $this->openbay->ebay->lasterror;
		$data2['msg']    = $this->openbay->ebay->lastmsg;

		if (!empty($response['ItemID'])) {
			$this->openbay->ebay->createLink($data['product_id'], $response['ItemID'], $variant);
			$this->openbay->ebay->addReserve($data, $response['ItemID'], $variant);

			$data2['data']['viewLink']  = html_entity_decode($this->config->get('ebay_itm_link') . $response['ItemID']);
		} else {
			$data2['error']             = false;
			$data2['msg']               = 'ok';
			$data2['data']['Failed']    = true;
		}

		return $data2;
	}

	public function logCategoryUsed($category_id) {
		$breadcrumb = array();
		$original_id = $category_id;
		$stop       = false;
		$i          = 0; //fallback to stop infinate loop
		$err 		= false;

		while($stop == false && $i < 10) {
			$cat = $this->getCategoryStructure($category_id);

			if (!empty($cat)) {
				$breadcrumb[] = $cat['CategoryName'];

				if ($cat['CategoryParentID'] == $category_id) {
					$stop = true;
				} else {
					$category_id = $cat['CategoryParentID'];
				}

				$i++;
			} else {
				$stop = true;
				$err = true;
			}
		}

		if ($err == false) {
			$res = $this->db->query("SELECT `used` FROM `" . DB_PREFIX . "ebay_category_history` WHERE `CategoryID` = '" . $original_id . "' LIMIT 1");

			if ($res->num_rows) {
				$new = $res->row['used'] + 1;
				$this->db->query("UPDATE `" . DB_PREFIX . "ebay_category_history` SET `used` = '" . $new . "' WHERE `CategoryID` = '" . $original_id . "' LIMIT 1");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_category_history` SET `CategoryID` = '" . $original_id . "', `breadcrumb` = '" .   $this->db->escape(implode(' > ', array_reverse($breadcrumb))) . "', `used` = '1'");
			}
		}
	}

	public function getProductStock($id) {
		$res = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . $this->db->escape($id) . "' LIMIT 1");

		if (isset($res->row['has_option']) && $res->row['has_option'] == 1) {
			if ($this->openbay->addonLoad('openstock')) {
				$this->load->model('openstock/openstock');
				$this->load->model('tool/image');
				$variant = $this->model_openstock_openstock->getProductOptionStocks((int)$id);
			} else {
				$variant = 0;
			}
		} else {
			$variant = 0;
		}

		return array(
			'qty'           => $res->row['quantity'],
			'subtract'      => (int)$res->row['subtract'],
			'allocated'     => $this->openbay->ebay->getAllocatedStock($id),
			'variant'       => $variant
		);
	}

	public function getUsage() {
		return $this->openbay->ebay->call('report/accountUse/');
	}

	public function getPlans() {
		return $this->openbay->ebay->call('plan/getPlans/');
	}

	public function getMyPlan() {
		return $this->openbay->ebay->call('plan/myPlan/');
	}

	public function getLiveListingArray() {
		$qry = $this->db->query("SELECT `product_id`, `ebay_item_id` FROM `" . DB_PREFIX . "ebay_listing` WHERE `status` = 1");

		$data = array();
		if ($qry->num_rows) {
			foreach ($qry->rows as $row) {
				$data[$row['product_id']] = $row['ebay_item_id'];
			}
		}

		return $data;
	}

	public function verifyCreds() {
		$this->request->post['domain'] = HTTPS_SERVER;

		$data = $this->openbay->ebay->call('account/validate/', $this->request->post, array(), 'json', 1);

		if ($this->openbay->ebay->lasterror == true) {
			return array(
				'error'     => $this->openbay->ebay->lasterror,
				'msg'       => $this->openbay->ebay->lastmsg
			);
		} else {
			return array(
				'error'     => $this->openbay->ebay->lasterror,
				'msg'       => $this->openbay->ebay->lastmsg,
				'data'      => $data
			);
		}
	}

	public function editSave($data) {
		$this->openbay->ebay->log('editSave() - start..');

		//get product id
		$product_id = $this->openbay->ebay->getProductId($data['itemId']);

		$this->openbay->ebay->log('editSave() - product_id: ' . $product_id);

		if ($data['variant'] == 0) {
			//save the reserve level
			$this->openbay->ebay->updateReserve($product_id, $data['itemId'], $data['qty_reserve']);

			//get the stock info
			$stock = $this->openbay->ebay->getProductStockLevel($product_id);

			//do the stock sync
			$this->openbay->ebay->putStockUpdate($data['itemId'], $stock['quantity']);

			//finish the revise item call
			return $this->openbay->ebay->call('listing/reviseItem/', $data);
		} else {
			$this->openbay->ebay->log('editSave() - variant item');

			$variant_data = array();
			$this->load->model('tool/image');
			$this->load->model('catalog/product');
			$this->load->model('openstock/openstock');

			//get the options list for this product
			$opts = $this->model_openstock_openstock->getProductOptionStocks($product_id);
			reset($opts);
			$variant_data['option_list'] = base64_encode(serialize($opts[key($opts)]['opts']));

			$variant_data['groups']      = $data['optGroupArray'];
			$variant_data['related']     = $data['optGroupRelArray'];
			$variant_data['id']          = $data['itemId'];

			$stock_flag = false;

			foreach ($data['opt'] as $k => $opt) {
				//update the variant reserve level
				$this->openbay->ebay->updateReserve($product_id, $data['itemId'], $opt['reserve'], $opt['sku'], 1);

				//get the stock info
				$stock = $this->openbay->ebay->getProductStockLevel($product_id, $opt['sku']);

				$this->openbay->ebay->log('editSave() - stock: ' . serialize($stock));

				if ($stock['quantity'] > 0 || $stock == true) {
					$stock_flag = true;
				}

				// PRODUCT RESERVE LEVELS FOR VARIANT ITEMS (DOES NOT PASS THROUGH NORMAL SYSTEM)
				$reserve = $this->openbay->ebay->getReserve($product_id, $data['itemId'], $opt['sku']);

				$this->openbay->ebay->log('editSave() - reserve level: ' . $reserve);

				if ($reserve != false) {
					$this->openbay->ebay->log('editSave() / Variant (' . $opt['sku'] . ') - Reserve stock: ' . $reserve);

					if ($stock['quantity'] > $reserve) {
						$this->openbay->ebay->log('editSave() - Stock (' . $stock['quantity'] . ') is larger than reserve (' . $reserve . '), setting level to reserve');
						$stock['quantity'] = $reserve;
					}
				}

				$variant_data['opt'][$k]['sku']     = $opt['sku'];
				$variant_data['opt'][$k]['qty']     = $stock['quantity'];
				$variant_data['opt'][$k]['price']   = number_format($opt['price'], 2, '.', '');
				$variant_data['opt'][$k]['active']  = $opt['active'];
			}

			$this->openbay->ebay->log('editSave() - Debug - ' . serialize($variant_data));

			//send to the api to process
			if ($stock_flag == true) {
				$this->openbay->ebay->log('editSave() - Sending to API');
				$response = $this->openbay->ebay->call('item/reviseVariants', $variant_data);
				return $response;
			} else {
				$this->openbay->ebay->log('editSave() - Ending item');
				$this->openbay->ebay->endItem($data['itemId']);
			}
		}
	}

	public function getProductAttributes($product_id) {
		$product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) WHERE pa.product_id = '" . (int)$product_id . "' AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");

		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) WHERE pa.product_id = '" . (int)$product_id . "' AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
				$product_attribute_data[] = array(
					'attribute_id' => $product_attribute['attribute_id'],
					'name'         => $product_attribute['name'],
					'text'         => $product_attribute['text']
				);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $product_attribute_data
			);
		}

		return $product_attribute_group_data;
	}
}