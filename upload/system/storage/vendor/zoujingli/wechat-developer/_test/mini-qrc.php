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

include '../include.php';

// 小程序配置
$config = [
    'appid'     => 'wx6bb7b70258da09c6',
    'appsecret' => '78b7b8d65bd67b078babf951d4342b42',
];

//We::config($config);

// $mini = We::WeMiniQrcode($config);
// $mini = new WeMini\Qrcode($config);
$mini = \WeMini\Qrcode::instance($config);

//echo '<pre>';
try {
    header('Content-type:image/jpeg'); //输出的类型
//    echo $mini->createDefault('pages/index?query=1');
//    echo $mini->createMiniScene('432432', 'pages/index/index');
    echo $mini->createMiniPath('pages/index?query=1');
} catch (Exception $e) {
    var_dump($e->getMessage());
}
