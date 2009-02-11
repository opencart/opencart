#
# TABLE STRUCTURE FOR: `address`
#

DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `address_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL default '0',
  `company` varchar(32) collate utf8_unicode_ci default NULL,
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `address_2` varchar(64) collate utf8_unicode_ci default NULL,
  `postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`address_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `address` (`address_id`, `customer_id`, `company`, `firstname`, `lastname`, `address_1`, `address_2`, `postcode`, `city`, `country_id`, `zone_id`) VALUES ('17', '11', 'OpenCart', 'Daniel', 'Kerr', '34 Lancaster Ave', '', 'FY5 4NN', 'Thornton-Cleveleys', '222', '3563');


#
# TABLE STRUCTURE FOR: `category`
#

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(11) NOT NULL auto_increment,
  `image_id` int(11) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `sort_order` int(3) default '0',
  `date_added` datetime default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('21', '33', '0', '5', '2009-01-05 21:50:02', '2009-01-20 00:05:41');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('19', '33', '0', '3', '2009-01-05 21:49:29', '2009-01-05 21:49:29');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('20', '33', '0', '4', '2009-01-05 21:49:43', '2009-01-20 00:05:25');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('24', '34', '18', '1', '2009-01-20 02:36:26', '2009-01-20 02:36:26');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('18', '33', '0', '2', '2009-01-05 21:49:15', '2009-01-05 21:49:15');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('17', '33', '0', '1', '2009-01-03 21:08:57', '2009-01-19 02:22:44');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('23', '34', '0', '7', '2009-01-20 00:05:06', '2009-01-20 00:05:06');
INSERT INTO `category` (`category_id`, `image_id`, `parent_id`, `sort_order`, `date_added`, `date_modified`) VALUES ('22', '33', '0', '6', '2009-01-05 21:54:20', '2009-01-20 00:05:54');


#
# TABLE STRUCTURE FOR: `category_description`
#

DROP TABLE IF EXISTS `category_description`;
CREATE TABLE `category_description` (
  `category_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`category_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('18', '4', 'Category 2');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('19', '1', 'Category 3');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('19', '4', 'Category 3');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('20', '1', 'Category 4');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('21', '1', 'Category 5');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('22', '1', 'Category 6');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('24', '1', 'Category 8');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('23', '1', 'Category 7');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('17', '1', 'Category 1');
INSERT INTO `category_description` (`category_id`, `language_id`, `name`) VALUES ('18', '1', 'Category 2');


#
# TABLE STRUCTURE FOR: `country`
#

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `country_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `iso_code_2` varchar(2) collate utf8_unicode_ci NOT NULL default '',
  `iso_code_3` varchar(3) collate utf8_unicode_ci NOT NULL default '',
  `address_format` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`country_id`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('1', 'Afghanistan', 'AF', 'AFG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('2', 'Albania', 'AL', 'ALB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('3', 'Algeria', 'DZ', 'DZA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('4', 'American Samoa', 'AS', 'ASM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('5', 'Andorra', 'AD', 'AND', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('6', 'Angola', 'AO', 'AGO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('7', 'Anguilla', 'AI', 'AIA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('8', 'Antarctica', 'AQ', 'ATA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('9', 'Antigua and Barbuda', 'AG', 'ATG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('10', 'Argentina', 'AR', 'ARG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('11', 'Armenia', 'AM', 'ARM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('12', 'Aruba', 'AW', 'ABW', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('13', 'Australia', 'AU', 'AUS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('14', 'Austria', 'AT', 'AUT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('15', 'Azerbaijan', 'AZ', 'AZE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('16', 'Bahamas', 'BS', 'BHS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('17', 'Bahrain', 'BH', 'BHR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('18', 'Bangladesh', 'BD', 'BGD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('19', 'Barbados', 'BB', 'BRB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('20', 'Belarus', 'BY', 'BLR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('21', 'Belgium', 'BE', 'BEL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('22', 'Belize', 'BZ', 'BLZ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('23', 'Benin', 'BJ', 'BEN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('24', 'Bermuda', 'BM', 'BMU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('25', 'Bhutan', 'BT', 'BTN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('26', 'Bolivia', 'BO', 'BOL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('27', 'Bosnia and Herzegowina', 'BA', 'BIH', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('28', 'Botswana', 'BW', 'BWA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('29', 'Bouvet Island', 'BV', 'BVT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('30', 'Brazil', 'BR', 'BRA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('31', 'British Indian Ocean Territory', 'IO', 'IOT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('32', 'Brunei Darussalam', 'BN', 'BRN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('33', 'Bulgaria', 'BG', 'BGR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('34', 'Burkina Faso', 'BF', 'BFA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('35', 'Burundi', 'BI', 'BDI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('36', 'Cambodia', 'KH', 'KHM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('37', 'Cameroon', 'CM', 'CMR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('38', 'Canada', 'CA', 'CAN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('39', 'Cape Verde', 'CV', 'CPV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('40', 'Cayman Islands', 'KY', 'CYM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('41', 'Central African Republic', 'CF', 'CAF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('42', 'Chad', 'TD', 'TCD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('43', 'Chile', 'CL', 'CHL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('44', 'China', 'CN', 'CHN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('45', 'Christmas Island', 'CX', 'CXR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('46', 'Cocos (Keeling) Islands', 'CC', 'CCK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('47', 'Colombia', 'CO', 'COL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('48', 'Comoros', 'KM', 'COM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('49', 'Congo', 'CG', 'COG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('50', 'Cook Islands', 'CK', 'COK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('51', 'Costa Rica', 'CR', 'CRI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('52', 'Cote D\'Ivoire', 'CI', 'CIV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('53', 'Croatia', 'HR', 'HRV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('54', 'Cuba', 'CU', 'CUB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('55', 'Cyprus', 'CY', 'CYP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('56', 'Czech Republic', 'CZ', 'CZE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('57', 'Denmark', 'DK', 'DNK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('58', 'Djibouti', 'DJ', 'DJI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('59', 'Dominica', 'DM', 'DMA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('60', 'Dominican Republic', 'DO', 'DOM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('61', 'East Timor', 'TP', 'TMP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('62', 'Ecuador', 'EC', 'ECU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('63', 'Egypt', 'EG', 'EGY', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('64', 'El Salvador', 'SV', 'SLV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('65', 'Equatorial Guinea', 'GQ', 'GNQ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('66', 'Eritrea', 'ER', 'ERI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('67', 'Estonia', 'EE', 'EST', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('68', 'Ethiopia', 'ET', 'ETH', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('69', 'Falkland Islands (Malvinas)', 'FK', 'FLK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('70', 'Faroe Islands', 'FO', 'FRO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('71', 'Fiji', 'FJ', 'FJI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('72', 'Finland', 'FI', 'FIN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('73', 'France', 'FR', 'FRA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('74', 'France, Metropolitan', 'FX', 'FXX', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('75', 'French Guiana', 'GF', 'GUF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('76', 'French Polynesia', 'PF', 'PYF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('77', 'French Southern Territories', 'TF', 'ATF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('78', 'Gabon', 'GA', 'GAB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('79', 'Gambia', 'GM', 'GMB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('80', 'Georgia', 'GE', 'GEO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('81', 'Germany', 'DE', 'DEU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('82', 'Ghana', 'GH', 'GHA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('83', 'Gibraltar', 'GI', 'GIB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('84', 'Greece', 'GR', 'GRC', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('85', 'Greenland', 'GL', 'GRL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('86', 'Grenada', 'GD', 'GRD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('87', 'Guadeloupe', 'GP', 'GLP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('88', 'Guam', 'GU', 'GUM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('89', 'Guatemala', 'GT', 'GTM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('90', 'Guinea', 'GN', 'GIN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('91', 'Guinea-bissau', 'GW', 'GNB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('92', 'Guyana', 'GY', 'GUY', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('93', 'Haiti', 'HT', 'HTI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('94', 'Heard and Mc Donald Islands', 'HM', 'HMD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('95', 'Honduras', 'HN', 'HND', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('96', 'Hong Kong', 'HK', 'HKG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('97', 'Hungary', 'HU', 'HUN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('98', 'Iceland', 'IS', 'ISL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('99', 'India', 'IN', 'IND', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('100', 'Indonesia', 'ID', 'IDN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('101', 'Iran (Islamic Republic of)', 'IR', 'IRN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('102', 'Iraq', 'IQ', 'IRQ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('103', 'Ireland', 'IE', 'IRL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('104', 'Israel', 'IL', 'ISR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('105', 'Italy', 'IT', 'ITA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('106', 'Jamaica', 'JM', 'JAM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('107', 'Japan', 'JP', 'JPN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('108', 'Jordan', 'JO', 'JOR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('109', 'Kazakhstan', 'KZ', 'KAZ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('110', 'Kenya', 'KE', 'KEN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('111', 'Kiribati', 'KI', 'KIR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('112', 'Korea, Democratic People\'s Republic of', 'KP', 'PRK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('113', 'Korea, Republic of', 'KR', 'KOR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('114', 'Kuwait', 'KW', 'KWT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('115', 'Kyrgyzstan', 'KG', 'KGZ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('116', 'Lao People\'s Democratic Republic', 'LA', 'LAO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('117', 'Latvia', 'LV', 'LVA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('118', 'Lebanon', 'LB', 'LBN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('119', 'Lesotho', 'LS', 'LSO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('120', 'Liberia', 'LR', 'LBR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('121', 'Libyan Arab Jamahiriya', 'LY', 'LBY', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('122', 'Liechtenstein', 'LI', 'LIE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('123', 'Lithuania', 'LT', 'LTU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('124', 'Luxembourg', 'LU', 'LUX', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('125', 'Macau', 'MO', 'MAC', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('126', 'Macedonia, The Former Yugoslav Republic of', 'MK', 'MKD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('127', 'Madagascar', 'MG', 'MDG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('128', 'Malawi', 'MW', 'MWI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('129', 'Malaysia', 'MY', 'MYS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('130', 'Maldives', 'MV', 'MDV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('131', 'Mali', 'ML', 'MLI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('132', 'Malta', 'MT', 'MLT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('133', 'Marshall Islands', 'MH', 'MHL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('134', 'Martinique', 'MQ', 'MTQ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('135', 'Mauritania', 'MR', 'MRT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('136', 'Mauritius', 'MU', 'MUS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('137', 'Mayotte', 'YT', 'MYT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('138', 'Mexico', 'MX', 'MEX', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('139', 'Micronesia, Federated States of', 'FM', 'FSM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('140', 'Moldova, Republic of', 'MD', 'MDA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('141', 'Monaco', 'MC', 'MCO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('142', 'Mongolia', 'MN', 'MNG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('143', 'Montserrat', 'MS', 'MSR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('144', 'Morocco', 'MA', 'MAR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('145', 'Mozambique', 'MZ', 'MOZ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('146', 'Myanmar', 'MM', 'MMR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('147', 'Namibia', 'NA', 'NAM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('148', 'Nauru', 'NR', 'NRU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('149', 'Nepal', 'NP', 'NPL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('150', 'Netherlands', 'NL', 'NLD', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('151', 'Netherlands Antilles', 'AN', 'ANT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('152', 'New Caledonia', 'NC', 'NCL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('153', 'New Zealand', 'NZ', 'NZL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('154', 'Nicaragua', 'NI', 'NIC', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('155', 'Niger', 'NE', 'NER', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('156', 'Nigeria', 'NG', 'NGA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('157', 'Niue', 'NU', 'NIU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('158', 'Norfolk Island', 'NF', 'NFK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('159', 'Northern Mariana Islands', 'MP', 'MNP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('160', 'Norway', 'NO', 'NOR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('161', 'Oman', 'OM', 'OMN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('162', 'Pakistan', 'PK', 'PAK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('163', 'Palau', 'PW', 'PLW', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('164', 'Panama', 'PA', 'PAN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('165', 'Papua New Guinea', 'PG', 'PNG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('166', 'Paraguay', 'PY', 'PRY', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('167', 'Peru', 'PE', 'PER', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('168', 'Philippines', 'PH', 'PHL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('169', 'Pitcairn', 'PN', 'PCN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('170', 'Poland', 'PL', 'POL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('171', 'Portugal', 'PT', 'PRT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('172', 'Puerto Rico', 'PR', 'PRI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('173', 'Qatar', 'QA', 'QAT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('174', 'Reunion', 'RE', 'REU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('175', 'Romania', 'RO', 'ROM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('176', 'Russian Federation', 'RU', 'RUS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('177', 'Rwanda', 'RW', 'RWA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('178', 'Saint Kitts and Nevis', 'KN', 'KNA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('179', 'Saint Lucia', 'LC', 'LCA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('180', 'Saint Vincent and the Grenadines', 'VC', 'VCT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('181', 'Samoa', 'WS', 'WSM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('182', 'San Marino', 'SM', 'SMR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('183', 'Sao Tome and Principe', 'ST', 'STP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('184', 'Saudi Arabia', 'SA', 'SAU', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('185', 'Senegal', 'SN', 'SEN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('186', 'Seychelles', 'SC', 'SYC', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('187', 'Sierra Leone', 'SL', 'SLE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('188', 'Singapore', 'SG', 'SGP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('189', 'Slovakia (Slovak Republic)', 'SK', 'SVK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('190', 'Slovenia', 'SI', 'SVN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('191', 'Solomon Islands', 'SB', 'SLB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('192', 'Somalia', 'SO', 'SOM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('193', 'South Africa', 'ZA', 'ZAF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('194', 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('195', 'Spain', 'ES', 'ESP', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('196', 'Sri Lanka', 'LK', 'LKA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('197', 'St. Helena', 'SH', 'SHN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('198', 'St. Pierre and Miquelon', 'PM', 'SPM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('199', 'Sudan', 'SD', 'SDN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('200', 'Suriname', 'SR', 'SUR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('201', 'Svalbard and Jan Mayen Islands', 'SJ', 'SJM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('202', 'Swaziland', 'SZ', 'SWZ', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('203', 'Sweden', 'SE', 'SWE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('204', 'Switzerland', 'CH', 'CHE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('205', 'Syrian Arab Republic', 'SY', 'SYR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('206', 'Taiwan', 'TW', 'TWN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('207', 'Tajikistan', 'TJ', 'TJK', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('208', 'Tanzania, United Republic of', 'TZ', 'TZA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('209', 'Thailand', 'TH', 'THA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('210', 'Togo', 'TG', 'TGO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('211', 'Tokelau', 'TK', 'TKL', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('212', 'Tonga', 'TO', 'TON', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('213', 'Trinidad and Tobago', 'TT', 'TTO', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('214', 'Tunisia', 'TN', 'TUN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('215', 'Turkey', 'TR', 'TUR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('216', 'Turkmenistan', 'TM', 'TKM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('217', 'Turks and Caicos Islands', 'TC', 'TCA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('218', 'Tuvalu', 'TV', 'TUV', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('219', 'Uganda', 'UG', 'UGA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('220', 'Ukraine', 'UA', 'UKR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('221', 'United Arab Emirates', 'AE', 'ARE', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('222', 'United Kingdom', 'GB', 'GBR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('223', 'United States', 'US', 'USA', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('224', 'United States Minor Outlying Islands', 'UM', 'UMI', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('225', 'Uruguay', 'UY', 'URY', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('226', 'Uzbekistan', 'UZ', 'UZB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('227', 'Vanuatu', 'VU', 'VUT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('228', 'Vatican City State (Holy See)', 'VA', 'VAT', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('229', 'Venezuela', 'VE', 'VEN', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('230', 'Viet Nam', 'VN', 'VNM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('231', 'Virgin Islands (British)', 'VG', 'VGB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('232', 'Virgin Islands (U.S.)', 'VI', 'VIR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('233', 'Wallis and Futuna Islands', 'WF', 'WLF', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('234', 'Western Sahara', 'EH', 'ESH', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('235', 'Yemen', 'YE', 'YEM', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('236', 'Yugoslavia', 'YU', 'YUG', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('237', 'Zaire', 'ZR', 'ZAR', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('238', 'Zambia', 'ZM', 'ZMB', '');
INSERT INTO `country` (`country_id`, `name`, `iso_code_2`, `iso_code_3`, `address_format`) VALUES ('239', 'Zimbabwe', 'ZW', 'ZWE', '');


#
# TABLE STRUCTURE FOR: `coupon`
#

DROP TABLE IF EXISTS `coupon`;
CREATE TABLE `coupon` (
  `coupon_id` int(11) NOT NULL auto_increment,
  `code` varchar(10) collate utf8_unicode_ci NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  `prefix` varchar(1) collate utf8_unicode_ci NOT NULL,
  `shipping` int(1) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `uses_total` int(11) NOT NULL,
  `uses_customer` varchar(11) collate utf8_unicode_ci NOT NULL,
  `status` int(1) NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`coupon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



#
# TABLE STRUCTURE FOR: `coupon_description`
#

DROP TABLE IF EXISTS `coupon_description`;
CREATE TABLE `coupon_description` (
  `coupon_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `name` varchar(128) collate utf8_unicode_ci NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`coupon_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



#
# TABLE STRUCTURE FOR: `coupon_redeem`
#

DROP TABLE IF EXISTS `coupon_redeem`;
CREATE TABLE `coupon_redeem` (
  `coupon_redeem_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `coupon_id` int(11) NOT NULL,
  PRIMARY KEY  (`coupon_redeem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('1', '5', '46', '2008-11-07 21:17:51', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('2', '5', '1', '2008-11-07 21:36:21', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('3', '5', '2', '2008-11-07 21:37:14', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('4', '5', '3', '2008-11-07 21:37:48', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('5', '5', '1', '2008-11-07 21:39:03', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('6', '5', '62', '2008-12-09 11:31:10', '0');
INSERT INTO `coupon_redeem` (`coupon_redeem_id`, `customer_id`, `order_id`, `date_added`, `coupon_id`) VALUES ('7', '5', '0', '2008-12-09 11:33:51', '1');


#
# TABLE STRUCTURE FOR: `currency`
#

DROP TABLE IF EXISTS `currency`;
CREATE TABLE `currency` (
  `currency_id` int(11) NOT NULL auto_increment,
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `code` varchar(3) collate utf8_unicode_ci NOT NULL default '',
  `symbol_left` varchar(12) collate utf8_unicode_ci default NULL,
  `symbol_right` varchar(12) collate utf8_unicode_ci default NULL,
  `decimal_place` char(1) collate utf8_unicode_ci default NULL,
  `value` float(13,8) default NULL,
  `status` int(1) NOT NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`currency_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`) VALUES ('1', 'Pound Sterling', 'GBP', '£', '', '2', '1.00000000', '1', '2009-01-12 01:05:22');
INSERT INTO `currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`) VALUES ('2', 'US Dollar', 'USD', '$', '', '2', '1.51359999', '1', '2009-01-12 01:06:17');
INSERT INTO `currency` (`currency_id`, `title`, `code`, `symbol_left`, `symbol_right`, `decimal_place`, `value`, `status`, `date_modified`) VALUES ('3', 'Euro', 'EUR', '€', '', '2', '1.12043822', '1', '2009-01-12 01:06:22');


#
# TABLE STRUCTURE FOR: `customer`
#

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL auto_increment,
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(40) collate utf8_unicode_ci NOT NULL default '',
  `cart` text collate utf8_unicode_ci NOT NULL,
  `newsletter` int(1) NOT NULL default '0',
  `address_id` int(11) NOT NULL default '0',
  `status` int(1) NOT NULL,
  `ip` varchar(15) collate utf8_unicode_ci default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `customer` (`customer_id`, `firstname`, `lastname`, `email`, `telephone`, `fax`, `password`, `cart`, `newsletter`, `address_id`, `status`, `ip`, `date_added`) VALUES ('11', 'Daniel', 'Kerr', 'webmaster@opencart.com', '07853 474792', '', '4edfc924721abb774d5447bade86ea5d', 'a:1:{s:6:"17:439";s:1:"1";}', '1', '17', '1', '87.81.199.5', '2009-01-03 22:14:44');


#
# TABLE STRUCTURE FOR: `download`
#

DROP TABLE IF EXISTS `download`;
CREATE TABLE `download` (
  `download_id` int(11) NOT NULL auto_increment,
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `mask` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `remaining` int(11) NOT NULL default '0',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `download` (`download_id`, `filename`, `mask`, `remaining`, `date_added`) VALUES ('1', 'download_1.txt', 'download_1.txt', '100', '2009-01-09 15:58:56');


#
# TABLE STRUCTURE FOR: `download_description`
#

DROP TABLE IF EXISTS `download_description`;
CREATE TABLE `download_description` (
  `download_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`download_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `download_description` (`download_id`, `language_id`, `name`) VALUES ('1', '1', 'Download 1');
INSERT INTO `download_description` (`download_id`, `language_id`, `name`) VALUES ('1', '4', 'Download 1');


#
# TABLE STRUCTURE FOR: `extension`
#

DROP TABLE IF EXISTS `extension`;
CREATE TABLE `extension` (
  `extension_id` int(11) NOT NULL auto_increment,
  `type` varchar(32) collate utf8_unicode_ci NOT NULL,
  `key` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`extension_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('19', 'shipping', 'flat');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('2', 'shipping', 'item');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('3', 'shipping', 'zone');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('4', 'payment', 'cod');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('5', 'payment', 'paypal');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('14', 'total', 'coupon');
INSERT INTO `extension` (`extension_id`, `type`, `key`) VALUES ('15', 'total', 'shipping');


#
# TABLE STRUCTURE FOR: `geo_zone`
#

DROP TABLE IF EXISTS `geo_zone`;
CREATE TABLE `geo_zone` (
  `geo_zone_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_modified` datetime NOT NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`geo_zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `geo_zone` (`geo_zone_id`, `name`, `description`, `date_modified`, `date_added`) VALUES ('3', 'UK VAT Zone', 'UK VAT', '0000-00-00 00:00:00', '2009-01-06 23:26:25');


#
# TABLE STRUCTURE FOR: `image`
#

DROP TABLE IF EXISTS `image`;
CREATE TABLE `image` (
  `image_id` int(11) NOT NULL auto_increment,
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`image_id`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('1', 'Winter.jpg', '2008-07-06 23:36:03');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('6', '', '2008-07-23 23:33:02');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('13', '', '2008-08-01 15:36:52');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('14', '', '2008-08-01 16:05:41');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('23', '', '2008-08-03 20:11:16');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('35', 'wii.jpg', '2009-01-20 21:34:08');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('34', 'laptop_1.jpg', '2009-01-13 19:20:21');
INSERT INTO `image` (`image_id`, `filename`, `date_added`) VALUES ('33', 'no_image.jpg', '2009-01-03 20:59:05');


#
# TABLE STRUCTURE FOR: `image_description`
#

DROP TABLE IF EXISTS `image_description`;
CREATE TABLE `image_description` (
  `image_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`image_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `image_description` (`image_id`, `language_id`, `title`) VALUES ('35', '1', 'Wii');
INSERT INTO `image_description` (`image_id`, `language_id`, `title`) VALUES ('34', '1', 'Laptop 1');
INSERT INTO `image_description` (`image_id`, `language_id`, `title`) VALUES ('33', '4', 'No Image');
INSERT INTO `image_description` (`image_id`, `language_id`, `title`) VALUES ('33', '1', 'No Image');


#
# TABLE STRUCTURE FOR: `information`
#

DROP TABLE IF EXISTS `information`;
CREATE TABLE `information` (
  `information_id` int(11) NOT NULL auto_increment,
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`information_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `information` (`information_id`, `sort_order`) VALUES ('3', '2');
INSERT INTO `information` (`information_id`, `sort_order`) VALUES ('4', '1');
INSERT INTO `information` (`information_id`, `sort_order`) VALUES ('5', '3');


#
# TABLE STRUCTURE FOR: `information_description`
#

DROP TABLE IF EXISTS `information_description`;
CREATE TABLE `information_description` (
  `information_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '0',
  `title` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`information_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `information_description` (`information_id`, `language_id`, `title`, `description`) VALUES ('3', '1', 'Privacy Policy', '&lt;p&gt;Privacy Policy&lt;/p&gt;');
INSERT INTO `information_description` (`information_id`, `language_id`, `title`, `description`) VALUES ('4', '1', 'About Us', '&lt;p&gt;About Us&lt;/p&gt;');
INSERT INTO `information_description` (`information_id`, `language_id`, `title`, `description`) VALUES ('5', '1', 'Terms &amp; Conditions', '&lt;p&gt;Terms &amp;amp; Conditions&lt;/p&gt;');


#
# TABLE STRUCTURE FOR: `language`
#

DROP TABLE IF EXISTS `language`;
CREATE TABLE `language` (
  `language_id` int(11) NOT NULL auto_increment,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `code` varchar(5) collate utf8_unicode_ci NOT NULL,
  `locale` varchar(255) collate utf8_unicode_ci NOT NULL,
  `image` varchar(64) collate utf8_unicode_ci default NULL,
  `directory` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `filename` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `sort_order` int(3) default NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY  (`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `language` (`language_id`, `name`, `code`, `locale`, `image`, `directory`, `filename`, `sort_order`, `status`) VALUES ('1', 'English', 'en', 'en_US.UTF-8,en_US,en-gb,english', 'gb.png', 'english', 'english', '1', '1');


#
# TABLE STRUCTURE FOR: `manufacturer`
#

DROP TABLE IF EXISTS `manufacturer`;
CREATE TABLE `manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `image_id` int(11) NOT NULL default '0',
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`manufacturer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `manufacturer` (`manufacturer_id`, `name`, `image_id`, `sort_order`) VALUES ('5', 'Sony', '33', '1');


#
# TABLE STRUCTURE FOR: `newsletter`
#

DROP TABLE IF EXISTS `newsletter`;
CREATE TABLE `newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `content` text collate utf8_unicode_ci NOT NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_sent` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`newsletter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



#
# TABLE STRUCTURE FOR: `option`
#

DROP TABLE IF EXISTS `option`;
CREATE TABLE `option` (
  `option_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '0',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`option_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `option` (`option_id`, `language_id`, `name`) VALUES ('10', '4', 'Speed');
INSERT INTO `option` (`option_id`, `language_id`, `name`) VALUES ('10', '1', 'Speed');
INSERT INTO `option` (`option_id`, `language_id`, `name`) VALUES ('9', '1', 'Size');
INSERT INTO `option` (`option_id`, `language_id`, `name`) VALUES ('9', '4', 'Size');


#
# TABLE STRUCTURE FOR: `option_value`
#

DROP TABLE IF EXISTS `option_value`;
CREATE TABLE `option_value` (
  `option_value_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`option_value_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=148 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('3', '4', '9', 'Small');
INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('3', '1', '9', 'Small');
INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('2', '4', '9', 'Medium');
INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('2', '1', '9', 'Medium');
INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('1', '4', '9', 'Large');
INSERT INTO `option_value` (`option_value_id`, `language_id`, `option_id`, `name`) VALUES ('1', '1', '9', 'Large');


#
# TABLE STRUCTURE FOR: `order`
#

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL default '0',
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci default NULL,
  `telephone` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `fax` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `shipping_firstname` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `shipping_company` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `shipping_postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `shipping_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `shipping_country` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `shipping_address_format` text collate utf8_unicode_ci NOT NULL,
  `shipping_method` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `payment_firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_company` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_address_1` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `payment_address_2` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_city` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `payment_postcode` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `payment_zone` varchar(32) collate utf8_unicode_ci default NULL,
  `payment_country` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `payment_address_format` text collate utf8_unicode_ci NOT NULL,
  `payment_method` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `total` decimal(15,4) NOT NULL default '0.0000',
  `order_status_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `currency` varchar(3) collate utf8_unicode_ci NOT NULL,
  `value` decimal(15,4) NOT NULL,
  `date_modified` datetime default NULL,
  `date_added` datetime default NULL,
  `confirm` int(1) NOT NULL,
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('1', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '119.8500', '1', '1', '1', '', '1.0000', '2009-01-13 20:27:28', '2009-01-13 20:27:28', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('2', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '119.8500', '1', '1', '1', '', '1.0000', '2009-01-13 20:29:00', '2009-01-13 20:29:00', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('3', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '119.8500', '1', '1', '1', '', '1.0000', '2009-01-13 20:29:37', '2009-01-13 20:29:37', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('11', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '118.6750', '1', '1', '1', 'GBP', '1.0000', '2009-01-15 21:45:53', '2009-01-14 22:20:41', '1', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('10', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '118.6750', '1', '1', '1', 'GBP', '1.0000', '2009-01-14 18:28:59', '2009-01-14 18:28:59', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('9', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '118.6750', '1', '1', '1', 'GBP', '1.0000', '2009-01-14 17:28:18', '2009-01-14 17:28:18', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('8', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '118.6750', '1', '1', '1', 'GBP', '1.0000', '2009-01-14 17:28:13', '2009-01-14 17:28:13', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('12', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '119.8500', '1', '1', '1', 'GBP', '1.0000', '2009-01-15 21:46:17', '2009-01-15 00:35:49', '1', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('13', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '119.8500', '1', '1', '1', 'GBP', '1.0000', '2009-01-19 01:41:05', '2009-01-19 01:41:05', '0', '87.81.199.5');
INSERT INTO `order` (`order_id`, `customer_id`, `firstname`, `lastname`, `telephone`, `fax`, `email`, `shipping_firstname`, `shipping_lastname`, `shipping_company`, `shipping_address_1`, `shipping_address_2`, `shipping_city`, `shipping_postcode`, `shipping_zone`, `shipping_country`, `shipping_address_format`, `shipping_method`, `payment_firstname`, `payment_lastname`, `payment_company`, `payment_address_1`, `payment_address_2`, `payment_city`, `payment_postcode`, `payment_zone`, `payment_country`, `payment_address_format`, `payment_method`, `total`, `order_status_id`, `language_id`, `currency_id`, `currency`, `value`, `date_modified`, `date_added`, `confirm`, `ip`) VALUES ('14', '11', 'Daniel', 'Kerr', '07853 474792', '', 'webmaster@opencart.com', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Flat Shipping Rate', 'Daniel', 'Kerr', 'OpenCart', '34 Lancaster Ave', '', 'Thornton-Cleveleys', 'FY5 4NN', 'Lancashire', 'United Kingdom', '', 'Cash On Delivery', '118.6750', '1', '1', '1', 'GBP', '1.0000', '2009-01-20 22:58:07', '2009-01-20 22:58:07', '0', '87.81.199.5');


#
# TABLE STRUCTURE FOR: `order_download`
#

DROP TABLE IF EXISTS `order_download`;
CREATE TABLE `order_download` (
  `order_download_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_product_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `filename` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `mask` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  `remaining` int(3) NOT NULL default '0',
  PRIMARY KEY  (`order_download_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('1', '1', '1', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('2', '2', '2', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('3', '3', '3', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('11', '11', '11', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('10', '10', '10', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('9', '9', '9', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('8', '8', '8', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('12', '12', '12', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('13', '13', '13', 'Download 1', 'download_1.txt', 'download_1.txt', '100');
INSERT INTO `order_download` (`order_download_id`, `order_id`, `order_product_id`, `name`, `filename`, `mask`, `remaining`) VALUES ('14', '14', '14', 'Download 1', 'download_1.txt', 'download_1.txt', '100');


#
# TABLE STRUCTURE FOR: `order_history`
#

DROP TABLE IF EXISTS `order_history`;
CREATE TABLE `order_history` (
  `order_history_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_status_id` int(5) NOT NULL default '0',
  `notify` int(1) default '0',
  `comment` text collate utf8_unicode_ci,
  `date_added` datetime NOT NULL,
  PRIMARY KEY  (`order_history_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('1', '1', '1', '1', '', '2009-01-13 20:27:28');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('2', '2', '1', '1', '', '2009-01-13 20:29:00');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('3', '3', '1', '1', '', '2009-01-13 20:29:37');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('11', '11', '1', '1', '', '2009-01-14 22:20:41');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('10', '10', '1', '1', '', '2009-01-14 18:28:59');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('9', '9', '1', '1', '', '2009-01-14 17:28:18');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('8', '8', '1', '1', '', '2009-01-14 17:28:13');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('12', '12', '1', '1', '', '2009-01-15 00:35:49');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('13', '12', '1', '0', 'test', '2009-01-15 01:37:00');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('14', '12', '1', '1', 'test', '2009-01-15 01:37:53');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('15', '11', '1', '0', 'test', '2009-01-15 21:45:53');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('16', '12', '1', '1', 'test', '2009-01-15 21:46:17');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('17', '13', '1', '1', '', '2009-01-19 01:41:07');
INSERT INTO `order_history` (`order_history_id`, `order_id`, `order_status_id`, `notify`, `comment`, `date_added`) VALUES ('18', '14', '1', '1', '', '2009-01-20 22:58:07');


#
# TABLE STRUCTURE FOR: `order_option`
#

DROP TABLE IF EXISTS `order_option`;
CREATE TABLE `order_option` (
  `order_option_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `order_product_id` int(11) NOT NULL default '0',
  `name` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `value` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `prefix` char(1) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`order_option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_option` (`order_option_id`, `order_id`, `order_product_id`, `name`, `value`, `price`, `prefix`) VALUES ('1', '1', '1', 'Size', 'Small', '101.0000', '+');
INSERT INTO `order_option` (`order_option_id`, `order_id`, `order_product_id`, `name`, `value`, `price`, `prefix`) VALUES ('2', '2', '2', 'Size', 'Small', '101.0000', '+');
INSERT INTO `order_option` (`order_option_id`, `order_id`, `order_product_id`, `name`, `value`, `price`, `prefix`) VALUES ('3', '3', '3', 'Size', 'Small', '101.0000', '+');
INSERT INTO `order_option` (`order_option_id`, `order_id`, `order_product_id`, `name`, `value`, `price`, `prefix`) VALUES ('9', '13', '13', 'Size', 'Small', '101.0000', '+');
INSERT INTO `order_option` (`order_option_id`, `order_id`, `order_product_id`, `name`, `value`, `price`, `prefix`) VALUES ('8', '12', '12', 'Size', 'Small', '101.0000', '+');


#
# TABLE STRUCTURE FOR: `order_product`
#

DROP TABLE IF EXISTS `order_product`;
CREATE TABLE `order_product` (
  `order_product_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `model` varchar(12) collate utf8_unicode_ci NOT NULL default '',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `discount` decimal(15,4) NOT NULL,
  `total` decimal(15,4) NOT NULL default '0.0000',
  `tax` decimal(15,4) NOT NULL default '0.0000',
  `quantity` int(4) NOT NULL default '0',
  PRIMARY KEY  (`order_product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('1', '1', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'prod 1', '101.0000', '0.0000', '101.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('2', '2', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'prod 1', '101.0000', '0.0000', '101.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('3', '3', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'prod 1', '101.0000', '0.0000', '101.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('10', '10', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '100.0000', '0.0000', '100.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('9', '9', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '100.0000', '0.0000', '100.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('8', '8', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '100.0000', '0.0000', '100.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('11', '11', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '100.0000', '0.0000', '100.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('12', '12', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '101.0000', '0.0000', '101.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('13', '13', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '101.0000', '0.0000', '101.0000', '17.5000', '1');
INSERT INTO `order_product` (`order_product_id`, `order_id`, `name`, `model`, `price`, `discount`, `total`, `tax`, `quantity`) VALUES ('14', '14', 'HP 1GHz 1GB RAM 1GB HD Notebook', 'DT100/16GB', '100.0000', '0.0000', '100.0000', '17.5000', '1');


#
# TABLE STRUCTURE FOR: `order_status`
#

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE `order_status` (
  `order_status_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`order_status_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('1', '1', 'Pending');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('1', '4', 'Pending');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('2', '1', 'Processing');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('2', '4', 'Processing');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('3', '1', 'Shipped');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('3', '4', 'Shipped');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('4', '4', 'Cancelled');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('4', '1', 'Cancelled');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('5', '1', 'Complete');
INSERT INTO `order_status` (`order_status_id`, `language_id`, `name`) VALUES ('5', '4', 'Complete');


#
# TABLE STRUCTURE FOR: `order_total`
#

DROP TABLE IF EXISTS `order_total`;
CREATE TABLE `order_total` (
  `order_total_id` int(10) unsigned NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `text` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `value` decimal(15,4) NOT NULL default '0.0000',
  `sort_order` int(3) NOT NULL,
  PRIMARY KEY  (`order_total_id`),
  KEY `idx_orders_total_orders_id` (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('1', '1', 'Sub-Total:', '£118.68', '118.6750', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('2', '1', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('3', '1', 'VAT 17.5%:', '£17.68', '17.6750', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('4', '1', 'Total:', '£119.85', '119.8500', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('5', '2', 'Sub-Total:', '£118.68', '118.6750', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('6', '2', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('7', '2', 'VAT 17.5%:', '£17.68', '17.6750', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('8', '2', 'Total:', '£119.85', '119.8500', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('9', '3', 'Sub-Total:', '£118.68', '118.6750', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('10', '3', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('11', '3', 'VAT 17.5%:', '£17.68', '17.6750', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('12', '3', 'Total:', '£119.85', '119.8500', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('40', '10', 'Total:', '£118.68', '118.6750', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('39', '10', 'VAT 17.5%:', '£17.50', '17.5000', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('38', '10', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('37', '10', 'Sub-Total:', '£117.50', '117.5000', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('36', '9', 'Total:', '£118.68', '118.6750', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('35', '9', 'VAT 17.5%:', '£17.50', '17.5000', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('34', '9', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('33', '9', 'Sub-Total:', '£117.50', '117.5000', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('32', '8', 'Total:', '£118.68', '118.6750', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('31', '8', 'VAT 17.5%:', '£17.50', '17.5000', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('30', '8', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('29', '8', 'Sub-Total:', '£117.50', '117.5000', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('41', '11', 'Sub-Total:', '£117.50', '117.5000', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('42', '11', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('43', '11', 'VAT 17.5%:', '£17.50', '17.5000', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('44', '11', 'Total:', '£118.68', '118.6750', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('45', '12', 'Sub-Total:', '£118.68', '118.6750', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('46', '12', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('47', '12', 'VAT 17.5%:', '£17.68', '17.6750', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('48', '12', 'Total:', '£119.85', '119.8500', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('49', '13', 'Sub-Total:', '£118.68', '118.6750', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('50', '13', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('51', '13', 'VAT 17.5%:', '£17.68', '17.6750', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('52', '13', 'Total:', '£119.85', '119.8500', '3');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('53', '14', 'Sub-Total:', '£117.50', '117.5000', '0');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('54', '14', 'Flat Shipping Rate:', '£1.18', '1.1750', '1');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('55', '14', 'VAT 17.5%:', '£17.50', '17.5000', '2');
INSERT INTO `order_total` (`order_total_id`, `order_id`, `title`, `text`, `value`, `sort_order`) VALUES ('56', '14', 'Total:', '£118.68', '118.6750', '3');


#
# TABLE STRUCTURE FOR: `product`
#

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL auto_increment,
  `model` varchar(12) collate utf8_unicode_ci NOT NULL,
  `quantity` int(4) NOT NULL default '0',
  `stock_status_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `manufacturer_id` int(11) NOT NULL default '0',
  `shipping` int(1) NOT NULL default '1',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `tax_class_id` int(11) NOT NULL,
  `date_available` date default NULL,
  `weight` decimal(5,2) NOT NULL default '0.00',
  `weight_class_id` int(11) NOT NULL default '0',
  `sort_order` int(3) NOT NULL,
  `status` int(1) NOT NULL default '0',
  `date_added` datetime NOT NULL,
  `date_modified` datetime NOT NULL,
  `viewed` int(5) NOT NULL default '0',
  PRIMARY KEY  (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('26', 'Product 10', '0', '6', '34', '0', '0', '1.0000', '0', '2009-01-14', '0.00', '1', '1', '1', '2009-01-14 17:12:41', '2009-01-14 17:12:57', '5');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('24', 'PROD8', '0', '6', '34', '0', '0', '100.0000', '0', '2009-01-14', '0.00', '1', '0', '1', '2009-01-14 17:11:27', '0000-00-00 00:00:00', '5');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('23', 'PROD7', '0', '6', '33', '0', '0', '0.0000', '0', '2009-01-14', '0.00', '1', '0', '1', '2009-01-14 17:10:37', '2009-01-14 17:10:52', '3');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('17', 'DT100/16GB', '1', '6', '34', '5', '1', '100.0000', '9', '2009-01-03', '100.00', '1', '1', '1', '2009-01-03 21:57:26', '2009-01-20 13:49:14', '839');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('18', 'prod 2', '1', '6', '35', '5', '1', '100.0000', '0', '2009-01-05', '100.00', '1', '1', '1', '2009-01-05 16:59:44', '2009-01-20 21:34:36', '30');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('19', 'prod 3', '1', '7', '33', '0', '0', '200.0000', '0', '2009-01-11', '0.00', '1', '1', '1', '2009-01-11 01:58:13', '2009-01-11 02:44:11', '2');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('20', 'prod 4', '1', '5', '33', '0', '0', '50.0000', '0', '2009-01-11', '10.00', '1', '1', '1', '2009-01-11 02:43:29', '2009-01-11 02:44:18', '5');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('21', 'prod 5', '1', '6', '33', '0', '0', '25.0000', '0', '2009-01-11', '10.00', '1', '1', '1', '2009-01-11 02:46:29', '0000-00-00 00:00:00', '7');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('22', 'prod 6', '1', '7', '33', '0', '0', '5.0000', '0', '2009-01-11', '0.00', '1', '1', '1', '2009-01-11 02:58:44', '2009-01-13 14:36:39', '16');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('27', 'Product 11', '1', '6', '34', '0', '0', '1.0000', '0', '2009-01-14', '0.00', '1', '1', '1', '2009-01-14 17:13:22', '2009-01-14 17:13:41', '52');
INSERT INTO `product` (`product_id`, `model`, `quantity`, `stock_status_id`, `image_id`, `manufacturer_id`, `shipping`, `price`, `tax_class_id`, `date_available`, `weight`, `weight_class_id`, `sort_order`, `status`, `date_added`, `date_modified`, `viewed`) VALUES ('25', 'Product 9', '1', '6', '34', '0', '0', '1.0000', '0', '2009-01-14', '0.00', '1', '0', '1', '2009-01-14 17:12:06', '0000-00-00 00:00:00', '3');


#
# TABLE STRUCTURE FOR: `product_description`
#

DROP TABLE IF EXISTS `product_description`;
CREATE TABLE `product_description` (
  `product_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL default '1',
  `name` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci,
  PRIMARY KEY  (`product_id`,`language_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('18', '1', 'Product 2', '&lt;p&gt;Product 2&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('19', '1', 'Product 3', '&lt;p&gt;Product 3&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('20', '1', 'Product 4', '&lt;p&gt;Product 4&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('21', '1', 'Product 5', '&lt;p&gt;Product 5&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('22', '1', 'Product 6', '&lt;p&gt;Product 6&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('17', '1', 'HP 1GHz 1GB RAM 1GB HD Notebook', '&lt;p&gt;&lt;span style=&quot;font-weight: bold&quot;&gt;Features:&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;/span&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n    &lt;li&gt;Windows XP Pro&lt;/li&gt;\r\n    &lt;li&gt;1.0 GHz processor&lt;/li&gt;\r\n    &lt;li&gt;1GB RAM&lt;/li&gt;\r\n    &lt;li&gt;1GB flash memory&lt;/li&gt;\r\n&lt;/ul&gt;\r\n&lt;p&gt;&lt;span style=&quot;font-weight: bold&quot;&gt;&lt;br /&gt;\r\n&lt;/span&gt;&lt;span style=&quot;font-weight: bold&quot;&gt;Specifications:&lt;br /&gt;\r\n&lt;br /&gt;\r\n&lt;/span&gt;&lt;/p&gt;\r\n&lt;ul&gt;\r\n    &lt;li&gt;PROCESSOR : VIA C7-M ULV processor, 1.0 GHz, 128 KB L2 cache, 400 MHz FSB&lt;/li&gt;\r\n    &lt;li&gt;MEMORY : 1 GB (1 x 1024MB DIMM) 667-MHz RAM&lt;/li&gt;\r\n    &lt;li&gt;STORAGE :1 GB PATA Flash Module&lt;/li&gt;\r\n    &lt;li&gt;CHIPSET : VIA CN896 Northbridge and VT8237S Southbridge&lt;/li&gt;\r\n    &lt;li&gt;DISPLAY : 12.1&amp;quot; Wide WXGA RES : 1280 x 768&lt;/li&gt;\r\n    &lt;li&gt;OPERATING SYSTEM: Genuine Windows XP Pro Embedded&lt;/li&gt;\r\n    &lt;li&gt;COMM : Integrated Broadcom Ethernet PCI Controller (10/100 NIC), Broadcom 802.11abg&lt;/li&gt;\r\n    &lt;li&gt;GRAPHICS : VIA Chrome 9 integrated graphics, 128 MB shared system memory&lt;/li&gt;\r\n    &lt;li&gt;AUDIO : High Definition Audio support w/24-bit DAC; Integrated mono Speaker; Integrated microphone&lt;/li&gt;\r\n    &lt;li&gt;PORTS : USB 2.0, 1 x Stereo headphone/line out, 1 x Stereo microphone in, 1 x VGA, 1 x RJ-45/Ethernet&lt;/li&gt;\r\n    &lt;li&gt;SLOT : Secure Digital slot; Type I/II PC Card slot&lt;/li&gt;\r\n    &lt;li&gt;KEYBOARD : 101/102-key compatible keyboard&lt;/li&gt;\r\n    &lt;li&gt;POINTING DEVICE : Dual pointing devices (touchpad with scroll zone and pointstick)&lt;/li&gt;\r\n    &lt;li&gt;BATTERY : 6-cell Lithium-Ion battery&lt;/li&gt;\r\n    &lt;li&gt;DIMENSIONS (HxWxD) : 25.2(at front) x 282.3 x 214.3 mm&lt;/li&gt;\r\n    &lt;li&gt;WEIGHT : Starting from 1.36 kg&lt;/li&gt;\r\n&lt;/ul&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('23', '1', 'Product 7', '&lt;p&gt;Product 7&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('24', '1', 'Product 8', '&lt;p&gt;Product 8&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('25', '1', 'Product 9', '&lt;p&gt;Product 9&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('26', '1', 'Product 10', '&lt;p&gt;Product 10&lt;/p&gt;');
INSERT INTO `product_description` (`product_id`, `language_id`, `name`, `description`) VALUES ('27', '1', 'Product 11', '&lt;p&gt;Product 11&lt;/p&gt;');


#
# TABLE STRUCTURE FOR: `product_discount`
#

DROP TABLE IF EXISTS `product_discount`;
CREATE TABLE `product_discount` (
  `product_discount_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  `discount` decimal(15,4) NOT NULL,
  PRIMARY KEY  (`product_discount_id`)
) ENGINE=MyISAM AUTO_INCREMENT=260 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



#
# TABLE STRUCTURE FOR: `product_to_category`
#

DROP TABLE IF EXISTS `product_to_category`;
CREATE TABLE `product_to_category` (
  `product_id` int(11) NOT NULL default '0',
  `category_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('17', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('17', '18');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('18', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('18', '18');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('19', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('20', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('21', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('22', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('23', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('24', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('25', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('26', '17');
INSERT INTO `product_to_category` (`product_id`, `category_id`) VALUES ('27', '17');


#
# TABLE STRUCTURE FOR: `product_to_download`
#

DROP TABLE IF EXISTS `product_to_download`;
CREATE TABLE `product_to_download` (
  `product_id` int(11) NOT NULL default '0',
  `download_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`download_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product_to_download` (`product_id`, `download_id`) VALUES ('17', '1');


#
# TABLE STRUCTURE FOR: `product_to_image`
#

DROP TABLE IF EXISTS `product_to_image`;
CREATE TABLE `product_to_image` (
  `product_id` int(11) NOT NULL default '0',
  `image_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`product_id`,`image_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product_to_image` (`product_id`, `image_id`) VALUES ('17', '33');
INSERT INTO `product_to_image` (`product_id`, `image_id`) VALUES ('17', '34');


#
# TABLE STRUCTURE FOR: `product_to_option`
#

DROP TABLE IF EXISTS `product_to_option`;
CREATE TABLE `product_to_option` (
  `product_to_option_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',
  `option_value_id` int(11) NOT NULL default '0',
  `price` decimal(15,4) NOT NULL default '0.0000',
  `prefix` char(1) collate utf8_unicode_ci NOT NULL default '+',
  `sort_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`product_to_option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=445 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product_to_option` (`product_to_option_id`, `product_id`, `option_id`, `option_value_id`, `price`, `prefix`, `sort_order`) VALUES ('444', '17', '9', '3', '1.0000', '+', '1');
INSERT INTO `product_to_option` (`product_to_option_id`, `product_id`, `option_id`, `option_value_id`, `price`, `prefix`, `sort_order`) VALUES ('443', '17', '9', '2', '2.0000', '+', '2');
INSERT INTO `product_to_option` (`product_to_option_id`, `product_id`, `option_id`, `option_value_id`, `price`, `prefix`, `sort_order`) VALUES ('442', '17', '9', '1', '3.0000', '+', '3');


#
# TABLE STRUCTURE FOR: `review`
#

DROP TABLE IF EXISTS `review`;
CREATE TABLE `review` (
  `review_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `customer_id` int(11) NOT NULL default '0',
  `author` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `text` text collate utf8_unicode_ci NOT NULL,
  `rating` int(1) default NULL,
  `status` int(1) NOT NULL default '0',
  `date_added` datetime default NULL,
  `date_modified` datetime default NULL,
  PRIMARY KEY  (`review_id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('37', '17', '0', 'test', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '3', '1', '2009-01-23 01:53:09', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('38', '17', '0', 'test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '4', '1', '2009-01-23 01:53:31', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('39', '17', '0', 'test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '3', '1', '2009-01-23 01:53:48', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('40', '17', '0', 'test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test ', '3', '1', '2009-01-23 01:53:58', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('41', '17', '0', 'test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '3', '1', '2009-01-23 01:54:13', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('42', '17', '0', 'test ', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '4', '1', '2009-01-23 01:54:27', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('43', '17', '0', ' ', '', '1', '0', '2009-01-23 02:15:00', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('44', '17', '0', ' ', '', '1', '0', '2009-01-23 02:15:32', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('45', '17', '0', ' ', '', '1', '0', '2009-01-23 02:16:04', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('46', '17', '0', ' ', '', '1', '0', '2009-01-23 02:17:53', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('47', '17', '0', ' ', '6yuty yj jj hghjff f hgfj ghjf jfgj ', '1', '0', '2009-01-23 02:20:10', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('48', '17', '0', ' Daniel', 'This product is crap don&#039;t buy it ever!', '1', '0', '2009-01-23 02:22:13', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('49', '17', '0', ' ', 'gh ghf hdfgd fdh gd hhj hfjh gjfgj ghjf hfj jghjf hjryu ytuytuytu', '0', '0', '2009-01-23 02:26:51', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('50', '17', '0', ' ', 'fhg fghfgh fghdf hgf hfdh fdhf hgfh dfh gdhghy jyhfj ', '0', '0', '2009-01-23 02:28:10', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('51', '17', '0', 'trtr', 'tyrt yty ytr yrt ytry rty t yrt yrt', '0', '0', '2009-01-23 16:47:07', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('52', '17', '0', 'Daniel', 'test test test test test test test test test test ', '5', '0', '2009-01-23 17:13:13', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('53', '17', '0', 'fgdfgdfg', 'fdgfdg g hhf hhn bnvbn bnv n c', '2', '0', '2009-01-23 17:20:42', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('54', '17', '0', 'hghf hg', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test ', '4', '0', '2009-01-23 19:18:10', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('55', '17', '0', 'Daniel', 'test test test test test test test test test test test test test test test test test test test test test test test test test', '2', '0', '2009-01-24 03:07:26', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('56', '17', '0', 'Daniel', 'test test test test test test test test test test test test test test test test test test test test test test test test test ', '3', '0', '2009-01-24 15:25:30', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('34', '17', '11', 'Daniel Kerr', 'fdf gfdg fgdf gd fdf dfgd gfd fghng nbbrt gthhgn nhgn  ', '3', '1', '2009-01-05 18:20:42', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('35', '17', '11', 'Daniel Kerr', 'Another review Another review Another review Another review Another review Another review Another review Another review Another review Another review ', '5', '1', '2009-01-05 18:20:48', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('36', '17', '11', 'Daniel Kerr', 'test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test', '2', '1', '2009-01-23 01:55:30', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('32', '17', '0', 'Daniel Kerr', 'test test test test test test test test test test test test test test test test test test test test test test test test test', '3', '1', '2009-01-03 22:48:30', '');
INSERT INTO `review` (`review_id`, `product_id`, `customer_id`, `author`, `text`, `rating`, `status`, `date_added`, `date_modified`) VALUES ('33', '17', '11', 'Daniel Kerr', 'test test test test test test test test test test ', '3', '1', '2009-01-05 18:20:37', '');


#
# TABLE STRUCTURE FOR: `setting`
#

DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL auto_increment,
  `group` varchar(12) collate utf8_unicode_ci NOT NULL default '',
  `key` varchar(64) collate utf8_unicode_ci NOT NULL default '',
  `value` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`setting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4059 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3877', 'coupon', 'coupon_sort_order', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4058', 'flat', 'flat_sort_order', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4057', 'flat', 'flat_tax_class_id', '9');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4056', 'flat', 'flat_cost', '1.00');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3933', 'item', 'item_sort_order', '2');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3875', 'paypal', 'paypal_sort_order', '2');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3932', 'item', 'item_tax_class_id', '9');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3931', 'item', 'item_cost', '1.00');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3930', 'item', 'item_geo_zone_id', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3929', 'item', 'item_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4055', 'flat', 'flat_geo_zone_id', '3');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4054', 'flat', 'flat_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3874', 'paypal', 'paypal_currency', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3873', 'paypal', 'paypal_test', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3872', 'paypal', 'paypal_email', 'webmaster@opencart.com');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3056', 'shipping', 'shipping_sort_order', '2');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3055', 'shipping', 'shipping_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3871', 'paypal', 'paypal_order_status_id', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3870', 'paypal', 'paypal_geo_zone_id', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3869', 'paypal', 'paypal_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3948', 'zone', 'zone_sort_order', '3');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3944', 'zone', 'zone_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3945', 'zone', 'zone_3_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3946', 'zone', 'zone_3_cost', '100:10');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3947', 'zone', 'zone_tax_class_id', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3858', 'cod', 'cod_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3859', 'cod', 'cod_geo_zone_id', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3860', 'cod', 'cod_order_status_id', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3861', 'cod', 'cod_sort_order', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4045', 'config', 'config_compression', '4');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4044', 'config', 'config_cache', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4043', 'config', 'config_download_status', '5');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4042', 'config', 'config_download', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('3876', 'coupon', 'coupon_status', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4041', 'config', 'config_order_status_id', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4040', 'config', 'config_stock_subtract', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4039', 'config', 'config_stock_checkout', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4038', 'config', 'config_stock_check', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4037', 'config', 'config_weight_class_id', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4036', 'config', 'config_tax', '1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4035', 'config', 'config_currency', 'GBP');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4034', 'config', 'config_language', 'en');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4033', 'config', 'config_zone_id', '3563');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4032', 'config', 'config_country_id', '222');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4031', 'config', 'config_ssl', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4030', 'config', 'config_parse_time', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4029', 'config', 'config_url_alias', '0');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4028', 'config', 'config_fax', '');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4027', 'config', 'config_telephone', '123456789');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4026', 'config', 'config_email', 'webmaster@opencart.com');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4025', 'config', 'config_address', 'Address 1');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4024', 'config', 'config_owner', 'Your Name');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4023', 'config', 'config_store', 'Test Store');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4052', 'mail', 'mail_update_subject_1', '{store} - Order Update #{order_id}');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4053', 'mail', 'mail_update_message_1', 'Order ID: #{order_id}\r\nDate Ordered: {date_added}\r\n\r\nYour order has been updated to the following status: {status}\r\n\r\nThe comments for your order are:\r\n\r\n{comment}\r\n\r\nTo view your order click the link below:\r\n{invoice}\r\n\r\nPlease reply to this email if you have any questions.');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4051', 'mail', 'mail_order_message_1', 'Thank you for interest in {store} products. Your order has been received and will be delt with as quickly as possible.\r\n \r\nOrder ID: #{order_id}\r\nDate Ordered: {date_added}\r\n\r\nTo view you order click the link below:\r\n{invoice}\r\n\r\nPlease reply to this email if you have any questions.');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4050', 'mail', 'mail_order_subject_1', '{store} - Order #{order_id}');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4048', 'mail', 'mail_forgotten_subject_1', '{store} - New Password');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4049', 'mail', 'mail_forgotten_message_1', 'A new password was requested from {store}.\r\n\r\nYour new password to is:\r\n\r\n{password}');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4046', 'mail', 'mail_account_subject_1', '{store} - Thank you for registering');
INSERT INTO `setting` (`setting_id`, `group`, `key`, `value`) VALUES ('4047', 'mail', 'mail_account_message_1', 'Welcome and thank you for registering at {store}!\r\n\r\nYour account has now been created and you can log in by using your email address and password by visiting our website or at the following URL:\r\n{login}\r\n\r\nUpon logging in, you will be able to access other services including reviewing past orders, printing invoices and editing your account information.\r\n\r\nThanks,\r\n{store}');


#
# TABLE STRUCTURE FOR: `stock_status`
#

DROP TABLE IF EXISTS `stock_status`;
CREATE TABLE `stock_status` (
  `stock_status_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`stock_status_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('7', '1', 'In Stock');
INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('5', '4', 'Out Of Stock');
INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('5', '1', 'Out Of Stock');
INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('7', '4', 'In Stock');
INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('6', '4', '2 - 3 Days');
INSERT INTO `stock_status` (`stock_status_id`, `language_id`, `name`) VALUES ('6', '1', '2 - 3 Days');


#
# TABLE STRUCTURE FOR: `tax_class`
#

DROP TABLE IF EXISTS `tax_class`;
CREATE TABLE `tax_class` (
  `tax_class_id` int(11) NOT NULL auto_increment,
  `title` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tax_class_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tax_class` (`tax_class_id`, `title`, `description`, `date_added`, `date_modified`) VALUES ('9', 'Taxable Goods', 'Taxed Stuff', '2009-01-06 23:21:53', '2009-01-06 23:27:16');


#
# TABLE STRUCTURE FOR: `tax_rate`
#

DROP TABLE IF EXISTS `tax_rate`;
CREATE TABLE `tax_rate` (
  `tax_rate_id` int(11) NOT NULL auto_increment,
  `geo_zone_id` int(11) NOT NULL default '0',
  `tax_class_id` int(11) NOT NULL default '0',
  `priority` int(5) default '1',
  `rate` decimal(7,4) NOT NULL default '0.0000',
  `description` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `date_modified` datetime default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`tax_rate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `tax_rate` (`tax_rate_id`, `geo_zone_id`, `tax_class_id`, `priority`, `rate`, `description`, `date_modified`, `date_added`) VALUES ('28', '3', '9', '1', '17.5000', 'VAT 17.5%', '', '2009-01-06 23:27:16');


#
# TABLE STRUCTURE FOR: `url_alias`
#

DROP TABLE IF EXISTS `url_alias`;
CREATE TABLE `url_alias` (
  `url_alias_id` int(11) NOT NULL auto_increment,
  `query` varchar(128) collate utf8_unicode_ci NOT NULL default '0',
  `alias` varchar(128) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`url_alias_id`),
  KEY `query` (`query`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `url_alias` (`url_alias_id`, `query`, `alias`) VALUES ('20', 'controller=product&product_id=1', 'test');


#
# TABLE STRUCTURE FOR: `user`
#

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_group_id` int(11) NOT NULL default '0',
  `username` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `firstname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lastname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(96) collate utf8_unicode_ci NOT NULL default '',
  `status` int(1) NOT NULL,
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`user_id`, `user_group_id`, `username`, `password`, `firstname`, `lastname`, `email`, `status`, `ip`, `date_added`) VALUES ('1', '1', 'Daniel', '4edfc924721abb774d5447bade86ea5d', 'Daniel', 'Kerr', 'webmaster@opencart.com', '1', '87.81.199.5', '2007-08-20 11:35:34');
INSERT INTO `user` (`user_id`, `user_group_id`, `username`, `password`, `firstname`, `lastname`, `email`, `status`, `ip`, `date_added`) VALUES ('10', '10', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo', 'demo', 'demo@demo.com', '0', '90.208.117.53', '2009-01-06 15:12:54');


#
# TABLE STRUCTURE FOR: `user_group`
#

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `user_group_id` int(11) NOT NULL auto_increment,
  `name` varchar(64) collate utf8_unicode_ci default NULL,
  `permission` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`user_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user_group` (`user_group_id`, `name`, `permission`) VALUES ('1', 'Top Administrator', 'a:2:{s:6:"access";a:39:{i:0;s:16:"catalog/category";i:1;s:16:"catalog/download";i:2;s:13:"catalog/image";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:14:"catalog/option";i:6;s:15:"catalog/product";i:7;s:14:"catalog/review";i:8;s:15:"customer/coupon";i:9;s:17:"customer/customer";i:10;s:14:"customer/order";i:11;s:18:"customer/send_mail";i:12;s:17:"extension/payment";i:13;s:18:"extension/shipping";i:14;s:15:"extension/total";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/order_status";i:20;s:25:"localisation/stock_status";i:21;s:22:"localisation/tax_class";i:22;s:25:"localisation/weight_class";i:23;s:17:"localisation/zone";i:24;s:11:"payment/cod";i:25;s:14:"payment/paypal";i:26;s:16:"report/purchased";i:27;s:11:"report/sale";i:28;s:13:"report/viewed";i:29;s:12:"setting/mail";i:30;s:15:"setting/setting";i:31;s:13:"shipping/flat";i:32;s:13:"shipping/item";i:33;s:13:"shipping/zone";i:34;s:11:"tool/backup";i:35;s:12:"total/coupon";i:36;s:14:"total/shipping";i:37;s:9:"user/user";i:38;s:15:"user/user_group";}s:6:"modify";a:39:{i:0;s:16:"catalog/category";i:1;s:16:"catalog/download";i:2;s:13:"catalog/image";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:14:"catalog/option";i:6;s:15:"catalog/product";i:7;s:14:"catalog/review";i:8;s:15:"customer/coupon";i:9;s:17:"customer/customer";i:10;s:14:"customer/order";i:11;s:18:"customer/send_mail";i:12;s:17:"extension/payment";i:13;s:18:"extension/shipping";i:14;s:15:"extension/total";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/order_status";i:20;s:25:"localisation/stock_status";i:21;s:22:"localisation/tax_class";i:22;s:25:"localisation/weight_class";i:23;s:17:"localisation/zone";i:24;s:11:"payment/cod";i:25;s:14:"payment/paypal";i:26;s:16:"report/purchased";i:27;s:11:"report/sale";i:28;s:13:"report/viewed";i:29;s:12:"setting/mail";i:30;s:15:"setting/setting";i:31;s:13:"shipping/flat";i:32;s:13:"shipping/item";i:33;s:13:"shipping/zone";i:34;s:11:"tool/backup";i:35;s:12:"total/coupon";i:36;s:14:"total/shipping";i:37;s:9:"user/user";i:38;s:15:"user/user_group";}}');
INSERT INTO `user_group` (`user_group_id`, `name`, `permission`) VALUES ('10', 'Demonstration', 'a:1:{s:6:"access";a:39:{i:0;s:16:"catalog/category";i:1;s:16:"catalog/download";i:2;s:13:"catalog/image";i:3;s:19:"catalog/information";i:4;s:20:"catalog/manufacturer";i:5;s:14:"catalog/option";i:6;s:15:"catalog/product";i:7;s:14:"catalog/review";i:8;s:15:"customer/coupon";i:9;s:17:"customer/customer";i:10;s:14:"customer/order";i:11;s:18:"customer/send_mail";i:12;s:17:"extension/payment";i:13;s:18:"extension/shipping";i:14;s:15:"extension/total";i:15;s:20:"localisation/country";i:16;s:21:"localisation/currency";i:17;s:21:"localisation/geo_zone";i:18;s:21:"localisation/language";i:19;s:25:"localisation/order_status";i:20;s:25:"localisation/stock_status";i:21;s:22:"localisation/tax_class";i:22;s:25:"localisation/weight_class";i:23;s:17:"localisation/zone";i:24;s:11:"payment/cod";i:25;s:14:"payment/paypal";i:26;s:16:"report/purchased";i:27;s:11:"report/sale";i:28;s:13:"report/viewed";i:29;s:12:"setting/mail";i:30;s:15:"setting/setting";i:31;s:13:"shipping/flat";i:32;s:13:"shipping/item";i:33;s:13:"shipping/zone";i:34;s:11:"tool/backup";i:35;s:12:"total/coupon";i:36;s:14:"total/shipping";i:37;s:9:"user/user";i:38;s:15:"user/user_group";}}');


#
# TABLE STRUCTURE FOR: `weight_class`
#

DROP TABLE IF EXISTS `weight_class`;
CREATE TABLE `weight_class` (
  `weight_class_id` int(11) NOT NULL auto_increment,
  `language_id` int(11) NOT NULL,
  `title` varchar(32) collate utf8_unicode_ci NOT NULL,
  `unit` varchar(4) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`weight_class_id`,`language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `weight_class` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES ('1', '1', 'Kilograms', 'kg');
INSERT INTO `weight_class` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES ('2', '4', 'Grams', 'g');
INSERT INTO `weight_class` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES ('2', '1', 'Grams', 'g');
INSERT INTO `weight_class` (`weight_class_id`, `language_id`, `title`, `unit`) VALUES ('1', '4', 'Kilograms', 'kg');


#
# TABLE STRUCTURE FOR: `weight_rule`
#

DROP TABLE IF EXISTS `weight_rule`;
CREATE TABLE `weight_rule` (
  `from_id` int(11) NOT NULL default '0',
  `to_id` int(11) NOT NULL default '0',
  `rule` decimal(15,4) NOT NULL default '0.0000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `weight_rule` (`from_id`, `to_id`, `rule`) VALUES ('2', '1', '0.0010');
INSERT INTO `weight_rule` (`from_id`, `to_id`, `rule`) VALUES ('1', '2', '1000.0000');


#
# TABLE STRUCTURE FOR: `zone`
#

DROP TABLE IF EXISTS `zone`;
CREATE TABLE `zone` (
  `zone_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '0',
  `code` varchar(32) NOT NULL default '',
  `name` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3842 DEFAULT CHARSET=utf8;

INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1', '1', 'BDS', 'Badakhshan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2', '1', 'BDG', 'Badghis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3', '1', 'BGL', 'Baghlan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('4', '1', 'BAL', 'Balkh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('5', '1', 'BAM', 'Bamian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('6', '1', 'FRA', 'Farah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('7', '1', 'FYB', 'Faryab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('8', '1', 'GHA', 'Ghazni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('9', '1', 'GHO', 'Ghowr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('10', '1', 'HEL', 'Helmand');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('11', '1', 'HER', 'Herat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('12', '1', 'JOW', 'Jowzjan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('13', '1', 'KAB', 'Kabul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('14', '1', 'KAN', 'Kandahar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('15', '1', 'KAP', 'Kapisa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('16', '1', 'KHO', 'Khost');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('17', '1', 'KNR', 'Konar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('18', '1', 'KDZ', 'Kondoz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('19', '1', 'LAG', 'Laghman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('20', '1', 'LOW', 'Lowgar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('21', '1', 'NAN', 'Nangrahar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('22', '1', 'NIM', 'Nimruz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('23', '1', 'NUR', 'Nurestan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('24', '1', 'ORU', 'Oruzgan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('25', '1', 'PIA', 'Paktia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('26', '1', 'PKA', 'Paktika');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('27', '1', 'PAR', 'Parwan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('28', '1', 'SAM', 'Samangan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('29', '1', 'SAR', 'Sar-e Pol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('30', '1', 'TAK', 'Takhar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('31', '1', 'WAR', 'Wardak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('32', '1', 'ZAB', 'Zabol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('33', '2', 'BR', 'Berat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('34', '2', 'BU', 'Bulqize');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('35', '2', 'DL', 'Delvine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('36', '2', 'DV', 'Devoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('37', '2', 'DI', 'Diber');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('38', '2', 'DR', 'Durres');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('39', '2', 'EL', 'Elbasan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('40', '2', 'ER', 'Kolonje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('41', '2', 'FR', 'Fier');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('42', '2', 'GJ', 'Gjirokaster');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('43', '2', 'GR', 'Gramsh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('44', '2', 'HA', 'Has');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('45', '2', 'KA', 'Kavaje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('46', '2', 'KB', 'Kurbin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('47', '2', 'KC', 'Kucove');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('48', '2', 'KO', 'Korce');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('49', '2', 'KR', 'Kruje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('50', '2', 'KU', 'Kukes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('51', '2', 'LB', 'Librazhd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('52', '2', 'LE', 'Lezhe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('53', '2', 'LU', 'Lushnje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('54', '2', 'MM', 'Malesi e Madhe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('55', '2', 'MK', 'Mallakaster');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('56', '2', 'MT', 'Mat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('57', '2', 'MR', 'Mirdite');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('58', '2', 'PQ', 'Peqin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('59', '2', 'PR', 'Permet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('60', '2', 'PG', 'Pogradec');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('61', '2', 'PU', 'Puke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('62', '2', 'SH', 'Shkoder');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('63', '2', 'SK', 'Skrapar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('64', '2', 'SR', 'Sarande');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('65', '2', 'TE', 'Tepelene');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('66', '2', 'TP', 'Tropoje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('67', '2', 'TR', 'Tirane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('68', '2', 'VL', 'Vlore');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('69', '3', 'ADR', 'Adrar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('70', '3', 'ADE', 'Ain Defla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('71', '3', 'ATE', 'Ain Temouchent');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('72', '3', 'ALG', 'Alger');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('73', '3', 'ANN', 'Annaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('74', '3', 'BAT', 'Batna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('75', '3', 'BEC', 'Bechar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('76', '3', 'BEJ', 'Bejaia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('77', '3', 'BIS', 'Biskra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('78', '3', 'BLI', 'Blida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('79', '3', 'BBA', 'Bordj Bou Arreridj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('80', '3', 'BOA', 'Bouira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('81', '3', 'BMD', 'Boumerdes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('82', '3', 'CHL', 'Chlef');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('83', '3', 'CON', 'Constantine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('84', '3', 'DJE', 'Djelfa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('85', '3', 'EBA', 'El Bayadh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('86', '3', 'EOU', 'El Oued');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('87', '3', 'ETA', 'El Tarf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('88', '3', 'GHA', 'Ghardaia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('89', '3', 'GUE', 'Guelma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('90', '3', 'ILL', 'Illizi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('91', '3', 'JIJ', 'Jijel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('92', '3', 'KHE', 'Khenchela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('93', '3', 'LAG', 'Laghouat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('94', '3', 'MUA', 'Muaskar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('95', '3', 'MED', 'Medea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('96', '3', 'MIL', 'Mila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('97', '3', 'MOS', 'Mostaganem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('98', '3', 'MSI', 'M\'Sila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('99', '3', 'NAA', 'Naama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('100', '3', 'ORA', 'Oran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('101', '3', 'OUA', 'Ouargla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('102', '3', 'OEB', 'Oum el-Bouaghi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('103', '3', 'REL', 'Relizane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('104', '3', 'SAI', 'Saida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('105', '3', 'SET', 'Setif');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('106', '3', 'SBA', 'Sidi Bel Abbes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('107', '3', 'SKI', 'Skikda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('108', '3', 'SAH', 'Souk Ahras');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('109', '3', 'TAM', 'Tamanghasset');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('110', '3', 'TEB', 'Tebessa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('111', '3', 'TIA', 'Tiaret');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('112', '3', 'TIN', 'Tindouf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('113', '3', 'TIP', 'Tipaza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('114', '3', 'TIS', 'Tissemsilt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('115', '3', 'TOU', 'Tizi Ouzou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('116', '3', 'TLE', 'Tlemcen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('117', '4', 'E', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('118', '4', 'M', 'Manu\'a');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('119', '4', 'R', 'Rose Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('120', '4', 'S', 'Swains Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('121', '4', 'W', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('122', '5', 'ALV', 'Andorra la Vella');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('123', '5', 'CAN', 'Canillo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('124', '5', 'ENC', 'Encamp');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('125', '5', 'ESE', 'Escaldes-Engordany');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('126', '5', 'LMA', 'La Massana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('127', '5', 'ORD', 'Ordino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('128', '5', 'SJL', 'Sant Julia de Loria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('129', '6', 'BGO', 'Bengo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('130', '6', 'BGU', 'Benguela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('131', '6', 'BIE', 'Bie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('132', '6', 'CAB', 'Cabinda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('133', '6', 'CCU', 'Cuando-Cubango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('134', '6', 'CNO', 'Cuanza Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('135', '6', 'CUS', 'Cuanza Sul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('136', '6', 'CNN', 'Cunene');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('137', '6', 'HUA', 'Huambo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('138', '6', 'HUI', 'Huila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('139', '6', 'LUA', 'Luanda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('140', '6', 'LNO', 'Lunda Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('141', '6', 'LSU', 'Lunda Sul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('142', '6', 'MAL', 'Malange');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('143', '6', 'MOX', 'Moxico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('144', '6', 'NAM', 'Namibe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('145', '6', 'UIG', 'Uige');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('146', '6', 'ZAI', 'Zaire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('147', '9', 'ASG', 'Saint George');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('148', '9', 'ASJ', 'Saint John');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('149', '9', 'ASM', 'Saint Mary');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('150', '9', 'ASL', 'Saint Paul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('151', '9', 'ASR', 'Saint Peter');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('152', '9', 'ASH', 'Saint Philip');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('153', '9', 'BAR', 'Barbuda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('154', '9', 'RED', 'Redonda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('155', '10', 'AN', 'Antartida e Islas del Atlantico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('156', '10', 'BA', 'Buenos Aires');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('157', '10', 'CA', 'Catamarca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('158', '10', 'CH', 'Chaco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('159', '10', 'CU', 'Chubut');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('160', '10', 'CO', 'Cordoba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('161', '10', 'CR', 'Corrientes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('162', '10', 'DF', 'Distrito Federal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('163', '10', 'ER', 'Entre Rios');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('164', '10', 'FO', 'Formosa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('165', '10', 'JU', 'Jujuy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('166', '10', 'LP', 'La Pampa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('167', '10', 'LR', 'La Rioja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('168', '10', 'ME', 'Mendoza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('169', '10', 'MI', 'Misiones');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('170', '10', 'NE', 'Neuquen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('171', '10', 'RN', 'Rio Negro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('172', '10', 'SA', 'Salta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('173', '10', 'SJ', 'San Juan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('174', '10', 'SL', 'San Luis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('175', '10', 'SC', 'Santa Cruz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('176', '10', 'SF', 'Santa Fe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('177', '10', 'SD', 'Santiago del Estero');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('178', '10', 'TF', 'Tierra del Fuego');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('179', '10', 'TU', 'Tucuman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('180', '11', 'AGT', 'Aragatsotn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('181', '11', 'ARR', 'Ararat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('182', '11', 'ARM', 'Armavir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('183', '11', 'GEG', 'Geghark\'unik\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('184', '11', 'KOT', 'Kotayk\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('185', '11', 'LOR', 'Lorri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('186', '11', 'SHI', 'Shirak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('187', '11', 'SYU', 'Syunik\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('188', '11', 'TAV', 'Tavush');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('189', '11', 'VAY', 'Vayots\' Dzor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('190', '11', 'YER', 'Yerevan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('191', '13', 'ACT', 'Australian Capitol Territory');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('192', '13', 'NSW', 'New South Wales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('193', '13', 'NT', 'Northern Territory');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('194', '13', 'QLD', 'Queensland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('195', '13', 'SA', 'South Australia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('196', '13', 'TAS', 'Tasmania');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('197', '13', 'VIC', 'Victoria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('198', '13', 'WA', 'Western Australia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('199', '14', 'BUR', 'Burgenland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('200', '14', 'KAR', 'KÃƒÂ¤rnten');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('201', '14', 'NOS', 'NiederÃƒÂ¶sterreich');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('202', '14', 'OOS', 'OberÃƒÂ¶sterreich');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('203', '14', 'SAL', 'Salzburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('204', '14', 'STE', 'Steiermark');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('205', '14', 'TIR', 'Tirol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('206', '14', 'VOR', 'Vorarlberg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('207', '14', 'WIE', 'Wien');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('208', '15', 'AB', 'Ali Bayramli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('209', '15', 'ABS', 'Abseron');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('210', '15', 'AGC', 'AgcabAdi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('211', '15', 'AGM', 'Agdam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('212', '15', 'AGS', 'Agdas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('213', '15', 'AGA', 'Agstafa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('214', '15', 'AGU', 'Agsu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('215', '15', 'AST', 'Astara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('216', '15', 'BA', 'Baki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('217', '15', 'BAB', 'BabAk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('218', '15', 'BAL', 'BalakAn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('219', '15', 'BAR', 'BArdA');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('220', '15', 'BEY', 'Beylaqan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('221', '15', 'BIL', 'Bilasuvar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('222', '15', 'CAB', 'Cabrayil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('223', '15', 'CAL', 'Calilabab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('224', '15', 'CUL', 'Culfa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('225', '15', 'DAS', 'Daskasan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('226', '15', 'DAV', 'Davaci');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('227', '15', 'FUZ', 'Fuzuli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('228', '15', 'GA', 'Ganca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('229', '15', 'GAD', 'Gadabay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('230', '15', 'GOR', 'Goranboy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('231', '15', 'GOY', 'Goycay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('232', '15', 'HAC', 'Haciqabul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('233', '15', 'IMI', 'Imisli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('234', '15', 'ISM', 'Ismayilli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('235', '15', 'KAL', 'Kalbacar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('236', '15', 'KUR', 'Kurdamir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('237', '15', 'LA', 'Lankaran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('238', '15', 'LAC', 'Lacin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('239', '15', 'LAN', 'Lankaran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('240', '15', 'LER', 'Lerik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('241', '15', 'MAS', 'Masalli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('242', '15', 'MI', 'Mingacevir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('243', '15', 'NA', 'Naftalan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('244', '15', 'NEF', 'Neftcala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('245', '15', 'OGU', 'Oguz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('246', '15', 'ORD', 'Ordubad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('247', '15', 'QAB', 'Qabala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('248', '15', 'QAX', 'Qax');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('249', '15', 'QAZ', 'Qazax');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('250', '15', 'QOB', 'Qobustan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('251', '15', 'QBA', 'Quba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('252', '15', 'QBI', 'Qubadli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('253', '15', 'QUS', 'Qusar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('254', '15', 'SA', 'Saki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('255', '15', 'SAT', 'Saatli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('256', '15', 'SAB', 'Sabirabad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('257', '15', 'SAD', 'Sadarak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('258', '15', 'SAH', 'Sahbuz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('259', '15', 'SAK', 'Saki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('260', '15', 'SAL', 'Salyan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('261', '15', 'SM', 'Sumqayit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('262', '15', 'SMI', 'Samaxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('263', '15', 'SKR', 'Samkir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('264', '15', 'SMX', 'Samux');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('265', '15', 'SAR', 'Sarur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('266', '15', 'SIY', 'Siyazan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('267', '15', 'SS', 'Susa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('268', '15', 'SUS', 'Susa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('269', '15', 'TAR', 'Tartar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('270', '15', 'TOV', 'Tovuz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('271', '15', 'UCA', 'Ucar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('272', '15', 'XA', 'Xankandi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('273', '15', 'XAC', 'Xacmaz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('274', '15', 'XAN', 'Xanlar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('275', '15', 'XIZ', 'Xizi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('276', '15', 'XCI', 'Xocali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('277', '15', 'XVD', 'Xocavand');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('278', '15', 'YAR', 'Yardimli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('279', '15', 'YEV', 'Yevlax');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('280', '15', 'ZAN', 'Zangilan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('281', '15', 'ZAQ', 'Zaqatala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('282', '15', 'ZAR', 'Zardab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('283', '15', 'NX', 'Naxcivan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('284', '16', 'ACK', 'Acklins');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('285', '16', 'BER', 'Berry Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('286', '16', 'BIM', 'Bimini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('287', '16', 'BLK', 'Black Point');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('288', '16', 'CAT', 'Cat Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('289', '16', 'CAB', 'Central Abaco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('290', '16', 'CAN', 'Central Andros');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('291', '16', 'CEL', 'Central Eleuthera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('292', '16', 'FRE', 'City of Freeport');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('293', '16', 'CRO', 'Crooked Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('294', '16', 'EGB', 'East Grand Bahama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('295', '16', 'EXU', 'Exuma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('296', '16', 'GRD', 'Grand Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('297', '16', 'HAR', 'Harbour Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('298', '16', 'HOP', 'Hope Town');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('299', '16', 'INA', 'Inagua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('300', '16', 'LNG', 'Long Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('301', '16', 'MAN', 'Mangrove Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('302', '16', 'MAY', 'Mayaguana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('303', '16', 'MOO', 'Moore\'s Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('304', '16', 'NAB', 'North Abaco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('305', '16', 'NAN', 'North Andros');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('306', '16', 'NEL', 'North Eleuthera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('307', '16', 'RAG', 'Ragged Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('308', '16', 'RUM', 'Rum Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('309', '16', 'SAL', 'San Salvador');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('310', '16', 'SAB', 'South Abaco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('311', '16', 'SAN', 'South Andros');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('312', '16', 'SEL', 'South Eleuthera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('313', '16', 'SWE', 'Spanish Wells');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('314', '16', 'WGB', 'West Grand Bahama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('315', '17', 'CAP', 'Capital');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('316', '17', 'CEN', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('317', '17', 'MUH', 'Muharraq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('318', '17', 'NOR', 'Northern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('319', '17', 'SOU', 'Southern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('320', '18', 'BAR', 'Barisal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('321', '18', 'CHI', 'Chittagong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('322', '18', 'DHA', 'Dhaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('323', '18', 'KHU', 'Khulna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('324', '18', 'RAJ', 'Rajshahi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('325', '18', 'SYL', 'Sylhet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('326', '19', 'CC', 'Christ Church');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('327', '19', 'AND', 'Saint Andrew');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('328', '19', 'GEO', 'Saint George');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('329', '19', 'JAM', 'Saint James');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('330', '19', 'JOH', 'Saint John');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('331', '19', 'JOS', 'Saint Joseph');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('332', '19', 'LUC', 'Saint Lucy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('333', '19', 'MIC', 'Saint Michael');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('334', '19', 'PET', 'Saint Peter');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('335', '19', 'PHI', 'Saint Philip');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('336', '19', 'THO', 'Saint Thomas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('337', '20', 'BR', 'Brestskaya (Brest)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('338', '20', 'HO', 'Homyel\'skaya (Homyel\')');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('339', '20', 'HM', 'Horad Minsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('340', '20', 'HR', 'Hrodzyenskaya (Hrodna)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('341', '20', 'MA', 'Mahilyowskaya (Mahilyow)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('342', '20', 'MI', 'Minskaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('343', '20', 'VI', 'Vitsyebskaya (Vitsyebsk)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('344', '21', 'VAN', 'Antwerpen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('345', '21', 'WBR', 'Brabant Wallon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('346', '21', 'WHT', 'Hainaut');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('347', '21', 'WLG', 'Liege');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('348', '21', 'VLI', 'Limburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('349', '21', 'WLX', 'Luxembourg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('350', '21', 'WNA', 'Namur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('351', '21', 'VOV', 'Oost-Vlaanderen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('352', '21', 'VBR', 'Vlaams Brabant');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('353', '21', 'VWV', 'West-Vlaanderen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('354', '22', 'BZ', 'Belize');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('355', '22', 'CY', 'Cayo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('356', '22', 'CR', 'Corozal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('357', '22', 'OW', 'Orange Walk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('358', '22', 'SC', 'Stann Creek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('359', '22', 'TO', 'Toledo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('360', '23', 'AL', 'Alibori');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('361', '23', 'AK', 'Atakora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('362', '23', 'AQ', 'Atlantique');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('363', '23', 'BO', 'Borgou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('364', '23', 'CO', 'Collines');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('365', '23', 'DO', 'Donga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('366', '23', 'KO', 'Kouffo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('367', '23', 'LI', 'Littoral');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('368', '23', 'MO', 'Mono');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('369', '23', 'OU', 'Oueme');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('370', '23', 'PL', 'Plateau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('371', '23', 'ZO', 'Zou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('372', '24', 'DS', 'Devonshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('373', '24', 'HC', 'Hamilton City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('374', '24', 'HA', 'Hamilton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('375', '24', 'PG', 'Paget');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('376', '24', 'PB', 'Pembroke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('377', '24', 'GC', 'Saint George City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('378', '24', 'SG', 'Saint George\'s');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('379', '24', 'SA', 'Sandys');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('380', '24', 'SM', 'Smith\'s');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('381', '24', 'SH', 'Southampton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('382', '24', 'WA', 'Warwick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('383', '25', 'BUM', 'Bumthang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('384', '25', 'CHU', 'Chukha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('385', '25', 'DAG', 'Dagana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('386', '25', 'GAS', 'Gasa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('387', '25', 'HAA', 'Haa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('388', '25', 'LHU', 'Lhuntse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('389', '25', 'MON', 'Mongar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('390', '25', 'PAR', 'Paro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('391', '25', 'PEM', 'Pemagatshel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('392', '25', 'PUN', 'Punakha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('393', '25', 'SJO', 'Samdrup Jongkhar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('394', '25', 'SAT', 'Samtse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('395', '25', 'SAR', 'Sarpang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('396', '25', 'THI', 'Thimphu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('397', '25', 'TRG', 'Trashigang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('398', '25', 'TRY', 'Trashiyangste');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('399', '25', 'TRO', 'Trongsa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('400', '25', 'TSI', 'Tsirang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('401', '25', 'WPH', 'Wangdue Phodrang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('402', '25', 'ZHE', 'Zhemgang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('403', '26', 'BEN', 'Beni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('404', '26', 'CHU', 'Chuquisaca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('405', '26', 'COC', 'Cochabamba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('406', '26', 'LPZ', 'La Paz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('407', '26', 'ORU', 'Oruro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('408', '26', 'PAN', 'Pando');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('409', '26', 'POT', 'Potosi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('410', '26', 'SCZ', 'Santa Cruz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('411', '26', 'TAR', 'Tarija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('412', '27', 'BRO', 'Brcko district');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('413', '27', 'FUS', 'Unsko-Sanski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('414', '27', 'FPO', 'Posavski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('415', '27', 'FTU', 'Tuzlanski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('416', '27', 'FZE', 'Zenicko-Dobojski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('417', '27', 'FBP', 'Bosanskopodrinjski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('418', '27', 'FSB', 'Srednjebosanski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('419', '27', 'FHN', 'Hercegovacko-neretvanski Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('420', '27', 'FZH', 'Zapadnohercegovacka Zupanija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('421', '27', 'FSA', 'Kanton Sarajevo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('422', '27', 'FZA', 'Zapadnobosanska');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('423', '27', 'SBL', 'Banja Luka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('424', '27', 'SDO', 'Doboj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('425', '27', 'SBI', 'Bijeljina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('426', '27', 'SVL', 'Vlasenica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('427', '27', 'SSR', 'Sarajevo-Romanija or Sokolac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('428', '27', 'SFO', 'Foca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('429', '27', 'STR', 'Trebinje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('430', '28', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('431', '28', 'GH', 'Ghanzi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('432', '28', 'KD', 'Kgalagadi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('433', '28', 'KT', 'Kgatleng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('434', '28', 'KW', 'Kweneng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('435', '28', 'NG', 'Ngamiland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('436', '28', 'NE', 'North East');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('437', '28', 'NW', 'North West');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('438', '28', 'SE', 'South East');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('439', '28', 'SO', 'Southern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('440', '30', 'AC', 'Acre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('441', '30', 'AL', 'Alagoas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('442', '30', 'AP', 'Amapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('443', '30', 'AM', 'Amazonas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('444', '30', 'BA', 'Bahia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('445', '30', 'CE', 'Ceara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('446', '30', 'DF', 'Distrito Federal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('447', '30', 'ES', 'Espirito Santo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('448', '30', 'GO', 'Goias');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('449', '30', 'MA', 'Maranhao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('450', '30', 'MT', 'Mato Grosso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('451', '30', 'MS', 'Mato Grosso do Sul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('452', '30', 'MG', 'Minas Gerais');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('453', '30', 'PA', 'Para');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('454', '30', 'PB', 'Paraiba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('455', '30', 'PR', 'Parana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('456', '30', 'PE', 'Pernambuco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('457', '30', 'PI', 'Piaui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('458', '30', 'RJ', 'Rio de Janeiro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('459', '30', 'RN', 'Rio Grande do Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('460', '30', 'RS', 'Rio Grande do Sul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('461', '30', 'RO', 'Rondonia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('462', '30', 'RR', 'Roraima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('463', '30', 'SC', 'Santa Catarina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('464', '30', 'SP', 'Sao Paulo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('465', '30', 'SE', 'Sergipe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('466', '30', 'TO', 'Tocantins');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('467', '31', 'PB', 'Peros Banhos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('468', '31', 'SI', 'Salomon Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('469', '31', 'NI', 'Nelsons Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('470', '31', 'TB', 'Three Brothers');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('471', '31', 'EA', 'Eagle Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('472', '31', 'DI', 'Danger Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('473', '31', 'EG', 'Egmont Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('474', '31', 'DG', 'Diego Garcia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('475', '32', 'BEL', 'Belait');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('476', '32', 'BRM', 'Brunei and Muara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('477', '32', 'TEM', 'Temburong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('478', '32', 'TUT', 'Tutong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('479', '33', '', 'Blagoevgrad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('480', '33', '', 'Burgas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('481', '33', '', 'Dobrich');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('482', '33', '', 'Gabrovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('483', '33', '', 'Haskovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('484', '33', '', 'Kardjali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('485', '33', '', 'Kyustendil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('486', '33', '', 'Lovech');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('487', '33', '', 'Montana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('488', '33', '', 'Pazardjik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('489', '33', '', 'Pernik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('490', '33', '', 'Pleven');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('491', '33', '', 'Plovdiv');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('492', '33', '', 'Razgrad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('493', '33', '', 'Shumen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('494', '33', '', 'Silistra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('495', '33', '', 'Sliven');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('496', '33', '', 'Smolyan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('497', '33', '', 'Sofia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('498', '33', '', 'Sofia - town');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('499', '33', '', 'Stara Zagora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('500', '33', '', 'Targovishte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('501', '33', '', 'Varna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('502', '33', '', 'Veliko Tarnovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('503', '33', '', 'Vidin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('504', '33', '', 'Vratza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('505', '33', '', 'Yambol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('506', '34', 'BAL', 'Bale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('507', '34', 'BAM', 'Bam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('508', '34', 'BAN', 'Banwa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('509', '34', 'BAZ', 'Bazega');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('510', '34', 'BOR', 'Bougouriba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('511', '34', 'BLG', 'Boulgou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('512', '34', 'BOK', 'Boulkiemde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('513', '34', 'COM', 'Comoe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('514', '34', 'GAN', 'Ganzourgou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('515', '34', 'GNA', 'Gnagna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('516', '34', 'GOU', 'Gourma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('517', '34', 'HOU', 'Houet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('518', '34', 'IOA', 'Ioba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('519', '34', 'KAD', 'Kadiogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('520', '34', 'KEN', 'Kenedougou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('521', '34', 'KOD', 'Komondjari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('522', '34', 'KOP', 'Kompienga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('523', '34', 'KOS', 'Kossi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('524', '34', 'KOL', 'Koulpelogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('525', '34', 'KOT', 'Kouritenga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('526', '34', 'KOW', 'Kourweogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('527', '34', 'LER', 'Leraba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('528', '34', 'LOR', 'Loroum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('529', '34', 'MOU', 'Mouhoun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('530', '34', 'NAH', 'Nahouri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('531', '34', 'NAM', 'Namentenga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('532', '34', 'NAY', 'Nayala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('533', '34', 'NOU', 'Noumbiel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('534', '34', 'OUB', 'Oubritenga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('535', '34', 'OUD', 'Oudalan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('536', '34', 'PAS', 'Passore');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('537', '34', 'PON', 'Poni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('538', '34', 'SAG', 'Sanguie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('539', '34', 'SAM', 'Sanmatenga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('540', '34', 'SEN', 'Seno');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('541', '34', 'SIS', 'Sissili');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('542', '34', 'SOM', 'Soum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('543', '34', 'SOR', 'Sourou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('544', '34', 'TAP', 'Tapoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('545', '34', 'TUY', 'Tuy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('546', '34', 'YAG', 'Yagha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('547', '34', 'YAT', 'Yatenga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('548', '34', 'ZIR', 'Ziro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('549', '34', 'ZOD', 'Zondoma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('550', '34', 'ZOW', 'Zoundweogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('551', '35', 'BB', 'Bubanza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('552', '35', 'BJ', 'Bujumbura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('553', '35', 'BR', 'Bururi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('554', '35', 'CA', 'Cankuzo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('555', '35', 'CI', 'Cibitoke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('556', '35', 'GI', 'Gitega');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('557', '35', 'KR', 'Karuzi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('558', '35', 'KY', 'Kayanza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('559', '35', 'KI', 'Kirundo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('560', '35', 'MA', 'Makamba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('561', '35', 'MU', 'Muramvya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('562', '35', 'MY', 'Muyinga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('563', '35', 'MW', 'Mwaro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('564', '35', 'NG', 'Ngozi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('565', '35', 'RT', 'Rutana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('566', '35', 'RY', 'Ruyigi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('567', '36', 'PP', 'Phnom Penh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('568', '36', 'PS', 'Preah Seihanu (Kompong Som or Si');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('569', '36', 'PA', 'Pailin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('570', '36', 'KB', 'Keb');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('571', '36', 'BM', 'Banteay Meanchey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('572', '36', 'BA', 'Battambang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('573', '36', 'KM', 'Kampong Cham');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('574', '36', 'KN', 'Kampong Chhnang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('575', '36', 'KU', 'Kampong Speu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('576', '36', 'KO', 'Kampong Som');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('577', '36', 'KT', 'Kampong Thom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('578', '36', 'KP', 'Kampot');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('579', '36', 'KL', 'Kandal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('580', '36', 'KK', 'Kaoh Kong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('581', '36', 'KR', 'Kratie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('582', '36', 'MK', 'Mondul Kiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('583', '36', 'OM', 'Oddar Meancheay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('584', '36', 'PU', 'Pursat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('585', '36', 'PR', 'Preah Vihear');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('586', '36', 'PG', 'Prey Veng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('587', '36', 'RK', 'Ratanak Kiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('588', '36', 'SI', 'Siemreap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('589', '36', 'ST', 'Stung Treng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('590', '36', 'SR', 'Svay Rieng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('591', '36', 'TK', 'Takeo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('592', '37', 'ADA', 'Adamawa (Adamaoua)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('593', '37', 'CEN', 'Centre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('594', '37', 'EST', 'East (Est)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('595', '37', 'EXN', 'Extreme North (Extreme-Nord)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('596', '37', 'LIT', 'Littoral');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('597', '37', 'NOR', 'North (Nord)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('598', '37', 'NOT', 'Northwest (Nord-Ouest)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('599', '37', 'OUE', 'West (Ouest)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('600', '37', 'SUD', 'South (Sud)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('601', '37', 'SOU', 'Southwest (Sud-Ouest).');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('602', '38', 'AB', 'Alberta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('603', '38', 'BC', 'British Columbia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('604', '38', 'MB', 'Manitoba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('605', '38', 'NB', 'New Brunswick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('606', '38', 'NL', 'Newfoundland and Labrador');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('607', '38', 'NT', 'Northwest Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('608', '38', 'NS', 'Nova Scotia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('609', '38', 'NU', 'Nunavut');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('610', '38', 'ON', 'Ontario');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('611', '38', 'PE', 'Prince Edward Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('612', '38', 'QC', 'QuÃƒÂ©bec');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('613', '38', 'SK', 'Saskatchewan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('614', '38', 'YT', 'Yukon Territory');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('615', '39', 'BV', 'Boa Vista');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('616', '39', 'BR', 'Brava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('617', '39', 'CS', 'Calheta de Sao Miguel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('618', '39', 'MA', 'Maio');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('619', '39', 'MO', 'Mosteiros');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('620', '39', 'PA', 'Paul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('621', '39', 'PN', 'Porto Novo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('622', '39', 'PR', 'Praia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('623', '39', 'RG', 'Ribeira Grande');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('624', '39', 'SL', 'Sal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('625', '39', 'CA', 'Santa Catarina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('626', '39', 'CR', 'Santa Cruz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('627', '39', 'SD', 'Sao Domingos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('628', '39', 'SF', 'Sao Filipe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('629', '39', 'SN', 'Sao Nicolau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('630', '39', 'SV', 'Sao Vicente');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('631', '39', 'TA', 'Tarrafal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('632', '40', 'CR', 'Creek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('633', '40', 'EA', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('634', '40', 'ML', 'Midland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('635', '40', 'ST', 'South Town');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('636', '40', 'SP', 'Spot Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('637', '40', 'SK', 'Stake Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('638', '40', 'WD', 'West End');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('639', '40', 'WN', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('640', '41', 'BBA', 'Bamingui-Bangoran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('641', '41', 'BKO', 'Basse-Kotto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('642', '41', 'HKO', 'Haute-Kotto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('643', '41', 'HMB', 'Haut-Mbomou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('644', '41', 'KEM', 'Kemo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('645', '41', 'LOB', 'Lobaye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('646', '41', 'MKD', 'MambÃƒÂ©re-KadÃƒÂ©ÃƒÂ¯');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('647', '41', 'MBO', 'Mbomou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('648', '41', 'NMM', 'Nana-Mambere');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('649', '41', 'OMP', 'Ombella-M\'Poko');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('650', '41', 'OUK', 'Ouaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('651', '41', 'OUH', 'Ouham');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('652', '41', 'OPE', 'Ouham-Pende');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('653', '41', 'VAK', 'Vakaga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('654', '41', 'NGR', 'Nana-Grebizi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('655', '41', 'SMB', 'Sangha-Mbaere');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('656', '41', 'BAN', 'Bangui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('657', '42', 'BA', 'Batha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('658', '42', 'BI', 'Biltine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('659', '42', 'BE', 'Borkou-Ennedi-Tibesti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('660', '42', 'CB', 'Chari-Baguirmi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('661', '42', 'GU', 'Guera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('662', '42', 'KA', 'Kanem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('663', '42', 'LA', 'Lac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('664', '42', 'LC', 'Logone Occidental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('665', '42', 'LR', 'Logone Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('666', '42', 'MK', 'Mayo-Kebbi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('667', '42', 'MC', 'Moyen-Chari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('668', '42', 'OU', 'Ouaddai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('669', '42', 'SA', 'Salamat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('670', '42', 'TA', 'Tandjile');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('671', '43', 'AI', 'Aisen del General Carlos Ibanez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('672', '43', 'AN', 'Antofagasta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('673', '43', 'AR', 'Araucania');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('674', '43', 'AT', 'Atacama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('675', '43', 'BI', 'Bio-Bio');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('676', '43', 'CO', 'Coquimbo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('677', '43', 'LI', 'Libertador General Bernardo O\'Hi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('678', '43', 'LL', 'Los Lagos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('679', '43', 'MA', 'Magallanes y de la Antartica Chi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('680', '43', 'ML', 'Maule');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('681', '43', 'RM', 'Region Metropolitana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('682', '43', 'TA', 'Tarapaca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('683', '43', 'VS', 'Valparaiso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('684', '44', 'AN', 'Anhui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('685', '44', 'BE', 'Beijing');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('686', '44', 'CH', 'Chongqing');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('687', '44', 'FU', 'Fujian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('688', '44', 'GA', 'Gansu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('689', '44', 'GU', 'Guangdong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('690', '44', 'GX', 'Guangxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('691', '44', 'GZ', 'Guizhou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('692', '44', 'HA', 'Hainan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('693', '44', 'HB', 'Hebei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('694', '44', 'HL', 'Heilongjiang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('695', '44', 'HE', 'Henan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('696', '44', 'HK', 'Hong Kong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('697', '44', 'HU', 'Hubei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('698', '44', 'HN', 'Hunan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('699', '44', 'IM', 'Inner Mongolia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('700', '44', 'JI', 'Jiangsu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('701', '44', 'JX', 'Jiangxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('702', '44', 'JL', 'Jilin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('703', '44', 'LI', 'Liaoning');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('704', '44', 'MA', 'Macau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('705', '44', 'NI', 'Ningxia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('706', '44', 'SH', 'Shaanxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('707', '44', 'SA', 'Shandong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('708', '44', 'SG', 'Shanghai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('709', '44', 'SX', 'Shanxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('710', '44', 'SI', 'Sichuan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('711', '44', 'TI', 'Tianjin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('712', '44', 'XI', 'Xinjiang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('713', '44', 'YU', 'Yunnan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('714', '44', 'ZH', 'Zhejiang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('715', '46', 'D', 'Direction Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('716', '46', 'H', 'Home Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('717', '46', 'O', 'Horsburgh Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('718', '46', 'S', 'South Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('719', '46', 'W', 'West Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('720', '47', 'AMZ', 'Amazonas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('721', '47', 'ANT', 'Antioquia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('722', '47', 'ARA', 'Arauca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('723', '47', 'ATL', 'Atlantico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('724', '47', 'BDC', 'Bogota D.C.');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('725', '47', 'BOL', 'Bolivar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('726', '47', 'BOY', 'Boyaca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('727', '47', 'CAL', 'Caldas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('728', '47', 'CAQ', 'Caqueta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('729', '47', 'CAS', 'Casanare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('730', '47', 'CAU', 'Cauca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('731', '47', 'CES', 'Cesar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('732', '47', 'CHO', 'Choco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('733', '47', 'COR', 'Cordoba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('734', '47', 'CAM', 'Cundinamarca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('735', '47', 'GNA', 'Guainia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('736', '47', 'GJR', 'Guajira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('737', '47', 'GVR', 'Guaviare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('738', '47', 'HUI', 'Huila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('739', '47', 'MAG', 'Magdalena');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('740', '47', 'MET', 'Meta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('741', '47', 'NAR', 'Narino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('742', '47', 'NDS', 'Norte de Santander');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('743', '47', 'PUT', 'Putumayo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('744', '47', 'QUI', 'Quindio');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('745', '47', 'RIS', 'Risaralda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('746', '47', 'SAP', 'San Andres y Providencia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('747', '47', 'SAN', 'Santander');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('748', '47', 'SUC', 'Sucre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('749', '47', 'TOL', 'Tolima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('750', '47', 'VDC', 'Valle del Cauca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('751', '47', 'VAU', 'Vaupes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('752', '47', 'VIC', 'Vichada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('753', '48', 'G', 'Grande Comore');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('754', '48', 'A', 'Anjouan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('755', '48', 'M', 'Moheli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('756', '49', 'BO', 'Bouenza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('757', '49', 'BR', 'Brazzaville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('758', '49', 'CU', 'Cuvette');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('759', '49', 'CO', 'Cuvette-Ouest');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('760', '49', 'KO', 'Kouilou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('761', '49', 'LE', 'Lekoumou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('762', '49', 'LI', 'Likouala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('763', '49', 'NI', 'Niari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('764', '49', 'PL', 'Plateaux');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('765', '49', 'PO', 'Pool');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('766', '49', 'SA', 'Sangha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('767', '50', 'PU', 'Pukapuka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('768', '50', 'RK', 'Rakahanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('769', '50', 'MK', 'Manihiki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('770', '50', 'PE', 'Penrhyn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('771', '50', 'NI', 'Nassau Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('772', '50', 'SU', 'Surwarrow');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('773', '50', 'PA', 'Palmerston');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('774', '50', 'AI', 'Aitutaki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('775', '50', 'MA', 'Manuae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('776', '50', 'TA', 'Takutea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('777', '50', 'MT', 'Mitiaro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('778', '50', 'AT', 'Atiu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('779', '50', 'MU', 'Mauke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('780', '50', 'RR', 'Rarotonga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('781', '50', 'MG', 'Mangaia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('782', '51', 'AL', 'Alajuela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('783', '51', 'CA', 'Cartago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('784', '51', 'GU', 'Guanacaste');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('785', '51', 'HE', 'Heredia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('786', '51', 'LI', 'Limon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('787', '51', 'PU', 'Puntarenas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('788', '51', 'SJ', 'San Jose');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('789', '52', 'ABE', 'Abengourou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('790', '52', 'ABI', 'Abidjan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('791', '52', 'ABO', 'Aboisso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('792', '52', 'ADI', 'Adiake');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('793', '52', 'ADZ', 'Adzope');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('794', '52', 'AGB', 'Agboville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('795', '52', 'AGN', 'Agnibilekrou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('796', '52', 'ALE', 'Alepe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('797', '52', 'BOC', 'Bocanda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('798', '52', 'BAN', 'Bangolo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('799', '52', 'BEO', 'Beoumi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('800', '52', 'BIA', 'Biankouma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('801', '52', 'BDK', 'Bondoukou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('802', '52', 'BGN', 'Bongouanou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('803', '52', 'BFL', 'Bouafle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('804', '52', 'BKE', 'Bouake');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('805', '52', 'BNA', 'Bouna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('806', '52', 'BDL', 'Boundiali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('807', '52', 'DKL', 'Dabakala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('808', '52', 'DBU', 'Dabou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('809', '52', 'DAL', 'Daloa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('810', '52', 'DAN', 'Danane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('811', '52', 'DAO', 'Daoukro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('812', '52', 'DIM', 'Dimbokro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('813', '52', 'DIV', 'Divo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('814', '52', 'DUE', 'Duekoue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('815', '52', 'FER', 'Ferkessedougou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('816', '52', 'GAG', 'Gagnoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('817', '52', 'GBA', 'Grand-Bassam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('818', '52', 'GLA', 'Grand-Lahou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('819', '52', 'GUI', 'Guiglo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('820', '52', 'ISS', 'Issia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('821', '52', 'JAC', 'Jacqueville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('822', '52', 'KAT', 'Katiola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('823', '52', 'KOR', 'Korhogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('824', '52', 'LAK', 'Lakota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('825', '52', 'MAN', 'Man');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('826', '52', 'MKN', 'Mankono');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('827', '52', 'MBA', 'Mbahiakro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('828', '52', 'ODI', 'Odienne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('829', '52', 'OUM', 'Oume');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('830', '52', 'SAK', 'Sakassou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('831', '52', 'SPE', 'San-Pedro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('832', '52', 'SAS', 'Sassandra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('833', '52', 'SEG', 'Seguela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('834', '52', 'SIN', 'Sinfra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('835', '52', 'SOU', 'Soubre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('836', '52', 'TAB', 'Tabou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('837', '52', 'TAN', 'Tanda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('838', '52', 'TIE', 'Tiebissou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('839', '52', 'TIN', 'Tingrela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('840', '52', 'TIA', 'Tiassale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('841', '52', 'TBA', 'Touba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('842', '52', 'TLP', 'Toulepleu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('843', '52', 'TMD', 'Toumodi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('844', '52', 'VAV', 'Vavoua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('845', '52', 'YAM', 'Yamoussoukro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('846', '52', 'ZUE', 'Zuenoula');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('847', '53', 'BB', 'Bjelovar-Bilogora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('848', '53', 'CZ', 'City of Zagreb');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('849', '53', 'DN', 'Dubrovnik-Neretva');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('850', '53', 'IS', 'Istra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('851', '53', 'KA', 'Karlovac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('852', '53', 'KK', 'Koprivnica-Krizevci');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('853', '53', 'KZ', 'Krapina-Zagorje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('854', '53', 'LS', 'Lika-Senj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('855', '53', 'ME', 'Medimurje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('856', '53', 'OB', 'Osijek-Baranja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('857', '53', 'PS', 'Pozega-Slavonia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('858', '53', 'PG', 'Primorje-Gorski Kotar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('859', '53', 'SI', 'Sibenik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('860', '53', 'SM', 'Sisak-Moslavina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('861', '53', 'SB', 'Slavonski Brod-Posavina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('862', '53', 'SD', 'Split-Dalmatia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('863', '53', 'VA', 'Varazdin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('864', '53', 'VP', 'Virovitica-Podravina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('865', '53', 'VS', 'Vukovar-Srijem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('866', '53', 'ZK', 'Zadar-Knin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('867', '53', 'ZA', 'Zagreb');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('868', '54', 'CA', 'Camaguey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('869', '54', 'CD', 'Ciego de Avila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('870', '54', 'CI', 'Cienfuegos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('871', '54', 'CH', 'Ciudad de La Habana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('872', '54', 'GR', 'Granma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('873', '54', 'GU', 'Guantanamo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('874', '54', 'HO', 'Holguin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('875', '54', 'IJ', 'Isla de la Juventud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('876', '54', 'LH', 'La Habana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('877', '54', 'LT', 'Las Tunas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('878', '54', 'MA', 'Matanzas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('879', '54', 'PR', 'Pinar del Rio');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('880', '54', 'SS', 'Sancti Spiritus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('881', '54', 'SC', 'Santiago de Cuba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('882', '54', 'VC', 'Villa Clara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('883', '55', 'F', 'Famagusta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('884', '55', 'K', 'Kyrenia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('885', '55', 'A', 'Larnaca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('886', '55', 'I', 'Limassol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('887', '55', 'N', 'Nicosia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('888', '55', 'P', 'Paphos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('889', '56', 'U', 'Ustecky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('890', '56', 'C', 'Jihocesky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('891', '56', 'B', 'Jihomoravsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('892', '56', 'K', 'Karlovarsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('893', '56', 'H', 'Kralovehradecky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('894', '56', 'L', 'Liberecky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('895', '56', 'T', 'Moravskoslezsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('896', '56', 'M', 'Olomoucky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('897', '56', 'E', 'Pardubicky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('898', '56', 'P', 'Plzensky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('899', '56', 'A', 'Praha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('900', '56', 'S', 'Stredocesky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('901', '56', 'J', 'Vysocina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('902', '56', 'Z', 'Zlinsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('903', '57', 'AR', 'Arhus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('904', '57', 'BH', 'Bornholm');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('905', '57', 'CO', 'Copenhagen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('906', '57', 'FO', 'Faroe Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('907', '57', 'FR', 'Frederiksborg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('908', '57', 'FY', 'Fyn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('909', '57', 'KO', 'Kobenhavn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('910', '57', 'NO', 'Nordjylland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('911', '57', 'RI', 'Ribe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('912', '57', 'RK', 'Ringkobing');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('913', '57', 'RO', 'Roskilde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('914', '57', 'SO', 'Sonderjylland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('915', '57', 'ST', 'Storstrom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('916', '57', 'VK', 'Vejle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('917', '57', 'VJ', 'VestjÃƒÂ¦lland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('918', '57', 'VB', 'Viborg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('919', '58', 'S', '\'Ali Sabih');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('920', '58', 'K', 'Dikhil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('921', '58', 'J', 'Djibouti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('922', '58', 'O', 'Obock');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('923', '58', 'T', 'Tadjoura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('924', '59', 'AND', 'Saint Andrew Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('925', '59', 'DAV', 'Saint David Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('926', '59', 'GEO', 'Saint George Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('927', '59', 'JOH', 'Saint John Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('928', '59', 'JOS', 'Saint Joseph Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('929', '59', 'LUK', 'Saint Luke Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('930', '59', 'MAR', 'Saint Mark Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('931', '59', 'PAT', 'Saint Patrick Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('932', '59', 'PAU', 'Saint Paul Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('933', '59', 'PET', 'Saint Peter Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('934', '60', 'DN', 'Distrito Nacional');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('935', '60', 'AZ', 'Azua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('936', '60', 'BC', 'Baoruco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('937', '60', 'BH', 'Barahona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('938', '60', 'DJ', 'Dajabon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('939', '60', 'DU', 'Duarte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('940', '60', 'EL', 'Elias Pina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('941', '60', 'SY', 'El Seybo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('942', '60', 'ET', 'Espaillat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('943', '60', 'HM', 'Hato Mayor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('944', '60', 'IN', 'Independencia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('945', '60', 'AL', 'La Altagracia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('946', '60', 'RO', 'La Romana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('947', '60', 'VE', 'La Vega');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('948', '60', 'MT', 'Maria Trinidad Sanchez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('949', '60', 'MN', 'Monsenor Nouel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('950', '60', 'MC', 'Monte Cristi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('951', '60', 'MP', 'Monte Plata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('952', '60', 'PD', 'Pedernales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('953', '60', 'PR', 'Peravia (Bani)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('954', '60', 'PP', 'Puerto Plata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('955', '60', 'SL', 'Salcedo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('956', '60', 'SM', 'Samana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('957', '60', 'SH', 'Sanchez Ramirez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('958', '60', 'SC', 'San Cristobal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('959', '60', 'JO', 'San Jose de Ocoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('960', '60', 'SJ', 'San Juan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('961', '60', 'PM', 'San Pedro de Macoris');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('962', '60', 'SA', 'Santiago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('963', '60', 'ST', 'Santiago Rodriguez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('964', '60', 'SD', 'Santo Domingo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('965', '60', 'VA', 'Valverde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('966', '61', 'AL', 'Aileu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('967', '61', 'AN', 'Ainaro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('968', '61', 'BA', 'Baucau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('969', '61', 'BO', 'Bobonaro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('970', '61', 'CO', 'Cova Lima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('971', '61', 'DI', 'Dili');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('972', '61', 'ER', 'Ermera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('973', '61', 'LA', 'Lautem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('974', '61', 'LI', 'Liquica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('975', '61', 'MT', 'Manatuto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('976', '61', 'MF', 'Manufahi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('977', '61', 'OE', 'Oecussi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('978', '61', 'VI', 'Viqueque');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('979', '62', 'AZU', 'Azuay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('980', '62', 'BOL', 'Bolivar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('981', '62', 'CAN', 'CaÃƒÂ±ar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('982', '62', 'CAR', 'Carchi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('983', '62', 'CHI', 'Chimborazo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('984', '62', 'COT', 'Cotopaxi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('985', '62', 'EOR', 'El Oro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('986', '62', 'ESM', 'Esmeraldas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('987', '62', 'GPS', 'GalÃƒÂ¡pagos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('988', '62', 'GUA', 'Guayas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('989', '62', 'IMB', 'Imbabura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('990', '62', 'LOJ', 'Loja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('991', '62', 'LRO', 'Los Rios');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('992', '62', 'MAN', 'ManabÃƒÂ­');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('993', '62', 'MSA', 'Morona Santiago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('994', '62', 'NAP', 'Napo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('995', '62', 'ORE', 'Orellana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('996', '62', 'PAS', 'Pastaza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('997', '62', 'PIC', 'Pichincha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('998', '62', 'SUC', 'SucumbÃƒÂ­os');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('999', '62', 'TUN', 'Tungurahua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1000', '62', 'ZCH', 'Zamora Chinchipe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1001', '63', 'DHY', 'Ad Daqahliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1002', '63', 'BAM', 'Al Bahr al Ahmar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1003', '63', 'BHY', 'Al Buhayrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1004', '63', 'FYM', 'Al Fayyum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1005', '63', 'GBY', 'Al Gharbiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1006', '63', 'IDR', 'Al Iskandariyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1007', '63', 'IML', 'Al Isma\'iliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1008', '63', 'JZH', 'Al Jizah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1009', '63', 'MFY', 'Al Minufiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1010', '63', 'MNY', 'Al Minya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1011', '63', 'QHR', 'Al Qahirah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1012', '63', 'QLY', 'Al Qalyubiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1013', '63', 'WJD', 'Al Wadi al Jadid');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1014', '63', 'SHQ', 'Ash Sharqiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1015', '63', 'SWY', 'As Suways');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1016', '63', 'ASW', 'Aswan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1017', '63', 'ASY', 'Asyut');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1018', '63', 'BSW', 'Bani Suwayf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1019', '63', 'BSD', 'Bur Sa\'id');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1020', '63', 'DMY', 'Dumyat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1021', '63', 'JNS', 'Janub Sina\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1022', '63', 'KSH', 'Kafr ash Shaykh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1023', '63', 'MAT', 'Matruh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1024', '63', 'QIN', 'Qina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1025', '63', 'SHS', 'Shamal Sina\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1026', '63', 'SUH', 'Suhaj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1027', '64', 'AH', 'Ahuachapan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1028', '64', 'CA', 'Cabanas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1029', '64', 'CH', 'Chalatenango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1030', '64', 'CU', 'Cuscatlan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1031', '64', 'LB', 'La Libertad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1032', '64', 'PZ', 'La Paz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1033', '64', 'UN', 'La Union');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1034', '64', 'MO', 'Morazan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1035', '64', 'SM', 'San Miguel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1036', '64', 'SS', 'San Salvador');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1037', '64', 'SV', 'San Vicente');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1038', '64', 'SA', 'Santa Ana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1039', '64', 'SO', 'Sonsonate');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1040', '64', 'US', 'Usulutan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1041', '65', 'AN', 'Provincia Annobon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1042', '65', 'BN', 'Provincia Bioko Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1043', '65', 'BS', 'Provincia Bioko Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1044', '65', 'CS', 'Provincia Centro Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1045', '65', 'KN', 'Provincia Kie-Ntem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1046', '65', 'LI', 'Provincia Litoral');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1047', '65', 'WN', 'Provincia Wele-Nzas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1048', '66', 'MA', 'Central (Maekel)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1049', '66', 'KE', 'Anseba (Keren)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1050', '66', 'DK', 'Southern Red Sea (Debub-Keih-Bah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1051', '66', 'SK', 'Northern Red Sea (Semien-Keih-Ba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1052', '66', 'DE', 'Southern (Debub)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1053', '66', 'BR', 'Gash-Barka (Barentu)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1054', '67', 'HA', 'Harjumaa (Tallinn)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1055', '67', 'HI', 'Hiiumaa (Kardla)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1056', '67', 'IV', 'Ida-Virumaa (Johvi)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1057', '67', 'JA', 'Jarvamaa (Paide)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1058', '67', 'JO', 'Jogevamaa (Jogeva)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1059', '67', 'LV', 'Laane-Virumaa (Rakvere)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1060', '67', 'LA', 'Laanemaa (Haapsalu)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1061', '67', 'PA', 'Parnumaa (Parnu)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1062', '67', 'PO', 'Polvamaa (Polva)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1063', '67', 'RA', 'Raplamaa (Rapla)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1064', '67', 'SA', 'Saaremaa (Kuessaare)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1065', '67', 'TA', 'Tartumaa (Tartu)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1066', '67', 'VA', 'Valgamaa (Valga)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1067', '67', 'VI', 'Viljandimaa (Viljandi)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1068', '67', 'VO', 'Vorumaa (Voru)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1069', '68', 'AF', 'Afar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1070', '68', 'AH', 'Amhara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1071', '68', 'BG', 'Benishangul-Gumaz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1072', '68', 'GB', 'Gambela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1073', '68', 'HR', 'Hariai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1074', '68', 'OR', 'Oromia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1075', '68', 'SM', 'Somali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1076', '68', 'SN', 'Southern Nations - Nationalities');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1077', '68', 'TG', 'Tigray');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1078', '68', 'AA', 'Addis Ababa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1079', '68', 'DD', 'Dire Dawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1080', '71', 'C', 'Central Division');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1081', '71', 'N', 'Northern Division');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1082', '71', 'E', 'Eastern Division');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1083', '71', 'W', 'Western Division');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1084', '71', 'R', 'Rotuma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1085', '72', 'AL', 'Ahvenanmaan Laani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1086', '72', 'ES', 'Etela-Suomen Laani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1087', '72', 'IS', 'Ita-Suomen Laani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1088', '72', 'LS', 'Lansi-Suomen Laani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1089', '72', 'LA', 'Lapin Lanani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1090', '72', 'OU', 'Oulun Laani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1091', '73', 'AL', 'Alsace');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1092', '73', 'AQ', 'Aquitaine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1093', '73', 'AU', 'Auvergne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1094', '73', 'BR', 'Brittany');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1095', '73', 'BU', 'Burgundy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1096', '73', 'CE', 'Center Loire Valley');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1097', '73', 'CH', 'Champagne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1098', '73', 'CO', 'Corse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1099', '73', 'FR', 'France Comte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1100', '73', 'LA', 'Languedoc Roussillon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1101', '73', 'LI', 'Limousin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1102', '73', 'LO', 'Lorraine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1103', '73', 'MI', 'Midi Pyrenees');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1104', '73', 'NO', 'Nord Pas de Calais');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1105', '73', 'NR', 'Normandy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1106', '73', 'PA', 'Paris / Ill de France');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1107', '73', 'PI', 'Picardie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1108', '73', 'PO', 'Poitou Charente');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1109', '73', 'PR', 'Provence');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1110', '73', 'RH', 'Rhone Alps');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1111', '73', 'RI', 'Riviera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1112', '73', 'WE', 'Western Loire Valley');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1113', '74', 'Et', 'Etranger');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1114', '74', '01', 'Ain');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1115', '74', '02', 'Aisne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1116', '74', '03', 'Allier');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1117', '74', '04', 'Alpes de Haute Provence');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1118', '74', '05', 'Hautes-Alpes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1119', '74', '06', 'Alpes Maritimes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1120', '74', '07', 'ArdÃƒÂ¨che');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1121', '74', '08', 'Ardennes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1122', '74', '09', 'AriÃƒÂ¨ge');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1123', '74', '10', 'Aube');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1124', '74', '11', 'Aude');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1125', '74', '12', 'Aveyron');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1126', '74', '13', 'Bouches du RhÃƒÂ´ne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1127', '74', '14', 'Calvados');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1128', '74', '15', 'Cantal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1129', '74', '16', 'Charente');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1130', '74', '17', 'Charente Maritime');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1131', '74', '18', 'Cher');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1132', '74', '19', 'CorrÃƒÂ¨ze');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1133', '74', '2A', 'Corse du Sud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1134', '74', '2B', 'Haute Corse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1135', '74', '21', 'CÃƒÂ´te d\'or');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1136', '74', '22', 'CÃƒÂ´tes d\'Armor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1137', '74', '23', 'Creuse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1138', '74', '24', 'Dordogne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1139', '74', '25', 'Doubs');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1140', '74', '26', 'DrÃƒÂ´me');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1141', '74', '27', 'Eure');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1142', '74', '28', 'Eure et Loir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1143', '74', '29', 'FinistÃƒÂ¨re');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1144', '74', '30', 'Gard');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1145', '74', '31', 'Haute Garonne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1146', '74', '32', 'Gers');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1147', '74', '33', 'Gironde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1148', '74', '34', 'HÃƒÂ©;rault');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1149', '74', '35', 'Ille et Vilaine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1150', '74', '36', 'Indre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1151', '74', '37', 'Indre et Loire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1152', '74', '38', 'IsÃƒÂ©;re');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1153', '74', '39', 'Jura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1154', '74', '40', 'Landes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1155', '74', '41', 'Loir et Cher');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1156', '74', '42', 'Loire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1157', '74', '43', 'Haute Loire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1158', '74', '44', 'Loire Atlantique');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1159', '74', '45', 'Loiret');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1160', '74', '46', 'Lot');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1161', '74', '47', 'Lot et Garonne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1162', '74', '48', 'LozÃƒÂ¨re');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1163', '74', '49', 'Maine et Loire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1164', '74', '50', 'Manche');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1165', '74', '51', 'Marne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1166', '74', '52', 'Haute Marne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1167', '74', '53', 'Mayenne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1168', '74', '54', 'Meurthe et Moselle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1169', '74', '55', 'Meuse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1170', '74', '56', 'Morbihan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1171', '74', '57', 'Moselle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1172', '74', '58', 'NiÃƒÂ¨vre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1173', '74', '59', 'Nord');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1174', '74', '60', 'Oise');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1175', '74', '61', 'Orne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1176', '74', '62', 'Pas de Calais');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1177', '74', '63', 'Puy de DÃƒÂ´me');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1178', '74', '64', 'PyrÃƒÂ©nÃƒÂ©es Atlantiqu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1179', '74', '65', 'Hautes PyrÃƒÂ©nÃƒÂ©es');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1180', '74', '66', 'PyrÃƒÂ©nÃƒÂ©es Orientale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1181', '74', '67', 'Bas Rhin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1182', '74', '68', 'Haut Rhin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1183', '74', '69', 'RhÃƒÂ´ne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1184', '74', '70', 'Haute SaÃƒÂ´ne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1185', '74', '71', 'SaÃƒÂ´ne et Loire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1186', '74', '72', 'Sarthe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1187', '74', '73', 'Savoie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1188', '74', '74', 'Haute Savoie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1189', '74', '75', 'Paris');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1190', '74', '76', 'Seine Maritime');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1191', '74', '77', 'Seine et Marne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1192', '74', '78', 'Yvelines');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1193', '74', '79', 'Deux SÃƒÂ¨vres');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1194', '74', '80', 'Somme');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1195', '74', '81', 'Tarn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1196', '74', '82', 'Tarn et Garonne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1197', '74', '83', 'Var');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1198', '74', '84', 'Vaucluse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1199', '74', '85', 'VendÃƒÂ©e');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1200', '74', '86', 'Vienne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1201', '74', '87', 'Haute Vienne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1202', '74', '88', 'Vosges');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1203', '74', '89', 'Yonne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1204', '74', '90', 'Territoire de Belfort');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1205', '74', '91', 'Essonne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1206', '74', '92', 'Hauts de Seine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1207', '74', '93', 'Seine St-Denis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1208', '74', '94', 'Val de Marne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1209', '74', '95', 'Val d\'Oise');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1210', '76', 'M', 'Archipel des Marquises');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1211', '76', 'T', 'Archipel des Tuamotu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1212', '76', 'I', 'Archipel des Tubuai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1213', '76', 'V', 'Iles du Vent');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1214', '76', 'S', 'Iles Sous-le-Vent');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1215', '77', 'C', 'Iles Crozet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1216', '77', 'K', 'Iles Kerguelen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1217', '77', 'A', 'Ile Amsterdam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1218', '77', 'P', 'Ile Saint-Paul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1219', '77', 'D', 'Adelie Land');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1220', '78', 'ES', 'Estuaire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1221', '78', 'HO', 'Haut-Ogooue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1222', '78', 'MO', 'Moyen-Ogooue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1223', '78', 'NG', 'Ngounie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1224', '78', 'NY', 'Nyanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1225', '78', 'OI', 'Ogooue-Ivindo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1226', '78', 'OL', 'Ogooue-Lolo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1227', '78', 'OM', 'Ogooue-Maritime');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1228', '78', 'WN', 'Woleu-Ntem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1229', '79', 'BJ', 'Banjul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1230', '79', 'BS', 'Basse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1231', '79', 'BR', 'Brikama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1232', '79', 'JA', 'Janjangbure');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1233', '79', 'KA', 'Kanifeng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1234', '79', 'KE', 'Kerewan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1235', '79', 'KU', 'Kuntaur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1236', '79', 'MA', 'Mansakonko');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1237', '79', 'LR', 'Lower River');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1238', '79', 'CR', 'Central River');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1239', '79', 'NB', 'North Bank');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1240', '79', 'UR', 'Upper River');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1241', '79', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1242', '80', 'AB', 'Abkhazia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1243', '80', 'AJ', 'Ajaria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1244', '80', 'TB', 'Tbilisi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1245', '80', 'GU', 'Guria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1246', '80', 'IM', 'Imereti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1247', '80', 'KA', 'Kakheti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1248', '80', 'KK', 'Kvemo Kartli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1249', '80', 'MM', 'Mtskheta-Mtianeti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1250', '80', 'RL', 'Racha Lechkhumi and Kvemo Svanet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1251', '80', 'SZ', 'Samegrelo-Zemo Svaneti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1252', '80', 'SJ', 'Samtskhe-Javakheti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1253', '80', 'SK', 'Shida Kartli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1254', '81', 'BAW', 'Baden-WÃƒÂ¼rttemberg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1255', '81', 'BAY', 'Bayern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1256', '81', 'BER', 'Berlin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1257', '81', 'BRG', 'Brandenburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1258', '81', 'BRE', 'Bremen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1259', '81', 'HAM', 'Hamburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1260', '81', 'HES', 'Hessen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1261', '81', 'MEC', 'Mecklenburg-Vorpommern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1262', '81', 'NDS', 'Niedersachsen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1263', '81', 'NRW', 'Nordrhein-Westfalen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1264', '81', 'RHE', 'Rheinland-Pfalz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1265', '81', 'SAR', 'Saarland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1266', '81', 'SAS', 'Sachsen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1267', '81', 'SAC', 'Sachsen-Anhalt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1268', '81', 'SCN', 'Schleswig-Holstein');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1269', '81', 'THE', 'ThÃƒÂ¼ringen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1270', '82', 'AS', 'Ashanti Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1271', '82', 'BA', 'Brong-Ahafo Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1272', '82', 'CE', 'Central Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1273', '82', 'EA', 'Eastern Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1274', '82', 'GA', 'Greater Accra Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1275', '82', 'NO', 'Northern Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1276', '82', 'UE', 'Upper East Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1277', '82', 'UW', 'Upper West Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1278', '82', 'VO', 'Volta Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1279', '82', 'WE', 'Western Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1280', '84', 'AT', 'Attica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1281', '84', 'CN', 'Central Greece');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1282', '84', 'CM', 'Central Macedonia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1283', '84', 'CR', 'Crete');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1284', '84', 'EM', 'East Macedonia and Thrace');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1285', '84', 'EP', 'Epirus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1286', '84', 'II', 'Ionian Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1287', '84', 'NA', 'North Aegean');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1288', '84', 'PP', 'Peloponnesos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1289', '84', 'SA', 'South Aegean');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1290', '84', 'TH', 'Thessaly');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1291', '84', 'WG', 'West Greece');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1292', '84', 'WM', 'West Macedonia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1293', '85', 'A', 'Avannaa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1294', '85', 'T', 'Tunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1295', '85', 'K', 'Kitaa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1296', '86', 'A', 'Saint Andrew');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1297', '86', 'D', 'Saint David');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1298', '86', 'G', 'Saint George');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1299', '86', 'J', 'Saint John');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1300', '86', 'M', 'Saint Mark');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1301', '86', 'P', 'Saint Patrick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1302', '86', 'C', 'Carriacou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1303', '86', 'Q', 'Petit Martinique');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1304', '89', 'AV', 'Alta Verapaz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1305', '89', 'BV', 'Baja Verapaz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1306', '89', 'CM', 'Chimaltenango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1307', '89', 'CQ', 'Chiquimula');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1308', '89', 'PE', 'El Peten');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1309', '89', 'PR', 'El Progreso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1310', '89', 'QC', 'El Quiche');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1311', '89', 'ES', 'Escuintla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1312', '89', 'GU', 'Guatemala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1313', '89', 'HU', 'Huehuetenango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1314', '89', 'IZ', 'Izabal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1315', '89', 'JA', 'Jalapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1316', '89', 'JU', 'Jutiapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1317', '89', 'QZ', 'Quetzaltenango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1318', '89', 'RE', 'Retalhuleu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1319', '89', 'ST', 'Sacatepequez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1320', '89', 'SM', 'San Marcos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1321', '89', 'SR', 'Santa Rosa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1322', '89', 'SO', 'Solola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1323', '89', 'SU', 'Suchitepequez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1324', '89', 'TO', 'Totonicapan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1325', '89', 'ZA', 'Zacapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1326', '90', 'CNK', 'Conakry');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1327', '90', 'BYL', 'Beyla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1328', '90', 'BFA', 'Boffa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1329', '90', 'BOK', 'Boke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1330', '90', 'COY', 'Coyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1331', '90', 'DBL', 'Dabola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1332', '90', 'DLB', 'Dalaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1333', '90', 'DGR', 'Dinguiraye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1334', '90', 'DBR', 'Dubreka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1335', '90', 'FRN', 'Faranah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1336', '90', 'FRC', 'Forecariah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1337', '90', 'FRI', 'Fria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1338', '90', 'GAO', 'Gaoual');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1339', '90', 'GCD', 'Gueckedou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1340', '90', 'KNK', 'Kankan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1341', '90', 'KRN', 'Kerouane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1342', '90', 'KND', 'Kindia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1343', '90', 'KSD', 'Kissidougou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1344', '90', 'KBA', 'Koubia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1345', '90', 'KDA', 'Koundara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1346', '90', 'KRA', 'Kouroussa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1347', '90', 'LAB', 'Labe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1348', '90', 'LLM', 'Lelouma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1349', '90', 'LOL', 'Lola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1350', '90', 'MCT', 'Macenta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1351', '90', 'MAL', 'Mali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1352', '90', 'MAM', 'Mamou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1353', '90', 'MAN', 'Mandiana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1354', '90', 'NZR', 'Nzerekore');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1355', '90', 'PIT', 'Pita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1356', '90', 'SIG', 'Siguiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1357', '90', 'TLM', 'Telimele');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1358', '90', 'TOG', 'Tougue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1359', '90', 'YOM', 'Yomou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1360', '91', 'BF', 'Bafata Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1361', '91', 'BB', 'Biombo Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1362', '91', 'BS', 'Bissau Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1363', '91', 'BL', 'Bolama Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1364', '91', 'CA', 'Cacheu Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1365', '91', 'GA', 'Gabu Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1366', '91', 'OI', 'Oio Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1367', '91', 'QU', 'Quinara Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1368', '91', 'TO', 'Tombali Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1369', '92', 'BW', 'Barima-Waini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1370', '92', 'CM', 'Cuyuni-Mazaruni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1371', '92', 'DM', 'Demerara-Mahaica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1372', '92', 'EC', 'East Berbice-Corentyne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1373', '92', 'EW', 'Essequibo Islands-West Demerara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1374', '92', 'MB', 'Mahaica-Berbice');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1375', '92', 'PM', 'Pomeroon-Supenaam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1376', '92', 'PI', 'Potaro-Siparuni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1377', '92', 'UD', 'Upper Demerara-Berbice');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1378', '92', 'UT', 'Upper Takutu-Upper Essequibo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1379', '93', 'AR', 'Artibonite');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1380', '93', 'CE', 'Centre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1381', '93', 'GA', 'Grand\'Anse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1382', '93', 'ND', 'Nord');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1383', '93', 'NE', 'Nord-Est');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1384', '93', 'NO', 'Nord-Ouest');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1385', '93', 'OU', 'Ouest');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1386', '93', 'SD', 'Sud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1387', '93', 'SE', 'Sud-Est');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1388', '94', 'F', 'Flat Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1389', '94', 'M', 'McDonald Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1390', '94', 'S', 'Shag Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1391', '94', 'H', 'Heard Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1392', '95', 'AT', 'Atlantida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1393', '95', 'CH', 'Choluteca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1394', '95', 'CL', 'Colon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1395', '95', 'CM', 'Comayagua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1396', '95', 'CP', 'Copan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1397', '95', 'CR', 'Cortes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1398', '95', 'PA', 'El Paraiso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1399', '95', 'FM', 'Francisco Morazan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1400', '95', 'GD', 'Gracias a Dios');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1401', '95', 'IN', 'Intibuca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1402', '95', 'IB', 'Islas de la Bahia (Bay Islands)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1403', '95', 'PZ', 'La Paz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1404', '95', 'LE', 'Lempira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1405', '95', 'OC', 'Ocotepeque');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1406', '95', 'OL', 'Olancho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1407', '95', 'SB', 'Santa Barbara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1408', '95', 'VA', 'Valle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1409', '95', 'YO', 'Yoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1410', '96', 'HCW', 'Central and Western Hong Kong Is');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1411', '96', 'HEA', 'Eastern Hong Kong Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1412', '96', 'HSO', 'Southern Hong Kong Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1413', '96', 'HWC', 'Wan Chai Hong Kong Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1414', '96', 'KKC', 'Kowloon City Kowloon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1415', '96', 'KKT', 'Kwun Tong Kowloon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1416', '96', 'KSS', 'Sham Shui Po Kowloon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1417', '96', 'KWT', 'Wong Tai Sin Kowloon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1418', '96', 'KYT', 'Yau Tsim Mong Kowloon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1419', '96', 'NIS', 'Islands New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1420', '96', 'NKT', 'Kwai Tsing New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1421', '96', 'NNO', 'North New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1422', '96', 'NSK', 'Sai Kung New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1423', '96', 'NST', 'Sha Tin New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1424', '96', 'NTP', 'Tai Po New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1425', '96', 'NTW', 'Tsuen Wan New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1426', '96', 'NTM', 'Tuen Mun New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1427', '96', 'NYL', 'Yuen Long New Territories');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1428', '97', 'BK', 'Bacs-Kiskun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1429', '97', 'BA', 'Baranya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1430', '97', 'BE', 'Bekes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1431', '97', 'BS', 'Bekescsaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1432', '97', 'BZ', 'Borsod-Abauj-Zemplen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1433', '97', 'BU', 'Budapest');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1434', '97', 'CS', 'Csongrad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1435', '97', 'DE', 'Debrecen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1436', '97', 'DU', 'Dunaujvaros');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1437', '97', 'EG', 'Eger');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1438', '97', 'FE', 'Fejer');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1439', '97', 'GY', 'Gyor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1440', '97', 'GM', 'Gyor-Moson-Sopron');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1441', '97', 'HB', 'Hajdu-Bihar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1442', '97', 'HE', 'Heves');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1443', '97', 'HO', 'Hodmezovasarhely');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1444', '97', 'JN', 'Jasz-Nagykun-Szolnok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1445', '97', 'KA', 'Kaposvar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1446', '97', 'KE', 'Kecskemet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1447', '97', 'KO', 'Komarom-Esztergom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1448', '97', 'MI', 'Miskolc');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1449', '97', 'NA', 'Nagykanizsa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1450', '97', 'NO', 'Nograd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1451', '97', 'NY', 'Nyiregyhaza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1452', '97', 'PE', 'Pecs');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1453', '97', 'PS', 'Pest');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1454', '97', 'SO', 'Somogy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1455', '97', 'SP', 'Sopron');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1456', '97', 'SS', 'Szabolcs-Szatmar-Bereg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1457', '97', 'SZ', 'Szeged');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1458', '97', 'SE', 'Szekesfehervar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1459', '97', 'SL', 'Szolnok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1460', '97', 'SM', 'Szombathely');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1461', '97', 'TA', 'Tatabanya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1462', '97', 'TO', 'Tolna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1463', '97', 'VA', 'Vas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1464', '97', 'VE', 'Veszprem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1465', '97', 'ZA', 'Zala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1466', '97', 'ZZ', 'Zalaegerszeg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1467', '98', 'AL', 'Austurland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1468', '98', 'HF', 'Hofuoborgarsvaeoi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1469', '98', 'NE', 'Norourland eystra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1470', '98', 'NV', 'Norourland vestra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1471', '98', 'SL', 'Suourland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1472', '98', 'SN', 'Suournes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1473', '98', 'VF', 'Vestfiroir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1474', '98', 'VL', 'Vesturland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1475', '99', 'AN', 'Andaman and Nicobar Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1476', '99', 'AP', 'Andhra Pradesh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1477', '99', 'AR', 'Arunachal Pradesh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1478', '99', 'AS', 'Assam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1479', '99', 'BI', 'Bihar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1480', '99', 'CH', 'Chandigarh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1481', '99', 'DA', 'Dadra and Nagar Haveli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1482', '99', 'DM', 'Daman and Diu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1483', '99', 'DE', 'Delhi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1484', '99', 'GO', 'Goa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1485', '99', 'GU', 'Gujarat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1486', '99', 'HA', 'Haryana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1487', '99', 'HP', 'Himachal Pradesh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1488', '99', 'JA', 'Jammu and Kashmir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1489', '99', 'KA', 'Karnataka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1490', '99', 'KE', 'Kerala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1491', '99', 'LI', 'Lakshadweep Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1492', '99', 'MP', 'Madhya Pradesh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1493', '99', 'MA', 'Maharashtra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1494', '99', 'MN', 'Manipur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1495', '99', 'ME', 'Meghalaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1496', '99', 'MI', 'Mizoram');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1497', '99', 'NA', 'Nagaland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1498', '99', 'OR', 'Orissa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1499', '99', 'PO', 'Pondicherry');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1500', '99', 'PU', 'Punjab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1501', '99', 'RA', 'Rajasthan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1502', '99', 'SI', 'Sikkim');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1503', '99', 'TN', 'Tamil Nadu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1504', '99', 'TR', 'Tripura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1505', '99', 'UP', 'Uttar Pradesh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1506', '99', 'WB', 'West Bengal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1507', '100', 'AC', 'Aceh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1508', '100', 'BA', 'Bali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1509', '100', 'BT', 'Banten');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1510', '100', 'BE', 'Bengkulu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1511', '100', 'BD', 'BoDeTaBek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1512', '100', 'GO', 'Gorontalo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1513', '100', 'JK', 'Jakarta Raya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1514', '100', 'JA', 'Jambi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1515', '100', 'JB', 'Jawa Barat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1516', '100', 'JT', 'Jawa Tengah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1517', '100', 'JI', 'Jawa Timur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1518', '100', 'KB', 'Kalimantan Barat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1519', '100', 'KS', 'Kalimantan Selatan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1520', '100', 'KT', 'Kalimantan Tengah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1521', '100', 'KI', 'Kalimantan Timur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1522', '100', 'BB', 'Kepulauan Bangka Belitung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1523', '100', 'LA', 'Lampung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1524', '100', 'MA', 'Maluku');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1525', '100', 'MU', 'Maluku Utara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1526', '100', 'NB', 'Nusa Tenggara Barat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1527', '100', 'NT', 'Nusa Tenggara Timur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1528', '100', 'PA', 'Papua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1529', '100', 'RI', 'Riau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1530', '100', 'SN', 'Sulawesi Selatan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1531', '100', 'ST', 'Sulawesi Tengah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1532', '100', 'SG', 'Sulawesi Tenggara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1533', '100', 'SA', 'Sulawesi Utara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1534', '100', 'SB', 'Sumatera Barat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1535', '100', 'SS', 'Sumatera Selatan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1536', '100', 'SU', 'Sumatera Utara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1537', '100', 'YO', 'Yogyakarta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1538', '101', 'TEH', 'Tehran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1539', '101', 'QOM', 'Qom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1540', '101', 'MKZ', 'Markazi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1541', '101', 'QAZ', 'Qazvin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1542', '101', 'GIL', 'Gilan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1543', '101', 'ARD', 'Ardabil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1544', '101', 'ZAN', 'Zanjan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1545', '101', 'EAZ', 'East Azarbaijan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1546', '101', 'WEZ', 'West Azarbaijan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1547', '101', 'KRD', 'Kurdistan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1548', '101', 'HMD', 'Hamadan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1549', '101', 'KRM', 'Kermanshah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1550', '101', 'ILM', 'Ilam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1551', '101', 'LRS', 'Lorestan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1552', '101', 'KZT', 'Khuzestan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1553', '101', 'CMB', 'Chahar Mahaal and Bakhtiari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1554', '101', 'KBA', 'Kohkiluyeh and Buyer Ahmad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1555', '101', 'BSH', 'Bushehr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1556', '101', 'FAR', 'Fars');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1557', '101', 'HRM', 'Hormozgan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1558', '101', 'SBL', 'Sistan and Baluchistan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1559', '101', 'KRB', 'Kerman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1560', '101', 'YZD', 'Yazd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1561', '101', 'EFH', 'Esfahan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1562', '101', 'SMN', 'Semnan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1563', '101', 'MZD', 'Mazandaran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1564', '101', 'GLS', 'Golestan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1565', '101', 'NKH', 'North Khorasan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1566', '101', 'RKH', 'Razavi Khorasan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1567', '101', 'SKH', 'South Khorasan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1568', '102', 'BD', 'Baghdad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1569', '102', 'SD', 'Salah ad Din');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1570', '102', 'DY', 'Diyala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1571', '102', 'WS', 'Wasit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1572', '102', 'MY', 'Maysan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1573', '102', 'BA', 'Al Basrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1574', '102', 'DQ', 'Dhi Qar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1575', '102', 'MU', 'Al Muthanna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1576', '102', 'QA', 'Al Qadisyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1577', '102', 'BB', 'Babil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1578', '102', 'KB', 'Al Karbala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1579', '102', 'NJ', 'An Najaf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1580', '102', 'AB', 'Al Anbar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1581', '102', 'NN', 'Ninawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1582', '102', 'DH', 'Dahuk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1583', '102', 'AL', 'Arbil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1584', '102', 'TM', 'At Ta\'mim');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1585', '102', 'SL', 'As Sulaymaniyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1586', '103', 'CA', 'Carlow');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1587', '103', 'CV', 'Cavan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1588', '103', 'CL', 'Clare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1589', '103', 'CO', 'Cork');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1590', '103', 'DO', 'Donegal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1591', '103', 'DU', 'Dublin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1592', '103', 'GA', 'Galway');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1593', '103', 'KE', 'Kerry');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1594', '103', 'KI', 'Kildare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1595', '103', 'KL', 'Kilkenny');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1596', '103', 'LA', 'Laois');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1597', '103', 'LE', 'Leitrim');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1598', '103', 'LI', 'Limerick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1599', '103', 'LO', 'Longford');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1600', '103', 'LU', 'Louth');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1601', '103', 'MA', 'Mayo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1602', '103', 'ME', 'Meath');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1603', '103', 'MO', 'Monaghan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1604', '103', 'OF', 'Offaly');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1605', '103', 'RO', 'Roscommon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1606', '103', 'SL', 'Sligo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1607', '103', 'TI', 'Tipperary');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1608', '103', 'WA', 'Waterford');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1609', '103', 'WE', 'Westmeath');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1610', '103', 'WX', 'Wexford');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1611', '103', 'WI', 'Wicklow');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1612', '104', 'BS', 'Be\'er Sheva');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1613', '104', 'BH', 'Bika\'at Hayarden');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1614', '104', 'EA', 'Eilat and Arava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1615', '104', 'GA', 'Galil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1616', '104', 'HA', 'Haifa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1617', '104', 'JM', 'Jehuda Mountains');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1618', '104', 'JE', 'Jerusalem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1619', '104', 'NE', 'Negev');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1620', '104', 'SE', 'Semaria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1621', '104', 'SH', 'Sharon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1622', '104', 'TA', 'Tel Aviv (Gosh Dan)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1623', '105', 'AB', 'Abruzzo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1624', '105', 'BA', 'Basilicata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1625', '105', 'CA', 'Calabria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1626', '105', 'CP', 'Campania');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1627', '105', 'ER', 'Emilia Romagna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1628', '105', 'FV', 'Friuli-Venezia Giulia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1629', '105', 'LA', 'Lazio (Latium & Rome)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1630', '105', 'TM', 'Le Marche (The Marches)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1631', '105', 'LI', 'Liguria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1632', '105', 'LO', 'Lombardia (Lombardy)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1633', '105', 'MO', 'Molise');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1634', '105', 'PI', 'Piemonte (Piedmont)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1635', '105', 'AP', 'Puglia (Apulia)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1636', '105', 'SA', 'Sardegna (Sardinia)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1637', '105', 'SI', 'Sicilia (Sicily)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1638', '105', 'TU', 'Toscana (Tuscany)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1639', '105', 'TR', 'Trentino Alto Adige');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1640', '105', 'UM', 'Umbria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1641', '105', 'VA', 'Val d\'Aosta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1642', '105', 'VE', 'Veneto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1643', '106', 'CLA', 'Clarendon Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1644', '106', 'HAN', 'Hanover Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1645', '106', 'KIN', 'Kingston Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1646', '106', 'MAN', 'Manchester Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1647', '106', 'POR', 'Portland Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1648', '106', 'AND', 'Saint Andrew Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1649', '106', 'ANN', 'Saint Ann Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1650', '106', 'CAT', 'Saint Catherine Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1651', '106', 'ELI', 'Saint Elizabeth Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1652', '106', 'JAM', 'Saint James Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1653', '106', 'MAR', 'Saint Mary Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1654', '106', 'THO', 'Saint Thomas Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1655', '106', 'TRL', 'Trelawny Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1656', '106', 'WML', 'Westmoreland Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1657', '107', 'AI', 'Aichi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1658', '107', 'AK', 'Akita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1659', '107', 'AO', 'Aomori');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1660', '107', 'CH', 'Chiba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1661', '107', 'EH', 'Ehime');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1662', '107', 'FK', 'Fukui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1663', '107', 'FU', 'Fukuoka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1664', '107', 'FS', 'Fukushima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1665', '107', 'GI', 'Gifu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1666', '107', 'GU', 'Gumma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1667', '107', 'HI', 'Hiroshima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1668', '107', 'HO', 'Hokkaido');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1669', '107', 'HY', 'Hyogo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1670', '107', 'IB', 'Ibaraki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1671', '107', 'IS', 'Ishikawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1672', '107', 'IW', 'Iwate');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1673', '107', 'KA', 'Kagawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1674', '107', 'KG', 'Kagoshima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1675', '107', 'KN', 'Kanagawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1676', '107', 'KO', 'Kochi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1677', '107', 'KU', 'Kumamoto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1678', '107', 'KY', 'Kyoto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1679', '107', 'MI', 'Mie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1680', '107', 'MY', 'Miyagi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1681', '107', 'MZ', 'Miyazaki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1682', '107', 'NA', 'Nagano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1683', '107', 'NG', 'Nagasaki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1684', '107', 'NR', 'Nara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1685', '107', 'NI', 'Niigata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1686', '107', 'OI', 'Oita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1687', '107', 'OK', 'Okayama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1688', '107', 'ON', 'Okinawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1689', '107', 'OS', 'Osaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1690', '107', 'SA', 'Saga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1691', '107', 'SI', 'Saitama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1692', '107', 'SH', 'Shiga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1693', '107', 'SM', 'Shimane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1694', '107', 'SZ', 'Shizuoka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1695', '107', 'TO', 'Tochigi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1696', '107', 'TS', 'Tokushima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1697', '107', 'TK', 'Tokyo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1698', '107', 'TT', 'Tottori');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1699', '107', 'TY', 'Toyama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1700', '107', 'WA', 'Wakayama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1701', '107', 'YA', 'Yamagata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1702', '107', 'YM', 'Yamaguchi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1703', '107', 'YN', 'Yamanashi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1704', '108', 'AM', '\'Amman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1705', '108', 'AJ', 'Ajlun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1706', '108', 'AA', 'Al \'Aqabah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1707', '108', 'AB', 'Al Balqa\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1708', '108', 'AK', 'Al Karak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1709', '108', 'AL', 'Al Mafraq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1710', '108', 'AT', 'At Tafilah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1711', '108', 'AZ', 'Az Zarqa\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1712', '108', 'IR', 'Irbid');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1713', '108', 'JA', 'Jarash');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1714', '108', 'MA', 'Ma\'an');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1715', '108', 'MD', 'Madaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1716', '109', 'AL', 'Almaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1717', '109', 'AC', 'Almaty City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1718', '109', 'AM', 'Aqmola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1719', '109', 'AQ', 'Aqtobe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1720', '109', 'AS', 'Astana City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1721', '109', 'AT', 'Atyrau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1722', '109', 'BA', 'Batys Qazaqstan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1723', '109', 'BY', 'Bayqongyr City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1724', '109', 'MA', 'Mangghystau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1725', '109', 'ON', 'Ongtustik Qazaqstan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1726', '109', 'PA', 'Pavlodar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1727', '109', 'QA', 'Qaraghandy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1728', '109', 'QO', 'Qostanay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1729', '109', 'QY', 'Qyzylorda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1730', '109', 'SH', 'Shyghys Qazaqstan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1731', '109', 'SO', 'Soltustik Qazaqstan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1732', '109', 'ZH', 'Zhambyl');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1733', '110', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1734', '110', 'CO', 'Coast');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1735', '110', 'EA', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1736', '110', 'NA', 'Nairobi Area');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1737', '110', 'NE', 'North Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1738', '110', 'NY', 'Nyanza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1739', '110', 'RV', 'Rift Valley');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1740', '110', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1741', '111', 'AG', 'Abaiang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1742', '111', 'AM', 'Abemama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1743', '111', 'AK', 'Aranuka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1744', '111', 'AO', 'Arorae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1745', '111', 'BA', 'Banaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1746', '111', 'BE', 'Beru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1747', '111', 'bT', 'Butaritari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1748', '111', 'KA', 'Kanton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1749', '111', 'KR', 'Kiritimati');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1750', '111', 'KU', 'Kuria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1751', '111', 'MI', 'Maiana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1752', '111', 'MN', 'Makin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1753', '111', 'ME', 'Marakei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1754', '111', 'NI', 'Nikunau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1755', '111', 'NO', 'Nonouti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1756', '111', 'ON', 'Onotoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1757', '111', 'TT', 'Tabiteuea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1758', '111', 'TR', 'Tabuaeran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1759', '111', 'TM', 'Tamana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1760', '111', 'TW', 'Tarawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1761', '111', 'TE', 'Teraina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1762', '112', 'CHA', 'Chagang-do');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1763', '112', 'HAB', 'Hamgyong-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1764', '112', 'HAN', 'Hamgyong-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1765', '112', 'HWB', 'Hwanghae-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1766', '112', 'HWN', 'Hwanghae-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1767', '112', 'KAN', 'Kangwon-do');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1768', '112', 'PYB', 'P\'yongan-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1769', '112', 'PYN', 'P\'yongan-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1770', '112', 'YAN', 'Ryanggang-do (Yanggang-do)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1771', '112', 'NAJ', 'Rason Directly Governed City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1772', '112', 'PYO', 'P\'yongyang Special City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1773', '113', 'CO', 'Ch\'ungch\'ong-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1774', '113', 'CH', 'Ch\'ungch\'ong-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1775', '113', 'CD', 'Cheju-do');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1776', '113', 'CB', 'Cholla-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1777', '113', 'CN', 'Cholla-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1778', '113', 'IG', 'Inch\'on-gwangyoksi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1779', '113', 'KA', 'Kangwon-do');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1780', '113', 'KG', 'Kwangju-gwangyoksi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1781', '113', 'KD', 'Kyonggi-do');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1782', '113', 'KB', 'Kyongsang-bukto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1783', '113', 'KN', 'Kyongsang-namdo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1784', '113', 'PG', 'Pusan-gwangyoksi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1785', '113', 'SO', 'Soul-t\'ukpyolsi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1786', '113', 'TA', 'Taegu-gwangyoksi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1787', '113', 'TG', 'Taejon-gwangyoksi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1788', '114', 'AL', 'Al \'Asimah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1789', '114', 'AA', 'Al Ahmadi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1790', '114', 'AF', 'Al Farwaniyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1791', '114', 'AJ', 'Al Jahra\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1792', '114', 'HA', 'Hawalli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1793', '115', 'GB', 'Bishkek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1794', '115', 'B', 'Batken');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1795', '115', 'C', 'Chu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1796', '115', 'J', 'Jalal-Abad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1797', '115', 'N', 'Naryn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1798', '115', 'O', 'Osh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1799', '115', 'T', 'Talas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1800', '115', 'Y', 'Ysyk-Kol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1801', '116', 'VT', 'Vientiane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1802', '116', 'AT', 'Attapu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1803', '116', 'BK', 'Bokeo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1804', '116', 'BL', 'Bolikhamxai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1805', '116', 'CH', 'Champasak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1806', '116', 'HO', 'Houaphan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1807', '116', 'KH', 'Khammouan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1808', '116', 'LM', 'Louang Namtha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1809', '116', 'LP', 'Louangphabang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1810', '116', 'OU', 'Oudomxai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1811', '116', 'PH', 'Phongsali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1812', '116', 'SL', 'Salavan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1813', '116', 'SV', 'Savannakhet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1814', '116', 'VI', 'Vientiane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1815', '116', 'XA', 'Xaignabouli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1816', '116', 'XE', 'Xekong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1817', '116', 'XI', 'Xiangkhoang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1818', '116', 'XN', 'Xaisomboun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1819', '117', 'AIZ', 'Aizkraukles Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1820', '117', 'ALU', 'Aluksnes Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1821', '117', 'BAL', 'Balvu Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1822', '117', 'BAU', 'Bauskas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1823', '117', 'CES', 'Cesu Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1824', '117', 'DGR', 'Daugavpils Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1825', '117', 'DOB', 'Dobeles Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1826', '117', 'GUL', 'Gulbenes Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1827', '117', 'JEK', 'Jekabpils Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1828', '117', 'JGR', 'Jelgavas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1829', '117', 'KRA', 'Kraslavas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1830', '117', 'KUL', 'Kuldigas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1831', '117', 'LPR', 'Liepajas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1832', '117', 'LIM', 'Limbazu Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1833', '117', 'LUD', 'Ludzas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1834', '117', 'MAD', 'Madonas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1835', '117', 'OGR', 'Ogres Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1836', '117', 'PRE', 'Preilu Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1837', '117', 'RZR', 'Rezeknes Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1838', '117', 'RGR', 'Rigas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1839', '117', 'SAL', 'Saldus Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1840', '117', 'TAL', 'Talsu Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1841', '117', 'TUK', 'Tukuma Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1842', '117', 'VLK', 'Valkas Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1843', '117', 'VLM', 'Valmieras Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1844', '117', 'VSR', 'Ventspils Rajons');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1845', '117', 'DGV', 'Daugavpils');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1846', '117', 'JGV', 'Jelgava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1847', '117', 'JUR', 'Jurmala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1848', '117', 'LPK', 'Liepaja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1849', '117', 'RZK', 'Rezekne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1850', '117', 'RGA', 'Riga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1851', '117', 'VSL', 'Ventspils');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1852', '119', 'BE', 'Berea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1853', '119', 'BB', 'Butha-Buthe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1854', '119', 'LE', 'Leribe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1855', '119', 'MF', 'Mafeteng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1856', '119', 'MS', 'Maseru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1857', '119', 'MH', 'Mohale\'s Hoek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1858', '119', 'MK', 'Mokhotlong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1859', '119', 'QN', 'Qacha\'s Nek');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1860', '119', 'QT', 'Quthing');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1861', '119', 'TT', 'Thaba-Tseka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1862', '120', 'BI', 'Bomi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1863', '120', 'BG', 'Bong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1864', '120', 'GB', 'Grand Bassa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1865', '120', 'CM', 'Grand Cape Mount');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1866', '120', 'GG', 'Grand Gedeh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1867', '120', 'GK', 'Grand Kru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1868', '120', 'LO', 'Lofa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1869', '120', 'MG', 'Margibi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1870', '120', 'ML', 'Maryland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1871', '120', 'MS', 'Montserrado');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1872', '120', 'NB', 'Nimba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1873', '120', 'RC', 'River Cess');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1874', '120', 'SN', 'Sinoe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1875', '121', 'AJ', 'Ajdabiya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1876', '121', 'AZ', 'Al \'Aziziyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1877', '121', 'FA', 'Al Fatih');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1878', '121', 'JA', 'Al Jabal al Akhdar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1879', '121', 'JU', 'Al Jufrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1880', '121', 'KH', 'Al Khums');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1881', '121', 'KU', 'Al Kufrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1882', '121', 'NK', 'An Nuqat al Khams');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1883', '121', 'AS', 'Ash Shati\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1884', '121', 'AW', 'Awbari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1885', '121', 'ZA', 'Az Zawiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1886', '121', 'BA', 'Banghazi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1887', '121', 'DA', 'Darnah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1888', '121', 'GD', 'Ghadamis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1889', '121', 'GY', 'Gharyan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1890', '121', 'MI', 'Misratah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1891', '121', 'MZ', 'Murzuq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1892', '121', 'SB', 'Sabha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1893', '121', 'SW', 'Sawfajjin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1894', '121', 'SU', 'Surt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1895', '121', 'TL', 'Tarabulus (Tripoli)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1896', '121', 'TH', 'Tarhunah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1897', '121', 'TU', 'Tubruq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1898', '121', 'YA', 'Yafran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1899', '121', 'ZL', 'Zlitan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1900', '122', 'V', 'Vaduz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1901', '122', 'A', 'Schaan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1902', '122', 'B', 'Balzers');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1903', '122', 'N', 'Triesen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1904', '122', 'E', 'Eschen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1905', '122', 'M', 'Mauren');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1906', '122', 'T', 'Triesenberg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1907', '122', 'R', 'Ruggell');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1908', '122', 'G', 'Gamprin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1909', '122', 'L', 'Schellenberg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1910', '122', 'P', 'Planken');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1911', '123', 'AL', 'Alytus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1912', '123', 'KA', 'Kaunas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1913', '123', 'KL', 'Klaipeda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1914', '123', 'MA', 'Marijampole');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1915', '123', 'PA', 'Panevezys');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1916', '123', 'SI', 'Siauliai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1917', '123', 'TA', 'Taurage');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1918', '123', 'TE', 'Telsiai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1919', '123', 'UT', 'Utena');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1920', '123', 'VI', 'Vilnius');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1921', '124', 'DD', 'Diekirch');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1922', '124', 'DC', 'Clervaux');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1923', '124', 'DR', 'Redange');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1924', '124', 'DV', 'Vianden');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1925', '124', 'DW', 'Wiltz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1926', '124', 'GG', 'Grevenmacher');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1927', '124', 'GE', 'Echternach');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1928', '124', 'GR', 'Remich');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1929', '124', 'LL', 'Luxembourg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1930', '124', 'LC', 'Capellen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1931', '124', 'LE', 'Esch-sur-Alzette');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1932', '124', 'LM', 'Mersch');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1933', '125', 'OLF', 'Our Lady Fatima Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1934', '125', 'ANT', 'St. Anthony Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1935', '125', 'LAZ', 'St. Lazarus Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1936', '125', 'CAT', 'Cathedral Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1937', '125', 'LAW', 'St. Lawrence Parish');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1938', '127', 'AN', 'Antananarivo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1939', '127', 'AS', 'Antsiranana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1940', '127', 'FN', 'Fianarantsoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1941', '127', 'MJ', 'Mahajanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1942', '127', 'TM', 'Toamasina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1943', '127', 'TL', 'Toliara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1944', '128', 'BLK', 'Balaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1945', '128', 'BLT', 'Blantyre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1946', '128', 'CKW', 'Chikwawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1947', '128', 'CRD', 'Chiradzulu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1948', '128', 'CTP', 'Chitipa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1949', '128', 'DDZ', 'Dedza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1950', '128', 'DWA', 'Dowa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1951', '128', 'KRG', 'Karonga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1952', '128', 'KSG', 'Kasungu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1953', '128', 'LKM', 'Likoma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1954', '128', 'LLG', 'Lilongwe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1955', '128', 'MCG', 'Machinga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1956', '128', 'MGC', 'Mangochi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1957', '128', 'MCH', 'Mchinji');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1958', '128', 'MLJ', 'Mulanje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1959', '128', 'MWZ', 'Mwanza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1960', '128', 'MZM', 'Mzimba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1961', '128', 'NTU', 'Ntcheu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1962', '128', 'NKB', 'Nkhata Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1963', '128', 'NKH', 'Nkhotakota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1964', '128', 'NSJ', 'Nsanje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1965', '128', 'NTI', 'Ntchisi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1966', '128', 'PHL', 'Phalombe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1967', '128', 'RMP', 'Rumphi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1968', '128', 'SLM', 'Salima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1969', '128', 'THY', 'Thyolo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1970', '128', 'ZBA', 'Zomba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1971', '129', 'JO', 'Johor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1972', '129', 'KE', 'Kedah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1973', '129', 'KL', 'Kelantan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1974', '129', 'LA', 'Labuan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1975', '129', 'ME', 'Melaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1976', '129', 'NS', 'Negeri Sembilan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1977', '129', 'PA', 'Pahang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1978', '129', 'PE', 'Perak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1979', '129', 'PR', 'Perlis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1980', '129', 'PP', 'Pulau Pinang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1981', '129', 'SA', 'Sabah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1982', '129', 'SR', 'Sarawak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1983', '129', 'SE', 'Selangor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1984', '129', 'TE', 'Terengganu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1985', '129', 'WP', 'Wilayah Persekutuan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1986', '130', 'THU', 'Thiladhunmathi Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1987', '130', 'THD', 'Thiladhunmathi Dhekunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1988', '130', 'MLU', 'Miladhunmadulu Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1989', '130', 'MLD', 'Miladhunmadulu Dhekunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1990', '130', 'MAU', 'Maalhosmadulu Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1991', '130', 'MAD', 'Maalhosmadulu Dhekunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1992', '130', 'FAA', 'Faadhippolhu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1993', '130', 'MAA', 'Male Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1994', '130', 'AAU', 'Ari Atoll Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1995', '130', 'AAD', 'Ari Atoll Dheknu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1996', '130', 'FEA', 'Felidhe Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1997', '130', 'MUA', 'Mulaku Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1998', '130', 'NAU', 'Nilandhe Atoll Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('1999', '130', 'NAD', 'Nilandhe Atoll Dhekunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2000', '130', 'KLH', 'Kolhumadulu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2001', '130', 'HDH', 'Hadhdhunmathi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2002', '130', 'HAU', 'Huvadhu Atoll Uthuru');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2003', '130', 'HAD', 'Huvadhu Atoll Dhekunu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2004', '130', 'FMU', 'Fua Mulaku');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2005', '130', 'ADD', 'Addu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2006', '131', 'GA', 'Gao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2007', '131', 'KY', 'Kayes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2008', '131', 'KD', 'Kidal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2009', '131', 'KL', 'Koulikoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2010', '131', 'MP', 'Mopti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2011', '131', 'SG', 'Segou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2012', '131', 'SK', 'Sikasso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2013', '131', 'TB', 'Tombouctou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2014', '131', 'CD', 'Bamako Capital District');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2015', '132', 'ATT', 'Attard');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2016', '132', 'BAL', 'Balzan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2017', '132', 'BGU', 'Birgu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2018', '132', 'BKK', 'Birkirkara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2019', '132', 'BRZ', 'Birzebbuga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2020', '132', 'BOR', 'Bormla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2021', '132', 'DIN', 'Dingli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2022', '132', 'FGU', 'Fgura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2023', '132', 'FLO', 'Floriana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2024', '132', 'GDJ', 'Gudja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2025', '132', 'GZR', 'Gzira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2026', '132', 'GRG', 'Gargur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2027', '132', 'GXQ', 'Gaxaq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2028', '132', 'HMR', 'Hamrun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2029', '132', 'IKL', 'Iklin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2030', '132', 'ISL', 'Isla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2031', '132', 'KLK', 'Kalkara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2032', '132', 'KRK', 'Kirkop');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2033', '132', 'LIJ', 'Lija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2034', '132', 'LUQ', 'Luqa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2035', '132', 'MRS', 'Marsa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2036', '132', 'MKL', 'Marsaskala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2037', '132', 'MXL', 'Marsaxlokk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2038', '132', 'MDN', 'Mdina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2039', '132', 'MEL', 'Melliea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2040', '132', 'MGR', 'Mgarr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2041', '132', 'MST', 'Mosta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2042', '132', 'MQA', 'Mqabba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2043', '132', 'MSI', 'Msida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2044', '132', 'MTF', 'Mtarfa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2045', '132', 'NAX', 'Naxxar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2046', '132', 'PAO', 'Paola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2047', '132', 'PEM', 'Pembroke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2048', '132', 'PIE', 'Pieta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2049', '132', 'QOR', 'Qormi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2050', '132', 'QRE', 'Qrendi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2051', '132', 'RAB', 'Rabat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2052', '132', 'SAF', 'Safi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2053', '132', 'SGI', 'San Giljan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2054', '132', 'SLU', 'Santa Lucija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2055', '132', 'SPB', 'San Pawl il-Bahar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2056', '132', 'SGW', 'San Gwann');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2057', '132', 'SVE', 'Santa Venera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2058', '132', 'SIG', 'Siggiewi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2059', '132', 'SLM', 'Sliema');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2060', '132', 'SWQ', 'Swieqi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2061', '132', 'TXB', 'Ta Xbiex');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2062', '132', 'TRX', 'Tarxien');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2063', '132', 'VLT', 'Valletta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2064', '132', 'XGJ', 'Xgajra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2065', '132', 'ZBR', 'Zabbar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2066', '132', 'ZBG', 'Zebbug');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2067', '132', 'ZJT', 'Zejtun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2068', '132', 'ZRQ', 'Zurrieq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2069', '132', 'FNT', 'Fontana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2070', '132', 'GHJ', 'Ghajnsielem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2071', '132', 'GHR', 'Gharb');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2072', '132', 'GHS', 'Ghasri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2073', '132', 'KRC', 'Kercem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2074', '132', 'MUN', 'Munxar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2075', '132', 'NAD', 'Nadur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2076', '132', 'QAL', 'Qala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2077', '132', 'VIC', 'Victoria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2078', '132', 'SLA', 'San Lawrenz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2079', '132', 'SNT', 'Sannat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2080', '132', 'ZAG', 'Xagra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2081', '132', 'XEW', 'Xewkija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2082', '132', 'ZEB', 'Zebbug');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2083', '133', 'ALG', 'Ailinginae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2084', '133', 'ALL', 'Ailinglaplap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2085', '133', 'ALK', 'Ailuk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2086', '133', 'ARN', 'Arno');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2087', '133', 'AUR', 'Aur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2088', '133', 'BKR', 'Bikar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2089', '133', 'BKN', 'Bikini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2090', '133', 'BKK', 'Bokak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2091', '133', 'EBN', 'Ebon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2092', '133', 'ENT', 'Enewetak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2093', '133', 'EKB', 'Erikub');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2094', '133', 'JBT', 'Jabat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2095', '133', 'JLT', 'Jaluit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2096', '133', 'JEM', 'Jemo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2097', '133', 'KIL', 'Kili');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2098', '133', 'KWJ', 'Kwajalein');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2099', '133', 'LAE', 'Lae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2100', '133', 'LIB', 'Lib');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2101', '133', 'LKP', 'Likiep');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2102', '133', 'MJR', 'Majuro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2103', '133', 'MLP', 'Maloelap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2104', '133', 'MJT', 'Mejit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2105', '133', 'MIL', 'Mili');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2106', '133', 'NMK', 'Namorik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2107', '133', 'NAM', 'Namu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2108', '133', 'RGL', 'Rongelap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2109', '133', 'RGK', 'Rongrik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2110', '133', 'TOK', 'Toke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2111', '133', 'UJA', 'Ujae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2112', '133', 'UJL', 'Ujelang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2113', '133', 'UTK', 'Utirik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2114', '133', 'WTH', 'Wotho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2115', '133', 'WTJ', 'Wotje');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2116', '135', 'AD', 'Adrar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2117', '135', 'AS', 'Assaba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2118', '135', 'BR', 'Brakna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2119', '135', 'DN', 'Dakhlet Nouadhibou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2120', '135', 'GO', 'Gorgol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2121', '135', 'GM', 'Guidimaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2122', '135', 'HC', 'Hodh Ech Chargui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2123', '135', 'HG', 'Hodh El Gharbi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2124', '135', 'IN', 'Inchiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2125', '135', 'TA', 'Tagant');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2126', '135', 'TZ', 'Tiris Zemmour');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2127', '135', 'TR', 'Trarza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2128', '135', 'NO', 'Nouakchott');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2129', '136', 'BR', 'Beau Bassin-Rose Hill');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2130', '136', 'CU', 'Curepipe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2131', '136', 'PU', 'Port Louis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2132', '136', 'QB', 'Quatre Bornes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2133', '136', 'VP', 'Vacoas-Phoenix');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2134', '136', 'AG', 'Agalega Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2135', '136', 'CC', 'Cargados Carajos Shoals (Saint B');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2136', '136', 'RO', 'Rodrigues');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2137', '136', 'BL', 'Black River');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2138', '136', 'FL', 'Flacq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2139', '136', 'GP', 'Grand Port');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2140', '136', 'MO', 'Moka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2141', '136', 'PA', 'Pamplemousses');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2142', '136', 'PW', 'Plaines Wilhems');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2143', '136', 'PL', 'Port Louis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2144', '136', 'RR', 'Riviere du Rempart');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2145', '136', 'SA', 'Savanne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2146', '138', 'BN', 'Baja California Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2147', '138', 'BS', 'Baja California Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2148', '138', 'CA', 'Campeche');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2149', '138', 'CI', 'Chiapas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2150', '138', 'CH', 'Chihuahua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2151', '138', 'CZ', 'Coahuila de Zaragoza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2152', '138', 'CL', 'Colima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2153', '138', 'DF', 'Distrito Federal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2154', '138', 'DU', 'Durango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2155', '138', 'GA', 'Guanajuato');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2156', '138', 'GE', 'Guerrero');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2157', '138', 'HI', 'Hidalgo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2158', '138', 'JA', 'Jalisco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2159', '138', 'ME', 'Mexico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2160', '138', 'MI', 'Michoacan de Ocampo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2161', '138', 'MO', 'Morelos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2162', '138', 'NA', 'Nayarit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2163', '138', 'NL', 'Nuevo Leon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2164', '138', 'OA', 'Oaxaca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2165', '138', 'PU', 'Puebla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2166', '138', 'QA', 'Queretaro de Arteaga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2167', '138', 'QR', 'Quintana Roo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2168', '138', 'SA', 'San Luis Potosi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2169', '138', 'SI', 'Sinaloa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2170', '138', 'SO', 'Sonora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2171', '138', 'TB', 'Tabasco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2172', '138', 'TM', 'Tamaulipas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2173', '138', 'TL', 'Tlaxcala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2174', '138', 'VE', 'Veracruz-Llave');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2175', '138', 'YU', 'Yucatan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2176', '138', 'ZA', 'Zacatecas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2177', '139', 'C', 'Chuuk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2178', '139', 'K', 'Kosrae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2179', '139', 'P', 'Pohnpei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2180', '139', 'Y', 'Yap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2181', '140', 'GA', 'Gagauzia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2182', '140', 'CU', 'Chisinau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2183', '140', 'BA', 'Balti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2184', '140', 'CA', 'Cahul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2185', '140', 'ED', 'Edinet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2186', '140', 'LA', 'Lapusna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2187', '140', 'OR', 'Orhei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2188', '140', 'SO', 'Soroca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2189', '140', 'TI', 'Tighina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2190', '140', 'UN', 'Ungheni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2191', '140', 'SN', 'StÃƒÂ®nga Nistrului');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2192', '141', 'FV', 'Fontvieille');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2193', '141', 'LC', 'La Condamine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2194', '141', 'MV', 'Monaco-Ville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2195', '141', 'MC', 'Monte-Carlo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2196', '142', '1', 'Ulanbaatar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2197', '142', '035', 'Orhon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2198', '142', '037', 'Darhan uul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2199', '142', '039', 'Hentiy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2200', '142', '041', 'Hovsgol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2201', '142', '043', 'Hovd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2202', '142', '046', 'Uvs');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2203', '142', '047', 'Tov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2204', '142', '049', 'Selenge');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2205', '142', '051', 'Suhbaatar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2206', '142', '053', 'Omnogovi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2207', '142', '055', 'Ovorhangay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2208', '142', '057', 'Dzavhan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2209', '142', '059', 'DundgovL');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2210', '142', '061', 'Dornod');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2211', '142', '063', 'Dornogov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2212', '142', '064', 'Govi-Sumber');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2213', '142', '065', 'Govi-Altay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2214', '142', '067', 'Bulgan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2215', '142', '069', 'Bayanhongor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2216', '142', '071', 'Bayan-Olgiy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2217', '142', '073', 'Arhangay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2218', '143', 'A', 'Saint Anthony');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2219', '143', 'G', 'Saint Georges');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2220', '143', 'P', 'Saint Peter');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2221', '144', 'AGD', 'Agadir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2222', '144', 'HOC', 'Al Hoceima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2223', '144', 'AZI', 'Azilal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2224', '144', 'BME', 'Beni Mellal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2225', '144', 'BSL', 'Ben Slimane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2226', '144', 'BLM', 'Boulemane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2227', '144', 'CBL', 'Casablanca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2228', '144', 'CHA', 'Chaouen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2229', '144', 'EJA', 'El Jadida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2230', '144', 'EKS', 'El Kelaa des Sraghna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2231', '144', 'ERA', 'Er Rachidia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2232', '144', 'ESS', 'Essaouira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2233', '144', 'FES', 'Fes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2234', '144', 'FIG', 'Figuig');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2235', '144', 'GLM', 'Guelmim');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2236', '144', 'IFR', 'Ifrane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2237', '144', 'KEN', 'Kenitra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2238', '144', 'KHM', 'Khemisset');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2239', '144', 'KHN', 'Khenifra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2240', '144', 'KHO', 'Khouribga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2241', '144', 'LYN', 'Laayoune');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2242', '144', 'LAR', 'Larache');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2243', '144', 'MRK', 'Marrakech');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2244', '144', 'MKN', 'Meknes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2245', '144', 'NAD', 'Nador');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2246', '144', 'ORZ', 'Ouarzazate');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2247', '144', 'OUJ', 'Oujda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2248', '144', 'RSA', 'Rabat-Sale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2249', '144', 'SAF', 'Safi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2250', '144', 'SET', 'Settat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2251', '144', 'SKA', 'Sidi Kacem');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2252', '144', 'TGR', 'Tangier');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2253', '144', 'TAN', 'Tan-Tan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2254', '144', 'TAO', 'Taounate');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2255', '144', 'TRD', 'Taroudannt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2256', '144', 'TAT', 'Tata');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2257', '144', 'TAZ', 'Taza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2258', '144', 'TET', 'Tetouan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2259', '144', 'TIZ', 'Tiznit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2260', '144', 'ADK', 'Ad Dakhla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2261', '144', 'BJD', 'Boujdour');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2262', '144', 'ESM', 'Es Smara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2263', '145', 'CD', 'Cabo Delgado');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2264', '145', 'GZ', 'Gaza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2265', '145', 'IN', 'Inhambane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2266', '145', 'MN', 'Manica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2267', '145', 'MC', 'Maputo (city)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2268', '145', 'MP', 'Maputo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2269', '145', 'NA', 'Nampula');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2270', '145', 'NI', 'Niassa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2271', '145', 'SO', 'Sofala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2272', '145', 'TE', 'Tete');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2273', '145', 'ZA', 'Zambezia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2274', '146', 'AY', 'Ayeyarwady');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2275', '146', 'BG', 'Bago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2276', '146', 'MG', 'Magway');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2277', '146', 'MD', 'Mandalay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2278', '146', 'SG', 'Sagaing');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2279', '146', 'TN', 'Tanintharyi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2280', '146', 'YG', 'Yangon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2281', '146', 'CH', 'Chin State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2282', '146', 'KC', 'Kachin State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2283', '146', 'KH', 'Kayah State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2284', '146', 'KN', 'Kayin State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2285', '146', 'MN', 'Mon State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2286', '146', 'RK', 'Rakhine State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2287', '146', 'SH', 'Shan State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2288', '147', 'CA', 'Caprivi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2289', '147', 'ER', 'Erongo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2290', '147', 'HA', 'Hardap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2291', '147', 'KR', 'Karas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2292', '147', 'KV', 'Kavango');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2293', '147', 'KH', 'Khomas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2294', '147', 'KU', 'Kunene');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2295', '147', 'OW', 'Ohangwena');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2296', '147', 'OK', 'Omaheke');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2297', '147', 'OT', 'Omusati');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2298', '147', 'ON', 'Oshana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2299', '147', 'OO', 'Oshikoto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2300', '147', 'OJ', 'Otjozondjupa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2301', '148', 'AO', 'Aiwo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2302', '148', 'AA', 'Anabar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2303', '148', 'AT', 'Anetan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2304', '148', 'AI', 'Anibare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2305', '148', 'BA', 'Baiti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2306', '148', 'BO', 'Boe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2307', '148', 'BU', 'Buada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2308', '148', 'DE', 'Denigomodu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2309', '148', 'EW', 'Ewa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2310', '148', 'IJ', 'Ijuw');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2311', '148', 'ME', 'Meneng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2312', '148', 'NI', 'Nibok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2313', '148', 'UA', 'Uaboe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2314', '148', 'YA', 'Yaren');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2315', '149', 'BA', 'Bagmati');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2316', '149', 'BH', 'Bheri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2317', '149', 'DH', 'Dhawalagiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2318', '149', 'GA', 'Gandaki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2319', '149', 'JA', 'Janakpur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2320', '149', 'KA', 'Karnali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2321', '149', 'KO', 'Kosi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2322', '149', 'LU', 'Lumbini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2323', '149', 'MA', 'Mahakali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2324', '149', 'ME', 'Mechi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2325', '149', 'NA', 'Narayani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2326', '149', 'RA', 'Rapti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2327', '149', 'SA', 'Sagarmatha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2328', '149', 'SE', 'Seti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2329', '150', 'DR', 'Drenthe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2330', '150', 'FL', 'Flevoland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2331', '150', 'FR', 'Friesland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2332', '150', 'GE', 'Gelderland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2333', '150', 'GR', 'Groningen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2334', '150', 'LI', 'Limburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2335', '150', 'NB', 'Noord Brabant');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2336', '150', 'NH', 'Noord Holland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2337', '150', 'OV', 'Overijssel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2338', '150', 'UT', 'Utrecht');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2339', '150', 'ZE', 'Zeeland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2340', '150', 'ZH', 'Zuid Holland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2341', '152', 'L', 'Iles Loyaute');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2342', '152', 'N', 'Nord');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2343', '152', 'S', 'Sud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2344', '153', 'AUK', 'Auckland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2345', '153', 'BOP', 'Bay of Plenty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2346', '153', 'CAN', 'Canterbury');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2347', '153', 'COR', 'Coromandel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2348', '153', 'GIS', 'Gisborne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2349', '153', 'FIO', 'Fiordland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2350', '153', 'HKB', 'Hawke\'s Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2351', '153', 'MBH', 'Marlborough');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2352', '153', 'MWT', 'Manawatu-Wanganui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2353', '153', 'MCM', 'Mt Cook-Mackenzie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2354', '153', 'NSN', 'Nelson');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2355', '153', 'NTL', 'Northland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2356', '153', 'OTA', 'Otago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2357', '153', 'STL', 'Southland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2358', '153', 'TKI', 'Taranaki');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2359', '153', 'WGN', 'Wellington');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2360', '153', 'WKO', 'Waikato');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2361', '153', 'WAI', 'Wairprarapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2362', '153', 'WTC', 'West Coast');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2363', '154', 'AN', 'Atlantico Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2364', '154', 'AS', 'Atlantico Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2365', '154', 'BO', 'Boaco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2366', '154', 'CA', 'Carazo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2367', '154', 'CI', 'Chinandega');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2368', '154', 'CO', 'Chontales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2369', '154', 'ES', 'Esteli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2370', '154', 'GR', 'Granada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2371', '154', 'JI', 'Jinotega');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2372', '154', 'LE', 'Leon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2373', '154', 'MD', 'Madriz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2374', '154', 'MN', 'Managua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2375', '154', 'MS', 'Masaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2376', '154', 'MT', 'Matagalpa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2377', '154', 'NS', 'Nuevo Segovia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2378', '154', 'RS', 'Rio San Juan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2379', '154', 'RI', 'Rivas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2380', '155', 'AG', 'Agadez');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2381', '155', 'DF', 'Diffa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2382', '155', 'DS', 'Dosso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2383', '155', 'MA', 'Maradi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2384', '155', 'NM', 'Niamey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2385', '155', 'TH', 'Tahoua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2386', '155', 'TL', 'Tillaberi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2387', '155', 'ZD', 'Zinder');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2388', '156', 'AB', 'Abia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2389', '156', 'CT', 'Abuja Federal Capital Territory');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2390', '156', 'AD', 'Adamawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2391', '156', 'AK', 'Akwa Ibom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2392', '156', 'AN', 'Anambra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2393', '156', 'BC', 'Bauchi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2394', '156', 'BY', 'Bayelsa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2395', '156', 'BN', 'Benue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2396', '156', 'BO', 'Borno');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2397', '156', 'CR', 'Cross River');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2398', '156', 'DE', 'Delta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2399', '156', 'EB', 'Ebonyi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2400', '156', 'ED', 'Edo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2401', '156', 'EK', 'Ekiti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2402', '156', 'EN', 'Enugu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2403', '156', 'GO', 'Gombe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2404', '156', 'IM', 'Imo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2405', '156', 'JI', 'Jigawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2406', '156', 'KD', 'Kaduna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2407', '156', 'KN', 'Kano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2408', '156', 'KT', 'Katsina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2409', '156', 'KE', 'Kebbi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2410', '156', 'KO', 'Kogi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2411', '156', 'KW', 'Kwara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2412', '156', 'LA', 'Lagos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2413', '156', 'NA', 'Nassarawa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2414', '156', 'NI', 'Niger');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2415', '156', 'OG', 'Ogun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2416', '156', 'ONG', 'Ondo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2417', '156', 'OS', 'Osun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2418', '156', 'OY', 'Oyo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2419', '156', 'PL', 'Plateau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2420', '156', 'RI', 'Rivers');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2421', '156', 'SO', 'Sokoto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2422', '156', 'TA', 'Taraba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2423', '156', 'YO', 'Yobe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2424', '156', 'ZA', 'Zamfara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2425', '159', 'N', 'Northern Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2426', '159', 'R', 'Rota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2427', '159', 'S', 'Saipan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2428', '159', 'T', 'Tinian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2429', '160', 'AK', 'Akershus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2430', '160', 'AA', 'Aust-Agder');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2431', '160', 'BU', 'Buskerud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2432', '160', 'FM', 'Finnmark');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2433', '160', 'HM', 'Hedmark');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2434', '160', 'HL', 'Hordaland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2435', '160', 'MR', 'More og Romdal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2436', '160', 'NT', 'Nord-Trondelag');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2437', '160', 'NL', 'Nordland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2438', '160', 'OF', 'Ostfold');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2439', '160', 'OP', 'Oppland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2440', '160', 'OL', 'Oslo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2441', '160', 'RL', 'Rogaland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2442', '160', 'ST', 'Sor-Trondelag');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2443', '160', 'SJ', 'Sogn og Fjordane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2444', '160', 'SV', 'Svalbard');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2445', '160', 'TM', 'Telemark');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2446', '160', 'TR', 'Troms');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2447', '160', 'VA', 'Vest-Agder');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2448', '160', 'VF', 'Vestfold');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2449', '161', 'DA', 'Ad Dakhiliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2450', '161', 'BA', 'Al Batinah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2451', '161', 'WU', 'Al Wusta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2452', '161', 'SH', 'Ash Sharqiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2453', '161', 'ZA', 'Az Zahirah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2454', '161', 'MA', 'Masqat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2455', '161', 'MU', 'Musandam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2456', '161', 'ZU', 'Zufar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2457', '162', 'B', 'Balochistan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2458', '162', 'T', 'Federally Administered Tribal Ar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2459', '162', 'I', 'Islamabad Capital Territory');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2460', '162', 'N', 'North-West Frontier');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2461', '162', 'P', 'Punjab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2462', '162', 'S', 'Sindh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2463', '163', 'AM', 'Aimeliik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2464', '163', 'AR', 'Airai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2465', '163', 'AN', 'Angaur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2466', '163', 'HA', 'Hatohobei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2467', '163', 'KA', 'Kayangel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2468', '163', 'KO', 'Koror');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2469', '163', 'ME', 'Melekeok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2470', '163', 'NA', 'Ngaraard');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2471', '163', 'NG', 'Ngarchelong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2472', '163', 'ND', 'Ngardmau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2473', '163', 'NT', 'Ngatpang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2474', '163', 'NC', 'Ngchesar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2475', '163', 'NR', 'Ngeremlengui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2476', '163', 'NW', 'Ngiwal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2477', '163', 'PE', 'Peleliu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2478', '163', 'SO', 'Sonsorol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2479', '164', 'BT', 'Bocas del Toro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2480', '164', 'CH', 'Chiriqui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2481', '164', 'CC', 'Cocle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2482', '164', 'CL', 'Colon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2483', '164', 'DA', 'Darien');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2484', '164', 'HE', 'Herrera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2485', '164', 'LS', 'Los Santos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2486', '164', 'PA', 'Panama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2487', '164', 'SB', 'San Blas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2488', '164', 'VG', 'Veraguas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2489', '165', 'BV', 'Bougainville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2490', '165', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2491', '165', 'CH', 'Chimbu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2492', '165', 'EH', 'Eastern Highlands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2493', '165', 'EB', 'East New Britain');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2494', '165', 'ES', 'East Sepik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2495', '165', 'EN', 'Enga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2496', '165', 'GU', 'Gulf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2497', '165', 'MD', 'Madang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2498', '165', 'MN', 'Manus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2499', '165', 'MB', 'Milne Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2500', '165', 'MR', 'Morobe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2501', '165', 'NC', 'National Capital');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2502', '165', 'NI', 'New Ireland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2503', '165', 'NO', 'Northern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2504', '165', 'SA', 'Sandaun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2505', '165', 'SH', 'Southern Highlands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2506', '165', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2507', '165', 'WH', 'Western Highlands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2508', '165', 'WB', 'West New Britain');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2509', '166', 'AG', 'Alto Paraguay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2510', '166', 'AN', 'Alto Parana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2511', '166', 'AM', 'Amambay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2512', '166', 'AS', 'Asuncion');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2513', '166', 'BO', 'Boqueron');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2514', '166', 'CG', 'Caaguazu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2515', '166', 'CZ', 'Caazapa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2516', '166', 'CN', 'Canindeyu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2517', '166', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2518', '166', 'CC', 'Concepcion');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2519', '166', 'CD', 'Cordillera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2520', '166', 'GU', 'Guaira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2521', '166', 'IT', 'Itapua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2522', '166', 'MI', 'Misiones');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2523', '166', 'NE', 'Neembucu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2524', '166', 'PA', 'Paraguari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2525', '166', 'PH', 'Presidente Hayes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2526', '166', 'SP', 'San Pedro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2527', '167', 'AM', 'Amazonas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2528', '167', 'AN', 'Ancash');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2529', '167', 'AP', 'Apurimac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2530', '167', 'AR', 'Arequipa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2531', '167', 'AY', 'Ayacucho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2532', '167', 'CJ', 'Cajamarca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2533', '167', 'CL', 'Callao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2534', '167', 'CU', 'Cusco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2535', '167', 'HV', 'Huancavelica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2536', '167', 'HO', 'Huanuco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2537', '167', 'IC', 'Ica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2538', '167', 'JU', 'Junin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2539', '167', 'LD', 'La Libertad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2540', '167', 'LY', 'Lambayeque');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2541', '167', 'LI', 'Lima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2542', '167', 'LO', 'Loreto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2543', '167', 'MD', 'Madre de Dios');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2544', '167', 'MO', 'Moquegua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2545', '167', 'PA', 'Pasco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2546', '167', 'PI', 'Piura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2547', '167', 'PU', 'Puno');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2548', '167', 'SM', 'San Martin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2549', '167', 'TA', 'Tacna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2550', '167', 'TU', 'Tumbes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2551', '167', 'UC', 'Ucayali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2552', '168', 'ABR', 'Abra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2553', '168', 'ANO', 'Agusan del Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2554', '168', 'ASU', 'Agusan del Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2555', '168', 'AKL', 'Aklan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2556', '168', 'ALB', 'Albay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2557', '168', 'ANT', 'Antique');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2558', '168', 'APY', 'Apayao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2559', '168', 'AUR', 'Aurora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2560', '168', 'BAS', 'Basilan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2561', '168', 'BTA', 'Bataan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2562', '168', 'BTE', 'Batanes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2563', '168', 'BTG', 'Batangas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2564', '168', 'BLR', 'Biliran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2565', '168', 'BEN', 'Benguet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2566', '168', 'BOL', 'Bohol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2567', '168', 'BUK', 'Bukidnon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2568', '168', 'BUL', 'Bulacan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2569', '168', 'CAG', 'Cagayan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2570', '168', 'CNO', 'Camarines Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2571', '168', 'CSU', 'Camarines Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2572', '168', 'CAM', 'Camiguin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2573', '168', 'CAP', 'Capiz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2574', '168', 'CAT', 'Catanduanes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2575', '168', 'CAV', 'Cavite');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2576', '168', 'CEB', 'Cebu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2577', '168', 'CMP', 'Compostela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2578', '168', 'DNO', 'Davao del Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2579', '168', 'DSU', 'Davao del Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2580', '168', 'DOR', 'Davao Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2581', '168', 'ESA', 'Eastern Samar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2582', '168', 'GUI', 'Guimaras');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2583', '168', 'IFU', 'Ifugao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2584', '168', 'INO', 'Ilocos Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2585', '168', 'ISU', 'Ilocos Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2586', '168', 'ILO', 'Iloilo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2587', '168', 'ISA', 'Isabela');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2588', '168', 'KAL', 'Kalinga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2589', '168', 'LAG', 'Laguna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2590', '168', 'LNO', 'Lanao del Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2591', '168', 'LSU', 'Lanao del Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2592', '168', 'UNI', 'La Union');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2593', '168', 'LEY', 'Leyte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2594', '168', 'MAG', 'Maguindanao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2595', '168', 'MRN', 'Marinduque');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2596', '168', 'MSB', 'Masbate');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2597', '168', 'MIC', 'Mindoro Occidental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2598', '168', 'MIR', 'Mindoro Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2599', '168', 'MSC', 'Misamis Occidental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2600', '168', 'MOR', 'Misamis Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2601', '168', 'MOP', 'Mountain');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2602', '168', 'NOC', 'Negros Occidental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2603', '168', 'NOR', 'Negros Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2604', '168', 'NCT', 'North Cotabato');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2605', '168', 'NSM', 'Northern Samar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2606', '168', 'NEC', 'Nueva Ecija');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2607', '168', 'NVZ', 'Nueva Vizcaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2608', '168', 'PLW', 'Palawan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2609', '168', 'PMP', 'Pampanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2610', '168', 'PNG', 'Pangasinan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2611', '168', 'QZN', 'Quezon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2612', '168', 'QRN', 'Quirino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2613', '168', 'RIZ', 'Rizal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2614', '168', 'ROM', 'Romblon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2615', '168', 'SMR', 'Samar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2616', '168', 'SRG', 'Sarangani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2617', '168', 'SQJ', 'Siquijor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2618', '168', 'SRS', 'Sorsogon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2619', '168', 'SCO', 'South Cotabato');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2620', '168', 'SLE', 'Southern Leyte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2621', '168', 'SKU', 'Sultan Kudarat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2622', '168', 'SLU', 'Sulu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2623', '168', 'SNO', 'Surigao del Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2624', '168', 'SSU', 'Surigao del Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2625', '168', 'TAR', 'Tarlac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2626', '168', 'TAW', 'Tawi-Tawi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2627', '168', 'ZBL', 'Zambales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2628', '168', 'ZNO', 'Zamboanga del Norte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2629', '168', 'ZSU', 'Zamboanga del Sur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2630', '168', 'ZSI', 'Zamboanga Sibugay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2631', '170', 'DO', 'Dolnoslaskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2632', '170', 'KP', 'Kujawsko-Pomorskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2633', '170', 'LO', 'Lodzkie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2634', '170', 'LL', 'Lubelskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2635', '170', 'LU', 'Lubuskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2636', '170', 'ML', 'Malopolskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2637', '170', 'MZ', 'Mazowieckie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2638', '170', 'OP', 'Opolskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2639', '170', 'PP', 'Podkarpackie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2640', '170', 'PL', 'Podlaskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2641', '170', 'PM', 'Pomorskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2642', '170', 'SL', 'Slaskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2643', '170', 'SW', 'Swietokrzyskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2644', '170', 'WM', 'Warminsko-Mazurskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2645', '170', 'WP', 'Wielkopolskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2646', '170', 'ZA', 'Zachodniopomorskie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2647', '198', 'P', 'Saint Pierre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2648', '198', 'M', 'Miquelon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2649', '171', 'AC', 'AÃƒÂ§ores');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2650', '171', 'AV', 'Aveiro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2651', '171', 'BE', 'Beja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2652', '171', 'BR', 'Braga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2653', '171', 'BA', 'BraganÃƒÂ§a');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2654', '171', 'CB', 'Castelo Branco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2655', '171', 'CO', 'Coimbra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2656', '171', 'EV', 'ÃƒÂ©;vora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2657', '171', 'FA', 'Faro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2658', '171', 'GU', 'Guarda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2659', '171', 'LE', 'Leiria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2660', '171', 'LI', 'Lisboa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2661', '171', 'ME', 'Madeira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2662', '171', 'PO', 'Portalegre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2663', '171', 'PR', 'Porto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2664', '171', 'SA', 'SantarÃƒÂ©;m');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2665', '171', 'SE', 'SetÃƒÂºbal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2666', '171', 'VC', 'Viana do Castelo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2667', '171', 'VR', 'Vila Real');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2668', '171', 'VI', 'Viseu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2669', '173', 'DW', 'Ad Dawhah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2670', '173', 'GW', 'Al Ghuwayriyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2671', '173', 'JM', 'Al Jumayliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2672', '173', 'KR', 'Al Khawr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2673', '173', 'WK', 'Al Wakrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2674', '173', 'RN', 'Ar Rayyan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2675', '173', 'JB', 'Jarayan al Batinah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2676', '173', 'MS', 'Madinat ash Shamal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2677', '173', 'UD', 'Umm Sa\'id');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2678', '173', 'UL', 'Umm Salal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2679', '175', 'AB', 'Alba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2680', '175', 'AR', 'Arad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2681', '175', 'AG', 'Arges');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2682', '175', 'BC', 'Bacau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2683', '175', 'BH', 'Bihor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2684', '175', 'BN', 'Bistrita-Nasaud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2685', '175', 'BT', 'Botosani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2686', '175', 'BV', 'Brasov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2687', '175', 'BR', 'Braila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2688', '175', 'B', 'Bucuresti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2689', '175', 'BZ', 'Buzau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2690', '175', 'CS', 'Caras-Severin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2691', '175', 'CL', 'Calarasi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2692', '175', 'CJ', 'Cluj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2693', '175', 'CT', 'Constanta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2694', '175', 'CV', 'Covasna');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2695', '175', 'DB', 'Dimbovita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2696', '175', 'DJ', 'Dolj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2697', '175', 'GL', 'Galati');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2698', '175', 'GR', 'Giurgiu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2699', '175', 'GJ', 'Gorj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2700', '175', 'HR', 'Harghita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2701', '175', 'HD', 'Hunedoara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2702', '175', 'IL', 'Ialomita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2703', '175', 'IS', 'Iasi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2704', '175', 'IF', 'Ilfov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2705', '175', 'MM', 'Maramures');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2706', '175', 'MH', 'Mehedinti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2707', '175', 'MS', 'Mures');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2708', '175', 'NT', 'Neamt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2709', '175', 'OT', 'Olt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2710', '175', 'PH', 'Prahova');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2711', '175', 'SM', 'Satu-Mare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2712', '175', 'SJ', 'Salaj');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2713', '175', 'SB', 'Sibiu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2714', '175', 'SV', 'Suceava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2715', '175', 'TR', 'Teleorman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2716', '175', 'TM', 'Timis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2717', '175', 'TL', 'Tulcea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2718', '175', 'VS', 'Vaslui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2719', '175', 'VL', 'Valcea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2720', '175', 'VN', 'Vrancea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2721', '176', 'AB', 'Abakan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2722', '176', 'AG', 'Aginskoye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2723', '176', 'AN', 'Anadyr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2724', '176', 'AR', 'Arkahangelsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2725', '176', 'AS', 'Astrakhan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2726', '176', 'BA', 'Barnaul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2727', '176', 'BE', 'Belgorod');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2728', '176', 'BI', 'Birobidzhan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2729', '176', 'BL', 'Blagoveshchensk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2730', '176', 'BR', 'Bryansk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2731', '176', 'CH', 'Cheboksary');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2732', '176', 'CL', 'Chelyabinsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2733', '176', 'CR', 'Cherkessk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2734', '176', 'CI', 'Chita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2735', '176', 'DU', 'Dudinka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2736', '176', 'EL', 'Elista');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2737', '176', 'GO', 'Gomo-Altaysk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2738', '176', 'GA', 'Gorno-Altaysk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2739', '176', 'GR', 'Groznyy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2740', '176', 'IR', 'Irkutsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2741', '176', 'IV', 'Ivanovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2742', '176', 'IZ', 'Izhevsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2743', '176', 'KA', 'Kalinigrad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2744', '176', 'KL', 'Kaluga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2745', '176', 'KS', 'Kasnodar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2746', '176', 'KZ', 'Kazan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2747', '176', 'KE', 'Kemerovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2748', '176', 'KH', 'Khabarovsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2749', '176', 'KM', 'Khanty-Mansiysk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2750', '176', 'KO', 'Kostroma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2751', '176', 'KR', 'Krasnodar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2752', '176', 'KN', 'Krasnoyarsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2753', '176', 'KU', 'Kudymkar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2754', '176', 'KG', 'Kurgan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2755', '176', 'KK', 'Kursk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2756', '176', 'KY', 'Kyzyl');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2757', '176', 'LI', 'Lipetsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2758', '176', 'MA', 'Magadan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2759', '176', 'MK', 'Makhachkala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2760', '176', 'MY', 'Maykop');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2761', '176', 'MO', 'Moscow');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2762', '176', 'MU', 'Murmansk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2763', '176', 'NA', 'Nalchik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2764', '176', 'NR', 'Naryan Mar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2765', '176', 'NZ', 'Nazran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2766', '176', 'NI', 'Nizhniy Novgorod');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2767', '176', 'NO', 'Novgorod');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2768', '176', 'NV', 'Novosibirsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2769', '176', 'OM', 'Omsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2770', '176', 'OR', 'Orel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2771', '176', 'OE', 'Orenburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2772', '176', 'PA', 'Palana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2773', '176', 'PE', 'Penza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2774', '176', 'PR', 'Perm');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2775', '176', 'PK', 'Petropavlovsk-Kamchatskiy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2776', '176', 'PT', 'Petrozavodsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2777', '176', 'PS', 'Pskov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2778', '176', 'RO', 'Rostov-na-Donu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2779', '176', 'RY', 'Ryazan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2780', '176', 'SL', 'Salekhard');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2781', '176', 'SA', 'Samara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2782', '176', 'SR', 'Saransk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2783', '176', 'SV', 'Saratov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2784', '176', 'SM', 'Smolensk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2785', '176', 'SP', 'St. Petersburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2786', '176', 'ST', 'Stavropol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2787', '176', 'SY', 'Syktyvkar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2788', '176', 'TA', 'Tambov');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2789', '176', 'TO', 'Tomsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2790', '176', 'TU', 'Tula');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2791', '176', 'TR', 'Tura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2792', '176', 'TV', 'Tver');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2793', '176', 'TY', 'Tyumen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2794', '176', 'UF', 'Ufa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2795', '176', 'UL', 'Ul\'yanovsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2796', '176', 'UU', 'Ulan-Ude');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2797', '176', 'US', 'Ust\'-Ordynskiy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2798', '176', 'VL', 'Vladikavkaz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2799', '176', 'VA', 'Vladimir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2800', '176', 'VV', 'Vladivostok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2801', '176', 'VG', 'Volgograd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2802', '176', 'VD', 'Vologda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2803', '176', 'VO', 'Voronezh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2804', '176', 'VY', 'Vyatka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2805', '176', 'YA', 'Yakutsk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2806', '176', 'YR', 'Yaroslavl');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2807', '176', 'YE', 'Yekaterinburg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2808', '176', 'YO', 'Yoshkar-Ola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2809', '177', 'BU', 'Butare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2810', '177', 'BY', 'Byumba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2811', '177', 'CY', 'Cyangugu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2812', '177', 'GK', 'Gikongoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2813', '177', 'GS', 'Gisenyi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2814', '177', 'GT', 'Gitarama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2815', '177', 'KG', 'Kibungo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2816', '177', 'KY', 'Kibuye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2817', '177', 'KR', 'Kigali Rurale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2818', '177', 'KV', 'Kigali-ville');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2819', '177', 'RU', 'Ruhengeri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2820', '177', 'UM', 'Umutara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2821', '178', 'CCN', 'Christ Church Nichola Town');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2822', '178', 'SAS', 'Saint Anne Sandy Point');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2823', '178', 'SGB', 'Saint George Basseterre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2824', '178', 'SGG', 'Saint George Gingerland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2825', '178', 'SJW', 'Saint James Windward');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2826', '178', 'SJC', 'Saint John Capesterre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2827', '178', 'SJF', 'Saint John Figtree');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2828', '178', 'SMC', 'Saint Mary Cayon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2829', '178', 'CAP', 'Saint Paul Capesterre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2830', '178', 'CHA', 'Saint Paul Charlestown');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2831', '178', 'SPB', 'Saint Peter Basseterre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2832', '178', 'STL', 'Saint Thomas Lowland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2833', '178', 'STM', 'Saint Thomas Middle Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2834', '178', 'TPP', 'Trinity Palmetto Point');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2835', '179', 'AR', 'Anse-la-Raye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2836', '179', 'CA', 'Castries');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2837', '179', 'CH', 'Choiseul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2838', '179', 'DA', 'Dauphin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2839', '179', 'DE', 'Dennery');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2840', '179', 'GI', 'Gros-Islet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2841', '179', 'LA', 'Laborie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2842', '179', 'MI', 'Micoud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2843', '179', 'PR', 'Praslin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2844', '179', 'SO', 'Soufriere');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2845', '179', 'VF', 'Vieux-Fort');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2846', '180', 'C', 'Charlotte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2847', '180', 'R', 'Grenadines');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2848', '180', 'A', 'Saint Andrew');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2849', '180', 'D', 'Saint David');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2850', '180', 'G', 'Saint George');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2851', '180', 'P', 'Saint Patrick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2852', '181', 'AN', 'A\'ana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2853', '181', 'AI', 'Aiga-i-le-Tai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2854', '181', 'AT', 'Atua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2855', '181', 'FA', 'Fa\'asaleleaga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2856', '181', 'GE', 'Gaga\'emauga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2857', '181', 'GF', 'Gagaifomauga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2858', '181', 'PA', 'Palauli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2859', '181', 'SA', 'Satupa\'itea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2860', '181', 'TU', 'Tuamasaga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2861', '181', 'VF', 'Va\'a-o-Fonoti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2862', '181', 'VS', 'Vaisigano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2863', '182', 'AC', 'Acquaviva');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2864', '182', 'BM', 'Borgo Maggiore');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2865', '182', 'CH', 'Chiesanuova');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2866', '182', 'DO', 'Domagnano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2867', '182', 'FA', 'Faetano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2868', '182', 'FI', 'Fiorentino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2869', '182', 'MO', 'Montegiardino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2870', '182', 'SM', 'Citta di San Marino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2871', '182', 'SE', 'Serravalle');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2872', '183', 'S', 'Sao Tome');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2873', '183', 'P', 'Principe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2874', '184', 'BH', 'Al Bahah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2875', '184', 'HS', 'Al Hudud ash Shamaliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2876', '184', 'JF', 'Al Jawf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2877', '184', 'MD', 'Al Madinah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2878', '184', 'QS', 'Al Qasim');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2879', '184', 'RD', 'Ar Riyad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2880', '184', 'AQ', 'Ash Sharqiyah (Eastern)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2881', '184', 'AS', '\'Asir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2882', '184', 'HL', 'Ha\'il');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2883', '184', 'JZ', 'Jizan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2884', '184', 'ML', 'Makkah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2885', '184', 'NR', 'Najran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2886', '184', 'TB', 'Tabuk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2887', '185', 'DA', 'Dakar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2888', '185', 'DI', 'Diourbel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2889', '185', 'FA', 'Fatick');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2890', '185', 'KA', 'Kaolack');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2891', '185', 'KO', 'Kolda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2892', '185', 'LO', 'Louga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2893', '185', 'MA', 'Matam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2894', '185', 'SL', 'Saint-Louis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2895', '185', 'TA', 'Tambacounda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2896', '185', 'TH', 'Thies');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2897', '185', 'ZI', 'Ziguinchor');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2898', '186', 'AP', 'Anse aux Pins');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2899', '186', 'AB', 'Anse Boileau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2900', '186', 'AE', 'Anse Etoile');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2901', '186', 'AL', 'Anse Louis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2902', '186', 'AR', 'Anse Royale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2903', '186', 'BL', 'Baie Lazare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2904', '186', 'BS', 'Baie Sainte Anne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2905', '186', 'BV', 'Beau Vallon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2906', '186', 'BA', 'Bel Air');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2907', '186', 'BO', 'Bel Ombre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2908', '186', 'CA', 'Cascade');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2909', '186', 'GL', 'Glacis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2910', '186', 'GM', 'Grand\' Anse (on Mahe)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2911', '186', 'GP', 'Grand\' Anse (on Praslin)');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2912', '186', 'DG', 'La Digue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2913', '186', 'RA', 'La Riviere Anglaise');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2914', '186', 'MB', 'Mont Buxton');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2915', '186', 'MF', 'Mont Fleuri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2916', '186', 'PL', 'Plaisance');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2917', '186', 'PR', 'Pointe La Rue');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2918', '186', 'PG', 'Port Glaud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2919', '186', 'SL', 'Saint Louis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2920', '186', 'TA', 'Takamaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2921', '187', 'E', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2922', '187', 'N', 'Northern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2923', '187', 'S', 'Southern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2924', '187', 'W', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2925', '189', 'BA', 'Banskobystricky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2926', '189', 'BR', 'Bratislavsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2927', '189', 'KO', 'Kosicky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2928', '189', 'NI', 'Nitriansky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2929', '189', 'PR', 'Presovsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2930', '189', 'TC', 'Trenciansky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2931', '189', 'TV', 'Trnavsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2932', '189', 'ZI', 'Zilinsky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2933', '191', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2934', '191', 'CH', 'Choiseul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2935', '191', 'GC', 'Guadalcanal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2936', '191', 'HO', 'Honiara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2937', '191', 'IS', 'Isabel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2938', '191', 'MK', 'Makira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2939', '191', 'ML', 'Malaita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2940', '191', 'RB', 'Rennell and Bellona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2941', '191', 'TM', 'Temotu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2942', '191', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2943', '192', 'AW', 'Awdal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2944', '192', 'BK', 'Bakool');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2945', '192', 'BN', 'Banaadir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2946', '192', 'BR', 'Bari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2947', '192', 'BY', 'Bay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2948', '192', 'GA', 'Galguduud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2949', '192', 'GE', 'Gedo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2950', '192', 'HI', 'Hiiraan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2951', '192', 'JD', 'Jubbada Dhexe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2952', '192', 'JH', 'Jubbada Hoose');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2953', '192', 'MU', 'Mudug');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2954', '192', 'NU', 'Nugaal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2955', '192', 'SA', 'Sanaag');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2956', '192', 'SD', 'Shabeellaha Dhexe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2957', '192', 'SH', 'Shabeellaha Hoose');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2958', '192', 'SL', 'Sool');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2959', '192', 'TO', 'Togdheer');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2960', '192', 'WG', 'Woqooyi Galbeed');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2961', '193', 'EC', 'Eastern Cape');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2962', '193', 'FS', 'Free State');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2963', '193', 'GT', 'Gauteng');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2964', '193', 'KN', 'KwaZulu-Natal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2965', '193', 'LP', 'Limpopo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2966', '193', 'MP', 'Mpumalanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2967', '193', 'NW', 'North West');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2968', '193', 'NC', 'Northern Cape');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2969', '193', 'WC', 'Western Cape');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2970', '195', 'CA', 'A CoruÃƒÂ±a');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2971', '195', 'AL', 'ÃƒÂlava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2972', '195', 'AB', 'Albacete');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2973', '195', 'AC', 'Alicante');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2974', '195', 'AM', 'Almeria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2975', '195', 'AS', 'Asturias');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2976', '195', 'AV', 'ÃƒÂvila');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2977', '195', 'BJ', 'Badajoz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2978', '195', 'IB', 'Baleares');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2979', '195', 'BA', 'Barcelona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2980', '195', 'BU', 'Burgos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2981', '195', 'CC', 'CÃƒÂ¡ceres');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2982', '195', 'CZ', 'CÃƒÂ¡diz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2983', '195', 'CT', 'Cantabria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2984', '195', 'CL', 'CastellÃƒÂ³n');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2985', '195', 'CE', 'Ceuta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2986', '195', 'CR', 'Ciudad Real');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2987', '195', 'CD', 'CÃƒÂ³rdoba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2988', '195', 'CU', 'Cuenca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2989', '195', 'GI', 'Girona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2990', '195', 'GD', 'Granada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2991', '195', 'GJ', 'Guadalajara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2992', '195', 'GP', 'GuipÃƒÂºzcoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2993', '195', 'HL', 'Huelva');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2994', '195', 'HS', 'Huesca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2995', '195', 'JN', 'JaÃƒÂ©n');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2996', '195', 'RJ', 'La Rioja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2997', '195', 'PM', 'Las Palmas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2998', '195', 'LE', 'Leon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('2999', '195', 'LL', 'Lleida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3000', '195', 'LG', 'Lugo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3001', '195', 'MD', 'Madrid');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3002', '195', 'MA', 'Malaga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3003', '195', 'ML', 'Melilla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3004', '195', 'MU', 'Murcia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3005', '195', 'NV', 'Navarra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3006', '195', 'OU', 'Ourense');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3007', '195', 'PL', 'Palencia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3008', '195', 'PO', 'Pontevedra');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3009', '195', 'SL', 'Salamanca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3010', '195', 'SC', 'Santa Cruz de Tenerife');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3011', '195', 'SG', 'Segovia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3012', '195', 'SV', 'Sevilla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3013', '195', 'SO', 'Soria');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3014', '195', 'TA', 'Tarragona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3015', '195', 'TE', 'Teruel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3016', '195', 'TO', 'Toledo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3017', '195', 'VC', 'Valencia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3018', '195', 'VD', 'Valladolid');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3019', '195', 'VZ', 'Vizcaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3020', '195', 'ZM', 'Zamora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3021', '195', 'ZR', 'Zaragoza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3022', '196', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3023', '196', 'EA', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3024', '196', 'NC', 'North Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3025', '196', 'NO', 'Northern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3026', '196', 'NW', 'North Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3027', '196', 'SA', 'Sabaragamuwa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3028', '196', 'SO', 'Southern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3029', '196', 'UV', 'Uva');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3030', '196', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3031', '197', 'A', 'Ascension');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3032', '197', 'S', 'Saint Helena');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3033', '197', 'T', 'Tristan da Cunha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3034', '199', 'ANL', 'A\'ali an Nil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3035', '199', 'BAM', 'Al Bahr al Ahmar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3036', '199', 'BRT', 'Al Buhayrat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3037', '199', 'JZR', 'Al Jazirah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3038', '199', 'KRT', 'Al Khartum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3039', '199', 'QDR', 'Al Qadarif');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3040', '199', 'WDH', 'Al Wahdah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3041', '199', 'ANB', 'An Nil al Abyad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3042', '199', 'ANZ', 'An Nil al Azraq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3043', '199', 'ASH', 'Ash Shamaliyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3044', '199', 'BJA', 'Bahr al Jabal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3045', '199', 'GIS', 'Gharb al Istiwa\'iyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3046', '199', 'GBG', 'Gharb Bahr al Ghazal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3047', '199', 'GDA', 'Gharb Darfur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3048', '199', 'GKU', 'Gharb Kurdufan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3049', '199', 'JDA', 'Janub Darfur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3050', '199', 'JKU', 'Janub Kurdufan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3051', '199', 'JQL', 'Junqali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3052', '199', 'KSL', 'Kassala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3053', '199', 'NNL', 'Nahr an Nil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3054', '199', 'SBG', 'Shamal Bahr al Ghazal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3055', '199', 'SDA', 'Shamal Darfur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3056', '199', 'SKU', 'Shamal Kurdufan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3057', '199', 'SIS', 'Sharq al Istiwa\'iyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3058', '199', 'SNR', 'Sinnar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3059', '199', 'WRB', 'Warab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3060', '200', 'BR', 'Brokopondo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3061', '200', 'CM', 'Commewijne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3062', '200', 'CR', 'Coronie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3063', '200', 'MA', 'Marowijne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3064', '200', 'NI', 'Nickerie');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3065', '200', 'PA', 'Para');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3066', '200', 'PM', 'Paramaribo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3067', '200', 'SA', 'Saramacca');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3068', '200', 'SI', 'Sipaliwini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3069', '200', 'WA', 'Wanica');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3070', '202', 'H', 'Hhohho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3071', '202', 'L', 'Lubombo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3072', '202', 'M', 'Manzini');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3073', '202', 'S', 'Shishelweni');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3074', '203', 'K', 'Blekinge');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3075', '203', 'W', 'Dalama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3076', '203', 'X', 'GÃƒÂ¤vleborg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3077', '203', 'I', 'Gotland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3078', '203', 'N', 'Halland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3079', '203', 'Z', 'JÃƒÂ¤mtland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3080', '203', 'F', 'JÃƒÂ¶nkping');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3081', '203', 'H', 'Kalmar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3082', '203', 'G', 'Kronoberg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3083', '203', 'BD', 'Norrbotten');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3084', '203', 'T', 'Ãƒâ€“rebro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3085', '203', 'E', 'Ãƒâ€“stergÃƒÂ¶tland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3086', '203', 'M', 'SkÃƒÂ¥ne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3087', '203', 'D', 'SÃƒÂ¶dermanland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3088', '203', 'AB', 'Stockholm');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3089', '203', 'C', 'Uppsala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3090', '203', 'S', 'VÃƒÂ¤rmland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3091', '203', 'AC', 'VÃƒÂ¤sterbotten');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3092', '203', 'Y', 'VÃƒÂ¤sternorrland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3093', '203', 'U', 'VÃƒÂ¤stmanland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3094', '203', 'O', 'VÃƒÂ¤stra GÃƒÂ¶taland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3095', '204', 'AG', 'Aargau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3096', '204', 'AR', 'Appenzell Ausserrhoden');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3097', '204', 'AI', 'Appenzell Innerrhoden');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3098', '204', 'BS', 'Basel-Stadt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3099', '204', 'BL', 'Basel-Landschaft');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3100', '204', 'BE', 'Bern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3101', '204', 'FR', 'Fribourg');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3102', '204', 'GE', 'GenÃƒÂ¨ve');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3103', '204', 'GL', 'Glarus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3104', '204', 'GR', 'GraubÃƒÂ¼nden');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3105', '204', 'JU', 'Jura');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3106', '204', 'LU', 'Luzern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3107', '204', 'NE', 'NeuchÃƒÂ¢tel');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3108', '204', 'NW', 'Nidwald');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3109', '204', 'OW', 'Obwald');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3110', '204', 'SG', 'St. Gallen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3111', '204', 'SH', 'Schaffhausen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3112', '204', 'SZ', 'Schwyz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3113', '204', 'SO', 'Solothurn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3114', '204', 'TG', 'Thurgau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3115', '204', 'TI', 'Ticino');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3116', '204', 'UR', 'Uri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3117', '204', 'VS', 'Valais');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3118', '204', 'VD', 'Vaud');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3119', '204', 'ZG', 'Zug');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3120', '204', 'ZH', 'ZÃƒÂ¼rich');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3121', '205', 'HA', 'Al Hasakah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3122', '205', 'LA', 'Al Ladhiqiyah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3123', '205', 'QU', 'Al Qunaytirah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3124', '205', 'RQ', 'Ar Raqqah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3125', '205', 'SU', 'As Suwayda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3126', '205', 'DA', 'Dara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3127', '205', 'DZ', 'Dayr az Zawr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3128', '205', 'DI', 'Dimashq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3129', '205', 'HL', 'Halab');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3130', '205', 'HM', 'Hamah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3131', '205', 'HI', 'Hims');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3132', '205', 'ID', 'Idlib');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3133', '205', 'RD', 'Rif Dimashq');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3134', '205', 'TA', 'Tartus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3135', '206', 'CH', 'Chang-hua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3136', '206', 'CI', 'Chia-i');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3137', '206', 'HS', 'Hsin-chu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3138', '206', 'HL', 'Hua-lien');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3139', '206', 'IL', 'I-lan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3140', '206', 'KH', 'Kao-hsiung county');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3141', '206', 'KM', 'Kin-men');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3142', '206', 'LC', 'Lien-chiang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3143', '206', 'ML', 'Miao-li');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3144', '206', 'NT', 'Nan-t\'ou');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3145', '206', 'PH', 'P\'eng-hu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3146', '206', 'PT', 'P\'ing-tung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3147', '206', 'TG', 'T\'ai-chung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3148', '206', 'TA', 'T\'ai-nan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3149', '206', 'TP', 'T\'ai-pei county');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3150', '206', 'TT', 'T\'ai-tung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3151', '206', 'TY', 'T\'ao-yuan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3152', '206', 'YL', 'Yun-lin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3153', '206', 'CC', 'Chia-i city');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3154', '206', 'CL', 'Chi-lung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3155', '206', 'HC', 'Hsin-chu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3156', '206', 'TH', 'T\'ai-chung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3157', '206', 'TN', 'T\'ai-nan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3158', '206', 'KC', 'Kao-hsiung city');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3159', '206', 'TC', 'T\'ai-pei city');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3160', '207', 'GB', 'Gorno-Badakhstan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3161', '207', 'KT', 'Khatlon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3162', '207', 'SU', 'Sughd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3163', '208', 'AR', 'Arusha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3164', '208', 'DS', 'Dar es Salaam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3165', '208', 'DO', 'Dodoma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3166', '208', 'IR', 'Iringa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3167', '208', 'KA', 'Kagera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3168', '208', 'KI', 'Kigoma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3169', '208', 'KJ', 'Kilimanjaro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3170', '208', 'LN', 'Lindi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3171', '208', 'MY', 'Manyara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3172', '208', 'MR', 'Mara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3173', '208', 'MB', 'Mbeya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3174', '208', 'MO', 'Morogoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3175', '208', 'MT', 'Mtwara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3176', '208', 'MW', 'Mwanza');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3177', '208', 'PN', 'Pemba North');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3178', '208', 'PS', 'Pemba South');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3179', '208', 'PW', 'Pwani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3180', '208', 'RK', 'Rukwa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3181', '208', 'RV', 'Ruvuma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3182', '208', 'SH', 'Shinyanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3183', '208', 'SI', 'Singida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3184', '208', 'TB', 'Tabora');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3185', '208', 'TN', 'Tanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3186', '208', 'ZC', 'Zanzibar Central/South');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3187', '208', 'ZN', 'Zanzibar North');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3188', '208', 'ZU', 'Zanzibar Urban/West');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3189', '209', 'Amnat Charoen', 'Amnat Charoen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3190', '209', 'Ang Thong', 'Ang Thong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3191', '209', 'Ayutthaya', 'Ayutthaya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3192', '209', 'Bangkok', 'Bangkok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3193', '209', 'Buriram', 'Buriram');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3194', '209', 'Chachoengsao', 'Chachoengsao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3195', '209', 'Chai Nat', 'Chai Nat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3196', '209', 'Chaiyaphum', 'Chaiyaphum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3197', '209', 'Chanthaburi', 'Chanthaburi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3198', '209', 'Chiang Mai', 'Chiang Mai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3199', '209', 'Chiang Rai', 'Chiang Rai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3200', '209', 'Chon Buri', 'Chon Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3201', '209', 'Chumphon', 'Chumphon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3202', '209', 'Kalasin', 'Kalasin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3203', '209', 'Kamphaeng Phet', 'Kamphaeng Phet');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3204', '209', 'Kanchanaburi', 'Kanchanaburi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3205', '209', 'Khon Kaen', 'Khon Kaen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3206', '209', 'Krabi', 'Krabi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3207', '209', 'Lampang', 'Lampang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3208', '209', 'Lamphun', 'Lamphun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3209', '209', 'Loei', 'Loei');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3210', '209', 'Lop Buri', 'Lop Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3211', '209', 'Mae Hong Son', 'Mae Hong Son');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3212', '209', 'Maha Sarakham', 'Maha Sarakham');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3213', '209', 'Mukdahan', 'Mukdahan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3214', '209', 'Nakhon Nayok', 'Nakhon Nayok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3215', '209', 'Nakhon Pathom', 'Nakhon Pathom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3216', '209', 'Nakhon Phanom', 'Nakhon Phanom');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3217', '209', 'Nakhon Ratchasima', 'Nakhon Ratchasima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3218', '209', 'Nakhon Sawan', 'Nakhon Sawan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3219', '209', 'Nakhon Si Thammarat', 'Nakhon Si Thammarat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3220', '209', 'Nan', 'Nan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3221', '209', 'Narathiwat', 'Narathiwat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3222', '209', 'Nong Bua Lamphu', 'Nong Bua Lamphu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3223', '209', 'Nong Khai', 'Nong Khai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3224', '209', 'Nonthaburi', 'Nonthaburi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3225', '209', 'Pathum Thani', 'Pathum Thani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3226', '209', 'Pattani', 'Pattani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3227', '209', 'Phangnga', 'Phangnga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3228', '209', 'Phatthalung', 'Phatthalung');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3229', '209', 'Phayao', 'Phayao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3230', '209', 'Phetchabun', 'Phetchabun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3231', '209', 'Phetchaburi', 'Phetchaburi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3232', '209', 'Phichit', 'Phichit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3233', '209', 'Phitsanulok', 'Phitsanulok');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3234', '209', 'Phrae', 'Phrae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3235', '209', 'Phuket', 'Phuket');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3236', '209', 'Prachin Buri', 'Prachin Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3237', '209', 'Prachuap Khiri Khan', 'Prachuap Khiri Khan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3238', '209', 'Ranong', 'Ranong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3239', '209', 'Ratchaburi', 'Ratchaburi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3240', '209', 'Rayong', 'Rayong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3241', '209', 'Roi Et', 'Roi Et');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3242', '209', 'Sa Kaeo', 'Sa Kaeo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3243', '209', 'Sakon Nakhon', 'Sakon Nakhon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3244', '209', 'Samut Prakan', 'Samut Prakan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3245', '209', 'Samut Sakhon', 'Samut Sakhon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3246', '209', 'Samut Songkhram', 'Samut Songkhram');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3247', '209', 'Sara Buri', 'Sara Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3248', '209', 'Satun', 'Satun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3249', '209', 'Sing Buri', 'Sing Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3250', '209', 'Sisaket', 'Sisaket');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3251', '209', 'Songkhla', 'Songkhla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3252', '209', 'Sukhothai', 'Sukhothai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3253', '209', 'Suphan Buri', 'Suphan Buri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3254', '209', 'Surat Thani', 'Surat Thani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3255', '209', 'Surin', 'Surin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3256', '209', 'Tak', 'Tak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3257', '209', 'Trang', 'Trang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3258', '209', 'Trat', 'Trat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3259', '209', 'Ubon Ratchathani', 'Ubon Ratchathani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3260', '209', 'Udon Thani', 'Udon Thani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3261', '209', 'Uthai Thani', 'Uthai Thani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3262', '209', 'Uttaradit', 'Uttaradit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3263', '209', 'Yala', 'Yala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3264', '209', 'Yasothon', 'Yasothon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3265', '210', 'K', 'Kara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3266', '210', 'P', 'Plateaux');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3267', '210', 'S', 'Savanes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3268', '210', 'C', 'Centrale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3269', '210', 'M', 'Maritime');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3270', '211', 'A', 'Atafu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3271', '211', 'F', 'Fakaofo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3272', '211', 'N', 'Nukunonu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3273', '212', 'H', 'Ha\'apai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3274', '212', 'T', 'Tongatapu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3275', '212', 'V', 'Vava\'u');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3276', '213', 'CT', 'Couva/Tabaquite/Talparo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3277', '213', 'DM', 'Diego Martin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3278', '213', 'MR', 'Mayaro/Rio Claro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3279', '213', 'PD', 'Penal/Debe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3280', '213', 'PT', 'Princes Town');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3281', '213', 'SG', 'Sangre Grande');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3282', '213', 'SL', 'San Juan/Laventille');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3283', '213', 'SI', 'Siparia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3284', '213', 'TP', 'Tunapuna/Piarco');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3285', '213', 'PS', 'Port of Spain');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3286', '213', 'SF', 'San Fernando');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3287', '213', 'AR', 'Arima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3288', '213', 'PF', 'Point Fortin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3289', '213', 'CH', 'Chaguanas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3290', '213', 'TO', 'Tobago');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3291', '214', 'AR', 'Ariana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3292', '214', 'BJ', 'Beja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3293', '214', 'BA', 'Ben Arous');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3294', '214', 'BI', 'Bizerte');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3295', '214', 'GB', 'Gabes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3296', '214', 'GF', 'Gafsa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3297', '214', 'JE', 'Jendouba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3298', '214', 'KR', 'Kairouan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3299', '214', 'KS', 'Kasserine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3300', '214', 'KB', 'Kebili');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3301', '214', 'KF', 'Kef');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3302', '214', 'MH', 'Mahdia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3303', '214', 'MN', 'Manouba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3304', '214', 'ME', 'Medenine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3305', '214', 'MO', 'Monastir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3306', '214', 'NA', 'Nabeul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3307', '214', 'SF', 'Sfax');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3308', '214', 'SD', 'Sidi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3309', '214', 'SL', 'Siliana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3310', '214', 'SO', 'Sousse');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3311', '214', 'TA', 'Tataouine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3312', '214', 'TO', 'Tozeur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3313', '214', 'TU', 'Tunis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3314', '214', 'ZA', 'Zaghouan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3315', '215', 'ADA', 'Adana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3316', '215', 'ADI', 'Adiyaman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3317', '215', 'AFY', 'Afyonkarahisar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3318', '215', 'AGR', 'Agri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3319', '215', 'AKS', 'Aksaray');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3320', '215', 'AMA', 'Amasya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3321', '215', 'ANK', 'Ankara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3322', '215', 'ANT', 'Antalya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3323', '215', 'ARD', 'Ardahan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3324', '215', 'ART', 'Artvin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3325', '215', 'AYI', 'Aydin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3326', '215', 'BAL', 'Balikesir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3327', '215', 'BAR', 'Bartin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3328', '215', 'BAT', 'Batman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3329', '215', 'BAY', 'Bayburt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3330', '215', 'BIL', 'Bilecik');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3331', '215', 'BIN', 'Bingol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3332', '215', 'BIT', 'Bitlis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3333', '215', 'BOL', 'Bolu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3334', '215', 'BRD', 'Burdur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3335', '215', 'BRS', 'Bursa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3336', '215', 'CKL', 'Canakkale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3337', '215', 'CKR', 'Cankiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3338', '215', 'COR', 'Corum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3339', '215', 'DEN', 'Denizli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3340', '215', 'DIY', 'Diyarbakir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3341', '215', 'DUZ', 'Duzce');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3342', '215', 'EDI', 'Edirne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3343', '215', 'ELA', 'Elazig');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3344', '215', 'EZC', 'Erzincan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3345', '215', 'EZR', 'Erzurum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3346', '215', 'ESK', 'Eskisehir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3347', '215', 'GAZ', 'Gaziantep');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3348', '215', 'GIR', 'Giresun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3349', '215', 'GMS', 'Gumushane');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3350', '215', 'HKR', 'Hakkari');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3351', '215', 'HTY', 'Hatay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3352', '215', 'IGD', 'Igdir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3353', '215', 'ISP', 'Isparta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3354', '215', 'IST', 'Istanbul');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3355', '215', 'IZM', 'Izmir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3356', '215', 'KAH', 'Kahramanmaras');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3357', '215', 'KRB', 'Karabuk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3358', '215', 'KRM', 'Karaman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3359', '215', 'KRS', 'Kars');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3360', '215', 'KAS', 'Kastamonu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3361', '215', 'KAY', 'Kayseri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3362', '215', 'KLS', 'Kilis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3363', '215', 'KRK', 'Kirikkale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3364', '215', 'KLR', 'Kirklareli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3365', '215', 'KRH', 'Kirsehir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3366', '215', 'KOC', 'Kocaeli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3367', '215', 'KON', 'Konya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3368', '215', 'KUT', 'Kutahya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3369', '215', 'MAL', 'Malatya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3370', '215', 'MAN', 'Manisa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3371', '215', 'MAR', 'Mardin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3372', '215', 'MER', 'Mersin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3373', '215', 'MUG', 'Mugla');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3374', '215', 'MUS', 'Mus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3375', '215', 'NEV', 'Nevsehir');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3376', '215', 'NIG', 'Nigde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3377', '215', 'ORD', 'Ordu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3378', '215', 'OSM', 'Osmaniye');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3379', '215', 'RIZ', 'Rize');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3380', '215', 'SAK', 'Sakarya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3381', '215', 'SAM', 'Samsun');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3382', '215', 'SAN', 'Sanliurfa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3383', '215', 'SII', 'Siirt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3384', '215', 'SIN', 'Sinop');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3385', '215', 'SIR', 'Sirnak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3386', '215', 'SIV', 'Sivas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3387', '215', 'TEL', 'Tekirdag');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3388', '215', 'TOK', 'Tokat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3389', '215', 'TRA', 'Trabzon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3390', '215', 'TUN', 'Tunceli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3391', '215', 'USK', 'Usak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3392', '215', 'VAN', 'Van');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3393', '215', 'YAL', 'Yalova');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3394', '215', 'YOZ', 'Yozgat');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3395', '215', 'ZON', 'Zonguldak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3396', '216', 'A', 'Ahal Welayaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3397', '216', 'B', 'Balkan Welayaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3398', '216', 'D', 'Dashhowuz Welayaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3399', '216', 'L', 'Lebap Welayaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3400', '216', 'M', 'Mary Welayaty');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3401', '217', 'AC', 'Ambergris Cays');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3402', '217', 'DC', 'Dellis Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3403', '217', 'FC', 'French Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3404', '217', 'LW', 'Little Water Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3405', '217', 'RC', 'Parrot Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3406', '217', 'PN', 'Pine Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3407', '217', 'SL', 'Salt Cay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3408', '217', 'GT', 'Grand Turk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3409', '217', 'SC', 'South Caicos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3410', '217', 'EC', 'East Caicos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3411', '217', 'MC', 'Middle Caicos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3412', '217', 'NC', 'North Caicos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3413', '217', 'PR', 'Providenciales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3414', '217', 'WC', 'West Caicos');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3415', '218', 'NMG', 'Nanumanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3416', '218', 'NLK', 'Niulakita');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3417', '218', 'NTO', 'Niutao');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3418', '218', 'FUN', 'Funafuti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3419', '218', 'NME', 'Nanumea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3420', '218', 'NUI', 'Nui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3421', '218', 'NFT', 'Nukufetau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3422', '218', 'NLL', 'Nukulaelae');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3423', '218', 'VAI', 'Vaitupu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3424', '219', 'KAL', 'Kalangala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3425', '219', 'KMP', 'Kampala');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3426', '219', 'KAY', 'Kayunga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3427', '219', 'KIB', 'Kiboga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3428', '219', 'LUW', 'Luwero');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3429', '219', 'MAS', 'Masaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3430', '219', 'MPI', 'Mpigi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3431', '219', 'MUB', 'Mubende');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3432', '219', 'MUK', 'Mukono');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3433', '219', 'NKS', 'Nakasongola');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3434', '219', 'RAK', 'Rakai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3435', '219', 'SEM', 'Sembabule');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3436', '219', 'WAK', 'Wakiso');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3437', '219', 'BUG', 'Bugiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3438', '219', 'BUS', 'Busia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3439', '219', 'IGA', 'Iganga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3440', '219', 'JIN', 'Jinja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3441', '219', 'KAB', 'Kaberamaido');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3442', '219', 'KML', 'Kamuli');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3443', '219', 'KPC', 'Kapchorwa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3444', '219', 'KTK', 'Katakwi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3445', '219', 'KUM', 'Kumi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3446', '219', 'MAY', 'Mayuge');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3447', '219', 'MBA', 'Mbale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3448', '219', 'PAL', 'Pallisa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3449', '219', 'SIR', 'Sironko');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3450', '219', 'SOR', 'Soroti');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3451', '219', 'TOR', 'Tororo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3452', '219', 'ADJ', 'Adjumani');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3453', '219', 'APC', 'Apac');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3454', '219', 'ARU', 'Arua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3455', '219', 'GUL', 'Gulu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3456', '219', 'KIT', 'Kitgum');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3457', '219', 'KOT', 'Kotido');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3458', '219', 'LIR', 'Lira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3459', '219', 'MRT', 'Moroto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3460', '219', 'MOY', 'Moyo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3461', '219', 'NAK', 'Nakapiripirit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3462', '219', 'NEB', 'Nebbi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3463', '219', 'PAD', 'Pader');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3464', '219', 'YUM', 'Yumbe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3465', '219', 'BUN', 'Bundibugyo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3466', '219', 'BSH', 'Bushenyi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3467', '219', 'HOI', 'Hoima');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3468', '219', 'KBL', 'Kabale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3469', '219', 'KAR', 'Kabarole');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3470', '219', 'KAM', 'Kamwenge');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3471', '219', 'KAN', 'Kanungu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3472', '219', 'KAS', 'Kasese');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3473', '219', 'KBA', 'Kibaale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3474', '219', 'KIS', 'Kisoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3475', '219', 'KYE', 'Kyenjojo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3476', '219', 'MSN', 'Masindi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3477', '219', 'MBR', 'Mbarara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3478', '219', 'NTU', 'Ntungamo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3479', '219', 'RUK', 'Rukungiri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3480', '220', 'CK', 'Cherkasy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3481', '220', 'CH', 'Chernihiv');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3482', '220', 'CV', 'Chernivtsi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3483', '220', 'CR', 'Crimea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3484', '220', 'DN', 'Dnipropetrovs\'k');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3485', '220', 'DO', 'Donets\'k');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3486', '220', 'IV', 'Ivano-Frankivs\'k');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3487', '220', 'KL', 'Kharkiv Kherson');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3488', '220', 'KM', 'Khmel\'nyts\'kyy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3489', '220', 'KR', 'Kirovohrad');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3490', '220', 'KV', 'Kiev');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3491', '220', 'KY', 'Kyyiv');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3492', '220', 'LU', 'Luhans\'k');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3493', '220', 'LV', 'L\'viv');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3494', '220', 'MY', 'Mykolayiv');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3495', '220', 'OD', 'Odesa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3496', '220', 'PO', 'Poltava');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3497', '220', 'RI', 'Rivne');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3498', '220', 'SE', 'Sevastopol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3499', '220', 'SU', 'Sumy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3500', '220', 'TE', 'Ternopil\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3501', '220', 'VI', 'Vinnytsya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3502', '220', 'VO', 'Volyn\'');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3503', '220', 'ZK', 'Zakarpattya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3504', '220', 'ZA', 'Zaporizhzhya');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3505', '220', 'ZH', 'Zhytomyr');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3506', '221', 'AZ', 'Abu Zaby');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3507', '221', 'AJ', '\'Ajman');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3508', '221', 'FU', 'Al Fujayrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3509', '221', 'SH', 'Ash Shariqah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3510', '221', 'DU', 'Dubayy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3511', '221', 'RK', 'R\'as al Khaymah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3512', '221', 'UQ', 'Umm al Qaywayn');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3513', '222', 'ABN', 'Aberdeen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3514', '222', 'ABNS', 'Aberdeenshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3515', '222', 'ANG', 'Anglesey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3516', '222', 'AGS', 'Angus');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3517', '222', 'ARY', 'Argyll and Bute');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3518', '222', 'BEDS', 'Bedfordshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3519', '222', 'BERKS', 'Berkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3520', '222', 'BLA', 'Blaenau Gwent');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3521', '222', 'BRI', 'Bridgend');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3522', '222', 'BSTL', 'Bristol');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3523', '222', 'BUCKS', 'Buckinghamshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3524', '222', 'CAE', 'Caerphilly');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3525', '222', 'CAMBS', 'Cambridgeshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3526', '222', 'CDF', 'Cardiff');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3527', '222', 'CARM', 'Carmarthenshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3528', '222', 'CDGN', 'Ceredigion');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3529', '222', 'CHES', 'Cheshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3530', '222', 'CLACK', 'Clackmannanshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3531', '222', 'CON', 'Conwy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3532', '222', 'CORN', 'Cornwall');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3533', '222', 'DNBG', 'Denbighshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3534', '222', 'DERBY', 'Derbyshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3535', '222', 'DVN', 'Devon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3536', '222', 'DOR', 'Dorset');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3537', '222', 'DGL', 'Dumfries and Galloway');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3538', '222', 'DUND', 'Dundee');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3539', '222', 'DHM', 'Durham');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3540', '222', 'ARYE', 'East Ayrshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3541', '222', 'DUNBE', 'East Dunbartonshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3542', '222', 'LOTE', 'East Lothian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3543', '222', 'RENE', 'East Renfrewshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3544', '222', 'ERYS', 'East Riding of Yorkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3545', '222', 'SXE', 'East Sussex');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3546', '222', 'EDIN', 'Edinburgh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3547', '222', 'ESX', 'Essex');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3548', '222', 'FALK', 'Falkirk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3549', '222', 'FFE', 'Fife');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3550', '222', 'FLINT', 'Flintshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3551', '222', 'GLAS', 'Glasgow');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3552', '222', 'GLOS', 'Gloucestershire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3553', '222', 'LDN', 'Greater London');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3554', '222', 'MCH', 'Greater Manchester');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3555', '222', 'GDD', 'Gwynedd');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3556', '222', 'HANTS', 'Hampshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3557', '222', 'HWR', 'Herefordshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3558', '222', 'HERTS', 'Hertfordshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3559', '222', 'HLD', 'Highlands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3560', '222', 'IVER', 'Inverclyde');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3561', '222', 'IOW', 'Isle of Wight');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3562', '222', 'KNT', 'Kent');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3563', '222', 'LANCS', 'Lancashire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3564', '222', 'LEICS', 'Leicestershire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3565', '222', 'LINCS', 'Lincolnshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3566', '222', 'MSY', 'Merseyside');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3567', '222', 'MERT', 'Merthyr Tydfil');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3568', '222', 'MLOT', 'Midlothian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3569', '222', 'MMOUTH', 'Monmouthshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3570', '222', 'MORAY', 'Moray');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3571', '222', 'NPRTAL', 'Neath Port Talbot');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3572', '222', 'NEWPT', 'Newport');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3573', '222', 'NOR', 'Norfolk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3574', '222', 'ARYN', 'North Ayrshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3575', '222', 'LANN', 'North Lanarkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3576', '222', 'YSN', 'North Yorkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3577', '222', 'NHM', 'Northamptonshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3578', '222', 'NLD', 'Northumberland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3579', '222', 'NOT', 'Nottinghamshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3580', '222', 'ORK', 'Orkney Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3581', '222', 'OFE', 'Oxfordshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3582', '222', 'PEM', 'Pembrokeshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3583', '222', 'PERTH', 'Perth and Kinross');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3584', '222', 'PWS', 'Powys');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3585', '222', 'REN', 'Renfrewshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3586', '222', 'RHON', 'Rhondda Cynon Taff');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3587', '222', 'RUT', 'Rutland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3588', '222', 'BOR', 'Scottish Borders');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3589', '222', 'SHET', 'Shetland Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3590', '222', 'SPE', 'Shropshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3591', '222', 'SOM', 'Somerset');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3592', '222', 'ARYS', 'South Ayrshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3593', '222', 'LANS', 'South Lanarkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3594', '222', 'YSS', 'South Yorkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3595', '222', 'SFD', 'Staffordshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3596', '222', 'STIR', 'Stirling');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3597', '222', 'SFK', 'Suffolk');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3598', '222', 'SRY', 'Surrey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3599', '222', 'SWAN', 'Swansea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3600', '222', 'TORF', 'Torfaen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3601', '222', 'TWR', 'Tyne and Wear');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3602', '222', 'VGLAM', 'Vale of Glamorgan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3603', '222', 'WARKS', 'Warwickshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3604', '222', 'WDUN', 'West Dunbartonshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3605', '222', 'WLOT', 'West Lothian');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3606', '222', 'WMD', 'West Midlands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3607', '222', 'SXW', 'West Sussex');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3608', '222', 'YSW', 'West Yorkshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3609', '222', 'WIL', 'Western Isles');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3610', '222', 'WLT', 'Wiltshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3611', '222', 'WORCS', 'Worcestershire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3612', '222', 'WRX', 'Wrexham');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3613', '223', 'AL', 'Alabama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3614', '223', 'AK', 'Alaska');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3615', '223', 'AS', 'American Samoa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3616', '223', 'AZ', 'Arizona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3617', '223', 'AR', 'Arkansas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3618', '223', 'AF', 'Armed Forces Africa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3619', '223', 'AA', 'Armed Forces Americas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3620', '223', 'AC', 'Armed Forces Canada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3621', '223', 'AE', 'Armed Forces Europe');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3622', '223', 'AM', 'Armed Forces Middle East');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3623', '223', 'AP', 'Armed Forces Pacific');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3624', '223', 'CA', 'California');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3625', '223', 'CO', 'Colorado');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3626', '223', 'CT', 'Connecticut');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3627', '223', 'DE', 'Delaware');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3628', '223', 'DC', 'District of Columbia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3629', '223', 'FM', 'Federated States Of Micronesia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3630', '223', 'FL', 'Florida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3631', '223', 'GA', 'Georgia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3632', '223', 'GU', 'Guam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3633', '223', 'HI', 'Hawaii');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3634', '223', 'ID', 'Idaho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3635', '223', 'IL', 'Illinois');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3636', '223', 'IN', 'Indiana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3637', '223', 'IA', 'Iowa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3638', '223', 'KS', 'Kansas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3639', '223', 'KY', 'Kentucky');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3640', '223', 'LA', 'Louisiana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3641', '223', 'ME', 'Maine');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3642', '223', 'MH', 'Marshall Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3643', '223', 'MD', 'Maryland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3644', '223', 'MA', 'Massachusetts');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3645', '223', 'MI', 'Michigan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3646', '223', 'MN', 'Minnesota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3647', '223', 'MS', 'Mississippi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3648', '223', 'MO', 'Missouri');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3649', '223', 'MT', 'Montana');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3650', '223', 'NE', 'Nebraska');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3651', '223', 'NV', 'Nevada');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3652', '223', 'NH', 'New Hampshire');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3653', '223', 'NJ', 'New Jersey');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3654', '223', 'NM', 'New Mexico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3655', '223', 'NY', 'New York');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3656', '223', 'NC', 'North Carolina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3657', '223', 'ND', 'North Dakota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3658', '223', 'MP', 'Northern Mariana Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3659', '223', 'OH', 'Ohio');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3660', '223', 'OK', 'Oklahoma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3661', '223', 'OR', 'Oregon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3662', '223', 'PW', 'Palau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3663', '223', 'PA', 'Pennsylvania');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3664', '223', 'PR', 'Puerto Rico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3665', '223', 'RI', 'Rhode Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3666', '223', 'SC', 'South Carolina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3667', '223', 'SD', 'South Dakota');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3668', '223', 'TN', 'Tennessee');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3669', '223', 'TX', 'Texas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3670', '223', 'UT', 'Utah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3671', '223', 'VT', 'Vermont');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3672', '223', 'VI', 'Virgin Islands');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3673', '223', 'VA', 'Virginia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3674', '223', 'WA', 'Washington');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3675', '223', 'WV', 'West Virginia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3676', '223', 'WI', 'Wisconsin');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3677', '223', 'WY', 'Wyoming');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3678', '224', 'BI', 'Baker Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3679', '224', 'HI', 'Howland Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3680', '224', 'JI', 'Jarvis Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3681', '224', 'JA', 'Johnston Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3682', '224', 'KR', 'Kingman Reef');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3683', '224', 'MA', 'Midway Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3684', '224', 'NI', 'Navassa Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3685', '224', 'PA', 'Palmyra Atoll');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3686', '224', 'WI', 'Wake Island');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3687', '225', 'AR', 'Artigas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3688', '225', 'CA', 'Canelones');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3689', '225', 'CL', 'Cerro Largo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3690', '225', 'CO', 'Colonia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3691', '225', 'DU', 'Durazno');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3692', '225', 'FS', 'Flores');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3693', '225', 'FA', 'Florida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3694', '225', 'LA', 'Lavalleja');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3695', '225', 'MA', 'Maldonado');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3696', '225', 'MO', 'Montevideo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3697', '225', 'PA', 'Paysandu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3698', '225', 'RN', 'Rio Negro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3699', '225', 'RV', 'Rivera');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3700', '225', 'RO', 'Rocha');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3701', '225', 'SL', 'Salto');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3702', '225', 'SJ', 'San Jose');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3703', '225', 'SO', 'Soriano');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3704', '225', 'TA', 'Tacuarembo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3705', '225', 'TT', 'Treinta y Tres');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3706', '226', 'AN', 'Andijon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3707', '226', 'BU', 'Buxoro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3708', '226', 'FA', 'Farg\'ona');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3709', '226', 'JI', 'Jizzax');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3710', '226', 'NG', 'Namangan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3711', '226', 'NW', 'Navoiy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3712', '226', 'QA', 'Qashqadaryo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3713', '226', 'QR', 'Qoraqalpog\'iston Republikasi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3714', '226', 'SA', 'Samarqand');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3715', '226', 'SI', 'Sirdaryo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3716', '226', 'SU', 'Surxondaryo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3717', '226', 'TK', 'Toshkent City');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3718', '226', 'TO', 'Toshkent Region');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3719', '226', 'XO', 'Xorazm');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3720', '227', 'MA', 'Malampa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3721', '227', 'PE', 'Penama');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3722', '227', 'SA', 'Sanma');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3723', '227', 'SH', 'Shefa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3724', '227', 'TA', 'Tafea');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3725', '227', 'TO', 'Torba');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3726', '229', 'AM', 'Amazonas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3727', '229', 'AN', 'Anzoategui');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3728', '229', 'AP', 'Apure');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3729', '229', 'AR', 'Aragua');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3730', '229', 'BA', 'Barinas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3731', '229', 'BO', 'Bolivar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3732', '229', 'CA', 'Carabobo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3733', '229', 'CO', 'Cojedes');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3734', '229', 'DA', 'Delta Amacuro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3735', '229', 'DF', 'Dependencias Federales');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3736', '229', 'DI', 'Distrito Federal');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3737', '229', 'FA', 'Falcon');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3738', '229', 'GU', 'Guarico');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3739', '229', 'LA', 'Lara');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3740', '229', 'ME', 'Merida');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3741', '229', 'MI', 'Miranda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3742', '229', 'MO', 'Monagas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3743', '229', 'NE', 'Nueva Esparta');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3744', '229', 'PO', 'Portuguesa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3745', '229', 'SU', 'Sucre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3746', '229', 'TA', 'Tachira');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3747', '229', 'TR', 'Trujillo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3748', '229', 'VA', 'Vargas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3749', '229', 'YA', 'Yaracuy');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3750', '229', 'ZU', 'Zulia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3751', '230', 'AG', 'An Giang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3752', '230', 'BG', 'Bac Giang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3753', '230', 'BK', 'Bac Kan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3754', '230', 'BL', 'Bac Lieu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3755', '230', 'BC', 'Bac Ninh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3756', '230', 'BR', 'Ba Ria-Vung Tau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3757', '230', 'BN', 'Ben Tre');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3758', '230', 'BH', 'Binh Dinh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3759', '230', 'BU', 'Binh Duong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3760', '230', 'BP', 'Binh Phuoc');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3761', '230', 'BT', 'Binh Thuan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3762', '230', 'CM', 'Ca Mau');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3763', '230', 'CT', 'Can Tho');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3764', '230', 'CB', 'Cao Bang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3765', '230', 'DL', 'Dak Lak');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3766', '230', 'DG', 'Dak Nong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3767', '230', 'DN', 'Da Nang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3768', '230', 'DB', 'Dien Bien');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3769', '230', 'DI', 'Dong Nai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3770', '230', 'DT', 'Dong Thap');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3771', '230', 'GL', 'Gia Lai');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3772', '230', 'HG', 'Ha Giang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3773', '230', 'HD', 'Hai Duong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3774', '230', 'HP', 'Hai Phong');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3775', '230', 'HM', 'Ha Nam');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3776', '230', 'HI', 'Ha Noi');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3777', '230', 'HT', 'Ha Tay');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3778', '230', 'HH', 'Ha Tinh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3779', '230', 'HB', 'Hoa Binh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3780', '230', 'HC', 'Ho Chin Minh');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3781', '230', 'HU', 'Hau Giang');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3782', '230', 'HY', 'Hung Yen');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3783', '232', 'C', 'Saint Croix');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3784', '232', 'J', 'Saint John');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3785', '232', 'T', 'Saint Thomas');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3786', '233', 'A', 'Alo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3787', '233', 'S', 'Sigave');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3788', '233', 'W', 'Wallis');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3789', '235', 'AB', 'Abyan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3790', '235', 'AD', 'Adan');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3791', '235', 'AM', 'Amran');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3792', '235', 'BA', 'Al Bayda');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3793', '235', 'DA', 'Ad Dali');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3794', '235', 'DH', 'Dhamar');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3795', '235', 'HD', 'Hadramawt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3796', '235', 'HJ', 'Hajjah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3797', '235', 'HU', 'Al Hudaydah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3798', '235', 'IB', 'Ibb');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3799', '235', 'JA', 'Al Jawf');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3800', '235', 'LA', 'Lahij');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3801', '235', 'MA', 'Ma\'rib');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3802', '235', 'MR', 'Al Mahrah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3803', '235', 'MW', 'Al Mahwit');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3804', '235', 'SD', 'Sa\'dah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3805', '235', 'SN', 'San\'a');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3806', '235', 'SH', 'Shabwah');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3807', '235', 'TA', 'Ta\'izz');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3808', '236', 'KOS', 'Kosovo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3809', '236', 'MON', 'Montenegro');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3810', '236', 'SER', 'Serbia');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3811', '236', 'VOJ', 'Vojvodina');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3812', '237', 'BC', 'Bas-Congo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3813', '237', 'BN', 'Bandundu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3814', '237', 'EQ', 'Equateur');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3815', '237', 'KA', 'Katanga');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3816', '237', 'KE', 'Kasai-Oriental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3817', '237', 'KN', 'Kinshasa');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3818', '237', 'KW', 'Kasai-Occidental');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3819', '237', 'MA', 'Maniema');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3820', '237', 'NK', 'Nord-Kivu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3821', '237', 'OR', 'Orientale');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3822', '237', 'SK', 'Sud-Kivu');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3823', '238', 'CE', 'Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3824', '238', 'CB', 'Copperbelt');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3825', '238', 'EA', 'Eastern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3826', '238', 'LP', 'Luapula');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3827', '238', 'LK', 'Lusaka');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3828', '238', 'NO', 'Northern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3829', '238', 'NW', 'North-Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3830', '238', 'SO', 'Southern');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3831', '238', 'WE', 'Western');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3832', '239', 'BU', 'Bulawayo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3833', '239', 'HA', 'Harare');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3834', '239', 'ML', 'Manicaland');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3835', '239', 'MC', 'Mashonaland Central');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3836', '239', 'ME', 'Mashonaland East');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3837', '239', 'MW', 'Mashonaland West');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3838', '239', 'MV', 'Masvingo');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3839', '239', 'MN', 'Matabeleland North');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3840', '239', 'MS', 'Matabeleland South');
INSERT INTO `zone` (`zone_id`, `country_id`, `code`, `name`) VALUES ('3841', '239', 'MD', 'Midlands');


#
# TABLE STRUCTURE FOR: `zone_to_geo_zone`
#

DROP TABLE IF EXISTS `zone_to_geo_zone`;
CREATE TABLE `zone_to_geo_zone` (
  `zone_to_geo_zone_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '0',
  `zone_id` int(11) default NULL,
  `geo_zone_id` int(11) default NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`zone_to_geo_zone_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `zone_to_geo_zone` (`zone_to_geo_zone_id`, `country_id`, `zone_id`, `geo_zone_id`, `date_added`, `date_modified`) VALUES ('1', '222', '0', '3', '2009-01-06 23:26:25', '0000-00-00 00:00:00');


