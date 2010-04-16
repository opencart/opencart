# OPENCART UPGRADE SCRIPT
# WWW.OPENCART.COM
# Qphoria


# DO NOT RUN THIS ENTIRE FILE MANUALLY THROUGH PHPMYADMIN OR OTHER MYSQL DB TOOL
# THIS FILE IS GENERATED FOR USE WITH THE UPGRADE.PHP SCRIPT LOCATED IN THE INSTALL FOLDER
# THE UPGRADE.PHP SCRIPT IS DESIGNED TO VERIFY THE TABLES BEFORE EXECUTING WHICH PREVENTS ERRORS

# IF YOU NEED TO MANUALLY RUN THEN YOU CAN DO IT BY INDIVIDUAL VERSIONS. EACH SECTION IS LABELED.
# BE SURE YOU CHANGE THE PREFIX "oc_" TO YOUR PREFIX OR REMOVE IT IF NOT USING A PREFIX


### Start 1.3.2
SET FOREIGN_KEY_CHECKS = 0;

#
# DDL START
#
CREATE TABLE IF NOT EXISTS oc_customer_group (
    customer_group_id int(11) NOT NULL COMMENT '' auto_increment,
    name varchar(32) NOT NULL COMMENT '' COLLATE utf8_unicode_ci,
    PRIMARY KEY (customer_group_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS oc_measurement_class (
    measurement_class_id int(11) NOT NULL COMMENT '' auto_increment,
    language_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    title varchar(32) NOT NULL COMMENT '' COLLATE utf8_unicode_ci,
    unit varchar(4) NOT NULL COMMENT '' COLLATE utf8_unicode_ci,
    PRIMARY KEY (measurement_class_id, language_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS oc_measurement_rule (
    from_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    to_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    rule decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT ''
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE oc_customer ADD customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER status;
ALTER TABLE oc_customer MODIFY cart text NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
ALTER TABLE oc_customer ALTER ip SET DEFAULT 0;
#
#  Fieldformat of
#    oc_customer.cart changed from text NOT NULL COMMENT '' COLLATE utf8_unicode_ci to text NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_order_product DROP discount;


ALTER TABLE oc_product ADD measurement_class_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER height;
ALTER TABLE oc_product  ALTER length SET DEFAULT 0.00;
ALTER TABLE oc_product  ALTER width SET DEFAULT 0.00;
ALTER TABLE oc_product  ALTER height SET DEFAULT 0.00;


ALTER TABLE oc_product_discount ADD customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER product_id;
ALTER TABLE oc_product_discount ADD priority int(5) NOT NULL DEFAULT '1' COMMENT '' AFTER quantity;
ALTER TABLE oc_product_discount ADD price decimal(15,4) NOT NULL DEFAULT '0.0000' COMMENT '' AFTER priority;
ALTER TABLE oc_product_discount ADD date_start date NOT NULL DEFAULT '0000-00-00' COMMENT '' AFTER price;
ALTER TABLE oc_product_discount ADD date_end date NOT NULL DEFAULT '0000-00-00' COMMENT '' AFTER date_start;
ALTER TABLE oc_product_discount ALTER quantity SET DEFAULT 0;
ALTER TABLE oc_product_discount DROP discount;


ALTER TABLE oc_product_special ADD customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER product_id;
ALTER TABLE oc_product_special ADD priority int(5) NOT NULL DEFAULT '1' COMMENT '' AFTER customer_group_id;
ALTER TABLE oc_product_special ALTER price SET DEFAULT 0.0000;
ALTER TABLE oc_product_special ALTER date_start SET DEFAULT '0000-00-00';
ALTER TABLE oc_product_special ALTER date_end SET DEFAULT '0000-00-00';


#
# DDL END
#

SET FOREIGN_KEY_CHECKS = 1;


### Start 1.3.4
SET FOREIGN_KEY_CHECKS = 0;

#
# DDL START
#
ALTER TABLE oc_category MODIFY image varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    oc_category.image changed from varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_category_description MODIFY meta_description varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    oc_category_description.meta_description changed from varchar(66) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_coupon ADD logged int(1) NOT NULL DEFAULT 0 COMMENT '' AFTER discount;
ALTER TABLE oc_coupon ALTER date_start SET DEFAULT '0000-00-00';
ALTER TABLE oc_coupon ALTER date_end SET DEFAULT '0000-00-00';


ALTER TABLE oc_manufacturer MODIFY image varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    oc_manufacturer.image changed from varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_order ADD shipping_zone_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER shipping_zone;
ALTER TABLE oc_order ADD shipping_country_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER shipping_country;
ALTER TABLE oc_order ADD payment_zone_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER payment_zone;
ALTER TABLE oc_order ADD payment_country_id int(11) NOT NULL DEFAULT 0 COMMENT '' AFTER payment_country;


ALTER TABLE oc_order_option ADD product_option_value_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER order_product_id;

ALTER TABLE oc_product ADD sku varchar(64) NOT NULL COMMENT '' COLLATE utf8_unicode_ci AFTER model;
ALTER TABLE oc_product ADD location varchar(128) NOT NULL COMMENT '' COLLATE utf8_unicode_ci AFTER sku;
ALTER TABLE oc_product MODIFY model varchar(64) NOT NULL COMMENT '' COLLATE utf8_unicode_ci;
ALTER TABLE oc_product MODIFY image varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
ALTER TABLE oc_product ALTER measurement_class_id SET DEFAULT 0;
ALTER TABLE oc_product DROP sort_order;
#
#  Fieldformats of
#    oc_product.model changed from varchar(24) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(64) NOT NULL COMMENT '' COLLATE utf8_unicode_ci.
#    oc_product.image changed from varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_product_description MODIFY meta_description varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    oc_product_description.meta_description changed from varchar(66) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_product_image MODIFY image varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci;
#
#  Fieldformat of
#    oc_product_image.image changed from varchar(255) NOT NULL COMMENT '' COLLATE utf8_unicode_ci to varchar(255) NULL DEFAULT NULL COMMENT '' COLLATE utf8_unicode_ci.
#  Possibly data modifications needed!
#

ALTER TABLE oc_product_option_value ADD quantity int(4) NOT NULL DEFAULT '0' COMMENT '' AFTER product_id;
ALTER TABLE oc_product_option_value ADD subtract int(1) NOT NULL DEFAULT '0' COMMENT '' AFTER quantity;


#
# DDL END
#

SET FOREIGN_KEY_CHECKS = 1;


### Start 1.4.0
SET FOREIGN_KEY_CHECKS = 0;

#
# DDL START
#
ALTER TABLE oc_user DROP INDEX username;


#
# DDL END
#

SET FOREIGN_KEY_CHECKS = 1;


### Start 1.4.1
SET FOREIGN_KEY_CHECKS = 0;

#
# DDL START
#

DELETE FROM oc_extension WHERE `type` = 'module' AND `key` = 'currency';
DELETE FROM oc_setting WHERE `group` = 'currency';

CREATE TABLE IF NOT EXISTS oc_category_to_store (
    category_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    store_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (category_id, store_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_information_to_store (
    information_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    store_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (information_id, store_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_length_class (
    length_class_id int(11) NOT NULL COMMENT '' auto_increment,
    value decimal(15,8) NOT NULL COMMENT '',
    PRIMARY KEY (length_class_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


INSERT INTO `oc_length_class` (`length_class_id`, `value`) VALUES (1, '1.00000000') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;
INSERT INTO `oc_length_class` (`length_class_id`, `value`) VALUES (2, '10.00000000') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;
INSERT INTO `oc_length_class` (`length_class_id`, `value`) VALUES (3, '0.39370000') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;

CREATE TABLE IF NOT EXISTS oc_length_class_description (
    length_class_id int(11) NOT NULL COMMENT '' auto_increment,
    language_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    title varchar(32) NOT NULL COMMENT '' COLLATE utf8_bin,
    unit varchar(4) NOT NULL COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (length_class_id, language_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `oc_length_class_description` (`length_class_id`, `language_id`, `title`, `unit`) VALUES (1, 1, 'Centimeter', 'cm') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;
INSERT INTO `oc_length_class_description` (`length_class_id`, `language_id`, `title`, `unit`) VALUES (2, 1, 'Millimeter', 'mm') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;
INSERT INTO `oc_length_class_description` (`length_class_id`, `language_id`, `title`, `unit`) VALUES (3, 1, 'Inch', 'in') ON DUPLICATE KEY UPDATE length_class_id=length_class_id;

CREATE TABLE IF NOT EXISTS oc_manufacturer_to_store (
    manufacturer_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    store_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (manufacturer_id, store_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_product_to_store (
    product_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    store_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (product_id, store_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_store (
    store_id int(11) NOT NULL COMMENT '' auto_increment,
    name varchar(64) NOT NULL COMMENT '' COLLATE utf8_bin,
    url varchar(255) NOT NULL COMMENT '' COLLATE utf8_bin,
    title varchar(128) NOT NULL COMMENT '' COLLATE utf8_bin,
    meta_description varchar(255) NOT NULL COMMENT '' COLLATE utf8_bin,
    template varchar(64) NOT NULL COMMENT '' COLLATE utf8_bin,
    country_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    zone_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    language varchar(5) NOT NULL COMMENT '' COLLATE utf8_bin,
    currency varchar(3) NOT NULL COMMENT '' COLLATE utf8_bin,
    tax int(1) NOT NULL DEFAULT '0' COMMENT '',
    customer_group_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    customer_price int(1) NOT NULL DEFAULT 0 COMMENT '',
    customer_approval int(1) NOT NULL DEFAULT 0 COMMENT '',
    guest_checkout int(1) NOT NULL DEFAULT 0 COMMENT '',
    account_id int(11) NOT NULL DEFAULT '0' COMMENT '',
    checkout_id int(11) NOT NULL DEFAULT '0' COMMENT '',
    stock_display int(1) NOT NULL DEFAULT 0 COMMENT '',
    stock_check int(1) NOT NULL DEFAULT 0 COMMENT '',
    stock_checkout int(1) NOT NULL DEFAULT 0 COMMENT '',
    stock_subtract int(1) NOT NULL DEFAULT 0 COMMENT '',
    order_status_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    stock_status_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    logo varchar(255) NOT NULL COMMENT '' COLLATE utf8_bin,
    icon varchar(255) NOT NULL COMMENT '' COLLATE utf8_bin,
    image_thumb_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_thumb_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_popup_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_popup_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_category_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_category_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_product_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_product_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_additional_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_additional_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_related_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_related_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_cart_width int(5) NOT NULL DEFAULT 0 COMMENT '',
    image_cart_height int(5) NOT NULL DEFAULT 0 COMMENT '',
    PRIMARY KEY (store_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

CREATE TABLE IF NOT EXISTS oc_store_description (
    store_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    language_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    description text NOT NULL COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (store_id, language_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

DROP TABLE IF EXISTS `oc_weight_class`;

CREATE TABLE `oc_weight_class` (
  `weight_class_id` int(11) NOT NULL AUTO_INCREMENT,
  `value` decimal(15,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (`weight_class_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=7 ;

--
-- Dumping data for table `oc_weight_class`
--

INSERT INTO `oc_weight_class` (`weight_class_id`, `value`) VALUES (1, '1.00000000') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class` (`weight_class_id`, `value`) VALUES (2, '1000.00000000') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class` (`weight_class_id`, `value`) VALUES (5, '2.20460000') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class` (`weight_class_id`, `value`) VALUES (6, '35.27400000') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;

-- --------------------------------------------------------

--
-- Table structure for table `oc_weight_class_description`
--


CREATE TABLE IF NOT EXISTS oc_weight_class_description (
    weight_class_id int(11) NOT NULL COMMENT '' auto_increment,
    language_id int(11) NOT NULL DEFAULT 0 COMMENT '',
    title varchar(32) NOT NULL COMMENT '' COLLATE utf8_bin,
    unit varchar(4) NOT NULL COMMENT '' COLLATE utf8_bin,
    PRIMARY KEY (weight_class_id, language_id)
) DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `oc_weight_class_description` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES (1, 1, 'Kilogram', 'kg') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class_description` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES (2, 1, 'Gram', 'g') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class_description` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES (5, 1, 'Pound ', 'lb') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;
INSERT INTO `oc_weight_class_description` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES (6, 1, 'Ounce', 'oz') ON DUPLICATE KEY UPDATE weight_class_id=weight_class_id;

UPDATE `oc_product` SET `weight_class_id` = '5' WHERE `weight_class_id` = '3';
UPDATE `oc_product` SET `weight_class_id` = '6' WHERE `weight_class_id` = '4';

ALTER TABLE oc_address DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_category DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_category_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_country DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_coupon DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_coupon_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_coupon_product DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_currency DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_customer ADD approved int(1) NOT NULL DEFAULT '0' COMMENT '' AFTER status, DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_customer_group DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_download DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_download_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_extension DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_geo_zone DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_information DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_information_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_language DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_manufacturer DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS oc_measurement_class;

DROP TABLE IF EXISTS oc_measurement_rule;

ALTER TABLE oc_order ADD store_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER order_id;
ALTER TABLE oc_order ADD store_name varchar(64) NOT NULL COMMENT '' COLLATE utf8_bin AFTER store_id;
ALTER TABLE oc_order ADD store_url varchar(255) NOT NULL COMMENT '' COLLATE utf8_bin AFTER store_name;
ALTER TABLE oc_order ADD customer_group_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER customer_id;
ALTER TABLE oc_order ALTER customer_id SET DEFAULT 0, DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_download DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_history DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_option DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_product DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_status DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_order_total DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product ADD length_class_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER height;
ALTER TABLE oc_product DROP measurement_class_id, DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_discount DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_image DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_option DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_option_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_option_value DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_option_value_description DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_related DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_special DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_to_category DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_product_to_download DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_review DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_setting DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_stock_status DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_tax_class DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_tax_rate DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_url_alias DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_user DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_user_group DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_weight_class ADD value decimal(15,8) NOT NULL DEFAULT '0.00000000' COMMENT '' AFTER weight_class_id;
ALTER TABLE oc_weight_class DROP language_id;
ALTER TABLE oc_weight_class DROP title;
ALTER TABLE oc_weight_class DROP unit;
ALTER TABLE oc_weight_class DROP PRIMARY KEY;
ALTER TABLE oc_weight_class ADD PRIMARY KEY (weight_class_id), DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS oc_weight_rule;

ALTER TABLE oc_zone DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


ALTER TABLE oc_zone_to_geo_zone DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


#
# DDL END
#

SET FOREIGN_KEY_CHECKS = 1;


### Start 1.4.3
ALTER TABLE oc_store ADD `ssl` int(1) NOT NULL DEFAULT '0' COMMENT '' AFTER url;

	
### Start 1.4.5
ALTER TABLE oc_customer ADD store_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER customer_id;


ALTER TABLE oc_order ADD invoice_id int(11) NOT NULL DEFAULT '0' COMMENT '' AFTER order_id;
ALTER TABLE oc_order ADD invoice_prefix varchar(10) NOT NULL COMMENT '' COLLATE utf8_bin AFTER invoice_id;


ALTER TABLE oc_store ALTER `ssl` DROP DEFAULT;
ALTER TABLE oc_store DROP stock_status_id;


### Start 1.4.6

INSERT INTO `oc_setting` (`setting_id` ,`group` ,`key` ,`value`) VALUES (NULL , 'config', 'config_admin_language', 'en') ON DUPLICATE KEY UPDATE setting_id=setting_id;

### Start 1.4.7

ALTER TABLE `oc_country` ADD `status` INT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `oc_zone` ADD `status` INT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `oc_category` ADD `status` INT( 1 ) NOT NULL DEFAULT '1';

ALTER TABLE `oc_information` ADD `status` INT( 1 ) NOT NULL DEFAULT '1';


CREATE TABLE IF NOT EXISTS `oc_product_featured` (
  `product_id` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `oc_product_tags` (
  `product_id` int(11) NOT NULL,
  `tag` varchar(32) NOT NULL,
  `language_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`,`tag`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `oc_setting` (`setting_id`, `group`, `key`, `value`) VALUES (NULL, 'config', 'config_catalog_limit', '12') ON DUPLICATE KEY UPDATE setting_id=setting_id;
INSERT INTO `oc_setting` (`setting_id`, `group`, `key`, `value`) VALUES (NULL, 'config', 'config_admin_limit', '20') ON DUPLICATE KEY UPDATE setting_id=setting_id;

ALTER TABLE `oc_store` ADD `catalog_limit` INT( 4 ) NOT NULL DEFAULT '12';
ALTER TABLE `oc_store` ADD `cart_weight` INT( 1 ) NOT NULL;