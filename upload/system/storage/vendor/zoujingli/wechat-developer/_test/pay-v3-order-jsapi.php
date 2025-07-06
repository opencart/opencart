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
    $config = include "./pay-v3-config.php";

    // 3. 创建接口实例
    $payment = \WePayV3\Order::instance($config);

    // 4. 组装支付参数
    $order = (string)time();
    $result = $payment->create('jsapi', [
        'appid'        => $config['appid'],
        'mchid'        => $config['mch_id'],
        'description'  => '商品描述',
        'out_trade_no' => $order,
        'notify_url'   => 'https://thinkadmin.top',
        'payer'        => ['openid' => 'o38gps3vNdCqaggFfrBRCRikwlWY'],
        'amount'       => ['total' => 2, 'currency' => 'CNY'],
    ]);

    echo '<pre>';
    echo "\n--- 创建支付参数 ---\n";
    var_export($result);

//    array(
//        'appId'     => 'wx60a43dd8161666d4',
//        'timeStamp' => '1669027650',
//        'nonceStr'  => 'dfscg4lm02uqy448kjd1kjs2eo26joom',
//        'package'   => 'prepay_id=wx211847302881094d83b1917194ca880000',
//        'signType'  => 'RSA',
//        'paySign'   => '1wvvi4vmcJmP3GXB0H52mxp8lOhyqE4BtLmyi3Flg8DVKCES4fsb6+0z/L9sYkbp/TNinsnK0k7mUpTe2Yo86P1DLg18fR7zsIn5u1+3tI58boHk3VsAJl4Uhlti9ME3T7kRq1bEb4DGxp16+ixRynOqndkIkYXxrREhsrZIQlsGMfNCV0K1707s7jBTgqIm1vlkpIjNEg8nbcuG88Vzly4dR1a9K6Fux+sm0gu2rMroRwIo2R/0rgHGDANmnAZj6YEfLZlRrGTbr9r0V1+HHQPvV4BJLvTG8KXVJmJSJzBWSgq31PwrLWdOwdtpNKk7wJbY7yoScYUysYqqzM4DTQ==',
//    );

    echo "\n\n--- 查询支付参数 ---\n";
    $result = $payment->query($order);
    var_export($result);

//    array(
//        'amount'           => array('payer_currency' => 'CNY', 'total' => 2),
//        'appid'            => 'wx60a43dd8161666d4',
//        'mchid'            => '1332187001',
//        'out_trade_no'     => '1669027802',
//        'promotion_detail' => array(),
//        'scene_info'       => array('device_id' => ''),
//        'trade_state'      => 'NOTPAY',
//        'trade_state_desc' => '订单未支付',
//    );

    // 创建退款
    $out_refund_no = strval(time());
    $result = $payment->createRefund([
        'out_trade_no'  => $order,
        'out_refund_no' => $out_refund_no,
        'amount'        => [
            'refund'   => 2,
            'total'    => 2,
            'currency' => 'CNY'
        ]
    ]);
    echo "\n--- 创建退款订单2 ---\n";
    var_export($result);

    $result = $payment->queryRefund($out_refund_no);

    echo "\n--- 查询退款订单2 ---\n";
    var_export($result);

} catch (\Exception $exception) {
    // 出错啦，处理下吧
    echo $exception->getMessage() . PHP_EOL;
}