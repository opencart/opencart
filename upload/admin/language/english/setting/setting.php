<?php
// Heading
$_['heading_title']           = 'Settings';

// Text
$_['text_success']            = 'Success: You have successfully saved your settings!';
$_['text_image_manager']      = 'Image Manager';
$_['text_default']            = 'Default';
$_['text_edit_store']         = 'Edit Store:';
$_['text_mail']               = 'Mail';
$_['text_smtp']               = 'SMTP';

// Entry
$_['entry_name']              = 'Store Name:';
$_['entry_url']               = 'Store URL:<br /><span class="help">Include the full URL to your store. Make sure to add \'/\' at the end. Example: http://www.yourdomain.com/path/</span>';
$_['entry_owner']             = 'Store Owner:';
$_['entry_address']           = 'Address:';
$_['entry_email']             = 'E-Mail:';
$_['entry_telephone']         = 'Telephone:';
$_['entry_fax']               = 'Fax:';
$_['entry_title']             = 'Title:';
$_['entry_meta_description']  = 'Meta Tag Description:';
$_['entry_description']       = 'Welcome Message:';
$_['entry_template']          = 'Template:';
$_['entry_country']           = 'Country:';
$_['entry_zone']              = 'Region / State:';
$_['entry_language']          = 'Language:';
$_['entry_admin_language']    = 'Administration Language:';
$_['entry_currency']          = 'Currency:';
$_['entry_currency_auto']     = 'Auto Update Currency:<br /><span class="help">Set your store to automatically update currencies daily.</span>';
$_['entry_weight_class']      = 'Weight Class:';
$_['entry_length_class']      = 'Length Class:';
$_['entry_tax']               = 'Display Prices With Tax:';
$_['entry_invoice']           = 'Invoice Start No.:<br /><span class="help">Set the starting number the invoices will begin from.</span>';
$_['entry_invoice_prefix']    = 'Invoice Prefix:<br /><span class="help">Set the invoice prefix e.g. IN/001</span>';
$_['entry_customer_group']    = 'Customer Group:<br /><span class="help">Default customer group.</span>';
$_['entry_customer_price']    = 'Login Display Prices:<br /><span class="help">Only show prices when a customer is logged in.</span>';
$_['entry_customer_approval'] = 'Approve New Customers:<br /><span class="help">Don\'t allow new customer to login until their account has been approved.</span>';
$_['entry_guest_checkout']    = 'Guest Checkout:<br /><span class="help">Allow customers to checkout without creating an account. This will not be available when a downloadable product is in the shopping cart.</span>';
$_['entry_account']           = 'Account Terms:<br /><span class="help">Forces people to agree to terms before an account can be created.</span>';
$_['entry_checkout']          = 'Checkout Terms:<br /><span class="help">Forces people to agree to terms before an a customer can checkout.</span>';
$_['entry_order_status']      = 'Order Status:<br /><span class="help">Set the default order status when an order is processed.</span>';
$_['entry_stock_display']     = 'Display Stock:<br /><span class="help">Display stock quantity on the product page.</span>';
$_['entry_stock_check']       = 'Show Out Of Stock:<br /><span class="help">Display out of stock message on the shopping cart page if a product is out of stock.</span>';
$_['entry_stock_checkout']    = 'Stock Checkout:<br /><span class="help">Allow customers to still checkout if the products they are ordering are not in stock.</span>';
$_['entry_stock_subtract']    = 'Stock Subtract:<br /><span class="help">Subtract product quantity when an order is processed.</span>';
$_['entry_stock_status']      = 'Stock Status:';
$_['entry_logo']              = 'Store Logo:';
$_['entry_icon']              = 'Icon:<br /><span class="help">The icon should be a PNG that is 16px x 16px.</span>';
$_['entry_image_thumb']       = 'Product Image Thumb Size:';
$_['entry_image_popup']       = 'Product Image Popup Size:';
$_['entry_image_category']    = 'Category List Size:';
$_['entry_image_product']     = 'Product List Size:';
$_['entry_image_additional']  = 'Additional Product Image Size:';
$_['entry_image_related']     = 'Related Product Image Size:';
$_['entry_image_cart']        = 'Cart Image Size:';
$_['entry_alert_mail']        = 'Alert Mail:<br /><span class="help">Send a email to the store owner when a new order is created.</span>';
$_['entry_download']          = 'Allow Downloads:';
$_['entry_download_status']   = 'Download Order Status:<br /><span class="help">Set the order status the customers order must reach before they are allowed to access their downloadable products.</span>';
$_['entry_mail_protocol']     = 'Mail Protocol:<span class="help">Only choose \'Mail\' unless your host has disabled the php mail function.';
$_['entry_smtp_host']         = 'SMTP Host:';
$_['entry_smtp_username']     = 'SMTP Username:';
$_['entry_smtp_password']     = 'SMTP Password:';
$_['entry_smtp_port']         = 'SMTP Port:';
$_['entry_smtp_timeout']      = 'SMTP Timeout:';
$_['entry_ssl']               = 'Use SSL:<br /><span class="help">To use SSL check with your host if a SSL certificate is installed and added the SSL URL to the admin config file.</span>';
$_['entry_encryption']        = 'Encryption Key:<br /><span class="help">Please provide a secret key that will be used to encrypt private information when processing orders.</span>';
$_['entry_seo_url']           = 'Use SEO URL\'s:<br /><span class="help">To use SEO URL\'s apache module mod-rewrite must be installed and you need to rename the htaccess.txt to .htaccess.</span>';
$_['entry_compression']       = 'Output Compression Level:<br /><span class="help">GZIP for more efficient transfer to requesting clients. Compression level must be between 0 - 9</span>';
$_['entry_error_display']     = 'Display Errors:';
$_['entry_error_log']         = 'Log Errors:';
$_['entry_error_filename']    = 'Error Log Filename:';
$_['entry_shipping_session']  = 'Use Shipping Session:<br /><span class="help">Saves shipping quotes to session to avoid re-quoting unnecessarily. Quotes will only be re-quoted if cart or address is changed.</span>';
$_['entry_catalog_limit'] 	  = 'Default Items per Page (Catalog):<br /><span class="help">Determines how many catalog items are shown per page (products, categories, etc)</span>';
$_['entry_admin_limit']   	  = 'Default Items per Page (Admin):<br /><span class="help">Determines how many admin items are shown per page (orders, customers, etc)</span>';
$_['entry_cart_weight']       = 'Display Weight on Cart Page:';

// Button
$_['button_add_store']        = 'Create A New Store';

// Error
$_['error_permission']        = 'Warning: You do not have permission to modify settings!';
$_['error_name']              = 'Store Name must be greater than 3 and less than 32 characters!';
$_['error_url']               = 'Store URL required!';
$_['error_title']             = 'Title must be greater than 3 and less than 32 characters!';
$_['error_owner']             = 'Store Owner must be greater than 3 and less than 64 characters!';
$_['error_address']           = 'Store Address must be greater than 10 and less than 256 characters!';
$_['error_email']             = 'E-Mail Address does not appear to be valid!';
$_['error_telephone']         = 'Telephone must be greater than 3 and less than 32 characters!';
$_['error_image_thumb']       = 'Product Image Thumb Size dimensions required!';
$_['error_image_popup']       = 'Product Image Popup Size dimensions required!';
$_['error_image_category']    = 'Category List Size dimensions required!';
$_['error_image_product']     = 'Product List Size dimensions required!';
$_['error_image_additional']  = 'Additional Product Image Size dimensions required!';
$_['error_image_related']     = 'Related Product Image Size dimensions required!';
$_['error_image_cart']        = 'Cart Image Size dimensions required!';
$_['error_error_filename']    = 'Error Log Filename required!';
$_['error_required_data']     = 'Required Data has not been entered. Check for field errors!';
$_['error_limit']       	  = 'Limit required!';

?>