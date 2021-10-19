<?php

$mfId = basename(__FILE__, '.php');

//Payments list
$_['heading_title'] = 'MyFatoorah V2';
$_["text_$mfId"]    = '<a href="https://www.myfatoorah.com" target="_blank"><img src="view/image/payment/myfatoorah.png" alt="' . $_['heading_title'] . '" title="' . $_['heading_title'] . '" style="border: 1px solid #EEEEEE;" /></a>';

// Text
$_['text_myfatoorah_page']    = 'Myfatoorah Invoice Page (Redirect)';
$_['text_multigateways_page'] = 'List All Enabled Gateways in Checkout Page';
$_['text_horizontal']         = 'Horizontal';
$_['text_vertical']           = 'Vertical';

// Entry
$_['entry_payment_type'] = 'Payment Type';
$_['entry_view']         = 'Display Option';
$_['entry_saveCard']     = 'Save Card Mode';

// Tooltip
$_['tooltip_payment_type'] = 'Select Payment Gateways that are enabled in Myfatroorah Portal';
$_['tooltip_view']         = 'This controls the display options of the gateways if you select to use multi-gateways on the Checkout page';
$_['tooltip_saveCard']     = 'Enable MyFatoorah save-card information feature for logged-in users for future payments';

include_once 'myfatoorah.php';
