<?php
class ModelOpenbayEbayProduct extends Model {
	public function getRelistRule($id) {
		return $this->openbay->ebay->call('item/getAutomationRule', array('id' => $id));
	}

	public function importItems($data) {
		$this->openbay->ebay->log('Starting item import');
		$this->load->model('catalog/product');

		//check for ebay import img table
		$res = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "ebay_image_import'");
		if ($res->num_rows == 0) {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "ebay_image_import` (`id` int(11) NOT NULL AUTO_INCREMENT, `image_original` text NOT NULL, `image_new` text NOT NULL, `name` text NOT NULL, `product_id` int(11) NOT NULL, `imgcount` int(11) NOT NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		}

		if ($this->openbay->addonLoad('openstock')) {
			$openstock = true;
			$this->load->model('module/openstock');
		} else {
			$openstock = false;
			$this->openbay->ebay->log('Openstock module not found');
		}

		$categories     = array();
		$data['data'] = unserialize(gzuncompress(stripslashes(base64_decode(strtr($data['data'], '-_,', '+/=')))));
		$new_data = base64_decode($data['data']);
		$options = json_decode($data['options'], 1);

		unset($data['data']);

		$this->openbay->ebay->log('Decoded data');

		$new_data_1   = unserialize($new_data);
		unset($new_data);

		$this->openbay->ebay->log('Data unserialized');

		if ($options['cat'] == 1 || !isset($options['cat'])) {
			$item_count_loop = 0;
			foreach ($new_data_1 as $item) {
				$item_count_loop++;
				$this->openbay->ebay->log('Processing item: ' . $item_count_loop);

				$parts = explode(':', $item['CategoryName']);

				//skip the first category as they are likely to be selling in that
				if (isset($parts[1])) {
					if (!isset($categories[$parts[0]][$parts[1]])) {
						if (!empty($parts[1])) {
							$categories[$parts[0]][$parts[1]] = array();
						}
					}
				}

				if (isset($parts[2])) {
					if (!isset($categories[$parts[0]][$parts[1]][$parts[2]])) {
						if (!empty($parts[2])) {
							$categories[$parts[0]][$parts[1]][$parts[2]] = array();
						}
					}
				}

				if (isset($parts[3])) {
					if (!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]])) {
						if (!empty($parts[3])) {
							$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = array();
						}
					}
				}

				if (isset($parts[4])) {
					if (!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]])) {
						if (!empty($parts[4])) {
							$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] = array();
						}
					}
				}

				if (isset($parts[5])) {
					if (!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]])) {
						if (!empty($parts[5])) {
							$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = array();
						}
					}
				}
			}

			$cat_link = array();
			foreach ($categories as $key1 => $cat1) {
				foreach ($cat1 as $key2 => $cat2) {
					//final cat, add to array as node
					$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '0' AND `" . DB_PREFIX . "category_description`.`name` = '" . $this->db->escape($key2) . "' LIMIT 1");

					if ($qry->num_rows != 0) {
						$id1 = $qry->row['category_id'];
					} else {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '0', `status` = '1', `top` = '1'");
						$id1 = $this->db->getLastId();
						$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '" . $this->db->escape($key2) . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `category_id` = '" . $this->db->escape($id1) . "'");
						$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . $this->db->escape($id1) . "', `store_id` = '0'");
					}

					if (!empty($cat2)) {
						foreach ($cat2 as $key3 => $cat3) {
							$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '" . $this->db->escape($id1) . "' AND `" . DB_PREFIX . "category_description`.`name` = '" . $this->db->escape($key3) . "' LIMIT 1");

							if ($qry->num_rows != 0) {
								$id2 = $qry->row['category_id'];
							} else {
								$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '" . $this->db->escape($id1) . "', `status` = '1', `top` = '1'");
								$id2 = $this->db->getLastId();
								$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '" . $this->db->escape($key3) . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `category_id` = '" . $this->db->escape($id2) . "'");
								$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . $this->db->escape($id2) . "', `store_id` = '0'");
							}

							if (!empty($cat3)) {
								foreach ($cat3 as $key4 => $cat4) {
									$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '" . $this->db->escape($id2) . "' AND `" . DB_PREFIX . "category_description`.`name` = '" . $this->db->escape($key4) . "' LIMIT 1");

									if ($qry->num_rows != 0) {
										$id3 = $qry->row['category_id'];
									} else {
										$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '" . $this->db->escape($id2) . "', `status` = '1', `top` = '1'");
										$id3 = $this->db->getLastId();
										$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '" . $this->db->escape($key4) . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `category_id` = '" . $id3 . "'");
										$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . $this->db->escape($id3) . "', `store_id` = '0'");
									}

									if (!empty($cat4)) {
										foreach ($cat4 as $key5 => $cat5) {
											$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '" . $this->db->escape($id3) . "' AND `" . DB_PREFIX . "category_description`.`name` = '" . $this->db->escape($key5) . "' LIMIT 1");

											if ($qry->num_rows != 0) {
												$id4 = $qry->row['category_id'];
											} else {
												$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '" . $this->db->escape($id3) . "', `status` = '1', `top` = '1'");
												$id4 = $this->db->getLastId();
												$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '" . $this->db->escape($key5) . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `category_id` = '" . $this->db->escape($id4) . "'");
												$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '" . $this->db->escape($id4) . "', `store_id` = '0'");
											}

											$cat_link[$key1 . ':' . $key2 . ':' . $key3 . ':' . $key4 . ':' . $key5] = $id4;
										}
									} else {
										$cat_link[$key1 . ':' . $key2 . ':' . $key3 . ':' . $key4] = $id3;
									}
								}
							} else {
								$cat_link[$key1 . ':' . $key2 . ':' . $key3] = $id2;
							}
						}
					} else {
						$cat_link[$key1 . ':' . $key2] = $id1;
					}
				}
			}

			$this->repairCategories();

			$this->openbay->ebay->log('Categories done');
		} else {
			$this->openbay->ebay->log('Categories set not to be created');
		}

		$current = $this->openbay->ebay->getLiveListingArray();

		foreach ($new_data_1 as $item) {
			if (!in_array($item['ItemID'], $current)) {
				$this->openbay->ebay->log('New item being created: ' . $item['ItemID']);

				//get the manufacturer id
				$manufacturer_id = 0;
				if (!empty($item['Brand'])) {
					$manufacturer_id = $this->manufacturerExists($item['Brand']);
				}

				//get the length class id
				$length_class_id = 1;
				if (isset($item['advanced']['package']['size']['width_unit']) && !empty($item['advanced']['package']['size']['width_unit'])) {
					$length_class_id = $this->lengthClassExists($item['advanced']['package']['size']['width_unit']);
				}

				//get the weight class id
				$weight_class_id = 1;
				if (isset($item['advanced']['package']['weight']['major_unit']) && !empty($item['advanced']['package']['weight']['major_unit'])) {
					$weight_class_id = $this->weightClassExists($item['advanced']['package']['weight']['major_unit']);
				}

				$tax            = $this->config->get('ebay_tax');
				$net_price      = $item['priceGross'] / (($tax / 100) + 1);

				//openstock variant check
				$openstock_sql = '';
				if (!empty($item['variation']) && $openstock == true) {
					$openstock_sql = "`has_option` = '1',";
				}

				//package weight
				if (isset($item['advanced']['package']['weight']['major'])) {
					$weight = $item['advanced']['package']['weight']['major'] . '.' . $item['advanced']['package']['weight']['minor'];
				} else {
					$weight = 0;
				}

				//package length
				if (isset($item['advanced']['package']['size']['length'])) {
					$length = $item['advanced']['package']['size']['length'];
				} else {
					$length = 0;
				}

				//package width
				if (isset($item['advanced']['package']['size']['width'])) {
					$width = $item['advanced']['package']['size']['width'];
				} else {
					$width = 0;
				}

				//package height
				if (isset($item['advanced']['package']['size']['height'])) {
					$height = $item['advanced']['package']['size']['height'];
				} else {
					$height = 0;
				}

				$this->db->query("
					INSERT INTO `" . DB_PREFIX . "product` SET
						`quantity`              = '" . (int)$item['Quantity'] . "',
						`manufacturer_id`       = '" . (int)$manufacturer_id . "',
						`stock_status_id`       = '6',
						`price`                 = '" . (double)$net_price . "',
						`tax_class_id`          = '9',
						`location`              = '" . $this->db->escape(isset($item['note']) ? $item['note'] : '') . "',
						`mpn`              		= '" . $this->db->escape(isset($item['advanced']['brand']['mpn']) ? $item['advanced']['brand']['mpn'] : '') . "',
						`sku`              		= '" . $this->db->escape(isset($item['SKU']) ? $item['SKU'] : '') . "',
						`model`              	= '" . $this->db->escape(isset($item['SKU']) ? $item['SKU'] : '') . "',
						`isbn`              	= '" . $this->db->escape(isset($item['advanced']['isbn']) ? $item['advanced']['isbn'] : '') . "',
						`ean`              		= '" . $this->db->escape(isset($item['advanced']['ean']) ? $item['advanced']['ean'] : '') . "',
						`upc`              		= '" . $this->db->escape(isset($item['advanced']['upc']) ? $item['advanced']['upc'] : '') . "',
						`weight`       			= '" . (double)$weight . "',
						`weight_class_id`       = '" . (int)$weight_class_id . "',
						`length`       			= '" . (double)$length . "',
						`width`       			= '" . (double)$width . "',
						`height`       			= '" . (double)$height . "',
						`length_class_id`       = '" . (int)$length_class_id . "',
						`subtract`              = '1',
						`minimum`               = '1',
						`status`                = '1',
						" . $openstock_sql . "
						`date_available`        = 'now()',
						`date_added`            = 'now()',
						`date_modified`         = 'now()'
				");

				$product_id = $this->db->getLastId();

				$this->openbay->ebay->log('Product insert done');

				//Insert product description
				$original_description = $item['Description'];

				if (!empty($original_description)) {
					if (false !== ($item['Description'] = gzuncompress($original_description))) {
						$item['Description'] = html_entity_decode($item['Description']);
					} else {
						$this->openbay->ebay->log('Description could not be decompressed, output below');
						$this->openbay->ebay->log($original_description);
						$item['Description'] = '';
					}
				}

				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_description` SET `product_id` = '" . (int)$product_id . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `name` = '" . $this->db->escape(htmlspecialchars(base64_decode($item['Title']), ENT_COMPAT, 'UTF-8')) . "', `description` = '" . $this->db->escape(htmlspecialchars(utf8_encode($item['Description']), ENT_COMPAT, 'UTF-8')) . "'");
				$this->openbay->ebay->log('Product description done');

				//Insert product store link
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET `product_id` = '" . (int)$product_id . "', `store_id` = '0'");
				$this->openbay->ebay->log('Store link done');

				//Create any attributes from eBay for the item
				if (!empty($item['specs'])) {
					//check the main group exists, if not create
					$group_id = $this->attributeGroupExists(base64_decode($item['CategoryNameSingle']));

					foreach ($item['specs'] as $spec) {
						//check if the attribute exists in the group, if not create
						$attribute_id = $this->attributeExists($group_id, base64_decode($spec['name']));

						//insert the attribute value into the product attribute table
						$this->attributeAdd($product_id, $attribute_id, base64_decode($spec['value']));
					}
				}

				//Create the product variants for OpenStock
				$variant = 0;
				if (!empty($item['variation'])) {
					$variant = 1;

					if ($openstock == true) {
						$this->openbay->ebay->log('OpenStock Loaded');
						$this->createVariants($product_id, $item);
					}

					$this->openbay->ebay->log('Variants done');
				}

				//insert store to eBay item link
				$this->openbay->ebay->createLink($product_id, $item['ItemID'], $variant);

				//Insert product/category link
				if ($options['cat'] == 1 || !isset($options['cat'])) {
					$this->createCategoryLink($product_id, $cat_link[$item['CategoryName']]);
				}

				//images
				$img_count = 0;
				if (is_array($item['pictures'])) {
					foreach ($item['pictures'] as $img) {
						if (!empty($img)) {
							$name = rand(500000, 1000000000);
							$this->addImage($img, DIR_IMAGE . 'catalog/' . $name . '.jpg', $name . '.jpg', $product_id, $img_count);
							$img_count++;
						}
					}
				}

				$this->openbay->ebay->log('Product import completed . ');
			} else {
				$this->openbay->ebay->log($item['ItemID'] . ' exists already');
			}
		}

		$this->openbay->ebay->log('Product data import done');
		$this->openbay->ebay->getImages();
	}

	public function getDisplayProducts() {
		$data = array();
		$data['search_keyword'] = $this->config->get('ebaydisplay_module_keywords');
		$data['seller_id']      = $this->config->get('ebaydisplay_module_username');
		$data['limit']          = $this->config->get('ebaydisplay_module_limit');
		$data['sort']           = $this->config->get('ebaydisplay_module_sort');
		$data['search_desc']    = $this->config->get('ebaydisplay_module_description');

		return $this->openbay->ebay->call('item/searchListingsForDisplay', $data);
	}

	private function createVariants($product_id, $data) {
		foreach ($data['variation']['vars'] as $variant_id => $variant) {
			foreach ($variant['opt'] as $k_opt => $v_opt) {
				$name = base64_decode($k_opt);
				$value = $v_opt;

				$option = $this->getOption($name);
				$option_value = $this->getOptionValue($value, $option['id']);

				$product_option_id = $this->getProductOption($product_id, $option['id']);
				$product_option_value_id = $this->getProductOptionValue($product_id, $option['id'], $option_value['id'], $product_option_id);
				$data['variation']['vars'][$variant_id]['product_option_values'][] = $product_option_value_id;
			}

			$this->db->query("UPDATE `" . DB_PREFIX . "product_option_value` SET subtract = '0', price = '0.000', quantity = '0' WHERE product_id = '" . (int)$product_id . "'");
		}

		$all_variants = $this->model_module_openstock->calculateVariants($product_id);
		foreach ($all_variants as $new_variant) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_variant` SET `product_id` = '" . (int)$product_id . "', `sku` = '', `stock` = '0', `active` = '0', `subtract` = '1', `price` = '0.00', `image` = '', `weight` = '0.00'");

			$variant_id = $this->db->getLastId();

			$i = 1;
			foreach ($new_variant as $new_variant_value) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_option_variant_value` SET `product_option_variant_id` = '" . (int)$variant_id . "', `product_option_value_id` = '" . (int)$new_variant_value . "', `product_id` = '" . (int)$product_id . "', `sort_order` = '" . (int)$i++ . "'");
			}
		}

		foreach ($data['variation']['vars'] as $variant_id => $variant) {
			$variant_row = $this->model_module_openstock->getVariantByOptionValues($variant['product_option_values'], $product_id);

			if (!empty($variant_row)) {
				$this->db->query("UPDATE `" . DB_PREFIX . "product_option_variant` SET `product_id` = '" . (int)$product_id . "', `sku` = '" . $this->db->escape($variant['sku']) . "', `stock` = '" . (int)$variant['qty'] . "', `active` = 1, `price` = '" . (float)$variant['price'] . "' WHERE `product_option_variant_id` = '" . (int)$variant_row['product_option_variant_id'] . "'");
			}
		}
	}

	private function getOption($name) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` `o` LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`od`.`option_id` = `o`.`option_id`) WHERE `od`.`name` = '" . $this->db->escape($name) . "'LIMIT 1");

		if ($qry->num_rows) {
			return array('id' => (int)$qry->row['option_id'], 'sort' => (int)$qry->row['sort_order']);
		} else {
			return $this->createOption($name);
		}
	}

	private function createOption($name) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET `type` = 'select', `sort_order` = IFNULL((select `sort` FROM (SELECT (MAX(`sort_order`)+1) AS `sort` FROM `" . DB_PREFIX . "option`) AS `i`),0)");

		$option_id = $this->db->getLastId();

		$qry_sort = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE `option_id` = '" . (int)$option_id . "' LIMIT 1");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `name` = '" . $this->db->escape($name) . "', `option_id` = '" . (int)$option_id . "'");

		return array('id' => (int)$option_id, 'sort' => (int)$qry_sort->row['sort_order']);
	}

	private function getOptionValue($name, $option_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option_value` ov LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`) WHERE `ovd`.`name` = '" . $this->db->escape($name) . "' AND `ovd`.`option_id` = '" . (int)$option_id . "'LIMIT 1");

		if ($qry->num_rows) {
			return array('id' => (int)$qry->row['option_value_id'], 'sort' => (int)$qry->row['sort_order']);
		} else {
			return $this->createOptionValue($name, $option_id);
		}
	}

	private function createOptionValue($name, $option_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_id` = '" . (int)$option_id . "', `sort_order` = IFNULL((select `sort` FROM (SELECT (MAX(`sort_order`)+1) AS `sort` FROM `" . DB_PREFIX . "option_value`) AS `i`),0)");

		$id = $this->db->getLastId();

		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `name` = '" . $this->db->escape($name) . "', `option_id` = '" . (int)$option_id . "', `option_value_id` = '" . (int)$id . "'");

		return array('id' => (int)$id);
	}

	private function getProductOption($product_id, $option_id) {
		$qry = $this->db->query("SELECT * FROM  " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$option_id . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['product_option_id'];
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', required = '1'");
			return $this->db->getLastId();
		}
	}

	private function getProductOptionValue($product_id, $option_id, $option_value_id, $product_option_id) {
		$qry = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "product_option_value` WHERE `product_id` = '" . (int)$product_id . "' AND `option_id` = '" . (int)$option_id . "' AND `product_option_id` = '" . (int)$product_option_id . "' AND `option_value_id` = '" . (int)$option_value_id . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['product_option_value_id'];
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option_value SET product_option_id = '" . (int)$product_option_id . "', product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', option_value_id = '" . (int)$option_value_id . "'");

			return $this->db->getLastId();
		}
	}

	private function attributeGroupExists($name) {
		$this->openbay->ebay->log('Checking attribute group: ' . $name);
		$qry = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "attribute_group_description` WHERE `name` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['attribute_group_id'];
		} else {
			$this->openbay->ebay->log('New group');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "attribute_group` ORDER BY `sort_order` DESC LIMIT 1");

			if ($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			} else {
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$sort . "'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$id . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `name` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "'");

			return $id;
		}
	}

	private function attributeExists($group_id, $name) {
		$this->openbay->ebay->log('Checking attribute: ' . $name);

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "attribute_description` `ad`, `" . DB_PREFIX . "attribute` `a` WHERE `ad`.`name` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "' AND `ad`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `a`.`attribute_id` = `ad`.`attribute_id` AND `a`.`attribute_group_id` = '" . (int)$group_id . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['attribute_id'];
		} else {
			$this->openbay->ebay->log('New attribute');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "attribute` ORDER BY `sort_order` DESC LIMIT 1");

			if ($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			} else {
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET `sort_order` = '" . (int)$sort . "', `attribute_group_id` = '" . (int)$group_id . "'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$id . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "', `name` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "'");

			return (int)$id;
		}
	}

	private function attributeAdd($product_id, $attribute_id, $name) {
		$this->openbay->ebay->log('Adding product attribute');

		$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '" . (int)$product_id . "' AND `attribute_id` = '" . (int)$attribute_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		if ($sql->num_rows == 0) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '" . (int)$product_id . "', `attribute_id` = '" . (int)$attribute_id . "', `text` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "', `language_id` = '" . (int)$this->config->get('config_language_id') . "'");
		}
	}

	private function createCategoryLink($product_id, $category_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '" . (int)$product_id . "', `category_id` = '" . (int)$category_id . "'");
	}

	private function manufacturerExists($name) {
		$this->openbay->ebay->log('Checking manufacturer: ' . $name);

		$qry = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "manufacturer` WHERE LCASE(`name`) = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['manufacturer_id'];
		} else {
			$this->openbay->ebay->log('New manufacturer');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "manufacturer` ORDER BY `sort_order` DESC LIMIT 1");

			if ($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			} else {
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET `sort_order` = '" . (int)$sort . "', `name` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$id . "', `store_id` = '0'");

			return $id;
		}
	}

	private function weightClassExists($name) {
		$this->openbay->ebay->log('Checking weight class: ' . $name);

		$qry = $this->db->query("SELECT `weight_class_id` FROM `" . DB_PREFIX . "weight_class_description` WHERE LCASE(`title`) = '" . $this->db->escape(strtolower($name)) . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['weight_class_id'];
		} else {
			$this->openbay->ebay->log('New weight class');

			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class` SET `value` = '1'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "weight_class_description` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `weight_class_id` = '" . (int)$id . "', `title` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "', `unit` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "'");

			return $id;
		}
	}

	private function lengthClassExists($name) {
		$this->openbay->ebay->log('Checking length class: ' . $name);

		$qry = $this->db->query("SELECT `length_class_id` FROM `" . DB_PREFIX . "length_class_description` WHERE LCASE(`title`) = '" . $this->db->escape(strtolower($name)) . "' LIMIT 1");

		if ($qry->num_rows != 0) {
			return $qry->row['length_class_id'];
		} else {
			$this->openbay->ebay->log('New length class');

			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class` SET `value` = '1'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "length_class_description` SET `language_id` = '" . (int)$this->config->get('config_language_id') . "', `length_class_id` = '" . (int)$id . "', `title` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "', `unit` = '" . $this->db->escape(htmlspecialchars($name, ENT_COMPAT)) . "'");

			return $id;
		}
	}

	private function addImage($orig, $new, $name, $product_id, $img_count) {
		$orig = str_replace(' ', '%20', $orig);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "ebay_image_import` SET `image_original` = '" . $this->db->escape($orig) . "', `image_new` = '" . $this->db->escape($new) . "', `name` = '" . $this->db->escape($name) . "', `product_id` = '" . (int)$product_id . "', `imgcount` = '" . (int)$img_count . "'");
	}

	public function resize($filename, $width, $height, $type = "") {
		if (!file_exists(DIR_IMAGE . 'catalog/' . md5($filename) . '.jpg')) {
			copy($filename, DIR_IMAGE . 'catalog/' . md5($filename) . '.jpg');
		}

		$old_image = DIR_IMAGE . 'catalog/' . md5($filename) . '.jpg';
		$new_image = 'cache/ebaydisplay/' . md5($filename) . '-' . $width . 'x' . $height . $type  . '.jpg';

		if (!file_exists(DIR_IMAGE . $new_image)) {
			$path = '';

			$directories = explode('/', dirname(str_replace(' . ./', '', $new_image)));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!file_exists(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			list($width_orig, $height_orig) = getimagesize($filename);

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image($old_image);
				$image->resize($width, $height, $type);
				$image->save(DIR_IMAGE . $new_image);
			} else {
				copy($filename, DIR_IMAGE . $new_image);
			}
		}

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}

	private function repairCategories($parent_id = 0) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category WHERE parent_id = '" . (int)$parent_id . "'");

		foreach ($query->rows as $category) {
			// Delete the path below the current one
			$this->db->query("DELETE FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$category['category_id'] . "'");

			// Fix for records with no paths
			$level = 0;

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category_path` WHERE category_id = '" . (int)$parent_id . "' ORDER BY level ASC");

			foreach ($query->rows as $result) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', level = '" . (int)$level . "'");

				$level++;
			}

			$this->db->query("REPLACE INTO `" . DB_PREFIX . "category_path` SET category_id = '" . (int)$category['category_id'] . "', `path_id` = '" . (int)$category['category_id'] . "', level = '" . (int)$level . "'");

			$this->repairCategories($category['category_id']);
		}
	}
}