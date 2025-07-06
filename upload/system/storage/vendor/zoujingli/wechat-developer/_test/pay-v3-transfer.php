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

    $pay = \WePayV3\Transfers::instance($config);

    $result = $pay->batchs([
        'out_batch_no'         => 'plfk2020042013',
        'batch_name'           => '2019年1月深圳分部报销单',
        'batch_remark'         => '2019年1月深圳分部报销单',
        'total_amount'         => 100,
        'transfer_detail_list' => [
            [
                'out_detail_no'   => 'x23zy545Bd5436',
                'transfer_amount' => 100,
                'transfer_remark' => '2020年4月报销',
                'openid'          => 'o-MYE42l80oelYMDE34nYD456Xoy',
                'user_name'       => '小小邹'
            ]
        ]
    ]);

    echo "\n--- 批量打款 ---\n";
    var_export($result);

} catch (\Exception $exception) {
    // 出错啦，处理下吧
    echo $exception->getMessage() . PHP_EOL;
}