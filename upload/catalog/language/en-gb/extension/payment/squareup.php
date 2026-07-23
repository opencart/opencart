<?php
// Text
$_['text_loading']                      = 'Loading... Please wait...';
$_['text_trial']                        = '%s every %s %s for %s payments then ';
$_['text_recurring']                    = '%s every %s %s';
$_['text_length']                       = ' for %s payments';
$_['text_cron_subject']                 = 'Square CRON job summary';
$_['text_cron_message']                 = 'Here is a list of all CRON tasks performed by your Square extension:';
$_['text_squareup_profile_suspended']   = ' Your recurring payments have been suspended. Please contact us for more details.';
$_['text_squareup_trial_expired']       = ' Your trial period has expired.';
$_['text_squareup_recurring_expired']   = ' Your recurring payments have expired. This was your last payment.';
$_['text_cron_summary_token_heading']   = 'Refresh of access token:';
$_['text_cron_summary_token_updated']   = 'Access token updated successfully!';
$_['text_cron_summary_error_heading']   = 'Transaction Errors:';
$_['text_cron_summary_fail_heading']    = 'Failed Transactions (Profiles Suspended):';
$_['text_cron_summary_success_heading'] = 'Successful Transactions:';
$_['text_cron_fail_charge']             = 'Profile <strong>#%s</strong> could not get charged with <strong>%s</strong>';
$_['text_cron_success_charge']          = 'Profile <strong>#%s</strong> was charged with <strong>%s</strong>';
$_['text_default_squareup_name']        = 'Credit / Debit Card';
$_['text_token_issue_customer_error']   = 'We are experiencing a technical outage in our payment system. Please try again later.';
$_['text_token_revoked_subject']        = 'Your Square access token has been revoked!';
$_['text_token_revoked_message']        = "The Square payment extension's access to your Square account has been revoked through the Square Dashboard. You need to verify your application credentials in the extension settings and connect again.";
$_['text_token_expired_subject']        = 'Your Square access token has expired!';
$_['text_token_expired_message']        = "The Square payment extension's access token connecting it to your Square account has expired. You need to verify your application credentials and CRON job in the extension settings and connect again.";
$_['text_order_id']                     = 'Order ID';
$_['text_capture']                      = 'Capture Payment';
$_['text_authorize']                    = 'Authorize Payment';

// Error
$_['error_squareup_cron_token']         = 'Error: Access token could not get refreshed. Please connect your Square Payment extension via the OpenCart admin panel.';
$_['error_missing_source_id']           = 'Error: Missing payment token!';
$_['error_missing_verification_token']  = 'Error: Missing verification token!';
$_['error_missing_intent']              = 'Error: Missing Squareup intent, it got lost in the session data!';
$_['error_missing_amount']              = 'Error: Missing Squareup payment amount, it got lost in the session data!';
$_['error_missing_currency']            = 'Error: Missing Squareup payment currency, it got lost in the session data!';

// Warning
$_['warning_test_mode']                 = 'Warning: Sandbox mode is enabled! Transactions will appear to go through, but no charges will be carried out.';

// Override errors
$_['squareup_override_error_billing_address.country']       = 'Payment Address country is not valid. Please modify it and try again.';
$_['squareup_override_error_shipping_address.country']      = 'Shipping Address country is not valid. Please modify it and try again.';
$_['squareup_override_error_email_address']                 = 'Your customer e-mail address is not valid. Please modify it and try again.';
$_['squareup_override_error_phone_number']                  = 'Your customer phone number is not valid. Please modify it and try again.';
$_['squareup_error_field']                                  = ' - Field: %s';

// Statuses
$_['squareup_status_comment_authorized'] = 'The card payment has been authorized but not yet captured.';
$_['squareup_status_comment_captured']   = 'The card payment was authorized and subsequently captured (i.e., completed).';
$_['squareup_status_comment_voided']     = 'The card payment was authorized and subsequently voided (i.e., canceled).   ';
$_['squareup_status_comment_failed']     = 'The card payment failed.';

// Error
$_['error_missing_payment_link']        = 'Error: Missing Squareup Payment Link!';
$_['error_missing_order_tender_id']     = 'Error: Missing Squareup Order Tender ID!';
$_['error_payment_status']              = 'Error: Unexpected Squareup Payment status \'%1\', it should have been \'COMPLETED\' or \'PENDING\'!';
$_['error_payment']                     = 'Error: Unable to process payment!';
$_['error_missing_email']               = 'Error: Missing or invalid email address which is needed for recurring payment initialisation!';
$_['error_missing_phone']               = 'Error: Missing or invalid phone number which is needed for recurring payment initialisation!';
$_['error_customer']                    = 'Error: Unable to find customer on Squareup with email=\'%1\' and phone=\'%2\', it\'s needed for creating a recurring payment profile!';
$_['error_card']                        = 'Error: Unable to find card details on Squareup for customer with email=\'%1\', it\'s needed for creating a recurring payment profile!';
