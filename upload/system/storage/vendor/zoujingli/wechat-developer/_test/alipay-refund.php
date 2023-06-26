<?php

// +----------------------------------------------------------------------
// | WeChatDeveloper
// +----------------------------------------------------------------------
// | 版权所有 2014~2018 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: http://think.ctolog.com
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/WeChatDeveloper
// +----------------------------------------------------------------------

// 1. 手动加载入口文件
include "../include.php";

// 2. 准备公众号配置参数
$config = include "./alipay.php";

// 原商户订单号
$out_trade_no = '56737188841424';
// 申请退款金额
$refund_fee = '1.00';

try {
    // 实例支付对象
    // $pay = We::AliPayApp($config);
    // $pay = new \AliPay\App($config);
    $pay = \AliPay\App::instance($config);

    // 参考链接：https://docs.open.alipay.com/api_1/alipay.trade.refund
    $result = $pay->refund($out_trade_no, $refund_fee);
    
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}