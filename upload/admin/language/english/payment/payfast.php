<?php
/**
 * admin/language/english/payfast.php
 *
 * Copyright (c) 2009-2012 PayFast (Pty) Ltd
 * 
 * LICENSE:
 * 
 * This payment module is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation; either version 3 of the License, or (at
 * your option) any later version.
 * 
 * This payment module is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 * or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public
 * License for more details.
 * 
 * @author     Ron Darby
 * @copyright  2009-2012 PayFast (Pty) Ltd
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @version    1.1.1
 */
// Heading
$_['heading_title']                  = 'PayFast.co.za';

// Text
$_['text_payment']                   = 'Payment';
$_['text_success']                   = 'Success!';
$_['text_payfast']                   = '<a href="https://www.payfast.co.za" ><img src="' . HTTP_SERVER . 'view/image/payment/payfast.png" border="0" /></a>';
$_['text_debug']                     = 'Debug'; 
// Entry

$_['entry_sandbox']                  = 'Sandbox Mode:';
$_['entry_total']                    = 'Total:<br /><span class="help">The checkout total the order must reach before this payment method becomes active.</span>';
$_['entry_completed_status']         = 'Payment Completed Status:';
$_['entry_failed_status']            = 'Payment Failed Status:';
$_['entry_cancelled_status']         = 'Payment Cancelled Status:';
$_['entry_geo_zone']                 = 'Geo Zone:';
$_['entry_status']                   = 'Status:';
$_['entry_sort_order']               = 'Sort Order:';
$_['entry_merchant_id']              = 'PayFast Merchant ID:';
$_['entry_merchant_key']             = 'PayFast Merchant Key:';

// Error
$_['error_permission']               = 'Warning: You do not have permission to modify the PayFast payment!';

?>