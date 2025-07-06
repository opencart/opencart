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

use WeChat\Contracts\BasicWeWork;

include '../include.php';

$config = include 'work-config.php';

try {
    $url = 'https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token=ACCESS_TOKEN';
    $result = BasicWeWork::instance($config)->callGetApi($url);
    echo '<pre>';
    print_r(BasicWeWork::instance($config)->config->get());
    print_r($result);
    echo '</pre>';
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
