<?php
// Text
$_['text_new_card']                     = '+ Add new card';
$_['text_loading']                      = 'Loading... Please wait...';
$_['text_card_details']                 = 'Card Details';
$_['text_saved_card']                   = 'Use Saved Card:';
$_['text_card_ends_in']                 = 'Pay with existing %s card that ends on XXXX XXXX XXXX %s';
$_['text_card_number']                  = 'Card Number:';
$_['text_card_expiry']                  = 'Card Expiry (MM/YY):';
$_['text_card_cvc']                     = 'Card Security Code (CVC):';
$_['text_card_zip']                     = 'Card Zip Code:';
$_['text_card_save']                    = 'Save card for future use?';
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
$_['text_card_placeholder']             = 'XXXX XXXX XXXX XXXX';
$_['text_cvv']                          = 'CVV';
$_['text_expiry']                       = 'MM/YY';
$_['text_default_squareup_name']        = 'Credit / Debit Card';
$_['text_token_issue_customer_error']   = 'We are experiencing a technical outage in our payment system. Please try again later.';
$_['text_token_revoked_subject']        = 'Your Square access token has been revoked!';
$_['text_token_revoked_message']        = "The Square payment extension's access to your Square account has been revoked through the Square Dashboard. You need to verify your application credentials in the extension settings and connect again.";
$_['text_token_expired_subject']        = 'Your Square access token has expired!';
$_['text_token_expired_message']        = "The Square payment extension's access token connecting it to your Square account has expired. You need to verify your application credentials and CRON job in the extension settings and connect again.";

// Error
$_['error_browser_not_supported']       = 'Error: The payment system no longer supports your web browser. Please update or use a different one.';
$_['error_card_invalid']                = 'Error: Card is invalid!';
$_['error_squareup_cron_token']         = 'Error: Access token could not get refreshed. Please connect your Square Payment extension via the OpenCart admin panel.';

// Warning
$_['warning_test_mode']                 = 'Warning: Sandbox mode is enabled! Transactions will appear to go through, but no charges will be carried out.';

// Statuses
$_['squareup_status_comment_authorized']    = 'The card transaction has been authorized but not yet captured.';
$_['squareup_status_comment_captured']      = 'The card transaction was authorized and subsequently captured (i.e., completed).';
$_['squareup_status_comment_voided']        = 'The card transaction was authorized and subsequently voided (i.e., canceled).   ';
$_['squareup_status_comment_failed']        = 'The card transaction failed.';

// Override errors
$_['squareup_override_error_billing_address.country']       = 'Payment Address country is not valid. Please modify it and try again.';
$_['squareup_override_error_shipping_address.country']      = 'Shipping Address country is not valid. Please modify it and try again.';
$_['squareup_override_error_email_address']                 = 'Your customer e-mail address is not valid. Please modify it and try again.';
$_['squareup_override_error_phone_number']                  = 'Your customer phone number is not valid. Please modify it and try again.';
$_['squareup_error_field']                                  = ' Field: %s';