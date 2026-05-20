<?php
/**
 * @package		OpenCart
 * @author		Meng Wenbin
 * @copyright	Copyright (c) 2010 - 2017, Chengdu Guangda Network Technology Co. Ltd. (https://www.opencart.cn/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.cn
 */

// Heading
$_['heading_title']                  = 'Wechat Pay';

// Text
$_['text_extension']                 = 'Extensions';
$_['text_success']                   = 'Success: You have modified Wechat account details!';
$_['text_edit']                      = 'Edit Wechat Pay';
$_['text_wechat_pay']                = '<a target="_BLANK" href="https://pay.weixin.qq.com"><img src="view/image/payment/wechat.png" alt="Wechat Pay Website" title="Wechat Pay Website" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_app_id']                   = 'WeChat App ID';
$_['entry_app_secret']               = 'WeChat App Secret';
$_['entry_mch_id']                   = 'Merchant ID';
$_['entry_api_v3_key']               = 'APIv3 Key (32 bytes)';
$_['entry_private_key']              = 'Merchant API Private Key';
$_['entry_cert_serial_no']           = 'Merchant API Certificate Serial Number';
$_['entry_public_key_id']            = 'WeChat Pay Public Key ID';
$_['entry_public_key']               = 'WeChat Pay Public Key';
$_['entry_debug']                    = 'Debug Mode';
$_['entry_total']                    = 'Total';
$_['entry_currency']                 = 'Currency';
$_['entry_completed_status']         = 'Completed Status';
$_['entry_geo_zone']                 = 'Geo Zone';
$_['entry_status']                   = 'Status';
$_['entry_sort_order']               = 'Sort Order';

// Help
$_['help_total']                     = 'The checkout total the order must reach before this payment method becomes active';
$_['help_currency']                  = 'The currency customer paid merchant!';
$_['help_wechat_pay_setup']          = '<a target="_blank" href="http://www.opencart.cn/docs/wechat-pay">Click here</a> to learn how to set up Wechat Pay account.';
$_['help_app_id']                    = 'Get from WeChat Public Platform: Settings & Development -> Basic Configuration -> Developer ID (AppID)';
$_['help_app_secret']                = 'Get from WeChat Public Platform: Settings & Development -> Basic Configuration -> Developer Password (AppSecret)';
$_['help_mch_id']                    = 'Get from WeChat Merchant Platform: Account Center -> Merchant Info -> Merchant ID';
$_['help_api_v3_key']                = 'Set in WeChat Merchant Platform: Account Center -> API Security -> APIv3 Key (used for callback decryption, must be 32 bytes)';
$_['help_private_key']               = 'Copy entire content from apiclient_key.pem in merchant API certificate package, including -----BEGIN PRIVATE KEY----- and -----END PRIVATE KEY----- headers';
$_['help_cert_serial_no']            = 'Get from merchant API certificate (apiclient_cert.pem). Command: openssl x509 -in apiclient_cert.pem -noout -serial';
$_['help_public_key_id']             = 'The serial number of WeChat Pay public key, used to verify callback signature. Get from WeChat Merchant Platform: Account Center -> API Security -> Platform Certificates';
$_['help_public_key']                = 'Copy entire content of WeChat Pay public key, including -----BEGIN PUBLIC KEY----- and -----END PUBLIC KEY----- headers';

// Error
$_['error_permission']               = 'Warning: You do not have permission to modify payment Wechat!';
$_['error_app_id']                   = 'App ID required!';
$_['error_app_secret']               = 'App Secret required!';
$_['error_mch_id']                   = 'Merchant ID required!';
$_['error_api_v3_key']               = 'APIv3 Key required!';
$_['error_private_key']              = 'Private Key Content required!';
$_['error_cert_serial_no']           = 'Certificate Serial No required!';
$_['error_public_key_id']            = 'Public Key ID required!';
$_['error_public_key']               = 'Public Key Content required!';
