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

try {
    // 实例支付对象
    // $pay = new \AliPay\Bill($config);
    // $pay = \We::AliPayBill($config);
    $pay = \AliPay\Bill::instance($config);

    /**
     * 账单类型，商户通过接口或商户经开放平台授权后其所属服务商通过接口可以获取以下账单类型，支持：
     * trade：商户基于支付宝交易收单的业务账单；
     * signcustomer：基于商户支付宝余额收入及支出等资金变动的账务账单；
     * merchant_act：营销活动账单，包含营销活动的发放，核销记录
     * trade_zft_merchant：直付通二级商户查询交易的业务账单；
     * zft_acc：直付通平台商查询二级商户流水使用，返回所有二级商户流水。
     */

    // 请参考（请求参数）：https://docs.open.alipay.com/api_15/alipay.data.dataservice.bill.downloadurl.query
    $result = $pay->apply([
        'bill_date' => date('Y-m-d', strtotime('-1 month')), // 账单时间(日账单yyyy-MM-dd,月账单 yyyy-MM)
        'bill_type' => 'trade',
    ]);
    echo '<pre>';
    var_export($result);
} catch (Exception $e) {
    echo $e->getMessage();
}