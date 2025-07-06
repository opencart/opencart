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
    'token'          => 'test',
    'appid'          => 'wx60a43dd8161666d4',
    'appsecret'      => 'b4e28746f1bd73b5c6684f5e01883c36',
    'encodingaeskey' => 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5',
    // 配置缓存目录，需要拥有写权限
    'cache_path'     => '',
    // 其他支付参数可以合并在这里
    // ...
];