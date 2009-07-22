<?php
// Heading
$_['heading_title']                        = 'PayPal';

// Text 
$_['text_payment']                         = 'Payment';
$_['text_success']                         = 'Success: You have modified PayPal account details!';
$_['text_pp_standard']                     = '<a onclick="window.open(\'https://www.paypal.com/uk/mrb/pal=W9TBB5DTD6QJW\');"><img src="view/image/payment/paypal.png" alt="PayPal" title="PayPal" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization']                   = 'Authorization';
$_['text_sale']                            = 'Sale';

// Entry
$_['entry_email']                          = 'E-Mail:';
$_['entry_test']                           = 'Test Mode:';
$_['entry_transaction']                    = 'Transaction Method:';
$_['entry_order_status']                   = 'Order Status:';
$_['entry_order_status_pending']           = 'Order Status Pending:<br /><span class="help">The payment is pending; see the pending_reason variable for more information. Please note, you will receive another Instant Payment Notification when the status of the payment changes to Completed, Failed, or Denied.</span>';
$_['entry_order_status_denied']            = 'Order Status Denied:<br /><span class="help">You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the following pending reasons.</span>';
$_['entry_order_status_failed']            = 'Order Status Failed:<br /><span class="help">The payment has failed. This will only happen if the payment was attempted from your customers bank account.</span>';
$_['entry_order_status_refunded']          = 'Order Status Refunded:<br /><span class="help">You, the merchant, refunded the payment.</span>';
$_['entry_order_status_canceled_reversal'] = 'Order Status Canceled Reversal:<br /><span class="help">This means a reversal has been canceled; for example, you, the merchant, won a dispute with the customer and the funds for the transaction that was reversed have been returned to you.</span>';
$_['entry_order_status_reversed']          = 'Order Status Reversed:<br /><span class="help">This means that a payment was reversed due to a chargeback or other type of reversal. The funds have been debited from your account balance and returned to the customer. The reason for the reversal is given by the reason_code variable.</span>';
$_['entry_order_status_unspecified']       = 'Order Status Unspecified Error:';
$_['entry_geo_zone']                       = 'Geo Zone:';
$_['entry_status']                         = 'Status:';
$_['entry_sort_order']                     = 'Sort Order:';

// Error
$_['error_permission']                     = 'Warning: You do not have permission to modify payment PayPal!';
$_['error_email']                          = 'E-Mail Required!'; 
?>