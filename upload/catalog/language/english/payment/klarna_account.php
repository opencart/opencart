<?php
// Text
$_['text_title']           = 'Klarna Account';
$_['text_pay_month']       = 'Klarna Account - Pay from %s/month <span id="klarna_account_toc_link"></span><script text="javascript">$.getScript(\'http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js\', function(){ var terms = new Klarna.Terms.Account({ el: \'klarna_account_toc_link\', eid: \'%s\',   country: \'%s\'});})</script>';
$_['text_information']     = 'Klarna Account Information';
$_['text_additional']      = 'Klarna Account requires some additional information before they can proccess your order.';
$_['text_wait']            = 'Please wait!';
$_['text_male']            = 'Male';
$_['text_female']          = 'Female';
$_['text_year']            = 'Year';
$_['text_month']           = 'Month';
$_['text_day']             = 'Day';
$_['text_payment_option']  = 'Payment options';
$_['text_single_payment']  = 'Single Payment';
$_['text_monthly_payment'] = '%s - %s per month';
$_['text_comment']         = "Klarna's Invoice ID: %s\n%s/%s: %.4f";

// Entry
$_['entry_gender']         = 'Gender:';
$_['entry_pno']            = 'Personal Number:<br /><span class="help">Please enter your Social Security number here.</span>';
$_['entry_dob']            = 'Date of Birth:';
$_['entry_phone_no']       = 'Phone number:<br /><span class="help">Please enter your phone number.</span>';
$_['entry_street']         = 'Street:<br /><span class="help">Please note that delivery can only take place to the registered address when paying with Klarna.</span>';
$_['entry_house_no']       = 'House No.:<br /><span class="help">Please enter your house number.</span>';
$_['entry_house_ext']      = 'House Ext.:<br /><span class="help">Please submit your house extension here. E.g. A, B, C, Red, Blue ect.</span>';
$_['entry_company']        = 'Company Registration Number:<br /><span class="help">Please enter your Company\'s registration number</span>';

// Error
$_['error_deu_terms']      = 'You must agree to Klarna\'s privacy policy (Datenschutz)';
$_['error_address_match']  = 'Billing and Shipping addresses must match if you want to use Klarna Payments';
$_['error_network']        = 'Error occurred while connecting to Klarna. Please try again later.';
?>
