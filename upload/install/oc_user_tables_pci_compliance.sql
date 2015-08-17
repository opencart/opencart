-- --------------------------------------------------------
--
-- Table structure for table `oc_admin_access_log`
--
DROP TABLE IF EXISTS `oc_admin_access_log`;
CREATE TABLE IF NOT EXISTS `oc_admin_access_log` (
  `admin_access_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `accessed_record_id` int(11) unsigned NOT NULL,
  `comment` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`admin_access_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;

-- --------------------------------------------------------
--
-- Custom table structure for `oc_user`
--
DROP TABLE IF EXISTS `oc_user`;
CREATE TABLE IF NOT EXISTS `oc_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_group_id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` tinyblob NOT NULL,
  `security_answer` tinyblob NOT NULL,
  `firstname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `lastname` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `email` varchar(96) COLLATE utf8_bin NOT NULL DEFAULT '',
  `status` int(1) NOT NULL,
  `image` varchar(255) COLLATE utf8_bin NOT NULL,
  `code` varchar(40) COLLATE utf8_bin NOT NULL,
  `ip` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_password_expiration` datetime NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

INSERT INTO `oc_user` (`user_id`, `user_group_id`, `username`, `firstname`, `lastname`, `email`, `status`, `image`, `code`, `ip`, `date_added`, `date_modified`, `date_password_expiration`) VALUES
(1, 1, 'aDministrator1', 'Admin', 'Admin', 'mdutra@mdisys.net', 1, '', '', '127.0.0.1', '2011-03-16 22:41:46', '2015-05-17 07:34:16', '2015-05-22 00:00:00');

-- --------------------------------------------------------
--
-- Custom table structure for `oc_user_failed_login_log`
--
DROP TABLE IF EXISTS `oc_user_failed_login_log`;
CREATE TABLE IF NOT EXISTS `oc_user_failed_login_log` (
  `user_failed_login_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT '',
  `password` tinyblob NOT NULL,
  `security_answer` tinyblob NOT NULL,
  `ip` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_failed_login_attempt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_failed_login_log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `oc_user_group`
--
DROP TABLE IF EXISTS `oc_user_group`;
CREATE TABLE IF NOT EXISTS `oc_user_group` (
  `user_group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `permission` text NOT NULL,
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `oc_user_group`
--
INSERT INTO `oc_user_group` (`user_group_id`, `name`, `permission`) VALUES
(1, 'Top Administrator', 'a:2:{s:6:"access";a:92:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:14:"extension/feed";i:9;s:16:"extension/module";i:10;s:17:"extension/payment";i:11;s:18:"extension/shipping";i:12;s:15:"extension/total";i:13;s:16:"feed/google_base";i:14;s:19:"feed/google_sitemap";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/length_class";i:20;s:25:"localisation/order_status";i:21;s:25:"localisation/stock_status";i:22;s:22:"localisation/tax_class";i:23;s:25:"localisation/weight_class";i:24;s:17:"localisation/zone";i:25;s:17:"module/bestseller";i:26;s:11:"module/cart";i:27;s:15:"module/category";i:28;s:15:"module/featured";i:29;s:23:"module/google_analytics";i:30;s:18:"module/google_talk";i:31;s:18:"module/information";i:32;s:13:"module/latest";i:33;s:19:"module/manufacturer";i:34;s:14:"module/special";i:35;s:16:"payment/alertpay";i:36;s:24:"payment/authorizenet_aim";i:37;s:21:"payment/bank_transfer";i:38;s:14:"payment/cheque";i:39;s:11:"payment/cod";i:40;s:21:"payment/free_checkout";i:41;s:14:"payment/liqpay";i:42;s:20:"payment/moneybookers";i:43;s:15:"payment/paymate";i:44;s:16:"payment/paypoint";i:45;s:26:"payment/perpetual_payments";i:46;s:18:"payment/pp_express";i:47;s:22:"payment/pp_payflow_pro";i:48;s:14:"payment/pp_pro";i:49;s:17:"payment/pp_pro_uk";i:50;s:19:"payment/pp_standard";i:51;s:15:"payment/sagepay";i:52;s:22:"payment/sagepay_direct";i:53;s:18:"payment/sagepay_us";i:54;s:19:"payment/twocheckout";i:55;s:16:"payment/worldpay";i:56;s:43:"report/daily_shoppingcart_events_by_product";i:57;s:42:"report/product_shoppingcart_sales_analysis";i:58;s:29:"report/products_sold_by_store";i:59;s:16:"report/purchased";i:60;s:11:"report/sale";i:61;s:13:"report/viewed";i:62;s:22:"sale/chart_of_accounts";i:63;s:12:"sale/contact";i:64;s:11:"sale/coupon";i:65;s:13:"sale/customer";i:66;s:19:"sale/customer_group";i:67;s:24:"sale/offers_mail_blaster";i:68;s:10:"sale/order";i:69;s:15:"setting/setting";i:70;s:13:"setting/store";i:71;s:17:"shipping/citylink";i:72;s:13:"shipping/flat";i:73;s:13:"shipping/free";i:74;s:13:"shipping/item";i:75;s:23:"shipping/parcelforce_48";i:76;s:15:"shipping/pickup";i:77;s:19:"shipping/royal_mail";i:78;s:12:"shipping/ups";i:79;s:13:"shipping/usps";i:80;s:15:"shipping/weight";i:81;s:11:"tool/backup";i:82;s:14:"tool/error_log";i:83;s:12:"total/coupon";i:84;s:14:"total/handling";i:85;s:19:"total/low_order_fee";i:86;s:14:"total/shipping";i:87;s:15:"total/sub_total";i:88;s:9:"total/tax";i:89;s:11:"total/total";i:90;s:9:"user/user";i:91;s:20:"user/user_permission";}s:6:"modify";a:92:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:14:"extension/feed";i:9;s:16:"extension/module";i:10;s:17:"extension/payment";i:11;s:18:"extension/shipping";i:12;s:15:"extension/total";i:13;s:16:"feed/google_base";i:14;s:19:"feed/google_sitemap";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/length_class";i:20;s:25:"localisation/order_status";i:21;s:25:"localisation/stock_status";i:22;s:22:"localisation/tax_class";i:23;s:25:"localisation/weight_class";i:24;s:17:"localisation/zone";i:25;s:17:"module/bestseller";i:26;s:11:"module/cart";i:27;s:15:"module/category";i:28;s:15:"module/featured";i:29;s:23:"module/google_analytics";i:30;s:18:"module/google_talk";i:31;s:18:"module/information";i:32;s:13:"module/latest";i:33;s:19:"module/manufacturer";i:34;s:14:"module/special";i:35;s:16:"payment/alertpay";i:36;s:24:"payment/authorizenet_aim";i:37;s:21:"payment/bank_transfer";i:38;s:14:"payment/cheque";i:39;s:11:"payment/cod";i:40;s:21:"payment/free_checkout";i:41;s:14:"payment/liqpay";i:42;s:20:"payment/moneybookers";i:43;s:15:"payment/paymate";i:44;s:16:"payment/paypoint";i:45;s:26:"payment/perpetual_payments";i:46;s:18:"payment/pp_express";i:47;s:22:"payment/pp_payflow_pro";i:48;s:14:"payment/pp_pro";i:49;s:17:"payment/pp_pro_uk";i:50;s:19:"payment/pp_standard";i:51;s:15:"payment/sagepay";i:52;s:22:"payment/sagepay_direct";i:53;s:18:"payment/sagepay_us";i:54;s:19:"payment/twocheckout";i:55;s:16:"payment/worldpay";i:56;s:43:"report/daily_shoppingcart_events_by_product";i:57;s:42:"report/product_shoppingcart_sales_analysis";i:58;s:29:"report/products_sold_by_store";i:59;s:16:"report/purchased";i:60;s:11:"report/sale";i:61;s:13:"report/viewed";i:62;s:22:"sale/chart_of_accounts";i:63;s:12:"sale/contact";i:64;s:11:"sale/coupon";i:65;s:13:"sale/customer";i:66;s:19:"sale/customer_group";i:67;s:24:"sale/offers_mail_blaster";i:68;s:10:"sale/order";i:69;s:15:"setting/setting";i:70;s:13:"setting/store";i:71;s:17:"shipping/citylink";i:72;s:13:"shipping/flat";i:73;s:13:"shipping/free";i:74;s:13:"shipping/item";i:75;s:23:"shipping/parcelforce_48";i:76;s:15:"shipping/pickup";i:77;s:19:"shipping/royal_mail";i:78;s:12:"shipping/ups";i:79;s:13:"shipping/usps";i:80;s:15:"shipping/weight";i:81;s:11:"tool/backup";i:82;s:14:"tool/error_log";i:83;s:12:"total/coupon";i:84;s:14:"total/handling";i:85;s:19:"total/low_order_fee";i:86;s:14:"total/shipping";i:87;s:15:"total/sub_total";i:88;s:9:"total/tax";i:89;s:11:"total/total";i:90;s:9:"user/user";i:91;s:20:"user/user_permission";}}'),
(10, 'Demonstration', 'a:1:{s:6:"access";a:12:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:19:"catalog/information";i:3;s:20:"catalog/manufacturer";i:4;s:15:"catalog/product";i:5;s:14:"catalog/review";i:6;s:18:"common/filemanager";i:7;s:25:"localisation/order_status";i:8;s:25:"localisation/stock_status";i:9;s:22:"localisation/tax_class";i:10;s:14:"payment/liqpay";i:11;s:20:"payment/moneybookers";}}'),
(11, 'SEO Administrator', 'a:2:{s:6:"access";a:40:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:16:"feed/google_base";i:9;s:19:"feed/google_sitemap";i:10;s:20:"localisation/country";i:11;s:21:"localisation/currency";i:12;s:21:"localisation/geo_zone";i:13;s:21:"localisation/language";i:14;s:25:"localisation/length_class";i:15;s:25:"localisation/order_status";i:16;s:25:"localisation/stock_status";i:17;s:22:"localisation/tax_class";i:18;s:25:"localisation/weight_class";i:19;s:17:"localisation/zone";i:20;s:17:"module/bestseller";i:21;s:11:"module/cart";i:22;s:15:"module/category";i:23;s:15:"module/featured";i:24;s:23:"module/google_analytics";i:25;s:18:"module/google_talk";i:26;s:13:"module/latest";i:27;s:43:"report/daily_shoppingcart_events_by_product";i:28;s:42:"report/product_shoppingcart_sales_analysis";i:29;s:29:"report/products_sold_by_store";i:30;s:16:"report/purchased";i:31;s:11:"report/sale";i:32;s:13:"report/viewed";i:33;s:22:"sale/chart_of_accounts";i:34;s:12:"sale/contact";i:35;s:11:"sale/coupon";i:36;s:13:"sale/customer";i:37;s:19:"sale/customer_group";i:38;s:24:"sale/offers_mail_blaster";i:39;s:10:"sale/order";}s:6:"modify";a:33:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:16:"feed/google_base";i:9;s:19:"feed/google_sitemap";i:10;s:17:"localisation/zone";i:11;s:17:"module/bestseller";i:12;s:11:"module/cart";i:13;s:15:"module/category";i:14;s:15:"module/featured";i:15;s:23:"module/google_analytics";i:16;s:18:"module/google_talk";i:17;s:13:"module/latest";i:18;s:19:"module/manufacturer";i:19;s:14:"module/special";i:20;s:43:"report/daily_shoppingcart_events_by_product";i:21;s:42:"report/product_shoppingcart_sales_analysis";i:22;s:29:"report/products_sold_by_store";i:23;s:16:"report/purchased";i:24;s:11:"report/sale";i:25;s:13:"report/viewed";i:26;s:22:"sale/chart_of_accounts";i:27;s:12:"sale/contact";i:28;s:11:"sale/coupon";i:29;s:13:"sale/customer";i:30;s:19:"sale/customer_group";i:31;s:24:"sale/offers_mail_blaster";i:32;s:10:"sale/order";}}'),
(12, 'Sales Team', 'a:2:{s:6:"access";a:19:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:43:"report/daily_shoppingcart_events_by_product";i:9;s:42:"report/product_shoppingcart_sales_analysis";i:10;s:29:"report/products_sold_by_store";i:11;s:16:"report/purchased";i:12;s:11:"report/sale";i:13;s:13:"report/viewed";i:14;s:12:"sale/contact";i:15;s:11:"sale/coupon";i:16;s:13:"sale/customer";i:17;s:19:"sale/customer_group";i:18;s:10:"sale/order";}s:6:"modify";a:19:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:43:"report/daily_shoppingcart_events_by_product";i:9;s:42:"report/product_shoppingcart_sales_analysis";i:10;s:29:"report/products_sold_by_store";i:11;s:16:"report/purchased";i:12;s:11:"report/sale";i:13;s:13:"report/viewed";i:14;s:12:"sale/contact";i:15;s:11:"sale/coupon";i:16;s:13:"sale/customer";i:17;s:19:"sale/customer_group";i:18;s:10:"sale/order";}}'),
(13, 'Catalog Team', 'a:2:{s:6:"access";a:10:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:11:"sale/coupon";i:9;s:13:"sale/customer";}s:6:"modify";a:8:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";}}'),
(14, 'Super Users', 'a:2:{s:6:"access";a:93:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:14:"extension/feed";i:9;s:16:"extension/module";i:10;s:17:"extension/payment";i:11;s:18:"extension/shipping";i:12;s:15:"extension/total";i:13;s:16:"feed/google_base";i:14;s:19:"feed/google_sitemap";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/length_class";i:20;s:25:"localisation/order_status";i:21;s:25:"localisation/stock_status";i:22;s:22:"localisation/tax_class";i:23;s:25:"localisation/weight_class";i:24;s:17:"localisation/zone";i:25;s:17:"module/bestseller";i:26;s:11:"module/cart";i:27;s:15:"module/category";i:28;s:15:"module/featured";i:29;s:23:"module/google_analytics";i:30;s:18:"module/google_talk";i:31;s:18:"module/information";i:32;s:13:"module/latest";i:33;s:19:"module/manufacturer";i:34;s:14:"module/special";i:35;s:16:"payment/alertpay";i:36;s:24:"payment/authorizenet_aim";i:37;s:21:"payment/bank_transfer";i:38;s:14:"payment/cheque";i:39;s:11:"payment/cod";i:40;s:21:"payment/free_checkout";i:41;s:14:"payment/liqpay";i:42;s:20:"payment/moneybookers";i:43;s:15:"payment/paymate";i:44;s:16:"payment/paypoint";i:45;s:26:"payment/perpetual_payments";i:46;s:18:"payment/pp_express";i:47;s:22:"payment/pp_payflow_pro";i:48;s:14:"payment/pp_pro";i:49;s:17:"payment/pp_pro_uk";i:50;s:19:"payment/pp_standard";i:51;s:15:"payment/sagepay";i:52;s:22:"payment/sagepay_direct";i:53;s:18:"payment/sagepay_us";i:54;s:19:"payment/twocheckout";i:55;s:16:"payment/worldpay";i:56;s:43:"report/daily_shoppingcart_events_by_product";i:57;s:42:"report/product_shoppingcart_sales_analysis";i:58;s:29:"report/products_sold_by_store";i:59;s:16:"report/purchased";i:60;s:11:"report/sale";i:61;s:13:"report/viewed";i:62;s:22:"sale/chart_of_accounts";i:63;s:12:"sale/contact";i:64;s:11:"sale/coupon";i:65;s:13:"sale/customer";i:66;s:19:"sale/customer_group";i:67;s:24:"sale/offers_mail_blaster";i:68;s:10:"sale/order";i:69;s:15:"setting/setting";i:70;s:13:"setting/store";i:71;s:17:"shipping/citylink";i:72;s:13:"shipping/flat";i:73;s:13:"shipping/free";i:74;s:13:"shipping/item";i:75;s:23:"shipping/parcelforce_48";i:76;s:15:"shipping/pickup";i:77;s:19:"shipping/royal_mail";i:78;s:12:"shipping/ups";i:79;s:13:"shipping/usps";i:80;s:15:"shipping/weight";i:81;s:11:"tool/backup";i:82;s:14:"tool/error_log";i:83;s:12:"total/coupon";i:84;s:27:"total/cuda_instant_discount";i:85;s:14:"total/handling";i:86;s:19:"total/low_order_fee";i:87;s:14:"total/shipping";i:88;s:15:"total/sub_total";i:89;s:9:"total/tax";i:90;s:11:"total/total";i:91;s:9:"user/user";i:92;s:20:"user/user_permission";}s:6:"modify";a:57:{i:0;s:16:"catalog/category";i:1;s:19:"catalog/distributor";i:2;s:16:"catalog/download";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:15:"catalog/product";i:6;s:14:"catalog/review";i:7;s:18:"common/filemanager";i:8;s:16:"feed/google_base";i:9;s:19:"feed/google_sitemap";i:10;s:20:"localisation/country";i:11;s:21:"localisation/currency";i:12;s:21:"localisation/geo_zone";i:13;s:21:"localisation/language";i:14;s:25:"localisation/length_class";i:15;s:25:"localisation/order_status";i:16;s:25:"localisation/stock_status";i:17;s:22:"localisation/tax_class";i:18;s:25:"localisation/weight_class";i:19;s:17:"localisation/zone";i:20;s:17:"module/bestseller";i:21;s:11:"module/cart";i:22;s:15:"module/category";i:23;s:15:"module/featured";i:24;s:23:"module/google_analytics";i:25;s:18:"module/google_talk";i:26;s:18:"module/information";i:27;s:13:"module/latest";i:28;s:19:"module/manufacturer";i:29;s:14:"module/special";i:30;s:43:"report/daily_shoppingcart_events_by_product";i:31;s:42:"report/product_shoppingcart_sales_analysis";i:32;s:29:"report/products_sold_by_store";i:33;s:16:"report/purchased";i:34;s:11:"report/sale";i:35;s:13:"report/viewed";i:36;s:22:"sale/chart_of_accounts";i:37;s:12:"sale/contact";i:38;s:11:"sale/coupon";i:39;s:13:"sale/customer";i:40;s:19:"sale/customer_group";i:41;s:10:"sale/order";i:42;s:13:"setting/store";i:43;s:17:"shipping/citylink";i:44;s:13:"shipping/flat";i:45;s:13:"shipping/free";i:46;s:13:"shipping/item";i:47;s:23:"shipping/parcelforce_48";i:48;s:15:"shipping/pickup";i:49;s:19:"shipping/royal_mail";i:50;s:12:"shipping/ups";i:51;s:13:"shipping/usps";i:52;s:15:"shipping/weight";i:53;s:11:"tool/backup";i:54;s:14:"tool/error_log";i:55;s:9:"user/user";i:56;s:20:"user/user_permission";}}');


-- --------------------------------------------------------
--
-- Custom table structure for `oc_user_login_log`
--
DROP TABLE IF EXISTS `oc_user_login_log`;
CREATE TABLE IF NOT EXISTS `oc_user_login_log` (
  `user_login_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ip` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT '',
  `date_logged_in` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_logged_out` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_login_log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Custom table structure for `oc_user_password_history`
--
DROP TABLE IF EXISTS `oc_user_password_history`;
CREATE TABLE IF NOT EXISTS `oc_user_password_history` (
  `user_password_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `password` tinyblob NOT NULL,
  `security_answer` tinyblob NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`user_password_history_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
