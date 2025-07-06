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

    // 4. 获取通知参数
    $data = $wechat->getNotify();
    if ($data['return_code'] === 'SUCCESS' && $data['result_code'] === 'SUCCESS') {
        // @todo 去更新下原订单的支付状态
        $order_no = $data['out_trade_no'];

        // 返回接收成功的回复
        ob_clean();
        echo $wechat->getNotifySuccessReply();
    }

} catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;

}
