<?php

$mfId = basename(__FILE__, '.php');

//Payments list
$_['heading_title'] = 'ماي فاتورة V2';
$_["text_$mfId"]    = '<a href="https://www.myfatoorah.com" target="_blank"><img src="view/image/payment/myfatoorah.png" alt="' . $_['heading_title'] . '" title="' . $_['heading_title'] . '" style="border: 1px solid #EEEEEE;" /></a>';

// Text
$_['text_myfatoorah_page']    = 'التحويل لصفحة ماي فاتورة للدفع';
$_['text_multigateways_page'] = 'عرض جميع بوابات الدفع المتاحه في صفحة الدفع';
$_['text_horizontal']         = 'أفقي';
$_['text_vertical']           = 'عمودي';

// Entry
$_['entry_payment_type'] = 'طرق الدفع';
$_['entry_view']         = 'خيارات العرض';
$_['entry_saveCard']     = 'حفظ بيانات الكارت';

// Tooltip
$_['tooltip_payment_type'] = 'اختر بوابات الدفع المفعلة في بوابة ماي فاتورة';
$_['tooltip_view']         = 'يتحكم هذا في طريقة العرض الخاصة ببوابات الدفع في صفحة الدفع';
$_['tooltip_saveCard']     = 'تفعيل خاصية ماي فاتورة للحفاظ على بيانات الكارت للمستخدمين المسجلين لديك لأي مشتريات مستقبلية';

include_once 'myfatoorah.php';
