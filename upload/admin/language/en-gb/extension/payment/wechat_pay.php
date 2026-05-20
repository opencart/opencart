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
$_['entry_app_id']                   = 'WeChat App ID (公众号AppID)';
$_['entry_app_secret']               = 'WeChat App Secret (公众号密钥)';
$_['entry_mch_id']                   = 'Merchant ID (商户号)';
$_['entry_api_secret']               = 'API Secret v2 (v2密钥，可选)';
$_['entry_api_v3_key']               = 'APIv3 Key (v3密钥，32位)';
$_['entry_private_key']              = 'Merchant API Private Key (商户API证书私钥)';
$_['entry_cert_serial_no']           = 'Merchant API Certificate Serial Number (商户API证书序列号)';
$_['entry_public_key']               = 'WeChat Pay Platform Public Key (微信支付平台公钥)';
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
$_['help_app_id']                    = '从微信公众平台获取：设置与开发 → 基本配置 → 开发者ID(AppID)';
$_['help_app_secret']                = '从微信公众平台获取：设置与开发 → 基本配置 → 开发者密码(AppSecret)';
$_['help_mch_id']                    = '从微信商户平台获取：账户中心 → 商户信息 → 商户号';
$_['help_api_v3_key']                = '从微信商户平台设置：账户中心 → API安全 → APIv3密钥（用于回调通知解密，必须是32位）';
$_['help_private_key']               = '从商户API证书压缩包(apiclient_key.pem)完整复制，包含 -----BEGIN PRIVATE KEY----- 和 -----END PRIVATE KEY-----';
$_['help_cert_serial_no']            = '从商户API证书(apiclient_cert.pem)获取，可使用命令：openssl x509 -in apiclient_cert.pem -noout -serial';
$_['help_public_key']                = '从微信商户平台下载：账户中心 → API安全 → 平台证书，用于验证回调签名';

// Error
$_['error_permission']               = 'Warning: You do not have permission to modify payment Wechat!';
$_['error_app_id']                   = 'App ID required!';
$_['error_app_secret']               = 'App Secret required!';
$_['error_mch_id']                   = 'Merchant ID required!';
$_['error_api_secret']               = 'API Secret required!';
$_['error_api_v3_key']               = 'APIv3 Key required!';
$_['error_private_key']              = 'Private Key Content required!';
$_['error_cert_serial_no']           = 'Certificate Serial No required!';
$_['error_public_key']               = 'Public Key Content required!';
