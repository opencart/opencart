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