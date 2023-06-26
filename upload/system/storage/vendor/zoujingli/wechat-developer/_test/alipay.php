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

return [
    // 沙箱模式
    'debug'       => true,
    // 签名类型（RSA|RSA2）
    'sign_type'   => "RSA2",
    // 应用ID
    'appid'       => '2016090900468879',
    // 支付宝公钥(1行填写，特别注意，这里是支付宝公钥，不是应用公钥，最好从开发者中心的网页上去复制)
    'public_key'  => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtU71NY53UDGY7JNvLYAhsNa+taTF6KthIHJmGgdio9bkqeJGhHk6ttkTKkLqFgwIfgAkHpdKiOv1uZw6gVGZ7TCu5LfHTqKrCd6Uz+N7hxhY+4IwicLgprcV1flXQLmbkJYzFMZqkXGkSgOsR2yXh4LyQZczgk9N456uuzGtRy7MoB4zQy34PLUkkxR6W1B2ftNbLRGXv6tc7p/cmDcrY6K1bSxnGmfRxFSb8lRfhe0V0UM6pKq2SGGSeovrKHN0OLp+Nn5wcULVnFgATXGCENshRlp96piPEBFwneXs19n+sX1jx60FTR7/rME3sW3AHug0fhZ9mSqW4x401WjdnwIDAQAB',
    // 支付宝私钥(1行填写)
    'private_key' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC3pbN7esinxgjE8uxXAsccgGNKIq+PR1LteNTFOy0fsete43ObQCrzd9DO0zaUeBUzpIOnxrKxez7QoZROZMYrinttFZ/V5rbObEM9E5AR5Tv/Fr4IBywoS8ZtN16Xb+fZmibfU91yq9O2RYSvscncU2qEYmmaTenM0QlUO80ZKqPsM5JkgCNdcYZTUeHclWeyER3dSImNtlSKiSBSSTHthb11fkudjzdiUXua0NKVWyYuAOoDMcpXbD6NJmYqEA/iZ/AxtQt08pv0Mow581GPB0Uop5+qA2hCV85DpagE94a067sKcRui0rtkJzHem9k7xVL+2RoFm1fv3RnUkMwhAgMBAAECggEAAetkddzxrfc+7jgPylUIGb8pyoOUTC4Vqs/BgZI9xYAJksNT2QKRsFvHPfItNt4Ocqy8h4tnIL3GCU43C564B4p6AcjhE85GiN/O0BudPOKlfuQQ9mqExqMMHuYeQfz0cmzPDTSGMwWiv9v4KBH2pyvkCCAzNF6uG+rvawb4/NNVuiI7C8Ku/wYsamtbgjMZVOFFdScYgIw1BgA99RUU/fWBLMnTQkoyowSRb9eSmEUHjt/WQt+/QgKAT2WmuX4RhaGy0qcQLbNaJNKXdJ+PVhQrSiasINNtqYMa8GsQuuKsk3X8TCg9K6/lowivt5ruhyWcP2sx93zY/LGzIHgHcQKBgQDoZlcs9RWxTdGDdtH8kk0J/r+QtMijNzWI0a+t+ZsWOyd3rw+uM/8O4JTNP4Y98TvvxhJXewITbfiuOIbW1mxh8bnO/fcz7+RXZKgPDeoTeNo717tZFZGBEyUdH9M9Inqvht7+hjVDIMCYBDomYebdk3Xqo4mDBjLRdVNGrhGmVQKBgQDKS/MgTMK8Ktfnu1KzwCbn/FfHTOrp1a1t1wWPv9AW0rJPYeaP6lOkgIoO/1odG9qDDhdB6njqM+mKY5Yr3N94PHamHbwJUCmbkqEunCWpGzgcQZ1Q254xk9D7UKq/XUqW2WDqDq80GQeNial+fBc46yelQzokwdA+JdIFKoyinQKBgQCBems9V/rTAtkk1nFdt6EGXZEbLS3PiXXhGXo4gqV+OEzf6H/i/YMwJb2hsK+5GQrcps0XQihA7PctEb9GOMa/tu5fva0ZmaDtc94SLR1p5d4okyQFGPgtIp594HpPSEN0Qb9BrUJFeRz0VP6U3dzDPGHo7V4yyqRLgIN6EIcy1QKBgAqdh6mHPaTAHspDMyjJiYEc5cJIj/8rPkmIQft0FkhMUB0IRyAALNlyAUyeK61hW8sKvz+vPR8VEEk5xpSQp41YpuU6pDZc5YILZLfca8F+8yfQbZ/jll6Foi694efezl4yE/rUQG9cbOAJfEJt4o4TEOaEK5XoMbRBKc8pl22lAoGARTq0qOr9SStihRAy9a+8wi2WEwL4QHcmOjH7iAuJxy5b5TRDSjlk6h+0dnTItiFlTXdfpO8KhWA8EoSJVBZ1kcACQDFgMIA+VM+yXydtzMotOn21W4stfZ4I6dHFiujMsnKpNYVpQh3oCrJf4SeXiQDdiSCodqb1HlKkEc6naHQ=',
    // 支付成功通知地址
    'notify_url'  => '',
    // 网页支付回跳地址
    'return_url'  => '',
];