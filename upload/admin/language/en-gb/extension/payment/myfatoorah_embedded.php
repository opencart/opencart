<?php

$mfId = basename(__FILE__, '.php');

//Payments list
$_['heading_title'] = 'MyFatoorah Embedded';
$_["text_$mfId"]    = '<a href="https://www.myfatoorah.com" target="_blank"><img src="view/image/payment/myfatoorah.png" alt="' . $_['heading_title'] . '" title="' . $_['heading_title'] . '" style="border: 1px solid #EEEEEE;" /></a>';

include_once 'myfatoorah.php';
