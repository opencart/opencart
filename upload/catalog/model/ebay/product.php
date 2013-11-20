<?php
class ModelEbayProduct extends Model {
	public function getRelistRule($id) {
		return $this->openbay->ebay->openbay_call('item/getAutomationRule', array('id' => $id));
	}

	public function importItems($data) {
		$this->openbay->ebay->log('Starting item import');
		$this->load->model('catalog/product');

		//check for ebay import img table
		$res = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."ebay_image_import'");
		if($res->num_rows == 0) {
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `".DB_PREFIX."ebay_image_import` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `image_original` text NOT NULL,
				  `image_new` text NOT NULL,
				  `name` text NOT NULL,
				  `product_id` int(11) NOT NULL,
				  `imgcount` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
			");
		}

		if ($this->openbay->addonLoad('openstock')) {
			$openstock = true;
			$this->load->model('openstock/openstock');
		}else{
			$openstock = false;
			$this->openbay->ebay->log('Openstock module not found');
		}

		$categories     = array();
		$data['data']   = unserialize(gzuncompress(stripslashes(base64_decode(strtr($data['data'], '-_,', '+/=')))));
		$newData        = base64_decode($data['data']);

		unset($data['data']);

		$this->openbay->ebay->log('Decoded data');

		$newData1   = unserialize($newData);
		unset($newData);

		$this->openbay->ebay->log('Data unserialized');

		$itemCountLoop = 0;
		foreach($newData1 as $item) {
			$itemCountLoop++;
			$this->openbay->ebay->log('Processing item: '.$itemCountLoop);

			$parts = explode(':', $item['CategoryName']);

			//skip the first category as they are likely to be selling in that
			if(isset($parts[1])) {
				if(!isset($categories[$parts[0]][$parts[1]])) {
					if(!empty($parts[1])) {
						$categories[$parts[0]][$parts[1]] = array();
					}
				}
			}

			if(isset($parts[2])) {
				if(!isset($categories[$parts[0]][$parts[1]][$parts[2]])) {
					if(!empty($parts[2])) {
						$categories[$parts[0]][$parts[1]][$parts[2]] = array();
					}
				}
			}

			if(isset($parts[3])) {
				if(!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]])) {
					if(!empty($parts[3])) {
						$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]] = array();
					}
				}
			}

			if(isset($parts[4])) {
				if(!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]])) {
					if(!empty($parts[4])) {
						$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]] = array();
					}
				}
			}

			if(isset($parts[5])) {
				if(!isset($categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]])) {
					if(!empty($parts[5])) {
						$categories[$parts[0]][$parts[1]][$parts[2]][$parts[3]][$parts[4]][$parts[5]] = array();
					}
				}
			}
		}

		$catLink = array();
		foreach($categories as $key1=>$cat1) {
			foreach($cat1 as $key2=>$cat2) {
				//final cat, add to array as node
				$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '0' AND `" . DB_PREFIX . "category_description`.`name` = '".$this->db->escape($key2)."' LIMIT 1");

				if($qry->num_rows != 0) {
					$id1 = $qry->row['category_id'];
				}else{
					$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '0', `status` = '1', `top` = '1'");
					$id1 = $this->db->getLastId();
					$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '".$this->db->escape($key2)."', `language_id` = '".(int)$this->config->get('config_language_id')."', `category_id` = '".$id1."'");
					$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '".$id1."', `store_id` = '0'");
				}

				if(!empty($cat2)) {
					foreach($cat2 as $key3=>$cat3) {
						$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '".$id1."' AND `" . DB_PREFIX . "category_description`.`name` = '".$this->db->escape($key3)."' LIMIT 1");

						if($qry->num_rows != 0) {
							$id2 = $qry->row['category_id'];
						}else{
							$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '".$id1."', `status` = '1', `top` = '1'");
							$id2 = $this->db->getLastId();
							$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '".$this->db->escape($key3)."', `language_id` = '".(int)$this->config->get('config_language_id')."', `category_id` = '".$id2."'");
							$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '".$id2."', `store_id` = '0'");
						}

						if(!empty($cat3)) {
							foreach($cat3 as $key4=>$cat4) {
								$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '".$id2."' AND `" . DB_PREFIX . "category_description`.`name` = '".$this->db->escape($key4)."' LIMIT 1");

								if($qry->num_rows != 0) {
									$id3 = $qry->row['category_id'];
								}else{
									$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '".$id2."', `status` = '1', `top` = '1'");
									$id3 = $this->db->getLastId();
									$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '".$this->db->escape($key4)."', `language_id` = '".(int)$this->config->get('config_language_id')."', `category_id` = '".$id3."'");
									$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '".$id3."', `store_id` = '0'");
								}

								if(!empty($cat4)) {
									foreach($cat4 as $key5=>$cat5) {
										$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "category`, `" . DB_PREFIX . "category_description` WHERE `" . DB_PREFIX . "category`.`parent_id` = '".$id3."' AND `" . DB_PREFIX . "category_description`.`name` = '".$this->db->escape($key5)."' LIMIT 1");

										if($qry->num_rows != 0) {
											$id4 = $qry->row['category_id'];
										}else{
											$this->db->query("INSERT INTO `" . DB_PREFIX . "category` SET `parent_id` = '".$id3."', `status` = '1', `top` = '1'");
											$id4 = $this->db->getLastId();
											$this->db->query("INSERT INTO `" . DB_PREFIX . "category_description` SET `name` = '".$this->db->escape($key5)."', `language_id` = '".(int)$this->config->get('config_language_id')."', `category_id` = '".$id4."'");
											$this->db->query("INSERT INTO `" . DB_PREFIX . "category_to_store` SET `category_id` = '".$id4."', `store_id` = '0'");
										}

										$catLink[$key1.':'.$key2.':'.$key3.':'.$key4.':'.$key5] = $id4;
									}
								}else{
									$catLink[$key1.':'.$key2.':'.$key3.':'.$key4] = $id3;
								}
							}
						}else{
							$catLink[$key1.':'.$key2.':'.$key3] = $id2;
						}
					}
				}else{
					$catLink[$key1.':'.$key2] = $id1;
				}
			}
		}

		$this->openbay->ebay->log('Categories done');

		$imgCount = 0;

		$current = $this->openbay->ebay->getLiveListingArray();

		foreach($newData1 as $item) {
			if(!in_array($item['ItemID'], $current)) {
				$this->openbay->ebay->log('New item being created: '.$item['ItemID']);

				//find or create the manufacturer id if it is provided.
				$manufacturer_id = 0;
				if(!empty($item['Brand'])) {
					$manufacturer_id = $this->manufacturerExists($item['Brand']);
				}

				$tax            = $this->config->get('tax');
				$net_price      = $item['priceGross'] / (($tax / 100) + 1);

				//openstock variant check
				$openStockHasOptionSql = '';
				if(!empty($item['variation']) && $openstock == true) {
					$openStockHasOptionSql = "`has_option` = '1',";
				}

				//if the version of OC contains the new columns
				$mpnSql = '';
				$res = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."product` LIKE 'mpn'");
				if($res->num_rows != 0) {
					$mpnSql = "`mpn` = '".$this->db->escape($item['SKU'])."',";
				}

				$this->db->query("
					INSERT INTO `" . DB_PREFIX . "product` SET
						`quantity`              = '".$item['Quantity']."',
						`manufacturer_id`       = '".$manufacturer_id."',
						`stock_status_id`       = '6',
						`model`                 = '".$this->db->escape($item['SKU'])."',
						`price`                 = '".$net_price."',
						`tax_class_id`          = '9',
						`location`              = '".$this->db->escape(isset($item['note']) ? $item['note'] : '')."',
						`weight_class_id`       = '1',
						`length_class_id`       = '1',
						`subtract`              = '1',
						`minimum`               = '1',
						`status`                = '1',
						".$openStockHasOptionSql."
						".$mpnSql."
						`date_available`        = 'now()',
						`date_added`            = 'now()',
						`date_modified`         = 'now()'
				");

				$product_id = $this->db->getLastId();

				$this->openbay->ebay->log('Product insert done');

				//Insert product description
				$originalDescription = $item['Description'];

				if(!empty($originalDescription)) {
					if ( false !== ($item['Description'] = gzuncompress($originalDescription))) {
						$item['Description'] = html_entity_decode($item['Description']);
					}else{
						$this->openbay->ebay->log('Description could not be decompressed, output below');
						$this->openbay->ebay->log($originalDescription);
						$item['Description'] = '';
					}
				}

				$sql = " INSERT INTO `" . DB_PREFIX . "product_description` SET
						`product_id`            = '".(int)$product_id."',
						`language_id`           = '".(int)$this->config->get('config_language_id')."',
						`name`                  = '".$this->db->escape(htmlspecialchars(base64_decode($item['Title']), ENT_COMPAT))."',
						`description`           = '".$this->db->escape(htmlspecialchars($item['Description'], ENT_COMPAT))."'";

				$this->db->query($sql);
				$this->openbay->ebay->log('Product description done');

				//Insert product store link
				$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_store` SET `product_id` = '".(int)$product_id."', `store_id` = '0'");
				$this->openbay->ebay->log('Store link done');

				//Create any attributes from eBay for the item
				if(!empty($item['specs'])) {
					//check the main group exists, if not create
					$groupId = $this->attributeGroupExists(base64_decode($item['CategoryNameSingle']));

					foreach($item['specs'] as $spec) {
						//check if the attribute exists in the group, if not create
						$attrId = $this->attributeExists($groupId, base64_decode($spec['name']));

						//insert the attribute value into the product attribute table
						$this->attributeAdd($product_id, $attrId, base64_decode($spec['value']));
					}
				}

				//Create the product variants for OpenStock
				$variant = 0;
				if(!empty($item['variation'])) {
					$variant = 1;

					if($openstock == true) {
						$this->openbay->ebay->log('OpenStock Loaded');
						$this->createVariants($product_id, $item);
					}

					$this->openbay->ebay->log('Variants done');
				}

				//insert store to eBay item link
				$this->openbay->ebay->createLink($product_id, $item['ItemID'], $variant);

				//Insert product/category link
				$this->createCategoryLink($product_id, $catLink[$item['CategoryName']]);

				//images
				$imgCount = 0;
				if(is_array($item['pictures'])) {
					foreach($item['pictures'] as $img) {
						if(!empty($img)) {
							$name = rand(500000, 1000000000);
							$this->addImage($img, DIR_IMAGE.'data/'.$name.'.jpg', $name.'.jpg', $product_id, $imgCount);
							$imgCount++;
						}
					}
				}
				$this->openbay->ebay->log('Product import completed.');

			}else{
				$this->openbay->ebay->log($item['ItemID'].' exists already');
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

		return $this->openbay->ebay->openbay_call('item/searchListingsForDisplay', $data);
	}

	private function createVariants($product_id, $data) {
		foreach($data['variation']['vars'] as $variant) {
			$vars           = array();
			$s              = '';

			foreach($variant['opt'] as $k_opt => $v_opt) {
				$name       = base64_decode($k_opt);
				$value      = $v_opt;

				$option     = $this->getOption($name);
				$opt        = $this->getOptionValue($value, $option['id']);

				//lookup product option rel table, insert if needed
				$product_option_id          = $this->getProductOption($product_id, $option['id']);
				$product_option_value_id    = $this->getProductOptionValue($product_id, $option['id'], $opt['id'], $product_option_id);

				$this->openbay->ebay->log('Option data: '.serialize($option));

				$s          = $option['sort'];
				$vars[$s]   = $product_option_value_id;
			}

			//$this->openbay->ebay->log('Unsorted: '.serialize($vars));

			//sort the array to the natural sort order
			ksort($vars);

			//$this->openbay->ebay->log('Sorted: '.serialize($vars));

			//remove the key from the array to pass to implode
			$vars2 = array();
			foreach($vars as $k=>$v)
			{
				$vars2[] = $v;
			}

			//implode the values
			$vars = implode(':', $vars2);

			//$this->openbay->ebay->log('Vars: '.$vars);

			//create the variant
			$this->createProductVariant(array('var' => $vars, 'price' => $variant['price'], 'stock' => $variant['qty'], 'product_id' => $product_id, 'sku' => $variant['sku']));
		}

		$this->updateVariantListing($product_id, $data['ItemID']);

		//$this->openbay->ebay->log('Item variant stuff done..');
	}

	private function getOption($name) {
		$qry = $this->db->query("
			SELECT *
			FROM
				`" . DB_PREFIX . "option` `o`
				LEFT JOIN `" . DB_PREFIX . "option_description` `od` ON (`od`.`option_id` = `o`.`option_id`)
			WHERE
				`od`.`name` = '".$this->db->escape($name)."'
			LIMIT 1");

		if($qry->num_rows) {
			$this->openbay->ebay->log('Option found: "'.$name.' / '.$qry->row['option_id'].'" with sort order of "'.$qry->row['sort_order'].'"');
			return array('id' => (int)$qry->row['option_id'], 'sort' => (int)$qry->row['sort_order']);
		}else{
			return $this->createOption($name);
		}
	}

	private function createOption($name) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option` SET `type` = 'select', `sort_order` = IFNULL((select `sort` FROM (SELECT (MAX(`sort_order`)+1) AS `sort` FROM `" . DB_PREFIX . "option`) AS `i`),0)");
		$option_id = $this->db->getLastId();

		$qry_sort = $this->db->query("SELECT * FROM `" . DB_PREFIX . "option` WHERE `option_id` = '".$option_id."' LIMIT 1");

		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_description` SET `language_id` = '".(int)$this->config->get('config_language_id')."', `name` = '".$this->db->escape($name)."', `option_id` = '".$option_id."'");

		$this->openbay->ebay->log('No option found, creating: "'.$name.' / '.$option_id.'" with sort order of "'.$qry_sort->row['sort_order'].'"');
		return array('id' => (int)$option_id, 'sort' => (int)$qry_sort->row['sort_order']);
	}

	private function getOptionValue($name, $option_id) {
		$qry = $this->db->query("
			SELECT *
			FROM
				`" . DB_PREFIX . "option_value` ov
				LEFT JOIN `" . DB_PREFIX . "option_value_description` `ovd` ON (`ovd`.`option_value_id` = `ov`.`option_value_id`)
			WHERE
				`ovd`.`name` = '".$this->db->escape($name)."'
			AND
				`ovd`.`option_id` = '".(int)$option_id."'
			LIMIT 1");

		if($qry->num_rows) {
			//$this->openbay->ebay->log('Option value found: "'.$name.'"');
			return array('id' => (int)$qry->row['option_value_id'], 'sort' => (int)$qry->row['sort_order']);
		}else{
			//$this->openbay->ebay->log('No option value found, creating "'.$name.'"');
			return $this->createOptionValue($name, $option_id);
		}
	}

	private function createOptionValue($name, $option_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value` SET `option_id` = '".$option_id."', `sort_order` = IFNULL((select `sort` FROM (SELECT (MAX(`sort_order`)+1) AS `sort` FROM `" . DB_PREFIX . "option_value`) AS `i`),0)");
		$id = $this->db->getLastId();
		$this->db->query("INSERT INTO `" . DB_PREFIX . "option_value_description` SET `language_id` = '".(int)$this->config->get('config_language_id')."', `name` = '".$this->db->escape($name)."', `option_id` = '".$option_id."', `option_value_id` = '".$id."'");
		return array('id' => (int)$id);
	}

	private function getProductOption($product_id, $option_id) {
		$qry = $this->db->query("SELECT * FROM  " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "' AND option_id = '" . (int)$option_id . "' LIMIT 1");

		if($qry->num_rows) {
			return $qry->row['product_option_id'];
		}else{
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_option SET product_id = '" . (int)$product_id . "', option_id = '" . (int)$option_id . "', required = '1'");
			return $this->db->getLastId();
		}
	}

	private function getProductOptionValue($product_id, $option_id, $option_value_id, $product_option_id) {
		$qry = $this->db->query("
			SELECT *
			FROM  `" . DB_PREFIX . "product_option_value`
				WHERE `product_id` = '" . (int)$product_id . "'
				AND `option_id` = '" . (int)$option_id . "'
				AND `product_option_id` = '".(int)$product_option_id."'
				AND `option_value_id` = '".(int)$option_value_id."'
				LIMIT 1
			");

		if($qry->num_rows) {
			return $qry->row['product_option_value_id'];
		}else{
			$this->db->query("
				INSERT INTO " . DB_PREFIX . "product_option_value
				SET
					product_option_id = '" . (int)$product_option_id . "',
					product_id = '" . (int)$product_id . "',
					option_id = '".(int)$option_id."',
					option_value_id = '".(int)$option_value_id."'
			");

			return $this->db->getLastId();
		}
	}

	private function createProductVariant($data) {
		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "product_option_relation`
			SET
				`product_id`    = '".$data['product_id']."',
				`var`           = '".$data['var']."',
				`stock`         = '".$data['stock']."',
				`sku`           = '".$data['sku']."',
				`active`        = '1',
				`subtract`      = '1',
				`price`         = '".$data['price']."'
		");

		return array('id' => $this->db->getLastId());
	}

	private function updateVariantListing($product_id, $item_id) {
		$varData = array();

		$variants           = $this->model_openstock_openstock->getProductOptionStocks($product_id);
		$groups             = $this->model_catalog_product->getProductOptions($product_id);

		$varData['groups']  = array();
		$varData['related'] = array();

		foreach($groups as $grp) {
			$t_tmp = array();
			foreach($grp['option_value'] as $grp_node) {
				$t_tmp[$grp_node['option_value_id']] = $grp_node['name'];

				$varData['related'][$grp_node['product_option_value_id']] = $grp['name'];
			}

			$varData['groups'][] = array('name' => $grp['name'], 'child' => $t_tmp);
		}

		$v = 0;

		foreach($variants as $option) {
			if($v == 0) {
				//create a php version of the option element array to use on server side
				$varData['option_list'] = base64_encode(serialize($option['opts']));
			}

			$varData['opt'][$v]['sku']     = $option['var'];
			$varData['opt'][$v]['qty']     = $option['stock'];
			$varData['opt'][$v]['price']   = number_format($option['price'], 2);

			$varData['opt'][$v]['active']  = 0;
			if($option['active'] == 1) {  $varData['opt'][$v]['active'] = 1; }

			$v++;
		}

		$varData['groups']  = base64_encode(serialize($varData['groups']));
		$varData['related'] = base64_encode(serialize($varData['related']));
		$varData['id']      = $item_id;

		//send to the api to process
		$this->openbay->ebay->openbay_call_noresponse('item/reviseVariants', $varData);
	}

	private function attributeGroupExists($name) {
		$this->openbay->ebay->log('Checking attribute group: '.$name);
		$qry = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "attribute_group_description` WHERE `name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."' AND `language_id` = '".(int)$this->config->get('config_language_id')."' LIMIT 1");

		if($qry->num_rows) {
			$this->openbay->ebay->log('Group exists');
			return $qry->row['attribute_group_id'];
		}else{
			$this->openbay->ebay->log('New group');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "attribute_group` ORDER BY `sort_order` DESC LIMIT 1");

			if($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			}else{
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group` SET `sort_order` = '" . (int)$sort . "'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_group_description` SET `attribute_group_id` = '" . (int)$id . "', `language_id` = '".(int)$this->config->get('config_language_id')."', `name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."'");

			return $id;
		}
	}

	private function attributeExists($groupId, $name) {
		$this->openbay->ebay->log('Checking attribute: '.$name);

		$qry = $this->db->query("
			SELECT * FROM
				`" . DB_PREFIX . "attribute_description` `ad`,
				`" . DB_PREFIX . "attribute` `a`
			WHERE `ad`.`name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."'
			AND `ad`.`language_id` = '".(int)$this->config->get('config_language_id')."'
			AND `a`.`attribute_id` = `ad`.`attribute_id`
			AND `a`.`attribute_group_id` = '".$groupId."'
			LIMIT 1
		");

		if($qry->num_rows) {
			$this->openbay->ebay->log('Attribute exists');
			return $qry->row['attribute_id'];
		}else{
			$this->openbay->ebay->log('New attribute');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "attribute` ORDER BY `sort_order` DESC LIMIT 1");

			if($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			}else{
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute` SET `sort_order` = '" . (int)$sort . "', `attribute_group_id` = '" . (int)$groupId . "'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "attribute_description` SET `attribute_id` = '" . (int)$id . "', `language_id` = '".(int)$this->config->get('config_language_id')."', `name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."'");

			return (int)$id;
		}
	}

	private function attributeAdd($product_id, $attrId, $name) {
		$this->openbay->ebay->log('Adding product attribute');
		$sql = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_attribute` WHERE `product_id` = '".(int)$product_id."' AND `attribute_id` = '".(int)$attrId."' AND `language_id` = '".(int)$this->config->get('config_language_id')."'");

		if($sql->num_rows == 0) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "product_attribute` SET `product_id` = '".(int)$product_id."', `attribute_id` = '".(int)$attrId."', `text` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."', `language_id` = '".(int)$this->config->get('config_language_id')."'");
		}
	}

	private function createCategoryLink($product_id, $category_id) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "product_to_category` SET `product_id` = '".(int)$product_id."', `category_id` = '".(int)$category_id."'");
	}

	private function manufacturerExists($name) {
		$this->openbay->ebay->log('Checking manufacturer: '.$name);
		$qry = $this->db->query("SELECT * FROM  `" . DB_PREFIX . "manufacturer` WHERE `name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."' LIMIT 1");

		if($qry->num_rows) {
			$this->openbay->ebay->log('Manufacturer exists');
			return $qry->row['manufacturer_id'];
		}else{
			$this->openbay->ebay->log('New manufacturer');
			$qry2 = $this->db->query("SELECT `sort_order` FROM  `" . DB_PREFIX . "manufacturer` ORDER BY `sort_order` DESC LIMIT 1");

			if($qry2->num_rows) {
				$sort = $qry2->row['sort_order'] + 1;
			}else{
				$sort = 0;
			}

			$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET `sort_order` = '" . (int)$sort . "', `name` = '".$this->db->escape(htmlspecialchars($name, ENT_COMPAT))."'");

			$id = $this->db->getLastId();

			$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$id . "', `store_id` = '0'");

			return $id;
		}
	}

	private function addImage($orig, $new, $name, $product_id, $imgCount) {
		$orig = str_replace(' ', '%20',$orig);

		$this->db->query("
			INSERT INTO `" . DB_PREFIX . "ebay_image_import` SET
				`image_original`    = '".$this->db->escape($orig)."',
				`image_new`         = '".$this->db->escape($new)."',
				`name`              = '".$this->db->escape($name)."',
				`product_id`        = '".(int)$product_id."',
				`imgcount`          = '".(int)$imgCount."'
		");
	}

	public function resize($filename, $width, $height, $type = "") {

		if (!file_exists(DIR_IMAGE . 'data/' . md5($filename).'.jpg')) {
			copy($filename, DIR_IMAGE . 'data/' . md5($filename).'.jpg');
		}

		$old_image = DIR_IMAGE . 'data/' . md5($filename).'.jpg';
		$new_image = 'cache/ebaydisplay/' . md5($filename) . '-' . $width . 'x' . $height . $type .'.jpg';

		if (!file_exists(DIR_IMAGE . $new_image)) {
			$path = '';

			$directories = explode('/', dirname(str_replace('../', '', $new_image)));

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
}
?>