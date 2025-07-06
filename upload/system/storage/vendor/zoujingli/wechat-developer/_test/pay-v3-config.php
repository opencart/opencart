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

// 微信商户证书公钥
$certPublic = <<<CERT
-----BEGIN CERTIFICATE-----
文件文本内容
-----END CERTIFICATE-----
CERT;

// 微信商户证书私钥
$certPrivate = <<<CERT
-----BEGIN PRIVATE KEY-----
文件文本内容
-----END PRIVATE KEY-----
CERT;

// 微信支付证书公钥
$certPayment = <<<CERT
-----BEGIN PUBLIC KEY-----
文件文本内容
-----END PUBLIC KEY-----
CERT;

// =====================================================
// 配置缓存处理函数（适配不同环境）
// -----------------------------------------------------
// - 数据缓存（set|get|del）：可存储到本地或 Redis
// - 文件缓存（put）：仅支持本地存储，并返回可读的文件路径
// - 若未设置自定义缓存处理，默认存储在 cache_path 目录
// =====================================================
// \WeChat\Contracts\Tools::$cache_callable = [
//    'set' => function ($name, $value, $expired = 360) {
//        var_dump(func_get_args());
//        return $value;
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
    // 公众号 APPID（可选）
    'appid'        => 'wx3760xxxxxxxxxxxx',
    // 微信商户号（必填）
    'mch_id'       => '15293xxxxxx',
    // 微信商户 V3 接口密钥（必填）
    'mch_v3_key'   => '98b7fxxxxxxxxxxxxxxxxxxxxxxxxxxxx',

    // 商户证书序列号（可选）：用于请求签名
    'cert_serial'  => '49055D67B2XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX',
    // 微信商户证书公钥（必填）：可填写证书内容或文件路径，仅用于提取序列号
    'cert_public'  => $certPublic,
    // 微信商户证书私钥（必填）：可填写证书内容或文件路径，用于请求数据签名
    'cert_private' => $certPrivate,

    // 自定义证书包：支持平台证书或支付公钥（可填写文件路径或证书内容）
    'cert_package' => [
        'PUB_KEY_ID_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' => $certPayment
    ],

    // 微信平台证书或支付证书序列号（可选）
    // 'mp_cert_serial'  => 'PUB_KEY_ID_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',

    // 微信平台证书或支付证书内容（可选）
    // 'mp_cert_content' => $certPayment,

    // 运行时文件缓存路径（可选）
    'cache_path'   => ''
];