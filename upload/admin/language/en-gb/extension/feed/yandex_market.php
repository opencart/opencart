<?php
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

// Heading
$_['heading_title']          = 'Yandex.Market';

// Text
$_['text_extension']         = 'Extensions';
$_['text_feed']              = 'Feeds';
$_['text_success']           = 'Success: You have modified Yandex.Market feed!';
$_['text_edit']        	     = 'Edit Yandex.Market';
$_['text_width']             = 'Width';
$_['text_height']            = 'Height';
$_['text_not_unload']        = 'Do not unload';
$_['text_product_id']        = 'Product ID - product_id';
$_['text_name']        	     = 'Product name - name';
$_['text_meta_h1']        	 = 'HTML tag H1 - meta_h1';
$_['text_meta_title']        = 'HTML Title Tag - meta_title';
$_['text_meta_keyword']      = 'Keywords tag - meta_keyword';
$_['text_meta_description']  = 'Meta-tag "Description" - meta_description';
$_['text_model']        	 = 'Product code - model';
$_['text_sku']        	     = 'Article (SKU, manufacturer code) - sku';
$_['text_upc']        	     = 'UPC - upc';
$_['text_ean']        	     = 'EAN - ean';
$_['text_jan']        	     = 'JAN - jan';
$_['text_isbn']        	     = 'ISBN - isbn';
$_['text_mpn']        	     = 'MPN - mpn';
$_['text_location']        	 = 'Location';
$_['text_simplified']        = 'Simplified Description Type';
$_['text_vendor_model'] 	 = 'Arbitrary description type - vendor.model';
$_['text_medicine'] 		 = 'Medicines - medicine';
$_['text_books'] 			 = 'Books - books';
$_['text_audiobooks'] 		 = 'Audiobooks - audiobooks';
$_['text_artist_title'] 	 = 'Music and video production - artist.title';
$_['text_event_ticket'] 	 = 'Event Tickets - event-ticket';
$_['text_tour'] 			 = 'Tours - tour';

// Entry
$_['entry_status']           = 'Status';
$_['entry_secret_key']       = 'Secret key';
$_['entry_data_feed']        = 'Data Feed Url';
$_['entry_shopname']         = 'Store Name';
$_['entry_company']          = 'Company';
$_['entry_id']               = 'Offer identifier';
$_['entry_type']             = 'Type of offers';
$_['entry_name']             = 'Take the name tag from the field';
$_['entry_model']        	 = 'Take the model tag from the field';
$_['entry_vendorcode']       = 'Take the vendorCode tag from the field';
$_['entry_image']            = 'Goods with images';
$_['entry_image_resize']     = 'Image Resolution';
$_['entry_image_quantity']   = 'Quantity of product pictures';
$_['entry_main_category']    = 'Product with main category';
$_['entry_category']         = 'Categories';
$_['entry_manufacturer']     = 'Manufacturers';
$_['entry_currency']         = 'Offer Currency';
$_['entry_in_stock']         = 'Status &quot;Available&quot;';
$_['entry_out_of_stock']     = 'Status &quot;Out of Stock&quot;';
$_['entry_quantity_status']  = 'Unload when quantity is 0';

//Help
$_['help_secret_key']        = 'Key to access the YML file, to protect against DDoS attacks and downloading the product database.';
$_['help_shopname']          = 'The short name of the store (the name that appears in the list of products found on Yandex.Market. Must not contain more than 20 characters).';
$_['help_company']           = 'The full name of the company that owns the store. Not published, used for internal identification.';
$_['help_id']                = 'The identifier can contain only numbers and Latin letters. The maximum id length is 20 characters. The default product ID.';
$_['help_type']              = 'The type of the structure of the YML file for a specific theme of the product.';
$_['help_name']        	     = 'By default, the name of the product.';
$_['help_model']        	 = 'The default product code.';
$_['help_vendorcode']        = 'The default article is (SKU, manufacturer code).';
$_['help_image']             = 'If yes, then goods without images will not be unloaded.';
$_['help_image_resize']      = 'Yandex requires an image resolution of at least 250x250px. 600x600px recommended. The maximum size is 3500х3500px.';
$_['help_image_quantity']    = 'How many product photos to export. Yandex accepts no more than 10.';
$_['help_main_category']     = 'If yes, then goods that do not have the main category will not be unloaded.';
$_['help_category']          = 'Check the categories from which you want to export offers for Yandex.Market. If not checked, then all the goods unloaded.';
$_['help_manufacturer']      = 'Check the manufacturers from which you want to export offers for Yandex.Market. If not checked, then all the goods unloaded.';
$_['help_currency']          = 'Yandex.Market accepts offers in RUR, RUB or UAH currency. Select the currency in which the offers will be transmitted.';
$_['help_in_stock']          = 'In the presence of goods in stock.';
$_['help_out_of_stock']      = 'With stock balance 0.';
$_['help_quantity_status']   = 'If yes, then products with zero quantity will be unloaded in the YML file.';
$_['help_yandex_market']     = 'Topic on the <a target="_blank" href="//forum.opencart.pro/topic/1059-yandexmarket-20/">Forum</a>';

// Error
$_['error_image_width'] 	 = 'Specify the width!';
$_['error_image_height'] 	 = 'Specify the height!';
$_['error_image_width_min']	 = 'Image width should not be less than 250 pixels!';
$_['error_image_height_min'] = 'Image height should not be less than 250 pixels!';
$_['error_image_width_max']  = 'Image width should not be more than 3500 pixels!';
$_['error_image_height_max'] = 'Image height should not be more than 3500 pixels!';
$_['error_permission']       = 'Warning: You do not have permission to modify Yandex.Market feed!';