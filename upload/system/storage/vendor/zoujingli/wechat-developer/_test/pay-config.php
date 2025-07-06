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

// =====================================================
// 配置缓存处理函数 ( 适配其他环境 )
// -----------------------------------------------------
// 数据缓存 (set|get|del) 操作可以将缓存写到任意位置或Redis
// 文件缓存 (put) 只能写在本地服务器，还需要返回可读的文件路径
// 未配置自定义缓存处理机制时，默认在 cache_path 写入文件缓存
// // =====================================================
// \WeChat\Contracts\Tools::$cache_callable = [
//    'set' => function ($name, $value, $expired = 360) {
//        var_dump(func_get_args());
//         return $value;
//    },
//    'get' => function ($name) {
//        var_dump(func_get_args());
//        return $value;
//    },
//    'del' => function ($name) {
//        var_dump(func_get_args());
//        return true;
//    },
//    'put' => function ($name) {
//        var_dump(func_get_args());
//        return $filePath;
//    },
// ];

return [
    'appid'      => 'wx60a43dd8161666d4',
    // 配置商户支付参数
    'mch_id'     => "1332187001",
    'mch_key'    => 'A82DC5BD1F3359081049C568D8502BC5',
    // 配置商户支付双向证书目录 （p12 | key,cert 二选一，两者都配置时p12优先）
    'ssl_p12'    => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . '1332187001_20181030_cert.p12',
    // 'ssl_key'        => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . '1332187001_20181030_key.pem',
    // 'ssl_cer'        => __DIR__ . DIRECTORY_SEPARATOR . 'cert' . DIRECTORY_SEPARATOR . '1332187001_20181030_cert.pem',
    // 配置缓存目录，需要拥有写权限
    'cache_path' => '',
];