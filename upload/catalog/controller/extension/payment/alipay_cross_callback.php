<?php
/**
 * @package		OpenCart
 * @author		Meng Wenbin
 * @copyright	Copyright (c) 2010 - 2017, Chengdu Guangda Network Technology Co. Ltd. (https://www.opencart.cn/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.cn
 */


if (is_file('../../../../config.php')) {
    require_once ('../../../../config.php');
}

$url = HTTP_SERVER . "index.php?route=payment/alipay_cross/callback";
echo curlPost($url, $_POST);

function curlPost($url, $data, $xml = false) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    if ($xml) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type'=>'text/xml'));
    }

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}
