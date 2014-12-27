<?php
// Heading
$_['heading_title']					= 'Authorize.Net (SIM)';

// Text 
$_['text_payment']          = '支付管理';
$_['text_success']          = '成功：您已修改Authorize.Net（AIM）帐户细节！';
$_['text_edit']                     = '编辑 Authorize.Net (SIM)';
$_['text_authorizenet_sim']			= '<a onclick="window.open(\'http://reseller.authorize.net/application/?id=5561103\');"><img src="view/image/payment/authorizenet.png" alt="Authorize.Net" title="Authorize.Net" style="border: 1px solid #EEEEEE;" /></a>';

// Entry
$_['entry_merchant']        = '商家 ID：';
$_['entry_key']             = '交易 Key：';
$_['entry_callback']        = 'Relay Response URL：';
$_['entry_md5']						= 'MD5 Hash Value';
$_['entry_test']            = '测试模式：';
$_['entry_total']           = '订单合计：';
$_['entry_order_status']    = '订单状态：';
$_['entry_geo_zone']        = '区域群组：';
$_['entry_status']          = '状态：';
$_['entry_sort_order']      = '排序：';

// Help
$_['help_callback']					= '请登入并设置了这个 <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>。';
$_['help_md5']						= 'The MD5 Hash feature enables you to authenticate that a transaction response is securely received from Authorize.Net.Please login and set this at <a href="https://secure.authorize.net" target="_blank" class="txtLink">https://secure.authorize.net</a>.(Optional)';
$_['help_total']					= '当结帐时订单合计必须大于此数置才可使用本支付方式。';

// Error
$_['error_permission']				= 'Warning: You do not have permission to modify payment Authorize.Net (SIM)!';
$_['error_merchant']				= 'Merchant ID Required!';
$_['error_key']						= 'Transaction Key Required!';