<?php

// Text
$_['text_title'] = "Klarna Account - Pay from %s/month <span id=\"klarna_account_toc_link\"></span><script text=\"javascript\">$.getScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js', function(){ var terms = new Klarna.Terms.Account({ el: 'klarna_account_toc_link', eid: '%s',   country: '%s'});})</script>";

$_['text_payment_method_title'] = "Klarna Account";

$_['text_information'] = 'Klarna Account Information';
$_['text_additional'] = 'Klarna Account requires some additional information before they can proccess your order.';
$_['text_wait'] = 'Please wait!';
$_['text_male'] = 'Male';
$_['text_female'] = 'Female';
$_['text_year'] = 'Year';
$_['text_month'] = 'Month';
$_['text_day'] = 'Day';
$_['text_payment_options'] = 'Payment options';
$_['text_single_payment'] = 'Single Payment';
$_['text_monthly_payment'] = '%s - %s per month';
$_['text_order_comment'] = "Klarna's Invoice ID: %s\n%s/%s: %.4f";
$_['text_other'] = 'Handling / Tax / Discount';

// Entry
$_['entry_gender'] = 'Gender:';
$_['entry_pno'] = 'Personal Number';
$_['entry_birthday'] = 'Date of Birth';
$_['entry_phone_no'] = 'Phone number';
$_['entry_street'] = 'Street:';
$_['entry_house_no'] = 'House No.:';
$_['entry_house_ext'] = 'House Ext.:';
$_['entry_cellno'] = 'Cell Phone:';
$_['entry_company'] = 'Company registration number:';
$_['entry_yearly_salary'] = 'Yearly salary:';

$_['help_pno'] = 'Please enter your Social Security number here';
$_['help_company_id'] = "Please enter your Company's registration number";
$_['help_street'] = "Please note that delivery can only take place to the registered address when paying with Klarna";
$_['help_house_number'] = "Please enter your house number";
$_['help_house_extension'] = "Please submit your house extension here. E.g. A, B, C, Red, Blue ect.";
$_['help_phone_number'] = "Please enter your phone number";

// Error
$_['error_gender'] = 'Gender Required!';
$_['error_deu_toc'] = "You must agree to Klarna's privacy policy (Datenschutz)";
$_['error_dob'] = 'Date of birth Required!';
$_['error_house_no'] = 'House No. Required!';
$_['error_house_ext'] = 'House Extension Required!';
$_['error_dob'] = 'Date of birth Required!';
$_['error_address_match'] = 'Billing and Shipping addresses must match if you want to use Klarna Payments';
$_['error_network'] = 'Error occurred while connecting to Klarna. Please try again later.';
?>
