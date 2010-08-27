<?php
// Heading
$_['heading_title']							= 'PayPal Standard';

// Text
$_['text_payment']							= 'Payment';
$_['text_success']							= 'Success: You have modified PayPal account details!';
$_['text_pp_standard']						= '<a onclick="window.open(\'https://www.paypal.com/uk/mrb/pal=W9TBB5DTD6QJW\');"><img src="view/image/payment/paypal.png" alt="PayPal" title="PayPal" style="border: 1px solid #EEEEEE;" /></a>';
$_['text_authorization']					= 'Authorization';
$_['text_sale']								= 'Sale';

// Entry
$_['entry_email']							= 'E-Mail:';
$_['entry_test']							= 'Sandbox Mode:';
$_['entry_transaction']						= 'Transaction Method:';
$_['entry_geo_zone']						= 'Geo Zone:';
$_['entry_status']							= 'Status:';
$_['entry_sort_order']						= 'Sort Order:';
$_['entry_pdt_token']						= 'PDT Token:<br/><span class="help">Payment Data Transfer Token is used for additional security and reliability. Find out how to enable PDT <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/howto_html_paymentdatatransfer" alt="">here</a></span>';
$_['entry_itemized']						= 'Itemize Products:<br/><span class="help">Show itemized list of products on Paypal invoice instead of store name.</span>';
$_['entry_debug']							= 'Debug Mode:<br/><span class="help">Logs additional information to the system log.</span>';
$_['entry_order_status']					= 'Order Status Completed:<br /><span class="help">This is the status set when the payment has been completed successfully.</span>';
$_['entry_order_status_pending']			= 'Order Status Pending:<br /><span class="help">The payment is pending; see the pending_reason variable for more information. Please note, you will receive another Instant Payment Notification when the status of the payment changes to Completed, Failed, or Denied.</span>';
$_['entry_order_status_denied']				= 'Order Status Denied:<br /><span class="help">You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the following pending reasons.</span>';
$_['entry_order_status_failed']				= 'Order Status Failed:<br /><span class="help">The payment has failed. This will only happen if the payment was attempted from your customers bank account.</span>';
$_['entry_order_status_refunded']			= 'Order Status Refunded:<br /><span class="help">You, the merchant, refunded the payment.</span>';
$_['entry_order_status_canceled_reversal']	= 'Order Status Canceled Reversal:<br /><span class="help">This means a reversal has been canceled; for example, you, the merchant, won a dispute with the customer and the funds for the transaction that was reversed have been returned to you.</span>';
$_['entry_order_status_reversed']			= 'Order Status Reversed:<br /><span class="help">This means that a payment was reversed due to a chargeback or other type of reversal. The funds have been debited from your account balance and returned to the customer. The reason for the reversal is given by the reason_code variable.</span>';
$_['entry_order_status_unspecified']		= 'Order Status Unspecified Error:';

// Error
$_['error_permission']						= 'Warning: You do not have permission to modify payment PayPal!';
$_['error_email']							= 'E-Mail required!';
?>