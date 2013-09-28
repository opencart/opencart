<?php
// Headings
$_['lang_openbay']                  = 'OpenBay Pro';
$_['lang_page_title']               = 'OpenBay Pro for eBay';
$_['lang_ebay']                     = 'eBay';
$_['lang_heading']                  = 'Item links';
$_['lang_linked_items']             = 'Linked items';
$_['lang_unlinked_items']           = 'Unlinked items';

// Buttons
$_['lang_btn_resync']               = 'Re-sync';
$_['lang_btn_return']               = 'Return';
$_['lang_btn_save']                 = 'Save';
$_['lang_btn_edit']                 = 'Edit';
$_['lang_btn_check_unlinked']       = 'Check unlinked items';
$_['lang_btn_remove_link']          = 'Remove link';

// Errors & alerts
$_['lang_error_validation']         = 'You need to register for your API token and enable the module.';
$_['lang_alert_stock_local']        = 'Your eBay listing will be updated with your local stock levels!';
$_['lang_ajax_error_listings']      = 'No linked products found';
$_['lang_ajax_load_error']          = 'Sorry, could not get a response. Try later.';
$_['lang_ajax_error_link']          = 'The product link is not value';
$_['lang_ajax_error_link_no_sk']    = 'A link cannot be created for an out of stock item. End the item manually on eBay.';
$_['lang_ajax_loaded_ok']           = 'Items have been loaded ok';

// Text
$_['lang_link_desc1']               = 'Linking your items will allow for stock control on your eBay listings.';
$_['lang_link_desc2']               = 'For each item that is updated the local stock (the stock available in your OpenCart store) will update your eBay listing';
$_['lang_link_desc3']               = 'Your local stock is stock that is available to sell. Your eBay stock levels should match this.';
$_['lang_link_desc4']               = 'Your allocated stock is items that have sold but not yet been paid for. These items should be set aside and not calculated in your available stock levels.';
$_['lang_text_linked_desc']         = 'Linked items are OpenCart items that have a link to an eBay listing.';
$_['lang_text_unlinked_desc']       = 'Unlinked items are listings on your eBay account that do not link to any of your OpenCart products.';
$_['lang_text_unlinked_info']       = 'Click the check unlinked items button to search your active eBay listings for unlinked items. This may take a long time if you have many eBay listings.';
$_['lang_text_loading_items']       = 'Loading items';

// Tables
$_['lang_column_action']            = 'Action';
$_['lang_column_status']            = 'Status';
$_['lang_column_variants']          = 'Variants';
$_['lang_column_itemId']            = 'eBay item ID';
$_['lang_column_product']           = 'Product';
$_['lang_column_product_auto']      = 'Product<span class="help">(Autocomplete from name)</span>';
$_['lang_column_stock_available']   = 'Local stock<br /><span class="help">Available to sell</span>';
$_['lang_column_listing_title']     = 'Listing title<span class="help">(eBay listing title)</span>';
$_['lang_column_allocated']         = 'Allocated stock<br /><span class="help">Sold but not paid</span>';
$_['lang_column_ebay_stock']        = 'eBay stock<span class="help">On listing</span>';
?>