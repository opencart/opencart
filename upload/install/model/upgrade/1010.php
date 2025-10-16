<?php
class ModelUpgrade1010 extends Model {
	public function upgrade() {
		$dir_storage = str_replace('\\', '/', realpath(DIR_STORAGE));
		$dir_current_storage = str_replace('\\', '/', realpath($this->getCurrentStorageDirectory()));
		$dir_vendor = $dir_storage.'/vendor';
		$dir_current_vendor = $dir_current_storage.'/vendor';

		// remove obsolete files and folders from vendor directory
		$obsoletes = array(
			'bin/',
			'braintree/braintree_php/tests/Braintree/fixtures/',
			'braintree/braintree_php/tests/integration/SubscriptionTestHelper.php',
			'braintree/braintree_php/tests/TestHelper.php',
			'braintree/braintree_php/.gitignore',
			'cardinity/cardinity-sdk-php/spec/Method/Void/',
			'cardinity/cardinity-sdk-php/src/Method/Void/',
			'cardinity/cardinity-sdk-php/tests/VoidTest.php',
			'cardinity/cardinity-sdk-php/.gitignore',
			'divido/',
			'guzzlehttp/guzzle/build/',
			'guzzlehttp/guzzle/docs/',
			'guzzlehttp/guzzle/src/Event/',
			'guzzlehttp/guzzle/src/Exception/CouldNotRewindStreamException.php',
			'guzzlehttp/guzzle/src/Exception/ParseException.php',
			'guzzlehttp/guzzle/src/Exception/SeekException.php',
			'guzzlehttp/guzzle/src/Exception/StateException.php',
			'guzzlehttp/guzzle/src/Exception/XmlParseException.php',
			'guzzlehttp/guzzle/src/Message/',
			'guzzlehttp/guzzle/src/Post/',
			'guzzlehttp/guzzle/src/Subscriber/',
			'guzzlehttp/guzzle/src/BatchResults.php',
			'guzzlehttp/guzzle/src/Collection.php',
			'guzzlehttp/guzzle/src/HasDataTrait.php',
			'guzzlehttp/guzzle/src/Mimetypes.php',
			'guzzlehttp/guzzle/src/Query.php',
			'guzzlehttp/guzzle/src/QueryParser.php',
			'guzzlehttp/guzzle/src/RequestFsm.php',
			'guzzlehttp/guzzle/src/RingBridge.php',
			'guzzlehttp/guzzle/src/ToArrayInterface.php',
			'guzzlehttp/guzzle/src/Transaction.php',
			'guzzlehttp/guzzle/src/UriTemplate.php',
			'guzzlehttp/guzzle/src/Url.php',
			'guzzlehttp/guzzle/tests/',
			'guzzlehttp/guzzle/.travis.yml',
			'guzzlehttp/guzzle/.php_cs',
			'guzzlehttp/guzzle/Dockerfile',
			'guzzlehttp/log-subscriber/',
			'guzzlehttp/oauth-subscriber/tests/',
			'guzzlehttp/oauth-subscriber/.gitignore',
			'guzzlehttp/oauth-subscriber/.travis.yml',
			'guzzlehttp/oauth-subscriber/README.rst',
			'guzzlehttp/oauth-subscriber/phpunit.xml.dist',
			'guzzlehttp/promises/src/functions.php',
			'guzzlehttp/promises/src/functions_include.php',
			'guzzlehttp/psr7/.github/',
			'guzzlehttp/psr7/src/functions.php',
			'guzzlehttp/psr7/src/functions_include.php',
			'guzzlehttp/psr7/.php_cs.dist',
			'guzzlehttp/ringphp/',
			'guzzlehttp/streams/',
			'klarna/',
			'leafo/',
			'psr/log/',
			'react/',
			'scssphp/scssphp/bin/',
			'scssphp/scssphp/src/Base/',
			'scssphp/scssphp/src/Block/',
			'scssphp/scssphp/src/Compiler/CachedResult.php',
			'scssphp/scssphp/src/Compiler/Environment.php',
			'scssphp/scssphp/src/Exception/CompilerException.php',
			'scssphp/scssphp/src/Exception/ParserException.php',
			'scssphp/scssphp/src/Exception/RangeException.php',
			'scssphp/scssphp/src/Exception/ServerException.php',
			'scssphp/scssphp/src/Formatter/',
			'scssphp/scssphp/src/SourceMap/SourceMapGenerator.php',
			'scssphp/scssphp/src/Block.php',
			'scssphp/scssphp/src/Cache.php',
			'scssphp/scssphp/src/Formatter.php',
			'scssphp/scssphp/src/Parser.php',
			'scssphp/scssphp/scss.inc.php',
			'symfony/polyfill-intl-idn/',
			'symfony/polyfill-intl-normalizer/',
			'symfony/polyfill-php72/',
			'symfony/translation/',
			'symfony/validator/Constraints/Collection/',
			'symfony/validator/Constraints/False.php',
			'symfony/validator/Constraints/FalseValidator.php',
			'symfony/validator/Constraints/Null.php',
			'symfony/validator/Constraints/NullValidator.php',
			'symfony/validator/Constraints/True.php',
			'symfony/validator/Constraints/TrueValidator.php',
			'symfony/validator/Context/LegacyExecutionContext.php',
			'symfony/validator/Context/LegacyExecutionContextFactory.php',
			'symfony/validator/Mapping/Cache/',
			'symfony/validator/Mapping/BlackholeMetadataFactory.php',
			'symfony/validator/Mapping/ClassMetadataFactory.php',
			'symfony/validator/Mapping/ElementMetadata.php',
			'symfony/validator/Test/ForwardCompatTestTrait.php',
			'symfony/validator/Tests/',
			'symfony/validator/Util/LegacyTranslatorProxy.php',
			'symfony/validator/Validator/LegacyValidator.php',
			'symfony/validator/Violation/LegacyConstraintViolationBuilder.php',
			'symfony/validator/.gitignore',
			'symfony/validator/ClassBasedInterface.php',
			'symfony/validator/DefaultTranslator.php',
			'symfony/validator/ExecutionContext.php',
			'symfony/validator/ExecutionContextInterface.php',
			'symfony/validator/GlobalExecutionContextInterface.php',
			'symfony/validator/MetadataFactoryInterface.php',
			'symfony/validator/MetadataInterface.php',
			'symfony/validator/PropertyMetadataContainerInterface.php',
			'symfony/validator/PropertyMetadataInterface.php',
			'symfony/validator/ValidationVisitor.php',
			'symfony/validator/ValidationVisitorInterface.php',
			'symfony/validator/Validator.php',
			'symfony/validator/ValidatorBuilderInterface.php',
			'symfony/validator/ValidatorInterface.php',
			'symfony/validator/phpunit.xml.dist',
			'twig/twig/doc/',
			'twig/twig/lib/',
			'twig/twig/src/Extension/InitRuntimeInterface.php',
			'twig/twig/src/Loader/ExistsLoaderInterface.php',
			'twig/twig/src/Loader/SourceContextLoaderInterface.php',
			'twig/twig/src/Node/SandboxedPrintNode.php',
			'twig/twig/src/Node/SpacelessNode.php',
			'twig/twig/src/NodeVisitor/MacroAutoImportNodeVisitor.php',
			'twig/twig/src/TokenParser/FilterTokenParser.php',
			'twig/twig/src/TokenParser/SpacelessTokenParser.php',
			'twig/twig/.editorconfig',
			'twig/twig/.gitattributes',
			'twig/twig/.gitignore',
			'twig/twig/.php_cs.dist',
			'twig/twig/.travis.yml',
			'twig/twig/drupal_test.sh',
			'zoujingli/wechat-developer/MIT-LICENSE.txt',
			'zoujingli/wechat-developer/.gitignore',
			'zoujingli/wechat-php-sdk/.gitignore',
			'scss.inc.php'
		);
		if ($dir_current_vendor != $dir_vendor) {
			$this->mergeDirectories($dir_vendor,$dir_current_vendor);
		}
		$this->deleteObsoletes($dir_vendor,$obsoletes);
		if ($dir_current_vendor != $dir_vendor) {
			$this->deleteObsoletes($dir_current_vendor,$obsoletes);
		}

		// clear modification folder
		$this->deleteEntries($dir_storage.'/modification/*/*');
		if ($dir_current_storage != $dir_storage) {
			$this->deleteEntries($dir_current_storage.'/modification/*/*');
		}

		// various DB changes and fixes
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."googleshopping_target';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `".DB_PREFIX."googleshopping_target` (";
			$sql .= "  `advertise_google_target_id` int(11) UNSIGNED NOT NULL,";
			$sql .= "  `store_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `campaign_name` varchar(255) NOT NULL DEFAULT '',";
			$sql .= "  `country` varchar(2) NOT NULL DEFAULT '',";
			$sql .= "  `budget` decimal(15,4) NOT NULL DEFAULT '0.0000',";
			$sql .= "  `feeds` text NOT NULL,";
			$sql .= "  `status` enum('paused','active') NOT NULL DEFAULT 'paused',";
			$sql .= "  `date_added` DATE,";
			$sql .= "  `roas` INT(11) NOT NULL DEFAULT '0',";
			$sql .= "  PRIMARY KEY (`advertise_google_target_id`),";
			$sql .= "  KEY `store_id` (`store_id`)";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->db->query( $sql );
		}
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."googleshopping_category';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `".DB_PREFIX."googleshopping_category` (";
			$sql .= "  `google_product_category` varchar(10) NOT NULL,";
			$sql .= "  `store_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `category_id` int(11) NOT NULL,";
			$sql .= "  PRIMARY KEY (`google_product_category`,`store_id`),";
			$sql .= "  KEY `category_id_store_id` (`category_id`,`store_id`)";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->db->query( $sql );
		}
		$this->db->query("ALTER TABLE `".DB_PREFIX."event` MODIFY `sort_order` int(3) NOT NULL DEFAULT '0';");
		$this->db->query("UPDATE `".DB_PREFIX."event` SET `trigger`='catalog/model/checkout/order/addOrderHistory/before' WHERE `code`='activity_order_add' AND `trigger`='catalog/model/checkout/order/addOrderHistory/after';");
		$this->db->query("UPDATE `".DB_PREFIX."event` SET `trigger`='admin/model/sale/return/addReturnHistory/after' WHERE `code`='admin_mail_return' AND `trigger`='admin/model/sale/return/addReturn/after';");
		$query = $this->db->query("SELECT COUNT(*) AS count_advertise_google FROM `".DB_PREFIX."event` WHERE `code`='advertise_google';");
		if ($query->row['count_advertise_google'] != '12') {
			$this->db->query("DELETE FROM `".DB_PREFIX."event` WHERE `code`='advertise_google';");
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'admin/model/catalog/product/deleteProduct/after', 'extension/advertise/google/deleteProduct', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'admin/model/catalog/product/copyProduct/after', 'extension/advertise/google/copyProduct', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'admin/view/common/column_left/before', 'extension/advertise/google/admin_link', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'admin/model/catalog/product/addProduct/after', 'extension/advertise/google/addProduct', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/controller/checkout/success/before', 'extension/advertise/google/before_checkout_success', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/common/header/after', 'extension/advertise/google/google_global_site_tag', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/common/success/after', 'extension/advertise/google/google_dynamic_remarketing_purchase', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/product/product/after', 'extension/advertise/google/google_dynamic_remarketing_product', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/product/search/after', 'extension/advertise/google/google_dynamic_remarketing_searchresults', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/product/category/after', 'extension/advertise/google/google_dynamic_remarketing_category', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/common/home/after', 'extension/advertise/google/google_dynamic_remarketing_home', 1, 0);";
			$this->db->query($sql);
			$sql  = "INSERT INTO `".DB_PREFIX."event` (`code`, `trigger`, `action`, `status`, `sort_order`) VALUES ";
			$sql .= "('advertise_google', 'catalog/view/checkout/cart/after', 'extension/advertise/google/google_dynamic_remarketing_cart', 1, 0);";
			$this->db->query($sql);
		}
		$query = $this->db->query("SELECT COUNT(*) AS count_advertise_google FROM `".DB_PREFIX."extension` WHERE `type`='advertise' AND `code`='google';");
		if ($query->row['count_advertise_google'] == 0) {
			$query = $this->db->query("INSERT INTO `".DB_PREFIX."extension` (`type`, `code`) VALUES ('advertise', 'google');");
		}
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."googleshopping_product';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `".DB_PREFIX."googleshopping_product` (";
			$sql .= "  `product_advertise_google_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,";
			$sql .= "  `product_id` int(11) DEFAULT NULL,";
			$sql .= "  `store_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `has_issues` tinyint(1) DEFAULT NULL,";
			$sql .= "  `destination_status` enum('pending','approved','disapproved') NOT NULL DEFAULT 'pending',";
			$sql .= "  `impressions` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `clicks` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `conversions` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `cost` decimal(15,4) NOT NULL DEFAULT '0.0000',";
			$sql .= "  `conversion_value` decimal(15,4) NOT NULL DEFAULT '0.0000',";
			$sql .= "  `google_product_category` varchar(10) DEFAULT NULL,";
			$sql .= "  `condition` enum('new','refurbished','used') DEFAULT NULL,";
			$sql .= "  `adult` tinyint(1) DEFAULT NULL,";
			$sql .= "  `multipack` int(11) DEFAULT NULL,";
			$sql .= "  `is_bundle` tinyint(1) DEFAULT NULL,";
			$sql .= "  `age_group` enum('newborn','infant','toddler','kids','adult') DEFAULT NULL,";
			$sql .= "  `color` int(11) DEFAULT NULL,";
			$sql .= "  `gender` enum('male','female','unisex') DEFAULT NULL,";
			$sql .= "  `size_type` enum('regular','petite','plus','big and tall','maternity') DEFAULT NULL,";
			$sql .= "  `size_system` enum('AU','BR','CN','DE','EU','FR','IT','JP','MEX','UK','US') DEFAULT NULL,";
			$sql .= "  `size` int(11) DEFAULT NULL,";
			$sql .= "  `is_modified` tinyint(1) NOT NULL DEFAULT '0',";
			$sql .= "  PRIMARY KEY (`product_advertise_google_id`),";
			$sql .= "  UNIQUE KEY `product_id_store_id` (`product_id`,`store_id`)";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->db->query( $sql );
		}
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."googleshopping_product_status';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `googleshopping_product_status` (";
			$sql .= "  `product_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `store_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `product_variation_id` varchar(64) NOT NULL DEFAULT '',";
			$sql .= "  `destination_statuses` text NOT NULL,";
			$sql .= "  `data_quality_issues` text NOT NULL,";
			$sql .= "  `item_level_issues` text NOT NULL,";
			$sql .= "  `google_expiration_date` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  PRIMARY KEY (`product_id`,`store_id`,`product_variation_id`)";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->db->query( $sql );
		}
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."googleshopping_product_target';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `".DB_PREFIX."googleshopping_product_target` (";
			$sql .= "  `product_id` int(11) NOT NULL,";
			$sql .= "  `store_id` int(11) NOT NULL DEFAULT '0',";
			$sql .= "  `advertise_google_target_id` int(11) UNSIGNED NOT NULL,";
			$sql .= "  PRIMARY KEY (`product_id`,`advertise_google_target_id`)";
			$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8;";
			$this->db->query( $sql );
		}
		$query = $this->db->query("SHOW TABLES LIKE '".DB_PREFIX."session';");
		if (empty($query->row)) {
			$sql  = "CREATE TABLE `".DB_PREFIX."session` (";
			$sql .= "  `session_id` varchar(32) NOT NULL,";
			$sql .= "  `data` text NOT NULL,";
			$sql .= "  `expire` datetime NOT NULL,";
			$sql .= "  PRIMARY KEY (`session_id`)";
			$sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
			$this->db->query( $sql );
		}
		$this->db->query("UPDATE `".DB_PREFIX."setting` SET `code`='total_voucher' WHERE `code`='voucher';");
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `key`='config_timezone';");
		if (empty($query->row)) {
			$this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'config', 'config_timezone', 'UTC', 0);");
		}
		$this->db->query("UPDATE `".DB_PREFIX."setting` SET `value`= CONCAT('INV-', YEAR(CURDATE()), '-00') WHERE `code`='config_invoice_prefix' AND `value`='INV-2013-00';");
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `key`='config_file_ext_allowed';");
		$config_file_ext_allowed = isset($query->row['value']) ? $query->row['value'] : '';
		if ($config_file_ext_allowed) {
			$values = explode("\r\n",$config_file_ext_allowed);
			if (!in_array('webp',$values)) {
				$pos = array_search('zip',$values);
				if ($pos===false) {
					array_push($values,'webp');
				} else {
					array_splice($values,$pos,0,array('webp'));
				}
				$new_config_file_ext_allowed = implode("\r\n",$values);
				$this->db->query("UPDATE `".DB_PREFIX."setting` SET `value`='".$this->db->escape($new_config_file_ext_allowed)."' WHERE `key`='config_file_ext_allowed';");
			}
		} else {
			$new_config_file_ext_allowed = "zip\r\ntxt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nwebp\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc";
			$this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'config', 'config_file_ext_allowed', '".$this->db->escape($new_config_file_ext_allowed)."', 0);");
		}
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `key`='config_file_mime_allowed';");
		$config_file_mime_allowed = isset($query->row['value']) ? $query->row['value'] : '';
		if ($config_file_mime_allowed) {
			$values = explode("\r\n",$config_file_mime_allowed);
			if (!in_array('image/webp',$values)) {
				$pos = array_search('application/zip',$values);
				if ($pos===false) {
					array_push($values,'image/webp');
				} else {
					array_splice($values,$pos,0,array('image/webp'));
				}
				$new_config_file_mime_allowed = implode("\r\n",$values);
				$this->db->query("UPDATE `".DB_PREFIX."setting` SET `value`='".$this->db->escape($new_config_file_mime_allowed)."' WHERE `key`='config_file_mime_allowed';");
			}
		} else {
			$new_config_file_mime_allowed = "text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/tiff\r\nimage/svg+xml\r\nimage/webp\r\napplication/zip\r\n&quot;application/zip&quot;\r\napplication/x-zip\r\n&quot;application/x-zip&quot;\r\napplication/x-zip-compressed\r\n&quot;application/x-zip-compressed&quot;\r\napplication/rar\r\n&quot;application/rar&quot;\r\napplication/x-rar\r\n&quot;application/x-rar&quot;\r\napplication/x-rar-compressed\r\n&quot;application/x-rar-compressed&quot;\r\napplication/octet-stream\r\n&quot;application/octet-stream&quot;\r\naudio/mpeg\r\nvideo/quicktime\r\napplication/pdf";
			$this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'config', 'config_file_mime_allowed', '".$this->db->escape($new_config_file_mime_allowed)."', 0);");
		}
		$this->db->query("UPDATE `".DB_PREFIX."setting` SET `key`='payment_free_checkout_order_status_id' WHERE `key`='free_checkout_order_status_id';");
		$this->db->query("UPDATE `".DB_PREFIX."setting` SET `key`='total_sub_total_sort_order' WHERE `key`='sub_total_sort_order';");
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `code`='developer' AND `key`='developer_theme';");
		if (empty($query->row)) {
			$this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'developer', 'developer_theme', '1', 0);");
		}
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."setting` WHERE `code`='developer' AND `key`='developer_sass';");
		if (empty($query->row)) {
			$this->db->query("INSERT INTO `".DB_PREFIX."setting` (`store_id`, `code`, `key`, `value`, `serialized`) VALUES (0, 'developer', 'developer_sass', '1', 0);");
		}
 		$this->db->query("UPDATE `".DB_PREFIX."zone` SET `name`='Abū Z̧aby', `code`='AZ' WHERE `name`='Abu Dhabi';");
		$this->db->query("UPDATE `".DB_PREFIX."zone` SET `name`='‘Ajmān' WHERE `name`='''Ajman';");
		$this->db->query("UPDATE `".DB_PREFIX."zone` SET `name`='Ash Shāriqah' WHERE `name`='Ash Shariqah';");
		$this->db->query("UPDATE `".DB_PREFIX."zone` SET `name`='Ra’s al Khaymah' WHERE `name`='R''as al Khaymah';");

		// fixes for Google Shopping DB tables
		$this->fixColumnsForGoogleShopping();

		// drop some obsolete DB tables
		$dir_opencart = str_replace('\\', '/', realpath(DIR_OPENCART));
		$dir_excluded = $dir_opencart.'/install';
		if (!$this->filesContain($dir_opencart,$dir_excluded,'order_shipment')) {
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."order_shipment`;");
		}
		if (!$this->filesContain($dir_opencart,$dir_excluded,'shipping_courier')) {
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."shipping_courier`;");
		}

		// remove various obsolete extensions files
		$this->removeByName($dir_opencart,'divido');
		$this->removeByNameFromDB('divido');
		$this->removeByName($dir_opencart,'openbay');
		$this->removeByNameFromDB('openbay');
		$this->removeByName($dir_opencart,'klarna_checkout');
		$this->removeByNameFromDB('klarna_checkout');
//		$this->removeByName($dir_opencart,'ebay');
//		$this->removeByName($dir_opencart,'pp_');
		$this->removeByName($dir_opencart,'ups.php');
		$this->removeByName($dir_opencart,'ups.twig');
		$this->removeByNameFromDB('ups');
		$this->removeByName($dir_opencart,'citylink');
		$this->removeByNameFromDB('citylink');
		$this->removeByName($dir_opencart,'maxmind');

		// remove some other obsolete core files
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/jquery/owl-carousel');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/jquery/jquery-2.1.1.min.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/jquery/jquery-2.1.1.min.map');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ar-AR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-bg-BG.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ca-ES.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-cs-CZ.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-da-DK.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-de-DE.js');		
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-es-ES.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-es-EU.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-fa-IR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-fi-FI.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-fr-FR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-he-IL.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-hr-HR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-hu-HU.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-id-ID.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-it-IT.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ja-JP.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ko-KR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-lt-LT.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-lt-LV.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-nb-NO.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-nl-NL.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-pl-PL.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-pt-BR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-pt-PT.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ro-RO.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ru-RU.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-sk-SK.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-sl-SI.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-sr-RS-Latin.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-sr-RS.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-sv-SE.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-ta-IN.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-th-TH.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-tr-TR.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-uk-UA.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-uz-UZ.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-vi-VN.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-zh-CN.js');
		$this->deleteEntry($dir_opencart.'/admin/view/javascript/summernote/lang/summernote-zh-TW.js');
		$this->deleteEntry($dir_opencart.'/catalog/controller/information/tracking.php');
		$this->deleteEntries($dir_opencart.'/catalog/language/*/information/tracking.php');
		$this->deleteEntry($dir_opencart.'/catalog/view/javascript/jquery/owl-carousel');
		$this->deleteEntries($dir_opencart.'/catalog/view/theme/*/template/affiliate/transaction.twig');
		$this->deleteEntries($dir_opencart.'/catalog/view/theme/*/template/extension/module/amazon_pay.twig');
		$this->deleteEntries($dir_opencart.'/catalog/view/theme/*/template/extension/payment/amazon_login_pay_failure.twig');
		$this->deleteEntries($dir_opencart.'/catalog/view/theme/*/template/information/tracking.twig');
		$this->deleteEntry($dir_opencart.'/install/view/javascript/jquery/jquery-2.1.1.min.js');
		$this->deleteEntry($dir_opencart.'/install/view/javascript/jquery/jquery-2.1.1.min.map');
		$this->deleteEntry($dir_opencart.'/system/library/db/mpdo.php');
		$this->deleteEntry($dir_opencart.'/system/library/db/mpdo.php');
		$this->deleteEntry($dir_opencart.'/system/library/db/mssql.php');
		$this->deleteEntry($dir_opencart.'/system/library/db/mysql.php');
		$this->deleteEntry($dir_opencart.'/system/library/db/postgre.php');
		$this->deleteEntry($dir_opencart.'/system/library/template/Twig');
		$this->deleteEntry($dir_opencart.'/system/vendor');
	}

	private function removeByName(string $dir,string $name): bool {
		if (!file_exists($dir)) {
			return true;
		}

		if (!is_dir($dir)) {
			$file = $dir;
			if (strpos($file,$name)===false) {
				return true;
			}
			return @unlink($file);
		}

		foreach (@scandir($dir) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->removeByName($dir . '/' . $item, $name)) {
				return false;
			}
		}

		return (strpos($dir,$name)===false) ? true : @rmdir($dir);
	}

	private function deleteObsoletes(string $dir, array $obsoletes): void {
		foreach ($obsoletes as $obsolete) {
			$this->deleteEntry($dir.'/'.$obsolete);
		}
	}

	private function getCurrentStorageDirectory(): string {
		$current_dir_storage = '';
		if (is_file(DIR_OPENCART . 'config.php') && filesize(DIR_OPENCART . 'config.php') > 0) {
			$lines = file(DIR_OPENCART . 'config.php');
			foreach ($lines as $line) {
				if (strpos($line, "'DIR_STORAGE'") !== false) {
					$line = str_replace("'DIR_STORAGE'","'CURRENT_DIR_STORAGE'",$line);
					eval($line);
					$current_dir_storage = CURRENT_DIR_STORAGE;
					break;
				}
			}
		}
		return ($current_dir_storage!='') ? $current_dir_storage : DIR_STORAGE;
	}

	private function mergeDirectories(string $source, string $target): bool {
		if (!is_dir($source)) {
			return false;
		}

		// Create the target directory if it doesn't exist
		if (!is_dir($target)) {
			if (!@mkdir($target, 0755, true)) {
				return false;
			}
		}

		// Open the source directory
		$dir = @opendir($source);
		if ($dir === false) {
			return false;
		}

		// Loop through the files and folders in the source
		while (($file = @readdir($dir)) !== false) {
			// Skip '.' and '..' entries
			if ($file === '.' || $file === '..') {
				continue;
			}

			$source_path = $source . '/' . $file;
			$target_path = $target . '/' . $file;

			// If the item is a directory, recurse
			if (is_dir($source_path)) {
				if (!$this->mergeDirectories($source_path, $target_path)) {
					@closedir($dir);
					return false;
				}
			} else {
				// Otherwise, copy the file
				if (!copy($source_path, $target_path)) {
					@closedir($dir);
					return false;
				}
			}
		}

		@closedir($dir);
		return true;
	}

	private function deleteEntries(string $dir): bool {
		$paths = glob($dir,0);
		if ($paths===false) {
			return false;
		}

		foreach ($paths as $path) {
			$entry = str_replace('\\', '/', realpath($path));
			$this->deleteEntry($entry);
		}

		return true;
	}

	private function deleteEntry(string $entry): bool {
		if (!file_exists($entry)) {
			return true;
		}

		if (!is_dir($entry)) {
			return @unlink($entry);
		}

		$dir = $entry;
		foreach (@scandir($dir) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (!$this->deleteEntry($dir . '/' . $item)) {
				return false;
			}
		}

		return @rmdir($dir);
	}

	private function removeByNameFromDB( string $name ): void {
		$query = $this->db->query("SELECT * FROM `".DB_PREFIX."user_group`");
		foreach ($query->rows as $row) {
			try {
				$user_group_permission = json_decode($row['permission'],true);
			} catch (\Exception $e) {
				$user_group_permission = null;
			}
			$user_group_id = $row['user_group_id'];
			if (!empty($user_group_permission) && is_array($user_group_permission)) {
				$new_user_group_permission = $user_group_permission;
				foreach ($user_group_permission as $type=>$permission) {
					if (($type=='access') || ($type=='modify')) {
						if (!empty($permission) && is_array($permission)) {
							foreach ($permission as $key=>$val) {
								if (strpos($val,$name)===false) {
									continue;
								}
								unset($new_user_group_permission[$type][$key]);
							}
						}
					}
				}
				if (empty($new_user_group_permission['access']) && empty($new_user_group_permission['modify'])) {
					$this->db->query("UPDATE `".DB_PREFIX."user_group` SET `permission`='' WHERE user_group_id='".(int)$user_group_id."';");
				} else {
					$json_user_group_permission = json_encode($new_user_group_permission);
					$this->db->query("UPDATE `".DB_PREFIX."user_group` SET `permission`='".$this->db->escape($json_user_group_permission)."' WHERE user_group_id='".(int)$user_group_id."';");
				}
			}
		}
		$this->db->query("DELETE FROM `".DB_PREFIX."extension` WHERE `code` LIKE '%".$this->db->escape($name)."%';");
	}

    private function fixColumnsForGoogleShopping(): void {
		$has_auto_increment = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."googleshopping_product` WHERE Field='product_advertise_google_id' AND Extra LIKE '%auto_increment%';")->num_rows > 0;

		if (!$has_auto_increment) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_product` MODIFY COLUMN `product_advertise_google_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT;");
		}

		$has_unique_key = $this->db->query("SHOW INDEX FROM `" . DB_PREFIX . "googleshopping_product` WHERE Key_name='product_id_store_id' AND Non_unique=0")->num_rows == 2;

		if (!$has_unique_key) {
			$index_exists = $this->db->query("SHOW INDEX FROM `".DB_PREFIX."googleshopping_product` WHERE Key_name='product_id_store_id';")->num_rows > 0;
			if ($index_exists) {
				$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_product` DROP INDEX product_id_store_id;");
			}
			$this->db->query("CREATE UNIQUE INDEX product_id_store_id ON `".DB_PREFIX."googleshopping_product` (product_id, store_id);");
		}

		$has_date_added_column = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."googleshopping_target` WHERE Field='date_added';")->num_rows > 0;

		if (!$has_date_added_column) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_target` ADD COLUMN date_added DATE;");
			$this->db->query("UPDATE `".DB_PREFIX."googleshopping_target` SET `date_added` = NOW() WHERE `date_added` IS NULL;");
		}

		$has_roas_column = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."googleshopping_target` WHERE Field='roas'")->num_rows > 0;

		if (!$has_roas_column) {
			$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_target` ADD COLUMN `roas` INT(11) NOT NULL DEFAULT '0';");
		}

		$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_target` MODIFY `campaign_name` varchar(255) NOT NULL DEFAULT '';");
		$this->db->query("ALTER TABLE `".DB_PREFIX."googleshopping_target` MODIFY `country` varchar(2) NOT NULL DEFAULT '';");
    }

    private function filesContain( string $dir, string $dir_excluded, string $search ): bool {
		if ($dir=='') {
			return false;
		}
		if ($dir==$dir_excluded) {
			return false;
		}
		$tree = glob(rtrim($dir, '/') . '/*');
		if (is_array($tree)) {
			foreach($tree as $entry) {
				if (is_dir($entry)) {
					if ($this->endsWith($entry,'/.') || $this->endsWith($entry,'/..')) {
						continue;
					}
					if ($this->filesContain($entry,$dir_excluded,$search)) {
						return true;
					}
				} elseif (is_file($entry)) {
					if ($this->endsWith($entry,'.php')) {
						$contents = file_get_contents($entry);
						if (strpos($contents,$search)===false) {
							continue;
						}
						return true;
					}
				}
            }
        }
        return false;
    }

	private function endsWith( string $haystack, string $needle ): bool {
		if (strlen( $haystack ) < strlen( $needle )) {
			return false;
		}
		return (substr( $haystack, strlen($haystack)-strlen($needle), strlen($needle) ) == $needle);
	}

}
