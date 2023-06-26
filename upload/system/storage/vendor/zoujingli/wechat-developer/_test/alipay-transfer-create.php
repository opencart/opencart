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

try {
    // 实例支付对象
    // $pay = We::AliPayTransfer($config);
    // $pay = new \AliPay\Transfer($config);
    $pay = \AliPay\Transfer::instance($config);

    // 参考链接：https://docs.open.alipay.com/api_28/alipay.fund.trans.uni.transfer/
    $result = $pay->create([
        'out_biz_no'   => time(), // 订单号
        'trans_amount' => '10', // 转账金额
        'product_code' => 'TRANS_ACCOUNT_NO_PWD',
        'biz_scene'    => 'DIRECT_TRANSFER',
        'payee_info'   => [
            'identity'      => 'zoujingli@qq.com',
            'identity_type' => 'ALIPAY_LOGON_ID',
            'name'          => '邹景立',
        ],
    ]);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}

