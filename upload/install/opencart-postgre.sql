-- --------------------------------------------------------

--
-- Database: opencart
--

-- --------------------------------------------------------

CREATE TABLE oc_product_profile (
  product_id integer NOT NULL,
  profile_id integer NOT NULL,
  customer_group_id integer NOT NULL,
  PRIMARY KEY (product_id,profile_id,customer_group_id)
);

DROP TYPE IF EXISTS frequency_enum;
CREATE TYPE frequency_enum AS ENUM ('day','week','semi_month','month','year');

CREATE TABLE oc_profile (
  profile_id serial NOT NULL,
  sort_order integer NOT NULL,
  status smallint NOT NULL,
  price decimal(10,4) NOT NULL,
  frequency frequency_enum NOT NULL,
  duration integer NOT NULL,
  cycle integer NOT NULL,
  trial_status smallint NOT NULL,
  trial_price decimal(10,4) NOT NULL,
  trial_frequency frequency_enum NOT NULL,
  trial_duration integer NOT NULL,
  trial_cycle integer NOT NULL,
  PRIMARY KEY (profile_id)
);


CREATE TABLE oc_profile_description (
  profile_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(255) NOT NULL,
  PRIMARY KEY (profile_id,language_id)
);

--
-- Table structure for table oc_order_recurring
--

DROP TABLE IF EXISTS oc_order_recurring;
CREATE TABLE oc_order_recurring (
  order_recurring_id serial NOT NULL,
  order_id integer NOT NULL,
  created timestamp without time zone NOT NULL,
  status smallint NOT NULL,
  product_id integer NOT NULL,
  product_name varchar(255) NOT NULL,
  product_quantity integer NOT NULL,
  profile_id integer NOT NULL,
  profile_name varchar(255) NOT NULL,
  profile_description varchar(255) NOT NULL,
  recurring_frequency varchar(25) NOT NULL,
  recurring_cycle smallint NOT NULL,
  recurring_duration smallint NOT NULL,
  recurring_price decimal(10,4) NOT NULL,
  trial boolean NOT NULL,
  trial_frequency varchar(25) NOT NULL,
  trial_cycle smallint NOT NULL,
  trial_duration smallint NOT NULL,
  trial_price decimal(10,4) NOT NULL,
  profile_reference varchar(255) NOT NULL,
  PRIMARY KEY (order_recurring_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_order_recurring_transaction
--

DROP TABLE IF EXISTS oc_order_recurring_transaction;
CREATE TABLE oc_order_recurring_transaction (
  order_recurring_transaction_id serial NOT NULL,
  order_recurring_id integer NOT NULL,
  created timestamp without time zone NOT NULL,
  amount decimal(10,4) NOT NULL,
  type varchar(255) NOT NULL,
  PRIMARY KEY (order_recurring_transaction_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_address
--

DROP TABLE IF EXISTS oc_address;
CREATE TABLE oc_address (
  address_id serial NOT NULL,
  customer_id integer NOT NULL,
  firstname varchar(32) NOT NULL,
  lastname varchar(32) NOT NULL,
  company varchar(40) NOT NULL,   
  address_1 varchar(128) NOT NULL,
  address_2 varchar(128) NOT NULL,
  city varchar(128) NOT NULL,
  postcode varchar(10) NOT NULL,
  country_id integer NOT NULL DEFAULT '0',
  zone_id integer NOT NULL DEFAULT '0',
  custom_field text NOT NULL,
  PRIMARY KEY (address_id)
);

CREATE INDEX idx_customer_id ON oc_address (customer_id);

-- --------------------------------------------------------

--
-- Table structure for table oc_affiliate
--

DROP TABLE IF EXISTS oc_affiliate;
CREATE TABLE oc_affiliate (
  affiliate_id serial NOT NULL,
  firstname varchar(32) NOT NULL,
  lastname varchar(32) NOT NULL,
  email varchar(96) NOT NULL,
  telephone varchar(32) NOT NULL,
  fax varchar(32) NOT NULL,
  password varchar(40) NOT NULL,
  salt varchar(9) NOT NULL,
  company varchar(40) NOT NULL,
  website varchar(255) NOT NULL,
  address_1 varchar(128) NOT NULL,
  address_2 varchar(128) NOT NULL,
  city varchar(128) NOT NULL,
  postcode varchar(10) NOT NULL,
  country_id integer NOT NULL,
  zone_id integer NOT NULL,
  code varchar(64) NOT NULL,
  commission decimal(4,2) NOT NULL DEFAULT '0.00',
  tax varchar(64) NOT NULL,
  payment varchar(6) NOT NULL,
  cheque varchar(100) NOT NULL,
  paypal varchar(64) NOT NULL,
  bank_name varchar(64) NOT NULL,
  bank_branch_number varchar(64) NOT NULL,
  bank_swift_code varchar(64) NOT NULL,
  bank_account_name varchar(64) NOT NULL,
  bank_account_number varchar(64) NOT NULL,
  ip varchar(40) NOT NULL,
  status boolean NOT NULL,
  approved boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (affiliate_id)
);

-- --------------------------------------------------------
--
-- Table structure for table oc_affiliate_activity
--

DROP TABLE IF EXISTS oc_affiliate_activity;
CREATE TABLE oc_affiliate_activity (
  activity_id serial NOT NULL,
  affiliate_id integer NOT NULL,
  key varchar(64) NOT NULL,
  data text NOT NULL,
  ip varchar(40) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (activity_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_affiliate_transaction
--

DROP TABLE IF EXISTS oc_affiliate_transaction;
CREATE TABLE oc_affiliate_transaction (
  affiliate_transaction_id serial NOT NULL,
  affiliate_id integer NOT NULL,
  order_id integer NOT NULL,
  description text NOT NULL,
  amount decimal(15,4) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (affiliate_transaction_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_attribute
--

DROP TABLE IF EXISTS oc_attribute;
CREATE TABLE oc_attribute (
  attribute_id serial NOT NULL,
  attribute_group_id integer NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (attribute_id)
);

--
-- Dumping data for table oc_attribute
--

INSERT INTO oc_attribute (attribute_id, attribute_group_id, sort_order) VALUES
(1, 6, 1),
(2, 6, 5),
(3, 6, 3),
(4, 3, 1),
(5, 3, 2),
(6, 3, 3),
(7, 3, 4),
(8, 3, 5),
(9, 3, 6),
(10, 3, 7),
(11, 3, 8);

-- --------------------------------------------------------

--
-- Table structure for table oc_attribute_description
--

DROP TABLE IF EXISTS oc_attribute_description;
CREATE TABLE oc_attribute_description (
  attribute_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (attribute_id,language_id)
);

--
-- Dumping data for table oc_attribute_description
--

INSERT INTO oc_attribute_description (attribute_id, language_id, name) VALUES
(1, 1, 'Description'),
(2, 1, 'No. of Cores'),
(4, 1, 'test 1'),
(5, 1, 'test 2'),
(6, 1, 'test 3'),
(7, 1, 'test 4'),
(8, 1, 'test 5'),
(9, 1, 'test 6'),
(10, 1, 'test 7'),
(11, 1, 'test 8'),
(3, 1, 'Clockspeed');

-- --------------------------------------------------------

--
-- Table structure for table oc_attribute_group
--

DROP TABLE IF EXISTS oc_attribute_group;
CREATE TABLE oc_attribute_group (
  attribute_group_id serial NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (attribute_group_id)
);

--
-- Dumping data for table oc_attribute_group
--

INSERT INTO oc_attribute_group (attribute_group_id, sort_order) VALUES
(3, 2),
(4, 1),
(5, 3),
(6, 4);

-- --------------------------------------------------------

--
-- Table structure for table oc_attribute_group_description
--

DROP TABLE IF EXISTS oc_attribute_group_description;
CREATE TABLE oc_attribute_group_description (
  attribute_group_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (attribute_group_id,language_id)
);

--
-- Dumping data for table oc_attribute_group_description
--

INSERT INTO oc_attribute_group_description (attribute_group_id, language_id, name) VALUES
(3, 1, 'Memory'),
(4, 1, 'Technical'),
(5, 1, 'Motherboard'),
(6, 1, 'Processor');

-- --------------------------------------------------------

--
-- Table structure for table oc_banner
--

DROP TABLE IF EXISTS oc_banner;
CREATE TABLE oc_banner (
  banner_id serial NOT NULL,
  name varchar(64) NOT NULL,
  status boolean NOT NULL,
  PRIMARY KEY (banner_id)
);

--
-- Dumping data for table oc_banner
--

INSERT INTO oc_banner (banner_id, name, status) VALUES
(6, 'HP Products', TRUE),
(7, 'Samsung Tab', TRUE),
(8, 'Manufacturers', TRUE);

-- --------------------------------------------------------

--
-- Table structure for table oc_banner_image
--

DROP TABLE IF EXISTS oc_banner_image;
CREATE TABLE oc_banner_image (
  banner_image_id serial NOT NULL,
  banner_id integer NOT NULL,
  link varchar(255) NOT NULL,
  image varchar(255) NOT NULL,
  sort_order integer NOT NULL DEFAULT '0',
  PRIMARY KEY (banner_image_id)
);

--
-- Dumping data for table oc_banner_image
--

INSERT INTO oc_banner_image (banner_image_id, banner_id, link, image, sort_order) VALUES
(54, 7, 'index.php?route=product/product&amp;path=57&amp;product_id=49', 'catalog/demo/samsung_banner.jpg', 0),
(77, 6, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', 'catalog/demo/hp_banner.jpg', 0),
(75, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=5', 'catalog/demo/htc_logo.jpg', 0),
(73, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=8', 'catalog/demo/apple_logo.jpg', 1),
(74, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=9', 'catalog/demo/canon_logo.jpg', 2),
(71, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=10', 'catalog/demo/sony_logo.jpg', 3),
(72, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=6', 'catalog/demo/palm_logo.jpg', 4),
(76, 8, 'index.php?route=product/manufacturer/info&amp;manufacturer_id=7', 'catalog/demo/hp_logo.jpg', 5);

-- --------------------------------------------------------

--
-- Table structure for table oc_banner_image_description
--

DROP TABLE IF EXISTS oc_banner_image_description;
CREATE TABLE oc_banner_image_description (
  banner_image_id integer NOT NULL,
  language_id integer NOT NULL,
  banner_id integer NOT NULL,
  title varchar(64) NOT NULL,
  PRIMARY KEY (banner_image_id,language_id)
);

--
-- Dumping data for table oc_banner_image_description
--

INSERT INTO oc_banner_image_description (banner_image_id, language_id, banner_id, title) VALUES
(54, 1, 7, 'Samsung Tab 10.1'),
(77, 1, 6, 'HP Banner'),
(75, 1, 8, 'HTC'),
(74, 1, 8, 'Canon'),
(73, 1, 8, 'Apple'),
(72, 1, 8, 'Palm'),
(71, 1, 8, 'Sony'),
(76, 1, 8, 'Hewlett-Packard');

-- --------------------------------------------------------

--
-- Table structure for table oc_category
--

DROP TABLE IF EXISTS oc_category;
CREATE TABLE oc_category (
  category_id serial NOT NULL,
  image varchar(255) DEFAULT NULL,
  parent_id integer NOT NULL DEFAULT '0',
  top boolean NOT NULL,
  "column" integer NOT NULL,
  sort_order integer NOT NULL DEFAULT '0',
  status boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (category_id)
);

CREATE INDEX idx_parent_id ON oc_category (parent_id);

--
-- Dumping data for table oc_category
--

INSERT INTO oc_category (category_id, image, parent_id, top, "column", sort_order, status, date_added, date_modified) VALUES
(25, '', 0, TRUE, 1, 3, TRUE, '2009-01-31 01:04:25', '2011-05-30 12:14:55'),
(27, '', 20, FALSE, 0, 2, TRUE, '2009-01-31 01:55:34', '2010-08-22 06:32:15'),
(20, 'catalog/demo/compaq_presario.jpg', 0, TRUE, 1, 1, TRUE, '2009-01-05 21:49:43', '2011-07-16 02:14:42'),
(24, '', 0, TRUE, 1, 5, TRUE, '2009-01-20 02:36:26', '2011-05-30 12:15:18'),
(18, 'catalog/demo/hp_2.jpg', 0, TRUE, 0, 2, TRUE, '2009-01-05 21:49:15', '2011-05-30 12:13:55'),
(17, '', 0, TRUE, 1, 4, TRUE, '2009-01-03 21:08:57', '2011-05-30 12:15:11'),
(28, '', 25, FALSE, 0, 1, TRUE, '2009-02-02 13:11:12', '2010-08-22 06:32:46'),
(26, '', 20, FALSE, 0, 1, TRUE, '2009-01-31 01:55:14', '2010-08-22 06:31:45'),
(29, '', 25, FALSE, 0, 1, TRUE, '2009-02-02 13:11:37', '2010-08-22 06:32:39'),
(30, '', 25, FALSE, 0, 1, TRUE, '2009-02-02 13:11:59', '2010-08-22 06:33:00'),
(31, '', 25, FALSE, 0, 1, TRUE, '2009-02-03 14:17:24', '2010-08-22 06:33:06'),
(32, '', 25, FALSE, 0, 1, TRUE, '2009-02-03 14:17:34', '2010-08-22 06:33:12'),
(33, '', 0, TRUE, 1, 6, TRUE, '2009-02-03 14:17:55', '2011-05-30 12:15:25'),
(34, 'catalog/demo/ipod_touch_4.jpg', 0, TRUE, 4, 7, TRUE, '2009-02-03 14:18:11', '2011-05-30 12:15:31'),
(35, '', 28, FALSE, 0, 0, TRUE, '2010-09-17 10:06:48', '2010-09-18 14:02:42'),
(36, '', 28, FALSE, 0, 0, TRUE, '2010-09-17 10:07:13', '2010-09-18 14:02:55'),
(37, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:03:39', '2011-04-22 01:55:08'),
(38, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:03:51', '2010-09-18 14:03:51'),
(39, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:04:17', '2011-04-22 01:55:20'),
(40, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:05:36', '2010-09-18 14:05:36'),
(41, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:05:49', '2011-04-22 01:55:30'),
(42, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:06:34', '2010-11-07 20:31:04'),
(43, '', 34, FALSE, 0, 0, TRUE, '2010-09-18 14:06:49', '2011-04-22 01:55:40'),
(44, '', 34, FALSE, 0, 0, TRUE, '2010-09-21 15:39:21', '2010-11-07 20:30:55'),
(45, '', 18, FALSE, 0, 0, TRUE, '2010-09-24 18:29:16', '2011-04-26 08:52:11'),
(46, '', 18, FALSE, 0, 0, TRUE, '2010-09-24 18:29:31', '2011-04-26 08:52:23'),
(47, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:13:16', '2010-11-07 11:13:16'),
(48, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:13:33', '2010-11-07 11:13:33'),
(49, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:14:04', '2010-11-07 11:14:04'),
(50, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:14:23', '2011-04-22 01:16:01'),
(51, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:14:38', '2011-04-22 01:16:13'),
(52, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:16:09', '2011-04-22 01:54:57'),
(53, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:28:53', '2011-04-22 01:14:36'),
(54, '', 34, FALSE, 0, 0, TRUE, '2010-11-07 11:29:16', '2011-04-22 01:16:50'),
(55, '', 34, FALSE, 0, 0, TRUE, '2010-11-08 10:31:32', '2010-11-08 10:31:32'),
(56, '', 34, FALSE, 0, 0, TRUE, '2010-11-08 10:31:50', '2011-04-22 01:16:37'),
(57, '', 0, TRUE, 1, 3, TRUE, '2011-04-26 08:53:16', '2011-05-30 12:15:05'),
(58, '', 52, FALSE, 0, 0, TRUE, '2011-05-08 13:44:16', '2011-05-08 13:44:16');

-- --------------------------------------------------------

--
-- Table structure for table oc_category_description
--

DROP TABLE IF EXISTS oc_category_description;
CREATE TABLE oc_category_description (
  category_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(255) NOT NULL,
  description text NOT NULL,
  meta_title varchar(255) NULL,
  meta_description varchar(255) NOT NULL,
  meta_keyword varchar(255) NOT NULL,
  PRIMARY KEY (category_id,language_id)
);

CREATE INDEX idx_name ON oc_category_description (name);

--
-- Dumping data for table oc_category_description
--

INSERT INTO oc_category_description (category_id, language_id, name, description, meta_description, meta_keyword) VALUES
(28, 1, 'Monitors', '', '', ''),
(33, 1, 'Cameras', '', '', ''),
(32, 1, 'Web Cameras', '', '', ''),
(31, 1, 'Scanners', '', '', ''),
(30, 1, 'Printers', '', '', ''),
(29, 1, 'Mice and Trackballs', '', '', ''),
(27, 1, 'Mac', '', '', ''),
(26, 1, 'PC', '', '', ''),
(17, 1, 'Software', '', '', ''),
(25, 1, 'Components', '', '', ''),
(24, 1, 'Phones &amp; PDAs', '', '', ''),
(20, 1, 'Desktops', '&lt;p&gt;\r\n	Example of category description text&lt;/p&gt;\r\n', 'Example of category description', ''),
(35, 1, 'test 1', '', '', ''),
(36, 1, 'test 2', '', '', ''),
(37, 1, 'test 5', '', '', ''),
(38, 1, 'test 4', '', '', ''),
(39, 1, 'test 6', '', '', ''),
(40, 1, 'test 7', '', '', ''),
(41, 1, 'test 8', '', '', ''),
(42, 1, 'test 9', '', '', ''),
(43, 1, 'test 11', '', '', ''),
(34, 1, 'MP3 Players', '&lt;p&gt;\r\n	Shop Laptop feature only the best laptop deals on the market. By comparing laptop deals from the likes of PC World, Comet, Dixons, The Link and Carphone Warehouse, Shop Laptop has the most comprehensive selection of laptops on the internet. At Shop Laptop, we pride ourselves on offering customers the very best laptop deals. From refurbished laptops to netbooks, Shop Laptop ensures that every laptop - in every colour, style, size and technical spec - is featured on the site at the lowest possible price.&lt;/p&gt;\r\n', '', ''),
(18, 1, 'Laptops &amp; Notebooks', '&lt;p&gt;\r\n	Shop Laptop feature only the best laptop deals on the market. By comparing laptop deals from the likes of PC World, Comet, Dixons, The Link and Carphone Warehouse, Shop Laptop has the most comprehensive selection of laptops on the internet. At Shop Laptop, we pride ourselves on offering customers the very best laptop deals. From refurbished laptops to netbooks, Shop Laptop ensures that every laptop - in every colour, style, size and technical spec - is featured on the site at the lowest possible price.&lt;/p&gt;\r\n', '', ''),
(44, 1, 'test 12', '', '', ''),
(45, 1, 'Windows', '', '', ''),
(46, 1, 'Macs', '', '', ''),
(47, 1, 'test 15', '', '', ''),
(48, 1, 'test 16', '', '', ''),
(49, 1, 'test 17', '', '', ''),
(50, 1, 'test 18', '', '', ''),
(51, 1, 'test 19', '', '', ''),
(52, 1, 'test 20', '', '', ''),
(53, 1, 'test 21', '', '', ''),
(54, 1, 'test 22', '', '', ''),
(55, 1, 'test 23', '', '', ''),
(56, 1, 'test 24', '', '', ''),
(57, 1, 'Tablets', '', '', ''),
(58, 1, 'test 25', '', '', '');

-- --------------------------------------------------------

DROP TABLE IF EXISTS oc_category_path;
CREATE TABLE oc_category_path (
  category_id integer NOT NULL,
  path_id integer NOT NULL,
  level integer NOT NULL,
  PRIMARY KEY (category_id,path_id)
);

--
-- Dumping data for table oc_category_path
--

INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('25', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('28', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('28', '28', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('35', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('35', '28', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('35', '35', '2');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('36', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('36', '28', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('36', '36', '2');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('29', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('29', '29', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('30', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('30', '30', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('31', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('31', '31', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('32', '25', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('32', '32', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('20', '20', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('27', '20', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('27', '27', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('26', '20', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('26', '26', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('24', '24', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('18', '18', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('45', '18', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('45', '45', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('46', '18', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('46', '46', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('17', '17', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('33', '33', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('34', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('37', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('37', '37', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('38', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('38', '38', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('39', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('39', '39', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('40', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('40', '40', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('41', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('41', '41', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('42', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('42', '42', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('43', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('43', '43', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('44', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('44', '44', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('47', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('47', '47', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('48', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('48', '48', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('49', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('49', '49', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('50', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('50', '50', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('51', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('51', '51', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('52', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('52', '52', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('58', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('58', '52', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('58', '58', '2');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('53', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('53', '53', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('54', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('54', '54', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('55', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('55', '55', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('56', '34', '0');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('56', '56', '1');
INSERT INTO oc_category_path (category_id, path_id, level) VALUES ('57', '57', '0');

-- --------------------------------------------------------

--
-- Table structure for table oc_category_filter
--

DROP TABLE IF EXISTS oc_category_filter;
CREATE TABLE oc_category_filter (
  category_id integer NOT NULL,
  filter_id integer NOT NULL,
  PRIMARY KEY (category_id,filter_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_category_to_layout
--

DROP TABLE IF EXISTS oc_category_to_layout;
CREATE TABLE oc_category_to_layout (
  category_id integer NOT NULL,
  store_id integer NOT NULL,
  layout_id integer NOT NULL,
  PRIMARY KEY (category_id,store_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_category_to_store
--

DROP TABLE IF EXISTS oc_category_to_store;
CREATE TABLE oc_category_to_store (
  category_id integer NOT NULL,
  store_id integer NOT NULL,
  PRIMARY KEY (category_id,store_id)
);

--
-- Dumping data for table oc_category_to_store
--

INSERT INTO oc_category_to_store (category_id, store_id) VALUES
(17, 0),
(18, 0),
(20, 0),
(24, 0),
(25, 0),
(26, 0),
(27, 0),
(28, 0),
(29, 0),
(30, 0),
(31, 0),
(32, 0),
(33, 0),
(34, 0),
(35, 0),
(36, 0),
(37, 0),
(38, 0),
(39, 0),
(40, 0),
(41, 0),
(42, 0),
(43, 0),
(44, 0),
(45, 0),
(46, 0),
(47, 0),
(48, 0),
(49, 0),
(50, 0),
(51, 0),
(52, 0),
(53, 0),
(54, 0),
(55, 0),
(56, 0),
(57, 0),
(58, 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_country
--

DROP TABLE IF EXISTS oc_country;
CREATE TABLE oc_country (
  country_id serial NOT NULL,
  name varchar(128) NOT NULL,
  iso_code_2 varchar(2) NOT NULL,
  iso_code_3 varchar(3) NOT NULL,
  address_format text NOT NULL,
  postcode_required boolean NOT NULL,
  status boolean NOT NULL DEFAULT TRUE,
  PRIMARY KEY (country_id)
);

--
-- Dumping data for table oc_country
--

INSERT INTO oc_country (country_id, name, iso_code_2, iso_code_3, address_format, postcode_required, status) VALUES
(1, 'Afghanistan', 'AF', 'AFG', '', FALSE, TRUE),
(2, 'Albania', 'AL', 'ALB', '', FALSE, TRUE),
(3, 'Algeria', 'DZ', 'DZA', '', FALSE, TRUE),
(4, 'American Samoa', 'AS', 'ASM', '', FALSE, TRUE),
(5, 'Andorra', 'AD', 'AND', '', FALSE, TRUE),
(6, 'Angola', 'AO', 'AGO', '', FALSE, TRUE),
(7, 'Anguilla', 'AI', 'AIA', '', FALSE, TRUE),
(8, 'Antarctica', 'AQ', 'ATA', '', FALSE, TRUE),
(9, 'Antigua and Barbuda', 'AG', 'ATG', '', FALSE, TRUE),
(10, 'Argentina', 'AR', 'ARG', '', FALSE, TRUE),
(11, 'Armenia', 'AM', 'ARM', '', FALSE, TRUE),
(12, 'Aruba', 'AW', 'ABW', '', FALSE, TRUE),
(13, 'Australia', 'AU', 'AUS', '', FALSE, TRUE),
(14, 'Austria', 'AT', 'AUT', '', FALSE, TRUE),
(15, 'Azerbaijan', 'AZ', 'AZE', '', FALSE, TRUE),
(16, 'Bahamas', 'BS', 'BHS', '', FALSE, TRUE),
(17, 'Bahrain', 'BH', 'BHR', '', FALSE, TRUE),
(18, 'Bangladesh', 'BD', 'BGD', '', FALSE, TRUE),
(19, 'Barbados', 'BB', 'BRB', '', FALSE, TRUE),
(20, 'Belarus', 'BY', 'BLR', '', FALSE, TRUE),
(21, 'Belgium', 'BE', 'BEL', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', FALSE, TRUE),
(22, 'Belize', 'BZ', 'BLZ', '', FALSE, TRUE),
(23, 'Benin', 'BJ', 'BEN', '', FALSE, TRUE),
(24, 'Bermuda', 'BM', 'BMU', '', FALSE, TRUE),
(25, 'Bhutan', 'BT', 'BTN', '', FALSE, TRUE),
(26, 'Bolivia', 'BO', 'BOL', '', FALSE, TRUE),
(27, 'Bosnia and Herzegovina', 'BA', 'BIH', '', FALSE, TRUE),
(28, 'Botswana', 'BW', 'BWA', '', FALSE, TRUE),
(29, 'Bouvet Island', 'BV', 'BVT', '', FALSE, TRUE),
(30, 'Brazil', 'BR', 'BRA', '', FALSE, TRUE),
(31, 'British Indian Ocean Territory', 'IO', 'IOT', '', FALSE, TRUE),
(32, 'Brunei Darussalam', 'BN', 'BRN', '', FALSE, TRUE),
(33, 'Bulgaria', 'BG', 'BGR', '', FALSE, TRUE),
(34, 'Burkina Faso', 'BF', 'BFA', '', FALSE, TRUE),
(35, 'Burundi', 'BI', 'BDI', '', FALSE, TRUE),
(36, 'Cambodia', 'KH', 'KHM', '', FALSE, TRUE),
(37, 'Cameroon', 'CM', 'CMR', '', FALSE, TRUE),
(38, 'Canada', 'CA', 'CAN', '', FALSE, TRUE),
(39, 'Cape Verde', 'CV', 'CPV', '', FALSE, TRUE),
(40, 'Cayman Islands', 'KY', 'CYM', '', FALSE, TRUE),
(41, 'Central African Republic', 'CF', 'CAF', '', FALSE, TRUE),
(42, 'Chad', 'TD', 'TCD', '', FALSE, TRUE),
(43, 'Chile', 'CL', 'CHL', '', FALSE, TRUE),
(44, 'China', 'CN', 'CHN', '', FALSE, TRUE),
(45, 'Christmas Island', 'CX', 'CXR', '', FALSE, TRUE),
(46, 'Cocos (Keeling) Islands', 'CC', 'CCK', '', FALSE, TRUE),
(47, 'Colombia', 'CO', 'COL', '', FALSE, TRUE),
(48, 'Comoros', 'KM', 'COM', '', FALSE, TRUE),
(49, 'Congo', 'CG', 'COG', '', FALSE, TRUE),
(50, 'Cook Islands', 'CK', 'COK', '', FALSE, TRUE),
(51, 'Costa Rica', 'CR', 'CRI', '', FALSE, TRUE),
(52, 'Cote D''Ivoire', 'CI', 'CIV', '', FALSE, TRUE),
(53, 'Croatia', 'HR', 'HRV', '', FALSE, TRUE),
(54, 'Cuba', 'CU', 'CUB', '', FALSE, TRUE),
(55, 'Cyprus', 'CY', 'CYP', '', FALSE, TRUE),
(56, 'Czech Republic', 'CZ', 'CZE', '', FALSE, TRUE),
(57, 'Denmark', 'DK', 'DNK', '', FALSE, TRUE),
(58, 'Djibouti', 'DJ', 'DJI', '', FALSE, TRUE),
(59, 'Dominica', 'DM', 'DMA', '', FALSE, TRUE),
(60, 'Dominican Republic', 'DO', 'DOM', '', FALSE, TRUE),
(61, 'East Timor', 'TL', 'TLS', '', FALSE, TRUE),
(62, 'Ecuador', 'EC', 'ECU', '', FALSE, TRUE),
(63, 'Egypt', 'EG', 'EGY', '', FALSE, TRUE),
(64, 'El Salvador', 'SV', 'SLV', '', FALSE, TRUE),
(65, 'Equatorial Guinea', 'GQ', 'GNQ', '', FALSE, TRUE),
(66, 'Eritrea', 'ER', 'ERI', '', FALSE, TRUE),
(67, 'Estonia', 'EE', 'EST', '', FALSE, TRUE),
(68, 'Ethiopia', 'ET', 'ETH', '', FALSE, TRUE),
(69, 'Falkland Islands (Malvinas)', 'FK', 'FLK', '', FALSE, TRUE),
(70, 'Faroe Islands', 'FO', 'FRO', '', FALSE, TRUE),
(71, 'Fiji', 'FJ', 'FJI', '', FALSE, TRUE),
(72, 'Finland', 'FI', 'FIN', '', FALSE, TRUE),
(74, 'France, Metropolitan', 'FR', 'FRA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', TRUE, TRUE),
(75, 'French Guiana', 'GF', 'GUF', '', FALSE, TRUE),
(76, 'French Polynesia', 'PF', 'PYF', '', FALSE, TRUE),
(77, 'French Southern Territories', 'TF', 'ATF', '', FALSE, TRUE),
(78, 'Gabon', 'GA', 'GAB', '', FALSE, TRUE),
(79, 'Gambia', 'GM', 'GMB', '', FALSE, TRUE),
(80, 'Georgia', 'GE', 'GEO', '', FALSE, TRUE),
(81, 'Germany', 'DE', 'DEU', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', TRUE, TRUE),
(82, 'Ghana', 'GH', 'GHA', '', FALSE, TRUE),
(83, 'Gibraltar', 'GI', 'GIB', '', FALSE, TRUE),
(84, 'Greece', 'GR', 'GRC', '', FALSE, TRUE),
(85, 'Greenland', 'GL', 'GRL', '', FALSE, TRUE),
(86, 'Grenada', 'GD', 'GRD', '', FALSE, TRUE),
(87, 'Guadeloupe', 'GP', 'GLP', '', FALSE, TRUE),
(88, 'Guam', 'GU', 'GUM', '', FALSE, TRUE),
(89, 'Guatemala', 'GT', 'GTM', '', FALSE, TRUE),
(90, 'Guinea', 'GN', 'GIN', '', FALSE, TRUE),
(91, 'Guinea-Bissau', 'GW', 'GNB', '', FALSE, TRUE),
(92, 'Guyana', 'GY', 'GUY', '', FALSE, TRUE),
(93, 'Haiti', 'HT', 'HTI', '', FALSE, TRUE),
(94, 'Heard and Mc Donald Islands', 'HM', 'HMD', '', FALSE, TRUE),
(95, 'Honduras', 'HN', 'HND', '', FALSE, TRUE),
(96, 'Hong Kong', 'HK', 'HKG', '', FALSE, TRUE),
(97, 'Hungary', 'HU', 'HUN', '', FALSE, TRUE),
(98, 'Iceland', 'IS', 'ISL', '', FALSE, TRUE),
(99, 'India', 'IN', 'IND', '', FALSE, TRUE),
(100, 'Indonesia', 'ID', 'IDN', '', FALSE, TRUE),
(101, 'Iran (Islamic Republic of)', 'IR', 'IRN', '', FALSE, TRUE),
(102, 'Iraq', 'IQ', 'IRQ', '', FALSE, TRUE),
(103, 'Ireland', 'IE', 'IRL', '', FALSE, TRUE),
(104, 'Israel', 'IL', 'ISR', '', FALSE, TRUE),
(105, 'Italy', 'IT', 'ITA', '', FALSE, TRUE),
(106, 'Jamaica', 'JM', 'JAM', '', FALSE, TRUE),
(107, 'Japan', 'JP', 'JPN', '', FALSE, TRUE),
(108, 'Jordan', 'JO', 'JOR', '', FALSE, TRUE),
(109, 'Kazakhstan', 'KZ', 'KAZ', '', FALSE, TRUE),
(110, 'Kenya', 'KE', 'KEN', '', FALSE, TRUE),
(111, 'Kiribati', 'KI', 'KIR', '', FALSE, TRUE),
(112, 'North Korea', 'KP', 'PRK', '', FALSE, TRUE),
(113, 'Korea, Republic of', 'KR', 'KOR', '', FALSE, TRUE),
(114, 'Kuwait', 'KW', 'KWT', '', FALSE, TRUE),
(115, 'Kyrgyzstan', 'KG', 'KGZ', '', FALSE, TRUE),
(116, 'Lao People''s Democratic Republic', 'LA', 'LAO', '', FALSE, TRUE),
(117, 'Latvia', 'LV', 'LVA', '', FALSE, TRUE),
(118, 'Lebanon', 'LB', 'LBN', '', FALSE, TRUE),
(119, 'Lesotho', 'LS', 'LSO', '', FALSE, TRUE),
(120, 'Liberia', 'LR', 'LBR', '', FALSE, TRUE),
(121, 'Libyan Arab Jamahiriya', 'LY', 'LBY', '', FALSE, TRUE),
(122, 'Liechtenstein', 'LI', 'LIE', '', FALSE, TRUE),
(123, 'Lithuania', 'LT', 'LTU', '', FALSE, TRUE),
(124, 'Luxembourg', 'LU', 'LUX', '', FALSE, TRUE),
(125, 'Macau', 'MO', 'MAC', '', FALSE, TRUE),
(126, 'FYROM', 'MK', 'MKD', '', FALSE, TRUE),
(127, 'Madagascar', 'MG', 'MDG', '', FALSE, TRUE),
(128, 'Malawi', 'MW', 'MWI', '', FALSE, TRUE),
(129, 'Malaysia', 'MY', 'MYS', '', FALSE, TRUE),
(130, 'Maldives', 'MV', 'MDV', '', FALSE, TRUE),
(131, 'Mali', 'ML', 'MLI', '', FALSE, TRUE),
(132, 'Malta', 'MT', 'MLT', '', FALSE, TRUE),
(133, 'Marshall Islands', 'MH', 'MHL', '', FALSE, TRUE),
(134, 'Martinique', 'MQ', 'MTQ', '', FALSE, TRUE),
(135, 'Mauritania', 'MR', 'MRT', '', FALSE, TRUE),
(136, 'Mauritius', 'MU', 'MUS', '', FALSE, TRUE),
(137, 'Mayotte', 'YT', 'MYT', '', FALSE, TRUE),
(138, 'Mexico', 'MX', 'MEX', '', FALSE, TRUE),
(139, 'Micronesia, Federated States of', 'FM', 'FSM', '', FALSE, TRUE),
(140, 'Moldova, Republic of', 'MD', 'MDA', '', FALSE, TRUE),
(141, 'Monaco', 'MC', 'MCO', '', FALSE, TRUE),
(142, 'Mongolia', 'MN', 'MNG', '', FALSE, TRUE),
(143, 'Montserrat', 'MS', 'MSR', '', FALSE, TRUE),
(144, 'Morocco', 'MA', 'MAR', '', FALSE, TRUE),
(145, 'Mozambique', 'MZ', 'MOZ', '', FALSE, TRUE),
(146, 'Myanmar', 'MM', 'MMR', '', FALSE, TRUE),
(147, 'Namibia', 'NA', 'NAM', '', FALSE, TRUE),
(148, 'Nauru', 'NR', 'NRU', '', FALSE, TRUE),
(149, 'Nepal', 'NP', 'NPL', '', FALSE, TRUE),
(150, 'Netherlands', 'NL', 'NLD', '', FALSE, TRUE),
(151, 'Netherlands Antilles', 'AN', 'ANT', '', FALSE, TRUE),
(152, 'New Caledonia', 'NC', 'NCL', '', FALSE, TRUE),
(153, 'New Zealand', 'NZ', 'NZL', '', FALSE, TRUE),
(154, 'Nicaragua', 'NI', 'NIC', '', FALSE, TRUE),
(155, 'Niger', 'NE', 'NER', '', FALSE, TRUE),
(156, 'Nigeria', 'NG', 'NGA', '', FALSE, TRUE),
(157, 'Niue', 'NU', 'NIU', '', FALSE, TRUE),
(158, 'Norfolk Island', 'NF', 'NFK', '', FALSE, TRUE),
(159, 'Northern Mariana Islands', 'MP', 'MNP', '', FALSE, TRUE),
(160, 'Norway', 'NO', 'NOR', '', FALSE, TRUE),
(161, 'Oman', 'OM', 'OMN', '', FALSE, TRUE),
(162, 'Pakistan', 'PK', 'PAK', '', FALSE, TRUE),
(163, 'Palau', 'PW', 'PLW', '', FALSE, TRUE),
(164, 'Panama', 'PA', 'PAN', '', FALSE, TRUE),
(165, 'Papua New Guinea', 'PG', 'PNG', '', FALSE, TRUE),
(166, 'Paraguay', 'PY', 'PRY', '', FALSE, TRUE),
(167, 'Peru', 'PE', 'PER', '', FALSE, TRUE),
(168, 'Philippines', 'PH', 'PHL', '', FALSE, TRUE),
(169, 'Pitcairn', 'PN', 'PCN', '', FALSE, TRUE),
(170, 'Poland', 'PL', 'POL', '', FALSE, TRUE),
(171, 'Portugal', 'PT', 'PRT', '', FALSE, TRUE),
(172, 'Puerto Rico', 'PR', 'PRI', '', FALSE, TRUE),
(173, 'Qatar', 'QA', 'QAT', '', FALSE, TRUE),
(174, 'Reunion', 'RE', 'REU', '', FALSE, TRUE),
(175, 'Romania', 'RO', 'ROM', '', FALSE, TRUE),
(176, 'Russian Federation', 'RU', 'RUS', '', FALSE, TRUE),
(177, 'Rwanda', 'RW', 'RWA', '', FALSE, TRUE),
(178, 'Saint Kitts and Nevis', 'KN', 'KNA', '', FALSE, TRUE),
(179, 'Saint Lucia', 'LC', 'LCA', '', FALSE, TRUE),
(180, 'Saint Vincent and the Grenadines', 'VC', 'VCT', '', FALSE, TRUE),
(181, 'Samoa', 'WS', 'WSM', '', FALSE, TRUE),
(182, 'San Marino', 'SM', 'SMR', '', FALSE, TRUE),
(183, 'Sao Tome and Principe', 'ST', 'STP', '', FALSE, TRUE),
(184, 'Saudi Arabia', 'SA', 'SAU', '', FALSE, TRUE),
(185, 'Senegal', 'SN', 'SEN', '', FALSE, TRUE),
(186, 'Seychelles', 'SC', 'SYC', '', FALSE, TRUE),
(187, 'Sierra Leone', 'SL', 'SLE', '', FALSE, TRUE),
(188, 'Singapore', 'SG', 'SGP', '', FALSE, TRUE),
(189, 'Slovak Republic', 'SK', 'SVK', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city} {postcode}\r\n{zone}\r\n{country}', FALSE, TRUE),
(190, 'Slovenia', 'SI', 'SVN', '', FALSE, TRUE),
(191, 'Solomon Islands', 'SB', 'SLB', '', FALSE, TRUE),
(192, 'Somalia', 'SO', 'SOM', '', FALSE, TRUE),
(193, 'South Africa', 'ZA', 'ZAF', '', FALSE, TRUE),
(194, 'South Georgia &amp; South Sandwich Islands', 'GS', 'SGS', '', FALSE, TRUE),
(195, 'Spain', 'ES', 'ESP', '', FALSE, TRUE),
(196, 'Sri Lanka', 'LK', 'LKA', '', FALSE, TRUE),
(197, 'St. Helena', 'SH', 'SHN', '', FALSE, TRUE),
(198, 'St. Pierre and Miquelon', 'PM', 'SPM', '', FALSE, TRUE),
(199, 'Sudan', 'SD', 'SDN', '', FALSE, TRUE),
(200, 'Suriname', 'SR', 'SUR', '', FALSE, TRUE),
(201, 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '', FALSE, TRUE),
(202, 'Swaziland', 'SZ', 'SWZ', '', FALSE, TRUE),
(203, 'Sweden', 'SE', 'SWE', '{company}\r\n{firstname} {lastname}\r\n{address_1}\r\n{address_2}\r\n{postcode} {city}\r\n{country}', TRUE, TRUE),
(204, 'Switzerland', 'CH', 'CHE', '', FALSE, TRUE),
(205, 'Syrian Arab Republic', 'SY', 'SYR', '', FALSE, TRUE),
(206, 'Taiwan', 'TW', 'TWN', '', FALSE, TRUE),
(207, 'Tajikistan', 'TJ', 'TJK', '', FALSE, TRUE),
(208, 'Tanzania, United Republic of', 'TZ', 'TZA', '', FALSE, TRUE),
(209, 'Thailand', 'TH', 'THA', '', FALSE, TRUE),
(210, 'Togo', 'TG', 'TGO', '', FALSE, TRUE),
(211, 'Tokelau', 'TK', 'TKL', '', FALSE, TRUE),
(212, 'Tonga', 'TO', 'TON', '', FALSE, TRUE),
(213, 'Trinidad and Tobago', 'TT', 'TTO', '', FALSE, TRUE),
(214, 'Tunisia', 'TN', 'TUN', '', FALSE, TRUE),
(215, 'Turkey', 'TR', 'TUR', '', FALSE, TRUE),
(216, 'Turkmenistan', 'TM', 'TKM', '', FALSE, TRUE),
(217, 'Turks and Caicos Islands', 'TC', 'TCA', '', FALSE, TRUE),
(218, 'Tuvalu', 'TV', 'TUV', '', FALSE, TRUE),
(219, 'Uganda', 'UG', 'UGA', '', FALSE, TRUE),
(220, 'Ukraine', 'UA', 'UKR', '', FALSE, TRUE),
(221, 'United Arab Emirates', 'AE', 'ARE', '', FALSE, TRUE),
(222, 'United Kingdom', 'GB', 'GBR', '', TRUE, TRUE),
(223, 'United States', 'US', 'USA', '{firstname} {lastname}\r\n{company}\r\n{address_1}\r\n{address_2}\r\n{city}, {zone} {postcode}\r\n{country}', FALSE, TRUE),
(224, 'United States Minor Outlying Islands', 'UM', 'UMI', '', FALSE, TRUE),
(225, 'Uruguay', 'UY', 'URY', '', FALSE, TRUE),
(226, 'Uzbekistan', 'UZ', 'UZB', '', FALSE, TRUE),
(227, 'Vanuatu', 'VU', 'VUT', '', FALSE, TRUE),
(228, 'Vatican City State (Holy See)', 'VA', 'VAT', '', FALSE, TRUE),
(229, 'Venezuela', 'VE', 'VEN', '', FALSE, TRUE),
(230, 'Viet Nam', 'VN', 'VNM', '', FALSE, TRUE),
(231, 'Virgin Islands (British)', 'VG', 'VGB', '', FALSE, TRUE),
(232, 'Virgin Islands (U.S.)', 'VI', 'VIR', '', FALSE, TRUE),
(233, 'Wallis and Futuna Islands', 'WF', 'WLF', '', FALSE, TRUE),
(234, 'Western Sahara', 'EH', 'ESH', '', FALSE, TRUE),
(235, 'Yemen', 'YE', 'YEM', '', FALSE, TRUE),
(237, 'Democratic Republic of Congo', 'CD', 'COD', '', FALSE, TRUE),
(238, 'Zambia', 'ZM', 'ZMB', '', FALSE, TRUE),
(239, 'Zimbabwe', 'ZW', 'ZWE', '', FALSE, TRUE),
(242, 'Montenegro', 'ME', 'MNE', '', FALSE, TRUE),
(243, 'Serbia', 'RS', 'SRB', '', FALSE, TRUE),
(244, 'Aaland Islands', 'AX', 'ALA', '', FALSE, TRUE),
(245, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'BES', '', FALSE, TRUE),
(246, 'Curacao', 'CW', 'CUW', '', FALSE, TRUE),
(247, 'Palestinian Territory, Occupied', 'PS', 'PSE', '', FALSE, TRUE),
(248, 'South Sudan', 'SS', 'SSD', '', FALSE, TRUE),
(249, 'St. Barthelemy', 'BL', 'BLM', '', FALSE, TRUE),
(250, 'St. Martin (French part)', 'MF', 'MAF', '', FALSE, TRUE),
(251, 'Canary Islands', 'IC', 'ICA', '', FALSE, TRUE);
-- --------------------------------------------------------

--
-- Table structure for table oc_coupon
--

DROP TABLE IF EXISTS oc_coupon;
CREATE TABLE oc_coupon (
  coupon_id serial NOT NULL,
  name varchar(128) NOT NULL,
  code varchar(10) NOT NULL,
  type char(1) NOT NULL,
  discount decimal(15,4) NOT NULL,
  logged boolean NOT NULL,
  shipping boolean NOT NULL,
  total decimal(15,4) NOT NULL,
  date_start date NOT NULL,
  date_end date NOT NULL,
  uses_total integer NOT NULL,
  uses_customer varchar(11) NOT NULL,
  status boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (coupon_id)
);

--
-- Dumping data for table oc_coupon
--

INSERT INTO oc_coupon (coupon_id, name, code, type, discount, logged, shipping, total, date_start, date_end, uses_total, uses_customer, status, date_added) VALUES
(4, '-10% Discount', '2222', 'P', '10.0000', FALSE, FALSE, '0.0000', '2011-01-01', '2012-01-01', 10, '10', TRUE, '2009-01-27 13:55:03'),
(5, 'Free Shipping', '3333', 'P', '0.0000', FALSE, TRUE, '100.0000', '2009-03-01', '2009-08-31', 10, '10', TRUE, '2009-03-14 21:13:53'),
(6, '-10.00 Discount', '1111', 'F', '10.0000', FALSE, FALSE, '10.0000', '1970-11-01', '2020-11-01', 100000, '10000', TRUE, '2009-03-14 21:15:18');

-- --------------------------------------------------------

--
-- Table structure for table oc_coupon_category
--

DROP TABLE IF EXISTS oc_coupon_category;
CREATE TABLE oc_coupon_category (
  coupon_id integer NOT NULL,
  category_id integer NOT NULL,
  PRIMARY KEY (coupon_id,category_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_coupon_history
--

DROP TABLE IF EXISTS oc_coupon_history;
CREATE TABLE oc_coupon_history (
  coupon_history_id serial NOT NULL,
  coupon_id integer NOT NULL,
  order_id integer NOT NULL,
  customer_id integer NOT NULL,
  amount decimal(15,4) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (coupon_history_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_coupon_product
--

DROP TABLE IF EXISTS oc_coupon_product;
CREATE TABLE oc_coupon_product (
  coupon_product_id serial NOT NULL,
  coupon_id integer NOT NULL,
  product_id integer NOT NULL,
  PRIMARY KEY (coupon_product_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_currency
--

DROP TABLE IF EXISTS oc_currency;
CREATE TABLE oc_currency (
  currency_id serial NOT NULL,
  title varchar(32) NOT NULL,
  code varchar(3) NOT NULL,
  symbol_left varchar(12) NOT NULL,
  symbol_right varchar(12) NOT NULL,
  decimal_place char(1) NOT NULL,
  value real NOT NULL,
  status boolean NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (currency_id)
);

--
-- Dumping data for table oc_currency
--

INSERT INTO oc_currency (currency_id, title, code, symbol_left, symbol_right, decimal_place, value, status, date_modified) VALUES
(1, 'Pound Sterling', 'GBP', '£', '', '2', 0.61979997, TRUE, '2011-07-16 10:30:52'),
(2, 'US Dollar', 'USD', '$', '', '2', 1.00000000, TRUE, '2011-07-16 16:55:46'),
(3, 'Euro', 'EUR', '', '€', '2', 0.70660001, TRUE, '2011-07-16 10:30:52');

-- --------------------------------------------------------

--
-- Table structure for table oc_customer
--

DROP TABLE IF EXISTS oc_customer;
CREATE TABLE oc_customer (
  customer_id serial NOT NULL,
  store_id integer NOT NULL DEFAULT '0',
  firstname varchar(32) NOT NULL,
  lastname varchar(32) NOT NULL,
  email varchar(96) NOT NULL,
  telephone varchar(32) NOT NULL,
  fax varchar(32) NOT NULL,
  password varchar(40) NOT NULL,
  salt varchar(9) NOT NULL,
  cart text,
  wishlist text,
  newsletter boolean NOT NULL DEFAULT FALSE,
  address_id integer NOT NULL DEFAULT '0',
  customer_group_id integer NOT NULL,
  custom_field text NOT NULL,
  ip varchar(40) NOT NULL,
  status boolean NOT NULL,
  approved boolean NOT NULL,
  token varchar(255) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (customer_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_activity
--

DROP TABLE IF EXISTS oc_customer_activity;
CREATE TABLE oc_customer_activity (
  activity_id serial NOT NULL,
  customer_id integer NOT NULL,
  key varchar(64) NOT NULL,
  data text NOT NULL,
  ip varchar(40) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (activity_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_group
--

DROP TABLE IF EXISTS oc_customer_group;
CREATE TABLE oc_customer_group (
  customer_group_id serial NOT NULL,
  approval integer NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (customer_group_id)
);

--
-- Dumping data for table oc_customer_group
--

INSERT INTO oc_customer_group (customer_group_id, approval, sort_order) VALUES
(1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_group_description
--

DROP TABLE IF EXISTS oc_customer_group_description;
CREATE TABLE oc_customer_group_description (
  customer_group_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(32) NOT NULL,
  description text NOT NULL,
  PRIMARY KEY (customer_group_id,language_id)
);

--
-- Dumping data for table oc_customer_group_description
--

INSERT INTO oc_customer_group_description (customer_group_id, language_id, name, description) VALUES
(1, 1, 'Default', 'test');

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_history
--

DROP TABLE IF EXISTS oc_customer_history;
CREATE TABLE oc_customer_history (
  customer_history_id serial NOT NULL,
  customer_id integer NOT NULL,
  comment text NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (customer_history_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_ip
--

DROP TABLE IF EXISTS oc_customer_ip;
CREATE TABLE oc_customer_ip (
  customer_ip_id serial NOT NULL,
  customer_id integer NOT NULL,
  ip varchar(40) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (customer_ip_id)
);

CREATE INDEX idx_ip ON oc_customer_ip (ip);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_ip_ban_ip
--

DROP TABLE IF EXISTS oc_customer_ban_ip;
CREATE TABLE oc_customer_ban_ip (
  customer_ban_ip_id serial NOT NULL,
  ip varchar(40) NOT NULL,
  PRIMARY KEY (customer_ban_ip_id)
);

CREATE INDEX idx_ban_ip ON oc_customer_ban_ip (ip);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_online
--

DROP TABLE IF EXISTS oc_customer_online;
CREATE TABLE oc_customer_online (
  ip varchar(40) NOT NULL,
  customer_id integer NOT NULL,
  url text NOT NULL,
  referer text NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (ip)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_reward
--

DROP TABLE IF EXISTS oc_customer_reward;
CREATE TABLE oc_customer_reward (
  customer_reward_id serial NOT NULL,
  customer_id integer NOT NULL DEFAULT '0',
  order_id integer NOT NULL DEFAULT '0',
  description text NOT NULL,
  points integer NOT NULL DEFAULT '0',
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (customer_reward_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_customer_transaction
--

DROP TABLE IF EXISTS oc_customer_transaction;
CREATE TABLE oc_customer_transaction (
  customer_transaction_id serial NOT NULL,
  customer_id integer NOT NULL,
  order_id integer NOT NULL,
  description text NOT NULL,
  amount decimal(15,4) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (customer_transaction_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_custom_field
--

DROP TABLE IF EXISTS oc_custom_field;
CREATE TABLE oc_custom_field (
  custom_field_id serial NOT NULL,
  type varchar(32) NOT NULL,
  value text NOT NULL,
  storage varchar(7) NOT NULL,
  status boolean NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (custom_field_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_custom_field_customer_group
--

DROP TABLE IF EXISTS oc_custom_field_customer_group;
CREATE TABLE oc_custom_field_customer_group (
  custom_field_id integer NOT NULL,
  location varchar(64) NOT NULL,
  customer_group_id integer NOT NULL,
  required boolean NOT NULL,
  PRIMARY KEY (custom_field_id,customer_group_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_custom_field_description
--

DROP TABLE IF EXISTS oc_custom_field_description;
CREATE TABLE oc_custom_field_description (
  custom_field_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (custom_field_id,language_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_custom_field_value
--

DROP TABLE IF EXISTS oc_custom_field_value;
CREATE TABLE oc_custom_field_value (
  custom_field_value_id serial NOT NULL,
  custom_field_id integer NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (custom_field_value_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_custom_field_value_description
--

DROP TABLE IF EXISTS oc_custom_field_value_description;
CREATE TABLE oc_custom_field_value_description (
  custom_field_value_id integer NOT NULL,
  language_id integer NOT NULL,
  custom_field_id integer NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (custom_field_value_id,language_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_download
--

DROP TABLE IF EXISTS oc_download;
CREATE TABLE oc_download (
  download_id serial NOT NULL,
  filename varchar(128) NOT NULL,
  mask varchar(128) NOT NULL,
  remaining integer NOT NULL DEFAULT '0',
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (download_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_download_description
--

DROP TABLE IF EXISTS oc_download_description;
CREATE TABLE oc_download_description (
  download_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (download_id,language_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_filter_group
--

DROP TABLE IF EXISTS oc_filter_group;
CREATE TABLE oc_filter_group (
  filter_group_id serial NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (filter_group_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_filter_description
--

DROP TABLE IF EXISTS oc_filter_group_description;
CREATE TABLE oc_filter_group_description (
  filter_group_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (filter_group_id,language_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_filter
--

DROP TABLE IF EXISTS oc_filter;
CREATE TABLE oc_filter (
  filter_id serial NOT NULL,
  filter_group_id integer NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (filter_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_filter_description
--

DROP TABLE IF EXISTS oc_filter_description;
CREATE TABLE oc_filter_description (
  filter_id integer NOT NULL,
  language_id integer NOT NULL,
  filter_group_id integer NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (filter_id,language_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_extension
--

DROP TABLE IF EXISTS oc_extension;
CREATE TABLE oc_extension (
  extension_id serial NOT NULL,
  type varchar(32) NOT NULL,
  code varchar(32) NOT NULL,
  PRIMARY KEY (extension_id)
);

--
-- Dumping data for table oc_extension
--

INSERT INTO oc_extension (extension_id, type, code) VALUES
(23, 'payment', 'cod'),
(22, 'total', 'shipping'),
(57, 'total', 'sub_total'),
(58, 'total', 'tax'),
(59, 'total', 'total'),
(410, 'module', 'banner'),
(426, 'module', 'carousel'),
(390, 'total', 'credit'),
(387, 'shipping', 'flat'),
(349, 'total', 'handling'),
(350, 'total', 'low_order_fee'),
(389, 'total', 'coupon'),
(413, 'module', 'category'),
(411, 'module', 'affiliate'),
(408, 'module', 'account'),
(393, 'total', 'reward'),
(398, 'total', 'voucher'),
(407, 'payment', 'free_checkout'),
(427, 'module', 'featured'),
(419, 'module', 'slideshow');

-- --------------------------------------------------------

--
-- Table structure for table oc_geo_zone
--

DROP TABLE IF EXISTS oc_geo_zone;
CREATE TABLE oc_geo_zone (
  geo_zone_id serial NOT NULL,
  name varchar(32) NOT NULL,
  description varchar(255) NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (geo_zone_id)
);

--
-- Dumping data for table oc_geo_zone
--

INSERT INTO oc_geo_zone (geo_zone_id, name, description, date_modified, date_added) VALUES
(3, 'UK VAT Zone', 'UK VAT', '2010-02-26 22:33:24', '2009-01-06 23:26:25'),
(4, 'UK Shipping', 'UK Shipping Zones', '2010-12-15 15:18:13', '2009-06-23 01:14:53');

-- --------------------------------------------------------

--
-- Table structure for table oc_information
--

DROP TABLE IF EXISTS oc_information;
CREATE TABLE oc_information (
  information_id serial NOT NULL,
  bottom integer NOT NULL DEFAULT '0',
  sort_order integer NOT NULL DEFAULT '0',
  status boolean NOT NULL DEFAULT TRUE,
  PRIMARY KEY (information_id)
);

--
-- Dumping data for table oc_information
--

INSERT INTO oc_information (information_id, bottom, sort_order, status) VALUES
(3, 1, 3, TRUE),
(4, 1, 1, TRUE),
(5, 1, 4, TRUE),
(6, 1, 2, TRUE);

-- --------------------------------------------------------

--
-- Table structure for table oc_information_description
--

DROP TABLE IF EXISTS oc_information_description;
CREATE TABLE oc_information_description (
  information_id integer NOT NULL,
  language_id integer NOT NULL,
  title varchar(64) NOT NULL,
  description text NOT NULL,
  meta_title varchar(255) NULL,
  meta_description varchar(255) NULL,
  meta_keyword varchar(255) NULL,
  PRIMARY KEY (information_id,language_id)
);

--
-- Dumping data for table oc_information_description
--

INSERT INTO oc_information_description (information_id, language_id, title, description) VALUES
(4, 1, 'About Us', '&lt;p&gt;\r\n	About Us&lt;/p&gt;\r\n'),
(5, 1, 'Terms &amp; Conditions', '&lt;p&gt;\r\n	Terms &amp;amp; Conditions&lt;/p&gt;\r\n'),
(3, 1, 'Privacy Policy', '&lt;p&gt;\r\n	Privacy Policy&lt;/p&gt;\r\n'),
(6, 1, 'Delivery Information', '&lt;p&gt;\r\n	Delivery Information&lt;/p&gt;\r\n');

-- --------------------------------------------------------

--
-- Table structure for table oc_information_to_layout
--

DROP TABLE IF EXISTS oc_information_to_layout;
CREATE TABLE oc_information_to_layout (
  information_id integer NOT NULL,
  store_id integer NOT NULL,
  layout_id integer NOT NULL,
  PRIMARY KEY (information_id,store_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_information_to_store
--

DROP TABLE IF EXISTS oc_information_to_store;
CREATE TABLE oc_information_to_store (
  information_id integer NOT NULL,
  store_id integer NOT NULL,
  PRIMARY KEY (information_id,store_id)
);

--
-- Dumping data for table oc_information_to_store
--

INSERT INTO oc_information_to_store (information_id, store_id) VALUES
(3, 0),
(4, 0),
(5, 0),
(6, 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_language
--

DROP TABLE IF EXISTS oc_language;
CREATE TABLE oc_language (
  language_id serial NOT NULL,
  name varchar(32) NOT NULL,
  code varchar(5) NOT NULL,
  locale varchar(255) NOT NULL,
  image varchar(64) NOT NULL,
  directory varchar(32) NOT NULL,
  filename varchar(64) NOT NULL,
  sort_order integer NOT NULL DEFAULT '0',
  status boolean NOT NULL,
  PRIMARY KEY (language_id)
);

CREATE INDEX idx_language_name ON oc_language (name);

--
-- Dumping data for table oc_language
--

INSERT INTO oc_language (language_id, name, code, locale, image, directory, filename, sort_order, status) VALUES
(1, 'English', 'en', 'en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 'english', 1, TRUE);

-- --------------------------------------------------------

--
-- Table structure for table oc_layout
--

DROP TABLE IF EXISTS oc_layout;
CREATE TABLE oc_layout (
  layout_id serial NOT NULL,
  name varchar(64) NOT NULL,
  PRIMARY KEY (layout_id)
);

--
-- Dumping data for table oc_layout
--

INSERT INTO oc_layout (layout_id, name) VALUES
(1, 'Home'),
(2, 'Product'),
(3, 'Category'),
(4, 'Default'),
(5, 'Manufacturer'),
(6, 'Account'),
(7, 'Checkout'),
(8, 'Contact'),
(9, 'Sitemap'),
(10, 'Affiliate'),
(11, 'Information');

-- --------------------------------------------------------

--
-- Table structure for table oc_layout_route
--

DROP TABLE IF EXISTS oc_layout_route;
CREATE TABLE oc_layout_route (
  layout_route_id serial NOT NULL,
  layout_id integer NOT NULL,
  store_id integer NOT NULL,
  route varchar(255) NOT NULL,
  PRIMARY KEY (layout_route_id)
);

--
-- Dumping data for table oc_layout_route
--

INSERT INTO oc_layout_route (layout_route_id, layout_id, store_id, route) VALUES
(30, 6, 0, 'account/%'),
(17, 10, 0, 'affiliate/%'),
(29, 3, 0, 'product/category'),
(26, 1, 0, 'common/home'),
(20, 2, 0, 'product/product'),
(24, 11, 0, 'information/information'),
(22, 5, 0, 'product/manufacturer'),
(23, 7, 0, 'checkout/'),
(31, 8, 0, 'information/contact'),
(32, 9, 0, 'information/sitemap');

-- --------------------------------------------------------

--
-- Table structure for table oc_length_class
--

DROP TABLE IF EXISTS oc_length_class;
CREATE TABLE oc_length_class (
  length_class_id serial NOT NULL,
  value decimal(15,8) NOT NULL,
  PRIMARY KEY (length_class_id)
);

--
-- Dumping data for table oc_length_class
--

INSERT INTO oc_length_class (length_class_id, value) VALUES
(1, '1.00000000'),
(2, '10.00000000'),
(3, '0.39370000');

-- --------------------------------------------------------

--
-- Table structure for table oc_length_class_description
--

DROP TABLE IF EXISTS oc_length_class_description;
CREATE TABLE oc_length_class_description (
  length_class_id serial NOT NULL,
  language_id integer NOT NULL,
  title varchar(32) NOT NULL,
  unit varchar(4) NOT NULL,
  PRIMARY KEY (length_class_id,language_id)
);

--
-- Dumping data for table oc_length_class_description
--

INSERT INTO oc_length_class_description (length_class_id, language_id, title, unit) VALUES
(1, 1, 'Centimeter', 'cm'),
(2, 1, 'Millimeter', 'mm'),
(3, 1, 'Inch', 'in');

-- --------------------------------------------------------

--
-- Table structure for table oc_location
--

DROP TABLE IF EXISTS oc_location;
CREATE TABLE oc_location (
  location_id serial NOT NULL,
  name varchar(32) NOT NULL,
  address text NOT NULL,
  telephone varchar(32) NOT NULL,
  fax varchar(32) NOT NULL,  
  geocode varchar(32) NOT NULL,
  image varchar(255) DEFAULT NULL,
  open text NOT NULL,
  comment text NOT NULL,
  PRIMARY KEY (location_id)
);

CREATE INDEX idx_location_name ON oc_location (name);

-- --------------------------------------------------------

--
-- Table structure for table oc_manufacturer
--

DROP TABLE IF EXISTS oc_manufacturer;
CREATE TABLE oc_manufacturer (
  manufacturer_id serial NOT NULL,
  name varchar(64) NOT NULL,
  image varchar(255) DEFAULT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (manufacturer_id)
);

--
-- Dumping data for table oc_manufacturer
--

INSERT INTO oc_manufacturer (manufacturer_id, name, image, sort_order) VALUES
(5, 'HTC', 'catalog/demo/htc_logo.jpg', 0),
(6, 'Palm', 'catalog/demo/palm_logo.jpg', 0),
(7, 'Hewlett-Packard', 'catalog/demo/hp_logo.jpg', 0),
(8, 'Apple', 'catalog/demo/apple_logo.jpg', 0),
(9, 'Canon', 'catalog/demo/canon_logo.jpg', 0),
(10, 'Sony', 'catalog/demo/sony_logo.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_manufacturer_to_store
--

DROP TABLE IF EXISTS oc_manufacturer_to_store;
CREATE TABLE oc_manufacturer_to_store (
  manufacturer_id integer NOT NULL,
  store_id integer NOT NULL,
  PRIMARY KEY (manufacturer_id,store_id)
);

--
-- Dumping data for table oc_manufacturer_to_store
--

INSERT INTO oc_manufacturer_to_store (manufacturer_id, store_id) VALUES
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_marketing
--

DROP TABLE IF EXISTS oc_marketing;
CREATE TABLE oc_marketing (
  marketing_id serial NOT NULL,
  name varchar(32) NOT NULL,
  description text NOT NULL,
  code varchar(64) NOT NULL,
  clicks integer NOT NULL DEFAULT '0',
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (marketing_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_modification
--

DROP TABLE IF EXISTS oc_modification;
CREATE TABLE oc_modification (
  modification_id serial NOT NULL,
  name varchar(64) NOT NULL,
  author varchar(64) NOT NULL,
  version varchar(32) NOT NULL,
  link varchar(255) NOT NULL,
  code text NOT NULL,
  status boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (modification_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_option
--

DROP TABLE IF EXISTS oc_option;
CREATE TABLE oc_option (
  option_id serial NOT NULL,
  type varchar(32) NOT NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (option_id)
);

--
-- Dumping data for table oc_option
--

INSERT INTO oc_option (option_id, type, sort_order) VALUES
(1, 'radio', 2),
(2, 'checkbox', 3),
(4, 'text', 4),
(5, 'select', 1),
(6, 'textarea', 5),
(7, 'file', 6),
(8, 'date', 7),
(9, 'time', 8),
(10, 'timestamp without time zone', 9),
(11, 'select', 1),
(12, 'date', 1);

-- --------------------------------------------------------

--
-- Table structure for table oc_option_description
--

DROP TABLE IF EXISTS oc_option_description;
CREATE TABLE oc_option_description (
  option_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (option_id,language_id)
);

--
-- Dumping data for table oc_option_description
--

INSERT INTO oc_option_description (option_id, language_id, name) VALUES
(1, 1, 'Radio'),
(2, 1, 'Checkbox'),
(4, 1, 'Text'),
(6, 1, 'Textarea'),
(8, 1, 'Date'),
(7, 1, 'File'),
(5, 1, 'Select'),
(9, 1, 'Time'),
(10, 1, 'Date &amp; Time'),
(12, 1, 'Delivery Date'),
(11, 1, 'Size');

-- --------------------------------------------------------

--
-- Table structure for table oc_option_value
--

DROP TABLE IF EXISTS oc_option_value;
CREATE TABLE oc_option_value (
  option_value_id serial NOT NULL,
  option_id integer NOT NULL,
  image varchar(255) NULL,
  sort_order integer NOT NULL,
  PRIMARY KEY (option_value_id)
);

--
-- Dumping data for table oc_option_value
--

INSERT INTO oc_option_value (option_value_id, option_id, sort_order) VALUES
(43, 1, 3),
(32, 1, 1),
(45, 2, 4),
(44, 2, 3),
(42, 5, 4),
(41, 5, 3),
(39, 5, 1),
(40, 5, 2),
(31, 1, 2),
(23, 2, 1),
(24, 2, 2),
(46, 11, 1),
(47, 11, 2),
(48, 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table oc_option_value_description
--

DROP TABLE IF EXISTS oc_option_value_description;
CREATE TABLE oc_option_value_description (
  option_value_id integer NOT NULL,
  language_id integer NOT NULL,
  option_id integer NOT NULL,
  name varchar(128) NOT NULL,
  PRIMARY KEY (option_value_id,language_id)
);

--
-- Dumping data for table oc_option_value_description
--

INSERT INTO oc_option_value_description (option_value_id, language_id, option_id, name) VALUES
(43, 1, 1, 'Large'),
(32, 1, 1, 'Small'),
(45, 1, 2, 'Checkbox 4'),
(44, 1, 2, 'Checkbox 3'),
(31, 1, 1, 'Medium'),
(42, 1, 5, 'Yellow'),
(41, 1, 5, 'Green'),
(39, 1, 5, 'Red'),
(40, 1, 5, 'Blue'),
(23, 1, 2, 'Checkbox 1'),
(24, 1, 2, 'Checkbox 2'),
(48, 1, 11, 'Large'),
(47, 1, 11, 'Medium'),
(46, 1, 11, 'Small');

-- --------------------------------------------------------

--
-- Table structure for table oc_order
--

DROP TABLE IF EXISTS oc_order;
CREATE TABLE oc_order (
  order_id serial NOT NULL,
  invoice_no integer NOT NULL DEFAULT '0',
  invoice_prefix varchar(26) NOT NULL,
  store_id integer NOT NULL DEFAULT '0',
  store_name varchar(64) NOT NULL,
  store_url varchar(255) NOT NULL,
  customer_id integer NOT NULL DEFAULT '0',
  customer_group_id integer NOT NULL DEFAULT '0',
  firstname varchar(32) NOT NULL,
  lastname varchar(32) NOT NULL,
  email varchar(96) NOT NULL,
  telephone varchar(32) NOT NULL,
  fax varchar(32) NOT NULL,
  custom_field text NOT NULL,
  payment_firstname varchar(32) NOT NULL,
  payment_lastname varchar(32) NOT NULL,
  payment_company varchar(40) NOT NULL,  
  payment_address_1 varchar(128) NOT NULL,
  payment_address_2 varchar(128) NOT NULL,
  payment_city varchar(128) NOT NULL,
  payment_postcode varchar(10) NOT NULL,
  payment_country varchar(128) NOT NULL,
  payment_country_id integer NOT NULL,
  payment_zone varchar(128) NOT NULL,
  payment_zone_id integer NOT NULL,
  payment_address_format text NOT NULL,
  payment_custom_field text NOT NULL,
  payment_method varchar(128) NOT NULL,
  payment_code varchar(128) NOT NULL,
  shipping_firstname varchar(32) NOT NULL,
  shipping_lastname varchar(32) NOT NULL,
  shipping_company varchar(40) NOT NULL,
  shipping_address_1 varchar(128) NOT NULL,
  shipping_address_2 varchar(128) NOT NULL,
  shipping_city varchar(128) NOT NULL,
  shipping_postcode varchar(10) NOT NULL,
  shipping_country varchar(128) NOT NULL,
  shipping_country_id integer NOT NULL,
  shipping_zone varchar(128) NOT NULL,
  shipping_zone_id integer NOT NULL,
  shipping_address_format text NOT NULL,
  shipping_custom_field text NOT NULL,
  shipping_method varchar(128) NOT NULL,
  shipping_code varchar(128) NOT NULL,  
  comment text NOT NULL,
  total decimal(15,4) NOT NULL DEFAULT '0.0000',
  order_status_id integer NOT NULL DEFAULT '0',
  affiliate_id integer NOT NULL,
  commission decimal(15,4) NOT NULL,
  marketing_id integer NOT NULL,
  tracking varchar(64) NOT NULL,
  language_id integer NOT NULL,
  currency_id integer NOT NULL,
  currency_code varchar(3) NOT NULL,
  currency_value decimal(15,8) NOT NULL DEFAULT '1.0000',
  ip varchar(40) NOT NULL,
  forwarded_ip varchar(40) NOT NULL,
  user_agent varchar(255) NOT NULL,
  accept_language varchar(255) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NOT NULL,
  PRIMARY KEY (order_id)
);

-- --------------------------------------------------------

--
-- Dumping data for table oc_order_fraud
--

DROP TABLE IF EXISTS oc_order_fraud;
CREATE TABLE oc_order_fraud (
  order_id integer NOT NULL,
  customer_id integer NOT NULL,
  country_match varchar(3) NOT NULL,
  country_code varchar(2) NOT NULL,
  high_risk_country varchar(3) NOT NULL,
  distance integer NOT NULL,
  ip_region varchar(255) NOT NULL,
  ip_city varchar(255) NOT NULL,
  ip_latitude decimal(10,6) NOT NULL,
  ip_longitude decimal(10,6) NOT NULL,
  ip_isp varchar(255) NOT NULL,
  ip_org varchar(255) NOT NULL,
  ip_asnum integer NOT NULL,
  ip_user_type varchar(255) NOT NULL,
  ip_country_confidence varchar(3) NOT NULL,
  ip_region_confidence varchar(3) NOT NULL,
  ip_city_confidence varchar(3) NOT NULL,
  ip_postal_confidence varchar(3) NOT NULL,
  ip_postal_code varchar(10) NOT NULL,
  ip_accuracy_radius integer NOT NULL,
  ip_net_speed_cell varchar(255) NOT NULL,
  ip_metro_code integer NOT NULL,
  ip_area_code integer NOT NULL,
  ip_time_zone varchar(255) NOT NULL,
  ip_region_name varchar(255) NOT NULL,
  ip_domain varchar(255) NOT NULL,
  ip_country_name varchar(255) NOT NULL,
  ip_continent_code varchar(2) NOT NULL,
  ip_corporate_proxy varchar(3) NOT NULL,
  anonymous_proxy varchar(3) NOT NULL,
  proxy_score integer NOT NULL,
  is_trans_proxy varchar(3) NOT NULL,
  free_mail varchar(3) NOT NULL,
  carder_email varchar(3) NOT NULL,
  high_risk_username varchar(3) NOT NULL,
  high_risk_password varchar(3) NOT NULL,
  bin_match varchar(10) NOT NULL,
  bin_country varchar(2) NOT NULL,
  bin_name_match varchar(3) NOT NULL,
  bin_name varchar(255) NOT NULL,
  bin_phone_match varchar(3) NOT NULL,
  bin_phone varchar(32) NOT NULL,
  customer_phone_in_billing_location varchar(8) NOT NULL,
  ship_forward varchar(3) NOT NULL,
  city_postal_match varchar(3) NOT NULL,
  ship_city_postal_match varchar(3) NOT NULL,
  score decimal(10,5) NOT NULL,
  explanation text NOT NULL,
  risk_score decimal(10,5) NOT NULL,
  queries_remaining integer NOT NULL,
  maxmind_id varchar(8) NOT NULL,
  error text NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (order_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_order_history
--

DROP TABLE IF EXISTS oc_order_history;
CREATE TABLE oc_order_history (
  order_history_id serial NOT NULL,
  order_id integer NOT NULL,
  order_status_id integer NOT NULL,
  notify boolean NOT NULL DEFAULT FALSE,
  comment text NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (order_history_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_order_option
--

DROP TABLE IF EXISTS oc_order_option;
CREATE TABLE oc_order_option (
  order_option_id serial NOT NULL,
  order_id integer NOT NULL,
  order_product_id integer NOT NULL,
  product_option_id integer NOT NULL,
  product_option_value_id integer NOT NULL DEFAULT '0',
  name varchar(255) NOT NULL,
  value text NOT NULL,
  type varchar(32) NOT NULL,
  PRIMARY KEY (order_option_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_order_product
--

DROP TABLE IF EXISTS oc_order_product;
CREATE TABLE oc_order_product (
  order_product_id serial NOT NULL,
  order_id integer NOT NULL,
  product_id integer NOT NULL,
  name varchar(255) NOT NULL,
  model varchar(64) NOT NULL,
  quantity integer NOT NULL,
  price decimal(15,4) NOT NULL DEFAULT '0.0000',
  total decimal(15,4) NOT NULL DEFAULT '0.0000',
  tax decimal(15,4) NOT NULL DEFAULT '0.0000',
  reward integer NOT NULL,
  PRIMARY KEY (order_product_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_order_status
--

DROP TABLE IF EXISTS oc_order_status;
CREATE TABLE oc_order_status (
  order_status_id serial NOT NULL,
  language_id integer NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (order_status_id,language_id)
);

--
-- Dumping data for table oc_order_status
--

INSERT INTO oc_order_status (order_status_id, language_id, name) VALUES
(2, 1, 'Processing'),
(3, 1, 'Shipped'),
(7, 1, 'Canceled'),
(5, 1, 'Complete'),
(8, 1, 'Denied'),
(9, 1, 'Canceled Reversal'),
(10, 1, 'Failed'),
(11, 1, 'Refunded'),
(12, 1, 'Reversed'),
(13, 1, 'Chargeback'),
(1, 1, 'Pending'),
(16, 1, 'Voided'),
(15, 1, 'Processed'),
(14, 1, 'Expired');

-- --------------------------------------------------------

--
-- Table structure for table oc_order_total
--

DROP TABLE IF EXISTS oc_order_total;
CREATE TABLE oc_order_total (
  order_total_id serial NOT NULL,
  order_id integer NOT NULL,
  code varchar(32) NOT NULL,
  title varchar(255) NOT NULL,
  value decimal(15,4) NOT NULL DEFAULT '0.0000',
  sort_order integer NOT NULL,
  PRIMARY KEY (order_total_id)
);

CREATE INDEX idx_order_id ON oc_order_total (order_id);

-- --------------------------------------------------------

DROP TABLE IF EXISTS oc_order_voucher;
CREATE TABLE oc_order_voucher (
  order_voucher_id serial NOT NULL,
  order_id integer NOT NULL,
  voucher_id integer NOT NULL,
  description varchar(255) NOT NULL,
  code varchar(10) NOT NULL,
  from_name varchar(64) NOT NULL,
  from_email varchar(96) NOT NULL,
  to_name varchar(64) NOT NULL,
  to_email varchar(96) NOT NULL,
  voucher_theme_id integer NOT NULL,
  message text NOT NULL,
  amount decimal(15,4) NOT NULL,
  PRIMARY KEY (order_voucher_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_product
--

DROP TABLE IF EXISTS oc_product;
CREATE TABLE oc_product (
  product_id serial NOT NULL,
  model varchar(64) NOT NULL,
  sku varchar(64) NOT NULL,
  upc varchar(12) NOT NULL,
  ean varchar(14) NULL,
  jan varchar(13) NULL,
  isbn varchar(13) NULL,
  mpn varchar(64)  NULL,
  location varchar(128) NOT NULL,
  quantity integer NOT NULL DEFAULT '0',
  stock_status_id integer NOT NULL,
  image varchar(255) DEFAULT NULL,
  manufacturer_id integer NOT NULL,
  shipping boolean NOT NULL DEFAULT TRUE,
  price decimal(15,4) NOT NULL DEFAULT '0.0000',
  points integer NOT NULL DEFAULT '0',
  tax_class_id integer NOT NULL,
  date_available date NOT NULL,
  weight decimal(15,8) NOT NULL DEFAULT '0.00000000',
  weight_class_id integer NOT NULL DEFAULT '0',
  length decimal(15,8) NOT NULL DEFAULT '0.00000000',
  width decimal(15,8) NOT NULL DEFAULT '0.00000000',
  height decimal(15,8) NOT NULL DEFAULT '0.00000000',
  length_class_id integer NOT NULL DEFAULT '0',
  subtract boolean NOT NULL DEFAULT TRUE,
  minimum integer NOT NULL DEFAULT '1',
  sort_order integer NOT NULL DEFAULT '0',
  status boolean NOT NULL DEFAULT FALSE,
  viewed integer NOT NULL DEFAULT '0',
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (product_id)
);

--
-- Dumping data for table oc_product
--

INSERT INTO oc_product (product_id, model, sku, upc, location, quantity, stock_status_id, image, manufacturer_id, shipping, price, points, tax_class_id, date_available, weight, weight_class_id, length, width, height, length_class_id, subtract, minimum, sort_order, status, date_added, date_modified, viewed) VALUES
(28, 'Product 1', '', '', '', 939, 7, 'catalog/demo/htc_touch_hd_1.jpg', 5, TRUE, '100.0000', 200, 9, '2009-02-03', '146.40', 2, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 16:06:50', '2011-09-30 01:05:39', 0),
(29, 'Product 2', '', '', '', 999, 6, 'catalog/demo/palm_treo_pro_1.jpg', 6, TRUE, '279.9900', 0, 9, '2009-02-03', '133.00', 2, '0.00', '0.00', '0.00', 3, TRUE, 1, 0, TRUE, '2009-02-03 16:42:17', '2011-09-30 01:06:08', 0),
(30, 'Product 3', '', '', '', 7, 6, 'catalog/demo/canon_eos_5d_1.jpg', 9, TRUE, '100.0000', 0, 9, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 16:59:00', '2011-09-30 01:05:23', 0),
(31, 'Product 4', '', '', '', 1000, 6, 'catalog/demo/nikon_d300_1.jpg', 0, TRUE, '80.0000', 0, 9, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 3, TRUE, 1, 0, TRUE, '2009-02-03 17:00:10', '2011-09-30 01:06:00', 0),
(32, 'Product 5', '', '', '', 999, 6, 'catalog/demo/ipod_touch_1.jpg', 8, TRUE, '100.0000', 0, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 17:07:26', '2011-09-30 01:07:22', 0),
(33, 'Product 6', '', '', '', 1000, 6, 'catalog/demo/samsung_syncmaster_941bw.jpg', 0, TRUE, '200.0000', 0, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 17:08:31', '2011-09-30 01:06:29', 0),
(34, 'Product 7', '', '', '', 1000, 6, 'catalog/demo/ipod_shuffle_1.jpg', 8, TRUE, '100.0000', 0, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 18:07:54', '2011-09-30 01:07:17', 0),
(35, 'Product 8', '', '', '', 1000, 5, '', 0, FALSE, '100.0000', 0, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 18:08:31', '2011-09-30 01:06:17', 0),
(36, 'Product 9', '', '', '', 994, 6, 'catalog/demo/ipod_nano_1.jpg', 8, FALSE, '100.0000', 100, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 18:09:19', '2011-09-30 01:07:12', 0),
(40, 'product 11', '', '', '', 970, 5, 'catalog/demo/iphone_1.jpg', 8, TRUE, '101.0000', 0, 9, '2009-02-03', '10.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 21:07:12', '2011-09-30 01:06:53', 0),
(41, 'Product 14', '', '', '', 977, 5, 'catalog/demo/imac_1.jpg', 8, TRUE, '100.0000', 0, 9, '2009-02-03', '5.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 0, TRUE, '2009-02-03 21:07:26', '2011-09-30 01:06:44', 0),
(42, 'Product 15', '', '', '', 990, 5, 'catalog/demo/apple_cinema_30.jpg', 8, TRUE, '100.0000', 400, 9, '2009-02-04', '12.50', 1, '1.00', '2.00', '3.00', 1, TRUE, 2, 0, TRUE, '2009-02-03 21:07:37', '2011-09-30 00:46:19', 0),
(43, 'Product 16', '', '', '', 929, 5, 'catalog/demo/macbook_1.jpg', 8, FALSE, '500.0000', 0, 9, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 21:07:49', '2011-09-30 01:05:46', 0),
(44, 'Product 17', '', '', '', 1000, 5, 'catalog/demo/macbook_air_1.jpg', 8, TRUE, '1000.0000', 0, 9, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 21:08:00', '2011-09-30 01:05:53', 0),
(45, 'Product 18', '', '', '', 998, 5, 'catalog/demo/macbook_pro_1.jpg', 8, TRUE, '2000.0000', 0, 100, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 21:08:17', '2011-09-15 22:22:01', 0),
(46, 'Product 19', '', '', '', 1000, 5, 'catalog/demo/sony_vaio_1.jpg', 10, TRUE, '1000.0000', 0, 9, '2009-02-03', '0.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-03 21:08:29', '2011-09-30 01:06:39', 0),
(47, 'Product 21', '', '', '', 1000, 5, 'catalog/demo/hp_1.jpg', 7, TRUE, '100.0000', 400, 9, '2009-02-03', '1.00', 1, '0.00', '0.00', '0.00', 1, FALSE, 1, 0, TRUE, '2009-02-03 21:08:40', '2011-09-30 01:05:28', 0),
(48, 'product 20', 'test 1', '', 'test 2', 995, 5, 'catalog/demo/ipod_classic_1.jpg', 8, TRUE, '100.0000', 0, 9, '2009-02-08', '1.00', 1, '0.00', '0.00', '0.00', 2, TRUE, 1, 0, TRUE, '2009-02-08 17:21:51', '2011-09-30 01:07:06', 0),
(49, 'SAM1', '', '', '', 0, 8, 'catalog/demo/samsung_tab_1.jpg', 0, TRUE, '199.9900', 0, 9, '2011-04-25', '0.00', 1, '0.00', '0.00', '0.00', 1, TRUE, 1, 1, TRUE, '2011-04-26 08:57:34', '2011-09-30 01:06:23', 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_attribute
--

DROP TABLE IF EXISTS oc_product_attribute;
CREATE TABLE oc_product_attribute (
  product_id integer NOT NULL,
  attribute_id integer NOT NULL,
  language_id integer NOT NULL,
  text text NOT NULL,
  PRIMARY KEY (product_id,attribute_id,language_id)
);

--
-- Dumping data for table oc_product_attribute
--

INSERT INTO oc_product_attribute (product_id, attribute_id, language_id, text) VALUES
(43, 2, 1, '1'),
(47, 4, 1, '16GB'),
(43, 4, 1, '8gb'),
(42, 3, 1, '100mhz'),
(47, 2, 1, '4');

-- --------------------------------------------------------

--
-- Table structure for table oc_product_description
--

DROP TABLE IF EXISTS oc_product_description;
CREATE TABLE oc_product_description (
  product_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(255) NOT NULL,
  description text NOT NULL,
  tag text NULL,
  meta_title varchar(255) NULL,
  meta_description varchar(255) NOT NULL,
  meta_keyword varchar(255) NOT NULL,
  PRIMARY KEY (product_id,language_id)
);

CREATE INDEX idx_product_description_name ON oc_product_description (name);

--
-- Dumping data for table oc_product_description
--

INSERT INTO oc_product_description (product_id, language_id, name, description, meta_description, meta_keyword) VALUES
(35, 1, 'Product 8', '&lt;p&gt;\r\n	Product 8&lt;/p&gt;\r\n', '', ''),
(48, 1, 'iPod Classic', '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;More room to move.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			With 80GB or 160GB of storage and up to 40 hours of battery life, the new iPod classic lets you enjoy up to 40,000 songs or up to 200 hours of video or any combination wherever you go.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Cover Flow.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Browse through your music collection by flipping through album art. Select an album to turn it over and see the track list.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Enhanced interface.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Experience a whole new way to browse and view your music and video.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;strong&gt;Sleeker design.&lt;/strong&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Beautiful, durable, and sleeker than ever, iPod classic now features an anodized aluminum and polished stainless steel enclosure with rounded edges.&lt;/p&gt;\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;', '', ''),
(40, 1, 'iPhone', '&lt;p class=&quot;intro&quot;&gt;\r\n	iPhone is a revolutionary new mobile phone that allows you to make a call by simply tapping a name or number in your address book, a favorites list, or a call log. It also automatically syncs all your contacts from a PC, Mac, or Internet service. And it lets you select and listen to voicemail messages in whatever order you want just like email.&lt;/p&gt;\r\n', '', ''),
(28, 1, 'HTC Touch HD', '&lt;p&gt;\r\n	HTC Touch - in High Definition. Watch music videos and streaming content in awe-inspiring high definition clarity for a mobile experience you never thought possible. Seductively sleek, the HTC Touch HD provides the next generation of mobile functionality, all at a simple touch. Fully integrated with Windows Mobile Professional 6.1, ultrafast 3.5G, GPS, 5MP camera, plus lots more - all delivered on a breathtakingly crisp 3.8&amp;quot; WVGA touchscreen - you can take control of your mobile world with the HTC Touch HD.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Features&lt;/strong&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Processor Qualcomm&amp;reg; MSM 7201A&amp;trade; 528 MHz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Windows Mobile&amp;reg; 6.1 Professional Operating System&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Memory: 512 MB ROM, 288 MB RAM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Dimensions: 115 mm x 62.8 mm x 12 mm / 146.4 grams&lt;/li&gt;\r\n	&lt;li&gt;\r\n		3.8-inch TFT-LCD flat touch-sensitive screen with 480 x 800 WVGA resolution&lt;/li&gt;\r\n	&lt;li&gt;\r\n		HSDPA/WCDMA: Europe/Asia: 900/2100 MHz; Up to 2 Mbps up-link and 7.2 Mbps down-link speeds&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Quad-band GSM/GPRS/EDGE: Europe/Asia: 850/900/1800/1900 MHz (Band frequency, HSUPA availability, and data speed are operator dependent.)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Device Control via HTC TouchFLO&amp;trade; 3D &amp;amp; Touch-sensitive front panel buttons&lt;/li&gt;\r\n	&lt;li&gt;\r\n		GPS and A-GPS ready&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Bluetooth&amp;reg; 2.0 with Enhanced Data Rate and A2DP for wireless stereo headsets&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Wi-Fi&amp;reg;: IEEE 802.11 b/g&lt;/li&gt;\r\n	&lt;li&gt;\r\n		HTC ExtUSB&amp;trade; (11-pin mini-USB 2.0)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		5 megapixel color camera with auto focus&lt;/li&gt;\r\n	&lt;li&gt;\r\n		VGA CMOS color camera&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Built-in 3.5 mm audio jack, microphone, speaker, and FM radio&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Ring tone formats: AAC, AAC+, eAAC+, AMR-NB, AMR-WB, QCP, MP3, WMA, WAV&lt;/li&gt;\r\n	&lt;li&gt;\r\n		40 polyphonic and standard MIDI format 0 and 1 (SMF)/SP MIDI&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Rechargeable Lithium-ion or Lithium-ion polymer 1350 mAh battery&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Expansion Slot: microSD&amp;trade; memory card (SD 2.0 compatible)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		AC Adapter Voltage range/frequency: 100 ~ 240V AC, 50/60 Hz DC output: 5V and 1A&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Special Features: FM Radio, G-Sensor&lt;/li&gt;\r\n&lt;/ul&gt;\r\n', '', ''),
(44, 1, 'MacBook Air', '&lt;div&gt;\r\n	MacBook Air is ultrathin, ultraportable, and ultra unlike anything else. But you don&amp;rsquo;t lose inches and pounds overnight. It&amp;rsquo;s the result of rethinking conventions. Of multiple wireless innovations. And of breakthrough design. With MacBook Air, mobile computing suddenly has a new standard.&lt;/div&gt;\r\n', '', ''),
(45, 1, 'MacBook Pro', '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Latest Intel mobile architecture&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Powered by the most advanced mobile processors from Intel, the new Core 2 Duo MacBook Pro is over 50% faster than the original Core Duo MacBook Pro and now supports up to 4GB of RAM.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Leading-edge graphics&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			The NVIDIA GeForce 8600M GT delivers exceptional graphics processing power. For the ultimate creative canvas, you can even configure the 17-inch model with a 1920-by-1200 resolution display.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Designed for life on the road&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Innovations such as a magnetic power connection and an illuminated keyboard with ambient light sensor put the MacBook Pro in a class by itself.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Connect. Create. Communicate.&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Quickly set up a video conference with the built-in iSight camera. Control presentations and media from up to 30 feet away with the included Apple Remote. Connect to high-bandwidth peripherals with FireWire 800 and DVI.&lt;/p&gt;\r\n		&lt;p&gt;\r\n			&lt;b&gt;Next-generation wireless&lt;/b&gt;&lt;/p&gt;\r\n		&lt;p&gt;\r\n			Featuring 802.11n wireless technology, the MacBook Pro delivers up to five times the performance and up to twice the range of previous-generation technologies.&lt;/p&gt;\r\n	&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;', '', ''),
(29, 1, 'Palm Treo Pro', '&lt;p&gt;\r\n	Redefine your workday with the Palm Treo Pro smartphone. Perfectly balanced, you can respond to business and personal email, stay on top of appointments and contacts, and use Wi-Fi or GPS when you&amp;rsquo;re out and about. Then watch a video on YouTube, catch up with news and sports on the web, or listen to a few songs. Balance your work and play the way you like it, with the Palm Treo Pro.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Features&lt;/strong&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Windows Mobile&amp;reg; 6.1 Professional Edition&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Qualcomm&amp;reg; MSM7201 400MHz Processor&lt;/li&gt;\r\n	&lt;li&gt;\r\n		320x320 transflective colour TFT touchscreen&lt;/li&gt;\r\n	&lt;li&gt;\r\n		HSDPA/UMTS/EDGE/GPRS/GSM radio&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Tri-band UMTS &amp;mdash; 850MHz, 1900MHz, 2100MHz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Quad-band GSM &amp;mdash; 850/900/1800/1900&lt;/li&gt;\r\n	&lt;li&gt;\r\n		802.11b/g with WPA, WPA2, and 801.1x authentication&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Built-in GPS&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Bluetooth Version: 2.0 + Enhanced Data Rate&lt;/li&gt;\r\n	&lt;li&gt;\r\n		256MB storage (100MB user available), 128MB RAM&lt;/li&gt;\r\n	&lt;li&gt;\r\n		2.0 megapixel camera, up to 8x digital zoom and video capture&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Removable, rechargeable 1500mAh lithium-ion battery&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Up to 5.0 hours talk time and up to 250 hours standby&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MicroSDHC card expansion (up to 32GB supported)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MicroUSB 2.0 for synchronization and charging&lt;/li&gt;\r\n	&lt;li&gt;\r\n		3.5mm stereo headset jack&lt;/li&gt;\r\n	&lt;li&gt;\r\n		60mm (W) x 114mm (L) x 13.5mm (D) / 133g&lt;/li&gt;\r\n&lt;/ul&gt;\r\n', '', ''),
(36, 1, 'iPod Nano', '&lt;div&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Video in your pocket.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Its the small iPod with one very big idea: video. The worlds most popular music player now lets you enjoy movies, TV shows, and more on a two-inch display thats 65% brighter than before.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Cover Flow.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Browse through your music collection by flipping through album art. Select an album to turn it over and see the track list.&lt;strong&gt;&amp;nbsp;&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Enhanced interface.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Experience a whole new way to browse and view your music and video.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Sleek and colorful.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		With an anodized aluminum and polished stainless steel enclosure and a choice of five colors, iPod nano is dressed to impress.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;iTunes.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Available as a free download, iTunes makes it easy to browse and buy millions of songs, movies, TV shows, audiobooks, and games and download free podcasts all at the iTunes Store. And you can import your own music, manage your whole media library, and sync your iPod or iPhone with ease.&lt;/p&gt;\r\n&lt;/div&gt;\r\n', '', ''),
(46, 1, 'Sony VAIO', '&lt;div&gt;\r\n	Unprecedented power. The next generation of processing technology has arrived. Built into the newest VAIO notebooks lies Intel&amp;#39;s latest, most powerful innovation yet: Intel&amp;reg; Centrino&amp;reg; 2 processor technology. Boasting incredible speed, expanded wireless connectivity, enhanced multimedia support and greater energy efficiency, all the high-performance essentials are seamlessly combined into a single chip.&lt;/div&gt;\r\n', '', ''),
(47, 1, 'HP LP3065', '&lt;p&gt;\r\n	Stop your co-workers in their tracks with the stunning new 30-inch diagonal HP LP3065 Flat Panel Monitor. This flagship monitor features best-in-class performance and presentation features on a huge wide-aspect screen while letting you work as comfortably as possible - you might even forget you&amp;#39;re at the office&lt;/p&gt;\r\n', '', ''),
(32, 1, 'iPod Touch', '&lt;p&gt;\r\n	&lt;strong&gt;Revolutionary multi-touch interface.&lt;/strong&gt;&lt;br /&gt;\r\n	iPod touch features the same multi-touch screen technology as iPhone. Pinch to zoom in on a photo. Scroll through your songs and videos with a flick. Flip through your library by album artwork with Cover Flow.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Gorgeous 3.5-inch widescreen display.&lt;/strong&gt;&lt;br /&gt;\r\n	Watch your movies, TV shows, and photos come alive with bright, vivid color on the 320-by-480-pixel display.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Music downloads straight from iTunes.&lt;/strong&gt;&lt;br /&gt;\r\n	Shop the iTunes Wi-Fi Music Store from anywhere with Wi-Fi.1 Browse or search to find the music youre looking for, preview it, and buy it with just a tap.&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;strong&gt;Surf the web with Wi-Fi.&lt;/strong&gt;&lt;br /&gt;\r\n	Browse the web using Safari and watch YouTube videos on the first iPod with Wi-Fi built in&lt;br /&gt;\r\n	&amp;nbsp;&lt;/p&gt;\r\n', '', ''),
(41, 1, 'iMac', '&lt;div&gt;\r\n	Just when you thought iMac had everything, now there&acute;s even more. More powerful Intel Core 2 Duo processors. And more memory standard. Combine this with Mac OS X Leopard and iLife &acute;08, and it&acute;s more all-in-one than ever. iMac packs amazing performance into a stunningly slim space.&lt;/div&gt;\r\n', '', ''),
(33, 1, 'Samsung SyncMaster 941BW', '&lt;div&gt;\r\n	Imagine the advantages of going big without slowing down. The big 19&amp;quot; 941BW monitor combines wide aspect ratio with fast pixel response time, for bigger images, more room to work and crisp motion. In addition, the exclusive MagicBright 2, MagicColor and MagicTune technologies help deliver the ideal image in every situation, while sleek, narrow bezels and adjustable stands deliver style just the way you want it. With the Samsung 941BW widescreen analog/digital LCD monitor, it&amp;#39;s not hard to imagine.&lt;/div&gt;\r\n', '', ''),
(34, 1, 'iPod Shuffle', '&lt;div&gt;\r\n	&lt;strong&gt;Born to be worn.&lt;/strong&gt;\r\n	&lt;p&gt;\r\n		Clip on the worlds most wearable music player and take up to 240 songs with you anywhere. Choose from five colors including four new hues to make your musical fashion statement.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;strong&gt;Random meets rhythm.&lt;/strong&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		With iTunes autofill, iPod shuffle can deliver a new musical experience every time you sync. For more randomness, you can shuffle songs during playback with the slide of a switch.&lt;/p&gt;\r\n	&lt;strong&gt;Everything is easy.&lt;/strong&gt;\r\n	&lt;p&gt;\r\n		Charge and sync with the included USB dock. Operate the iPod shuffle controls with one hand. Enjoy up to 12 hours straight of skip-free music playback.&lt;/p&gt;\r\n&lt;/div&gt;\r\n', '', ''),
(43, 1, 'MacBook', '&lt;div&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Intel Core 2 Duo processor&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Powered by an Intel Core 2 Duo processor at speeds up to 2.16GHz, the new MacBook is the fastest ever.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;1GB memory, larger hard drives&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		The new MacBook now comes with 1GB of memory standard and larger hard drives for the entire line perfect for running more of your favorite applications and storing growing media collections.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Sleek, 1.08-inch-thin design&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		MacBook makes it easy to hit the road thanks to its tough polycarbonate case, built-in wireless technologies, and innovative MagSafe Power Adapter that releases automatically if someone accidentally trips on the cord.&lt;/p&gt;\r\n	&lt;p&gt;\r\n		&lt;b&gt;Built-in iSight camera&lt;/b&gt;&lt;/p&gt;\r\n	&lt;p&gt;\r\n		Right out of the box, you can have a video chat with friends or family,2 record a video at your desk, or take fun pictures with Photo Booth&lt;/p&gt;\r\n&lt;/div&gt;\r\n', '', ''),
(31, 1, 'Nikon D300', '&lt;div class=&quot;cpt_product_description &quot;&gt;\r\n	&lt;div&gt;\r\n		Engineered with pro-level features and performance, the 12.3-effective-megapixel D300 combines brand new technologies with advanced features inherited from Nikon&amp;#39;s newly announced D3 professional digital SLR camera to offer serious photographers remarkable performance combined with agility.&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		Similar to the D3, the D300 features Nikon&amp;#39;s exclusive EXPEED Image Processing System that is central to driving the speed and processing power needed for many of the camera&amp;#39;s new features. The D300 features a new 51-point autofocus system with Nikon&amp;#39;s 3D Focus Tracking feature and two new LiveView shooting modes that allow users to frame a photograph using the camera&amp;#39;s high-resolution LCD monitor. The D300 shares a similar Scene Recognition System as is found in the D3; it promises to greatly enhance the accuracy of autofocus, autoexposure, and auto white balance by recognizing the subject or scene being photographed and applying this information to the calculations for the three functions.&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		The D300 reacts with lightning speed, powering up in a mere 0.13 seconds and shooting with an imperceptible 45-millisecond shutter release lag time. The D300 is capable of shooting at a rapid six frames per second and can go as fast as eight frames per second when using the optional MB-D10 multi-power battery pack. In continuous bursts, the D300 can shoot up to 100 shots at full 12.3-megapixel resolution. (NORMAL-LARGE image setting, using a SanDisk Extreme IV 1GB CompactFlash card.)&lt;br /&gt;\r\n		&lt;br /&gt;\r\n		The D300 incorporates a range of innovative technologies and features that will significantly improve the accuracy, control, and performance photographers can get from their equipment. Its new Scene Recognition System advances the use of Nikon&amp;#39;s acclaimed 1,005-segment sensor to recognize colors and light patterns that help the camera determine the subject and the type of scene being photographed before a picture is taken. This information is used to improve the accuracy of autofocus, autoexposure, and auto white balance functions in the D300. For example, the camera can track moving subjects better and by identifying them, it can also automatically select focus points faster and with greater accuracy. It can also analyze highlights and more accurately determine exposure, as well as infer light sources to deliver more accurate white balance detection.&lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;!-- cpt_container_end --&gt;', '', ''),
(49, 1, 'Samsung Galaxy Tab 10.1', '&lt;p&gt;\r\n	Samsung Galaxy Tab 10.1, is the world&amp;rsquo;s thinnest tablet, measuring 8.6 mm thickness, running with Android 3.0 Honeycomb OS on a 1GHz dual-core Tegra 2 processor, similar to its younger brother Samsung Galaxy Tab 8.9.&lt;/p&gt;\r\n&lt;p&gt;\r\n	Samsung Galaxy Tab 10.1 gives pure Android 3.0 experience, adding its new TouchWiz UX or TouchWiz 4.0 &amp;ndash; includes a live panel, which lets you to customize with different content, such as your pictures, bookmarks, and social feeds, sporting a 10.1 inches WXGA capacitive touch screen with 1280 x 800 pixels of resolution, equipped with 3 megapixel rear camera with LED flash and a 2 megapixel front camera, HSPA+ connectivity up to 21Mbps, 720p HD video recording capability, 1080p HD playback, DLNA support, Bluetooth 2.1, USB 2.0, gyroscope, Wi-Fi 802.11 a/b/g/n, micro-SD slot, 3.5mm headphone jack, and SIM slot, including the Samsung Stick &amp;ndash; a Bluetooth microphone that can be carried in a pocket like a pen and sound dock with powered subwoofer.&lt;/p&gt;\r\n&lt;p&gt;\r\n	Samsung Galaxy Tab 10.1 will come in 16GB / 32GB / 64GB verities and pre-loaded with Social Hub, Reader&amp;rsquo;s Hub, Music Hub and Samsung Mini Apps Tray &amp;ndash; which gives you access to more commonly used apps to help ease multitasking and it is capable of Adobe Flash Player 10.2, powered by 6860mAh battery that gives you 10hours of video-playback time.&amp;nbsp;&amp;auml;&amp;ouml;&lt;/p&gt;\r\n', '', ''),
(42, 1, 'Apple Cinema 30&quot;', '&lt;p&gt;\r\n	&lt;font face=&quot;helvetica,geneva,arial&quot; size=&quot;2&quot;&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;The 30-inch Apple Cinema HD Display delivers an amazing 2560 x 1600 pixel resolution. Designed specifically for the creative professional, this display provides more space for easier access to all the tools and palettes needed to edit, format and composite your work. Combine this display with a Mac Pro, MacBook Pro, or PowerMac G5 and there&amp;#39;s no limit to what you can achieve. &lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;The Cinema HD features an active-matrix liquid crystal display that produces flicker-free images that deliver twice the brightness, twice the sharpness and twice the contrast ratio of a typical CRT display. Unlike other flat panels, it&amp;#39;s designed with a pure digital interface to deliver distortion-free images that never need adjusting. With over 4 million digital pixels, the display is uniquely suited for scientific and technical applications such as visualizing molecular structures or analyzing geological data. &lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Offering accurate, brilliant color performance, the Cinema HD delivers up to 16.7 million colors across a wide gamut allowing you to see subtle nuances between colors from soft pastels to rich jewel tones. A wide viewing angle ensures uniform color from edge to edge. Apple&amp;#39;s ColorSync technology allows you to create custom profiles to maintain consistent color onscreen and in print. The result: You can confidently use this display in all your color-critical applications. &lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;Housed in a new aluminum design, the display has a very thin bezel that enhances visual accuracy. Each display features two FireWire 400 ports and two USB 2.0 ports, making attachment of desktop peripherals, such as iSight, iPod, digital and still cameras, hard drives, printers and scanners, even more accessible and convenient. Taking advantage of the much thinner and lighter footprint of an LCD, the new displays support the VESA (Video Electronics Standards Association) mounting interface standard. Customers with the optional Cinema Display VESA Mount Adapter kit gain the flexibility to mount their display in locations most appropriate for their work environment. &lt;br /&gt;\r\n	&lt;br /&gt;\r\n	&lt;/font&gt;&lt;font face=&quot;Helvetica&quot; size=&quot;2&quot;&gt;The Cinema HD features a single cable design with elegant breakout for the USB 2.0, FireWire 400 and a pure digital connection using the industry standard Digital Video Interface (DVI) interface. The DVI connection allows for a direct pure-digital connection.&lt;br /&gt;\r\n	&lt;/font&gt;&lt;/font&gt;&lt;/p&gt;\r\n&lt;h3&gt;\r\n	Features:&lt;/h3&gt;\r\n&lt;p&gt;\r\n	Unrivaled display performance&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		30-inch (viewable) active-matrix liquid crystal display provides breathtaking image quality and vivid, richly saturated color.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Support for 2560-by-1600 pixel resolution for display of high definition still and video imagery.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Wide-format design for simultaneous display of two full pages of text and graphics.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Industry standard DVI connector for direct attachment to Mac- and Windows-based desktops and notebooks&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Incredibly wide (170 degree) horizontal and vertical viewing angle for maximum visibility and color performance.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Lightning-fast pixel response for full-motion digital video playback.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Support for 16.7 million saturated colors, for use in all graphics-intensive applications.&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Simple setup and operation&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Single cable with elegant breakout for connection to DVI, USB and FireWire ports&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Built-in two-port USB 2.0 hub for easy connection of desktop peripheral devices.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Two FireWire 400 ports to support iSight and other desktop peripherals&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Sleek, elegant design&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Huge virtual workspace, very small footprint.&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Narrow Bezel design to minimize visual impact of using dual displays&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Unique hinge design for effortless adjustment&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Support for VESA mounting solutions (Apple Cinema Display VESA Mount Adapter sold separately)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;h3&gt;\r\n	Technical specifications&lt;/h3&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Screen size (diagonal viewable image size)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Apple Cinema HD Display: 30 inches (29.7-inch viewable)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Screen type&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Thin film transistor (TFT) active-matrix liquid crystal display (AMLCD)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Resolutions&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		2560 x 1600 pixels (optimum resolution)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		2048 x 1280&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1920 x 1200&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1280 x 800&lt;/li&gt;\r\n	&lt;li&gt;\r\n		1024 x 640&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Display colors (maximum)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		16.7 million&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Viewing angle (typical)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		170&amp;deg; horizontal; 170&amp;deg; vertical&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Brightness (typical)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		30-inch Cinema HD Display: 400 cd/m2&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Contrast ratio (typical)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		700:1&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Response time (typical)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		16 ms&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Pixel pitch&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		30-inch Cinema HD Display: 0.250 mm&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Screen treatment&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Antiglare hardcoat&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;User controls (hardware and software)&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Display Power,&lt;/li&gt;\r\n	&lt;li&gt;\r\n		System sleep, wake&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Brightness&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Monitor tilt&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Connectors and cables&lt;/b&gt;&lt;br /&gt;\r\n	Cable&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		DVI (Digital Visual Interface)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		FireWire 400&lt;/li&gt;\r\n	&lt;li&gt;\r\n		USB 2.0&lt;/li&gt;\r\n	&lt;li&gt;\r\n		DC power (24 V)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	Connectors&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Two-port, self-powered USB 2.0 hub&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Two FireWire 400 ports&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Kensington security port&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;VESA mount adapter&lt;/b&gt;&lt;br /&gt;\r\n	Requires optional Cinema Display VESA Mount Adapter (M9649G/A)&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Compatible with VESA FDMI (MIS-D, 100, C) compliant mounting solutions&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Electrical requirements&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Input voltage: 100-240 VAC 50-60Hz&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Maximum power when operating: 150W&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Energy saver mode: 3W or less&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Environmental requirements&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Operating temperature: 50&amp;deg; to 95&amp;deg; F (10&amp;deg; to 35&amp;deg; C)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Storage temperature: -40&amp;deg; to 116&amp;deg; F (-40&amp;deg; to 47&amp;deg; C)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Operating humidity: 20% to 80% noncondensing&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Maximum operating altitude: 10,000 feet&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Agency approvals&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		FCC Part 15 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN55022 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN55024&lt;/li&gt;\r\n	&lt;li&gt;\r\n		VCCI Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		AS/NZS 3548 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		CNS 13438 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ICES-003 Class B&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ISO 13406 part 2&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MPR II&lt;/li&gt;\r\n	&lt;li&gt;\r\n		IEC 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		UL 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		CSA 60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		EN60950&lt;/li&gt;\r\n	&lt;li&gt;\r\n		ENERGY STAR&lt;/li&gt;\r\n	&lt;li&gt;\r\n		TCO &amp;#39;03&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;Size and weight&lt;/b&gt;&lt;br /&gt;\r\n	30-inch Apple Cinema HD Display&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Height: 21.3 inches (54.3 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Width: 27.2 inches (68.8 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Depth: 8.46 inches (21.5 cm)&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Weight: 27.5 pounds (12.5 kg)&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;\r\n	&lt;b&gt;System Requirements&lt;/b&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n	&lt;li&gt;\r\n		Mac Pro, all graphic options&lt;/li&gt;\r\n	&lt;li&gt;\r\n		MacBook Pro&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Power Mac G5 (PCI-X) with ATI Radeon 9650 or better or NVIDIA GeForce 6800 GT DDL or better&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Power Mac G5 (PCI Express), all graphics options&lt;/li&gt;\r\n	&lt;li&gt;\r\n		PowerBook G4 with dual-link DVI support&lt;/li&gt;\r\n	&lt;li&gt;\r\n		Windows PC and graphics card that supports DVI ports with dual-link digital bandwidth and VESA DDC standard for plug-and-play setup&lt;/li&gt;\r\n&lt;/ul&gt;\r\n', '', ''),
(30, 1, 'Canon EOS 5D', '&lt;p&gt;\r\n	Canon''s press material for the EOS 5D states that it ''defines (a) new D-SLR category'', while we''re not typically too concerned with marketing talk this particular statement is clearly pretty accurate. The EOS 5D is unlike any previous digital SLR in that it combines a full-frame (35 mm sized) high resolution sensor (12.8 megapixels) with a relatively compact body (slightly larger than the EOS 20D, although in your hand it feels noticeably ''chunkier''). The EOS 5D is aimed to slot in between the EOS 20D and the EOS-1D professional digital SLR''s, an important difference when compared to the latter is that the EOS 5D doesn''t have any environmental seals. While Canon don''t specifically refer to the EOS 5D as a ''professional'' digital SLR it will have obvious appeal to professionals who want a high quality digital SLR in a body lighter than the EOS-1D. It will also no doubt appeal to current EOS 20D owners (although lets hope they''ve not bought too many EF-S lenses...) äë&lt;/p&gt;\r\n', '', '');

-- --------------------------------------------------------

--
-- Table structure for table oc_product_discount
--

DROP TABLE IF EXISTS oc_product_discount;
CREATE TABLE oc_product_discount (
  product_discount_id serial NOT NULL,
  product_id integer NOT NULL,
  customer_group_id integer NOT NULL,
  quantity integer NOT NULL DEFAULT '0',
  priority integer NOT NULL DEFAULT '1',
  price decimal(15,4) NOT NULL DEFAULT '0.0000',
  date_start date NULL,
  date_end date NULL,
  PRIMARY KEY (product_discount_id)
);

CREATE INDEX idx_product_discount ON oc_product_discount (product_id);

--
-- Dumping data for table oc_product_discount
--

INSERT INTO oc_product_discount (product_discount_id, product_id, customer_group_id, quantity, priority, price, date_start, date_end) VALUES
(440, 42, 1, 30, 1, '66.0000', NULL, NULL),
(439, 42, 1, 20, 1, '77.0000', NULL, NULL),
(438, 42, 1, 10, 1, '88.0000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_filter
--

DROP TABLE IF EXISTS oc_product_filter;
CREATE TABLE oc_product_filter (
  product_id integer NOT NULL,
  filter_id integer NOT NULL,
  PRIMARY KEY (product_id,filter_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_image
--

DROP TABLE IF EXISTS oc_product_image;
CREATE TABLE oc_product_image (
  product_image_id serial NOT NULL,
  product_id integer NOT NULL,
  image varchar(255) DEFAULT NULL,
  sort_order integer NOT NULL DEFAULT '0',
  PRIMARY KEY (product_image_id)
);

CREATE INDEX idx_product_image ON oc_product_image (product_id);

--
-- Dumping data for table oc_product_image
--

INSERT INTO oc_product_image (product_image_id, product_id, image) VALUES
(2345, 30, 'catalog/demo/canon_eos_5d_2.jpg'),
(2321, 47, 'catalog/demo/hp_3.jpg'),
(2035, 28, 'catalog/demo/htc_touch_hd_2.jpg'),
(2351, 41, 'catalog/demo/imac_3.jpg'),
(1982, 40, 'catalog/demo/iphone_6.jpg'),
(2001, 36, 'catalog/demo/ipod_nano_5.jpg'),
(2000, 36, 'catalog/demo/ipod_nano_4.jpg'),
(2005, 34, 'catalog/demo/ipod_shuffle_5.jpg'),
(2004, 34, 'catalog/demo/ipod_shuffle_4.jpg'),
(2011, 32, 'catalog/demo/ipod_touch_7.jpg'),
(2010, 32, 'catalog/demo/ipod_touch_6.jpg'),
(2009, 32, 'catalog/demo/ipod_touch_5.jpg'),
(1971, 43, 'catalog/demo/macbook_5.jpg'),
(1970, 43, 'catalog/demo/macbook_4.jpg'),
(1974, 44, 'catalog/demo/macbook_air_4.jpg'),
(1973, 44, 'catalog/demo/macbook_air_2.jpg'),
(1977, 45, 'catalog/demo/macbook_pro_2.jpg'),
(1976, 45, 'catalog/demo/macbook_pro_3.jpg'),
(1986, 31, 'catalog/demo/nikon_d300_3.jpg'),
(1985, 31, 'catalog/demo/nikon_d300_2.jpg'),
(1988, 29, 'catalog/demo/palm_treo_pro_3.jpg'),
(1995, 46, 'catalog/demo/sony_vaio_5.jpg'),
(1994, 46, 'catalog/demo/sony_vaio_4.jpg'),
(1991, 48, 'catalog/demo/ipod_classic_4.jpg'),
(1990, 48, 'catalog/demo/ipod_classic_3.jpg'),
(1981, 40, 'catalog/demo/iphone_2.jpg'),
(1980, 40, 'catalog/demo/iphone_5.jpg'),
(2344, 30, 'catalog/demo/canon_eos_5d_3.jpg'),
(2320, 47, 'catalog/demo/hp_2.jpg'),
(2034, 28, 'catalog/demo/htc_touch_hd_3.jpg'),
(2350, 41, 'catalog/demo/imac_2.jpg'),
(1979, 40, 'catalog/demo/iphone_3.jpg'),
(1978, 40, 'catalog/demo/iphone_4.jpg'),
(1989, 48, 'catalog/demo/ipod_classic_2.jpg'),
(1999, 36, 'catalog/demo/ipod_nano_2.jpg'),
(1998, 36, 'catalog/demo/ipod_nano_3.jpg'),
(2003, 34, 'catalog/demo/ipod_shuffle_2.jpg'),
(2002, 34, 'catalog/demo/ipod_shuffle_3.jpg'),
(2008, 32, 'catalog/demo/ipod_touch_2.jpg'),
(2007, 32, 'catalog/demo/ipod_touch_3.jpg'),
(2006, 32, 'catalog/demo/ipod_touch_4.jpg'),
(1969, 43, 'catalog/demo/macbook_2.jpg'),
(1968, 43, 'catalog/demo/macbook_3.jpg'),
(1972, 44, 'catalog/demo/macbook_air_3.jpg'),
(1975, 45, 'catalog/demo/macbook_pro_4.jpg'),
(1984, 31, 'catalog/demo/nikon_d300_4.jpg'),
(1983, 31, 'catalog/demo/nikon_d300_5.jpg'),
(1987, 29, 'catalog/demo/palm_treo_pro_2.jpg'),
(1993, 46, 'catalog/demo/sony_vaio_2.jpg'),
(1992, 46, 'catalog/demo/sony_vaio_3.jpg'),
(2327, 49, 'catalog/demo/samsung_tab_7.jpg'),
(2326, 49, 'catalog/demo/samsung_tab_6.jpg'),
(2325, 49, 'catalog/demo/samsung_tab_5.jpg'),
(2324, 49, 'catalog/demo/samsung_tab_4.jpg'),
(2323, 49, 'catalog/demo/samsung_tab_3.jpg'),
(2322, 49, 'catalog/demo/samsung_tab_2.jpg'),
(2317, 42, 'catalog/demo/canon_logo.jpg'),
(2316, 42, 'catalog/demo/hp_1.jpg'),
(2315, 42, 'catalog/demo/compaq_presario.jpg'),
(2314, 42, 'catalog/demo/canon_eos_5d_1.jpg'),
(2313, 42, 'catalog/demo/canon_eos_5d_2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table oc_product_option
--

DROP TABLE IF EXISTS oc_product_option;
CREATE TABLE oc_product_option (
  product_option_id serial NOT NULL,
  product_id integer NOT NULL,
  option_id integer NOT NULL,
  value text NOT NULL,
  required boolean NOT NULL,
  PRIMARY KEY (product_option_id)
);

--
-- Dumping data for table oc_product_option
--

INSERT INTO oc_product_option (product_option_id, product_id, option_id, value, required) VALUES
(224, 35, 11, '', TRUE),
(225, 47, 12, '2011-04-22', TRUE),
(223, 42, 2, '', TRUE),
(217, 42, 5, '', TRUE),
(209, 42, 6, '', TRUE),
(218, 42, 1, '', TRUE),
(208, 42, 4, 'test', TRUE),
(219, 42, 8, '2011-02-20', TRUE),
(222, 42, 7, '', TRUE),
(221, 42, 9, '22:25', TRUE),
(220, 42, 10, '2011-02-20 22:25', TRUE),
(226, 30, 5, '', TRUE);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_option_value
--

DROP TABLE IF EXISTS oc_product_option_value;
CREATE TABLE oc_product_option_value (
  product_option_value_id serial NOT NULL,
  product_option_id integer NOT NULL,
  product_id integer NOT NULL,
  option_id integer NOT NULL,
  option_value_id integer NOT NULL,
  quantity integer NOT NULL,
  subtract boolean NOT NULL,
  price decimal(15,4) NOT NULL,
  price_prefix varchar(1) NOT NULL,
  points integer NOT NULL,
  points_prefix varchar(1) NOT NULL,
  weight decimal(15,8) NOT NULL,
  weight_prefix varchar(1) NOT NULL,
  PRIMARY KEY (product_option_value_id)
);

--
-- Dumping data for table oc_product_option_value
--

INSERT INTO oc_product_option_value (product_option_value_id, product_option_id, product_id, option_id, option_value_id, quantity, subtract, price, price_prefix, points, points_prefix, weight, weight_prefix) VALUES
(1, 217, 42, 5, 41, 100, FALSE, '1.0000', '+', 0, '+', '1.00000000', '+'),
(6, 218, 42, 1, 31, 146, TRUE, '20.0000', '+', 2, '-', '20.00000000', '+'),
(7, 218, 42, 1, 43, 300, TRUE, '30.0000', '+', 3, '+', '30.00000000', '+'),
(5, 218, 42, 1, 32, 96, TRUE, '10.0000', '+', 1, '+', '10.00000000', '+'),
(4, 217, 42, 5, 39, 92, TRUE, '4.0000', '+', 0, '+', '4.00000000', '+'),
(2, 217, 42, 5, 42, 200, TRUE, '2.0000', '+', 0, '+', '2.00000000', '+'),
(3, 217, 42, 5, 40, 300, FALSE, '3.0000', '+', 0, '+', '3.00000000', '+'),
(8, 223, 42, 2, 23, 48, TRUE, '10.0000', '+', 0, '+', '10.00000000', '+'),
(10, 223, 42, 2, 44, 2696, TRUE, '30.0000', '+', 0, '+', '30.00000000', '+'),
(9, 223, 42, 2, 24, 194, TRUE, '20.0000', '+', 0, '+', '20.00000000', '+'),
(11, 223, 42, 2, 45, 3998, TRUE, '40.0000', '+', 0, '+', '40.00000000', '+'),
(12, 224, 35, 11, 46, 0, TRUE, '5.0000', '+', 0, '+', '0.00000000', '+'),
(13, 224, 35, 11, 47, 10, TRUE, '10.0000', '+', 0, '+', '0.00000000', '+'),
(14, 224, 35, 11, 48, 15, TRUE, '15.0000', '+', 0, '+', '0.00000000', '+'),
(16, 226, 30, 5, 40, 5, TRUE, '0.0000', '+', 0, '+', '0.00000000', '+'),
(15, 226, 30, 5, 39, 2, TRUE, '0.0000', '+', 0, '+', '0.00000000', '+');

-- --------------------------------------------------------

--
-- Table structure for table oc_product_related
--

DROP TABLE IF EXISTS oc_product_related;
CREATE TABLE oc_product_related (
  product_id integer NOT NULL,
  related_id integer NOT NULL,
  PRIMARY KEY (product_id,related_id)
);

--
-- Dumping data for table oc_product_related
--

INSERT INTO oc_product_related (product_id, related_id) VALUES
(40, 42),
(41, 42),
(42, 40),
(42, 41);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_reward
--

DROP TABLE IF EXISTS oc_product_reward;
CREATE TABLE oc_product_reward (
  product_reward_id serial NOT NULL,
  product_id integer NOT NULL DEFAULT '0',
  customer_group_id integer NOT NULL DEFAULT '0',
  points integer NOT NULL DEFAULT '0',
  PRIMARY KEY (product_reward_id)
);

--
-- Dumping data for table oc_product_reward
--

INSERT INTO oc_product_reward (product_reward_id, product_id, customer_group_id, points) VALUES
(515, 42, 1, 100),
(519, 47, 1, 300),
(379, 28, 1, 400),
(329, 43, 1, 600),
(339, 29, 1, 0),
(343, 48, 1, 0),
(335, 40, 1, 0),
(539, 30, 1, 200),
(331, 44, 1, 700),
(333, 45, 1, 800),
(337, 31, 1, 0),
(425, 35, 1, 0),
(345, 33, 1, 0),
(347, 46, 1, 0),
(545, 41, 1, 0),
(351, 36, 1, 0),
(353, 34, 1, 0),
(355, 32, 1, 0),
(521, 49, 1, 1000);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_special
--

DROP TABLE IF EXISTS oc_product_special;
CREATE TABLE oc_product_special (
  product_special_id serial NOT NULL,
  product_id integer NOT NULL,
  customer_group_id integer NOT NULL,
  priority integer NOT NULL DEFAULT '1',
  price decimal(15,4) NOT NULL DEFAULT '0.0000',
  date_start date NULL DEFAULT NULL,
  date_end date NULL DEFAULT NULL,
  PRIMARY KEY (product_special_id)
);

CREATE INDEX idx_product_special_product_id ON oc_product_special (product_id);

--
-- Dumping data for table oc_product_special
--

INSERT INTO oc_product_special (product_special_id, product_id, customer_group_id, priority, price) VALUES
(419, 42, 1, 1, '90.0000'),
(439, 30, 1, 2, '90.0000'),
(438, 30, 1, 1, '80.0000');

-- --------------------------------------------------------

--
-- Table structure for table oc_product_to_category
--

DROP TABLE IF EXISTS oc_product_to_category;
CREATE TABLE oc_product_to_category (
  product_id integer NOT NULL,
  category_id integer NOT NULL,
  PRIMARY KEY (product_id,category_id)
);

CREATE INDEX idx_product_to_category_product_id ON oc_product_to_category (product_id);

--
-- Dumping data for table oc_product_to_category
--

INSERT INTO oc_product_to_category (product_id, category_id) VALUES
(28, 20),
(28, 24),
(29, 20),
(29, 24),
(30, 20),
(30, 33),
(31, 33),
(32, 34),
(33, 20),
(33, 28),
(34, 34),
(35, 20),
(36, 34),
(40, 20),
(40, 24),
(41, 27),
(42, 20),
(42, 28),
(43, 18),
(43, 20),
(44, 18),
(44, 20),
(45, 18),
(46, 18),
(46, 20),
(47, 18),
(47, 20),
(48, 20),
(48, 34),
(49, 57);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_to_download
--

DROP TABLE IF EXISTS oc_product_to_download;
CREATE TABLE oc_product_to_download (
  product_id integer NOT NULL,
  download_id integer NOT NULL,
  PRIMARY KEY (product_id,download_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_to_layout
--

DROP TABLE IF EXISTS oc_product_to_layout;
CREATE TABLE oc_product_to_layout (
  product_id integer NOT NULL,
  store_id integer NOT NULL,
  layout_id integer NOT NULL,
  PRIMARY KEY (product_id,store_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_product_to_store
--

DROP TABLE IF EXISTS oc_product_to_store;
CREATE TABLE oc_product_to_store (
  product_id integer NOT NULL,
  store_id integer NOT NULL DEFAULT '0',
  PRIMARY KEY (product_id,store_id)
);

--
-- Dumping data for table oc_product_to_store
--

INSERT INTO oc_product_to_store (product_id, store_id) VALUES
(28, 0),
(29, 0),
(30, 0),
(31, 0),
(32, 0),
(33, 0),
(34, 0),
(35, 0),
(36, 0),
(40, 0),
(41, 0),
(42, 0),
(43, 0),
(44, 0),
(45, 0),
(46, 0),
(47, 0),
(48, 0),
(49, 0);

-- --------------------------------------------------------

--
-- Table structure for table oc_return
--

DROP TABLE IF EXISTS oc_return;
CREATE TABLE oc_return (
  return_id serial NOT NULL,
  order_id integer NOT NULL,
  product_id integer NOT NULL,
  customer_id integer NOT NULL,
  firstname varchar(32) NOT NULL,
  lastname varchar(32) NOT NULL,
  email varchar(96) NOT NULL,
  telephone varchar(32) NOT NULL,
  product varchar(255) NOT NULL,
  model varchar(64) NOT NULL,
  quantity integer NOT NULL,
  opened boolean NOT NULL,
  return_reason_id integer NOT NULL,
  return_action_id integer NOT NULL,
  return_status_id integer NOT NULL,
  comment text,
  date_ordered date NOT NULL,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NOT NULL,
  PRIMARY KEY (return_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_return_action
--

DROP TABLE IF EXISTS oc_return_action;
CREATE TABLE oc_return_action (
  return_action_id serial NOT NULL,
  language_id integer NOT NULL DEFAULT '0',
  name varchar(64) NOT NULL,
  PRIMARY KEY (return_action_id,language_id)
);

--
-- Dumping data for table oc_return_action
--

INSERT INTO oc_return_action (return_action_id, language_id, name) VALUES
(1, 1, 'Refunded'),
(2, 1, 'Credit Issued'),
(3, 1, 'Replacement Sent');

-- --------------------------------------------------------

--
-- Table structure for table oc_return_history
--

DROP TABLE IF EXISTS oc_return_history;
CREATE TABLE oc_return_history (
  return_history_id serial NOT NULL,
  return_id integer NOT NULL,
  return_status_id integer NOT NULL,
  notify boolean NOT NULL,
  comment text NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (return_history_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_return_reason
--

DROP TABLE IF EXISTS oc_return_reason;
CREATE TABLE oc_return_reason (
  return_reason_id serial NOT NULL,
  language_id integer NOT NULL DEFAULT '0',
  name varchar(128) NOT NULL,
  PRIMARY KEY (return_reason_id,language_id)
);

--
-- Dumping data for table oc_return_reason
--

INSERT INTO oc_return_reason (return_reason_id, language_id, name) VALUES
(1, 1, 'Dead On Arrival'),
(2, 1, 'Received Wrong Item'),
(3, 1, 'Order Error'),
(4, 1, 'Faulty, please supply details'),
(5, 1, 'Other, please supply details');

-- --------------------------------------------------------

--
-- Table structure for table oc_return_status
--

DROP TABLE IF EXISTS oc_return_status;
CREATE TABLE oc_return_status (
  return_status_id serial NOT NULL,
  language_id integer NOT NULL DEFAULT '0',
  name varchar(32) NOT NULL,
  PRIMARY KEY (return_status_id,language_id)
);

--
-- Dumping data for table oc_return_status
--

INSERT INTO oc_return_status (language_id, name) VALUES
(1, 'Pending'),
(1, 'Complete'),
(1, 'Awaiting Products');

-- --------------------------------------------------------

--
-- Table structure for table oc_review
--

DROP TABLE IF EXISTS oc_review;
CREATE TABLE oc_review (
  review_id serial NOT NULL,
  product_id integer NOT NULL,
  customer_id integer NOT NULL,
  author varchar(64) NOT NULL,
  text text NOT NULL,
  rating integer NOT NULL,
  status boolean NOT NULL DEFAULT FALSE,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (review_id)
);

CREATE INDEX idx_review_product_id ON oc_review (product_id);

-- --------------------------------------------------------

--
-- Table structure for table oc_setting
--

DROP TABLE IF EXISTS oc_setting;
CREATE TABLE oc_setting (
  setting_id serial NOT NULL,
  store_id integer NOT NULL DEFAULT '0',
  "group" varchar(32) NOT NULL,
  key varchar(64) NOT NULL,
  value text NOT NULL,
  serialized boolean NOT NULL,
  PRIMARY KEY (setting_id)
);

--
-- Dumping data for table oc_setting
--

INSERT INTO oc_setting (store_id, "group", key, value, serialized) VALUES
(0, 'shipping', 'shipping_sort_order', '3', FALSE),
(0, 'sub_total', 'sub_total_sort_order', '1', FALSE),
(0, 'sub_total', 'sub_total_status', '1', FALSE),
(0, 'tax', 'tax_status', '1', FALSE),
(0, 'total', 'total_sort_order', '9', FALSE),
(0, 'total', 'total_status', '1', FALSE),
(0, 'tax', 'tax_sort_order', '5', FALSE),
(0, 'free_checkout', 'free_checkout_sort_order', '1', FALSE),
(0, 'cod', 'cod_sort_order', '5', FALSE),
(0, 'cod', 'cod_total', '0.01', FALSE),
(0, 'cod', 'cod_order_status_id', '1', FALSE),
(0, 'cod', 'cod_geo_zone_id', '0', FALSE),
(0, 'cod', 'cod_status', '1', FALSE),
(0, 'shipping', 'shipping_status', '1', FALSE),
(0, 'shipping', 'shipping_estimator', '1', FALSE),
(0, 'config', 'config_google_analytics', '', FALSE),
(0, 'config', 'config_error_filename', 'error.log', FALSE),
(0, 'config', 'config_error_log', '1', FALSE),
(0, 'config', 'config_error_display', '1', FALSE),
(0, 'config', 'config_compression', '0', FALSE),
(0, 'config', 'config_encryption', 'SUBSTRING(SHA1(RAND()) FROM 1 FOR 8)', FALSE),
(0, 'config', 'config_maintenance', '0', FALSE),
(0, 'config', 'config_account_mail', '0', FALSE),
(0, 'config', 'config_mail_alert', '', FALSE),
(0, 'config', 'config_secure', '0', FALSE),
(0, 'config', 'config_seo_url', '0', FALSE),
(0, 'coupon', 'coupon_sort_order', '4', FALSE),
(0, 'coupon', 'coupon_status', '1', FALSE),
(0, 'config', 'config_order_mail', '0', FALSE),
(0, 'config', 'config_smtp_username', '', FALSE),
(0, 'config', 'config_smtp_password', '', FALSE),
(0, 'config', 'config_smtp_port', '25', FALSE),
(0, 'config', 'config_smtp_timeout', '5', FALSE),
(0, 'flat', 'flat_sort_order', '1', FALSE),
(0, 'flat', 'flat_status', '1', FALSE),
(0, 'flat', 'flat_geo_zone_id', '0', FALSE),
(0, 'flat', 'flat_tax_class_id', '9', FALSE),
(0, 'carousel', 'carousel_module', 'a:1:{i:0;a:10:{s:9:"banner_id";s:1:"8";s:5:"limit";s:1:"5";s:6:"scroll";s:1:"3";s:5:"width";s:2:"80";s:6:"height";s:2:"80";s:11:"resize_type";s:7:"default";s:9:"layout_id";s:1:"1";s:8:"position";s:14:"content_bottom";s:6:"status";s:1:"1";s:10:"sort_order";s:2:"-1";}}', TRUE),
(0, 'featured', 'featured_product', '43,40,42,49,46,47,28', FALSE),
(0, 'featured', 'featured_module', 'a:1:{i:0;a:8:{s:5:"limit";s:1:"6";s:11:"image_width";s:2:"80";s:12:"image_height";s:2:"80";s:11:"resize_type";s:7:"default";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"content_top";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"2";}}', TRUE),
(0, 'flat', 'flat_cost', '5.00', FALSE),
(0, 'credit', 'credit_sort_order', '7', FALSE),
(0, 'credit', 'credit_status', '1', FALSE),
(0, 'config', 'config_smtp_host', '', FALSE),
(0, 'config', 'config_image_cart_height', '47', FALSE),
(0, 'config', 'config_mail_protocol', 'mail', FALSE),
(0, 'config', 'config_mail_parameter', '', FALSE),
(0, 'config', 'config_image_wishlist_height', '47', FALSE),
(0, 'config', 'config_image_cart_width', '47', FALSE),
(0, 'config', 'config_image_wishlist_width', '47', FALSE),
(0, 'config', 'config_image_compare_height', '90', FALSE),
(0, 'config', 'config_image_compare_width', '90', FALSE),
(0, 'reward', 'reward_sort_order', '2', FALSE),
(0, 'reward', 'reward_status', '1', FALSE),
(0, 'config', 'config_image_related_height', '80', FALSE),
(0, 'affiliate', 'affiliate_module', 'a:1:{i:0;a:4:{s:9:"layout_id";s:2:"10";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', TRUE),
(0, 'category', 'category_module', 'a:2:{i:0;a:5:{s:9:"layout_id";s:1:"3";s:8:"position";s:11:"column_left";s:5:"count";s:1:"0";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}i:1;a:5:{s:9:"layout_id";s:1:"2";s:8:"position";s:11:"column_left";s:5:"count";s:1:"0";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', TRUE),
(0, 'config', 'config_image_related_width', '80', FALSE),
(0, 'config', 'config_image_additional_height', '74', FALSE),
(0, 'account', 'account_module', 'a:1:{i:0;a:4:{s:9:"layout_id";s:1:"6";s:8:"position";s:12:"column_right";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', TRUE),
(0, 'config', 'config_image_additional_width', '74', FALSE),
(0, 'config', 'config_image_manufacturer_height', '80', FALSE),
(0, 'config', 'config_image_manufacturer_width', '80', FALSE),
(0, 'config', 'config_image_category_height', '80', FALSE),
(0, 'config', 'config_image_category_width', '80', FALSE),
(0, 'config', 'config_image_product_height', '80', FALSE),
(0, 'config', 'config_image_product_width', '80', FALSE),
(0, 'config', 'config_image_popup_height', '500', FALSE),
(0, 'config', 'config_image_popup_width', '500', FALSE),
(0, 'config', 'config_image_thumb_height', '228', FALSE),
(0, 'config', 'config_image_thumb_width', '228', FALSE),
(0, 'config', 'config_icon', 'data/cart.png', FALSE),
(0, 'config', 'config_logo', 'data/logo.png', FALSE),
(0, 'config', 'config_cart_weight', '1', FALSE),
(0, 'config', 'config_upload_allowed', 'jpg, JPG, jpeg, gif, png, txt', FALSE),
(0, 'config', 'config_file_extension_allowed', 'txt\r\npng\r\njpe\r\njpeg\r\njpg\r\ngif\r\nbmp\r\nico\r\ntiff\r\ntif\r\nsvg\r\nsvgz\r\nzip\r\nrar\r\nmsi\r\ncab\r\nmp3\r\nqt\r\nmov\r\npdf\r\npsd\r\nai\r\neps\r\nps\r\ndoc\r\nrtf\r\nxls\r\nppt\r\nodt\r\nods', FALSE),
(0, 'config', 'config_file_mime_allowed', 'text/plain\r\nimage/png\r\nimage/jpeg\r\nimage/jpeg\r\nimage/jpeg\r\nimage/gif\r\nimage/bmp\r\nimage/vnd.microsoft.icon\r\nimage/tiff\r\nimage/tiff\r\nimage/svg+xml\r\nimage/svg+xml\r\napplication/zip\r\napplication/x-rar-compressed\r\napplication/x-msdownload\r\napplication/vnd.ms-cab-compressed\r\naudio/mpeg\r\nvideo/quicktime\r\nvideo/quicktime\r\napplication/pdf\r\nimage/vnd.adobe.photoshop\r\napplication/postscript\r\napplication/postscript\r\napplication/postscript\r\napplication/msword\r\napplication/rtf\r\napplication/vnd.ms-excel\r\napplication/vnd.ms-powerpoint\r\napplication/vnd.oasis.opendocument.text\r\napplication/vnd.oasis.opendocument.spreadsheet', FALSE),
(0, 'config', 'config_review_status', '1', FALSE),
(0, 'config', 'config_download', '1', FALSE),
(0, 'config', 'config_return_status_id', '2', FALSE),
(0, 'config', 'config_complete_status_id', '5', FALSE),
(0, 'config', 'config_order_status_id', '1', FALSE),
(0, 'config', 'config_stock_status_id', '5', FALSE),
(0, 'config', 'config_stock_checkout', '0', FALSE),
(0, 'config', 'config_stock_warning', '0', FALSE),
(0, 'config', 'config_stock_display', '0', FALSE),
(0, 'config', 'config_affiliate_commission', '5', FALSE),
(0, 'config', 'config_affiliate_id', '4', FALSE),
(0, 'config', 'config_checkout_id', '5', FALSE),
(0, 'config', 'config_checkout_guest', '1', FALSE),
(0, 'config', 'config_account_id', '3', FALSE),
(0, 'config', 'config_customer_price', '0', FALSE),
(0, 'config', 'config_customer_group_id', '1', FALSE),
(0, 'voucher', 'voucher_sort_order', '8', FALSE),
(0, 'voucher', 'voucher_status', '1', FALSE),
(0, 'config', 'config_length_class_id', '1', FALSE),
(0, 'config', 'config_invoice_prefix', 'INV-2013-00', FALSE),
(0, 'config', 'config_tax', '1', FALSE),
(0, 'config', 'config_tax_customer', 'shipping', FALSE),
(0, 'config', 'config_tax_default', 'shipping', FALSE),
(0, 'config', 'config_limit_admin', '20', FALSE),
(0, 'config', 'config_product_limit', '15', FALSE),
(0, 'free_checkout', 'free_checkout_status', '1', FALSE),
(0, 'free_checkout', 'free_checkout_order_status_id', '1', FALSE),
(0, 'config', 'config_weight_class_id', '1', FALSE),
(0, 'config', 'config_currency_auto', '1', FALSE),
(0, 'config', 'config_currency', 'USD', FALSE),
(0, 'slideshow', 'slideshow_module', 'a:1:{i:0;a:8:{s:9:"banner_id";s:1:"7";s:5:"width";s:3:"980";s:6:"height";s:3:"280";s:11:"resize_type";s:7:"default";s:9:"layout_id";s:1:"1";s:8:"position";s:11:"content_top";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"1";}}', TRUE),
(0, 'banner', 'banner_module', 'a:1:{i:0;a:8:{s:9:"banner_id";s:1:"6";s:5:"width";s:3:"182";s:6:"height";s:3:"182";s:11:"resize_type";s:7:"default";s:9:"layout_id";s:1:"3";s:8:"position";s:11:"column_left";s:6:"status";s:1:"1";s:10:"sort_order";s:1:"3";}}', TRUE),
(0, 'config', 'config_name', 'Your Store', FALSE),
(0, 'config', 'config_owner', 'Your Name', FALSE),
(0, 'config', 'config_address', 'Address 1', FALSE),
(0, 'config', 'config_email', 'your@store.com', FALSE),
(0, 'config', 'config_telephone', '123456789', FALSE),
(0, 'config', 'config_fax', '', FALSE),
(0, 'config', 'config_meta_title', 'Your Store', FALSE),
(0, 'config', 'config_meta_description', 'My Store', FALSE),
(0, 'config', 'config_template', 'default', FALSE),
(0, 'config', 'config_layout_id', '4', FALSE),
(0, 'config', 'config_country_id', '222', FALSE),
(0, 'config', 'config_zone_id', '3563', FALSE),
(0, 'config', 'config_language', 'en', FALSE),
(0, 'config', 'config_admin_language', 'en', FALSE),
(0, 'config', 'config_order_edit', '100', FALSE),
(0, 'config', 'config_voucher_min', '1', FALSE),
(0, 'config', 'config_voucher_max', '1000', FALSE),
(0, 'config', 'config_customer_group_display', 'a:1:{i:0;s:1:\"1\";}', TRUE),
(0, 'config', 'config_robots', 'abot\r\ndbot\r\nebot\r\nhbot\r\nkbot\r\nlbot\r\nmbot\r\nnbot\r\nobot\r\npbot\r\nrbot\r\nsbot\r\ntbot\r\nvbot\r\nybot\r\nzbot\r\nbot.\r\nbot/\r\n_bot\r\n.bot\r\n/bot\r\n-bot\r\n:bot\r\n(bot\r\ncrawl\r\nslurp\r\nspider\r\nseek\r\naccoona\r\nacoon\r\nadressendeutschland\r\nah-ha.com\r\nahoy\r\naltavista\r\nananzi\r\nanthill\r\nappie\r\narachnophilia\r\narale\r\naraneo\r\naranha\r\narchitext\r\naretha\r\narks\r\nasterias\r\natlocal\r\natn\r\natomz\r\naugurfind\r\nbackrub\r\nbannana_bot\r\nbaypup\r\nbdfetch\r\nbig brother\r\nbiglotron\r\nbjaaland\r\nblackwidow\r\nblaiz\r\nblog\r\nblo.\r\nbloodhound\r\nboitho\r\nbooch\r\nbradley\r\nbutterfly\r\ncalif\r\ncassandra\r\nccubee\r\ncfetch\r\ncharlotte\r\nchurl\r\ncienciaficcion\r\ncmc\r\ncollective\r\ncomagent\r\ncombine\r\ncomputingsite\r\ncsci\r\ncurl\r\ncusco\r\ndaumoa\r\ndeepindex\r\ndelorie\r\ndepspid\r\ndeweb\r\ndie blinde kuh\r\ndigger\r\nditto\r\ndmoz\r\ndocomo\r\ndownload express\r\ndtaagent\r\ndwcp\r\nebiness\r\nebingbong\r\ne-collector\r\nejupiter\r\nemacs-w3 search engine\r\nesther\r\nevliya celebi\r\nezresult\r\nfalcon\r\nfelix ide\r\nferret\r\nfetchrover\r\nfido\r\nfindlinks\r\nfireball\r\nfish search\r\nfouineur\r\nfunnelweb\r\ngazz\r\ngcreep\r\ngenieknows\r\ngetterroboplus\r\ngeturl\r\nglx\r\ngoforit\r\ngolem\r\ngrabber\r\ngrapnel\r\ngralon\r\ngriffon\r\ngromit\r\ngrub\r\ngulliver\r\nhamahakki\r\nharvest\r\nhavindex\r\nhelix\r\nheritrix\r\nhku www octopus\r\nhomerweb\r\nhtdig\r\nhtml index\r\nhtml_analyzer\r\nhtmlgobble\r\nhubater\r\nhyper-decontextualizer\r\nia_archiver\r\nibm_planetwide\r\nichiro\r\niconsurf\r\niltrovatore\r\nimage.kapsi.net\r\nimagelock\r\nincywincy\r\nindexer\r\ninfobee\r\ninformant\r\ningrid\r\ninktomisearch.com\r\ninspector web\r\nintelliagent\r\ninternet shinchakubin\r\nip3000\r\niron33\r\nisraeli-search\r\nivia\r\njack\r\njakarta\r\njavabee\r\njetbot\r\njumpstation\r\nkatipo\r\nkdd-explorer\r\nkilroy\r\nknowledge\r\nkototoi\r\nkretrieve\r\nlabelgrabber\r\nlachesis\r\nlarbin\r\nlegs\r\nlibwww\r\nlinkalarm\r\nlink validator\r\nlinkscan\r\nlockon\r\nlwp\r\nlycos\r\nmagpie\r\nmantraagent\r\nmapoftheinternet\r\nmarvin/\r\nmattie\r\nmediafox\r\nmediapartners\r\nmercator\r\nmerzscope\r\nmicrosoft url control\r\nminirank\r\nmiva\r\nmj12\r\nmnogosearch\r\nmoget\r\nmonster\r\nmoose\r\nmotor\r\nmultitext\r\nmuncher\r\nmuscatferret\r\nmwd.search\r\nmyweb\r\nnajdi\r\nnameprotect\r\nnationaldirectory\r\nnazilla\r\nncsa beta\r\nnec-meshexplorer\r\nnederland.zoek\r\nnetcarta webmap engine\r\nnetmechanic\r\nnetresearchserver\r\nnetscoop\r\nnewscan-online\r\nnhse\r\nnokia6682/\r\nnomad\r\nnoyona\r\nnutch\r\nnzexplorer\r\nobjectssearch\r\noccam\r\nomni\r\nopen text\r\nopenfind\r\nopenintelligencedata\r\norb search\r\nosis-project\r\npack rat\r\npageboy\r\npagebull\r\npage_verifier\r\npanscient\r\nparasite\r\npartnersite\r\npatric\r\npear.\r\npegasus\r\nperegrinator\r\npgp key agent\r\nphantom\r\nphpdig\r\npicosearch\r\npiltdownman\r\npimptrain\r\npinpoint\r\npioneer\r\npiranha\r\nplumtreewebaccessor\r\npogodak\r\npoirot\r\npompos\r\npoppelsdorf\r\npoppi\r\npopular iconoclast\r\npsycheclone\r\npublisher\r\npython\r\nrambler\r\nraven search\r\nroach\r\nroad runner\r\nroadhouse\r\nrobbie\r\nrobofox\r\nrobozilla\r\nrules\r\nsalty\r\nsbider\r\nscooter\r\nscoutjet\r\nscrubby\r\nsearch.\r\nsearchprocess\r\nsemanticdiscovery\r\nsenrigan\r\nsg-scout\r\nshai''hulud\r\nshark\r\nshopwiki\r\nsidewinder\r\nsift\r\nsilk\r\nsimmany\r\nsite searcher\r\nsite valet\r\nsitetech-rover\r\nskymob.com\r\nsleek\r\nsmartwit\r\nsna-\r\nsnappy\r\nsnooper\r\nsohu\r\nspeedfind\r\nsphere\r\nsphider\r\nspinner\r\nspyder\r\nsteeler/\r\nsuke\r\nsuntek\r\nsupersnooper\r\nsurfnomore\r\nsven\r\nsygol\r\nszukacz\r\ntach black widow\r\ntarantula\r\ntempleton\r\n/teoma\r\nt-h-u-n-d-e-r-s-t-o-n-e\r\ntheophrastus\r\ntitan\r\ntitin\r\ntkwww\r\ntoutatis\r\nt-rex\r\ntutorgig\r\ntwiceler\r\ntwisted\r\nucsd\r\nudmsearch\r\nurl check\r\nupdated\r\nvagabondo\r\nvalkyrie\r\nverticrawl\r\nvictoria\r\nvision-search\r\nvolcano\r\nvoyager/\r\nvoyager-hc\r\nw3c_validator\r\nw3m2\r\nw3mir\r\nwalker\r\nwallpaper\r\nwanderer\r\nwauuu\r\nwavefire\r\nweb core\r\nweb hopper\r\nweb wombat\r\nwebbandit\r\nwebcatcher\r\nwebcopy\r\nwebfoot\r\nweblayers\r\nweblinker\r\nweblog monitor\r\nwebmirror\r\nwebmonkey\r\nwebquest\r\nwebreaper\r\nwebsitepulse\r\nwebsnarf\r\nwebstolperer\r\nwebvac\r\nwebwalk\r\nwebwatch\r\nwebwombat\r\nwebzinger\r\nwhizbang\r\nwhowhere\r\nwild ferret\r\nworldlight\r\nwwwc\r\nwwwster\r\nxenu\r\nxget\r\nxift\r\nxirq\r\nyandex\r\nyanga\r\nyeti\r\nyodao\r\nzao\r\nzippp\r\nzyborg', FALSE),
(0, 'config', 'config_password', '1', FALSE),
(0, 'config', 'config_product_count', '1', FALSE),
(0, 'config', 'config_product_description_length', '100', FALSE),
(0, 'config', 'config_image_file_size', '300000', FALSE),
(0, 'config', 'config_review_mail', '0', FALSE),
(0, 'config', 'config_review_guest', '1', FALSE);

-- --------------------------------------------------------

--
-- Table structure for table oc_stock_status
--

DROP TABLE IF EXISTS oc_stock_status;
CREATE TABLE oc_stock_status (
  stock_status_id serial NOT NULL,
  language_id integer NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (stock_status_id,language_id)
);

--
-- Dumping data for table oc_stock_status
--

INSERT INTO oc_stock_status (stock_status_id, language_id, name) VALUES
(7, 1, 'In Stock'),
(8, 1, 'Pre-Order'),
(5, 1, 'Out Of Stock'),
(6, 1, '2 - 3 Days');

-- --------------------------------------------------------

--
-- Table structure for table oc_store
--

DROP TABLE IF EXISTS oc_store;
CREATE TABLE oc_store (
  store_id serial NOT NULL,
  name varchar(64) NOT NULL,
  url varchar(255) NOT NULL,
  ssl varchar(255) NOT NULL,
  PRIMARY KEY (store_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_tax_class
--

DROP TABLE IF EXISTS oc_tax_class;
CREATE TABLE oc_tax_class (
  tax_class_id serial NOT NULL,
  title varchar(32) NOT NULL,
  description varchar(255) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (tax_class_id)
);

--
-- Dumping data for table oc_tax_class
--

INSERT INTO oc_tax_class (tax_class_id, title, description, date_added, date_modified) VALUES
(9, 'Taxable Goods', 'Taxed Stuff', '2009-01-06 23:21:53', '2011-09-23 14:07:50'),
(10, 'Downloadable Products', 'Downloadable', '2011-09-21 22:19:39', '2011-09-22 10:27:36');

-- --------------------------------------------------------

--
-- Table structure for table oc_tax_rate
--

DROP TABLE IF EXISTS oc_tax_rate;
CREATE TABLE oc_tax_rate (
  tax_rate_id serial NOT NULL,
  geo_zone_id integer NOT NULL DEFAULT '0',
  name varchar(32) NOT NULL,
  rate decimal(15,4) NOT NULL DEFAULT '0.0000',
  type char(1) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (tax_rate_id)
);

--
-- Dumping data for table oc_tax_rate
--

INSERT INTO oc_tax_rate (tax_rate_id, geo_zone_id, name, rate, type, date_added, date_modified) VALUES
(86, 3, 'VAT (17.5%)', '17.5000', 'P', '2011-03-09 21:17:10', '2011-09-22 22:24:29'),
(87, 3, 'Eco Tax (-2.00)', '2.0000', 'F', '2011-09-21 21:49:23', '2011-09-23 00:40:19');

-- --------------------------------------------------------

--
-- Table structure for table oc_tax_rate_to_customer_group
--

DROP TABLE IF EXISTS oc_tax_rate_to_customer_group;
CREATE TABLE oc_tax_rate_to_customer_group (
  tax_rate_id integer NOT NULL,
  customer_group_id integer NOT NULL,
  PRIMARY KEY (tax_rate_id,customer_group_id)
);

--
-- Dumping data for table oc_tax_rate_to_customer_group
--

INSERT INTO oc_tax_rate_to_customer_group (tax_rate_id, customer_group_id) VALUES
(86, 1),
(87, 1);

-- --------------------------------------------------------

--
-- Table structure for table oc_tax_rule
--

DROP TABLE IF EXISTS oc_tax_rule;
CREATE TABLE oc_tax_rule (
  tax_rule_id serial NOT NULL,
  tax_class_id integer NOT NULL,
  tax_rate_id integer NOT NULL,
  based varchar(10) NOT NULL,
  priority integer NOT NULL DEFAULT '1',
  PRIMARY KEY (tax_rule_id)
);

--
-- Dumping data for table oc_tax_rule
--

INSERT INTO oc_tax_rule (tax_rule_id, tax_class_id, tax_rate_id, based, priority) VALUES
(121, 10, 86, 'payment', 1),
(120, 10, 87, 'store', 0),
(128, 9, 86, 'shipping', 1),
(127, 9, 87, 'shipping', 2);

-- --------------------------------------------------------

--
-- Table structure for table oc_url_alias
--

DROP TABLE IF EXISTS oc_url_alias;
CREATE TABLE oc_url_alias (
  url_alias_id serial NOT NULL,
  query varchar(255) NOT NULL,
  keyword varchar(255) NOT NULL,
  PRIMARY KEY (url_alias_id)
);

--
-- Dumping data for table oc_url_alias
--

INSERT INTO oc_url_alias (url_alias_id, query, keyword) VALUES
(824, 'product_id=48', 'ipod-classic'),
(836, 'category_id=20', 'desktops'),
(834, 'category_id=26', 'pc'),
(835, 'category_id=27', 'mac'),
(730, 'manufacturer_id=8', 'apple'),
(772, 'information_id=4', 'about_us'),
(768, 'product_id=42', 'test'),
(789, 'category_id=34', 'mp3-players'),
(781, 'category_id=36', 'test2'),
(774, 'category_id=18', 'laptop-notebook'),
(775, 'category_id=46', 'macs'),
(776, 'category_id=45', 'windows'),
(777, 'category_id=25', 'component'),
(778, 'category_id=29', 'mouse'),
(779, 'category_id=28', 'monitor'),
(780, 'category_id=35', 'test1'),
(782, 'category_id=30', 'printer'),
(783, 'category_id=31', 'scanner'),
(784, 'category_id=32', 'web-camera'),
(785, 'category_id=57', 'tablet'),
(786, 'category_id=17', 'software'),
(787, 'category_id=24', 'smartphone'),
(788, 'category_id=33', 'camera'),
(790, 'category_id=43', 'test11'),
(791, 'category_id=44', 'test12'),
(792, 'category_id=47', 'test15'),
(793, 'category_id=48', 'test16'),
(794, 'category_id=49', 'test17'),
(795, 'category_id=50', 'test18'),
(796, 'category_id=51', 'test19'),
(797, 'category_id=52', 'test20'),
(798, 'category_id=58', 'test25'),
(799, 'category_id=53', 'test21'),
(800, 'category_id=54', 'test22'),
(801, 'category_id=55', 'test23'),
(802, 'category_id=56', 'test24'),
(803, 'category_id=38', 'test4'),
(804, 'category_id=37', 'test5'),
(805, 'category_id=39', 'test6'),
(806, 'category_id=40', 'test7'),
(807, 'category_id=41', 'test8'),
(808, 'category_id=42', 'test9'),
(809, 'product_id=30', 'canon-eos-5d'),
(840, 'product_id=47', 'hp-lp3065'),
(811, 'product_id=28', 'htc-touch-hd'),
(812, 'product_id=43', 'macbook'),
(813, 'product_id=44', 'macbook-air'),
(814, 'product_id=45', 'macbook-pro'),
(816, 'product_id=31', 'nikon-d300'),
(817, 'product_id=29', 'palm-treo-pro'),
(818, 'product_id=35', 'product-8'),
(819, 'product_id=49', 'samsung-galaxy-tab-10-1'),
(820, 'product_id=33', 'samsung-syncmaster-941bw'),
(821, 'product_id=46', 'sony-vaio'),
(837, 'product_id=41', 'imac'),
(823, 'product_id=40', 'iphone'),
(825, 'product_id=36', 'ipod-nano'),
(826, 'product_id=34', 'ipod-shuffle'),
(827, 'product_id=32', 'ipod-touch'),
(828, 'manufacturer_id=9', 'canon'),
(829, 'manufacturer_id=5', 'htc'),
(830, 'manufacturer_id=7', 'hewlett-packard'),
(831, 'manufacturer_id=6', 'palm'),
(832, 'manufacturer_id=10', 'sony'),
(841, 'information_id=6', 'delivery'),
(842, 'information_id=3', 'privacy'),
(843, 'information_id=5', 'terms');

-- --------------------------------------------------------

--
-- Table structure for table oc_user
--

DROP TABLE IF EXISTS oc_user;
CREATE TABLE oc_user (
  user_id serial NOT NULL,
  user_group_id integer NOT NULL,
  username varchar(20) NOT NULL,
  password varchar(40) NOT NULL,
  salt varchar(9) NOT NULL,
  firstname varchar(32) NULL,
  lastname varchar(32) NULL,
  email varchar(96) NOT NULL,
  image varchar(255) NULL,
  code varchar(40) NULL,
  ip varchar(40) NULL,
  status boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (user_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_user_group
--

DROP TABLE IF EXISTS oc_user_group;
CREATE TABLE oc_user_group (
  user_group_id serial NOT NULL,
  name varchar(64) NOT NULL,
  permission text NOT NULL,
  PRIMARY KEY (user_group_id)
);

--
-- Dumping data for table oc_user_group
--

INSERT INTO oc_user_group (user_group_id, name, permission) VALUES
(1, 'Administrator', 'a:2:{s:6:"access";a:137:{i:0;s:17:"catalog/attribute";i:1;s:23:"catalog/attribute_group";i:2;s:16:"catalog/category";i:3;s:16:"catalog/download";i:4;s:14:"catalog/filter";i:5;s:19:"catalog/information";i:6;s:20:"catalog/manufacturer";i:7;s:14:"catalog/option";i:8;s:15:"catalog/product";i:9;s:15:"catalog/profile";i:10;s:14:"catalog/review";i:11;s:18:"common/filemanager";i:12;s:13:"design/banner";i:13;s:13:"design/layout";i:14;s:14:"extension/feed";i:15;s:19:"extension/installer";i:16;s:22:"extension/modification";i:17;s:16:"extension/module";i:18;s:17:"extension/payment";i:19;s:18:"extension/shipping";i:20;s:15:"extension/total";i:21;s:16:"feed/google_base";i:22;s:19:"feed/google_sitemap";i:23;s:20:"localisation/country";i:24;s:21:"localisation/currency";i:25;s:21:"localisation/geo_zone";i:26;s:21:"localisation/language";i:27;s:25:"localisation/length_class";i:28;s:21:"localisation/location";i:29;s:25:"localisation/order_status";i:30;s:26:"localisation/return_action";i:31;s:26:"localisation/return_reason";i:32;s:26:"localisation/return_status";i:33;s:25:"localisation/stock_status";i:34;s:22:"localisation/tax_class";i:35;s:21:"localisation/tax_rate";i:36;s:25:"localisation/weight_class";i:37;s:17:"localisation/zone";i:38;s:19:"marketing/affiliate";i:39;s:17:"marketing/contact";i:40;s:16:"marketing/coupon";i:41;s:19:"marketing/marketing";i:42;s:14:"module/account";i:43;s:16:"module/affiliate";i:44;s:13:"module/banner";i:45;s:17:"module/bestseller";i:46;s:15:"module/carousel";i:47;s:15:"module/category";i:48;s:15:"module/featured";i:49;s:13:"module/filter";i:50;s:18:"module/google_talk";i:51;s:18:"module/information";i:52;s:13:"module/latest";i:53;s:16:"module/slideshow";i:54;s:14:"module/special";i:55;s:12:"module/store";i:56;s:14:"module/welcome";i:57;s:24:"payment/authorizenet_aim";i:58;s:21:"payment/bank_transfer";i:59;s:14:"payment/cheque";i:60;s:11:"payment/cod";i:61;s:21:"payment/free_checkout";i:62;s:22:"payment/klarna_account";i:63;s:22:"payment/klarna_invoice";i:64;s:14:"payment/liqpay";i:65;s:20:"payment/moneybookers";i:66;s:14:"payment/nochex";i:67;s:15:"payment/paymate";i:68;s:16:"payment/paypoint";i:69;s:13:"payment/payza";i:70;s:26:"payment/perpetual_payments";i:71;s:18:"payment/pp_express";i:72;s:25:"payment/pp_payflow_iframe";i:73;s:14:"payment/pp_pro";i:74;s:21:"payment/pp_pro_iframe";i:75;s:18:"payment/pp_payflow";i:76;s:17:"payment/pp_pro_uk";i:77;s:19:"payment/pp_standard";i:78;s:15:"payment/sagepay";i:79;s:22:"payment/sagepay_direct";i:80;s:18:"payment/sagepay_us";i:81;s:19:"payment/twocheckout";i:82;s:28:"payment/web_payment_software";i:83;s:16:"payment/worldpay";i:84;s:16:"report/affiliate";i:85;s:25:"report/affiliate_activity";i:86;s:24:"report/customer_activity";i:87;s:22:"report/customer_credit";i:88;s:22:"report/customer_online";i:89;s:21:"report/customer_order";i:90;s:22:"report/customer_reward";i:91;s:16:"report/marketing";i:92;s:24:"report/product_purchased";i:93;s:21:"report/product_viewed";i:94;s:18:"report/sale_coupon";i:95;s:17:"report/sale_order";i:96;s:18:"report/sale_return";i:97;s:20:"report/sale_shipping";i:98;s:15:"report/sale_tax";i:99;s:17:"sale/custom_field";i:100;s:13:"sale/customer";i:101;s:20:"sale/customer_ban_ip";i:102;s:19:"sale/customer_group";i:103;s:10:"sale/order";i:104;s:14:"sale/recurring";i:105;s:11:"sale/return";i:106;s:12:"sale/voucher";i:107;s:18:"sale/voucher_theme";i:108;s:15:"setting/setting";i:109;s:13:"setting/store";i:110;s:16:"shipping/auspost";i:111;s:17:"shipping/citylink";i:112;s:14:"shipping/fedex";i:113;s:13:"shipping/flat";i:114;s:13:"shipping/free";i:115;s:13:"shipping/item";i:116;s:23:"shipping/parcelforce_48";i:117;s:15:"shipping/pickup";i:118;s:19:"shipping/royal_mail";i:119;s:12:"shipping/ups";i:120;s:13:"shipping/usps";i:121;s:15:"shipping/weight";i:122;s:11:"tool/backup";i:123;s:14:"tool/error_log";i:124;s:12:"total/coupon";i:125;s:12:"total/credit";i:126;s:14:"total/handling";i:127;s:16:"total/klarna_fee";i:128;s:19:"total/low_order_fee";i:129;s:12:"total/reward";i:130;s:14:"total/shipping";i:131;s:15:"total/sub_total";i:132;s:9:"total/tax";i:133;s:11:"total/total";i:134;s:13:"total/voucher";i:135;s:9:"user/user";i:136;s:20:"user/user_permission";}s:6:"modify";a:137:{i:0;s:17:"catalog/attribute";i:1;s:23:"catalog/attribute_group";i:2;s:16:"catalog/category";i:3;s:16:"catalog/download";i:4;s:14:"catalog/filter";i:5;s:19:"catalog/information";i:6;s:20:"catalog/manufacturer";i:7;s:14:"catalog/option";i:8;s:15:"catalog/product";i:9;s:15:"catalog/profile";i:10;s:14:"catalog/review";i:11;s:18:"common/filemanager";i:12;s:13:"design/banner";i:13;s:13:"design/layout";i:14;s:14:"extension/feed";i:15;s:19:"extension/installer";i:16;s:22:"extension/modification";i:17;s:16:"extension/module";i:18;s:17:"extension/payment";i:19;s:18:"extension/shipping";i:20;s:15:"extension/total";i:21;s:16:"feed/google_base";i:22;s:19:"feed/google_sitemap";i:23;s:20:"localisation/country";i:24;s:21:"localisation/currency";i:25;s:21:"localisation/geo_zone";i:26;s:21:"localisation/language";i:27;s:25:"localisation/length_class";i:28;s:21:"localisation/location";i:29;s:25:"localisation/order_status";i:30;s:26:"localisation/return_action";i:31;s:26:"localisation/return_reason";i:32;s:26:"localisation/return_status";i:33;s:25:"localisation/stock_status";i:34;s:22:"localisation/tax_class";i:35;s:21:"localisation/tax_rate";i:36;s:25:"localisation/weight_class";i:37;s:17:"localisation/zone";i:38;s:19:"marketing/affiliate";i:39;s:17:"marketing/contact";i:40;s:16:"marketing/coupon";i:41;s:19:"marketing/marketing";i:42;s:14:"module/account";i:43;s:16:"module/affiliate";i:44;s:13:"module/banner";i:45;s:17:"module/bestseller";i:46;s:15:"module/carousel";i:47;s:15:"module/category";i:48;s:15:"module/featured";i:49;s:13:"module/filter";i:50;s:18:"module/google_talk";i:51;s:18:"module/information";i:52;s:13:"module/latest";i:53;s:16:"module/slideshow";i:54;s:14:"module/special";i:55;s:12:"module/store";i:56;s:14:"module/welcome";i:57;s:24:"payment/authorizenet_aim";i:58;s:21:"payment/bank_transfer";i:59;s:14:"payment/cheque";i:60;s:11:"payment/cod";i:61;s:21:"payment/free_checkout";i:62;s:22:"payment/klarna_account";i:63;s:22:"payment/klarna_invoice";i:64;s:14:"payment/liqpay";i:65;s:20:"payment/moneybookers";i:66;s:14:"payment/nochex";i:67;s:15:"payment/paymate";i:68;s:16:"payment/paypoint";i:69;s:13:"payment/payza";i:70;s:26:"payment/perpetual_payments";i:71;s:18:"payment/pp_express";i:72;s:25:"payment/pp_payflow_iframe";i:73;s:14:"payment/pp_pro";i:74;s:21:"payment/pp_pro_iframe";i:75;s:18:"payment/pp_payflow";i:76;s:17:"payment/pp_pro_uk";i:77;s:19:"payment/pp_standard";i:78;s:15:"payment/sagepay";i:79;s:22:"payment/sagepay_direct";i:80;s:18:"payment/sagepay_us";i:81;s:19:"payment/twocheckout";i:82;s:28:"payment/web_payment_software";i:83;s:16:"payment/worldpay";i:84;s:16:"report/affiliate";i:85;s:25:"report/affiliate_activity";i:86;s:24:"report/customer_activity";i:87;s:22:"report/customer_credit";i:88;s:22:"report/customer_online";i:89;s:21:"report/customer_order";i:90;s:22:"report/customer_reward";i:91;s:16:"report/marketing";i:92;s:24:"report/product_purchased";i:93;s:21:"report/product_viewed";i:94;s:18:"report/sale_coupon";i:95;s:17:"report/sale_order";i:96;s:18:"report/sale_return";i:97;s:20:"report/sale_shipping";i:98;s:15:"report/sale_tax";i:99;s:17:"sale/custom_field";i:100;s:13:"sale/customer";i:101;s:20:"sale/customer_ban_ip";i:102;s:19:"sale/customer_group";i:103;s:10:"sale/order";i:104;s:14:"sale/recurring";i:105;s:11:"sale/return";i:106;s:12:"sale/voucher";i:107;s:18:"sale/voucher_theme";i:108;s:15:"setting/setting";i:109;s:13:"setting/store";i:110;s:16:"shipping/auspost";i:111;s:17:"shipping/citylink";i:112;s:14:"shipping/fedex";i:113;s:13:"shipping/flat";i:114;s:13:"shipping/free";i:115;s:13:"shipping/item";i:116;s:23:"shipping/parcelforce_48";i:117;s:15:"shipping/pickup";i:118;s:19:"shipping/royal_mail";i:119;s:12:"shipping/ups";i:120;s:13:"shipping/usps";i:121;s:15:"shipping/weight";i:122;s:11:"tool/backup";i:123;s:14:"tool/error_log";i:124;s:12:"total/coupon";i:125;s:12:"total/credit";i:126;s:14:"total/handling";i:127;s:16:"total/klarna_fee";i:128;s:19:"total/low_order_fee";i:129;s:12:"total/reward";i:130;s:14:"total/shipping";i:131;s:15:"total/sub_total";i:132;s:9:"total/tax";i:133;s:11:"total/total";i:134;s:13:"total/voucher";i:135;s:9:"user/user";i:136;s:20:"user/user_permission";}}'),
(10, 'Demonstration', '');

-- --------------------------------------------------------

--
-- Table structure for table oc_voucher
--

DROP TABLE IF EXISTS oc_voucher;
CREATE TABLE oc_voucher (
  voucher_id serial NOT NULL,
  order_id integer NOT NULL,
  code varchar(10) NOT NULL,
  from_name varchar(64) NOT NULL,
  from_email varchar(96) NOT NULL,
  to_name varchar(64) NOT NULL,
  to_email varchar(96) NOT NULL,
  voucher_theme_id integer NOT NULL,
  message text NOT NULL,
  amount decimal(15,4) NOT NULL,
  status boolean NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (voucher_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_voucher_history
--

DROP TABLE IF EXISTS oc_voucher_history;
CREATE TABLE oc_voucher_history (
  voucher_history_id serial NOT NULL,
  voucher_id integer NOT NULL,
  order_id integer NOT NULL,
  amount decimal(15,4) NOT NULL,
  date_added timestamp without time zone NOT NULL,
  PRIMARY KEY (voucher_history_id)
);

-- --------------------------------------------------------

--
-- Table structure for table oc_voucher_theme
--

DROP TABLE IF EXISTS oc_voucher_theme;
CREATE TABLE oc_voucher_theme (
  voucher_theme_id serial NOT NULL,
  image varchar(255) NOT NULL,
  PRIMARY KEY (voucher_theme_id)
);

--
-- Dumping data for table oc_voucher_theme
--

INSERT INTO oc_voucher_theme (voucher_theme_id, image) VALUES
(8, 'catalog/demo/canon_eos_5d_2.jpg'),
(7, 'catalog/demo/gift-voucher-birthday.jpg'),
(6, 'catalog/demo/apple_logo.jpg');

-- --------------------------------------------------------

--
-- Table structure for table oc_voucher_theme_description
--

DROP TABLE IF EXISTS oc_voucher_theme_description;
CREATE TABLE oc_voucher_theme_description (
  voucher_theme_id integer NOT NULL,
  language_id integer NOT NULL,
  name varchar(32) NOT NULL,
  PRIMARY KEY (voucher_theme_id,language_id)
);

--
-- Dumping data for table oc_voucher_theme_description
--

INSERT INTO oc_voucher_theme_description (voucher_theme_id, language_id, name) VALUES
(6, 1, 'Christmas'),
(7, 1, 'Birthday'),
(8, 1, 'General');

-- --------------------------------------------------------

--
-- Table structure for table oc_weight_class
--

DROP TABLE IF EXISTS oc_weight_class;
CREATE TABLE oc_weight_class (
  weight_class_id serial NOT NULL,
  value decimal(15,8) NOT NULL DEFAULT '0.00000000',
  PRIMARY KEY (weight_class_id)
);

--
-- Dumping data for table oc_weight_class
--

INSERT INTO oc_weight_class (weight_class_id, value) VALUES
(1, '1.00000000'),
(2, '1000.00000000'),
(5, '2.20460000'),
(6, '35.27400000');

-- --------------------------------------------------------

--
-- Table structure for table oc_weight_class_description
--

DROP TABLE IF EXISTS oc_weight_class_description;
CREATE TABLE oc_weight_class_description (
  weight_class_id serial NOT NULL,
  language_id integer NOT NULL,
  title varchar(32) NOT NULL,
  unit varchar(4) NOT NULL,
  PRIMARY KEY (weight_class_id,language_id)
);

--
-- Dumping data for table oc_weight_class_description
--

INSERT INTO oc_weight_class_description (weight_class_id, language_id, title, unit) VALUES
(1, 1, 'Kilogram', 'kg'),
(2, 1, 'Gram', 'g'),
(5, 1, 'Pound ', 'lb'),
(6, 1, 'Ounce', 'oz');

-- --------------------------------------------------------

--
-- Table structure for table oc_zone
--

DROP TABLE IF EXISTS oc_zone;
CREATE TABLE oc_zone (
  zone_id serial NOT NULL,
  country_id integer NOT NULL,
  name varchar(128) NOT NULL,
  code varchar(32) NOT NULL,
  status boolean NOT NULL DEFAULT TRUE,
  PRIMARY KEY (zone_id)
);

--
-- Dumping data for table oc_zone
--

INSERT INTO oc_zone (zone_id, country_id, code, name, status) VALUES
(1, 1, 'BDS', 'Badakhshan', TRUE),
(2, 1, 'BDG', 'Badghis', TRUE),
(3, 1, 'BGL', 'Baghlan', TRUE),
(4, 1, 'BAL', 'Balkh', TRUE),
(5, 1, 'BAM', 'Bamian', TRUE),
(6, 1, 'FRA', 'Farah', TRUE),
(7, 1, 'FYB', 'Faryab', TRUE),
(8, 1, 'GHA', 'Ghazni', TRUE),
(9, 1, 'GHO', 'Ghowr', TRUE),
(10, 1, 'HEL', 'Helmand', TRUE),
(11, 1, 'HER', 'Herat', TRUE),
(12, 1, 'JOW', 'Jowzjan', TRUE),
(13, 1, 'KAB', 'Kabul', TRUE),
(14, 1, 'KAN', 'Kandahar', TRUE),
(15, 1, 'KAP', 'Kapisa', TRUE),
(16, 1, 'KHO', 'Khost', TRUE),
(17, 1, 'KNR', 'Konar', TRUE),
(18, 1, 'KDZ', 'Kondoz', TRUE),
(19, 1, 'LAG', 'Laghman', TRUE),
(20, 1, 'LOW', 'Lowgar', TRUE),
(21, 1, 'NAN', 'Nangrahar', TRUE),
(22, 1, 'NIM', 'Nimruz', TRUE),
(23, 1, 'NUR', 'Nurestan', TRUE),
(24, 1, 'ORU', 'Oruzgan', TRUE),
(25, 1, 'PIA', 'Paktia', TRUE),
(26, 1, 'PKA', 'Paktika', TRUE),
(27, 1, 'PAR', 'Parwan', TRUE),
(28, 1, 'SAM', 'Samangan', TRUE),
(29, 1, 'SAR', 'Sar-e Pol', TRUE),
(30, 1, 'TAK', 'Takhar', TRUE),
(31, 1, 'WAR', 'Wardak', TRUE),
(32, 1, 'ZAB', 'Zabol', TRUE),
(33, 2, 'BR', 'Berat', TRUE),
(34, 2, 'BU', 'Bulqize', TRUE),
(35, 2, 'DL', 'Delvine', TRUE),
(36, 2, 'DV', 'Devoll', TRUE),
(37, 2, 'DI', 'Diber', TRUE),
(38, 2, 'DR', 'Durres', TRUE),
(39, 2, 'EL', 'Elbasan', TRUE),
(40, 2, 'ER', 'Kolonje', TRUE),
(41, 2, 'FR', 'Fier', TRUE),
(42, 2, 'GJ', 'Gjirokaster', TRUE),
(43, 2, 'GR', 'Gramsh', TRUE),
(44, 2, 'HA', 'Has', TRUE),
(45, 2, 'KA', 'Kavaje', TRUE),
(46, 2, 'KB', 'Kurbin', TRUE),
(47, 2, 'KC', 'Kucove', TRUE),
(48, 2, 'KO', 'Korce', TRUE),
(49, 2, 'KR', 'Kruje', TRUE),
(50, 2, 'KU', 'Kukes', TRUE),
(51, 2, 'LB', 'Librazhd', TRUE),
(52, 2, 'LE', 'Lezhe', TRUE),
(53, 2, 'LU', 'Lushnje', TRUE),
(54, 2, 'MM', 'Malesi e Madhe', TRUE),
(55, 2, 'MK', 'Mallakaster', TRUE),
(56, 2, 'MT', 'Mat', TRUE),
(57, 2, 'MR', 'Mirdite', TRUE),
(58, 2, 'PQ', 'Peqin', TRUE),
(59, 2, 'PR', 'Permet', TRUE),
(60, 2, 'PG', 'Pogradec', TRUE),
(61, 2, 'PU', 'Puke', TRUE),
(62, 2, 'SH', 'Shkoder', TRUE),
(63, 2, 'SK', 'Skrapar', TRUE),
(64, 2, 'SR', 'Sarande', TRUE),
(65, 2, 'TE', 'Tepelene', TRUE),
(66, 2, 'TP', 'Tropoje', TRUE),
(67, 2, 'TR', 'Tirane', TRUE),
(68, 2, 'VL', 'Vlore', TRUE),
(69, 3, 'ADR', 'Adrar', TRUE),
(70, 3, 'ADE', 'Ain Defla', TRUE),
(71, 3, 'ATE', 'Ain Temouchent', TRUE),
(72, 3, 'ALG', 'Alger', TRUE),
(73, 3, 'ANN', 'Annaba', TRUE),
(74, 3, 'BAT', 'Batna', TRUE),
(75, 3, 'BEC', 'Bechar', TRUE),
(76, 3, 'BEJ', 'Bejaia', TRUE),
(77, 3, 'BIS', 'Biskra', TRUE),
(78, 3, 'BLI', 'Blida', TRUE),
(79, 3, 'BBA', 'Bordj Bou Arreridj', TRUE),
(80, 3, 'BOA', 'Bouira', TRUE),
(81, 3, 'BMD', 'Boumerdes', TRUE),
(82, 3, 'CHL', 'Chlef', TRUE),
(83, 3, 'CON', 'Constantine', TRUE),
(84, 3, 'DJE', 'Djelfa', TRUE),
(85, 3, 'EBA', 'El Bayadh', TRUE),
(86, 3, 'EOU', 'El Oued', TRUE),
(87, 3, 'ETA', 'El Tarf', TRUE),
(88, 3, 'GHA', 'Ghardaia', TRUE),
(89, 3, 'GUE', 'Guelma', TRUE),
(90, 3, 'ILL', 'Illizi', TRUE),
(91, 3, 'JIJ', 'Jijel', TRUE),
(92, 3, 'KHE', 'Khenchela', TRUE),
(93, 3, 'LAG', 'Laghouat', TRUE),
(94, 3, 'MUA', 'Muaskar', TRUE),
(95, 3, 'MED', 'Medea', TRUE),
(96, 3, 'MIL', 'Mila', TRUE),
(97, 3, 'MOS', 'Mostaganem', TRUE),
(98, 3, 'MSI', 'M''Sila', TRUE),
(99, 3, 'NAA', 'Naama', TRUE),
(100, 3, 'ORA', 'Oran', TRUE),
(101, 3, 'OUA', 'Ouargla', TRUE),
(102, 3, 'OEB', 'Oum el-Bouaghi', TRUE),
(103, 3, 'REL', 'Relizane', TRUE),
(104, 3, 'SAI', 'Saida', TRUE),
(105, 3, 'SET', 'Setif', TRUE),
(106, 3, 'SBA', 'Sidi Bel Abbes', TRUE),
(107, 3, 'SKI', 'Skikda', TRUE),
(108, 3, 'SAH', 'Souk Ahras', TRUE),
(109, 3, 'TAM', 'Tamanghasset', TRUE),
(110, 3, 'TEB', 'Tebessa', TRUE),
(111, 3, 'TIA', 'Tiaret', TRUE),
(112, 3, 'TIN', 'Tindouf', TRUE),
(113, 3, 'TIP', 'Tipaza', TRUE),
(114, 3, 'TIS', 'Tissemsilt', TRUE),
(115, 3, 'TOU', 'Tizi Ouzou', TRUE),
(116, 3, 'TLE', 'Tlemcen', TRUE),
(117, 4, 'E', 'Eastern', TRUE),
(118, 4, 'M', 'Manu''a', TRUE),
(119, 4, 'R', 'Rose Island', TRUE),
(120, 4, 'S', 'Swains Island', TRUE),
(121, 4, 'W', 'Western', TRUE),
(122, 5, 'ALV', 'Andorra la Vella', TRUE),
(123, 5, 'CAN', 'Canillo', TRUE),
(124, 5, 'ENC', 'Encamp', TRUE),
(125, 5, 'ESE', 'Escaldes-Engordany', TRUE),
(126, 5, 'LMA', 'La Massana', TRUE),
(127, 5, 'ORD', 'Ordino', TRUE),
(128, 5, 'SJL', 'Sant Julia de Loria', TRUE),
(129, 6, 'BGO', 'Bengo', TRUE),
(130, 6, 'BGU', 'Benguela', TRUE),
(131, 6, 'BIE', 'Bie', TRUE),
(132, 6, 'CAB', 'Cabinda', TRUE),
(133, 6, 'CCU', 'Cuando-Cubango', TRUE),
(134, 6, 'CNO', 'Cuanza Norte', TRUE),
(135, 6, 'CUS', 'Cuanza Sul', TRUE),
(136, 6, 'CNN', 'Cunene', TRUE),
(137, 6, 'HUA', 'Huambo', TRUE),
(138, 6, 'HUI', 'Huila', TRUE),
(139, 6, 'LUA', 'Luanda', TRUE),
(140, 6, 'LNO', 'Lunda Norte', TRUE),
(141, 6, 'LSU', 'Lunda Sul', TRUE),
(142, 6, 'MAL', 'Malange', TRUE),
(143, 6, 'MOX', 'Moxico', TRUE),
(144, 6, 'NAM', 'Namibe', TRUE),
(145, 6, 'UIG', 'Uige', TRUE),
(146, 6, 'ZAI', 'Zaire', TRUE),
(147, 9, 'ASG', 'Saint George', TRUE),
(148, 9, 'ASJ', 'Saint John', TRUE),
(149, 9, 'ASM', 'Saint Mary', TRUE),
(150, 9, 'ASL', 'Saint Paul', TRUE),
(151, 9, 'ASR', 'Saint Peter', TRUE),
(152, 9, 'ASH', 'Saint Philip', TRUE),
(153, 9, 'BAR', 'Barbuda', TRUE),
(154, 9, 'RED', 'Redonda', TRUE),
(155, 10, 'AN', 'Antartida e Islas del Atlantico', TRUE),
(156, 10, 'BA', 'Buenos Aires', TRUE),
(157, 10, 'CA', 'Catamarca', TRUE),
(158, 10, 'CH', 'Chaco', TRUE),
(159, 10, 'CU', 'Chubut', TRUE),
(160, 10, 'CO', 'Cordoba', TRUE),
(161, 10, 'CR', 'Corrientes', TRUE),
(162, 10, 'DF', 'Distrito Federal', TRUE),
(163, 10, 'ER', 'Entre Rios', TRUE),
(164, 10, 'FO', 'Formosa', TRUE),
(165, 10, 'JU', 'Jujuy', TRUE),
(166, 10, 'LP', 'La Pampa', TRUE),
(167, 10, 'LR', 'La Rioja', TRUE),
(168, 10, 'ME', 'Mendoza', TRUE),
(169, 10, 'MI', 'Misiones', TRUE),
(170, 10, 'NE', 'Neuquen', TRUE),
(171, 10, 'RN', 'Rio Negro', TRUE),
(172, 10, 'SA', 'Salta', TRUE),
(173, 10, 'SJ', 'San Juan', TRUE),
(174, 10, 'SL', 'San Luis', TRUE),
(175, 10, 'SC', 'Santa Cruz', TRUE),
(176, 10, 'SF', 'Santa Fe', TRUE),
(177, 10, 'SD', 'Santiago del Estero', TRUE),
(178, 10, 'TF', 'Tierra del Fuego', TRUE),
(179, 10, 'TU', 'Tucuman', TRUE),
(180, 11, 'AGT', 'Aragatsotn', TRUE),
(181, 11, 'ARR', 'Ararat', TRUE),
(182, 11, 'ARM', 'Armavir', TRUE),
(183, 11, 'GEG', 'Geghark''unik''', TRUE),
(184, 11, 'KOT', 'Kotayk''', TRUE),
(185, 11, 'LOR', 'Lorri', TRUE),
(186, 11, 'SHI', 'Shirak', TRUE),
(187, 11, 'SYU', 'Syunik''', TRUE),
(188, 11, 'TAV', 'Tavush', TRUE),
(189, 11, 'VAY', 'Vayots'' Dzor', TRUE),
(190, 11, 'YER', 'Yerevan', TRUE),
(191, 13, 'ACT', 'Australian Capital Territory', TRUE),
(192, 13, 'NSW', 'New South Wales', TRUE),
(193, 13, 'NT', 'Northern Territory', TRUE),
(194, 13, 'QLD', 'Queensland', TRUE),
(195, 13, 'SA', 'South Australia', TRUE),
(196, 13, 'TAS', 'Tasmania', TRUE),
(197, 13, 'VIC', 'Victoria', TRUE),
(198, 13, 'WA', 'Western Australia', TRUE),
(199, 14, 'BUR', 'Burgenland', TRUE),
(200, 14, 'KAR', 'Kärnten', TRUE),
(201, 14, 'NOS', 'Nieder&ouml;sterreich', TRUE),
(202, 14, 'OOS', 'Ober&ouml;sterreich', TRUE),
(203, 14, 'SAL', 'Salzburg', TRUE),
(204, 14, 'STE', 'Steiermark', TRUE),
(205, 14, 'TIR', 'Tirol', TRUE),
(206, 14, 'VOR', 'Vorarlberg', TRUE),
(207, 14, 'WIE', 'Wien', TRUE),
(208, 15, 'AB', 'Ali Bayramli', TRUE),
(209, 15, 'ABS', 'Abseron', TRUE),
(210, 15, 'AGC', 'AgcabAdi', TRUE),
(211, 15, 'AGM', 'Agdam', TRUE),
(212, 15, 'AGS', 'Agdas', TRUE),
(213, 15, 'AGA', 'Agstafa', TRUE),
(214, 15, 'AGU', 'Agsu', TRUE),
(215, 15, 'AST', 'Astara', TRUE),
(216, 15, 'BA', 'Baki', TRUE),
(217, 15, 'BAB', 'BabAk', TRUE),
(218, 15, 'BAL', 'BalakAn', TRUE),
(219, 15, 'BAR', 'BArdA', TRUE),
(220, 15, 'BEY', 'Beylaqan', TRUE),
(221, 15, 'BIL', 'Bilasuvar', TRUE),
(222, 15, 'CAB', 'Cabrayil', TRUE),
(223, 15, 'CAL', 'Calilabab', TRUE),
(224, 15, 'CUL', 'Culfa', TRUE),
(225, 15, 'DAS', 'Daskasan', TRUE),
(226, 15, 'DAV', 'Davaci', TRUE),
(227, 15, 'FUZ', 'Fuzuli', TRUE),
(228, 15, 'GA', 'Ganca', TRUE),
(229, 15, 'GAD', 'Gadabay', TRUE),
(230, 15, 'GOR', 'Goranboy', TRUE),
(231, 15, 'GOY', 'Goycay', TRUE),
(232, 15, 'HAC', 'Haciqabul', TRUE),
(233, 15, 'IMI', 'Imisli', TRUE),
(234, 15, 'ISM', 'Ismayilli', TRUE),
(235, 15, 'KAL', 'Kalbacar', TRUE),
(236, 15, 'KUR', 'Kurdamir', TRUE),
(237, 15, 'LA', 'Lankaran', TRUE),
(238, 15, 'LAC', 'Lacin', TRUE),
(239, 15, 'LAN', 'Lankaran', TRUE),
(240, 15, 'LER', 'Lerik', TRUE),
(241, 15, 'MAS', 'Masalli', TRUE),
(242, 15, 'MI', 'Mingacevir', TRUE),
(243, 15, 'NA', 'Naftalan', TRUE),
(244, 15, 'NEF', 'Neftcala', TRUE),
(245, 15, 'OGU', 'Oguz', TRUE),
(246, 15, 'ORD', 'Ordubad', TRUE),
(247, 15, 'QAB', 'Qabala', TRUE),
(248, 15, 'QAX', 'Qax', TRUE),
(249, 15, 'QAZ', 'Qazax', TRUE),
(250, 15, 'QOB', 'Qobustan', TRUE),
(251, 15, 'QBA', 'Quba', TRUE),
(252, 15, 'QBI', 'Qubadli', TRUE),
(253, 15, 'QUS', 'Qusar', TRUE),
(254, 15, 'SA', 'Saki', TRUE),
(255, 15, 'SAT', 'Saatli', TRUE),
(256, 15, 'SAB', 'Sabirabad', TRUE),
(257, 15, 'SAD', 'Sadarak', TRUE),
(258, 15, 'SAH', 'Sahbuz', TRUE),
(259, 15, 'SAK', 'Saki', TRUE),
(260, 15, 'SAL', 'Salyan', TRUE),
(261, 15, 'SM', 'Sumqayit', TRUE),
(262, 15, 'SMI', 'Samaxi', TRUE),
(263, 15, 'SKR', 'Samkir', TRUE),
(264, 15, 'SMX', 'Samux', TRUE),
(265, 15, 'SAR', 'Sarur', TRUE),
(266, 15, 'SIY', 'Siyazan', TRUE),
(267, 15, 'SS', 'Susa', TRUE),
(268, 15, 'SUS', 'Susa', TRUE),
(269, 15, 'TAR', 'Tartar', TRUE),
(270, 15, 'TOV', 'Tovuz', TRUE),
(271, 15, 'UCA', 'Ucar', TRUE),
(272, 15, 'XA', 'Xankandi', TRUE),
(273, 15, 'XAC', 'Xacmaz', TRUE),
(274, 15, 'XAN', 'Xanlar', TRUE),
(275, 15, 'XIZ', 'Xizi', TRUE),
(276, 15, 'XCI', 'Xocali', TRUE),
(277, 15, 'XVD', 'Xocavand', TRUE),
(278, 15, 'YAR', 'Yardimli', TRUE),
(279, 15, 'YEV', 'Yevlax', TRUE),
(280, 15, 'ZAN', 'Zangilan', TRUE),
(281, 15, 'ZAQ', 'Zaqatala', TRUE),
(282, 15, 'ZAR', 'Zardab', TRUE),
(283, 15, 'NX', 'Naxcivan', TRUE),
(284, 16, 'ACK', 'Acklins', TRUE),
(285, 16, 'BER', 'Berry Islands', TRUE),
(286, 16, 'BIM', 'Bimini', TRUE),
(287, 16, 'BLK', 'Black Point', TRUE),
(288, 16, 'CAT', 'Cat Island', TRUE),
(289, 16, 'CAB', 'Central Abaco', TRUE),
(290, 16, 'CAN', 'Central Andros', TRUE),
(291, 16, 'CEL', 'Central Eleuthera', TRUE),
(292, 16, 'FRE', 'City of Freeport', TRUE),
(293, 16, 'CRO', 'Crooked Island', TRUE),
(294, 16, 'EGB', 'East Grand Bahama', TRUE),
(295, 16, 'EXU', 'Exuma', TRUE),
(296, 16, 'GRD', 'Grand Cay', TRUE),
(297, 16, 'HAR', 'Harbour Island', TRUE),
(298, 16, 'HOP', 'Hope Town', TRUE),
(299, 16, 'INA', 'Inagua', TRUE),
(300, 16, 'LNG', 'Long Island', TRUE),
(301, 16, 'MAN', 'Mangrove Cay', TRUE),
(302, 16, 'MAY', 'Mayaguana', TRUE),
(303, 16, 'MOO', 'Moore''s Island', TRUE),
(304, 16, 'NAB', 'North Abaco', TRUE),
(305, 16, 'NAN', 'North Andros', TRUE),
(306, 16, 'NEL', 'North Eleuthera', TRUE),
(307, 16, 'RAG', 'Ragged Island', TRUE),
(308, 16, 'RUM', 'Rum Cay', TRUE),
(309, 16, 'SAL', 'San Salvador', TRUE),
(310, 16, 'SAB', 'South Abaco', TRUE),
(311, 16, 'SAN', 'South Andros', TRUE),
(312, 16, 'SEL', 'South Eleuthera', TRUE),
(313, 16, 'SWE', 'Spanish Wells', TRUE),
(314, 16, 'WGB', 'West Grand Bahama', TRUE),
(315, 17, 'CAP', 'Capital', TRUE),
(316, 17, 'CEN', 'Central', TRUE),
(317, 17, 'MUH', 'Muharraq', TRUE),
(318, 17, 'NOR', 'Northern', TRUE),
(319, 17, 'SOU', 'Southern', TRUE),
(320, 18, 'BAR', 'Barisal', TRUE),
(321, 18, 'CHI', 'Chittagong', TRUE),
(322, 18, 'DHA', 'Dhaka', TRUE),
(323, 18, 'KHU', 'Khulna', TRUE),
(324, 18, 'RAJ', 'Rajshahi', TRUE),
(325, 18, 'SYL', 'Sylhet', TRUE),
(326, 19, 'CC', 'Christ Church', TRUE),
(327, 19, 'AND', 'Saint Andrew', TRUE),
(328, 19, 'GEO', 'Saint George', TRUE),
(329, 19, 'JAM', 'Saint James', TRUE),
(330, 19, 'JOH', 'Saint John', TRUE),
(331, 19, 'JOS', 'Saint Joseph', TRUE),
(332, 19, 'LUC', 'Saint Lucy', TRUE),
(333, 19, 'MIC', 'Saint Michael', TRUE),
(334, 19, 'PET', 'Saint Peter', TRUE),
(335, 19, 'PHI', 'Saint Philip', TRUE),
(336, 19, 'THO', 'Saint Thomas', TRUE),
(337, 20, 'BR', 'Brestskaya (Brest)', TRUE),
(338, 20, 'HO', 'Homyel''skaya (Homyel'')', TRUE),
(339, 20, 'HM', 'Horad Minsk', TRUE),
(340, 20, 'HR', 'Hrodzyenskaya (Hrodna)', TRUE),
(341, 20, 'MA', 'Mahilyowskaya (Mahilyow)', TRUE),
(342, 20, 'MI', 'Minskaya', TRUE),
(343, 20, 'VI', 'Vitsyebskaya (Vitsyebsk)', TRUE),
(344, 21, 'VAN', 'Antwerpen', TRUE),
(345, 21, 'WBR', 'Brabant Wallon', TRUE),
(346, 21, 'WHT', 'Hainaut', TRUE),
(347, 21, 'WLG', 'Liège', TRUE),
(348, 21, 'VLI', 'Limburg', TRUE),
(349, 21, 'WLX', 'Luxembourg', TRUE),
(350, 21, 'WNA', 'Namur', TRUE),
(351, 21, 'VOV', 'Oost-Vlaanderen', TRUE),
(352, 21, 'VBR', 'Vlaams Brabant', TRUE),
(353, 21, 'VWV', 'West-Vlaanderen', TRUE),
(354, 22, 'BZ', 'Belize', TRUE),
(355, 22, 'CY', 'Cayo', TRUE),
(356, 22, 'CR', 'Corozal', TRUE),
(357, 22, 'OW', 'Orange Walk', TRUE),
(358, 22, 'SC', 'Stann Creek', TRUE),
(359, 22, 'TO', 'Toledo', TRUE),
(360, 23, 'AL', 'Alibori', TRUE),
(361, 23, 'AK', 'Atakora', TRUE),
(362, 23, 'AQ', 'Atlantique', TRUE),
(363, 23, 'BO', 'Borgou', TRUE),
(364, 23, 'CO', 'Collines', TRUE),
(365, 23, 'DO', 'Donga', TRUE),
(366, 23, 'KO', 'Kouffo', TRUE),
(367, 23, 'LI', 'Littoral', TRUE),
(368, 23, 'MO', 'Mono', TRUE),
(369, 23, 'OU', 'Oueme', TRUE),
(370, 23, 'PL', 'Plateau', TRUE),
(371, 23, 'ZO', 'Zou', TRUE),
(372, 24, 'DS', 'Devonshire', TRUE),
(373, 24, 'HC', 'Hamilton City', TRUE),
(374, 24, 'HA', 'Hamilton', TRUE),
(375, 24, 'PG', 'Paget', TRUE),
(376, 24, 'PB', 'Pembroke', TRUE),
(377, 24, 'GC', 'Saint George City', TRUE),
(378, 24, 'SG', 'Saint George''s', TRUE),
(379, 24, 'SA', 'Sandys', TRUE),
(380, 24, 'SM', 'Smith''s', TRUE),
(381, 24, 'SH', 'Southampton', TRUE),
(382, 24, 'WA', 'Warwick', TRUE),
(383, 25, 'BUM', 'Bumthang', TRUE),
(384, 25, 'CHU', 'Chukha', TRUE),
(385, 25, 'DAG', 'Dagana', TRUE),
(386, 25, 'GAS', 'Gasa', TRUE),
(387, 25, 'HAA', 'Haa', TRUE),
(388, 25, 'LHU', 'Lhuntse', TRUE),
(389, 25, 'MON', 'Mongar', TRUE),
(390, 25, 'PAR', 'Paro', TRUE),
(391, 25, 'PEM', 'Pemagatshel', TRUE),
(392, 25, 'PUN', 'Punakha', TRUE),
(393, 25, 'SJO', 'Samdrup Jongkhar', TRUE),
(394, 25, 'SAT', 'Samtse', TRUE),
(395, 25, 'SAR', 'Sarpang', TRUE),
(396, 25, 'THI', 'Thimphu', TRUE),
(397, 25, 'TRG', 'Trashigang', TRUE),
(398, 25, 'TRY', 'Trashiyangste', TRUE),
(399, 25, 'TRO', 'Trongsa', TRUE),
(400, 25, 'TSI', 'Tsirang', TRUE),
(401, 25, 'WPH', 'Wangdue Phodrang', TRUE),
(402, 25, 'ZHE', 'Zhemgang', TRUE),
(403, 26, 'BEN', 'Beni', TRUE),
(404, 26, 'CHU', 'Chuquisaca', TRUE),
(405, 26, 'COC', 'Cochabamba', TRUE),
(406, 26, 'LPZ', 'La Paz', TRUE),
(407, 26, 'ORU', 'Oruro', TRUE),
(408, 26, 'PAN', 'Pando', TRUE),
(409, 26, 'POT', 'Potosi', TRUE),
(410, 26, 'SCZ', 'Santa Cruz', TRUE),
(411, 26, 'TAR', 'Tarija', TRUE),
(412, 27, 'BRO', 'Brcko district', TRUE),
(413, 27, 'FUS', 'Unsko-Sanski Kanton', TRUE),
(414, 27, 'FPO', 'Posavski Kanton', TRUE),
(415, 27, 'FTU', 'Tuzlanski Kanton', TRUE),
(416, 27, 'FZE', 'Zenicko-Dobojski Kanton', TRUE),
(417, 27, 'FBP', 'Bosanskopodrinjski Kanton', TRUE),
(418, 27, 'FSB', 'Srednjebosanski Kanton', TRUE),
(419, 27, 'FHN', 'Hercegovacko-neretvanski Kanton', TRUE),
(420, 27, 'FZH', 'Zapadnohercegovacka Zupanija', TRUE),
(421, 27, 'FSA', 'Kanton Sarajevo', TRUE),
(422, 27, 'FZA', 'Zapadnobosanska', TRUE),
(423, 27, 'SBL', 'Banja Luka', TRUE),
(424, 27, 'SDO', 'Doboj', TRUE),
(425, 27, 'SBI', 'Bijeljina', TRUE),
(426, 27, 'SVL', 'Vlasenica', TRUE),
(427, 27, 'SSR', 'Sarajevo-Romanija or Sokolac', TRUE),
(428, 27, 'SFO', 'Foca', TRUE),
(429, 27, 'STR', 'Trebinje', TRUE),
(430, 28, 'CE', 'Central', TRUE),
(431, 28, 'GH', 'Ghanzi', TRUE),
(432, 28, 'KD', 'Kgalagadi', TRUE),
(433, 28, 'KT', 'Kgatleng', TRUE),
(434, 28, 'KW', 'Kweneng', TRUE),
(435, 28, 'NG', 'Ngamiland', TRUE),
(436, 28, 'NE', 'North East', TRUE),
(437, 28, 'NW', 'North West', TRUE),
(438, 28, 'SE', 'South East', TRUE),
(439, 28, 'SO', 'Southern', TRUE),
(440, 30, 'AC', 'Acre', TRUE),
(441, 30, 'AL', 'Alagoas', TRUE),
(442, 30, 'AP', 'Amapá', TRUE),
(443, 30, 'AM', 'Amazonas', TRUE),
(444, 30, 'BA', 'Bahia', TRUE),
(445, 30, 'CE', 'Ceará', TRUE),
(446, 30, 'DF', 'Distrito Federal', TRUE),
(447, 30, 'ES', 'Espírito Santo', TRUE),
(448, 30, 'GO', 'Goiás', TRUE),
(449, 30, 'MA', 'Maranhão', TRUE),
(450, 30, 'MT', 'Mato Grosso', TRUE),
(451, 30, 'MS', 'Mato Grosso do Sul', TRUE),
(452, 30, 'MG', 'Minas Gerais', TRUE),
(453, 30, 'PA', 'Pará', TRUE),
(454, 30, 'PB', 'Paraíba', TRUE),
(455, 30, 'PR', 'Paraná', TRUE),
(456, 30, 'PE', 'Pernambuco', TRUE),
(457, 30, 'PI', 'Piauí', TRUE),
(458, 30, 'RJ', 'Rio de Janeiro', TRUE),
(459, 30, 'RN', 'Rio Grande do Norte', TRUE),
(460, 30, 'RS', 'Rio Grande do Sul', TRUE),
(461, 30, 'RO', 'Rondônia', TRUE),
(462, 30, 'RR', 'Roraima', TRUE),
(463, 30, 'SC', 'Santa Catarina', TRUE),
(464, 30, 'SP', 'São Paulo', TRUE),
(465, 30, 'SE', 'Sergipe', TRUE),
(466, 30, 'TO', 'Tocantins', TRUE),
(467, 31, 'PB', 'Peros Banhos', TRUE),
(468, 31, 'SI', 'Salomon Islands', TRUE),
(469, 31, 'NI', 'Nelsons Island', TRUE),
(470, 31, 'TB', 'Three Brothers', TRUE),
(471, 31, 'EA', 'Eagle Islands', TRUE),
(472, 31, 'DI', 'Danger Island', TRUE),
(473, 31, 'EG', 'Egmont Islands', TRUE),
(474, 31, 'DG', 'Diego Garcia', TRUE),
(475, 32, 'BEL', 'Belait', TRUE),
(476, 32, 'BRM', 'Brunei and Muara', TRUE),
(477, 32, 'TEM', 'Temburong', TRUE),
(478, 32, 'TUT', 'Tutong', TRUE),
(479, 33, '', 'Blagoevgrad', TRUE),
(480, 33, '', 'Burgas', TRUE),
(481, 33, '', 'Dobrich', TRUE),
(482, 33, '', 'Gabrovo', TRUE),
(483, 33, '', 'Haskovo', TRUE),
(484, 33, '', 'Kardjali', TRUE),
(485, 33, '', 'Kyustendil', TRUE),
(486, 33, '', 'Lovech', TRUE),
(487, 33, '', 'Montana', TRUE),
(488, 33, '', 'Pazardjik', TRUE),
(489, 33, '', 'Pernik', TRUE),
(490, 33, '', 'Pleven', TRUE),
(491, 33, '', 'Plovdiv', TRUE),
(492, 33, '', 'Razgrad', TRUE),
(493, 33, '', 'Shumen', TRUE),
(494, 33, '', 'Silistra', TRUE),
(495, 33, '', 'Sliven', TRUE),
(496, 33, '', 'Smolyan', TRUE),
(497, 33, '', 'Sofia', TRUE),
(498, 33, '', 'Sofia - town', TRUE),
(499, 33, '', 'Stara Zagora', TRUE),
(500, 33, '', 'Targovishte', TRUE),
(501, 33, '', 'Varna', TRUE),
(502, 33, '', 'Veliko Tarnovo', TRUE),
(503, 33, '', 'Vidin', TRUE),
(504, 33, '', 'Vratza', TRUE),
(505, 33, '', 'Yambol', TRUE),
(506, 34, 'BAL', 'Bale', TRUE),
(507, 34, 'BAM', 'Bam', TRUE),
(508, 34, 'BAN', 'Banwa', TRUE),
(509, 34, 'BAZ', 'Bazega', TRUE),
(510, 34, 'BOR', 'Bougouriba', TRUE),
(511, 34, 'BLG', 'Boulgou', TRUE),
(512, 34, 'BOK', 'Boulkiemde', TRUE),
(513, 34, 'COM', 'Comoe', TRUE),
(514, 34, 'GAN', 'Ganzourgou', TRUE),
(515, 34, 'GNA', 'Gnagna', TRUE),
(516, 34, 'GOU', 'Gourma', TRUE),
(517, 34, 'HOU', 'Houet', TRUE),
(518, 34, 'IOA', 'Ioba', TRUE),
(519, 34, 'KAD', 'Kadiogo', TRUE),
(520, 34, 'KEN', 'Kenedougou', TRUE),
(521, 34, 'KOD', 'Komondjari', TRUE),
(522, 34, 'KOP', 'Kompienga', TRUE),
(523, 34, 'KOS', 'Kossi', TRUE),
(524, 34, 'KOL', 'Koulpelogo', TRUE),
(525, 34, 'KOT', 'Kouritenga', TRUE),
(526, 34, 'KOW', 'Kourweogo', TRUE),
(527, 34, 'LER', 'Leraba', TRUE),
(528, 34, 'LOR', 'Loroum', TRUE),
(529, 34, 'MOU', 'Mouhoun', TRUE),
(530, 34, 'NAH', 'Nahouri', TRUE),
(531, 34, 'NAM', 'Namentenga', TRUE),
(532, 34, 'NAY', 'Nayala', TRUE),
(533, 34, 'NOU', 'Noumbiel', TRUE),
(534, 34, 'OUB', 'Oubritenga', TRUE),
(535, 34, 'OUD', 'Oudalan', TRUE),
(536, 34, 'PAS', 'Passore', TRUE),
(537, 34, 'PON', 'Poni', TRUE),
(538, 34, 'SAG', 'Sanguie', TRUE),
(539, 34, 'SAM', 'Sanmatenga', TRUE),
(540, 34, 'SEN', 'Seno', TRUE),
(541, 34, 'SIS', 'Sissili', TRUE),
(542, 34, 'SOM', 'Soum', TRUE),
(543, 34, 'SOR', 'Sourou', TRUE),
(544, 34, 'TAP', 'Tapoa', TRUE),
(545, 34, 'TUY', 'Tuy', TRUE),
(546, 34, 'YAG', 'Yagha', TRUE),
(547, 34, 'YAT', 'Yatenga', TRUE),
(548, 34, 'ZIR', 'Ziro', TRUE),
(549, 34, 'ZOD', 'Zondoma', TRUE),
(550, 34, 'ZOW', 'Zoundweogo', TRUE),
(551, 35, 'BB', 'Bubanza', TRUE),
(552, 35, 'BJ', 'Bujumbura', TRUE),
(553, 35, 'BR', 'Bururi', TRUE),
(554, 35, 'CA', 'Cankuzo', TRUE),
(555, 35, 'CI', 'Cibitoke', TRUE),
(556, 35, 'GI', 'Gitega', TRUE),
(557, 35, 'KR', 'Karuzi', TRUE),
(558, 35, 'KY', 'Kayanza', TRUE),
(559, 35, 'KI', 'Kirundo', TRUE),
(560, 35, 'MA', 'Makamba', TRUE),
(561, 35, 'MU', 'Muramvya', TRUE),
(562, 35, 'MY', 'Muyinga', TRUE),
(563, 35, 'MW', 'Mwaro', TRUE),
(564, 35, 'NG', 'Ngozi', TRUE),
(565, 35, 'RT', 'Rutana', TRUE),
(566, 35, 'RY', 'Ruyigi', TRUE),
(567, 36, 'PP', 'Phnom Penh', TRUE),
(568, 36, 'PS', 'Preah Seihanu (Kompong Som or Sihanoukville)', TRUE),
(569, 36, 'PA', 'Pailin', TRUE),
(570, 36, 'KB', 'Keb', TRUE),
(571, 36, 'BM', 'Banteay Meanchey', TRUE),
(572, 36, 'BA', 'Battambang', TRUE),
(573, 36, 'KM', 'Kampong Cham', TRUE),
(574, 36, 'KN', 'Kampong Chhnang', TRUE),
(575, 36, 'KU', 'Kampong Speu', TRUE),
(576, 36, 'KO', 'Kampong Som', TRUE),
(577, 36, 'KT', 'Kampong Thom', TRUE),
(578, 36, 'KP', 'Kampot', TRUE),
(579, 36, 'KL', 'Kandal', TRUE),
(580, 36, 'KK', 'Kaoh Kong', TRUE),
(581, 36, 'KR', 'Kratie', TRUE),
(582, 36, 'MK', 'Mondul Kiri', TRUE),
(583, 36, 'OM', 'Oddar Meancheay', TRUE),
(584, 36, 'PU', 'Pursat', TRUE),
(585, 36, 'PR', 'Preah Vihear', TRUE),
(586, 36, 'PG', 'Prey Veng', TRUE),
(587, 36, 'RK', 'Ratanak Kiri', TRUE),
(588, 36, 'SI', 'Siemreap', TRUE),
(589, 36, 'ST', 'Stung Treng', TRUE),
(590, 36, 'SR', 'Svay Rieng', TRUE),
(591, 36, 'TK', 'Takeo', TRUE),
(592, 37, 'ADA', 'Adamawa (Adamaoua)', TRUE),
(593, 37, 'CEN', 'Centre', TRUE),
(594, 37, 'EST', 'East (Est)', TRUE),
(595, 37, 'EXN', 'Extreme North (Extreme-Nord)', TRUE),
(596, 37, 'LIT', 'Littoral', TRUE),
(597, 37, 'NOR', 'North (Nord)', TRUE),
(598, 37, 'NOT', 'Northwest (Nord-Ouest)', TRUE),
(599, 37, 'OUE', 'West (Ouest)', TRUE),
(600, 37, 'SUD', 'South (Sud)', TRUE),
(601, 37, 'SOU', 'Southwest (Sud-Ouest).', TRUE),
(602, 38, 'AB', 'Alberta', TRUE),
(603, 38, 'BC', 'British Columbia', TRUE),
(604, 38, 'MB', 'Manitoba', TRUE),
(605, 38, 'NB', 'New Brunswick', TRUE),
(606, 38, 'NL', 'Newfoundland and Labrador', TRUE),
(607, 38, 'NT', 'Northwest Territories', TRUE),
(608, 38, 'NS', 'Nova Scotia', TRUE),
(609, 38, 'NU', 'Nunavut', TRUE),
(610, 38, 'ON', 'Ontario', TRUE),
(611, 38, 'PE', 'Prince Edward Island', TRUE),
(612, 38, 'QC', 'Qu&eacute;bec', TRUE),
(613, 38, 'SK', 'Saskatchewan', TRUE),
(614, 38, 'YT', 'Yukon Territory', TRUE),
(615, 39, 'BV', 'Boa Vista', TRUE),
(616, 39, 'BR', 'Brava', TRUE),
(617, 39, 'CS', 'Calheta de Sao Miguel', TRUE),
(618, 39, 'MA', 'Maio', TRUE),
(619, 39, 'MO', 'Mosteiros', TRUE),
(620, 39, 'PA', 'Paul', TRUE),
(621, 39, 'PN', 'Porto Novo', TRUE),
(622, 39, 'PR', 'Praia', TRUE),
(623, 39, 'RG', 'Ribeira Grande', TRUE),
(624, 39, 'SL', 'Sal', TRUE),
(625, 39, 'CA', 'Santa Catarina', TRUE),
(626, 39, 'CR', 'Santa Cruz', TRUE),
(627, 39, 'SD', 'Sao Domingos', TRUE),
(628, 39, 'SF', 'Sao Filipe', TRUE),
(629, 39, 'SN', 'Sao Nicolau', TRUE),
(630, 39, 'SV', 'Sao Vicente', TRUE),
(631, 39, 'TA', 'Tarrafal', TRUE),
(632, 40, 'CR', 'Creek', TRUE),
(633, 40, 'EA', 'Eastern', TRUE),
(634, 40, 'ML', 'Midland', TRUE),
(635, 40, 'ST', 'South Town', TRUE),
(636, 40, 'SP', 'Spot Bay', TRUE),
(637, 40, 'SK', 'Stake Bay', TRUE),
(638, 40, 'WD', 'West End', TRUE),
(639, 40, 'WN', 'Western', TRUE),
(640, 41, 'BBA', 'Bamingui-Bangoran', TRUE),
(641, 41, 'BKO', 'Basse-Kotto', TRUE),
(642, 41, 'HKO', 'Haute-Kotto', TRUE),
(643, 41, 'HMB', 'Haut-Mbomou', TRUE),
(644, 41, 'KEM', 'Kemo', TRUE),
(645, 41, 'LOB', 'Lobaye', TRUE),
(646, 41, 'MKD', 'Mambere-KadeÔ', TRUE),
(647, 41, 'MBO', 'Mbomou', TRUE),
(648, 41, 'NMM', 'Nana-Mambere', TRUE),
(649, 41, 'OMP', 'Ombella-M''Poko', TRUE),
(650, 41, 'OUK', 'Ouaka', TRUE),
(651, 41, 'OUH', 'Ouham', TRUE),
(652, 41, 'OPE', 'Ouham-Pende', TRUE),
(653, 41, 'VAK', 'Vakaga', TRUE),
(654, 41, 'NGR', 'Nana-Grebizi', TRUE),
(655, 41, 'SMB', 'Sangha-Mbaere', TRUE),
(656, 41, 'BAN', 'Bangui', TRUE),
(657, 42, 'BA', 'Batha', TRUE),
(658, 42, 'BI', 'Biltine', TRUE),
(659, 42, 'BE', 'Borkou-Ennedi-Tibesti', TRUE),
(660, 42, 'CB', 'Chari-Baguirmi', TRUE),
(661, 42, 'GU', 'Guera', TRUE),
(662, 42, 'KA', 'Kanem', TRUE),
(663, 42, 'LA', 'Lac', TRUE),
(664, 42, 'LC', 'Logone Occidental', TRUE),
(665, 42, 'LR', 'Logone Oriental', TRUE),
(666, 42, 'MK', 'Mayo-Kebbi', TRUE),
(667, 42, 'MC', 'Moyen-Chari', TRUE),
(668, 42, 'OU', 'Ouaddai', TRUE),
(669, 42, 'SA', 'Salamat', TRUE),
(670, 42, 'TA', 'Tandjile', TRUE),
(671, 43, 'AI', 'Aisen del General Carlos Ibanez', TRUE),
(672, 43, 'AN', 'Antofagasta', TRUE),
(673, 43, 'AR', 'Araucania', TRUE),
(674, 43, 'AT', 'Atacama', TRUE),
(675, 43, 'BI', 'Bio-Bio', TRUE),
(676, 43, 'CO', 'Coquimbo', TRUE),
(677, 43, 'LI', 'Libertador General Bernardo O''Hi', TRUE),
(678, 43, 'LL', 'Los Lagos', TRUE),
(679, 43, 'MA', 'Magallanes y de la Antartica Chi', TRUE),
(680, 43, 'ML', 'Maule', TRUE),
(681, 43, 'RM', 'Region Metropolitana', TRUE),
(682, 43, 'TA', 'Tarapaca', TRUE),
(683, 43, 'VS', 'Valparaiso', TRUE),
(684, 44, 'AN', 'Anhui', TRUE),
(685, 44, 'BE', 'Beijing', TRUE),
(686, 44, 'CH', 'Chongqing', TRUE),
(687, 44, 'FU', 'Fujian', TRUE),
(688, 44, 'GA', 'Gansu', TRUE),
(689, 44, 'GU', 'Guangdong', TRUE),
(690, 44, 'GX', 'Guangxi', TRUE),
(691, 44, 'GZ', 'Guizhou', TRUE),
(692, 44, 'HA', 'Hainan', TRUE),
(693, 44, 'HB', 'Hebei', TRUE),
(694, 44, 'HL', 'Heilongjiang', TRUE),
(695, 44, 'HE', 'Henan', TRUE),
(696, 44, 'HK', 'Hong Kong', TRUE),
(697, 44, 'HU', 'Hubei', TRUE),
(698, 44, 'HN', 'Hunan', TRUE),
(699, 44, 'IM', 'Inner Mongolia', TRUE),
(700, 44, 'JI', 'Jiangsu', TRUE),
(701, 44, 'JX', 'Jiangxi', TRUE),
(702, 44, 'JL', 'Jilin', TRUE),
(703, 44, 'LI', 'Liaoning', TRUE),
(704, 44, 'MA', 'Macau', TRUE),
(705, 44, 'NI', 'Ningxia', TRUE),
(706, 44, 'SH', 'Shaanxi', TRUE),
(707, 44, 'SA', 'Shandong', TRUE),
(708, 44, 'SG', 'Shanghai', TRUE),
(709, 44, 'SX', 'Shanxi', TRUE),
(710, 44, 'SI', 'Sichuan', TRUE),
(711, 44, 'TI', 'Tianjin', TRUE),
(712, 44, 'XI', 'Xinjiang', TRUE),
(713, 44, 'YU', 'Yunnan', TRUE),
(714, 44, 'ZH', 'Zhejiang', TRUE),
(715, 46, 'D', 'Direction Island', TRUE),
(716, 46, 'H', 'Home Island', TRUE),
(717, 46, 'O', 'Horsburgh Island', TRUE),
(718, 46, 'S', 'South Island', TRUE),
(719, 46, 'W', 'West Island', TRUE),
(720, 47, 'AMZ', 'Amazonas', TRUE),
(721, 47, 'ANT', 'Antioquia', TRUE),
(722, 47, 'ARA', 'Arauca', TRUE),
(723, 47, 'ATL', 'Atlantico', TRUE),
(724, 47, 'BDC', 'Bogota D.C.', TRUE),
(725, 47, 'BOL', 'Bolivar', TRUE),
(726, 47, 'BOY', 'Boyaca', TRUE),
(727, 47, 'CAL', 'Caldas', TRUE),
(728, 47, 'CAQ', 'Caqueta', TRUE),
(729, 47, 'CAS', 'Casanare', TRUE),
(730, 47, 'CAU', 'Cauca', TRUE),
(731, 47, 'CES', 'Cesar', TRUE),
(732, 47, 'CHO', 'Choco', TRUE),
(733, 47, 'COR', 'Cordoba', TRUE),
(734, 47, 'CAM', 'Cundinamarca', TRUE),
(735, 47, 'GNA', 'Guainia', TRUE),
(736, 47, 'GJR', 'Guajira', TRUE),
(737, 47, 'GVR', 'Guaviare', TRUE),
(738, 47, 'HUI', 'Huila', TRUE),
(739, 47, 'MAG', 'Magdalena', TRUE),
(740, 47, 'MET', 'Meta', TRUE),
(741, 47, 'NAR', 'Narino', TRUE),
(742, 47, 'NDS', 'Norte de Santander', TRUE),
(743, 47, 'PUT', 'Putumayo', TRUE),
(744, 47, 'QUI', 'Quindio', TRUE),
(745, 47, 'RIS', 'Risaralda', TRUE),
(746, 47, 'SAP', 'San Andres y Providencia', TRUE),
(747, 47, 'SAN', 'Santander', TRUE),
(748, 47, 'SUC', 'Sucre', TRUE),
(749, 47, 'TOL', 'Tolima', TRUE),
(750, 47, 'VDC', 'Valle del Cauca', TRUE),
(751, 47, 'VAU', 'Vaupes', TRUE),
(752, 47, 'VIC', 'Vichada', TRUE),
(753, 48, 'G', 'Grande Comore', TRUE),
(754, 48, 'A', 'Anjouan', TRUE),
(755, 48, 'M', 'Moheli', TRUE),
(756, 49, 'BO', 'Bouenza', TRUE),
(757, 49, 'BR', 'Brazzaville', TRUE),
(758, 49, 'CU', 'Cuvette', TRUE),
(759, 49, 'CO', 'Cuvette-Ouest', TRUE),
(760, 49, 'KO', 'Kouilou', TRUE),
(761, 49, 'LE', 'Lekoumou', TRUE),
(762, 49, 'LI', 'Likouala', TRUE),
(763, 49, 'NI', 'Niari', TRUE),
(764, 49, 'PL', 'Plateaux', TRUE),
(765, 49, 'PO', 'Pool', TRUE),
(766, 49, 'SA', 'Sangha', TRUE),
(767, 50, 'PU', 'Pukapuka', TRUE),
(768, 50, 'RK', 'Rakahanga', TRUE),
(769, 50, 'MK', 'Manihiki', TRUE),
(770, 50, 'PE', 'Penrhyn', TRUE),
(771, 50, 'NI', 'Nassau Island', TRUE),
(772, 50, 'SU', 'Surwarrow', TRUE),
(773, 50, 'PA', 'Palmerston', TRUE),
(774, 50, 'AI', 'Aitutaki', TRUE),
(775, 50, 'MA', 'Manuae', TRUE),
(776, 50, 'TA', 'Takutea', TRUE),
(777, 50, 'MT', 'Mitiaro', TRUE),
(778, 50, 'AT', 'Atiu', TRUE),
(779, 50, 'MU', 'Mauke', TRUE),
(780, 50, 'RR', 'Rarotonga', TRUE),
(781, 50, 'MG', 'Mangaia', TRUE),
(782, 51, 'AL', 'Alajuela', TRUE),
(783, 51, 'CA', 'Cartago', TRUE),
(784, 51, 'GU', 'Guanacaste', TRUE),
(785, 51, 'HE', 'Heredia', TRUE),
(786, 51, 'LI', 'Limon', TRUE),
(787, 51, 'PU', 'Puntarenas', TRUE),
(788, 51, 'SJ', 'San Jose', TRUE),
(789, 52, 'ABE', 'Abengourou', TRUE),
(790, 52, 'ABI', 'Abidjan', TRUE),
(791, 52, 'ABO', 'Aboisso', TRUE),
(792, 52, 'ADI', 'Adiake', TRUE),
(793, 52, 'ADZ', 'Adzope', TRUE),
(794, 52, 'AGB', 'Agboville', TRUE),
(795, 52, 'AGN', 'Agnibilekrou', TRUE),
(796, 52, 'ALE', 'Alepe', TRUE),
(797, 52, 'BOC', 'Bocanda', TRUE),
(798, 52, 'BAN', 'Bangolo', TRUE),
(799, 52, 'BEO', 'Beoumi', TRUE),
(800, 52, 'BIA', 'Biankouma', TRUE),
(801, 52, 'BDK', 'Bondoukou', TRUE),
(802, 52, 'BGN', 'Bongouanou', TRUE),
(803, 52, 'BFL', 'Bouafle', TRUE),
(804, 52, 'BKE', 'Bouake', TRUE),
(805, 52, 'BNA', 'Bouna', TRUE),
(806, 52, 'BDL', 'Boundiali', TRUE),
(807, 52, 'DKL', 'Dabakala', TRUE),
(808, 52, 'DBU', 'Dabou', TRUE),
(809, 52, 'DAL', 'Daloa', TRUE),
(810, 52, 'DAN', 'Danane', TRUE),
(811, 52, 'DAO', 'Daoukro', TRUE),
(812, 52, 'DIM', 'Dimbokro', TRUE),
(813, 52, 'DIV', 'Divo', TRUE),
(814, 52, 'DUE', 'Duekoue', TRUE),
(815, 52, 'FER', 'Ferkessedougou', TRUE),
(816, 52, 'GAG', 'Gagnoa', TRUE),
(817, 52, 'GBA', 'Grand-Bassam', TRUE),
(818, 52, 'GLA', 'Grand-Lahou', TRUE),
(819, 52, 'GUI', 'Guiglo', TRUE),
(820, 52, 'ISS', 'Issia', TRUE),
(821, 52, 'JAC', 'Jacqueville', TRUE),
(822, 52, 'KAT', 'Katiola', TRUE),
(823, 52, 'KOR', 'Korhogo', TRUE),
(824, 52, 'LAK', 'Lakota', TRUE),
(825, 52, 'MAN', 'Man', TRUE),
(826, 52, 'MKN', 'Mankono', TRUE),
(827, 52, 'MBA', 'Mbahiakro', TRUE),
(828, 52, 'ODI', 'Odienne', TRUE),
(829, 52, 'OUM', 'Oume', TRUE),
(830, 52, 'SAK', 'Sakassou', TRUE),
(831, 52, 'SPE', 'San-Pedro', TRUE),
(832, 52, 'SAS', 'Sassandra', TRUE),
(833, 52, 'SEG', 'Seguela', TRUE),
(834, 52, 'SIN', 'Sinfra', TRUE),
(835, 52, 'SOU', 'Soubre', TRUE),
(836, 52, 'TAB', 'Tabou', TRUE),
(837, 52, 'TAN', 'Tanda', TRUE),
(838, 52, 'TIE', 'Tiebissou', TRUE),
(839, 52, 'TIN', 'Tingrela', TRUE),
(840, 52, 'TIA', 'Tiassale', TRUE),
(841, 52, 'TBA', 'Touba', TRUE),
(842, 52, 'TLP', 'Toulepleu', TRUE),
(843, 52, 'TMD', 'Toumodi', TRUE),
(844, 52, 'VAV', 'Vavoua', TRUE),
(845, 52, 'YAM', 'Yamoussoukro', TRUE),
(846, 52, 'ZUE', 'Zuenoula', TRUE),
(847, 53, 'BB', 'Bjelovar-Bilogora', TRUE),
(848, 53, 'CZ', 'City of Zagreb', TRUE),
(849, 53, 'DN', 'Dubrovnik-Neretva', TRUE),
(850, 53, 'IS', 'Istra', TRUE),
(851, 53, 'KA', 'Karlovac', TRUE),
(852, 53, 'KK', 'Koprivnica-Krizevci', TRUE),
(853, 53, 'KZ', 'Krapina-Zagorje', TRUE),
(854, 53, 'LS', 'Lika-Senj', TRUE),
(855, 53, 'ME', 'Medimurje', TRUE),
(856, 53, 'OB', 'Osijek-Baranja', TRUE),
(857, 53, 'PS', 'Pozega-Slavonia', TRUE),
(858, 53, 'PG', 'Primorje-Gorski Kotar', TRUE),
(859, 53, 'SI', 'Sibenik', TRUE),
(860, 53, 'SM', 'Sisak-Moslavina', TRUE),
(861, 53, 'SB', 'Slavonski Brod-Posavina', TRUE),
(862, 53, 'SD', 'Split-Dalmatia', TRUE),
(863, 53, 'VA', 'Varazdin', TRUE),
(864, 53, 'VP', 'Virovitica-Podravina', TRUE),
(865, 53, 'VS', 'Vukovar-Srijem', TRUE),
(866, 53, 'ZK', 'Zadar-Knin', TRUE),
(867, 53, 'ZA', 'Zagreb', TRUE),
(868, 54, 'CA', 'Camaguey', TRUE),
(869, 54, 'CD', 'Ciego de Avila', TRUE),
(870, 54, 'CI', 'Cienfuegos', TRUE),
(871, 54, 'CH', 'Ciudad de La Habana', TRUE),
(872, 54, 'GR', 'Granma', TRUE),
(873, 54, 'GU', 'Guantanamo', TRUE),
(874, 54, 'HO', 'Holguin', TRUE),
(875, 54, 'IJ', 'Isla de la Juventud', TRUE),
(876, 54, 'LH', 'La Habana', TRUE),
(877, 54, 'LT', 'Las Tunas', TRUE),
(878, 54, 'MA', 'Matanzas', TRUE),
(879, 54, 'PR', 'Pinar del Rio', TRUE),
(880, 54, 'SS', 'Sancti Spiritus', TRUE),
(881, 54, 'SC', 'Santiago de Cuba', TRUE),
(882, 54, 'VC', 'Villa Clara', TRUE),
(883, 55, 'F', 'Famagusta', TRUE),
(884, 55, 'K', 'Kyrenia', TRUE),
(885, 55, 'A', 'Larnaca', TRUE),
(886, 55, 'I', 'Limassol', TRUE),
(887, 55, 'N', 'Nicosia', TRUE),
(888, 55, 'P', 'Paphos', TRUE),
(889, 56, 'U', 'Ústecký', TRUE),
(890, 56, 'C', 'Jihočeský', TRUE),
(891, 56, 'B', 'Jihomoravský', TRUE),
(892, 56, 'K', 'Karlovarský', TRUE),
(893, 56, 'H', 'Královehradecký', TRUE),
(894, 56, 'L', 'Liberecký', TRUE),
(895, 56, 'T', 'Moravskoslezský', TRUE),
(896, 56, 'M', 'Olomoucký', TRUE),
(897, 56, 'E', 'Pardubický', TRUE),
(898, 56, 'P', 'Plzeňský', TRUE),
(899, 56, 'A', 'Praha', TRUE),
(900, 56, 'S', 'Středočeský', TRUE),
(901, 56, 'J', 'Vysočina', TRUE),
(902, 56, 'Z', 'Zlínský', TRUE),
(903, 57, 'AR', 'Arhus', TRUE),
(904, 57, 'BH', 'Bornholm', TRUE),
(905, 57, 'CO', 'Copenhagen', TRUE),
(906, 57, 'FO', 'Faroe Islands', TRUE),
(907, 57, 'FR', 'Frederiksborg', TRUE),
(908, 57, 'FY', 'Fyn', TRUE),
(909, 57, 'KO', 'Kobenhavn', TRUE),
(910, 57, 'NO', 'Nordjylland', TRUE),
(911, 57, 'RI', 'Ribe', TRUE),
(912, 57, 'RK', 'Ringkobing', TRUE),
(913, 57, 'RO', 'Roskilde', TRUE),
(914, 57, 'SO', 'Sonderjylland', TRUE),
(915, 57, 'ST', 'Storstrom', TRUE),
(916, 57, 'VK', 'Vejle', TRUE),
(917, 57, 'VJ', 'Vestj&aelig;lland', TRUE),
(918, 57, 'VB', 'Viborg', TRUE),
(919, 58, 'S', '''Ali Sabih', TRUE),
(920, 58, 'K', 'Dikhil', TRUE),
(921, 58, 'J', 'Djibouti', TRUE),
(922, 58, 'O', 'Obock', TRUE),
(923, 58, 'T', 'Tadjoura', TRUE),
(924, 59, 'AND', 'Saint Andrew Parish', TRUE),
(925, 59, 'DAV', 'Saint David Parish', TRUE),
(926, 59, 'GEO', 'Saint George Parish', TRUE),
(927, 59, 'JOH', 'Saint John Parish', TRUE),
(928, 59, 'JOS', 'Saint Joseph Parish', TRUE),
(929, 59, 'LUK', 'Saint Luke Parish', TRUE),
(930, 59, 'MAR', 'Saint Mark Parish', TRUE),
(931, 59, 'PAT', 'Saint Patrick Parish', TRUE),
(932, 59, 'PAU', 'Saint Paul Parish', TRUE),
(933, 59, 'PET', 'Saint Peter Parish', TRUE),
(934, 60, 'DN', 'Distrito Nacional', TRUE),
(935, 60, 'AZ', 'Azua', TRUE),
(936, 60, 'BC', 'Baoruco', TRUE),
(937, 60, 'BH', 'Barahona', TRUE),
(938, 60, 'DJ', 'Dajabon', TRUE),
(939, 60, 'DU', 'Duarte', TRUE),
(940, 60, 'EL', 'Elias Pina', TRUE),
(941, 60, 'SY', 'El Seybo', TRUE),
(942, 60, 'ET', 'Espaillat', TRUE),
(943, 60, 'HM', 'Hato Mayor', TRUE),
(944, 60, 'IN', 'Independencia', TRUE),
(945, 60, 'AL', 'La Altagracia', TRUE),
(946, 60, 'RO', 'La Romana', TRUE),
(947, 60, 'VE', 'La Vega', TRUE),
(948, 60, 'MT', 'Maria Trinidad Sanchez', TRUE),
(949, 60, 'MN', 'Monsenor Nouel', TRUE),
(950, 60, 'MC', 'Monte Cristi', TRUE),
(951, 60, 'MP', 'Monte Plata', TRUE),
(952, 60, 'PD', 'Pedernales', TRUE),
(953, 60, 'PR', 'Peravia (Bani)', TRUE),
(954, 60, 'PP', 'Puerto Plata', TRUE),
(955, 60, 'SL', 'Salcedo', TRUE),
(956, 60, 'SM', 'Samana', TRUE),
(957, 60, 'SH', 'Sanchez Ramirez', TRUE),
(958, 60, 'SC', 'San Cristobal', TRUE),
(959, 60, 'JO', 'San Jose de Ocoa', TRUE),
(960, 60, 'SJ', 'San Juan', TRUE),
(961, 60, 'PM', 'San Pedro de Macoris', TRUE),
(962, 60, 'SA', 'Santiago', TRUE),
(963, 60, 'ST', 'Santiago Rodriguez', TRUE),
(964, 60, 'SD', 'Santo Domingo', TRUE),
(965, 60, 'VA', 'Valverde', TRUE),
(966, 61, 'AL', 'Aileu', TRUE),
(967, 61, 'AN', 'Ainaro', TRUE),
(968, 61, 'BA', 'Baucau', TRUE),
(969, 61, 'BO', 'Bobonaro', TRUE),
(970, 61, 'CO', 'Cova Lima', TRUE),
(971, 61, 'DI', 'Dili', TRUE),
(972, 61, 'ER', 'Ermera', TRUE),
(973, 61, 'LA', 'Lautem', TRUE),
(974, 61, 'LI', 'Liquica', TRUE),
(975, 61, 'MT', 'Manatuto', TRUE),
(976, 61, 'MF', 'Manufahi', TRUE),
(977, 61, 'OE', 'Oecussi', TRUE),
(978, 61, 'VI', 'Viqueque', TRUE),
(979, 62, 'AZU', 'Azuay', TRUE),
(980, 62, 'BOL', 'Bolivar', TRUE),
(981, 62, 'CAN', 'Ca&ntilde;ar', TRUE),
(982, 62, 'CAR', 'Carchi', TRUE),
(983, 62, 'CHI', 'Chimborazo', TRUE),
(984, 62, 'COT', 'Cotopaxi', TRUE),
(985, 62, 'EOR', 'El Oro', TRUE),
(986, 62, 'ESM', 'Esmeraldas', TRUE),
(987, 62, 'GPS', 'Gal&aacute;pagos', TRUE),
(988, 62, 'GUA', 'Guayas', TRUE),
(989, 62, 'IMB', 'Imbabura', TRUE),
(990, 62, 'LOJ', 'Loja', TRUE),
(991, 62, 'LRO', 'Los Rios', TRUE),
(992, 62, 'MAN', 'Manab&iacute;', TRUE),
(993, 62, 'MSA', 'Morona Santiago', TRUE),
(994, 62, 'NAP', 'Napo', TRUE),
(995, 62, 'ORE', 'Orellana', TRUE),
(996, 62, 'PAS', 'Pastaza', TRUE),
(997, 62, 'PIC', 'Pichincha', TRUE),
(998, 62, 'SUC', 'Sucumb&iacute;os', TRUE),
(999, 62, 'TUN', 'Tungurahua', TRUE),
(1000, 62, 'ZCH', 'Zamora Chinchipe', TRUE),
(1001, 63, 'DHY', 'Ad Daqahliyah', TRUE),
(1002, 63, 'BAM', 'Al Bahr al Ahmar', TRUE),
(1003, 63, 'BHY', 'Al Buhayrah', TRUE),
(1004, 63, 'FYM', 'Al Fayyum', TRUE),
(1005, 63, 'GBY', 'Al Gharbiyah', TRUE),
(1006, 63, 'IDR', 'Al Iskandariyah', TRUE),
(1007, 63, 'IML', 'Al Isma''iliyah', TRUE),
(1008, 63, 'JZH', 'Al Jizah', TRUE),
(1009, 63, 'MFY', 'Al Minufiyah', TRUE),
(1010, 63, 'MNY', 'Al Minya', TRUE),
(1011, 63, 'QHR', 'Al Qahirah', TRUE),
(1012, 63, 'QLY', 'Al Qalyubiyah', TRUE),
(1013, 63, 'WJD', 'Al Wadi al Jadid', TRUE),
(1014, 63, 'SHQ', 'Ash Sharqiyah', TRUE),
(1015, 63, 'SWY', 'As Suways', TRUE),
(1016, 63, 'ASW', 'Aswan', TRUE),
(1017, 63, 'ASY', 'Asyut', TRUE),
(1018, 63, 'BSW', 'Bani Suwayf', TRUE),
(1019, 63, 'BSD', 'Bur Sa''id', TRUE),
(1020, 63, 'DMY', 'Dumyat', TRUE),
(1021, 63, 'JNS', 'Janub Sina''', TRUE),
(1022, 63, 'KSH', 'Kafr ash Shaykh', TRUE),
(1023, 63, 'MAT', 'Matruh', TRUE),
(1024, 63, 'QIN', 'Qina', TRUE),
(1025, 63, 'SHS', 'Shamal Sina''', TRUE),
(1026, 63, 'SUH', 'Suhaj', TRUE),
(1027, 64, 'AH', 'Ahuachapan', TRUE),
(1028, 64, 'CA', 'Cabanas', TRUE),
(1029, 64, 'CH', 'Chalatenango', TRUE),
(1030, 64, 'CU', 'Cuscatlan', TRUE),
(1031, 64, 'LB', 'La Libertad', TRUE),
(1032, 64, 'PZ', 'La Paz', TRUE),
(1033, 64, 'UN', 'La Union', TRUE),
(1034, 64, 'MO', 'Morazan', TRUE),
(1035, 64, 'SM', 'San Miguel', TRUE),
(1036, 64, 'SS', 'San Salvador', TRUE),
(1037, 64, 'SV', 'San Vicente', TRUE),
(1038, 64, 'SA', 'Santa Ana', TRUE),
(1039, 64, 'SO', 'Sonsonate', TRUE),
(1040, 64, 'US', 'Usulutan', TRUE),
(1041, 65, 'AN', 'Provincia Annobon', TRUE),
(1042, 65, 'BN', 'Provincia Bioko Norte', TRUE),
(1043, 65, 'BS', 'Provincia Bioko Sur', TRUE),
(1044, 65, 'CS', 'Provincia Centro Sur', TRUE),
(1045, 65, 'KN', 'Provincia Kie-Ntem', TRUE),
(1046, 65, 'LI', 'Provincia Litoral', TRUE),
(1047, 65, 'WN', 'Provincia Wele-Nzas', TRUE),
(1048, 66, 'MA', 'Central (Maekel)', TRUE),
(1049, 66, 'KE', 'Anseba (Keren)', TRUE),
(1050, 66, 'DK', 'Southern Red Sea (Debub-Keih-Bahri)', TRUE),
(1051, 66, 'SK', 'Northern Red Sea (Semien-Keih-Bahri)', TRUE),
(1052, 66, 'DE', 'Southern (Debub)', TRUE),
(1053, 66, 'BR', 'Gash-Barka (Barentu)', TRUE),
(1054, 67, 'HA', 'Harjumaa (Tallinn)', TRUE),
(1055, 67, 'HI', 'Hiiumaa (Kardla)', TRUE),
(1056, 67, 'IV', 'Ida-Virumaa (Johvi)', TRUE),
(1057, 67, 'JA', 'Jarvamaa (Paide)', TRUE),
(1058, 67, 'JO', 'Jogevamaa (Jogeva)', TRUE),
(1059, 67, 'LV', 'Laane-Virumaa (Rakvere)', TRUE),
(1060, 67, 'LA', 'Laanemaa (Haapsalu)', TRUE),
(1061, 67, 'PA', 'Parnumaa (Parnu)', TRUE),
(1062, 67, 'PO', 'Polvamaa (Polva)', TRUE),
(1063, 67, 'RA', 'Raplamaa (Rapla)', TRUE),
(1064, 67, 'SA', 'Saaremaa (Kuessaare)', TRUE),
(1065, 67, 'TA', 'Tartumaa (Tartu)', TRUE),
(1066, 67, 'VA', 'Valgamaa (Valga)', TRUE),
(1067, 67, 'VI', 'Viljandimaa (Viljandi)', TRUE),
(1068, 67, 'VO', 'Vorumaa (Voru)', TRUE),
(1069, 68, 'AF', 'Afar', TRUE),
(1070, 68, 'AH', 'Amhara', TRUE),
(1071, 68, 'BG', 'Benishangul-Gumaz', TRUE),
(1072, 68, 'GB', 'Gambela', TRUE),
(1073, 68, 'HR', 'Hariai', TRUE),
(1074, 68, 'OR', 'Oromia', TRUE),
(1075, 68, 'SM', 'Somali', TRUE),
(1076, 68, 'SN', 'Southern Nations - Nationalities and Peoples Region', TRUE),
(1077, 68, 'TG', 'Tigray', TRUE),
(1078, 68, 'AA', 'Addis Ababa', TRUE),
(1079, 68, 'DD', 'Dire Dawa', TRUE),
(1080, 71, 'C', 'Central Division', TRUE),
(1081, 71, 'N', 'Northern Division', TRUE),
(1082, 71, 'E', 'Eastern Division', TRUE),
(1083, 71, 'W', 'Western Division', TRUE),
(1084, 71, 'R', 'Rotuma', TRUE),
(1085, 72, 'AL', 'Ahvenanmaan Laani', TRUE),
(1086, 72, 'ES', 'Etela-Suomen Laani', TRUE),
(1087, 72, 'IS', 'Ita-Suomen Laani', TRUE),
(1088, 72, 'LS', 'Lansi-Suomen Laani', TRUE),
(1089, 72, 'LA', 'Lapin Lanani', TRUE),
(1090, 72, 'OU', 'Oulun Laani', TRUE),
(1114, 74, '01', 'Ain', TRUE),
(1115, 74, '02', 'Aisne', TRUE),
(1116, 74, '03', 'Allier', TRUE),
(1117, 74, '04', 'Alpes de Haute Provence', TRUE),
(1118, 74, '05', 'Hautes-Alpes', TRUE),
(1119, 74, '06', 'Alpes Maritimes', TRUE),
(1120, 74, '07', 'Ard&egrave;che', TRUE),
(1121, 74, '08', 'Ardennes', TRUE),
(1122, 74, '09', 'Ari&egrave;ge', TRUE),
(1123, 74, '10', 'Aube', TRUE),
(1124, 74, '11', 'Aude', TRUE),
(1125, 74, '12', 'Aveyron', TRUE),
(1126, 74, '13', 'Bouches du Rh&ocirc;ne', TRUE),
(1127, 74, '14', 'Calvados', TRUE),
(1128, 74, '15', 'Cantal', TRUE),
(1129, 74, '16', 'Charente', TRUE),
(1130, 74, '17', 'Charente Maritime', TRUE),
(1131, 74, '18', 'Cher', TRUE),
(1132, 74, '19', 'Corr&egrave;ze', TRUE),
(1133, 74, '2A', 'Corse du Sud', TRUE),
(1134, 74, '2B', 'Haute Corse', TRUE),
(1135, 74, '21', 'C&ocirc;te d&#039;or', TRUE),
(1136, 74, '22', 'C&ocirc;tes d&#039;Armor', TRUE),
(1137, 74, '23', 'Creuse', TRUE),
(1138, 74, '24', 'Dordogne', TRUE),
(1139, 74, '25', 'Doubs', TRUE),
(1140, 74, '26', 'Dr&ocirc;me', TRUE),
(1141, 74, '27', 'Eure', TRUE),
(1142, 74, '28', 'Eure et Loir', TRUE),
(1143, 74, '29', 'Finist&egrave;re', TRUE),
(1144, 74, '30', 'Gard', TRUE),
(1145, 74, '31', 'Haute Garonne', TRUE),
(1146, 74, '32', 'Gers', TRUE),
(1147, 74, '33', 'Gironde', TRUE),
(1148, 74, '34', 'H&eacute;rault', TRUE),
(1149, 74, '35', 'Ille et Vilaine', TRUE),
(1150, 74, '36', 'Indre', TRUE),
(1151, 74, '37', 'Indre et Loire', TRUE),
(1152, 74, '38', 'Is&eacute;re', TRUE),
(1153, 74, '39', 'Jura', TRUE),
(1154, 74, '40', 'Landes', TRUE),
(1155, 74, '41', 'Loir et Cher', TRUE),
(1156, 74, '42', 'Loire', TRUE),
(1157, 74, '43', 'Haute Loire', TRUE),
(1158, 74, '44', 'Loire Atlantique', TRUE),
(1159, 74, '45', 'Loiret', TRUE),
(1160, 74, '46', 'Lot', TRUE),
(1161, 74, '47', 'Lot et Garonne', TRUE),
(1162, 74, '48', 'Loz&egrave;re', TRUE),
(1163, 74, '49', 'Maine et Loire', TRUE),
(1164, 74, '50', 'Manche', TRUE),
(1165, 74, '51', 'Marne', TRUE),
(1166, 74, '52', 'Haute Marne', TRUE),
(1167, 74, '53', 'Mayenne', TRUE),
(1168, 74, '54', 'Meurthe et Moselle', TRUE),
(1169, 74, '55', 'Meuse', TRUE),
(1170, 74, '56', 'Morbihan', TRUE),
(1171, 74, '57', 'Moselle', TRUE),
(1172, 74, '58', 'Ni&egrave;vre', TRUE),
(1173, 74, '59', 'Nord', TRUE),
(1174, 74, '60', 'Oise', TRUE),
(1175, 74, '61', 'Orne', TRUE),
(1176, 74, '62', 'Pas de Calais', TRUE),
(1177, 74, '63', 'Puy de D&ocirc;me', TRUE),
(1178, 74, '64', 'Pyr&eacute;n&eacute;es Atlantiques', TRUE),
(1179, 74, '65', 'Hautes Pyr&eacute;n&eacute;es', TRUE),
(1180, 74, '66', 'Pyr&eacute;n&eacute;es Orientales', TRUE),
(1181, 74, '67', 'Bas Rhin', TRUE),
(1182, 74, '68', 'Haut Rhin', TRUE),
(1183, 74, '69', 'Rh&ocirc;ne', TRUE),
(1184, 74, '70', 'Haute Sa&ocirc;ne', TRUE),
(1185, 74, '71', 'Sa&ocirc;ne et Loire', TRUE),
(1186, 74, '72', 'Sarthe', TRUE),
(1187, 74, '73', 'Savoie', TRUE),
(1188, 74, '74', 'Haute Savoie', TRUE),
(1189, 74, '75', 'Paris', TRUE),
(1190, 74, '76', 'Seine Maritime', TRUE),
(1191, 74, '77', 'Seine et Marne', TRUE),
(1192, 74, '78', 'Yvelines', TRUE),
(1193, 74, '79', 'Deux S&egrave;vres', TRUE),
(1194, 74, '80', 'Somme', TRUE),
(1195, 74, '81', 'Tarn', TRUE),
(1196, 74, '82', 'Tarn et Garonne', TRUE),
(1197, 74, '83', 'Var', TRUE),
(1198, 74, '84', 'Vaucluse', TRUE),
(1199, 74, '85', 'Vend&eacute;e', TRUE),
(1200, 74, '86', 'Vienne', TRUE),
(1201, 74, '87', 'Haute Vienne', TRUE),
(1202, 74, '88', 'Vosges', TRUE),
(1203, 74, '89', 'Yonne', TRUE),
(1204, 74, '90', 'Territoire de Belfort', TRUE),
(1205, 74, '91', 'Essonne', TRUE),
(1206, 74, '92', 'Hauts de Seine', TRUE),
(1207, 74, '93', 'Seine St-Denis', TRUE),
(1208, 74, '94', 'Val de Marne', TRUE),
(1209, 74, '95', 'Val d''Oise', TRUE),
(1210, 76, 'M', 'Archipel des Marquises', TRUE),
(1211, 76, 'T', 'Archipel des Tuamotu', TRUE),
(1212, 76, 'I', 'Archipel des Tubuai', TRUE),
(1213, 76, 'V', 'Iles du Vent', TRUE),
(1214, 76, 'S', 'Iles Sous-le-Vent', TRUE),
(1215, 77, 'C', 'Iles Crozet', TRUE),
(1216, 77, 'K', 'Iles Kerguelen', TRUE),
(1217, 77, 'A', 'Ile Amsterdam', TRUE),
(1218, 77, 'P', 'Ile Saint-Paul', TRUE),
(1219, 77, 'D', 'Adelie Land', TRUE),
(1220, 78, 'ES', 'Estuaire', TRUE),
(1221, 78, 'HO', 'Haut-Ogooue', TRUE),
(1222, 78, 'MO', 'Moyen-Ogooue', TRUE),
(1223, 78, 'NG', 'Ngounie', TRUE),
(1224, 78, 'NY', 'Nyanga', TRUE),
(1225, 78, 'OI', 'Ogooue-Ivindo', TRUE),
(1226, 78, 'OL', 'Ogooue-Lolo', TRUE),
(1227, 78, 'OM', 'Ogooue-Maritime', TRUE),
(1228, 78, 'WN', 'Woleu-Ntem', TRUE),
(1229, 79, 'BJ', 'Banjul', TRUE),
(1230, 79, 'BS', 'Basse', TRUE),
(1231, 79, 'BR', 'Brikama', TRUE),
(1232, 79, 'JA', 'Janjangbure', TRUE),
(1233, 79, 'KA', 'Kanifeng', TRUE),
(1234, 79, 'KE', 'Kerewan', TRUE),
(1235, 79, 'KU', 'Kuntaur', TRUE),
(1236, 79, 'MA', 'Mansakonko', TRUE),
(1237, 79, 'LR', 'Lower River', TRUE),
(1238, 79, 'CR', 'Central River', TRUE),
(1239, 79, 'NB', 'North Bank', TRUE),
(1240, 79, 'UR', 'Upper River', TRUE),
(1241, 79, 'WE', 'Western', TRUE),
(1242, 80, 'AB', 'Abkhazia', TRUE),
(1243, 80, 'AJ', 'Ajaria', TRUE),
(1244, 80, 'TB', 'Tbilisi', TRUE),
(1245, 80, 'GU', 'Guria', TRUE),
(1246, 80, 'IM', 'Imereti', TRUE),
(1247, 80, 'KA', 'Kakheti', TRUE),
(1248, 80, 'KK', 'Kvemo Kartli', TRUE),
(1249, 80, 'MM', 'Mtskheta-Mtianeti', TRUE),
(1250, 80, 'RL', 'Racha Lechkhumi and Kvemo Svanet', TRUE),
(1251, 80, 'SZ', 'Samegrelo-Zemo Svaneti', TRUE),
(1252, 80, 'SJ', 'Samtskhe-Javakheti', TRUE),
(1253, 80, 'SK', 'Shida Kartli', TRUE),
(1254, 81, 'BAW', 'Baden-W&uuml;rttemberg', TRUE),
(1255, 81, 'BAY', 'Bayern', TRUE),
(1256, 81, 'BER', 'Berlin', TRUE),
(1257, 81, 'BRG', 'Brandenburg', TRUE),
(1258, 81, 'BRE', 'Bremen', TRUE),
(1259, 81, 'HAM', 'Hamburg', TRUE),
(1260, 81, 'HES', 'Hessen', TRUE),
(1261, 81, 'MEC', 'Mecklenburg-Vorpommern', TRUE),
(1262, 81, 'NDS', 'Niedersachsen', TRUE),
(1263, 81, 'NRW', 'Nordrhein-Westfalen', TRUE),
(1264, 81, 'RHE', 'Rheinland-Pfalz', TRUE),
(1265, 81, 'SAR', 'Saarland', TRUE),
(1266, 81, 'SAS', 'Sachsen', TRUE),
(1267, 81, 'SAC', 'Sachsen-Anhalt', TRUE),
(1268, 81, 'SCN', 'Schleswig-Holstein', TRUE),
(1269, 81, 'THE', 'Th&uuml;ringen', TRUE),
(1270, 82, 'AS', 'Ashanti Region', TRUE),
(1271, 82, 'BA', 'Brong-Ahafo Region', TRUE),
(1272, 82, 'CE', 'Central Region', TRUE),
(1273, 82, 'EA', 'Eastern Region', TRUE),
(1274, 82, 'GA', 'Greater Accra Region', TRUE),
(1275, 82, 'NO', 'Northern Region', TRUE),
(1276, 82, 'UE', 'Upper East Region', TRUE),
(1277, 82, 'UW', 'Upper West Region', TRUE),
(1278, 82, 'VO', 'Volta Region', TRUE),
(1279, 82, 'WE', 'Western Region', TRUE),
(1280, 84, 'AT', 'Attica', TRUE),
(1281, 84, 'CN', 'Central Greece', TRUE),
(1282, 84, 'CM', 'Central Macedonia', TRUE),
(1283, 84, 'CR', 'Crete', TRUE),
(1284, 84, 'EM', 'East Macedonia and Thrace', TRUE),
(1285, 84, 'EP', 'Epirus', TRUE),
(1286, 84, 'II', 'Ionian Islands', TRUE),
(1287, 84, 'NA', 'North Aegean', TRUE),
(1288, 84, 'PP', 'Peloponnesos', TRUE),
(1289, 84, 'SA', 'South Aegean', TRUE),
(1290, 84, 'TH', 'Thessaly', TRUE),
(1291, 84, 'WG', 'West Greece', TRUE),
(1292, 84, 'WM', 'West Macedonia', TRUE),
(1293, 85, 'A', 'Avannaa', TRUE),
(1294, 85, 'T', 'Tunu', TRUE),
(1295, 85, 'K', 'Kitaa', TRUE),
(1296, 86, 'A', 'Saint Andrew', TRUE),
(1297, 86, 'D', 'Saint David', TRUE),
(1298, 86, 'G', 'Saint George', TRUE),
(1299, 86, 'J', 'Saint John', TRUE),
(1300, 86, 'M', 'Saint Mark', TRUE),
(1301, 86, 'P', 'Saint Patrick', TRUE),
(1302, 86, 'C', 'Carriacou', TRUE),
(1303, 86, 'Q', 'Petit Martinique', TRUE),
(1304, 89, 'AV', 'Alta Verapaz', TRUE),
(1305, 89, 'BV', 'Baja Verapaz', TRUE),
(1306, 89, 'CM', 'Chimaltenango', TRUE),
(1307, 89, 'CQ', 'Chiquimula', TRUE),
(1308, 89, 'PE', 'El Peten', TRUE),
(1309, 89, 'PR', 'El Progreso', TRUE),
(1310, 89, 'QC', 'El Quiche', TRUE),
(1311, 89, 'ES', 'Escuintla', TRUE),
(1312, 89, 'GU', 'Guatemala', TRUE),
(1313, 89, 'HU', 'Huehuetenango', TRUE),
(1314, 89, 'IZ', 'Izabal', TRUE),
(1315, 89, 'JA', 'Jalapa', TRUE),
(1316, 89, 'JU', 'Jutiapa', TRUE),
(1317, 89, 'QZ', 'Quetzaltenango', TRUE),
(1318, 89, 'RE', 'Retalhuleu', TRUE),
(1319, 89, 'ST', 'Sacatepequez', TRUE),
(1320, 89, 'SM', 'San Marcos', TRUE),
(1321, 89, 'SR', 'Santa Rosa', TRUE),
(1322, 89, 'SO', 'Solola', TRUE),
(1323, 89, 'SU', 'Suchitepequez', TRUE),
(1324, 89, 'TO', 'Totonicapan', TRUE),
(1325, 89, 'ZA', 'Zacapa', TRUE),
(1326, 90, 'CNK', 'Conakry', TRUE),
(1327, 90, 'BYL', 'Beyla', TRUE),
(1328, 90, 'BFA', 'Boffa', TRUE),
(1329, 90, 'BOK', 'Boke', TRUE),
(1330, 90, 'COY', 'Coyah', TRUE),
(1331, 90, 'DBL', 'Dabola', TRUE),
(1332, 90, 'DLB', 'Dalaba', TRUE),
(1333, 90, 'DGR', 'Dinguiraye', TRUE),
(1334, 90, 'DBR', 'Dubreka', TRUE),
(1335, 90, 'FRN', 'Faranah', TRUE),
(1336, 90, 'FRC', 'Forecariah', TRUE),
(1337, 90, 'FRI', 'Fria', TRUE),
(1338, 90, 'GAO', 'Gaoual', TRUE),
(1339, 90, 'GCD', 'Gueckedou', TRUE),
(1340, 90, 'KNK', 'Kankan', TRUE),
(1341, 90, 'KRN', 'Kerouane', TRUE),
(1342, 90, 'KND', 'Kindia', TRUE),
(1343, 90, 'KSD', 'Kissidougou', TRUE),
(1344, 90, 'KBA', 'Koubia', TRUE),
(1345, 90, 'KDA', 'Koundara', TRUE),
(1346, 90, 'KRA', 'Kouroussa', TRUE),
(1347, 90, 'LAB', 'Labe', TRUE),
(1348, 90, 'LLM', 'Lelouma', TRUE),
(1349, 90, 'LOL', 'Lola', TRUE),
(1350, 90, 'MCT', 'Macenta', TRUE),
(1351, 90, 'MAL', 'Mali', TRUE),
(1352, 90, 'MAM', 'Mamou', TRUE),
(1353, 90, 'MAN', 'Mandiana', TRUE),
(1354, 90, 'NZR', 'Nzerekore', TRUE),
(1355, 90, 'PIT', 'Pita', TRUE),
(1356, 90, 'SIG', 'Siguiri', TRUE),
(1357, 90, 'TLM', 'Telimele', TRUE),
(1358, 90, 'TOG', 'Tougue', TRUE),
(1359, 90, 'YOM', 'Yomou', TRUE),
(1360, 91, 'BF', 'Bafata Region', TRUE),
(1361, 91, 'BB', 'Biombo Region', TRUE),
(1362, 91, 'BS', 'Bissau Region', TRUE),
(1363, 91, 'BL', 'Bolama Region', TRUE),
(1364, 91, 'CA', 'Cacheu Region', TRUE),
(1365, 91, 'GA', 'Gabu Region', TRUE),
(1366, 91, 'OI', 'Oio Region', TRUE),
(1367, 91, 'QU', 'Quinara Region', TRUE),
(1368, 91, 'TO', 'Tombali Region', TRUE),
(1369, 92, 'BW', 'Barima-Waini', TRUE),
(1370, 92, 'CM', 'Cuyuni-Mazaruni', TRUE),
(1371, 92, 'DM', 'Demerara-Mahaica', TRUE),
(1372, 92, 'EC', 'East Berbice-Corentyne', TRUE),
(1373, 92, 'EW', 'Essequibo Islands-West Demerara', TRUE),
(1374, 92, 'MB', 'Mahaica-Berbice', TRUE),
(1375, 92, 'PM', 'Pomeroon-Supenaam', TRUE),
(1376, 92, 'PI', 'Potaro-Siparuni', TRUE),
(1377, 92, 'UD', 'Upper Demerara-Berbice', TRUE),
(1378, 92, 'UT', 'Upper Takutu-Upper Essequibo', TRUE),
(1379, 93, 'AR', 'Artibonite', TRUE),
(1380, 93, 'CE', 'Centre', TRUE),
(1381, 93, 'GA', 'Grand''Anse', TRUE),
(1382, 93, 'ND', 'Nord', TRUE),
(1383, 93, 'NE', 'Nord-Est', TRUE),
(1384, 93, 'NO', 'Nord-Ouest', TRUE),
(1385, 93, 'OU', 'Ouest', TRUE),
(1386, 93, 'SD', 'Sud', TRUE),
(1387, 93, 'SE', 'Sud-Est', TRUE),
(1388, 94, 'F', 'Flat Island', TRUE),
(1389, 94, 'M', 'McDonald Island', TRUE),
(1390, 94, 'S', 'Shag Island', TRUE),
(1391, 94, 'H', 'Heard Island', TRUE),
(1392, 95, 'AT', 'Atlantida', TRUE),
(1393, 95, 'CH', 'Choluteca', TRUE),
(1394, 95, 'CL', 'Colon', TRUE),
(1395, 95, 'CM', 'Comayagua', TRUE),
(1396, 95, 'CP', 'Copan', TRUE),
(1397, 95, 'CR', 'Cortes', TRUE),
(1398, 95, 'PA', 'El Paraiso', TRUE),
(1399, 95, 'FM', 'Francisco Morazan', TRUE),
(1400, 95, 'GD', 'Gracias a Dios', TRUE),
(1401, 95, 'IN', 'Intibuca', TRUE),
(1402, 95, 'IB', 'Islas de la Bahia (Bay Islands)', TRUE),
(1403, 95, 'PZ', 'La Paz', TRUE),
(1404, 95, 'LE', 'Lempira', TRUE),
(1405, 95, 'OC', 'Ocotepeque', TRUE),
(1406, 95, 'OL', 'Olancho', TRUE),
(1407, 95, 'SB', 'Santa Barbara', TRUE),
(1408, 95, 'VA', 'Valle', TRUE),
(1409, 95, 'YO', 'Yoro', TRUE),
(1410, 96, 'HCW', 'Central and Western Hong Kong Island', TRUE),
(1411, 96, 'HEA', 'Eastern Hong Kong Island', TRUE),
(1412, 96, 'HSO', 'Southern Hong Kong Island', TRUE),
(1413, 96, 'HWC', 'Wan Chai Hong Kong Island', TRUE),
(1414, 96, 'KKC', 'Kowloon City Kowloon', TRUE),
(1415, 96, 'KKT', 'Kwun Tong Kowloon', TRUE),
(1416, 96, 'KSS', 'Sham Shui Po Kowloon', TRUE),
(1417, 96, 'KWT', 'Wong Tai Sin Kowloon', TRUE),
(1418, 96, 'KYT', 'Yau Tsim Mong Kowloon', TRUE),
(1419, 96, 'NIS', 'Islands New Territories', TRUE),
(1420, 96, 'NKT', 'Kwai Tsing New Territories', TRUE),
(1421, 96, 'NNO', 'North New Territories', TRUE),
(1422, 96, 'NSK', 'Sai Kung New Territories', TRUE),
(1423, 96, 'NST', 'Sha Tin New Territories', TRUE),
(1424, 96, 'NTP', 'Tai Po New Territories', TRUE),
(1425, 96, 'NTW', 'Tsuen Wan New Territories', TRUE),
(1426, 96, 'NTM', 'Tuen Mun New Territories', TRUE),
(1427, 96, 'NYL', 'Yuen Long New Territories', TRUE),
(1428, 97, 'BK', 'Bacs-Kiskun', TRUE),
(1429, 97, 'BA', 'Baranya', TRUE),
(1430, 97, 'BE', 'Bekes', TRUE),
(1431, 97, 'BS', 'Bekescsaba', TRUE),
(1432, 97, 'BZ', 'Borsod-Abauj-Zemplen', TRUE),
(1433, 97, 'BU', 'Budapest', TRUE),
(1434, 97, 'CS', 'Csongrad', TRUE),
(1435, 97, 'DE', 'Debrecen', TRUE),
(1436, 97, 'DU', 'Dunaujvaros', TRUE),
(1437, 97, 'EG', 'Eger', TRUE),
(1438, 97, 'FE', 'Fejer', TRUE),
(1439, 97, 'GY', 'Gyor', TRUE),
(1440, 97, 'GM', 'Gyor-Moson-Sopron', TRUE),
(1441, 97, 'HB', 'Hajdu-Bihar', TRUE),
(1442, 97, 'HE', 'Heves', TRUE),
(1443, 97, 'HO', 'Hodmezovasarhely', TRUE),
(1444, 97, 'JN', 'Jasz-Nagykun-Szolnok', TRUE),
(1445, 97, 'KA', 'Kaposvar', TRUE),
(1446, 97, 'KE', 'Kecskemet', TRUE),
(1447, 97, 'KO', 'Komarom-Esztergom', TRUE),
(1448, 97, 'MI', 'Miskolc', TRUE),
(1449, 97, 'NA', 'Nagykanizsa', TRUE),
(1450, 97, 'NO', 'Nograd', TRUE),
(1451, 97, 'NY', 'Nyiregyhaza', TRUE),
(1452, 97, 'PE', 'Pecs', TRUE),
(1453, 97, 'PS', 'Pest', TRUE),
(1454, 97, 'SO', 'Somogy', TRUE),
(1455, 97, 'SP', 'Sopron', TRUE),
(1456, 97, 'SS', 'Szabolcs-Szatmar-Bereg', TRUE),
(1457, 97, 'SZ', 'Szeged', TRUE),
(1458, 97, 'SE', 'Szekesfehervar', TRUE),
(1459, 97, 'SL', 'Szolnok', TRUE),
(1460, 97, 'SM', 'Szombathely', TRUE),
(1461, 97, 'TA', 'Tatabanya', TRUE),
(1462, 97, 'TO', 'Tolna', TRUE),
(1463, 97, 'VA', 'Vas', TRUE),
(1464, 97, 'VE', 'Veszprem', TRUE),
(1465, 97, 'ZA', 'Zala', TRUE),
(1466, 97, 'ZZ', 'Zalaegerszeg', TRUE),
(1467, 98, 'AL', 'Austurland', TRUE),
(1468, 98, 'HF', 'Hofuoborgarsvaeoi', TRUE),
(1469, 98, 'NE', 'Norourland eystra', TRUE),
(1470, 98, 'NV', 'Norourland vestra', TRUE),
(1471, 98, 'SL', 'Suourland', TRUE),
(1472, 98, 'SN', 'Suournes', TRUE),
(1473, 98, 'VF', 'Vestfiroir', TRUE),
(1474, 98, 'VL', 'Vesturland', TRUE),
(1475, 99, 'AN', 'Andaman and Nicobar Islands', TRUE),
(1476, 99, 'AP', 'Andhra Pradesh', TRUE),
(1477, 99, 'AR', 'Arunachal Pradesh', TRUE),
(1478, 99, 'AS', 'Assam', TRUE),
(1479, 99, 'BI', 'Bihar', TRUE),
(1480, 99, 'CH', 'Chandigarh', TRUE),
(1481, 99, 'DA', 'Dadra and Nagar Haveli', TRUE),
(1482, 99, 'DM', 'Daman and Diu', TRUE),
(1483, 99, 'DE', 'Delhi', TRUE),
(1484, 99, 'GO', 'Goa', TRUE),
(1485, 99, 'GU', 'Gujarat', TRUE),
(1486, 99, 'HA', 'Haryana', TRUE),
(1487, 99, 'HP', 'Himachal Pradesh', TRUE),
(1488, 99, 'JA', 'Jammu and Kashmir', TRUE),
(1489, 99, 'KA', 'Karnataka', TRUE),
(1490, 99, 'KE', 'Kerala', TRUE),
(1491, 99, 'LI', 'Lakshadweep Islands', TRUE),
(1492, 99, 'MP', 'Madhya Pradesh', TRUE),
(1493, 99, 'MA', 'Maharashtra', TRUE),
(1494, 99, 'MN', 'Manipur', TRUE),
(1495, 99, 'ME', 'Meghalaya', TRUE),
(1496, 99, 'MI', 'Mizoram', TRUE),
(1497, 99, 'NA', 'Nagaland', TRUE),
(1498, 99, 'OR', 'Orissa', TRUE),
(1499, 99, 'PO', 'Pondicherry', TRUE),
(1500, 99, 'PU', 'Punjab', TRUE),
(1501, 99, 'RA', 'Rajasthan', TRUE),
(1502, 99, 'SI', 'Sikkim', TRUE),
(1503, 99, 'TN', 'Tamil Nadu', TRUE),
(1504, 99, 'TR', 'Tripura', TRUE),
(1505, 99, 'UP', 'Uttar Pradesh', TRUE),
(1506, 99, 'WB', 'West Bengal', TRUE),
(1507, 100, 'AC', 'Aceh', TRUE),
(1508, 100, 'BA', 'Bali', TRUE),
(1509, 100, 'BT', 'Banten', TRUE),
(1510, 100, 'BE', 'Bengkulu', TRUE),
(1511, 100, 'BD', 'BoDeTaBek', TRUE),
(1512, 100, 'GO', 'Gorontalo', TRUE),
(1513, 100, 'JK', 'Jakarta Raya', TRUE),
(1514, 100, 'JA', 'Jambi', TRUE),
(1515, 100, 'JB', 'Jawa Barat', TRUE),
(1516, 100, 'JT', 'Jawa Tengah', TRUE),
(1517, 100, 'JI', 'Jawa Timur', TRUE),
(1518, 100, 'KB', 'Kalimantan Barat', TRUE),
(1519, 100, 'KS', 'Kalimantan Selatan', TRUE),
(1520, 100, 'KT', 'Kalimantan Tengah', TRUE),
(1521, 100, 'KI', 'Kalimantan Timur', TRUE),
(1522, 100, 'BB', 'Kepulauan Bangka Belitung', TRUE),
(1523, 100, 'LA', 'Lampung', TRUE),
(1524, 100, 'MA', 'Maluku', TRUE),
(1525, 100, 'MU', 'Maluku Utara', TRUE),
(1526, 100, 'NB', 'Nusa Tenggara Barat', TRUE),
(1527, 100, 'NT', 'Nusa Tenggara Timur', TRUE),
(1528, 100, 'PA', 'Papua', TRUE),
(1529, 100, 'RI', 'Riau', TRUE),
(1530, 100, 'SN', 'Sulawesi Selatan', TRUE),
(1531, 100, 'ST', 'Sulawesi Tengah', TRUE),
(1532, 100, 'SG', 'Sulawesi Tenggara', TRUE),
(1533, 100, 'SA', 'Sulawesi Utara', TRUE),
(1534, 100, 'SB', 'Sumatera Barat', TRUE),
(1535, 100, 'SS', 'Sumatera Selatan', TRUE),
(1536, 100, 'SU', 'Sumatera Utara', TRUE);
INSERT INTO oc_zone (zone_id, country_id, code, name, status) VALUES
(1537, 100, 'YO', 'Yogyakarta', TRUE),
(1538, 101, 'TEH', 'Tehran', TRUE),
(1539, 101, 'QOM', 'Qom', TRUE),
(1540, 101, 'MKZ', 'Markazi', TRUE),
(1541, 101, 'QAZ', 'Qazvin', TRUE),
(1542, 101, 'GIL', 'Gilan', TRUE),
(1543, 101, 'ARD', 'Ardabil', TRUE),
(1544, 101, 'ZAN', 'Zanjan', TRUE),
(1545, 101, 'EAZ', 'East Azarbaijan', TRUE),
(1546, 101, 'WEZ', 'West Azarbaijan', TRUE),
(1547, 101, 'KRD', 'Kurdistan', TRUE),
(1548, 101, 'HMD', 'Hamadan', TRUE),
(1549, 101, 'KRM', 'Kermanshah', TRUE),
(1550, 101, 'ILM', 'Ilam', TRUE),
(1551, 101, 'LRS', 'Lorestan', TRUE),
(1552, 101, 'KZT', 'Khuzestan', TRUE),
(1553, 101, 'CMB', 'Chahar Mahaal and Bakhtiari', TRUE),
(1554, 101, 'KBA', 'Kohkiluyeh and Buyer Ahmad', TRUE),
(1555, 101, 'BSH', 'Bushehr', TRUE),
(1556, 101, 'FAR', 'Fars', TRUE),
(1557, 101, 'HRM', 'Hormozgan', TRUE),
(1558, 101, 'SBL', 'Sistan and Baluchistan', TRUE),
(1559, 101, 'KRB', 'Kerman', TRUE),
(1560, 101, 'YZD', 'Yazd', TRUE),
(1561, 101, 'EFH', 'Esfahan', TRUE),
(1562, 101, 'SMN', 'Semnan', TRUE),
(1563, 101, 'MZD', 'Mazandaran', TRUE),
(1564, 101, 'GLS', 'Golestan', TRUE),
(1565, 101, 'NKH', 'North Khorasan', TRUE),
(1566, 101, 'RKH', 'Razavi Khorasan', TRUE),
(1567, 101, 'SKH', 'South Khorasan', TRUE),
(1568, 102, 'BD', 'Baghdad', TRUE),
(1569, 102, 'SD', 'Salah ad Din', TRUE),
(1570, 102, 'DY', 'Diyala', TRUE),
(1571, 102, 'WS', 'Wasit', TRUE),
(1572, 102, 'MY', 'Maysan', TRUE),
(1573, 102, 'BA', 'Al Basrah', TRUE),
(1574, 102, 'DQ', 'Dhi Qar', TRUE),
(1575, 102, 'MU', 'Al Muthanna', TRUE),
(1576, 102, 'QA', 'Al Qadisyah', TRUE),
(1577, 102, 'BB', 'Babil', TRUE),
(1578, 102, 'KB', 'Al Karbala', TRUE),
(1579, 102, 'NJ', 'An Najaf', TRUE),
(1580, 102, 'AB', 'Al Anbar', TRUE),
(1581, 102, 'NN', 'Ninawa', TRUE),
(1582, 102, 'DH', 'Dahuk', TRUE),
(1583, 102, 'AL', 'Arbil', TRUE),
(1584, 102, 'TM', 'At Ta''mim', TRUE),
(1585, 102, 'SL', 'As Sulaymaniyah', TRUE),
(1586, 103, 'CA', 'Carlow', TRUE),
(1587, 103, 'CV', 'Cavan', TRUE),
(1588, 103, 'CL', 'Clare', TRUE),
(1589, 103, 'CO', 'Cork', TRUE),
(1590, 103, 'DO', 'Donegal', TRUE),
(1591, 103, 'DU', 'Dublin', TRUE),
(1592, 103, 'GA', 'Galway', TRUE),
(1593, 103, 'KE', 'Kerry', TRUE),
(1594, 103, 'KI', 'Kildare', TRUE),
(1595, 103, 'KL', 'Kilkenny', TRUE),
(1596, 103, 'LA', 'Laois', TRUE),
(1597, 103, 'LE', 'Leitrim', TRUE),
(1598, 103, 'LI', 'Limerick', TRUE),
(1599, 103, 'LO', 'Longford', TRUE),
(1600, 103, 'LU', 'Louth', TRUE),
(1601, 103, 'MA', 'Mayo', TRUE),
(1602, 103, 'ME', 'Meath', TRUE),
(1603, 103, 'MO', 'Monaghan', TRUE),
(1604, 103, 'OF', 'Offaly', TRUE),
(1605, 103, 'RO', 'Roscommon', TRUE),
(1606, 103, 'SL', 'Sligo', TRUE),
(1607, 103, 'TI', 'Tipperary', TRUE),
(1608, 103, 'WA', 'Waterford', TRUE),
(1609, 103, 'WE', 'Westmeath', TRUE),
(1610, 103, 'WX', 'Wexford', TRUE),
(1611, 103, 'WI', 'Wicklow', TRUE),
(1612, 104, 'BS', 'Be''er Sheva', TRUE),
(1613, 104, 'BH', 'Bika''at Hayarden', TRUE),
(1614, 104, 'EA', 'Eilat and Arava', TRUE),
(1615, 104, 'GA', 'Galil', TRUE),
(1616, 104, 'HA', 'Haifa', TRUE),
(1617, 104, 'JM', 'Jehuda Mountains', TRUE),
(1618, 104, 'JE', 'Jerusalem', TRUE),
(1619, 104, 'NE', 'Negev', TRUE),
(1620, 104, 'SE', 'Semaria', TRUE),
(1621, 104, 'SH', 'Sharon', TRUE),
(1622, 104, 'TA', 'Tel Aviv (Gosh Dan)', TRUE),
(3860, 105, 'CL', 'Caltanissetta', TRUE),
(3842, 105, 'AG', 'Agrigento', TRUE),
(3843, 105, 'AL', 'Alessandria', TRUE),
(3844, 105, 'AN', 'Ancona', TRUE),
(3845, 105, 'AO', 'Aosta', TRUE),
(3846, 105, 'AR', 'Arezzo', TRUE),
(3847, 105, 'AP', 'Ascoli Piceno', TRUE),
(3848, 105, 'AT', 'Asti', TRUE),
(3849, 105, 'AV', 'Avellino', TRUE),
(3850, 105, 'BA', 'Bari', TRUE),
(3851, 105, 'BL', 'Belluno', TRUE),
(3852, 105, 'BN', 'Benevento', TRUE),
(3853, 105, 'BG', 'Bergamo', TRUE),
(3854, 105, 'BI', 'Biella', TRUE),
(3855, 105, 'BO', 'Bologna', TRUE),
(3856, 105, 'BZ', 'Bolzano', TRUE),
(3857, 105, 'BS', 'Brescia', TRUE),
(3858, 105, 'BR', 'Brindisi', TRUE),
(3859, 105, 'CA', 'Cagliari', TRUE),
(1643, 106, 'CLA', 'Clarendon Parish', TRUE),
(1644, 106, 'HAN', 'Hanover Parish', TRUE),
(1645, 106, 'KIN', 'Kingston Parish', TRUE),
(1646, 106, 'MAN', 'Manchester Parish', TRUE),
(1647, 106, 'POR', 'Portland Parish', TRUE),
(1648, 106, 'AND', 'Saint Andrew Parish', TRUE),
(1649, 106, 'ANN', 'Saint Ann Parish', TRUE),
(1650, 106, 'CAT', 'Saint Catherine Parish', TRUE),
(1651, 106, 'ELI', 'Saint Elizabeth Parish', TRUE),
(1652, 106, 'JAM', 'Saint James Parish', TRUE),
(1653, 106, 'MAR', 'Saint Mary Parish', TRUE),
(1654, 106, 'THO', 'Saint Thomas Parish', TRUE),
(1655, 106, 'TRL', 'Trelawny Parish', TRUE),
(1656, 106, 'WML', 'Westmoreland Parish', TRUE),
(1657, 107, 'AI', 'Aichi', TRUE),
(1658, 107, 'AK', 'Akita', TRUE),
(1659, 107, 'AO', 'Aomori', TRUE),
(1660, 107, 'CH', 'Chiba', TRUE),
(1661, 107, 'EH', 'Ehime', TRUE),
(1662, 107, 'FK', 'Fukui', TRUE),
(1663, 107, 'FU', 'Fukuoka', TRUE),
(1664, 107, 'FS', 'Fukushima', TRUE),
(1665, 107, 'GI', 'Gifu', TRUE),
(1666, 107, 'GU', 'Gumma', TRUE),
(1667, 107, 'HI', 'Hiroshima', TRUE),
(1668, 107, 'HO', 'Hokkaido', TRUE),
(1669, 107, 'HY', 'Hyogo', TRUE),
(1670, 107, 'IB', 'Ibaraki', TRUE),
(1671, 107, 'IS', 'Ishikawa', TRUE),
(1672, 107, 'IW', 'Iwate', TRUE),
(1673, 107, 'KA', 'Kagawa', TRUE),
(1674, 107, 'KG', 'Kagoshima', TRUE),
(1675, 107, 'KN', 'Kanagawa', TRUE),
(1676, 107, 'KO', 'Kochi', TRUE),
(1677, 107, 'KU', 'Kumamoto', TRUE),
(1678, 107, 'KY', 'Kyoto', TRUE),
(1679, 107, 'MI', 'Mie', TRUE),
(1680, 107, 'MY', 'Miyagi', TRUE),
(1681, 107, 'MZ', 'Miyazaki', TRUE),
(1682, 107, 'NA', 'Nagano', TRUE),
(1683, 107, 'NG', 'Nagasaki', TRUE),
(1684, 107, 'NR', 'Nara', TRUE),
(1685, 107, 'NI', 'Niigata', TRUE),
(1686, 107, 'OI', 'Oita', TRUE),
(1687, 107, 'OK', 'Okayama', TRUE),
(1688, 107, 'ON', 'Okinawa', TRUE),
(1689, 107, 'OS', 'Osaka', TRUE),
(1690, 107, 'SA', 'Saga', TRUE),
(1691, 107, 'SI', 'Saitama', TRUE),
(1692, 107, 'SH', 'Shiga', TRUE),
(1693, 107, 'SM', 'Shimane', TRUE),
(1694, 107, 'SZ', 'Shizuoka', TRUE),
(1695, 107, 'TO', 'Tochigi', TRUE),
(1696, 107, 'TS', 'Tokushima', TRUE),
(1697, 107, 'TK', 'Tokyo', TRUE),
(1698, 107, 'TT', 'Tottori', TRUE),
(1699, 107, 'TY', 'Toyama', TRUE),
(1700, 107, 'WA', 'Wakayama', TRUE),
(1701, 107, 'YA', 'Yamagata', TRUE),
(1702, 107, 'YM', 'Yamaguchi', TRUE),
(1703, 107, 'YN', 'Yamanashi', TRUE),
(1704, 108, 'AM', '''Amman', TRUE),
(1705, 108, 'AJ', 'Ajlun', TRUE),
(1706, 108, 'AA', 'Al ''Aqabah', TRUE),
(1707, 108, 'AB', 'Al Balqa''', TRUE),
(1708, 108, 'AK', 'Al Karak', TRUE),
(1709, 108, 'AL', 'Al Mafraq', TRUE),
(1710, 108, 'AT', 'At Tafilah', TRUE),
(1711, 108, 'AZ', 'Az Zarqa''', TRUE),
(1712, 108, 'IR', 'Irbid', TRUE),
(1713, 108, 'JA', 'Jarash', TRUE),
(1714, 108, 'MA', 'Ma''an', TRUE),
(1715, 108, 'MD', 'Madaba', TRUE),
(1716, 109, 'AL', 'Almaty', TRUE),
(1717, 109, 'AC', 'Almaty City', TRUE),
(1718, 109, 'AM', 'Aqmola', TRUE),
(1719, 109, 'AQ', 'Aqtobe', TRUE),
(1720, 109, 'AS', 'Astana City', TRUE),
(1721, 109, 'AT', 'Atyrau', TRUE),
(1722, 109, 'BA', 'Batys Qazaqstan', TRUE),
(1723, 109, 'BY', 'Bayqongyr City', TRUE),
(1724, 109, 'MA', 'Mangghystau', TRUE),
(1725, 109, 'ON', 'Ongtustik Qazaqstan', TRUE),
(1726, 109, 'PA', 'Pavlodar', TRUE),
(1727, 109, 'QA', 'Qaraghandy', TRUE),
(1728, 109, 'QO', 'Qostanay', TRUE),
(1729, 109, 'QY', 'Qyzylorda', TRUE),
(1730, 109, 'SH', 'Shyghys Qazaqstan', TRUE),
(1731, 109, 'SO', 'Soltustik Qazaqstan', TRUE),
(1732, 109, 'ZH', 'Zhambyl', TRUE),
(1733, 110, 'CE', 'Central', TRUE),
(1734, 110, 'CO', 'Coast', TRUE),
(1735, 110, 'EA', 'Eastern', TRUE),
(1736, 110, 'NA', 'Nairobi Area', TRUE),
(1737, 110, 'NE', 'North Eastern', TRUE),
(1738, 110, 'NY', 'Nyanza', TRUE),
(1739, 110, 'RV', 'Rift Valley', TRUE),
(1740, 110, 'WE', 'Western', TRUE),
(1741, 111, 'AG', 'Abaiang', TRUE),
(1742, 111, 'AM', 'Abemama', TRUE),
(1743, 111, 'AK', 'Aranuka', TRUE),
(1744, 111, 'AO', 'Arorae', TRUE),
(1745, 111, 'BA', 'Banaba', TRUE),
(1746, 111, 'BE', 'Beru', TRUE),
(1747, 111, 'bT', 'Butaritari', TRUE),
(1748, 111, 'KA', 'Kanton', TRUE),
(1749, 111, 'KR', 'Kiritimati', TRUE),
(1750, 111, 'KU', 'Kuria', TRUE),
(1751, 111, 'MI', 'Maiana', TRUE),
(1752, 111, 'MN', 'Makin', TRUE),
(1753, 111, 'ME', 'Marakei', TRUE),
(1754, 111, 'NI', 'Nikunau', TRUE),
(1755, 111, 'NO', 'Nonouti', TRUE),
(1756, 111, 'ON', 'Onotoa', TRUE),
(1757, 111, 'TT', 'Tabiteuea', TRUE),
(1758, 111, 'TR', 'Tabuaeran', TRUE),
(1759, 111, 'TM', 'Tamana', TRUE),
(1760, 111, 'TW', 'Tarawa', TRUE),
(1761, 111, 'TE', 'Teraina', TRUE),
(1762, 112, 'CHA', 'Chagang-do', TRUE),
(1763, 112, 'HAB', 'Hamgyong-bukto', TRUE),
(1764, 112, 'HAN', 'Hamgyong-namdo', TRUE),
(1765, 112, 'HWB', 'Hwanghae-bukto', TRUE),
(1766, 112, 'HWN', 'Hwanghae-namdo', TRUE),
(1767, 112, 'KAN', 'Kangwon-do', TRUE),
(1768, 112, 'PYB', 'P''yongan-bukto', TRUE),
(1769, 112, 'PYN', 'P''yongan-namdo', TRUE),
(1770, 112, 'YAN', 'Ryanggang-do (Yanggang-do)', TRUE),
(1771, 112, 'NAJ', 'Rason Directly Governed City', TRUE),
(1772, 112, 'PYO', 'P''yongyang Special City', TRUE),
(1773, 113, 'CO', 'Ch''ungch''ong-bukto', TRUE),
(1774, 113, 'CH', 'Ch''ungch''ong-namdo', TRUE),
(1775, 113, 'CD', 'Cheju-do', TRUE),
(1776, 113, 'CB', 'Cholla-bukto', TRUE),
(1777, 113, 'CN', 'Cholla-namdo', TRUE),
(1778, 113, 'IG', 'Inch''on-gwangyoksi', TRUE),
(1779, 113, 'KA', 'Kangwon-do', TRUE),
(1780, 113, 'KG', 'Kwangju-gwangyoksi', TRUE),
(1781, 113, 'KD', 'Kyonggi-do', TRUE),
(1782, 113, 'KB', 'Kyongsang-bukto', TRUE),
(1783, 113, 'KN', 'Kyongsang-namdo', TRUE),
(1784, 113, 'PG', 'Pusan-gwangyoksi', TRUE),
(1785, 113, 'SO', 'Soul-t''ukpyolsi', TRUE),
(1786, 113, 'TA', 'Taegu-gwangyoksi', TRUE),
(1787, 113, 'TG', 'Taejon-gwangyoksi', TRUE),
(1788, 114, 'AL', 'Al ''Asimah', TRUE),
(1789, 114, 'AA', 'Al Ahmadi', TRUE),
(1790, 114, 'AF', 'Al Farwaniyah', TRUE),
(1791, 114, 'AJ', 'Al Jahra''', TRUE),
(1792, 114, 'HA', 'Hawalli', TRUE),
(1793, 115, 'GB', 'Bishkek', TRUE),
(1794, 115, 'B', 'Batken', TRUE),
(1795, 115, 'C', 'Chu', TRUE),
(1796, 115, 'J', 'Jalal-Abad', TRUE),
(1797, 115, 'N', 'Naryn', TRUE),
(1798, 115, 'O', 'Osh', TRUE),
(1799, 115, 'T', 'Talas', TRUE),
(1800, 115, 'Y', 'Ysyk-Kol', TRUE),
(1801, 116, 'VT', 'Vientiane', TRUE),
(1802, 116, 'AT', 'Attapu', TRUE),
(1803, 116, 'BK', 'Bokeo', TRUE),
(1804, 116, 'BL', 'Bolikhamxai', TRUE),
(1805, 116, 'CH', 'Champasak', TRUE),
(1806, 116, 'HO', 'Houaphan', TRUE),
(1807, 116, 'KH', 'Khammouan', TRUE),
(1808, 116, 'LM', 'Louang Namtha', TRUE),
(1809, 116, 'LP', 'Louangphabang', TRUE),
(1810, 116, 'OU', 'Oudomxai', TRUE),
(1811, 116, 'PH', 'Phongsali', TRUE),
(1812, 116, 'SL', 'Salavan', TRUE),
(1813, 116, 'SV', 'Savannakhet', TRUE),
(1814, 116, 'VI', 'Vientiane', TRUE),
(1815, 116, 'XA', 'Xaignabouli', TRUE),
(1816, 116, 'XE', 'Xekong', TRUE),
(1817, 116, 'XI', 'Xiangkhoang', TRUE),
(1818, 116, 'XN', 'Xaisomboun', TRUE),
(1852, 119, 'BE', 'Berea', TRUE),
(1853, 119, 'BB', 'Butha-Buthe', TRUE),
(1854, 119, 'LE', 'Leribe', TRUE),
(1855, 119, 'MF', 'Mafeteng', TRUE),
(1856, 119, 'MS', 'Maseru', TRUE),
(1857, 119, 'MH', 'Mohale''s Hoek', TRUE),
(1858, 119, 'MK', 'Mokhotlong', TRUE),
(1859, 119, 'QN', 'Qacha''s Nek', TRUE),
(1860, 119, 'QT', 'Quthing', TRUE),
(1861, 119, 'TT', 'Thaba-Tseka', TRUE),
(1862, 120, 'BI', 'Bomi', TRUE),
(1863, 120, 'BG', 'Bong', TRUE),
(1864, 120, 'GB', 'Grand Bassa', TRUE),
(1865, 120, 'CM', 'Grand Cape Mount', TRUE),
(1866, 120, 'GG', 'Grand Gedeh', TRUE),
(1867, 120, 'GK', 'Grand Kru', TRUE),
(1868, 120, 'LO', 'Lofa', TRUE),
(1869, 120, 'MG', 'Margibi', TRUE),
(1870, 120, 'ML', 'Maryland', TRUE),
(1871, 120, 'MS', 'Montserrado', TRUE),
(1872, 120, 'NB', 'Nimba', TRUE),
(1873, 120, 'RC', 'River Cess', TRUE),
(1874, 120, 'SN', 'Sinoe', TRUE),
(1875, 121, 'AJ', 'Ajdabiya', TRUE),
(1876, 121, 'AZ', 'Al ''Aziziyah', TRUE),
(1877, 121, 'FA', 'Al Fatih', TRUE),
(1878, 121, 'JA', 'Al Jabal al Akhdar', TRUE),
(1879, 121, 'JU', 'Al Jufrah', TRUE),
(1880, 121, 'KH', 'Al Khums', TRUE),
(1881, 121, 'KU', 'Al Kufrah', TRUE),
(1882, 121, 'NK', 'An Nuqat al Khams', TRUE),
(1883, 121, 'AS', 'Ash Shati''', TRUE),
(1884, 121, 'AW', 'Awbari', TRUE),
(1885, 121, 'ZA', 'Az Zawiyah', TRUE),
(1886, 121, 'BA', 'Banghazi', TRUE),
(1887, 121, 'DA', 'Darnah', TRUE),
(1888, 121, 'GD', 'Ghadamis', TRUE),
(1889, 121, 'GY', 'Gharyan', TRUE),
(1890, 121, 'MI', 'Misratah', TRUE),
(1891, 121, 'MZ', 'Murzuq', TRUE),
(1892, 121, 'SB', 'Sabha', TRUE),
(1893, 121, 'SW', 'Sawfajjin', TRUE),
(1894, 121, 'SU', 'Surt', TRUE),
(1895, 121, 'TL', 'Tarabulus (Tripoli)', TRUE),
(1896, 121, 'TH', 'Tarhunah', TRUE),
(1897, 121, 'TU', 'Tubruq', TRUE),
(1898, 121, 'YA', 'Yafran', TRUE),
(1899, 121, 'ZL', 'Zlitan', TRUE),
(1900, 122, 'V', 'Vaduz', TRUE),
(1901, 122, 'A', 'Schaan', TRUE),
(1902, 122, 'B', 'Balzers', TRUE),
(1903, 122, 'N', 'Triesen', TRUE),
(1904, 122, 'E', 'Eschen', TRUE),
(1905, 122, 'M', 'Mauren', TRUE),
(1906, 122, 'T', 'Triesenberg', TRUE),
(1907, 122, 'R', 'Ruggell', TRUE),
(1908, 122, 'G', 'Gamprin', TRUE),
(1909, 122, 'L', 'Schellenberg', TRUE),
(1910, 122, 'P', 'Planken', TRUE),
(1911, 123, 'AL', 'Alytus', TRUE),
(1912, 123, 'KA', 'Kaunas', TRUE),
(1913, 123, 'KL', 'Klaipeda', TRUE),
(1914, 123, 'MA', 'Marijampole', TRUE),
(1915, 123, 'PA', 'Panevezys', TRUE),
(1916, 123, 'SI', 'Siauliai', TRUE),
(1917, 123, 'TA', 'Taurage', TRUE),
(1918, 123, 'TE', 'Telsiai', TRUE),
(1919, 123, 'UT', 'Utena', TRUE),
(1920, 123, 'VI', 'Vilnius', TRUE),
(1921, 124, 'DD', 'Diekirch', TRUE),
(1922, 124, 'DC', 'Clervaux', TRUE),
(1923, 124, 'DR', 'Redange', TRUE),
(1924, 124, 'DV', 'Vianden', TRUE),
(1925, 124, 'DW', 'Wiltz', TRUE),
(1926, 124, 'GG', 'Grevenmacher', TRUE),
(1927, 124, 'GE', 'Echternach', TRUE),
(1928, 124, 'GR', 'Remich', TRUE),
(1929, 124, 'LL', 'Luxembourg', TRUE),
(1930, 124, 'LC', 'Capellen', TRUE),
(1931, 124, 'LE', 'Esch-sur-Alzette', TRUE),
(1932, 124, 'LM', 'Mersch', TRUE),
(1933, 125, 'OLF', 'Our Lady Fatima Parish', TRUE),
(1934, 125, 'ANT', 'St. Anthony Parish', TRUE),
(1935, 125, 'LAZ', 'St. Lazarus Parish', TRUE),
(1936, 125, 'CAT', 'Cathedral Parish', TRUE),
(1937, 125, 'LAW', 'St. Lawrence Parish', TRUE),
(1938, 127, 'AN', 'Antananarivo', TRUE),
(1939, 127, 'AS', 'Antsiranana', TRUE),
(1940, 127, 'FN', 'Fianarantsoa', TRUE),
(1941, 127, 'MJ', 'Mahajanga', TRUE),
(1942, 127, 'TM', 'Toamasina', TRUE),
(1943, 127, 'TL', 'Toliara', TRUE),
(1944, 128, 'BLK', 'Balaka', TRUE),
(1945, 128, 'BLT', 'Blantyre', TRUE),
(1946, 128, 'CKW', 'Chikwawa', TRUE),
(1947, 128, 'CRD', 'Chiradzulu', TRUE),
(1948, 128, 'CTP', 'Chitipa', TRUE),
(1949, 128, 'DDZ', 'Dedza', TRUE),
(1950, 128, 'DWA', 'Dowa', TRUE),
(1951, 128, 'KRG', 'Karonga', TRUE),
(1952, 128, 'KSG', 'Kasungu', TRUE),
(1953, 128, 'LKM', 'Likoma', TRUE),
(1954, 128, 'LLG', 'Lilongwe', TRUE),
(1955, 128, 'MCG', 'Machinga', TRUE),
(1956, 128, 'MGC', 'Mangochi', TRUE),
(1957, 128, 'MCH', 'Mchinji', TRUE),
(1958, 128, 'MLJ', 'Mulanje', TRUE),
(1959, 128, 'MWZ', 'Mwanza', TRUE),
(1960, 128, 'MZM', 'Mzimba', TRUE),
(1961, 128, 'NTU', 'Ntcheu', TRUE),
(1962, 128, 'NKB', 'Nkhata Bay', TRUE),
(1963, 128, 'NKH', 'Nkhotakota', TRUE),
(1964, 128, 'NSJ', 'Nsanje', TRUE),
(1965, 128, 'NTI', 'Ntchisi', TRUE),
(1966, 128, 'PHL', 'Phalombe', TRUE),
(1967, 128, 'RMP', 'Rumphi', TRUE),
(1968, 128, 'SLM', 'Salima', TRUE),
(1969, 128, 'THY', 'Thyolo', TRUE),
(1970, 128, 'ZBA', 'Zomba', TRUE),
(1971, 129, 'MY-01', 'Johor', TRUE),
(1972, 129, 'MY-02', 'Kedah', TRUE),
(1973, 129, 'MY-03', 'Kelantan', TRUE),
(1974, 129, 'MY-15', 'Labuan', TRUE),
(1975, 129, 'MY-04', 'Melaka', TRUE),
(1976, 129, 'MY-05', 'Negeri Sembilan', TRUE),
(1977, 129, 'MY-06', 'Pahang', TRUE),
(1978, 129, 'MY-08', 'Perak', TRUE),
(1979, 129, 'MY-09', 'Perlis', TRUE),
(1980, 129, 'MY-07', 'Pulau Pinang', TRUE),
(1981, 129, 'MY-12', 'Sabah', TRUE),
(1982, 129, 'MY-13', 'Sarawak', TRUE),
(1983, 129, 'MY-10', 'Selangor', TRUE),
(1984, 129, 'MY-11', 'Terengganu', TRUE),
(1985, 129, 'MY-14', 'Kuala Lumpur', TRUE),
(4035, 129, 'MY-16', 'Putrajaya', TRUE),
(1986, 130, 'THU', 'Thiladhunmathi Uthuru', TRUE),
(1987, 130, 'THD', 'Thiladhunmathi Dhekunu', TRUE),
(1988, 130, 'MLU', 'Miladhunmadulu Uthuru', TRUE),
(1989, 130, 'MLD', 'Miladhunmadulu Dhekunu', TRUE),
(1990, 130, 'MAU', 'Maalhosmadulu Uthuru', TRUE),
(1991, 130, 'MAD', 'Maalhosmadulu Dhekunu', TRUE),
(1992, 130, 'FAA', 'Faadhippolhu', TRUE),
(1993, 130, 'MAA', 'Male Atoll', TRUE),
(1994, 130, 'AAU', 'Ari Atoll Uthuru', TRUE),
(1995, 130, 'AAD', 'Ari Atoll Dheknu', TRUE),
(1996, 130, 'FEA', 'Felidhe Atoll', TRUE),
(1997, 130, 'MUA', 'Mulaku Atoll', TRUE),
(1998, 130, 'NAU', 'Nilandhe Atoll Uthuru', TRUE),
(1999, 130, 'NAD', 'Nilandhe Atoll Dhekunu', TRUE),
(2000, 130, 'KLH', 'Kolhumadulu', TRUE),
(2001, 130, 'HDH', 'Hadhdhunmathi', TRUE),
(2002, 130, 'HAU', 'Huvadhu Atoll Uthuru', TRUE),
(2003, 130, 'HAD', 'Huvadhu Atoll Dhekunu', TRUE),
(2004, 130, 'FMU', 'Fua Mulaku', TRUE),
(2005, 130, 'ADD', 'Addu', TRUE),
(2006, 131, 'GA', 'Gao', TRUE),
(2007, 131, 'KY', 'Kayes', TRUE),
(2008, 131, 'KD', 'Kidal', TRUE),
(2009, 131, 'KL', 'Koulikoro', TRUE),
(2010, 131, 'MP', 'Mopti', TRUE),
(2011, 131, 'SG', 'Segou', TRUE),
(2012, 131, 'SK', 'Sikasso', TRUE),
(2013, 131, 'TB', 'Tombouctou', TRUE),
(2014, 131, 'CD', 'Bamako Capital District', TRUE),
(2015, 132, 'ATT', 'Attard', TRUE),
(2016, 132, 'BAL', 'Balzan', TRUE),
(2017, 132, 'BGU', 'Birgu', TRUE),
(2018, 132, 'BKK', 'Birkirkara', TRUE),
(2019, 132, 'BRZ', 'Birzebbuga', TRUE),
(2020, 132, 'BOR', 'Bormla', TRUE),
(2021, 132, 'DIN', 'Dingli', TRUE),
(2022, 132, 'FGU', 'Fgura', TRUE),
(2023, 132, 'FLO', 'Floriana', TRUE),
(2024, 132, 'GDJ', 'Gudja', TRUE),
(2025, 132, 'GZR', 'Gzira', TRUE),
(2026, 132, 'GRG', 'Gargur', TRUE),
(2027, 132, 'GXQ', 'Gaxaq', TRUE),
(2028, 132, 'HMR', 'Hamrun', TRUE),
(2029, 132, 'IKL', 'Iklin', TRUE),
(2030, 132, 'ISL', 'Isla', TRUE),
(2031, 132, 'KLK', 'Kalkara', TRUE),
(2032, 132, 'KRK', 'Kirkop', TRUE),
(2033, 132, 'LIJ', 'Lija', TRUE),
(2034, 132, 'LUQ', 'Luqa', TRUE),
(2035, 132, 'MRS', 'Marsa', TRUE),
(2036, 132, 'MKL', 'Marsaskala', TRUE),
(2037, 132, 'MXL', 'Marsaxlokk', TRUE),
(2038, 132, 'MDN', 'Mdina', TRUE),
(2039, 132, 'MEL', 'Melliea', TRUE),
(2040, 132, 'MGR', 'Mgarr', TRUE),
(2041, 132, 'MST', 'Mosta', TRUE),
(2042, 132, 'MQA', 'Mqabba', TRUE),
(2043, 132, 'MSI', 'Msida', TRUE),
(2044, 132, 'MTF', 'Mtarfa', TRUE),
(2045, 132, 'NAX', 'Naxxar', TRUE),
(2046, 132, 'PAO', 'Paola', TRUE),
(2047, 132, 'PEM', 'Pembroke', TRUE),
(2048, 132, 'PIE', 'Pieta', TRUE),
(2049, 132, 'QOR', 'Qormi', TRUE),
(2050, 132, 'QRE', 'Qrendi', TRUE),
(2051, 132, 'RAB', 'Rabat', TRUE),
(2052, 132, 'SAF', 'Safi', TRUE),
(2053, 132, 'SGI', 'San Giljan', TRUE),
(2054, 132, 'SLU', 'Santa Lucija', TRUE),
(2055, 132, 'SPB', 'San Pawl il-Bahar', TRUE),
(2056, 132, 'SGW', 'San Gwann', TRUE),
(2057, 132, 'SVE', 'Santa Venera', TRUE),
(2058, 132, 'SIG', 'Siggiewi', TRUE),
(2059, 132, 'SLM', 'Sliema', TRUE),
(2060, 132, 'SWQ', 'Swieqi', TRUE),
(2061, 132, 'TXB', 'Ta Xbiex', TRUE),
(2062, 132, 'TRX', 'Tarxien', TRUE),
(2063, 132, 'VLT', 'Valletta', TRUE),
(2064, 132, 'XGJ', 'Xgajra', TRUE),
(2065, 132, 'ZBR', 'Zabbar', TRUE),
(2066, 132, 'ZBG', 'Zebbug', TRUE),
(2067, 132, 'ZJT', 'Zejtun', TRUE),
(2068, 132, 'ZRQ', 'Zurrieq', TRUE),
(2069, 132, 'FNT', 'Fontana', TRUE),
(2070, 132, 'GHJ', 'Ghajnsielem', TRUE),
(2071, 132, 'GHR', 'Gharb', TRUE),
(2072, 132, 'GHS', 'Ghasri', TRUE),
(2073, 132, 'KRC', 'Kercem', TRUE),
(2074, 132, 'MUN', 'Munxar', TRUE),
(2075, 132, 'NAD', 'Nadur', TRUE),
(2076, 132, 'QAL', 'Qala', TRUE),
(2077, 132, 'VIC', 'Victoria', TRUE),
(2078, 132, 'SLA', 'San Lawrenz', TRUE),
(2079, 132, 'SNT', 'Sannat', TRUE),
(2080, 132, 'ZAG', 'Xagra', TRUE),
(2081, 132, 'XEW', 'Xewkija', TRUE),
(2082, 132, 'ZEB', 'Zebbug', TRUE),
(2083, 133, 'ALG', 'Ailinginae', TRUE),
(2084, 133, 'ALL', 'Ailinglaplap', TRUE),
(2085, 133, 'ALK', 'Ailuk', TRUE),
(2086, 133, 'ARN', 'Arno', TRUE),
(2087, 133, 'AUR', 'Aur', TRUE),
(2088, 133, 'BKR', 'Bikar', TRUE),
(2089, 133, 'BKN', 'Bikini', TRUE),
(2090, 133, 'BKK', 'Bokak', TRUE),
(2091, 133, 'EBN', 'Ebon', TRUE),
(2092, 133, 'ENT', 'Enewetak', TRUE),
(2093, 133, 'EKB', 'Erikub', TRUE),
(2094, 133, 'JBT', 'Jabat', TRUE),
(2095, 133, 'JLT', 'Jaluit', TRUE),
(2096, 133, 'JEM', 'Jemo', TRUE),
(2097, 133, 'KIL', 'Kili', TRUE),
(2098, 133, 'KWJ', 'Kwajalein', TRUE),
(2099, 133, 'LAE', 'Lae', TRUE),
(2100, 133, 'LIB', 'Lib', TRUE),
(2101, 133, 'LKP', 'Likiep', TRUE),
(2102, 133, 'MJR', 'Majuro', TRUE),
(2103, 133, 'MLP', 'Maloelap', TRUE),
(2104, 133, 'MJT', 'Mejit', TRUE),
(2105, 133, 'MIL', 'Mili', TRUE),
(2106, 133, 'NMK', 'Namorik', TRUE),
(2107, 133, 'NAM', 'Namu', TRUE),
(2108, 133, 'RGL', 'Rongelap', TRUE),
(2109, 133, 'RGK', 'Rongrik', TRUE),
(2110, 133, 'TOK', 'Toke', TRUE),
(2111, 133, 'UJA', 'Ujae', TRUE),
(2112, 133, 'UJL', 'Ujelang', TRUE),
(2113, 133, 'UTK', 'Utirik', TRUE),
(2114, 133, 'WTH', 'Wotho', TRUE),
(2115, 133, 'WTJ', 'Wotje', TRUE),
(2116, 135, 'AD', 'Adrar', TRUE),
(2117, 135, 'AS', 'Assaba', TRUE),
(2118, 135, 'BR', 'Brakna', TRUE),
(2119, 135, 'DN', 'Dakhlet Nouadhibou', TRUE),
(2120, 135, 'GO', 'Gorgol', TRUE),
(2121, 135, 'GM', 'Guidimaka', TRUE),
(2122, 135, 'HC', 'Hodh Ech Chargui', TRUE),
(2123, 135, 'HG', 'Hodh El Gharbi', TRUE),
(2124, 135, 'IN', 'Inchiri', TRUE),
(2125, 135, 'TA', 'Tagant', TRUE),
(2126, 135, 'TZ', 'Tiris Zemmour', TRUE),
(2127, 135, 'TR', 'Trarza', TRUE),
(2128, 135, 'NO', 'Nouakchott', TRUE),
(2129, 136, 'BR', 'Beau Bassin-Rose Hill', TRUE),
(2130, 136, 'CU', 'Curepipe', TRUE),
(2131, 136, 'PU', 'Port Louis', TRUE),
(2132, 136, 'QB', 'Quatre Bornes', TRUE),
(2133, 136, 'VP', 'Vacoas-Phoenix', TRUE),
(2134, 136, 'AG', 'Agalega Islands', TRUE),
(2135, 136, 'CC', 'Cargados Carajos Shoals (Saint Brandon Islands)', TRUE),
(2136, 136, 'RO', 'Rodrigues', TRUE),
(2137, 136, 'BL', 'Black River', TRUE),
(2138, 136, 'FL', 'Flacq', TRUE),
(2139, 136, 'GP', 'Grand Port', TRUE),
(2140, 136, 'MO', 'Moka', TRUE),
(2141, 136, 'PA', 'Pamplemousses', TRUE),
(2142, 136, 'PW', 'Plaines Wilhems', TRUE),
(2143, 136, 'PL', 'Port Louis', TRUE),
(2144, 136, 'RR', 'Riviere du Rempart', TRUE),
(2145, 136, 'SA', 'Savanne', TRUE),
(2146, 138, 'BN', 'Baja California Norte', TRUE),
(2147, 138, 'BS', 'Baja California Sur', TRUE),
(2148, 138, 'CA', 'Campeche', TRUE),
(2149, 138, 'CI', 'Chiapas', TRUE),
(2150, 138, 'CH', 'Chihuahua', TRUE),
(2151, 138, 'CZ', 'Coahuila de Zaragoza', TRUE),
(2152, 138, 'CL', 'Colima', TRUE),
(2153, 138, 'DF', 'Distrito Federal', TRUE),
(2154, 138, 'DU', 'Durango', TRUE),
(2155, 138, 'GA', 'Guanajuato', TRUE),
(2156, 138, 'GE', 'Guerrero', TRUE),
(2157, 138, 'HI', 'Hidalgo', TRUE),
(2158, 138, 'JA', 'Jalisco', TRUE),
(2159, 138, 'ME', 'Mexico', TRUE),
(2160, 138, 'MI', 'Michoacan de Ocampo', TRUE),
(2161, 138, 'MO', 'Morelos', TRUE),
(2162, 138, 'NA', 'Nayarit', TRUE),
(2163, 138, 'NL', 'Nuevo Leon', TRUE),
(2164, 138, 'OA', 'Oaxaca', TRUE),
(2165, 138, 'PU', 'Puebla', TRUE),
(2166, 138, 'QA', 'Queretaro de Arteaga', TRUE),
(2167, 138, 'QR', 'Quintana Roo', TRUE),
(2168, 138, 'SA', 'San Luis Potosi', TRUE),
(2169, 138, 'SI', 'Sinaloa', TRUE),
(2170, 138, 'SO', 'Sonora', TRUE),
(2171, 138, 'TB', 'Tabasco', TRUE),
(2172, 138, 'TM', 'Tamaulipas', TRUE),
(2173, 138, 'TL', 'Tlaxcala', TRUE),
(2174, 138, 'VE', 'Veracruz-Llave', TRUE),
(2175, 138, 'YU', 'Yucatan', TRUE),
(2176, 138, 'ZA', 'Zacatecas', TRUE),
(2177, 139, 'C', 'Chuuk', TRUE),
(2178, 139, 'K', 'Kosrae', TRUE),
(2179, 139, 'P', 'Pohnpei', TRUE),
(2180, 139, 'Y', 'Yap', TRUE),
(2181, 140, 'GA', 'Gagauzia', TRUE),
(2182, 140, 'CU', 'Chisinau', TRUE),
(2183, 140, 'BA', 'Balti', TRUE),
(2184, 140, 'CA', 'Cahul', TRUE),
(2185, 140, 'ED', 'Edinet', TRUE),
(2186, 140, 'LA', 'Lapusna', TRUE),
(2187, 140, 'OR', 'Orhei', TRUE),
(2188, 140, 'SO', 'Soroca', TRUE),
(2189, 140, 'TI', 'Tighina', TRUE),
(2190, 140, 'UN', 'Ungheni', TRUE),
(2191, 140, 'SN', 'St‚nga Nistrului', TRUE),
(2192, 141, 'FV', 'Fontvieille', TRUE),
(2193, 141, 'LC', 'La Condamine', TRUE),
(2194, 141, 'MV', 'Monaco-Ville', TRUE),
(2195, 141, 'MC', 'Monte-Carlo', TRUE),
(2196, 142, '1', 'Ulanbaatar', TRUE),
(2197, 142, '035', 'Orhon', TRUE),
(2198, 142, '037', 'Darhan uul', TRUE),
(2199, 142, '039', 'Hentiy', TRUE),
(2200, 142, '041', 'Hovsgol', TRUE),
(2201, 142, '043', 'Hovd', TRUE),
(2202, 142, '046', 'Uvs', TRUE),
(2203, 142, '047', 'Tov', TRUE),
(2204, 142, '049', 'Selenge', TRUE),
(2205, 142, '051', 'Suhbaatar', TRUE),
(2206, 142, '053', 'Omnogovi', TRUE),
(2207, 142, '055', 'Ovorhangay', TRUE),
(2208, 142, '057', 'Dzavhan', TRUE),
(2209, 142, '059', 'DundgovL', TRUE),
(2210, 142, '061', 'Dornod', TRUE),
(2211, 142, '063', 'Dornogov', TRUE),
(2212, 142, '064', 'Govi-Sumber', TRUE),
(2213, 142, '065', 'Govi-Altay', TRUE),
(2214, 142, '067', 'Bulgan', TRUE),
(2215, 142, '069', 'Bayanhongor', TRUE),
(2216, 142, '071', 'Bayan-Olgiy', TRUE),
(2217, 142, '073', 'Arhangay', TRUE),
(2218, 143, 'A', 'Saint Anthony', TRUE),
(2219, 143, 'G', 'Saint Georges', TRUE),
(2220, 143, 'P', 'Saint Peter', TRUE),
(2221, 144, 'AGD', 'Agadir', TRUE),
(2222, 144, 'HOC', 'Al Hoceima', TRUE),
(2223, 144, 'AZI', 'Azilal', TRUE),
(2224, 144, 'BME', 'Beni Mellal', TRUE),
(2225, 144, 'BSL', 'Ben Slimane', TRUE),
(2226, 144, 'BLM', 'Boulemane', TRUE),
(2227, 144, 'CBL', 'Casablanca', TRUE),
(2228, 144, 'CHA', 'Chaouen', TRUE),
(2229, 144, 'EJA', 'El Jadida', TRUE),
(2230, 144, 'EKS', 'El Kelaa des Sraghna', TRUE),
(2231, 144, 'ERA', 'Er Rachidia', TRUE),
(2232, 144, 'ESS', 'Essaouira', TRUE),
(2233, 144, 'FES', 'Fes', TRUE),
(2234, 144, 'FIG', 'Figuig', TRUE),
(2235, 144, 'GLM', 'Guelmim', TRUE),
(2236, 144, 'IFR', 'Ifrane', TRUE),
(2237, 144, 'KEN', 'Kenitra', TRUE),
(2238, 144, 'KHM', 'Khemisset', TRUE),
(2239, 144, 'KHN', 'Khenifra', TRUE),
(2240, 144, 'KHO', 'Khouribga', TRUE),
(2241, 144, 'LYN', 'Laayoune', TRUE),
(2242, 144, 'LAR', 'Larache', TRUE),
(2243, 144, 'MRK', 'Marrakech', TRUE),
(2244, 144, 'MKN', 'Meknes', TRUE),
(2245, 144, 'NAD', 'Nador', TRUE),
(2246, 144, 'ORZ', 'Ouarzazate', TRUE),
(2247, 144, 'OUJ', 'Oujda', TRUE),
(2248, 144, 'RSA', 'Rabat-Sale', TRUE),
(2249, 144, 'SAF', 'Safi', TRUE),
(2250, 144, 'SET', 'Settat', TRUE),
(2251, 144, 'SKA', 'Sidi Kacem', TRUE),
(2252, 144, 'TGR', 'Tangier', TRUE),
(2253, 144, 'TAN', 'Tan-Tan', TRUE),
(2254, 144, 'TAO', 'Taounate', TRUE),
(2255, 144, 'TRD', 'Taroudannt', TRUE),
(2256, 144, 'TAT', 'Tata', TRUE),
(2257, 144, 'TAZ', 'Taza', TRUE),
(2258, 144, 'TET', 'Tetouan', TRUE),
(2259, 144, 'TIZ', 'Tiznit', TRUE),
(2260, 144, 'ADK', 'Ad Dakhla', TRUE),
(2261, 144, 'BJD', 'Boujdour', TRUE),
(2262, 144, 'ESM', 'Es Smara', TRUE),
(2263, 145, 'CD', 'Cabo Delgado', TRUE),
(2264, 145, 'GZ', 'Gaza', TRUE),
(2265, 145, 'IN', 'Inhambane', TRUE),
(2266, 145, 'MN', 'Manica', TRUE),
(2267, 145, 'MC', 'Maputo (city)', TRUE),
(2268, 145, 'MP', 'Maputo', TRUE),
(2269, 145, 'NA', 'Nampula', TRUE),
(2270, 145, 'NI', 'Niassa', TRUE),
(2271, 145, 'SO', 'Sofala', TRUE),
(2272, 145, 'TE', 'Tete', TRUE),
(2273, 145, 'ZA', 'Zambezia', TRUE),
(2274, 146, 'AY', 'Ayeyarwady', TRUE),
(2275, 146, 'BG', 'Bago', TRUE),
(2276, 146, 'MG', 'Magway', TRUE),
(2277, 146, 'MD', 'Mandalay', TRUE),
(2278, 146, 'SG', 'Sagaing', TRUE),
(2279, 146, 'TN', 'Tanintharyi', TRUE),
(2280, 146, 'YG', 'Yangon', TRUE),
(2281, 146, 'CH', 'Chin State', TRUE),
(2282, 146, 'KC', 'Kachin State', TRUE),
(2283, 146, 'KH', 'Kayah State', TRUE),
(2284, 146, 'KN', 'Kayin State', TRUE),
(2285, 146, 'MN', 'Mon State', TRUE),
(2286, 146, 'RK', 'Rakhine State', TRUE),
(2287, 146, 'SH', 'Shan State', TRUE),
(2288, 147, 'CA', 'Caprivi', TRUE),
(2289, 147, 'ER', 'Erongo', TRUE),
(2290, 147, 'HA', 'Hardap', TRUE),
(2291, 147, 'KR', 'Karas', TRUE),
(2292, 147, 'KV', 'Kavango', TRUE),
(2293, 147, 'KH', 'Khomas', TRUE),
(2294, 147, 'KU', 'Kunene', TRUE),
(2295, 147, 'OW', 'Ohangwena', TRUE),
(2296, 147, 'OK', 'Omaheke', TRUE),
(2297, 147, 'OT', 'Omusati', TRUE),
(2298, 147, 'ON', 'Oshana', TRUE),
(2299, 147, 'OO', 'Oshikoto', TRUE),
(2300, 147, 'OJ', 'Otjozondjupa', TRUE),
(2301, 148, 'AO', 'Aiwo', TRUE),
(2302, 148, 'AA', 'Anabar', TRUE),
(2303, 148, 'AT', 'Anetan', TRUE),
(2304, 148, 'AI', 'Anibare', TRUE),
(2305, 148, 'BA', 'Baiti', TRUE),
(2306, 148, 'BO', 'Boe', TRUE),
(2307, 148, 'BU', 'Buada', TRUE),
(2308, 148, 'DE', 'Denigomodu', TRUE),
(2309, 148, 'EW', 'Ewa', TRUE),
(2310, 148, 'IJ', 'Ijuw', TRUE),
(2311, 148, 'ME', 'Meneng', TRUE),
(2312, 148, 'NI', 'Nibok', TRUE),
(2313, 148, 'UA', 'Uaboe', TRUE),
(2314, 148, 'YA', 'Yaren', TRUE),
(2315, 149, 'BA', 'Bagmati', TRUE),
(2316, 149, 'BH', 'Bheri', TRUE),
(2317, 149, 'DH', 'Dhawalagiri', TRUE),
(2318, 149, 'GA', 'Gandaki', TRUE),
(2319, 149, 'JA', 'Janakpur', TRUE),
(2320, 149, 'KA', 'Karnali', TRUE),
(2321, 149, 'KO', 'Kosi', TRUE),
(2322, 149, 'LU', 'Lumbini', TRUE),
(2323, 149, 'MA', 'Mahakali', TRUE),
(2324, 149, 'ME', 'Mechi', TRUE),
(2325, 149, 'NA', 'Narayani', TRUE),
(2326, 149, 'RA', 'Rapti', TRUE),
(2327, 149, 'SA', 'Sagarmatha', TRUE),
(2328, 149, 'SE', 'Seti', TRUE),
(2329, 150, 'DR', 'Drenthe', TRUE),
(2330, 150, 'FL', 'Flevoland', TRUE),
(2331, 150, 'FR', 'Friesland', TRUE),
(2332, 150, 'GE', 'Gelderland', TRUE),
(2333, 150, 'GR', 'Groningen', TRUE),
(2334, 150, 'LI', 'Limburg', TRUE),
(2335, 150, 'NB', 'Noord Brabant', TRUE),
(2336, 150, 'NH', 'Noord Holland', TRUE),
(2337, 150, 'OV', 'Overijssel', TRUE),
(2338, 150, 'UT', 'Utrecht', TRUE),
(2339, 150, 'ZE', 'Zeeland', TRUE),
(2340, 150, 'ZH', 'Zuid Holland', TRUE),
(2341, 152, 'L', 'Iles Loyaute', TRUE),
(2342, 152, 'N', 'Nord', TRUE),
(2343, 152, 'S', 'Sud', TRUE),
(2344, 153, 'AUK', 'Auckland', TRUE),
(2345, 153, 'BOP', 'Bay of Plenty', TRUE),
(2346, 153, 'CAN', 'Canterbury', TRUE),
(2347, 153, 'COR', 'Coromandel', TRUE),
(2348, 153, 'GIS', 'Gisborne', TRUE),
(2349, 153, 'FIO', 'Fiordland', TRUE),
(2350, 153, 'HKB', 'Hawke''s Bay', TRUE),
(2351, 153, 'MBH', 'Marlborough', TRUE),
(2352, 153, 'MWT', 'Manawatu-Wanganui', TRUE),
(2353, 153, 'MCM', 'Mt Cook-Mackenzie', TRUE),
(2354, 153, 'NSN', 'Nelson', TRUE),
(2355, 153, 'NTL', 'Northland', TRUE),
(2356, 153, 'OTA', 'Otago', TRUE),
(2357, 153, 'STL', 'Southland', TRUE),
(2358, 153, 'TKI', 'Taranaki', TRUE),
(2359, 153, 'WGN', 'Wellington', TRUE),
(2360, 153, 'WKO', 'Waikato', TRUE),
(2361, 153, 'WAI', 'Wairarapa', TRUE),
(2362, 153, 'WTC', 'West Coast', TRUE),
(2363, 154, 'AN', 'Atlantico Norte', TRUE),
(2364, 154, 'AS', 'Atlantico Sur', TRUE),
(2365, 154, 'BO', 'Boaco', TRUE),
(2366, 154, 'CA', 'Carazo', TRUE),
(2367, 154, 'CI', 'Chinandega', TRUE),
(2368, 154, 'CO', 'Chontales', TRUE),
(2369, 154, 'ES', 'Esteli', TRUE),
(2370, 154, 'GR', 'Granada', TRUE),
(2371, 154, 'JI', 'Jinotega', TRUE),
(2372, 154, 'LE', 'Leon', TRUE),
(2373, 154, 'MD', 'Madriz', TRUE),
(2374, 154, 'MN', 'Managua', TRUE),
(2375, 154, 'MS', 'Masaya', TRUE),
(2376, 154, 'MT', 'Matagalpa', TRUE),
(2377, 154, 'NS', 'Nuevo Segovia', TRUE),
(2378, 154, 'RS', 'Rio San Juan', TRUE),
(2379, 154, 'RI', 'Rivas', TRUE),
(2380, 155, 'AG', 'Agadez', TRUE),
(2381, 155, 'DF', 'Diffa', TRUE),
(2382, 155, 'DS', 'Dosso', TRUE),
(2383, 155, 'MA', 'Maradi', TRUE),
(2384, 155, 'NM', 'Niamey', TRUE),
(2385, 155, 'TH', 'Tahoua', TRUE),
(2386, 155, 'TL', 'Tillaberi', TRUE),
(2387, 155, 'ZD', 'Zinder', TRUE),
(2388, 156, 'AB', 'Abia', TRUE),
(2389, 156, 'CT', 'Abuja Federal Capital Territory', TRUE),
(2390, 156, 'AD', 'Adamawa', TRUE),
(2391, 156, 'AK', 'Akwa Ibom', TRUE),
(2392, 156, 'AN', 'Anambra', TRUE),
(2393, 156, 'BC', 'Bauchi', TRUE),
(2394, 156, 'BY', 'Bayelsa', TRUE),
(2395, 156, 'BN', 'Benue', TRUE),
(2396, 156, 'BO', 'Borno', TRUE),
(2397, 156, 'CR', 'Cross River', TRUE),
(2398, 156, 'DE', 'Delta', TRUE),
(2399, 156, 'EB', 'Ebonyi', TRUE),
(2400, 156, 'ED', 'Edo', TRUE),
(2401, 156, 'EK', 'Ekiti', TRUE),
(2402, 156, 'EN', 'Enugu', TRUE),
(2403, 156, 'GO', 'Gombe', TRUE),
(2404, 156, 'IM', 'Imo', TRUE),
(2405, 156, 'JI', 'Jigawa', TRUE),
(2406, 156, 'KD', 'Kaduna', TRUE),
(2407, 156, 'KN', 'Kano', TRUE),
(2408, 156, 'KT', 'Katsina', TRUE),
(2409, 156, 'KE', 'Kebbi', TRUE),
(2410, 156, 'KO', 'Kogi', TRUE),
(2411, 156, 'KW', 'Kwara', TRUE),
(2412, 156, 'LA', 'Lagos', TRUE),
(2413, 156, 'NA', 'Nassarawa', TRUE),
(2414, 156, 'NI', 'Niger', TRUE),
(2415, 156, 'OG', 'Ogun', TRUE),
(2416, 156, 'ONG', 'Ondo', TRUE),
(2417, 156, 'OS', 'Osun', TRUE),
(2418, 156, 'OY', 'Oyo', TRUE),
(2419, 156, 'PL', 'Plateau', TRUE),
(2420, 156, 'RI', 'Rivers', TRUE),
(2421, 156, 'SO', 'Sokoto', TRUE),
(2422, 156, 'TA', 'Taraba', TRUE),
(2423, 156, 'YO', 'Yobe', TRUE),
(2424, 156, 'ZA', 'Zamfara', TRUE),
(2425, 159, 'N', 'Northern Islands', TRUE),
(2426, 159, 'R', 'Rota', TRUE),
(2427, 159, 'S', 'Saipan', TRUE),
(2428, 159, 'T', 'Tinian', TRUE),
(2429, 160, 'AK', 'Akershus', TRUE),
(2430, 160, 'AA', 'Aust-Agder', TRUE),
(2431, 160, 'BU', 'Buskerud', TRUE),
(2432, 160, 'FM', 'Finnmark', TRUE),
(2433, 160, 'HM', 'Hedmark', TRUE),
(2434, 160, 'HL', 'Hordaland', TRUE),
(2435, 160, 'MR', 'More og Romdal', TRUE),
(2436, 160, 'NT', 'Nord-Trondelag', TRUE),
(2437, 160, 'NL', 'Nordland', TRUE),
(2438, 160, 'OF', 'Ostfold', TRUE),
(2439, 160, 'OP', 'Oppland', TRUE),
(2440, 160, 'OL', 'Oslo', TRUE),
(2441, 160, 'RL', 'Rogaland', TRUE),
(2442, 160, 'ST', 'Sor-Trondelag', TRUE),
(2443, 160, 'SJ', 'Sogn og Fjordane', TRUE),
(2444, 160, 'SV', 'Svalbard', TRUE),
(2445, 160, 'TM', 'Telemark', TRUE),
(2446, 160, 'TR', 'Troms', TRUE),
(2447, 160, 'VA', 'Vest-Agder', TRUE),
(2448, 160, 'VF', 'Vestfold', TRUE),
(2449, 161, 'DA', 'Ad Dakhiliyah', TRUE),
(2450, 161, 'BA', 'Al Batinah', TRUE),
(2451, 161, 'WU', 'Al Wusta', TRUE),
(2452, 161, 'SH', 'Ash Sharqiyah', TRUE),
(2453, 161, 'ZA', 'Az Zahirah', TRUE),
(2454, 161, 'MA', 'Masqat', TRUE),
(2455, 161, 'MU', 'Musandam', TRUE),
(2456, 161, 'ZU', 'Zufar', TRUE),
(2457, 162, 'B', 'Balochistan', TRUE),
(2458, 162, 'T', 'Federally Administered Tribal Areas', TRUE),
(2459, 162, 'I', 'Islamabad Capital Territory', TRUE),
(2460, 162, 'N', 'North-West Frontier', TRUE),
(2461, 162, 'P', 'Punjab', TRUE),
(2462, 162, 'S', 'Sindh', TRUE),
(2463, 163, 'AM', 'Aimeliik', TRUE),
(2464, 163, 'AR', 'Airai', TRUE),
(2465, 163, 'AN', 'Angaur', TRUE),
(2466, 163, 'HA', 'Hatohobei', TRUE),
(2467, 163, 'KA', 'Kayangel', TRUE),
(2468, 163, 'KO', 'Koror', TRUE),
(2469, 163, 'ME', 'Melekeok', TRUE),
(2470, 163, 'NA', 'Ngaraard', TRUE),
(2471, 163, 'NG', 'Ngarchelong', TRUE),
(2472, 163, 'ND', 'Ngardmau', TRUE),
(2473, 163, 'NT', 'Ngatpang', TRUE),
(2474, 163, 'NC', 'Ngchesar', TRUE),
(2475, 163, 'NR', 'Ngeremlengui', TRUE),
(2476, 163, 'NW', 'Ngiwal', TRUE),
(2477, 163, 'PE', 'Peleliu', TRUE),
(2478, 163, 'SO', 'Sonsorol', TRUE),
(2479, 164, 'BT', 'Bocas del Toro', TRUE),
(2480, 164, 'CH', 'Chiriqui', TRUE),
(2481, 164, 'CC', 'Cocle', TRUE),
(2482, 164, 'CL', 'Colon', TRUE),
(2483, 164, 'DA', 'Darien', TRUE),
(2484, 164, 'HE', 'Herrera', TRUE),
(2485, 164, 'LS', 'Los Santos', TRUE),
(2486, 164, 'PA', 'Panama', TRUE),
(2487, 164, 'SB', 'San Blas', TRUE),
(2488, 164, 'VG', 'Veraguas', TRUE),
(2489, 165, 'BV', 'Bougainville', TRUE),
(2490, 165, 'CE', 'Central', TRUE),
(2491, 165, 'CH', 'Chimbu', TRUE),
(2492, 165, 'EH', 'Eastern Highlands', TRUE),
(2493, 165, 'EB', 'East New Britain', TRUE),
(2494, 165, 'ES', 'East Sepik', TRUE),
(2495, 165, 'EN', 'Enga', TRUE),
(2496, 165, 'GU', 'Gulf', TRUE),
(2497, 165, 'MD', 'Madang', TRUE),
(2498, 165, 'MN', 'Manus', TRUE),
(2499, 165, 'MB', 'Milne Bay', TRUE),
(2500, 165, 'MR', 'Morobe', TRUE),
(2501, 165, 'NC', 'National Capital', TRUE),
(2502, 165, 'NI', 'New Ireland', TRUE),
(2503, 165, 'NO', 'Northern', TRUE),
(2504, 165, 'SA', 'Sandaun', TRUE),
(2505, 165, 'SH', 'Southern Highlands', TRUE),
(2506, 165, 'WE', 'Western', TRUE),
(2507, 165, 'WH', 'Western Highlands', TRUE),
(2508, 165, 'WB', 'West New Britain', TRUE),
(2509, 166, 'AG', 'Alto Paraguay', TRUE),
(2510, 166, 'AN', 'Alto Parana', TRUE),
(2511, 166, 'AM', 'Amambay', TRUE),
(2512, 166, 'AS', 'Asuncion', TRUE),
(2513, 166, 'BO', 'Boqueron', TRUE),
(2514, 166, 'CG', 'Caaguazu', TRUE),
(2515, 166, 'CZ', 'Caazapa', TRUE),
(2516, 166, 'CN', 'Canindeyu', TRUE),
(2517, 166, 'CE', 'Central', TRUE),
(2518, 166, 'CC', 'Concepcion', TRUE),
(2519, 166, 'CD', 'Cordillera', TRUE),
(2520, 166, 'GU', 'Guaira', TRUE),
(2521, 166, 'IT', 'Itapua', TRUE),
(2522, 166, 'MI', 'Misiones', TRUE),
(2523, 166, 'NE', 'Neembucu', TRUE),
(2524, 166, 'PA', 'Paraguari', TRUE),
(2525, 166, 'PH', 'Presidente Hayes', TRUE),
(2526, 166, 'SP', 'San Pedro', TRUE),
(2527, 167, 'AM', 'Amazonas', TRUE),
(2528, 167, 'AN', 'Ancash', TRUE),
(2529, 167, 'AP', 'Apurimac', TRUE),
(2530, 167, 'AR', 'Arequipa', TRUE),
(2531, 167, 'AY', 'Ayacucho', TRUE),
(2532, 167, 'CJ', 'Cajamarca', TRUE),
(2533, 167, 'CL', 'Callao', TRUE),
(2534, 167, 'CU', 'Cusco', TRUE),
(2535, 167, 'HV', 'Huancavelica', TRUE),
(2536, 167, 'HO', 'Huanuco', TRUE),
(2537, 167, 'IC', 'Ica', TRUE),
(2538, 167, 'JU', 'Junin', TRUE),
(2539, 167, 'LD', 'La Libertad', TRUE),
(2540, 167, 'LY', 'Lambayeque', TRUE),
(2541, 167, 'LI', 'Lima', TRUE),
(2542, 167, 'LO', 'Loreto', TRUE),
(2543, 167, 'MD', 'Madre de Dios', TRUE),
(2544, 167, 'MO', 'Moquegua', TRUE),
(2545, 167, 'PA', 'Pasco', TRUE),
(2546, 167, 'PI', 'Piura', TRUE),
(2547, 167, 'PU', 'Puno', TRUE),
(2548, 167, 'SM', 'San Martin', TRUE),
(2549, 167, 'TA', 'Tacna', TRUE),
(2550, 167, 'TU', 'Tumbes', TRUE),
(2551, 167, 'UC', 'Ucayali', TRUE),
(2552, 168, 'ABR', 'Abra', TRUE),
(2553, 168, 'ANO', 'Agusan del Norte', TRUE),
(2554, 168, 'ASU', 'Agusan del Sur', TRUE),
(2555, 168, 'AKL', 'Aklan', TRUE),
(2556, 168, 'ALB', 'Albay', TRUE),
(2557, 168, 'ANT', 'Antique', TRUE),
(2558, 168, 'APY', 'Apayao', TRUE),
(2559, 168, 'AUR', 'Aurora', TRUE),
(2560, 168, 'BAS', 'Basilan', TRUE),
(2561, 168, 'BTA', 'Bataan', TRUE),
(2562, 168, 'BTE', 'Batanes', TRUE),
(2563, 168, 'BTG', 'Batangas', TRUE),
(2564, 168, 'BLR', 'Biliran', TRUE),
(2565, 168, 'BEN', 'Benguet', TRUE),
(2566, 168, 'BOL', 'Bohol', TRUE),
(2567, 168, 'BUK', 'Bukidnon', TRUE),
(2568, 168, 'BUL', 'Bulacan', TRUE),
(2569, 168, 'CAG', 'Cagayan', TRUE),
(2570, 168, 'CNO', 'Camarines Norte', TRUE),
(2571, 168, 'CSU', 'Camarines Sur', TRUE),
(2572, 168, 'CAM', 'Camiguin', TRUE),
(2573, 168, 'CAP', 'Capiz', TRUE),
(2574, 168, 'CAT', 'Catanduanes', TRUE),
(2575, 168, 'CAV', 'Cavite', TRUE),
(2576, 168, 'CEB', 'Cebu', TRUE),
(2577, 168, 'CMP', 'Compostela', TRUE),
(2578, 168, 'DNO', 'Davao del Norte', TRUE),
(2579, 168, 'DSU', 'Davao del Sur', TRUE),
(2580, 168, 'DOR', 'Davao Oriental', TRUE),
(2581, 168, 'ESA', 'Eastern Samar', TRUE),
(2582, 168, 'GUI', 'Guimaras', TRUE),
(2583, 168, 'IFU', 'Ifugao', TRUE),
(2584, 168, 'INO', 'Ilocos Norte', TRUE),
(2585, 168, 'ISU', 'Ilocos Sur', TRUE),
(2586, 168, 'ILO', 'Iloilo', TRUE),
(2587, 168, 'ISA', 'Isabela', TRUE),
(2588, 168, 'KAL', 'Kalinga', TRUE),
(2589, 168, 'LAG', 'Laguna', TRUE),
(2590, 168, 'LNO', 'Lanao del Norte', TRUE),
(2591, 168, 'LSU', 'Lanao del Sur', TRUE),
(2592, 168, 'UNI', 'La Union', TRUE),
(2593, 168, 'LEY', 'Leyte', TRUE),
(2594, 168, 'MAG', 'Maguindanao', TRUE),
(2595, 168, 'MRN', 'Marinduque', TRUE),
(2596, 168, 'MSB', 'Masbate', TRUE),
(2597, 168, 'MIC', 'Mindoro Occidental', TRUE),
(2598, 168, 'MIR', 'Mindoro Oriental', TRUE),
(2599, 168, 'MSC', 'Misamis Occidental', TRUE),
(2600, 168, 'MOR', 'Misamis Oriental', TRUE),
(2601, 168, 'MOP', 'Mountain', TRUE),
(2602, 168, 'NOC', 'Negros Occidental', TRUE),
(2603, 168, 'NOR', 'Negros Oriental', TRUE),
(2604, 168, 'NCT', 'North Cotabato', TRUE),
(2605, 168, 'NSM', 'Northern Samar', TRUE),
(2606, 168, 'NEC', 'Nueva Ecija', TRUE),
(2607, 168, 'NVZ', 'Nueva Vizcaya', TRUE),
(2608, 168, 'PLW', 'Palawan', TRUE),
(2609, 168, 'PMP', 'Pampanga', TRUE),
(2610, 168, 'PNG', 'Pangasinan', TRUE),
(2611, 168, 'QZN', 'Quezon', TRUE),
(2612, 168, 'QRN', 'Quirino', TRUE),
(2613, 168, 'RIZ', 'Rizal', TRUE),
(2614, 168, 'ROM', 'Romblon', TRUE),
(2615, 168, 'SMR', 'Samar', TRUE),
(2616, 168, 'SRG', 'Sarangani', TRUE),
(2617, 168, 'SQJ', 'Siquijor', TRUE),
(2618, 168, 'SRS', 'Sorsogon', TRUE),
(2619, 168, 'SCO', 'South Cotabato', TRUE),
(2620, 168, 'SLE', 'Southern Leyte', TRUE),
(2621, 168, 'SKU', 'Sultan Kudarat', TRUE),
(2622, 168, 'SLU', 'Sulu', TRUE),
(2623, 168, 'SNO', 'Surigao del Norte', TRUE),
(2624, 168, 'SSU', 'Surigao del Sur', TRUE),
(2625, 168, 'TAR', 'Tarlac', TRUE),
(2626, 168, 'TAW', 'Tawi-Tawi', TRUE),
(2627, 168, 'ZBL', 'Zambales', TRUE),
(2628, 168, 'ZNO', 'Zamboanga del Norte', TRUE),
(2629, 168, 'ZSU', 'Zamboanga del Sur', TRUE),
(2630, 168, 'ZSI', 'Zamboanga Sibugay', TRUE),
(2631, 170, 'DO', 'Dolnoslaskie', TRUE),
(2632, 170, 'KP', 'Kujawsko-Pomorskie', TRUE),
(2633, 170, 'LO', 'Lodzkie', TRUE),
(2634, 170, 'LL', 'Lubelskie', TRUE),
(2635, 170, 'LU', 'Lubuskie', TRUE),
(2636, 170, 'ML', 'Malopolskie', TRUE),
(2637, 170, 'MZ', 'Mazowieckie', TRUE),
(2638, 170, 'OP', 'Opolskie', TRUE),
(2639, 170, 'PP', 'Podkarpackie', TRUE),
(2640, 170, 'PL', 'Podlaskie', TRUE),
(2641, 170, 'PM', 'Pomorskie', TRUE),
(2642, 170, 'SL', 'Slaskie', TRUE),
(2643, 170, 'SW', 'Swietokrzyskie', TRUE),
(2644, 170, 'WM', 'Warminsko-Mazurskie', TRUE),
(2645, 170, 'WP', 'Wielkopolskie', TRUE),
(2646, 170, 'ZA', 'Zachodniopomorskie', TRUE),
(2647, 198, 'P', 'Saint Pierre', TRUE),
(2648, 198, 'M', 'Miquelon', TRUE),
(2649, 171, 'AC', 'A&ccedil;ores', TRUE),
(2650, 171, 'AV', 'Aveiro', TRUE),
(2651, 171, 'BE', 'Beja', TRUE),
(2652, 171, 'BR', 'Braga', TRUE),
(2653, 171, 'BA', 'Bragan&ccedil;a', TRUE),
(2654, 171, 'CB', 'Castelo Branco', TRUE),
(2655, 171, 'CO', 'Coimbra', TRUE),
(2656, 171, 'EV', '&Eacute;vora', TRUE),
(2657, 171, 'FA', 'Faro', TRUE),
(2658, 171, 'GU', 'Guarda', TRUE),
(2659, 171, 'LE', 'Leiria', TRUE),
(2660, 171, 'LI', 'Lisboa', TRUE),
(2661, 171, 'ME', 'Madeira', TRUE),
(2662, 171, 'PO', 'Portalegre', TRUE),
(2663, 171, 'PR', 'Porto', TRUE),
(2664, 171, 'SA', 'Santar&eacute;m', TRUE),
(2665, 171, 'SE', 'Set&uacute;bal', TRUE),
(2666, 171, 'VC', 'Viana do Castelo', TRUE),
(2667, 171, 'VR', 'Vila Real', TRUE),
(2668, 171, 'VI', 'Viseu', TRUE),
(2669, 173, 'DW', 'Ad Dawhah', TRUE),
(2670, 173, 'GW', 'Al Ghuwayriyah', TRUE),
(2671, 173, 'JM', 'Al Jumayliyah', TRUE),
(2672, 173, 'KR', 'Al Khawr', TRUE),
(2673, 173, 'WK', 'Al Wakrah', TRUE),
(2674, 173, 'RN', 'Ar Rayyan', TRUE),
(2675, 173, 'JB', 'Jarayan al Batinah', TRUE),
(2676, 173, 'MS', 'Madinat ash Shamal', TRUE),
(2677, 173, 'UD', 'Umm Sa''id', TRUE),
(2678, 173, 'UL', 'Umm Salal', TRUE),
(2679, 175, 'AB', 'Alba', TRUE),
(2680, 175, 'AR', 'Arad', TRUE),
(2681, 175, 'AG', 'Arges', TRUE),
(2682, 175, 'BC', 'Bacau', TRUE),
(2683, 175, 'BH', 'Bihor', TRUE),
(2684, 175, 'BN', 'Bistrita-Nasaud', TRUE),
(2685, 175, 'BT', 'Botosani', TRUE),
(2686, 175, 'BV', 'Brasov', TRUE),
(2687, 175, 'BR', 'Braila', TRUE),
(2688, 175, 'B', 'Bucuresti', TRUE),
(2689, 175, 'BZ', 'Buzau', TRUE),
(2690, 175, 'CS', 'Caras-Severin', TRUE),
(2691, 175, 'CL', 'Calarasi', TRUE),
(2692, 175, 'CJ', 'Cluj', TRUE),
(2693, 175, 'CT', 'Constanta', TRUE),
(2694, 175, 'CV', 'Covasna', TRUE),
(2695, 175, 'DB', 'Dimbovita', TRUE),
(2696, 175, 'DJ', 'Dolj', TRUE),
(2697, 175, 'GL', 'Galati', TRUE),
(2698, 175, 'GR', 'Giurgiu', TRUE),
(2699, 175, 'GJ', 'Gorj', TRUE),
(2700, 175, 'HR', 'Harghita', TRUE),
(2701, 175, 'HD', 'Hunedoara', TRUE),
(2702, 175, 'IL', 'Ialomita', TRUE),
(2703, 175, 'IS', 'Iasi', TRUE),
(2704, 175, 'IF', 'Ilfov', TRUE),
(2705, 175, 'MM', 'Maramures', TRUE),
(2706, 175, 'MH', 'Mehedinti', TRUE),
(2707, 175, 'MS', 'Mures', TRUE),
(2708, 175, 'NT', 'Neamt', TRUE),
(2709, 175, 'OT', 'Olt', TRUE),
(2710, 175, 'PH', 'Prahova', TRUE),
(2711, 175, 'SM', 'Satu-Mare', TRUE),
(2712, 175, 'SJ', 'Salaj', TRUE),
(2713, 175, 'SB', 'Sibiu', TRUE),
(2714, 175, 'SV', 'Suceava', TRUE),
(2715, 175, 'TR', 'Teleorman', TRUE),
(2716, 175, 'TM', 'Timis', TRUE),
(2717, 175, 'TL', 'Tulcea', TRUE),
(2718, 175, 'VS', 'Vaslui', TRUE),
(2719, 175, 'VL', 'Valcea', TRUE),
(2720, 175, 'VN', 'Vrancea', TRUE),
(2721, 176, 'AB', 'Abakan', TRUE),
(2722, 176, 'AG', 'Aginskoye', TRUE),
(2723, 176, 'AN', 'Anadyr', TRUE),
(2724, 176, 'AR', 'Arkahangelsk', TRUE),
(2725, 176, 'AS', 'Astrakhan', TRUE),
(2726, 176, 'BA', 'Barnaul', TRUE),
(2727, 176, 'BE', 'Belgorod', TRUE),
(2728, 176, 'BI', 'Birobidzhan', TRUE),
(2729, 176, 'BL', 'Blagoveshchensk', TRUE),
(2730, 176, 'BR', 'Bryansk', TRUE),
(2731, 176, 'CH', 'Cheboksary', TRUE),
(2732, 176, 'CL', 'Chelyabinsk', TRUE),
(2733, 176, 'CR', 'Cherkessk', TRUE),
(2734, 176, 'CI', 'Chita', TRUE),
(2735, 176, 'DU', 'Dudinka', TRUE),
(2736, 176, 'EL', 'Elista', TRUE),
(2737, 176, 'GO', 'Gomo-Altaysk', TRUE),
(2738, 176, 'GA', 'Gorno-Altaysk', TRUE),
(2739, 176, 'GR', 'Groznyy', TRUE),
(2740, 176, 'IR', 'Irkutsk', TRUE),
(2741, 176, 'IV', 'Ivanovo', TRUE),
(2742, 176, 'IZ', 'Izhevsk', TRUE),
(2743, 176, 'KA', 'Kalinigrad', TRUE),
(2744, 176, 'KL', 'Kaluga', TRUE),
(2745, 176, 'KS', 'Kasnodar', TRUE),
(2746, 176, 'KZ', 'Kazan', TRUE),
(2747, 176, 'KE', 'Kemerovo', TRUE),
(2748, 176, 'KH', 'Khabarovsk', TRUE),
(2749, 176, 'KM', 'Khanty-Mansiysk', TRUE),
(2750, 176, 'KO', 'Kostroma', TRUE),
(2751, 176, 'KR', 'Krasnodar', TRUE),
(2752, 176, 'KN', 'Krasnoyarsk', TRUE),
(2753, 176, 'KU', 'Kudymkar', TRUE),
(2754, 176, 'KG', 'Kurgan', TRUE),
(2755, 176, 'KK', 'Kursk', TRUE),
(2756, 176, 'KY', 'Kyzyl', TRUE),
(2757, 176, 'LI', 'Lipetsk', TRUE),
(2758, 176, 'MA', 'Magadan', TRUE),
(2759, 176, 'MK', 'Makhachkala', TRUE),
(2760, 176, 'MY', 'Maykop', TRUE),
(2761, 176, 'MO', 'Moscow', TRUE),
(2762, 176, 'MU', 'Murmansk', TRUE),
(2763, 176, 'NA', 'Nalchik', TRUE),
(2764, 176, 'NR', 'Naryan Mar', TRUE),
(2765, 176, 'NZ', 'Nazran', TRUE),
(2766, 176, 'NI', 'Nizhniy Novgorod', TRUE),
(2767, 176, 'NO', 'Novgorod', TRUE),
(2768, 176, 'NV', 'Novosibirsk', TRUE),
(2769, 176, 'OM', 'Omsk', TRUE),
(2770, 176, 'OR', 'Orel', TRUE),
(2771, 176, 'OE', 'Orenburg', TRUE),
(2772, 176, 'PA', 'Palana', TRUE),
(2773, 176, 'PE', 'Penza', TRUE),
(2774, 176, 'PR', 'Perm', TRUE),
(2775, 176, 'PK', 'Petropavlovsk-Kamchatskiy', TRUE),
(2776, 176, 'PT', 'Petrozavodsk', TRUE),
(2777, 176, 'PS', 'Pskov', TRUE),
(2778, 176, 'RO', 'Rostov-na-Donu', TRUE),
(2779, 176, 'RY', 'Ryazan', TRUE),
(2780, 176, 'SL', 'Salekhard', TRUE),
(2781, 176, 'SA', 'Samara', TRUE),
(2782, 176, 'SR', 'Saransk', TRUE),
(2783, 176, 'SV', 'Saratov', TRUE),
(2784, 176, 'SM', 'Smolensk', TRUE),
(2785, 176, 'SP', 'St. Petersburg', TRUE),
(2786, 176, 'ST', 'Stavropol', TRUE),
(2787, 176, 'SY', 'Syktyvkar', TRUE),
(2788, 176, 'TA', 'Tambov', TRUE),
(2789, 176, 'TO', 'Tomsk', TRUE),
(2790, 176, 'TU', 'Tula', TRUE),
(2791, 176, 'TR', 'Tura', TRUE),
(2792, 176, 'TV', 'Tver', TRUE),
(2793, 176, 'TY', 'Tyumen', TRUE),
(2794, 176, 'UF', 'Ufa', TRUE),
(2795, 176, 'UL', 'Ul''yanovsk', TRUE),
(2796, 176, 'UU', 'Ulan-Ude', TRUE),
(2797, 176, 'US', 'Ust''-Ordynskiy', TRUE),
(2798, 176, 'VL', 'Vladikavkaz', TRUE),
(2799, 176, 'VA', 'Vladimir', TRUE),
(2800, 176, 'VV', 'Vladivostok', TRUE),
(2801, 176, 'VG', 'Volgograd', TRUE),
(2802, 176, 'VD', 'Vologda', TRUE),
(2803, 176, 'VO', 'Voronezh', TRUE),
(2804, 176, 'VY', 'Vyatka', TRUE),
(2805, 176, 'YA', 'Yakutsk', TRUE),
(2806, 176, 'YR', 'Yaroslavl', TRUE),
(2807, 176, 'YE', 'Yekaterinburg', TRUE),
(2808, 176, 'YO', 'Yoshkar-Ola', TRUE),
(2809, 177, 'BU', 'Butare', TRUE),
(2810, 177, 'BY', 'Byumba', TRUE),
(2811, 177, 'CY', 'Cyangugu', TRUE),
(2812, 177, 'GK', 'Gikongoro', TRUE),
(2813, 177, 'GS', 'Gisenyi', TRUE),
(2814, 177, 'GT', 'Gitarama', TRUE),
(2815, 177, 'KG', 'Kibungo', TRUE),
(2816, 177, 'KY', 'Kibuye', TRUE),
(2817, 177, 'KR', 'Kigali Rurale', TRUE),
(2818, 177, 'KV', 'Kigali-ville', TRUE),
(2819, 177, 'RU', 'Ruhengeri', TRUE),
(2820, 177, 'UM', 'Umutara', TRUE),
(2821, 178, 'CCN', 'Christ Church Nichola Town', TRUE),
(2822, 178, 'SAS', 'Saint Anne Sandy Point', TRUE),
(2823, 178, 'SGB', 'Saint George Basseterre', TRUE),
(2824, 178, 'SGG', 'Saint George Gingerland', TRUE),
(2825, 178, 'SJW', 'Saint James Windward', TRUE),
(2826, 178, 'SJC', 'Saint John Capesterre', TRUE),
(2827, 178, 'SJF', 'Saint John Figtree', TRUE),
(2828, 178, 'SMC', 'Saint Mary Cayon', TRUE),
(2829, 178, 'CAP', 'Saint Paul Capesterre', TRUE),
(2830, 178, 'CHA', 'Saint Paul Charlestown', TRUE),
(2831, 178, 'SPB', 'Saint Peter Basseterre', TRUE),
(2832, 178, 'STL', 'Saint Thomas Lowland', TRUE),
(2833, 178, 'STM', 'Saint Thomas Middle Island', TRUE),
(2834, 178, 'TPP', 'Trinity Palmetto Point', TRUE),
(2835, 179, 'AR', 'Anse-la-Raye', TRUE),
(2836, 179, 'CA', 'Castries', TRUE),
(2837, 179, 'CH', 'Choiseul', TRUE),
(2838, 179, 'DA', 'Dauphin', TRUE),
(2839, 179, 'DE', 'Dennery', TRUE),
(2840, 179, 'GI', 'Gros-Islet', TRUE),
(2841, 179, 'LA', 'Laborie', TRUE),
(2842, 179, 'MI', 'Micoud', TRUE),
(2843, 179, 'PR', 'Praslin', TRUE),
(2844, 179, 'SO', 'Soufriere', TRUE),
(2845, 179, 'VF', 'Vieux-Fort', TRUE),
(2846, 180, 'C', 'Charlotte', TRUE),
(2847, 180, 'R', 'Grenadines', TRUE),
(2848, 180, 'A', 'Saint Andrew', TRUE),
(2849, 180, 'D', 'Saint David', TRUE),
(2850, 180, 'G', 'Saint George', TRUE),
(2851, 180, 'P', 'Saint Patrick', TRUE),
(2852, 181, 'AN', 'A''ana', TRUE),
(2853, 181, 'AI', 'Aiga-i-le-Tai', TRUE),
(2854, 181, 'AT', 'Atua', TRUE),
(2855, 181, 'FA', 'Fa''asaleleaga', TRUE),
(2856, 181, 'GE', 'Gaga''emauga', TRUE),
(2857, 181, 'GF', 'Gagaifomauga', TRUE),
(2858, 181, 'PA', 'Palauli', TRUE),
(2859, 181, 'SA', 'Satupa''itea', TRUE),
(2860, 181, 'TU', 'Tuamasaga', TRUE),
(2861, 181, 'VF', 'Va''a-o-Fonoti', TRUE),
(2862, 181, 'VS', 'Vaisigano', TRUE),
(2863, 182, 'AC', 'Acquaviva', TRUE),
(2864, 182, 'BM', 'Borgo Maggiore', TRUE),
(2865, 182, 'CH', 'Chiesanuova', TRUE),
(2866, 182, 'DO', 'Domagnano', TRUE),
(2867, 182, 'FA', 'Faetano', TRUE),
(2868, 182, 'FI', 'Fiorentino', TRUE),
(2869, 182, 'MO', 'Montegiardino', TRUE),
(2870, 182, 'SM', 'Citta di San Marino', TRUE),
(2871, 182, 'SE', 'Serravalle', TRUE),
(2872, 183, 'S', 'Sao Tome', TRUE),
(2873, 183, 'P', 'Principe', TRUE),
(2874, 184, 'BH', 'Al Bahah', TRUE),
(2875, 184, 'HS', 'Al Hudud ash Shamaliyah', TRUE),
(2876, 184, 'JF', 'Al Jawf', TRUE),
(2877, 184, 'MD', 'Al Madinah', TRUE),
(2878, 184, 'QS', 'Al Qasim', TRUE),
(2879, 184, 'RD', 'Ar Riyad', TRUE),
(2880, 184, 'AQ', 'Ash Sharqiyah (Eastern)', TRUE),
(2881, 184, 'AS', '''Asir', TRUE),
(2882, 184, 'HL', 'Ha''il', TRUE),
(2883, 184, 'JZ', 'Jizan', TRUE),
(2884, 184, 'ML', 'Makkah', TRUE),
(2885, 184, 'NR', 'Najran', TRUE),
(2886, 184, 'TB', 'Tabuk', TRUE),
(2887, 185, 'DA', 'Dakar', TRUE),
(2888, 185, 'DI', 'Diourbel', TRUE),
(2889, 185, 'FA', 'Fatick', TRUE),
(2890, 185, 'KA', 'Kaolack', TRUE),
(2891, 185, 'KO', 'Kolda', TRUE),
(2892, 185, 'LO', 'Louga', TRUE),
(2893, 185, 'MA', 'Matam', TRUE),
(2894, 185, 'SL', 'Saint-Louis', TRUE),
(2895, 185, 'TA', 'Tambacounda', TRUE),
(2896, 185, 'TH', 'Thies', TRUE),
(2897, 185, 'ZI', 'Ziguinchor', TRUE),
(2898, 186, 'AP', 'Anse aux Pins', TRUE),
(2899, 186, 'AB', 'Anse Boileau', TRUE),
(2900, 186, 'AE', 'Anse Etoile', TRUE),
(2901, 186, 'AL', 'Anse Louis', TRUE),
(2902, 186, 'AR', 'Anse Royale', TRUE),
(2903, 186, 'BL', 'Baie Lazare', TRUE),
(2904, 186, 'BS', 'Baie Sainte Anne', TRUE),
(2905, 186, 'BV', 'Beau Vallon', TRUE),
(2906, 186, 'BA', 'Bel Air', TRUE),
(2907, 186, 'BO', 'Bel Ombre', TRUE),
(2908, 186, 'CA', 'Cascade', TRUE),
(2909, 186, 'GL', 'Glacis', TRUE),
(2910, 186, 'GM', 'Grand'' Anse (on Mahe)', TRUE),
(2911, 186, 'GP', 'Grand'' Anse (on Praslin)', TRUE),
(2912, 186, 'DG', 'La Digue', TRUE),
(2913, 186, 'RA', 'La Riviere Anglaise', TRUE),
(2914, 186, 'MB', 'Mont Buxton', TRUE),
(2915, 186, 'MF', 'Mont Fleuri', TRUE),
(2916, 186, 'PL', 'Plaisance', TRUE),
(2917, 186, 'PR', 'Pointe La Rue', TRUE),
(2918, 186, 'PG', 'Port Glaud', TRUE),
(2919, 186, 'SL', 'Saint Louis', TRUE),
(2920, 186, 'TA', 'Takamaka', TRUE),
(2921, 187, 'E', 'Eastern', TRUE),
(2922, 187, 'N', 'Northern', TRUE),
(2923, 187, 'S', 'Southern', TRUE),
(2924, 187, 'W', 'Western', TRUE),
(2925, 189, 'BA', 'Banskobystrický', TRUE),
(2926, 189, 'BR', 'Bratislavský', TRUE),
(2927, 189, 'KO', 'Košický', TRUE),
(2928, 189, 'NI', 'Nitriansky', TRUE),
(2929, 189, 'PR', 'Prešovský', TRUE),
(2930, 189, 'TC', 'Trenčiansky', TRUE),
(2931, 189, 'TV', 'Trnavský', TRUE),
(2932, 189, 'ZI', 'Žilinský', TRUE),
(2933, 191, 'CE', 'Central', TRUE),
(2934, 191, 'CH', 'Choiseul', TRUE),
(2935, 191, 'GC', 'Guadalcanal', TRUE),
(2936, 191, 'HO', 'Honiara', TRUE),
(2937, 191, 'IS', 'Isabel', TRUE),
(2938, 191, 'MK', 'Makira', TRUE),
(2939, 191, 'ML', 'Malaita', TRUE),
(2940, 191, 'RB', 'Rennell and Bellona', TRUE),
(2941, 191, 'TM', 'Temotu', TRUE),
(2942, 191, 'WE', 'Western', TRUE),
(2943, 192, 'AW', 'Awdal', TRUE),
(2944, 192, 'BK', 'Bakool', TRUE),
(2945, 192, 'BN', 'Banaadir', TRUE),
(2946, 192, 'BR', 'Bari', TRUE),
(2947, 192, 'BY', 'Bay', TRUE),
(2948, 192, 'GA', 'Galguduud', TRUE),
(2949, 192, 'GE', 'Gedo', TRUE),
(2950, 192, 'HI', 'Hiiraan', TRUE),
(2951, 192, 'JD', 'Jubbada Dhexe', TRUE),
(2952, 192, 'JH', 'Jubbada Hoose', TRUE),
(2953, 192, 'MU', 'Mudug', TRUE),
(2954, 192, 'NU', 'Nugaal', TRUE),
(2955, 192, 'SA', 'Sanaag', TRUE),
(2956, 192, 'SD', 'Shabeellaha Dhexe', TRUE),
(2957, 192, 'SH', 'Shabeellaha Hoose', TRUE),
(2958, 192, 'SL', 'Sool', TRUE),
(2959, 192, 'TO', 'Togdheer', TRUE),
(2960, 192, 'WG', 'Woqooyi Galbeed', TRUE),
(2961, 193, 'EC', 'Eastern Cape', TRUE),
(2962, 193, 'FS', 'Free State', TRUE),
(2963, 193, 'GT', 'Gauteng', TRUE),
(2964, 193, 'KN', 'KwaZulu-Natal', TRUE),
(2965, 193, 'LP', 'Limpopo', TRUE),
(2966, 193, 'MP', 'Mpumalanga', TRUE),
(2967, 193, 'NW', 'North West', TRUE),
(2968, 193, 'NC', 'Northern Cape', TRUE),
(2969, 193, 'WC', 'Western Cape', TRUE),
(2970, 195, 'CA', 'La Coru&ntilde;a', TRUE),
(2971, 195, 'AL', '&Aacute;lava', TRUE),
(2972, 195, 'AB', 'Albacete', TRUE),
(2973, 195, 'AC', 'Alicante', TRUE),
(2974, 195, 'AM', 'Almeria', TRUE),
(2975, 195, 'AS', 'Asturias', TRUE),
(2976, 195, 'AV', '&Aacute;vila', TRUE),
(2977, 195, 'BJ', 'Badajoz', TRUE),
(2978, 195, 'IB', 'Baleares', TRUE),
(2979, 195, 'BA', 'Barcelona', TRUE),
(2980, 195, 'BU', 'Burgos', TRUE),
(2981, 195, 'CC', 'C&aacute;ceres', TRUE),
(2982, 195, 'CZ', 'C&aacute;diz', TRUE),
(2983, 195, 'CT', 'Cantabria', TRUE),
(2984, 195, 'CL', 'Castell&oacute;n', TRUE),
(2985, 195, 'CE', 'Ceuta', TRUE),
(2986, 195, 'CR', 'Ciudad Real', TRUE),
(2987, 195, 'CD', 'C&oacute;rdoba', TRUE),
(2988, 195, 'CU', 'Cuenca', TRUE),
(2989, 195, 'GI', 'Girona', TRUE),
(2990, 195, 'GD', 'Granada', TRUE),
(2991, 195, 'GJ', 'Guadalajara', TRUE),
(2992, 195, 'GP', 'Guip&uacute;zcoa', TRUE),
(2993, 195, 'HL', 'Huelva', TRUE),
(2994, 195, 'HS', 'Huesca', TRUE),
(2995, 195, 'JN', 'Ja&eacute;n', TRUE),
(2996, 195, 'RJ', 'La Rioja', TRUE),
(2997, 195, 'PM', 'Las Palmas', TRUE),
(2998, 195, 'LE', 'Leon', TRUE),
(2999, 195, 'LL', 'Lleida', TRUE),
(3000, 195, 'LG', 'Lugo', TRUE),
(3001, 195, 'MD', 'Madrid', TRUE),
(3002, 195, 'MA', 'Malaga', TRUE),
(3003, 195, 'ML', 'Melilla', TRUE),
(3004, 195, 'MU', 'Murcia', TRUE),
(3005, 195, 'NV', 'Navarra', TRUE),
(3006, 195, 'OU', 'Ourense', TRUE),
(3007, 195, 'PL', 'Palencia', TRUE),
(3008, 195, 'PO', 'Pontevedra', TRUE),
(3009, 195, 'SL', 'Salamanca', TRUE),
(3010, 195, 'SC', 'Santa Cruz de Tenerife', TRUE),
(3011, 195, 'SG', 'Segovia', TRUE),
(3012, 195, 'SV', 'Sevilla', TRUE),
(3013, 195, 'SO', 'Soria', TRUE),
(3014, 195, 'TA', 'Tarragona', TRUE),
(3015, 195, 'TE', 'Teruel', TRUE),
(3016, 195, 'TO', 'Toledo', TRUE),
(3017, 195, 'VC', 'Valencia', TRUE),
(3018, 195, 'VD', 'Valladolid', TRUE),
(3019, 195, 'VZ', 'Vizcaya', TRUE),
(3020, 195, 'ZM', 'Zamora', TRUE),
(3021, 195, 'ZR', 'Zaragoza', TRUE),
(3022, 196, 'CE', 'Central', TRUE),
(3023, 196, 'EA', 'Eastern', TRUE),
(3024, 196, 'NC', 'North Central', TRUE),
(3025, 196, 'NO', 'Northern', TRUE),
(3026, 196, 'NW', 'North Western', TRUE),
(3027, 196, 'SA', 'Sabaragamuwa', TRUE),
(3028, 196, 'SO', 'Southern', TRUE),
(3029, 196, 'UV', 'Uva', TRUE),
(3030, 196, 'WE', 'Western', TRUE),
(3031, 197, 'A', 'Ascension', TRUE),
(3032, 197, 'S', 'Saint Helena', TRUE),
(3033, 197, 'T', 'Tristan da Cunha', TRUE),
(3034, 199, 'ANL', 'A''ali an Nil', TRUE),
(3035, 199, 'BAM', 'Al Bahr al Ahmar', TRUE),
(3036, 199, 'BRT', 'Al Buhayrat', TRUE),
(3037, 199, 'JZR', 'Al Jazirah', TRUE),
(3038, 199, 'KRT', 'Al Khartum', TRUE);
INSERT INTO oc_zone (zone_id, country_id, code, name, status) VALUES
(3039, 199, 'QDR', 'Al Qadarif', TRUE),
(3040, 199, 'WDH', 'Al Wahdah', TRUE),
(3041, 199, 'ANB', 'An Nil al Abyad', TRUE),
(3042, 199, 'ANZ', 'An Nil al Azraq', TRUE),
(3043, 199, 'ASH', 'Ash Shamaliyah', TRUE),
(3044, 199, 'BJA', 'Bahr al Jabal', TRUE),
(3045, 199, 'GIS', 'Gharb al Istiwa''iyah', TRUE),
(3046, 199, 'GBG', 'Gharb Bahr al Ghazal', TRUE),
(3047, 199, 'GDA', 'Gharb Darfur', TRUE),
(3048, 199, 'GKU', 'Gharb Kurdufan', TRUE),
(3049, 199, 'JDA', 'Janub Darfur', TRUE),
(3050, 199, 'JKU', 'Janub Kurdufan', TRUE),
(3051, 199, 'JQL', 'Junqali', TRUE),
(3052, 199, 'KSL', 'Kassala', TRUE),
(3053, 199, 'NNL', 'Nahr an Nil', TRUE),
(3054, 199, 'SBG', 'Shamal Bahr al Ghazal', TRUE),
(3055, 199, 'SDA', 'Shamal Darfur', TRUE),
(3056, 199, 'SKU', 'Shamal Kurdufan', TRUE),
(3057, 199, 'SIS', 'Sharq al Istiwa''iyah', TRUE),
(3058, 199, 'SNR', 'Sinnar', TRUE),
(3059, 199, 'WRB', 'Warab', TRUE),
(3060, 200, 'BR', 'Brokopondo', TRUE),
(3061, 200, 'CM', 'Commewijne', TRUE),
(3062, 200, 'CR', 'Coronie', TRUE),
(3063, 200, 'MA', 'Marowijne', TRUE),
(3064, 200, 'NI', 'Nickerie', TRUE),
(3065, 200, 'PA', 'Para', TRUE),
(3066, 200, 'PM', 'Paramaribo', TRUE),
(3067, 200, 'SA', 'Saramacca', TRUE),
(3068, 200, 'SI', 'Sipaliwini', TRUE),
(3069, 200, 'WA', 'Wanica', TRUE),
(3070, 202, 'H', 'Hhohho', TRUE),
(3071, 202, 'L', 'Lubombo', TRUE),
(3072, 202, 'M', 'Manzini', TRUE),
(3073, 202, 'S', 'Shishelweni', TRUE),
(3074, 203, 'K', 'Blekinge', TRUE),
(3075, 203, 'W', 'Dalarna', TRUE),
(3076, 203, 'X', 'G&auml;vleborg', TRUE),
(3077, 203, 'I', 'Gotland', TRUE),
(3078, 203, 'N', 'Halland', TRUE),
(3079, 203, 'Z', 'J&auml;mtland', TRUE),
(3080, 203, 'F', 'J&ouml;nk&ouml;ping', TRUE),
(3081, 203, 'H', 'Kalmar', TRUE),
(3082, 203, 'G', 'Kronoberg', TRUE),
(3083, 203, 'BD', 'Norrbotten', TRUE),
(3084, 203, 'T', '&Ouml;rebro', TRUE),
(3085, 203, 'E', '&Ouml;sterg&ouml;tland', TRUE),
(3086, 203, 'M', 'Sk&aring;ne', TRUE),
(3087, 203, 'D', 'S&ouml;dermanland', TRUE),
(3088, 203, 'AB', 'Stockholm', TRUE),
(3089, 203, 'C', 'Uppsala', TRUE),
(3090, 203, 'S', 'V&auml;rmland', TRUE),
(3091, 203, 'AC', 'V&auml;sterbotten', TRUE),
(3092, 203, 'Y', 'V&auml;sternorrland', TRUE),
(3093, 203, 'U', 'V&auml;stmanland', TRUE),
(3094, 203, 'O', 'V&auml;stra G&ouml;taland', TRUE),
(3095, 204, 'AG', 'Aargau', TRUE),
(3096, 204, 'AR', 'Appenzell Ausserrhoden', TRUE),
(3097, 204, 'AI', 'Appenzell Innerrhoden', TRUE),
(3098, 204, 'BS', 'Basel-Stadt', TRUE),
(3099, 204, 'BL', 'Basel-Landschaft', TRUE),
(3100, 204, 'BE', 'Bern', TRUE),
(3101, 204, 'FR', 'Fribourg', TRUE),
(3102, 204, 'GE', 'Gen&egrave;ve', TRUE),
(3103, 204, 'GL', 'Glarus', TRUE),
(3104, 204, 'GR', 'Graub&uuml;nden', TRUE),
(3105, 204, 'JU', 'Jura', TRUE),
(3106, 204, 'LU', 'Luzern', TRUE),
(3107, 204, 'NE', 'Neuch&acirc;tel', TRUE),
(3108, 204, 'NW', 'Nidwald', TRUE),
(3109, 204, 'OW', 'Obwald', TRUE),
(3110, 204, 'SG', 'St. Gallen', TRUE),
(3111, 204, 'SH', 'Schaffhausen', TRUE),
(3112, 204, 'SZ', 'Schwyz', TRUE),
(3113, 204, 'SO', 'Solothurn', TRUE),
(3114, 204, 'TG', 'Thurgau', TRUE),
(3115, 204, 'TI', 'Ticino', TRUE),
(3116, 204, 'UR', 'Uri', TRUE),
(3117, 204, 'VS', 'Valais', TRUE),
(3118, 204, 'VD', 'Vaud', TRUE),
(3119, 204, 'ZG', 'Zug', TRUE),
(3120, 204, 'ZH', 'Z&uuml;rich', TRUE),
(3121, 205, 'HA', 'Al Hasakah', TRUE),
(3122, 205, 'LA', 'Al Ladhiqiyah', TRUE),
(3123, 205, 'QU', 'Al Qunaytirah', TRUE),
(3124, 205, 'RQ', 'Ar Raqqah', TRUE),
(3125, 205, 'SU', 'As Suwayda', TRUE),
(3126, 205, 'DA', 'Dara', TRUE),
(3127, 205, 'DZ', 'Dayr az Zawr', TRUE),
(3128, 205, 'DI', 'Dimashq', TRUE),
(3129, 205, 'HL', 'Halab', TRUE),
(3130, 205, 'HM', 'Hamah', TRUE),
(3131, 205, 'HI', 'Hims', TRUE),
(3132, 205, 'ID', 'Idlib', TRUE),
(3133, 205, 'RD', 'Rif Dimashq', TRUE),
(3134, 205, 'TA', 'Tartus', TRUE),
(3135, 206, 'CH', 'Chang-hua', TRUE),
(3136, 206, 'CI', 'Chia-i', TRUE),
(3137, 206, 'HS', 'Hsin-chu', TRUE),
(3138, 206, 'HL', 'Hua-lien', TRUE),
(3139, 206, 'IL', 'I-lan', TRUE),
(3140, 206, 'KH', 'Kao-hsiung county', TRUE),
(3141, 206, 'KM', 'Kin-men', TRUE),
(3142, 206, 'LC', 'Lien-chiang', TRUE),
(3143, 206, 'ML', 'Miao-li', TRUE),
(3144, 206, 'NT', 'Nan-t''ou', TRUE),
(3145, 206, 'PH', 'P''eng-hu', TRUE),
(3146, 206, 'PT', 'P''ing-tung', TRUE),
(3147, 206, 'TG', 'T''ai-chung', TRUE),
(3148, 206, 'TA', 'T''ai-nan', TRUE),
(3149, 206, 'TP', 'T''ai-pei county', TRUE),
(3150, 206, 'TT', 'T''ai-tung', TRUE),
(3151, 206, 'TY', 'T''ao-yuan', TRUE),
(3152, 206, 'YL', 'Yun-lin', TRUE),
(3153, 206, 'CC', 'Chia-i city', TRUE),
(3154, 206, 'CL', 'Chi-lung', TRUE),
(3155, 206, 'HC', 'Hsin-chu', TRUE),
(3156, 206, 'TH', 'T''ai-chung', TRUE),
(3157, 206, 'TN', 'T''ai-nan', TRUE),
(3158, 206, 'KC', 'Kao-hsiung city', TRUE),
(3159, 206, 'TC', 'T''ai-pei city', TRUE),
(3160, 207, 'GB', 'Gorno-Badakhstan', TRUE),
(3161, 207, 'KT', 'Khatlon', TRUE),
(3162, 207, 'SU', 'Sughd', TRUE),
(3163, 208, 'AR', 'Arusha', TRUE),
(3164, 208, 'DS', 'Dar es Salaam', TRUE),
(3165, 208, 'DO', 'Dodoma', TRUE),
(3166, 208, 'IR', 'Iringa', TRUE),
(3167, 208, 'KA', 'Kagera', TRUE),
(3168, 208, 'KI', 'Kigoma', TRUE),
(3169, 208, 'KJ', 'Kilimanjaro', TRUE),
(3170, 208, 'LN', 'Lindi', TRUE),
(3171, 208, 'MY', 'Manyara', TRUE),
(3172, 208, 'MR', 'Mara', TRUE),
(3173, 208, 'MB', 'Mbeya', TRUE),
(3174, 208, 'MO', 'Morogoro', TRUE),
(3175, 208, 'MT', 'Mtwara', TRUE),
(3176, 208, 'MW', 'Mwanza', TRUE),
(3177, 208, 'PN', 'Pemba North', TRUE),
(3178, 208, 'PS', 'Pemba South', TRUE),
(3179, 208, 'PW', 'Pwani', TRUE),
(3180, 208, 'RK', 'Rukwa', TRUE),
(3181, 208, 'RV', 'Ruvuma', TRUE),
(3182, 208, 'SH', 'Shinyanga', TRUE),
(3183, 208, 'SI', 'Singida', TRUE),
(3184, 208, 'TB', 'Tabora', TRUE),
(3185, 208, 'TN', 'Tanga', TRUE),
(3186, 208, 'ZC', 'Zanzibar Central/South', TRUE),
(3187, 208, 'ZN', 'Zanzibar North', TRUE),
(3188, 208, 'ZU', 'Zanzibar Urban/West', TRUE),
(3189, 209, 'Amnat Charoen', 'Amnat Charoen', TRUE),
(3190, 209, 'Ang Thong', 'Ang Thong', TRUE),
(3191, 209, 'Ayutthaya', 'Ayutthaya', TRUE),
(3192, 209, 'Bangkok', 'Bangkok', TRUE),
(3193, 209, 'Buriram', 'Buriram', TRUE),
(3194, 209, 'Chachoengsao', 'Chachoengsao', TRUE),
(3195, 209, 'Chai Nat', 'Chai Nat', TRUE),
(3196, 209, 'Chaiyaphum', 'Chaiyaphum', TRUE),
(3197, 209, 'Chanthaburi', 'Chanthaburi', TRUE),
(3198, 209, 'Chiang Mai', 'Chiang Mai', TRUE),
(3199, 209, 'Chiang Rai', 'Chiang Rai', TRUE),
(3200, 209, 'Chon Buri', 'Chon Buri', TRUE),
(3201, 209, 'Chumphon', 'Chumphon', TRUE),
(3202, 209, 'Kalasin', 'Kalasin', TRUE),
(3203, 209, 'Kamphaeng Phet', 'Kamphaeng Phet', TRUE),
(3204, 209, 'Kanchanaburi', 'Kanchanaburi', TRUE),
(3205, 209, 'Khon Kaen', 'Khon Kaen', TRUE),
(3206, 209, 'Krabi', 'Krabi', TRUE),
(3207, 209, 'Lampang', 'Lampang', TRUE),
(3208, 209, 'Lamphun', 'Lamphun', TRUE),
(3209, 209, 'Loei', 'Loei', TRUE),
(3210, 209, 'Lop Buri', 'Lop Buri', TRUE),
(3211, 209, 'Mae Hong Son', 'Mae Hong Son', TRUE),
(3212, 209, 'Maha Sarakham', 'Maha Sarakham', TRUE),
(3213, 209, 'Mukdahan', 'Mukdahan', TRUE),
(3214, 209, 'Nakhon Nayok', 'Nakhon Nayok', TRUE),
(3215, 209, 'Nakhon Pathom', 'Nakhon Pathom', TRUE),
(3216, 209, 'Nakhon Phanom', 'Nakhon Phanom', TRUE),
(3217, 209, 'Nakhon Ratchasima', 'Nakhon Ratchasima', TRUE),
(3218, 209, 'Nakhon Sawan', 'Nakhon Sawan', TRUE),
(3219, 209, 'Nakhon Si Thammarat', 'Nakhon Si Thammarat', TRUE),
(3220, 209, 'Nan', 'Nan', TRUE),
(3221, 209, 'Narathiwat', 'Narathiwat', TRUE),
(3222, 209, 'Nong Bua Lamphu', 'Nong Bua Lamphu', TRUE),
(3223, 209, 'Nong Khai', 'Nong Khai', TRUE),
(3224, 209, 'Nonthaburi', 'Nonthaburi', TRUE),
(3225, 209, 'Pathum Thani', 'Pathum Thani', TRUE),
(3226, 209, 'Pattani', 'Pattani', TRUE),
(3227, 209, 'Phangnga', 'Phangnga', TRUE),
(3228, 209, 'Phatthalung', 'Phatthalung', TRUE),
(3229, 209, 'Phayao', 'Phayao', TRUE),
(3230, 209, 'Phetchabun', 'Phetchabun', TRUE),
(3231, 209, 'Phetchaburi', 'Phetchaburi', TRUE),
(3232, 209, 'Phichit', 'Phichit', TRUE),
(3233, 209, 'Phitsanulok', 'Phitsanulok', TRUE),
(3234, 209, 'Phrae', 'Phrae', TRUE),
(3235, 209, 'Phuket', 'Phuket', TRUE),
(3236, 209, 'Prachin Buri', 'Prachin Buri', TRUE),
(3237, 209, 'Prachuap Khiri Khan', 'Prachuap Khiri Khan', TRUE),
(3238, 209, 'Ranong', 'Ranong', TRUE),
(3239, 209, 'Ratchaburi', 'Ratchaburi', TRUE),
(3240, 209, 'Rayong', 'Rayong', TRUE),
(3241, 209, 'Roi Et', 'Roi Et', TRUE),
(3242, 209, 'Sa Kaeo', 'Sa Kaeo', TRUE),
(3243, 209, 'Sakon Nakhon', 'Sakon Nakhon', TRUE),
(3244, 209, 'Samut Prakan', 'Samut Prakan', TRUE),
(3245, 209, 'Samut Sakhon', 'Samut Sakhon', TRUE),
(3246, 209, 'Samut Songkhram', 'Samut Songkhram', TRUE),
(3247, 209, 'Sara Buri', 'Sara Buri', TRUE),
(3248, 209, 'Satun', 'Satun', TRUE),
(3249, 209, 'Sing Buri', 'Sing Buri', TRUE),
(3250, 209, 'Sisaket', 'Sisaket', TRUE),
(3251, 209, 'Songkhla', 'Songkhla', TRUE),
(3252, 209, 'Sukhothai', 'Sukhothai', TRUE),
(3253, 209, 'Suphan Buri', 'Suphan Buri', TRUE),
(3254, 209, 'Surat Thani', 'Surat Thani', TRUE),
(3255, 209, 'Surin', 'Surin', TRUE),
(3256, 209, 'Tak', 'Tak', TRUE),
(3257, 209, 'Trang', 'Trang', TRUE),
(3258, 209, 'Trat', 'Trat', TRUE),
(3259, 209, 'Ubon Ratchathani', 'Ubon Ratchathani', TRUE),
(3260, 209, 'Udon Thani', 'Udon Thani', TRUE),
(3261, 209, 'Uthai Thani', 'Uthai Thani', TRUE),
(3262, 209, 'Uttaradit', 'Uttaradit', TRUE),
(3263, 209, 'Yala', 'Yala', TRUE),
(3264, 209, 'Yasothon', 'Yasothon', TRUE),
(3265, 210, 'K', 'Kara', TRUE),
(3266, 210, 'P', 'Plateaux', TRUE),
(3267, 210, 'S', 'Savanes', TRUE),
(3268, 210, 'C', 'Centrale', TRUE),
(3269, 210, 'M', 'Maritime', TRUE),
(3270, 211, 'A', 'Atafu', TRUE),
(3271, 211, 'F', 'Fakaofo', TRUE),
(3272, 211, 'N', 'Nukunonu', TRUE),
(3273, 212, 'H', 'Ha''apai', TRUE),
(3274, 212, 'T', 'Tongatapu', TRUE),
(3275, 212, 'V', 'Vava''u', TRUE),
(3276, 213, 'CT', 'Couva/Tabaquite/Talparo', TRUE),
(3277, 213, 'DM', 'Diego Martin', TRUE),
(3278, 213, 'MR', 'Mayaro/Rio Claro', TRUE),
(3279, 213, 'PD', 'Penal/Debe', TRUE),
(3280, 213, 'PT', 'Princes Town', TRUE),
(3281, 213, 'SG', 'Sangre Grande', TRUE),
(3282, 213, 'SL', 'San Juan/Laventille', TRUE),
(3283, 213, 'SI', 'Siparia', TRUE),
(3284, 213, 'TP', 'Tunapuna/Piarco', TRUE),
(3285, 213, 'PS', 'Port of Spain', TRUE),
(3286, 213, 'SF', 'San Fernando', TRUE),
(3287, 213, 'AR', 'Arima', TRUE),
(3288, 213, 'PF', 'Point Fortin', TRUE),
(3289, 213, 'CH', 'Chaguanas', TRUE),
(3290, 213, 'TO', 'Tobago', TRUE),
(3291, 214, 'AR', 'Ariana', TRUE),
(3292, 214, 'BJ', 'Beja', TRUE),
(3293, 214, 'BA', 'Ben Arous', TRUE),
(3294, 214, 'BI', 'Bizerte', TRUE),
(3295, 214, 'GB', 'Gabes', TRUE),
(3296, 214, 'GF', 'Gafsa', TRUE),
(3297, 214, 'JE', 'Jendouba', TRUE),
(3298, 214, 'KR', 'Kairouan', TRUE),
(3299, 214, 'KS', 'Kasserine', TRUE),
(3300, 214, 'KB', 'Kebili', TRUE),
(3301, 214, 'KF', 'Kef', TRUE),
(3302, 214, 'MH', 'Mahdia', TRUE),
(3303, 214, 'MN', 'Manouba', TRUE),
(3304, 214, 'ME', 'Medenine', TRUE),
(3305, 214, 'MO', 'Monastir', TRUE),
(3306, 214, 'NA', 'Nabeul', TRUE),
(3307, 214, 'SF', 'Sfax', TRUE),
(3308, 214, 'SD', 'Sidi', TRUE),
(3309, 214, 'SL', 'Siliana', TRUE),
(3310, 214, 'SO', 'Sousse', TRUE),
(3311, 214, 'TA', 'Tataouine', TRUE),
(3312, 214, 'TO', 'Tozeur', TRUE),
(3313, 214, 'TU', 'Tunis', TRUE),
(3314, 214, 'ZA', 'Zaghouan', TRUE),
(3315, 215, 'ADA', 'Adana', TRUE),
(3316, 215, 'ADI', 'Adıyaman', TRUE),
(3317, 215, 'AFY', 'Afyonkarahisar', TRUE),
(3318, 215, 'AGR', 'Ağrı', TRUE),
(3319, 215, 'AKS', 'Aksaray', TRUE),
(3320, 215, 'AMA', 'Amasya', TRUE),
(3321, 215, 'ANK', 'Ankara', TRUE),
(3322, 215, 'ANT', 'Antalya', TRUE),
(3323, 215, 'ARD', 'Ardahan', TRUE),
(3324, 215, 'ART', 'Artvin', TRUE),
(3325, 215, 'AYI', 'Aydın', TRUE),
(3326, 215, 'BAL', 'Balıkesir', TRUE),
(3327, 215, 'BAR', 'Bartın', TRUE),
(3328, 215, 'BAT', 'Batman', TRUE),
(3329, 215, 'BAY', 'Bayburt', TRUE),
(3330, 215, 'BIL', 'Bilecik', TRUE),
(3331, 215, 'BIN', 'Bingöl', TRUE),
(3332, 215, 'BIT', 'Bitlis', TRUE),
(3333, 215, 'BOL', 'Bolu', TRUE),
(3334, 215, 'BRD', 'Burdur', TRUE),
(3335, 215, 'BRS', 'Bursa', TRUE),
(3336, 215, 'CKL', 'Çanakkale', TRUE),
(3337, 215, 'CKR', 'Çankırı', TRUE),
(3338, 215, 'COR', 'Çorum', TRUE),
(3339, 215, 'DEN', 'Denizli', TRUE),
(3340, 215, 'DIY', 'Diyarbakir', TRUE),
(3341, 215, 'DUZ', 'Düzce', TRUE),
(3342, 215, 'EDI', 'Edirne', TRUE),
(3343, 215, 'ELA', 'Elazığ', TRUE),
(3344, 215, 'EZC', 'Erzincan', TRUE),
(3345, 215, 'EZR', 'Erzurum', TRUE),
(3346, 215, 'ESK', 'Eskişehir', TRUE),
(3347, 215, 'GAZ', 'Gaziantep', TRUE),
(3348, 215, 'GIR', 'Giresun', TRUE),
(3349, 215, 'GMS', 'Gümüşhane', TRUE),
(3350, 215, 'HKR', 'Hakkari', TRUE),
(3351, 215, 'HTY', 'Hatay', TRUE),
(3352, 215, 'IGD', 'Iğdır', TRUE),
(3353, 215, 'ISP', 'Isparta', TRUE),
(3354, 215, 'IST', 'İstanbul', TRUE),
(3355, 215, 'IZM', 'İzmir', TRUE),
(3356, 215, 'KAH', 'Kahramanmaraş', TRUE),
(3357, 215, 'KRB', 'Karabük', TRUE),
(3358, 215, 'KRM', 'Karaman', TRUE),
(3359, 215, 'KRS', 'Kars', TRUE),
(3360, 215, 'KAS', 'Kastamonu', TRUE),
(3361, 215, 'KAY', 'Kayseri', TRUE),
(3362, 215, 'KLS', 'Kilis', TRUE),
(3363, 215, 'KRK', 'Kırıkkale', TRUE),
(3364, 215, 'KLR', 'Kırklareli', TRUE),
(3365, 215, 'KRH', 'Kırşehir', TRUE),
(3366, 215, 'KOC', 'Kocaeli', TRUE),
(3367, 215, 'KON', 'Konya', TRUE),
(3368, 215, 'KUT', 'Kütahya', TRUE),
(3369, 215, 'MAL', 'Malatya', TRUE),
(3370, 215, 'MAN', 'Manisa', TRUE),
(3371, 215, 'MAR', 'Mardin', TRUE),
(3372, 215, 'MER', 'Mersin', TRUE),
(3373, 215, 'MUG', 'Muğla', TRUE),
(3374, 215, 'MUS', 'Muş', TRUE),
(3375, 215, 'NEV', 'Nevşehir', TRUE),
(3376, 215, 'NIG', 'Niğde', TRUE),
(3377, 215, 'ORD', 'Ordu', TRUE),
(3378, 215, 'OSM', 'Osmaniye', TRUE),
(3379, 215, 'RIZ', 'Rize', TRUE),
(3380, 215, 'SAK', 'Sakarya', TRUE),
(3381, 215, 'SAM', 'Samsun', TRUE),
(3382, 215, 'SAN', 'Şanlıurfa', TRUE),
(3383, 215, 'SII', 'Siirt', TRUE),
(3384, 215, 'SIN', 'Sinop', TRUE),
(3385, 215, 'SIR', 'Şırnak', TRUE),
(3386, 215, 'SIV', 'Sivas', TRUE),
(3387, 215, 'TEL', 'Tekirdağ', TRUE),
(3388, 215, 'TOK', 'Tokat', TRUE),
(3389, 215, 'TRA', 'Trabzon', TRUE),
(3390, 215, 'TUN', 'Tunceli', TRUE),
(3391, 215, 'USK', 'Uşak', TRUE),
(3392, 215, 'VAN', 'Van', TRUE),
(3393, 215, 'YAL', 'Yalova', TRUE),
(3394, 215, 'YOZ', 'Yozgat', TRUE),
(3395, 215, 'ZON', 'Zonguldak', TRUE),
(3396, 216, 'A', 'Ahal Welayaty', TRUE),
(3397, 216, 'B', 'Balkan Welayaty', TRUE),
(3398, 216, 'D', 'Dashhowuz Welayaty', TRUE),
(3399, 216, 'L', 'Lebap Welayaty', TRUE),
(3400, 216, 'M', 'Mary Welayaty', TRUE),
(3401, 217, 'AC', 'Ambergris Cays', TRUE),
(3402, 217, 'DC', 'Dellis Cay', TRUE),
(3403, 217, 'FC', 'French Cay', TRUE),
(3404, 217, 'LW', 'Little Water Cay', TRUE),
(3405, 217, 'RC', 'Parrot Cay', TRUE),
(3406, 217, 'PN', 'Pine Cay', TRUE),
(3407, 217, 'SL', 'Salt Cay', TRUE),
(3408, 217, 'GT', 'Grand Turk', TRUE),
(3409, 217, 'SC', 'South Caicos', TRUE),
(3410, 217, 'EC', 'East Caicos', TRUE),
(3411, 217, 'MC', 'Middle Caicos', TRUE),
(3412, 217, 'NC', 'North Caicos', TRUE),
(3413, 217, 'PR', 'Providenciales', TRUE),
(3414, 217, 'WC', 'West Caicos', TRUE),
(3415, 218, 'NMG', 'Nanumanga', TRUE),
(3416, 218, 'NLK', 'Niulakita', TRUE),
(3417, 218, 'NTO', 'Niutao', TRUE),
(3418, 218, 'FUN', 'Funafuti', TRUE),
(3419, 218, 'NME', 'Nanumea', TRUE),
(3420, 218, 'NUI', 'Nui', TRUE),
(3421, 218, 'NFT', 'Nukufetau', TRUE),
(3422, 218, 'NLL', 'Nukulaelae', TRUE),
(3423, 218, 'VAI', 'Vaitupu', TRUE),
(3424, 219, 'KAL', 'Kalangala', TRUE),
(3425, 219, 'KMP', 'Kampala', TRUE),
(3426, 219, 'KAY', 'Kayunga', TRUE),
(3427, 219, 'KIB', 'Kiboga', TRUE),
(3428, 219, 'LUW', 'Luwero', TRUE),
(3429, 219, 'MAS', 'Masaka', TRUE),
(3430, 219, 'MPI', 'Mpigi', TRUE),
(3431, 219, 'MUB', 'Mubende', TRUE),
(3432, 219, 'MUK', 'Mukono', TRUE),
(3433, 219, 'NKS', 'Nakasongola', TRUE),
(3434, 219, 'RAK', 'Rakai', TRUE),
(3435, 219, 'SEM', 'Sembabule', TRUE),
(3436, 219, 'WAK', 'Wakiso', TRUE),
(3437, 219, 'BUG', 'Bugiri', TRUE),
(3438, 219, 'BUS', 'Busia', TRUE),
(3439, 219, 'IGA', 'Iganga', TRUE),
(3440, 219, 'JIN', 'Jinja', TRUE),
(3441, 219, 'KAB', 'Kaberamaido', TRUE),
(3442, 219, 'KML', 'Kamuli', TRUE),
(3443, 219, 'KPC', 'Kapchorwa', TRUE),
(3444, 219, 'KTK', 'Katakwi', TRUE),
(3445, 219, 'KUM', 'Kumi', TRUE),
(3446, 219, 'MAY', 'Mayuge', TRUE),
(3447, 219, 'MBA', 'Mbale', TRUE),
(3448, 219, 'PAL', 'Pallisa', TRUE),
(3449, 219, 'SIR', 'Sironko', TRUE),
(3450, 219, 'SOR', 'Soroti', TRUE),
(3451, 219, 'TOR', 'Tororo', TRUE),
(3452, 219, 'ADJ', 'Adjumani', TRUE),
(3453, 219, 'APC', 'Apac', TRUE),
(3454, 219, 'ARU', 'Arua', TRUE),
(3455, 219, 'GUL', 'Gulu', TRUE),
(3456, 219, 'KIT', 'Kitgum', TRUE),
(3457, 219, 'KOT', 'Kotido', TRUE),
(3458, 219, 'LIR', 'Lira', TRUE),
(3459, 219, 'MRT', 'Moroto', TRUE),
(3460, 219, 'MOY', 'Moyo', TRUE),
(3461, 219, 'NAK', 'Nakapiripirit', TRUE),
(3462, 219, 'NEB', 'Nebbi', TRUE),
(3463, 219, 'PAD', 'Pader', TRUE),
(3464, 219, 'YUM', 'Yumbe', TRUE),
(3465, 219, 'BUN', 'Bundibugyo', TRUE),
(3466, 219, 'BSH', 'Bushenyi', TRUE),
(3467, 219, 'HOI', 'Hoima', TRUE),
(3468, 219, 'KBL', 'Kabale', TRUE),
(3469, 219, 'KAR', 'Kabarole', TRUE),
(3470, 219, 'KAM', 'Kamwenge', TRUE),
(3471, 219, 'KAN', 'Kanungu', TRUE),
(3472, 219, 'KAS', 'Kasese', TRUE),
(3473, 219, 'KBA', 'Kibaale', TRUE),
(3474, 219, 'KIS', 'Kisoro', TRUE),
(3475, 219, 'KYE', 'Kyenjojo', TRUE),
(3476, 219, 'MSN', 'Masindi', TRUE),
(3477, 219, 'MBR', 'Mbarara', TRUE),
(3478, 219, 'NTU', 'Ntungamo', TRUE),
(3479, 219, 'RUK', 'Rukungiri', TRUE),
(3480, 220, 'CK', 'Cherkasy', TRUE),
(3481, 220, 'CH', 'Chernihiv', TRUE),
(3482, 220, 'CV', 'Chernivtsi', TRUE),
(3483, 220, 'CR', 'Crimea', TRUE),
(3484, 220, 'DN', 'Dnipropetrovs''k', TRUE),
(3485, 220, 'DO', 'Donets''k', TRUE),
(3486, 220, 'IV', 'Ivano-Frankivs''k', TRUE),
(3487, 220, 'KL', 'Kharkiv Kherson', TRUE),
(3488, 220, 'KM', 'Khmel''nyts''kyy', TRUE),
(3489, 220, 'KR', 'Kirovohrad', TRUE),
(3490, 220, 'KV', 'Kiev', TRUE),
(3491, 220, 'KY', 'Kyyiv', TRUE),
(3492, 220, 'LU', 'Luhans''k', TRUE),
(3493, 220, 'LV', 'L''viv', TRUE),
(3494, 220, 'MY', 'Mykolayiv', TRUE),
(3495, 220, 'OD', 'Odesa', TRUE),
(3496, 220, 'PO', 'Poltava', TRUE),
(3497, 220, 'RI', 'Rivne', TRUE),
(3498, 220, 'SE', 'Sevastopol', TRUE),
(3499, 220, 'SU', 'Sumy', TRUE),
(3500, 220, 'TE', 'Ternopil''', TRUE),
(3501, 220, 'VI', 'Vinnytsya', TRUE),
(3502, 220, 'VO', 'Volyn''', TRUE),
(3503, 220, 'ZK', 'Zakarpattya', TRUE),
(3504, 220, 'ZA', 'Zaporizhzhya', TRUE),
(3505, 220, 'ZH', 'Zhytomyr', TRUE),
(3506, 221, 'AZ', 'Abu Zaby', TRUE),
(3507, 221, 'AJ', '''Ajman', TRUE),
(3508, 221, 'FU', 'Al Fujayrah', TRUE),
(3509, 221, 'SH', 'Ash Shariqah', TRUE),
(3510, 221, 'DU', 'Dubayy', TRUE),
(3511, 221, 'RK', 'R''as al Khaymah', TRUE),
(3512, 221, 'UQ', 'Umm al Qaywayn', TRUE),
(3513, 222, 'ABN', 'Aberdeen', TRUE),
(3514, 222, 'ABNS', 'Aberdeenshire', TRUE),
(3515, 222, 'ANG', 'Anglesey', TRUE),
(3516, 222, 'AGS', 'Angus', TRUE),
(3517, 222, 'ARY', 'Argyll and Bute', TRUE),
(3518, 222, 'BEDS', 'Bedfordshire', TRUE),
(3519, 222, 'BERKS', 'Berkshire', TRUE),
(3520, 222, 'BLA', 'Blaenau Gwent', TRUE),
(3521, 222, 'BRI', 'Bridgend', TRUE),
(3522, 222, 'BSTL', 'Bristol', TRUE),
(3523, 222, 'BUCKS', 'Buckinghamshire', TRUE),
(3524, 222, 'CAE', 'Caerphilly', TRUE),
(3525, 222, 'CAMBS', 'Cambridgeshire', TRUE),
(3526, 222, 'CDF', 'Cardiff', TRUE),
(3527, 222, 'CARM', 'Carmarthenshire', TRUE),
(3528, 222, 'CDGN', 'Ceredigion', TRUE),
(3529, 222, 'CHES', 'Cheshire', TRUE),
(3530, 222, 'CLACK', 'Clackmannanshire', TRUE),
(3531, 222, 'CON', 'Conwy', TRUE),
(3532, 222, 'CORN', 'Cornwall', TRUE),
(3533, 222, 'DNBG', 'Denbighshire', TRUE),
(3534, 222, 'DERBY', 'Derbyshire', TRUE),
(3535, 222, 'DVN', 'Devon', TRUE),
(3536, 222, 'DOR', 'Dorset', TRUE),
(3537, 222, 'DGL', 'Dumfries and Galloway', TRUE),
(3538, 222, 'DUND', 'Dundee', TRUE),
(3539, 222, 'DHM', 'Durham', TRUE),
(3540, 222, 'ARYE', 'East Ayrshire', TRUE),
(3541, 222, 'DUNBE', 'East Dunbartonshire', TRUE),
(3542, 222, 'LOTE', 'East Lothian', TRUE),
(3543, 222, 'RENE', 'East Renfrewshire', TRUE),
(3544, 222, 'ERYS', 'East Riding of Yorkshire', TRUE),
(3545, 222, 'SXE', 'East Sussex', TRUE),
(3546, 222, 'EDIN', 'Edinburgh', TRUE),
(3547, 222, 'ESX', 'Essex', TRUE),
(3548, 222, 'FALK', 'Falkirk', TRUE),
(3549, 222, 'FFE', 'Fife', TRUE),
(3550, 222, 'FLINT', 'Flintshire', TRUE),
(3551, 222, 'GLAS', 'Glasgow', TRUE),
(3552, 222, 'GLOS', 'Gloucestershire', TRUE),
(3553, 222, 'LDN', 'Greater London', TRUE),
(3554, 222, 'MCH', 'Greater Manchester', TRUE),
(3555, 222, 'GDD', 'Gwynedd', TRUE),
(3556, 222, 'HANTS', 'Hampshire', TRUE),
(3557, 222, 'HWR', 'Herefordshire', TRUE),
(3558, 222, 'HERTS', 'Hertfordshire', TRUE),
(3559, 222, 'HLD', 'Highlands', TRUE),
(3560, 222, 'IVER', 'Inverclyde', TRUE),
(3561, 222, 'IOW', 'Isle of Wight', TRUE),
(3562, 222, 'KNT', 'Kent', TRUE),
(3563, 222, 'LANCS', 'Lancashire', TRUE),
(3564, 222, 'LEICS', 'Leicestershire', TRUE),
(3565, 222, 'LINCS', 'Lincolnshire', TRUE),
(3566, 222, 'MSY', 'Merseyside', TRUE),
(3567, 222, 'MERT', 'Merthyr Tydfil', TRUE),
(3568, 222, 'MLOT', 'Midlothian', TRUE),
(3569, 222, 'MMOUTH', 'Monmouthshire', TRUE),
(3570, 222, 'MORAY', 'Moray', TRUE),
(3571, 222, 'NPRTAL', 'Neath Port Talbot', TRUE),
(3572, 222, 'NEWPT', 'Newport', TRUE),
(3573, 222, 'NOR', 'Norfolk', TRUE),
(3574, 222, 'ARYN', 'North Ayrshire', TRUE),
(3575, 222, 'LANN', 'North Lanarkshire', TRUE),
(3576, 222, 'YSN', 'North Yorkshire', TRUE),
(3577, 222, 'NHM', 'Northamptonshire', TRUE),
(3578, 222, 'NLD', 'Northumberland', TRUE),
(3579, 222, 'NOT', 'Nottinghamshire', TRUE),
(3580, 222, 'ORK', 'Orkney Islands', TRUE),
(3581, 222, 'OFE', 'Oxfordshire', TRUE),
(3582, 222, 'PEM', 'Pembrokeshire', TRUE),
(3583, 222, 'PERTH', 'Perth and Kinross', TRUE),
(3584, 222, 'PWS', 'Powys', TRUE),
(3585, 222, 'REN', 'Renfrewshire', TRUE),
(3586, 222, 'RHON', 'Rhondda Cynon Taff', TRUE),
(3587, 222, 'RUT', 'Rutland', TRUE),
(3588, 222, 'BOR', 'Scottish Borders', TRUE),
(3589, 222, 'SHET', 'Shetland Islands', TRUE),
(3590, 222, 'SPE', 'Shropshire', TRUE),
(3591, 222, 'SOM', 'Somerset', TRUE),
(3592, 222, 'ARYS', 'South Ayrshire', TRUE),
(3593, 222, 'LANS', 'South Lanarkshire', TRUE),
(3594, 222, 'YSS', 'South Yorkshire', TRUE),
(3595, 222, 'SFD', 'Staffordshire', TRUE),
(3596, 222, 'STIR', 'Stirling', TRUE),
(3597, 222, 'SFK', 'Suffolk', TRUE),
(3598, 222, 'SRY', 'Surrey', TRUE),
(3599, 222, 'SWAN', 'Swansea', TRUE),
(3600, 222, 'TORF', 'Torfaen', TRUE),
(3601, 222, 'TWR', 'Tyne and Wear', TRUE),
(3602, 222, 'VGLAM', 'Vale of Glamorgan', TRUE),
(3603, 222, 'WARKS', 'Warwickshire', TRUE),
(3604, 222, 'WDUN', 'West Dunbartonshire', TRUE),
(3605, 222, 'WLOT', 'West Lothian', TRUE),
(3606, 222, 'WMD', 'West Midlands', TRUE),
(3607, 222, 'SXW', 'West Sussex', TRUE),
(3608, 222, 'YSW', 'West Yorkshire', TRUE),
(3609, 222, 'WIL', 'Western Isles', TRUE),
(3610, 222, 'WLT', 'Wiltshire', TRUE),
(3611, 222, 'WORCS', 'Worcestershire', TRUE),
(3612, 222, 'WRX', 'Wrexham', TRUE),
(3613, 223, 'AL', 'Alabama', TRUE),
(3614, 223, 'AK', 'Alaska', TRUE),
(3615, 223, 'AS', 'American Samoa', TRUE),
(3616, 223, 'AZ', 'Arizona', TRUE),
(3617, 223, 'AR', 'Arkansas', TRUE),
(3618, 223, 'AF', 'Armed Forces Africa', TRUE),
(3619, 223, 'AA', 'Armed Forces Americas', TRUE),
(3620, 223, 'AC', 'Armed Forces Canada', TRUE),
(3621, 223, 'AE', 'Armed Forces Europe', TRUE),
(3622, 223, 'AM', 'Armed Forces Middle East', TRUE),
(3623, 223, 'AP', 'Armed Forces Pacific', TRUE),
(3624, 223, 'CA', 'California', TRUE),
(3625, 223, 'CO', 'Colorado', TRUE),
(3626, 223, 'CT', 'Connecticut', TRUE),
(3627, 223, 'DE', 'Delaware', TRUE),
(3628, 223, 'DC', 'District of Columbia', TRUE),
(3629, 223, 'FM', 'Federated States Of Micronesia', TRUE),
(3630, 223, 'FL', 'Florida', TRUE),
(3631, 223, 'GA', 'Georgia', TRUE),
(3632, 223, 'GU', 'Guam', TRUE),
(3633, 223, 'HI', 'Hawaii', TRUE),
(3634, 223, 'ID', 'Idaho', TRUE),
(3635, 223, 'IL', 'Illinois', TRUE),
(3636, 223, 'IN', 'Indiana', TRUE),
(3637, 223, 'IA', 'Iowa', TRUE),
(3638, 223, 'KS', 'Kansas', TRUE),
(3639, 223, 'KY', 'Kentucky', TRUE),
(3640, 223, 'LA', 'Louisiana', TRUE),
(3641, 223, 'ME', 'Maine', TRUE),
(3642, 223, 'MH', 'Marshall Islands', TRUE),
(3643, 223, 'MD', 'Maryland', TRUE),
(3644, 223, 'MA', 'Massachusetts', TRUE),
(3645, 223, 'MI', 'Michigan', TRUE),
(3646, 223, 'MN', 'Minnesota', TRUE),
(3647, 223, 'MS', 'Mississippi', TRUE),
(3648, 223, 'MO', 'Missouri', TRUE),
(3649, 223, 'MT', 'Montana', TRUE),
(3650, 223, 'NE', 'Nebraska', TRUE),
(3651, 223, 'NV', 'Nevada', TRUE),
(3652, 223, 'NH', 'New Hampshire', TRUE),
(3653, 223, 'NJ', 'New Jersey', TRUE),
(3654, 223, 'NM', 'New Mexico', TRUE),
(3655, 223, 'NY', 'New York', TRUE),
(3656, 223, 'NC', 'North Carolina', TRUE),
(3657, 223, 'ND', 'North Dakota', TRUE),
(3658, 223, 'MP', 'Northern Mariana Islands', TRUE),
(3659, 223, 'OH', 'Ohio', TRUE),
(3660, 223, 'OK', 'Oklahoma', TRUE),
(3661, 223, 'OR', 'Oregon', TRUE),
(3662, 223, 'PW', 'Palau', TRUE),
(3663, 223, 'PA', 'Pennsylvania', TRUE),
(3664, 223, 'PR', 'Puerto Rico', TRUE),
(3665, 223, 'RI', 'Rhode Island', TRUE),
(3666, 223, 'SC', 'South Carolina', TRUE),
(3667, 223, 'SD', 'South Dakota', TRUE),
(3668, 223, 'TN', 'Tennessee', TRUE),
(3669, 223, 'TX', 'Texas', TRUE),
(3670, 223, 'UT', 'Utah', TRUE),
(3671, 223, 'VT', 'Vermont', TRUE),
(3672, 223, 'VI', 'Virgin Islands', TRUE),
(3673, 223, 'VA', 'Virginia', TRUE),
(3674, 223, 'WA', 'Washington', TRUE),
(3675, 223, 'WV', 'West Virginia', TRUE),
(3676, 223, 'WI', 'Wisconsin', TRUE),
(3677, 223, 'WY', 'Wyoming', TRUE),
(3678, 224, 'BI', 'Baker Island', TRUE),
(3679, 224, 'HI', 'Howland Island', TRUE),
(3680, 224, 'JI', 'Jarvis Island', TRUE),
(3681, 224, 'JA', 'Johnston Atoll', TRUE),
(3682, 224, 'KR', 'Kingman Reef', TRUE),
(3683, 224, 'MA', 'Midway Atoll', TRUE),
(3684, 224, 'NI', 'Navassa Island', TRUE),
(3685, 224, 'PA', 'Palmyra Atoll', TRUE),
(3686, 224, 'WI', 'Wake Island', TRUE),
(3687, 225, 'AR', 'Artigas', TRUE),
(3688, 225, 'CA', 'Canelones', TRUE),
(3689, 225, 'CL', 'Cerro Largo', TRUE),
(3690, 225, 'CO', 'Colonia', TRUE),
(3691, 225, 'DU', 'Durazno', TRUE),
(3692, 225, 'FS', 'Flores', TRUE),
(3693, 225, 'FA', 'Florida', TRUE),
(3694, 225, 'LA', 'Lavalleja', TRUE),
(3695, 225, 'MA', 'Maldonado', TRUE),
(3696, 225, 'MO', 'Montevideo', TRUE),
(3697, 225, 'PA', 'Paysandu', TRUE),
(3698, 225, 'RN', 'Rio Negro', TRUE),
(3699, 225, 'RV', 'Rivera', TRUE),
(3700, 225, 'RO', 'Rocha', TRUE),
(3701, 225, 'SL', 'Salto', TRUE),
(3702, 225, 'SJ', 'San Jose', TRUE),
(3703, 225, 'SO', 'Soriano', TRUE),
(3704, 225, 'TA', 'Tacuarembo', TRUE),
(3705, 225, 'TT', 'Treinta y Tres', TRUE),
(3706, 226, 'AN', 'Andijon', TRUE),
(3707, 226, 'BU', 'Buxoro', TRUE),
(3708, 226, 'FA', 'Farg''ona', TRUE),
(3709, 226, 'JI', 'Jizzax', TRUE),
(3710, 226, 'NG', 'Namangan', TRUE),
(3711, 226, 'NW', 'Navoiy', TRUE),
(3712, 226, 'QA', 'Qashqadaryo', TRUE),
(3713, 226, 'QR', 'Qoraqalpog''iston Republikasi', TRUE),
(3714, 226, 'SA', 'Samarqand', TRUE),
(3715, 226, 'SI', 'Sirdaryo', TRUE),
(3716, 226, 'SU', 'Surxondaryo', TRUE),
(3717, 226, 'TK', 'Toshkent City', TRUE),
(3718, 226, 'TO', 'Toshkent Region', TRUE),
(3719, 226, 'XO', 'Xorazm', TRUE),
(3720, 227, 'MA', 'Malampa', TRUE),
(3721, 227, 'PE', 'Penama', TRUE),
(3722, 227, 'SA', 'Sanma', TRUE),
(3723, 227, 'SH', 'Shefa', TRUE),
(3724, 227, 'TA', 'Tafea', TRUE),
(3725, 227, 'TO', 'Torba', TRUE),
(3726, 229, 'AM', 'Amazonas', TRUE),
(3727, 229, 'AN', 'Anzoategui', TRUE),
(3728, 229, 'AP', 'Apure', TRUE),
(3729, 229, 'AR', 'Aragua', TRUE),
(3730, 229, 'BA', 'Barinas', TRUE),
(3731, 229, 'BO', 'Bolivar', TRUE),
(3732, 229, 'CA', 'Carabobo', TRUE),
(3733, 229, 'CO', 'Cojedes', TRUE),
(3734, 229, 'DA', 'Delta Amacuro', TRUE),
(3735, 229, 'DF', 'Dependencias Federales', TRUE),
(3736, 229, 'DI', 'Distrito Federal', TRUE),
(3737, 229, 'FA', 'Falcon', TRUE),
(3738, 229, 'GU', 'Guarico', TRUE),
(3739, 229, 'LA', 'Lara', TRUE),
(3740, 229, 'ME', 'Merida', TRUE),
(3741, 229, 'MI', 'Miranda', TRUE),
(3742, 229, 'MO', 'Monagas', TRUE),
(3743, 229, 'NE', 'Nueva Esparta', TRUE),
(3744, 229, 'PO', 'Portuguesa', TRUE),
(3745, 229, 'SU', 'Sucre', TRUE),
(3746, 229, 'TA', 'Tachira', TRUE),
(3747, 229, 'TR', 'Trujillo', TRUE),
(3748, 229, 'VA', 'Vargas', TRUE),
(3749, 229, 'YA', 'Yaracuy', TRUE),
(3750, 229, 'ZU', 'Zulia', TRUE),
(3751, 230, 'AG', 'An Giang', TRUE),
(3752, 230, 'BG', 'Bac Giang', TRUE),
(3753, 230, 'BK', 'Bac Kan', TRUE),
(3754, 230, 'BL', 'Bac Lieu', TRUE),
(3755, 230, 'BC', 'Bac Ninh', TRUE),
(3756, 230, 'BR', 'Ba Ria-Vung Tau', TRUE),
(3757, 230, 'BN', 'Ben Tre', TRUE),
(3758, 230, 'BH', 'Binh Dinh', TRUE),
(3759, 230, 'BU', 'Binh Duong', TRUE),
(3760, 230, 'BP', 'Binh Phuoc', TRUE),
(3761, 230, 'BT', 'Binh Thuan', TRUE),
(3762, 230, 'CM', 'Ca Mau', TRUE),
(3763, 230, 'CT', 'Can Tho', TRUE),
(3764, 230, 'CB', 'Cao Bang', TRUE),
(3765, 230, 'DL', 'Dak Lak', TRUE),
(3766, 230, 'DG', 'Dak Nong', TRUE),
(3767, 230, 'DN', 'Da Nang', TRUE),
(3768, 230, 'DB', 'Dien Bien', TRUE),
(3769, 230, 'DI', 'Dong Nai', TRUE),
(3770, 230, 'DT', 'Dong Thap', TRUE),
(3771, 230, 'GL', 'Gia Lai', TRUE),
(3772, 230, 'HG', 'Ha Giang', TRUE),
(3773, 230, 'HD', 'Hai Duong', TRUE),
(3774, 230, 'HP', 'Hai Phong', TRUE),
(3775, 230, 'HM', 'Ha Nam', TRUE),
(3776, 230, 'HI', 'Ha Noi', TRUE),
(3777, 230, 'HT', 'Ha Tay', TRUE),
(3778, 230, 'HH', 'Ha Tinh', TRUE),
(3779, 230, 'HB', 'Hoa Binh', TRUE),
(3780, 230, 'HC', 'Ho Chi Minh City', TRUE),
(3781, 230, 'HU', 'Hau Giang', TRUE),
(3782, 230, 'HY', 'Hung Yen', TRUE),
(3783, 232, 'C', 'Saint Croix', TRUE),
(3784, 232, 'J', 'Saint John', TRUE),
(3785, 232, 'T', 'Saint Thomas', TRUE),
(3786, 233, 'A', 'Alo', TRUE),
(3787, 233, 'S', 'Sigave', TRUE),
(3788, 233, 'W', 'Wallis', TRUE),
(3789, 235, 'AB', 'Abyan', TRUE),
(3790, 235, 'AD', 'Adan', TRUE),
(3791, 235, 'AM', 'Amran', TRUE),
(3792, 235, 'BA', 'Al Bayda', TRUE),
(3793, 235, 'DA', 'Ad Dali', TRUE),
(3794, 235, 'DH', 'Dhamar', TRUE),
(3795, 235, 'HD', 'Hadramawt', TRUE),
(3796, 235, 'HJ', 'Hajjah', TRUE),
(3797, 235, 'HU', 'Al Hudaydah', TRUE),
(3798, 235, 'IB', 'Ibb', TRUE),
(3799, 235, 'JA', 'Al Jawf', TRUE),
(3800, 235, 'LA', 'Lahij', TRUE),
(3801, 235, 'MA', 'Ma''rib', TRUE),
(3802, 235, 'MR', 'Al Mahrah', TRUE),
(3803, 235, 'MW', 'Al Mahwit', TRUE),
(3804, 235, 'SD', 'Sa''dah', TRUE),
(3805, 235, 'SN', 'San''a', TRUE),
(3806, 235, 'SH', 'Shabwah', TRUE),
(3807, 235, 'TA', 'Ta''izz', TRUE),
(3812, 237, 'BC', 'Bas-Congo', TRUE),
(3813, 237, 'BN', 'Bandundu', TRUE),
(3814, 237, 'EQ', 'Equateur', TRUE),
(3815, 237, 'KA', 'Katanga', TRUE),
(3816, 237, 'KE', 'Kasai-Oriental', TRUE),
(3817, 237, 'KN', 'Kinshasa', TRUE),
(3818, 237, 'KW', 'Kasai-Occidental', TRUE),
(3819, 237, 'MA', 'Maniema', TRUE),
(3820, 237, 'NK', 'Nord-Kivu', TRUE),
(3821, 237, 'OR', 'Orientale', TRUE),
(3822, 237, 'SK', 'Sud-Kivu', TRUE),
(3823, 238, 'CE', 'Central', TRUE),
(3824, 238, 'CB', 'Copperbelt', TRUE),
(3825, 238, 'EA', 'Eastern', TRUE),
(3826, 238, 'LP', 'Luapula', TRUE),
(3827, 238, 'LK', 'Lusaka', TRUE),
(3828, 238, 'NO', 'Northern', TRUE),
(3829, 238, 'NW', 'North-Western', TRUE),
(3830, 238, 'SO', 'Southern', TRUE),
(3831, 238, 'WE', 'Western', TRUE),
(3832, 239, 'BU', 'Bulawayo', TRUE),
(3833, 239, 'HA', 'Harare', TRUE),
(3834, 239, 'ML', 'Manicaland', TRUE),
(3835, 239, 'MC', 'Mashonaland Central', TRUE),
(3836, 239, 'ME', 'Mashonaland East', TRUE),
(3837, 239, 'MW', 'Mashonaland West', TRUE),
(3838, 239, 'MV', 'Masvingo', TRUE),
(3839, 239, 'MN', 'Matabeleland North', TRUE),
(3840, 239, 'MS', 'Matabeleland South', TRUE),
(3841, 239, 'MD', 'Midlands', TRUE),
(3861, 105, 'CB', 'Campobasso', TRUE),
(3862, 105, 'CI', 'Carbonia-Iglesias', TRUE),
(3863, 105, 'CE', 'Caserta', TRUE),
(3864, 105, 'CT', 'Catania', TRUE),
(3865, 105, 'CZ', 'Catanzaro', TRUE),
(3866, 105, 'CH', 'Chieti', TRUE),
(3867, 105, 'CO', 'Como', TRUE),
(3868, 105, 'CS', 'Cosenza', TRUE),
(3869, 105, 'CR', 'Cremona', TRUE),
(3870, 105, 'KR', 'Crotone', TRUE),
(3871, 105, 'CN', 'Cuneo', TRUE),
(3872, 105, 'EN', 'Enna', TRUE),
(3873, 105, 'FE', 'Ferrara', TRUE),
(3874, 105, 'FI', 'Firenze', TRUE),
(3875, 105, 'FG', 'Foggia', TRUE),
(3876, 105, 'FC', 'Forli-Cesena', TRUE),
(3877, 105, 'FR', 'Frosinone', TRUE),
(3878, 105, 'GE', 'Genova', TRUE),
(3879, 105, 'GO', 'Gorizia', TRUE),
(3880, 105, 'GR', 'Grosseto', TRUE),
(3881, 105, 'IM', 'Imperia', TRUE),
(3882, 105, 'IS', 'Isernia', TRUE),
(3883, 105, 'AQ', 'L&#39;Aquila', TRUE),
(3884, 105, 'SP', 'La Spezia', TRUE),
(3885, 105, 'LT', 'Latina', TRUE),
(3886, 105, 'LE', 'Lecce', TRUE),
(3887, 105, 'LC', 'Lecco', TRUE),
(3888, 105, 'LI', 'Livorno', TRUE),
(3889, 105, 'LO', 'Lodi', TRUE),
(3890, 105, 'LU', 'Lucca', TRUE),
(3891, 105, 'MC', 'Macerata', TRUE),
(3892, 105, 'MN', 'Mantova', TRUE),
(3893, 105, 'MS', 'Massa-Carrara', TRUE),
(3894, 105, 'MT', 'Matera', TRUE),
(3895, 105, 'VS', 'Medio Campidano', TRUE),
(3896, 105, 'ME', 'Messina', TRUE),
(3897, 105, 'MI', 'Milano', TRUE),
(3898, 105, 'MO', 'Modena', TRUE),
(3899, 105, 'NA', 'Napoli', TRUE),
(3900, 105, 'NO', 'Novara', TRUE),
(3901, 105, 'NU', 'Nuoro', TRUE),
(3902, 105, 'OG', 'Ogliastra', TRUE),
(3903, 105, 'OT', 'Olbia-Tempio', TRUE),
(3904, 105, 'OR', 'Oristano', TRUE),
(3905, 105, 'PD', 'Padova', TRUE),
(3906, 105, 'PA', 'Palermo', TRUE),
(3907, 105, 'PR', 'Parma', TRUE),
(3908, 105, 'PV', 'Pavia', TRUE),
(3909, 105, 'PG', 'Perugia', TRUE),
(3910, 105, 'PU', 'Pesaro e Urbino', TRUE),
(3911, 105, 'PE', 'Pescara', TRUE),
(3912, 105, 'PC', 'Piacenza', TRUE),
(3913, 105, 'PI', 'Pisa', TRUE),
(3914, 105, 'PT', 'Pistoia', TRUE),
(3915, 105, 'PN', 'Pordenone', TRUE),
(3916, 105, 'PZ', 'Potenza', TRUE),
(3917, 105, 'PO', 'Prato', TRUE),
(3918, 105, 'RG', 'Ragusa', TRUE),
(3919, 105, 'RA', 'Ravenna', TRUE),
(3920, 105, 'RC', 'Reggio Calabria', TRUE),
(3921, 105, 'RE', 'Reggio Emilia', TRUE),
(3922, 105, 'RI', 'Rieti', TRUE),
(3923, 105, 'RN', 'Rimini', TRUE),
(3924, 105, 'RM', 'Roma', TRUE),
(3925, 105, 'RO', 'Rovigo', TRUE),
(3926, 105, 'SA', 'Salerno', TRUE),
(3927, 105, 'SS', 'Sassari', TRUE),
(3928, 105, 'SV', 'Savona', TRUE),
(3929, 105, 'SI', 'Siena', TRUE),
(3930, 105, 'SR', 'Siracusa', TRUE),
(3931, 105, 'SO', 'Sondrio', TRUE),
(3932, 105, 'TA', 'Taranto', TRUE),
(3933, 105, 'TE', 'Teramo', TRUE),
(3934, 105, 'TR', 'Terni', TRUE),
(3935, 105, 'TO', 'Torino', TRUE),
(3936, 105, 'TP', 'Trapani', TRUE),
(3937, 105, 'TN', 'Trento', TRUE),
(3938, 105, 'TV', 'Treviso', TRUE),
(3939, 105, 'TS', 'Trieste', TRUE),
(3940, 105, 'UD', 'Udine', TRUE),
(3941, 105, 'VA', 'Varese', TRUE),
(3942, 105, 'VE', 'Venezia', TRUE),
(3943, 105, 'VB', 'Verbano-Cusio-Ossola', TRUE),
(3944, 105, 'VC', 'Vercelli', TRUE),
(3945, 105, 'VR', 'Verona', TRUE),
(3946, 105, 'VV', 'Vibo Valentia', TRUE),
(3947, 105, 'VI', 'Vicenza', TRUE),
(3948, 105, 'VT', 'Viterbo', TRUE),
(3949, 222, 'ANT', 'County Antrim', TRUE),
(3950, 222, 'ARM', 'County Armagh', TRUE),
(3951, 222, 'DOW', 'County Down', TRUE),
(3952, 222, 'FER', 'County Fermanagh', TRUE),
(3953, 222, 'LDY', 'County Londonderry', TRUE),
(3954, 222, 'TYR', 'County Tyrone', TRUE),
(3955, 222, 'CMA', 'Cumbria', TRUE),
(3956, 190, '1', 'Pomurska', TRUE),
(3957, 190, '2', 'Podravska', TRUE),
(3958, 190, '3', 'Koroška', TRUE),
(3959, 190, '4', 'Savinjska', TRUE),
(3960, 190, '5', 'Zasavska', TRUE),
(3961, 190, '6', 'Spodnjeposavska', TRUE),
(3962, 190, '7', 'Jugovzhodna Slovenija', TRUE),
(3963, 190, '8', 'Osrednjeslovenska', TRUE),
(3964, 190, '9', 'Gorenjska', TRUE),
(3965, 190, '10', 'Notranjsko-kraška', TRUE),
(3966, 190, '11', 'Goriška', TRUE),
(3967, 190, '12', 'Obalno-kraška', TRUE),
(3968, 33, '', 'Ruse', TRUE),
(3969, 101, 'ALB', 'Alborz', TRUE),
(3970, 21, 'BRU', 'Brussels-Capital Region', TRUE),
(3971, 138, 'AG', 'Aguascalientes', TRUE),
(3972, 222, 'IOM', 'Isle of Man', TRUE),
(3973, 242, '01', 'Andrijevica', TRUE),
(3974, 242, '02', 'Bar', TRUE),
(3975, 242, '03', 'Berane', TRUE),
(3976, 242, '04', 'Bijelo Polje', TRUE),
(3977, 242, '05', 'Budva', TRUE),
(3978, 242, '06', 'Cetinje', TRUE),
(3979, 242, '07', 'Danilovgrad', TRUE),
(3980, 242, '08', 'Herceg-Novi', TRUE),
(3981, 242, '09', 'Kolašin', TRUE),
(3982, 242, '10', 'Kotor', TRUE),
(3983, 242, '11', 'Mojkovac', TRUE),
(3984, 242, '12', 'Nikšić', TRUE),
(3985, 242, '13', 'Plav', TRUE),
(3986, 242, '14', 'Pljevlja', TRUE),
(3987, 242, '15', 'Plužine', TRUE),
(3988, 242, '16', 'Podgorica', TRUE),
(3989, 242, '17', 'Rožaje', TRUE),
(3990, 242, '18', 'Šavnik', TRUE),
(3991, 242, '19', 'Tivat', TRUE),
(3992, 242, '20', 'Ulcinj', TRUE),
(3993, 242, '21', 'Žabljak', TRUE),
(3994, 243, '00', 'Belgrade', TRUE),
(3995, 243, '01', 'North Bačka', TRUE),
(3996, 243, '02', 'Central Banat', TRUE),
(3997, 243, '03', 'North Banat', TRUE),
(3998, 243, '04', 'South Banat', TRUE),
(3999, 243, '05', 'West Bačka', TRUE),
(4000, 243, '06', 'South Bačka', TRUE),
(4001, 243, '07', 'Srem', TRUE),
(4002, 243, '08', 'Mačva', TRUE),
(4003, 243, '09', 'Kolubara', TRUE),
(4004, 243, '10', 'Podunavlje', TRUE),
(4005, 243, '11', 'Braničevo', TRUE),
(4006, 243, '12', 'Šumadija', TRUE),
(4007, 243, '13', 'Pomoravlje', TRUE),
(4008, 243, '14', 'Bor', TRUE),
(4009, 243, '15', 'Zaječar', TRUE),
(4010, 243, '16', 'Zlatibor', TRUE),
(4011, 243, '17', 'Moravica', TRUE),
(4012, 243, '18', 'Raška', TRUE),
(4013, 243, '19', 'Rasina', TRUE),
(4014, 243, '20', 'Nišava', TRUE),
(4015, 243, '21', 'Toplica', TRUE),
(4016, 243, '22', 'Pirot', TRUE),
(4017, 243, '23', 'Jablanica', TRUE),
(4018, 243, '24', 'Pčinja', TRUE),
(4019, 243, 'KM', 'Kosovo', TRUE),
(4020, 245, 'BO', 'Bonaire', TRUE),
(4021, 245, 'SA', 'Saba', TRUE),
(4022, 245, 'SE', 'Sint Eustatius', TRUE),
(4023, 248, 'EC', 'Central Equatoria', TRUE),
(4024, 248, 'EE', 'Eastern Equatoria', TRUE),
(4025, 248, 'JG', 'Jonglei', TRUE),
(4026, 248, 'LK', 'Lakes', TRUE),
(4027, 248, 'BN', 'Northern Bahr el-Ghazal', TRUE),
(4028, 248, 'UY', 'Unity', TRUE),
(4029, 248, 'NU', 'Upper Nile', TRUE),
(4030, 248, 'WR', 'Warrap', TRUE),
(4031, 248, 'BW', 'Western Bahr el-Ghazal', TRUE),
(4032, 248, 'EW', 'Western Equatoria', TRUE),
(4033, 222, 'GGY', 'Guernsey', TRUE),
(4034, 222, 'JEY', 'Jersey', TRUE),
(4036,117,  '0661405', 'Ainaži, Salacgrīvas novads', TRUE),
(4037,117,  '0320201', 'Aizkraukle, Aizkraukles novads', TRUE),
(4038,117,  '0320200', 'Aizkraukles novads', TRUE),
(4039,117,  '0640605', 'Aizpute, Aizputes novads', TRUE),
(4040,117,  '0640600', 'Aizputes novads', TRUE),
(4041,117,  '0560805', 'Aknīste, Aknīstes novads', TRUE),
(4042,117,  '0560800', 'Aknīstes novads', TRUE),
(4043,117,  '0661007', 'Aloja, Alojas novads', TRUE),
(4044,117,  '0661000', 'Alojas novads', TRUE),
(4045,117,  '0624200', 'Alsungas novads', TRUE),
(4046,117,  '0360201', 'Alūksne, Alūksnes novads', TRUE),
(4047,117,  '0360200', 'Alūksnes novads', TRUE),
(4048,117,  '0424701', 'Amatas novads', TRUE),
(4049,117,  '0360805', 'Ape, Apes novads', TRUE),
(4050,117,  '0360800', 'Apes novads', TRUE),
(4051,117,  '0460805', 'Auce, Auces novads', TRUE),
(4052,117,  '0460800', 'Auces novads', TRUE),
(4053,117,  '0804400', 'Ādažu novads', TRUE),
(4054,117,  '0804900', 'Babītes novads', TRUE),
(4055,117,  '0800605', 'Baldone, Baldones novads', TRUE),
(4056,117,  '0800600', 'Baldones novads', TRUE),
(4057,117,  '0800807', 'Baloži, Ķekavas novads', TRUE),
(4058,117,  '0384400', 'Baltinavas novads', TRUE),
(4059,117,  '0380201', 'Balvi, Balvu novads', TRUE),
(4060,117,  '0380200', 'Balvu novads', TRUE),
(4061,117,  '0400201', 'Bauska, Bauskas novads', TRUE),
(4062,117,  '0400200', 'Bauskas novads', TRUE),
(4063,117,  '0964700', 'Beverīnas novads', TRUE),
(4064,117,  '0840605', 'Brocēni, Brocēnu novads', TRUE),
(4065,117,  '0840601', 'Brocēnu novads', TRUE),
(4066,117,  '0967101', 'Burtnieku novads', TRUE),
(4067,117,  '0805200', 'Carnikavas novads', TRUE),
(4068,117,  '0700807', 'Cesvaine, Cesvaines novads', TRUE),
(4069,117,  '0700800', 'Cesvaines novads', TRUE),
(4070,117,  '0420201', 'Cēsis, Cēsu novads', TRUE),
(4071,117,  '0420200', 'Cēsu novads', TRUE),
(4072,117,  '0684901', 'Ciblas novads', TRUE),
(4073,117,  '0601009', 'Dagda, Dagdas novads', TRUE),
(4074,117,  '0601000', 'Dagdas novads', TRUE),
(4075,117,  '0050000', 'Daugavpils', TRUE),
(4076,117,  '0440200', 'Daugavpils novads', TRUE),
(4077,117,  '0460201', 'Dobele, Dobeles novads', TRUE),
(4078,117,  '0460200', 'Dobeles novads', TRUE),
(4079,117,  '0885100', 'Dundagas novads', TRUE),
(4080,117,  '0640807', 'Durbe, Durbes novads', TRUE),
(4081,117,  '0640801', 'Durbes novads', TRUE),
(4082,117,  '0905100', 'Engures novads', TRUE),
(4083,117,  '0705500', 'Ērgļu novads', TRUE),
(4084,117,  '0806000', 'Garkalnes novads', TRUE),
(4085,117,  '0641009', 'Grobiņa, Grobiņas novads', TRUE),
(4086,117,  '0641000', 'Grobiņas novads', TRUE),
(4087,117,  '0500201', 'Gulbene, Gulbenes novads', TRUE),
(4088,117,  '0500200', 'Gulbenes novads', TRUE),
(4089,117,  '0406400', 'Iecavas novads', TRUE),
(4090,117,  '0740605', 'Ikšķile, Ikšķiles novads', TRUE),
(4091,117,  '0740600', 'Ikšķiles novads', TRUE),
(4092,117,  '0440807', 'Ilūkste, Ilūkstes novads', TRUE),
(4093,117,  '0440801', 'Ilūkstes novads', TRUE),
(4094,117,  '0801800', 'Inčukalna novads', TRUE),
(4095,117,  '0321007', 'Jaunjelgava, Jaunjelgavas novads', TRUE),
(4096,117,  '0321000', 'Jaunjelgavas novads', TRUE),
(4097,117,  '0425700', 'Jaunpiebalgas novads', TRUE),
(4098,117,  '0905700', 'Jaunpils novads', TRUE),
(4099,117,  '0090000', 'Jelgava', TRUE),
(4100,117,  '0540200', 'Jelgavas novads', TRUE),
(4101,117,  '0110000', 'Jēkabpils', TRUE),
(4102,117,  '0560200', 'Jēkabpils novads', TRUE),
(4103,117,  '0130000', 'Jūrmala', TRUE),
(4104,117,  '0540211', 'Kalnciems, Jelgavas novads', TRUE),
(4105,117,  '0901211', 'Kandava, Kandavas novads', TRUE),
(4106,117,  '0901201', 'Kandavas novads', TRUE),
(4107,117,  '0681009', 'Kārsava, Kārsavas novads', TRUE),
(4108,117,  '0681000', 'Kārsavas novads', TRUE),
(4109,117,  '0960200', 'Kocēnu novads ,bij. Valmieras)', TRUE),
(4110,117,  '0326100', 'Kokneses novads', TRUE),
(4111,117,  '0600201', 'Krāslava, Krāslavas novads', TRUE),
(4112,117,  '0600202', 'Krāslavas novads', TRUE),
(4113,117,  '0806900', 'Krimuldas novads', TRUE),
(4114,117,  '0566900', 'Krustpils novads', TRUE),
(4115,117,  '0620201', 'Kuldīga, Kuldīgas novads', TRUE),
(4116,117,  '0620200', 'Kuldīgas novads', TRUE),
(4117,117,  '0741001', 'Ķeguma novads', TRUE),
(4118,117,  '0741009', 'Ķegums, Ķeguma novads', TRUE),
(4119,117,  '0800800', 'Ķekavas novads', TRUE),
(4120,117,  '0741413', 'Lielvārde, Lielvārdes novads', TRUE),
(4121,117,  '0741401', 'Lielvārdes novads', TRUE),
(4122,117,  '0170000', 'Liepāja', TRUE),
(4123,117,  '0660201', 'Limbaži, Limbažu novads', TRUE),
(4124,117,  '0660200', 'Limbažu novads', TRUE),
(4125,117,  '0421211', 'Līgatne, Līgatnes novads', TRUE),
(4126,117,  '0421200', 'Līgatnes novads', TRUE),
(4127,117,  '0761211', 'Līvāni, Līvānu novads', TRUE),
(4128,117,  '0761201', 'Līvānu novads', TRUE),
(4129,117,  '0701413', 'Lubāna, Lubānas novads', TRUE),
(4130,117,  '0701400', 'Lubānas novads', TRUE),
(4131,117,  '0680201', 'Ludza, Ludzas novads', TRUE),
(4132,117,  '0680200', 'Ludzas novads', TRUE),
(4133,117,  '0700201', 'Madona, Madonas novads', TRUE),
(4134,117,  '0700200', 'Madonas novads', TRUE),
(4135,117,  '0961011', 'Mazsalaca, Mazsalacas novads', TRUE),
(4136,117,  '0961000', 'Mazsalacas novads', TRUE),
(4137,117,  '0807400', 'Mālpils novads', TRUE),
(4138,117,  '0807600', 'Mārupes novads', TRUE),
(4139,117,  '0887600', 'Mērsraga novads', TRUE),
(4140,117,  '0967300', 'Naukšēnu novads', TRUE),
(4141,117,  '0327100', 'Neretas novads', TRUE),
(4142,117,  '0647900', 'Nīcas novads', TRUE),
(4143,117,  '0740201', 'Ogre, Ogres novads', TRUE),
(4144,117,  '0740202', 'Ogres novads', TRUE),
(4145,117,  '0801009', 'Olaine, Olaines novads', TRUE),
(4146,117,  '0801000', 'Olaines novads', TRUE),
(4147,117,  '0546701', 'Ozolnieku novads', TRUE),
(4148,117,  '0427500', 'Pārgaujas novads', TRUE),
(4149,117,  '0641413', 'Pāvilosta, Pāvilostas novads', TRUE),
(4150,117,  '0641401', 'Pāvilostas novads', TRUE),
(4151,117,  '0980213', 'Piltene, Ventspils novads', TRUE),
(4152,117,  '0321413', 'Pļaviņas, Pļaviņu novads', TRUE),
(4153,117,  '0321400', 'Pļaviņu novads', TRUE),
(4154,117,  '0760201', 'Preiļi, Preiļu novads', TRUE),
(4155,117,  '0760202', 'Preiļu novads', TRUE),
(4156,117,  '0641615', 'Priekule, Priekules novads', TRUE),
(4157,117,  '0641600', 'Priekules novads', TRUE),
(4158,117,  '0427300', 'Priekuļu novads', TRUE),
(4159,117,  '0427700', 'Raunas novads', TRUE),
(4160,117,  '0210000', 'Rēzekne', TRUE),
(4161,117,  '0780200', 'Rēzeknes novads', TRUE),
(4162,117,  '0766300', 'Riebiņu novads', TRUE),
(4163,117,  '0010000', 'Rīga', TRUE),
(4164,117,  '0888300', 'Rojas novads', TRUE),
(4165,117,  '0808400', 'Ropažu novads', TRUE),
(4166,117,  '0648500', 'Rucavas novads', TRUE),
(4167,117,  '0387500', 'Rugāju novads', TRUE),
(4168,117,  '0407700', 'Rundāles novads', TRUE),
(4169,117,  '0961615', 'Rūjiena, Rūjienas novads', TRUE),
(4170,117,  '0961600', 'Rūjienas novads', TRUE),
(4171,117,  '0880213', 'Sabile, Talsu novads', TRUE),
(4172,117,  '0661415', 'Salacgrīva, Salacgrīvas novads', TRUE),
(4173,117,  '0661400', 'Salacgrīvas novads', TRUE),
(4174,117,  '0568700', 'Salas novads', TRUE),
(4175,117,  '0801200', 'Salaspils novads', TRUE),
(4176,117,  '0801211', 'Salaspils, Salaspils novads', TRUE),
(4177,117,  '0840200', 'Saldus novads', TRUE),
(4178,117,  '0840201', 'Saldus, Saldus novads', TRUE),
(4179,117,  '0801413', 'Saulkrasti, Saulkrastu novads', TRUE),
(4180,117,  '0801400', 'Saulkrastu novads', TRUE),
(4181,117,  '0941813', 'Seda, Strenču novads', TRUE),
(4182,117,  '0809200', 'Sējas novads', TRUE),
(4183,117,  '0801615', 'Sigulda, Siguldas novads', TRUE),
(4184,117,  '0801601', 'Siguldas novads', TRUE),
(4185,117,  '0328200', 'Skrīveru novads', TRUE),
(4186,117,  '0621209', 'Skrunda, Skrundas novads', TRUE),
(4187,117,  '0621200', 'Skrundas novads', TRUE),
(4188,117,  '0941615', 'Smiltene, Smiltenes novads', TRUE),
(4189,117,  '0941600', 'Smiltenes novads', TRUE),
(4190,117,  '0661017', 'Staicele, Alojas novads', TRUE),
(4191,117,  '0880215', 'Stende, Talsu novads', TRUE),
(4192,117,  '0809600', 'Stopiņu novads', TRUE),
(4193,117,  '0941817', 'Strenči, Strenču novads', TRUE),
(4194,117,  '0941800', 'Strenču novads', TRUE),
(4195,117,  '0440815', 'Subate, Ilūkstes novads', TRUE),
(4196,117,  '0880201', 'Talsi, Talsu novads', TRUE),
(4197,117,  '0880200', 'Talsu novads', TRUE),
(4198,117,  '0468900', 'Tērvetes novads', TRUE),
(4199,117,  '0900200', 'Tukuma novads', TRUE),
(4200,117,  '0900201', 'Tukums, Tukuma novads', TRUE),
(4201,117,  '0649300', 'Vaiņodes novads', TRUE),
(4202,117,  '0880217', 'Valdemārpils, Talsu novads', TRUE),
(4203,117,  '0940201', 'Valka, Valkas novads', TRUE),
(4204,117,  '0940200', 'Valkas novads', TRUE),
(4205,117,  '0250000', 'Valmiera', TRUE),
(4206,117,  '0801817', 'Vangaži, Inčukalna novads', TRUE),
(4207,117,  '0701817', 'Varakļāni, Varakļānu novads', TRUE),
(4208,117,  '0701800', 'Varakļānu novads', TRUE),
(4209,117,  '0769101', 'Vārkavas novads', TRUE),
(4210,117,  '0429300', 'Vecpiebalgas novads', TRUE),
(4211,117,  '0409500', 'Vecumnieku novads', TRUE),
(4212,117,  '0270000', 'Ventspils', TRUE),
(4213,117,  '0980200', 'Ventspils novads', TRUE),
(4214,117,  '0561815', 'Viesīte, Viesītes novads', TRUE),
(4215,117,  '0561800', 'Viesītes novads', TRUE),
(4216,117,  '0381615', 'Viļaka, Viļakas novads', TRUE),
(4217,117,  '0381600', 'Viļakas novads', TRUE),
(4218,117,  '0781817', 'Viļāni, Viļānu novads', TRUE),
(4219,117,  '0781800', 'Viļānu novads', TRUE),
(4220,117,  '0681817', 'Zilupe, Zilupes novads', TRUE),
(4221,117,  '0681801', 'Zilupes novads', TRUE);

-- --------------------------------------------------------

--
-- Table structure for table oc_zone_to_geo_zone
--

DROP TABLE IF EXISTS oc_zone_to_geo_zone;
CREATE TABLE oc_zone_to_geo_zone (
  zone_to_geo_zone_id serial NOT NULL,
  country_id integer NOT NULL,
  zone_id integer NOT NULL DEFAULT '0',
  geo_zone_id integer NOT NULL,
  date_added timestamp without time zone NULL DEFAULT NULL,
  date_modified timestamp without time zone NULL DEFAULT NULL,
  PRIMARY KEY (zone_to_geo_zone_id)
);

--
-- Dumping data for table oc_zone_to_geo_zone
--

INSERT INTO oc_zone_to_geo_zone (zone_to_geo_zone_id, country_id, zone_id, geo_zone_id) VALUES
(1, 222, 0, 4),
(2, 222, 3513, 3),
(3, 222, 3514, 3),
(4, 222, 3515, 3),
(5, 222, 3516, 3),
(6, 222, 3517, 3),
(7, 222, 3518, 3),
(8, 222, 3519, 3),
(9, 222, 3520, 3),
(10, 222, 3521, 3),
(11, 222, 3522, 3),
(12, 222, 3523, 3),
(13, 222, 3524, 3),
(14, 222, 3525, 3),
(15, 222, 3526, 3),
(16, 222, 3527, 3),
(17, 222, 3528, 3),
(18, 222, 3529, 3),
(19, 222, 3530, 3),
(20, 222, 3531, 3),
(21, 222, 3532, 3),
(22, 222, 3533, 3),
(23, 222, 3534, 3),
(24, 222, 3535, 3),
(25, 222, 3536, 3),
(26, 222, 3537, 3),
(27, 222, 3538, 3),
(28, 222, 3539, 3),
(29, 222, 3540, 3),
(30, 222, 3541, 3),
(31, 222, 3542, 3),
(32, 222, 3543, 3),
(33, 222, 3544, 3),
(34, 222, 3545, 3),
(35, 222, 3546, 3),
(36, 222, 3547, 3),
(37, 222, 3548, 3),
(38, 222, 3549, 3),
(39, 222, 3550, 3),
(40, 222, 3551, 3),
(41, 222, 3552, 3),
(42, 222, 3553, 3),
(43, 222, 3554, 3),
(44, 222, 3555, 3),
(45, 222, 3556, 3),
(46, 222, 3557, 3),
(47, 222, 3558, 3),
(48, 222, 3559, 3),
(49, 222, 3560, 3),
(50, 222, 3561, 3),
(51, 222, 3562, 3),
(52, 222, 3563, 3),
(53, 222, 3564, 3),
(54, 222, 3565, 3),
(55, 222, 3566, 3),
(56, 222, 3567, 3),
(57, 222, 3568, 3),
(58, 222, 3569, 3),
(59, 222, 3570, 3),
(60, 222, 3571, 3),
(61, 222, 3572, 3),
(62, 222, 3573, 3),
(63, 222, 3574, 3),
(64, 222, 3575, 3),
(65, 222, 3576, 3),
(66, 222, 3577, 3),
(67, 222, 3578, 3),
(68, 222, 3579, 3),
(69, 222, 3580, 3),
(70, 222, 3581, 3),
(71, 222, 3582, 3),
(72, 222, 3583, 3),
(73, 222, 3584, 3),
(74, 222, 3585, 3),
(75, 222, 3586, 3),
(76, 222, 3587, 3),
(77, 222, 3588, 3),
(78, 222, 3589, 3),
(79, 222, 3590, 3),
(80, 222, 3591, 3),
(81, 222, 3592, 3),
(82, 222, 3593, 3),
(83, 222, 3594, 3),
(84, 222, 3595, 3),
(85, 222, 3596, 3),
(86, 222, 3597, 3),
(87, 222, 3598, 3),
(88, 222, 3599, 3),
(89, 222, 3600, 3),
(90, 222, 3601, 3),
(91, 222, 3602, 3),
(92, 222, 3603, 3),
(93, 222, 3604, 3),
(94, 222, 3605, 3),
(95, 222, 3606, 3),
(96, 222, 3607, 3),
(97, 222, 3608, 3),
(98, 222, 3609, 3),
(99, 222, 3610, 3),
(100, 222, 3611, 3),
(101, 222, 3612, 3),
(102, 222, 3949, 3),
(103, 222, 3950, 3),
(104, 222, 3951, 3),
(105, 222, 3952, 3),
(106, 222, 3953, 3),
(107, 222, 3954, 3),
(108, 222, 3955, 3),
(109, 222, 3972, 3);
