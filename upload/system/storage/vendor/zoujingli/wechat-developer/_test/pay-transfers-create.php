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

try {

    // 1. 手动加载入口文件
    include "../include.php";

    // 2. 准备公众号配置参数
    $config = include "./config.php";

    // 3. 创建接口实例
    // $wechat = new \WeChat\Pay($config);
    // $wechat = \We::WeChatPay($config);
    $wechat = \WeChat\Pay::instance($config);

    // 4. 组装参数，可以参考官方商户文档
    $options = [
        'partner_trade_no' => time(),
        'openid'           => 'o38gps3vNdCqaggFfrBRCRikwlWY',
        'check_name'       => 'NO_CHECK',
        'amount'           => '100',
        'desc'             => '企业付款操作说明信息',
        'spbill_create_ip' => '127.0.0.1',
    ];
    $result = $wechat->createTransfers($options);
    echo '<pre>';
    var_export($result);
    $result = $wechat->queryTransfers($options['partner_trade_no']);
    var_export($result);

} catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

}