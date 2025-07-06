<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2025 ThinkAdmin [ thinkadmin.top ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免责声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/WeChatDeveloper
// | github 代码仓库：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

try {

    // 1. 手动加载入口文件
    include "../include.php";

    // 2. 准备公众号配置参数
    $config = include "./pay-config.php";

    // 3. 创建接口实例
    // $wechat = new \WeChat\Pay($config);
    // $wechat = \We::WeChatPay($config);
    $wechat = \WeChat\Pay::instance($config);

    // 4. 组装参数，可以参考官方商户文档
    $options = [
        'body'             => '测试商品',
        'out_trade_no'     => time(),
        'total_fee'        => '1',
        'openid'           => 'o38gpszoJoC9oJYz3UHHf6bEp0Lo',
        'trade_type'       => 'JSAPI', // JSAPI--JSAPI支付（服务号或小程序支付）、NATIVE--Native 支付、APP--APP支付，MWEB--H5支付
        'notify_url'       => 'https://a.com/text.html',
        'spbill_create_ip' => '127.0.0.1',
    ];

    // 生成预支付码
    $result = $wechat->createOrder($options);

    echo '<pre>';
    if ($options['trade_type'] === 'NATIVE') {
        echo '<h3>二维码 NATIVE 支付，直接使用 code_url 生成二维码即可</h3>';
        var_export($result);
        return;
    }

    // 创建JSAPI参数签名
    $options = $wechat->createParamsForJsApi($result['prepay_id']);

    echo "<h3>--- 创建 JSAPI 预支付码 ---</h3>";
    var_export($result);
//    array(
//        'return_code' => 'SUCCESS',
//        'return_msg'  => 'OK',
//        'result_code' => 'SUCCESS',
//        'mch_id'      => '1332187001',
//        'appid'       => 'wx60a43dd8161666d4',
//        'nonce_str'   => 'YIPDbGWT1jpLLM5R',
//        'sign'        => '7EBBA1B5F196CF122C920D10FA768D96',
//        'prepay_id'   => 'wx211858080224615a10c2fc9f6c824f0000',
//        'trade_type'  => 'JSAPI',
//    )

    echo "<h3>--- 生成 JSAPI 及 H5 支付参数 ---</h3>";
    var_export($options);
//    array(
//        'appId'     => 'wx60a43dd8161666d4',
//        'timeStamp' => '1669028299',
//        'nonceStr'  => '5s7h0dyp0nmzylbqytb462fpnb0tmrjg',
//        'package'   => 'prepay_id=wx21185819502283c23cca162e9d787f0000',
//        'signType'  => 'MD5',
//        'paySign'   => 'BBE0F426B8E1EEC9E45AC4459E8AE9D6',
//        'timestamp' => '1669028299',
//    )

} catch (Exception $exception) {

    // 出错啦，处理下吧
    echo $exception->getMessage() . PHP_EOL;

}