<?php
// Text
$_['text_title'] 				= 'PayPal';
$_['text_reason'] 				= 'REASON';
$_['text_attn_email']			= 'ATTN: Paypal Order %s needs manual verification';
$_['text_testmode']	   	 		= 'ATTENTION!!! The Payment Gateway is in \'Sandbox Mode\'. Your account will not be charged.';
$_['text_tax']	 				= 'Tax';

// Error
$_['error_referer'] 			= 'PP_Standard - Possible Scam: IPN/PDT Referrer URL "%s" was not Paypal.com. Order needs manual verification';
$_['error_amount_mismatch']		= 'PP_Standard - Possible Scam: IPN/PDT Price "%s" does not match OpenCart Total "%s". Order needs manual verification';
$_['error_email_mismatch']		= 'PP_Standard - Possible Scam: IPN/PDT Receiver Email does not match seller email. Order needs manual verification';
$_['error_verify']				= 'PP_Standard - IPN/PDT Auto-Verification Failed. This is often caused by strange characters in the customer address or name. Verify manually.';
$_['error_non_complete']		= 'PP_Standard - Non-complete order status received for order. Research needed.';
$_['error_no_data']				= 'PP_Standard - No data/response from verification.';
?>