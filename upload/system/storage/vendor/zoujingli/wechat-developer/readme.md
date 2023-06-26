[![Latest Stable Version](https://poser.pugx.org/zoujingli/wechat-developer/v/stable)](https://packagist.org/packages/zoujingli/wechat-developer) 
[![Latest Unstable Version](https://poser.pugx.org/zoujingli/wechat-developer/v/unstable)](https://packagist.org/packages/zoujingli/wechat-developer) 
[![Total Downloads](https://poser.pugx.org/zoujingli/wechat-developer/downloads)](https://packagist.org/packages/zoujingli/wechat-developer) 
[![License](https://poser.pugx.org/zoujingli/wechat-developer/license)](https://packagist.org/packages/zoujingli/wechat-developer)

WeChatDeveloper for PHP
----
* WeChatDeveloper 是基于 [wechat-php-sdk](https://github.com/zoujingli/wechat-php-sdk) 重构，优化并完善；
* 运行最底要求 PHP 版本 5.4 , 建议在 PHP7 上运行以获取最佳性能；
* WeChatDeveloper 针对 access_token 失效增加了自动刷新机制；
* 微信的部分接口需要缓存数据在本地，因此对目录需要有写权限；
* 我们鼓励大家使用 composer 来管理您的第三方库，方便后期更新操作；
* WeChatDeveloper 已历经数个线上项目考验，欢迎 fork 或 star 此项目。

功能描述
----
* 微信小程序，服务端接口支持
* 微信认证服务号，服务端接口支持
* 微信支付（账单、卡券、红包、退款、转账、App支付、JSAPI支付、Web支付、扫码支付等）
* 支付宝支付（账单、转账、App支付、刷卡支付、扫码支付、Web支付、Wap支付等）

技术帮助
----
PHP开发技术交流（QQ群 513350915）

[![PHP微信开发群 (SDK)](http://pub.idqqimg.com/wpa/images/group.png)](http://shang.qq.com/wpa/qunwpa?idkey=ae25cf789dafbef62e50a980ffc31242f150bc61a61164458216dd98c411832a) 

WeChatDeveloper 是基于官方接口封装，在做微信开发前，必需先阅读微信官方文档。
* 微信官方文档：https://mp.weixin.qq.com/wiki
* 商户支付文档：https://pay.weixin.qq.com/wiki/doc/api/index.html

针对 WeChatDeveloper 也有一准备了帮助资料可供参考。
* ThinkAdmin：https://github.com/zoujingli/ThinkAdmin
* WeChatDeveloper：https://www.kancloud.cn/zoujingli/wechat-developer


代码仓库
----
WeChatDeveloper 为开源项目，允许把它用于任何地方，不受任何约束，欢迎 fork 项目。
* Gitee 托管地址：https://gitee.com/zoujingli/WeChatDeveloper
* GitHub 托管地址：https://github.com/zoujingli/WeChatDeveloper

文件说明
----

|文件名|类名|描述|类型|加载 ①|
|---|---|---|---|---|
|  App.php  |  AliPay\App  |  支付宝App支付  |  支付宝支付  |  \We::AliPayApp() |
|  Bill.php  |  AliPay\Bill  |  支付宝账单下载  |  支付宝支付  |  \We::AliPayBill() |
|  Pos.php  |  AliPay\Pos  |  支付宝刷卡支付  |  支付宝支付  |  \We::AliPayPos() |
|  Scan.php  |  AliPay\Scan  |  支付宝扫码支付  |  支付宝支付  |  \We::AliPayScan() |
|  Transfer.php  |  AliPay\Transfer  |  支付宝转账  |  支付宝支付  |  \We::AliPayTransfer() |
|  Wap.php  |  AliPay\Wap  |  支付宝Wap支付  |  支付宝支付  |  \We::AliPayWap() |
|  Web.php  |  AliPay\Web  |  支付宝Web支付  |  支付宝支付  |  \We::AliPayWeb() |
|  Card.php  |  WeChat\Card  |  微信卡券接口支持  |  认证服务号  |  \We::WeChatCard() |
|  Custom.php  | WeChat\Custom   |  微信客服消息接口支持   |  认证服务号 | \We::WeChatCustom() |
|  Media.php  | WeChat\Media   |  微信媒体素材接口支持  |  认证服务号 | \We::WeChatMedia() |
|  Oauth.php  | WeChat\Oauth   |  微信网页授权消息类接口  |  认证服务号 | \We::WeChatOauth() |
|  Pay.php  | WeChat\Pay   |  微信支付类接口  |  认证服务号 | \We::WeChatPay() |
|  Product.php  | WeChat\Product   |  微信商店类接口  |  认证服务号 | \We::WeChatProduct() |
|  Qrcode.php  | WeChat\Qrcode   |  微信二维码接口支持  |  认证服务号 | \We::WeChatQrcode() |
|  Receive.php  | WeChat\Receive   |  微信推送事件消息处理支持 |  认证服务号 | \We::WeChatReceive() |
|  Scan.php  | WeChat\Scan   |  微信扫一扫接口支持  |  认证服务号 | \We::WeChatScan() |
|  Script.php  | WeChat\Script   |  微信前端JSSDK支持  |  认证服务号 | \We::WeChatScript() |
|  Shake.php  | WeChat\Shake   |  微信蓝牙设备揺一揺接口  |  认证服务号 | \We::WeChatShake() |
|  Tags.php  | WeChat\Tags   |  微信粉丝标签接口支持  |  认证服务号 | \We::WeChatTags() |
|  Template.php  | WeChat\Template   |  微信模板消息接口支持  |  认证服务号 | \We::WeChatTemplate() |
|  User.php  | WeChat\User   |  微信粉丝管理接口支持  |  认证服务号 | \We::WeChatCard() |
|  Wifi.php  | WeChat\Wifi   |  微信门店WIFI管理支持  |  认证服务号 | \We::WeChatWifi() |
|  Bill.php  | WePay\Bill   |  微信商户账单及评论  | 微信支付 | \We::WePayBill() |
|  Coupon.php  | WePay\Coupon   |  微信商户代金券  |  微信支付 | \We::WePayCoupon() |
|  Order.php  | WePay\Order   |  微信商户订单  |  微信支付 | \We::WePayOrder() |
|  Redpack.php  | WePay\Redpack   |  微信红包支持  |  微信支付 | \We::WePayRedpack() |
|  Refund.php  | WePay\Refund   |  微信商户退款  |  微信支付 | \We::WePayRefund() |
|  Transfers.php  | WePay\Transfers   |  微信商户打款到零钱  |   微信支付 | \We::WePayTransfers() |
|  TransfersBank.php  | WePay\TransfersBank   |  微信商户打款到银行卡  |  微信支付 | \We::WePayTransfersBank() |
|  Crypt.php  | WeMini\Crypt   |  微信小程序数据加密处理  |  微信小程序 | \We::WeMiniCrypt() |
|  Plugs.php  | WeMini\Plugs   |  微信小程序插件管理  |  微信小程序 | \We::WeMiniPlugs() |
|  Poi.php  | WeMini\Poi   |  微信小程序地址管理  |  微信小程序 | \We::WeMiniPoi() |
|  Qrcode.php  | WeMini\Qrcode   |  微信小程序二维码管理  | 微信小程序 | \We::WeMiniCrypt() |
|  Template.php  | WeMini\Template   |  微信小程序模板消息支持  | 微信小程序 | \We::WeMiniTemplate() |
|  Total.php  | WeMini\Total   |  微信小程序数据接口  | 微信小程序 | \We::WeMiniTotal() |


安装使用
----
1.1 通过 Composer 来管理安装
```shell
# 首次安装 线上版本（稳定）
composer require zoujingli/wechat-developer

# 首次安装 开发版本（开发）
composer require zoujingli/wechat-developer dev-master

# 更新 WeChatDeveloper
composer update zoujingli/wechat-developer
```

1.2 如果不使用 Composer， 可以下载 WeChatDeveloper 并解压到项目中
```php
# 在项目中加载初始化文件
include "您的目录/WeChatDeveloper/include.php";
```

2.1 接口实例所需参数
```php
$config = [
    'token'          => 'test',
    'appid'          => 'wx60a43dd8161666d4',
    'appsecret'      => '71308e96a204296c57d7cd4b21b883e8',
    'encodingaeskey' => 'BJIUzE0gqlWy0GxfPp4J1oPTBmOrNDIGPNav1YFH5Z5',
    // 配置商户支付参数（可选，在使用支付功能时需要）
    'mch_id'         => "1235704602",
    'mch_key'        => 'IKI4kpHjU94ji3oqre5zYaQMwLHuZPmj',
    // 配置商户支付双向证书目录（可选，在使用退款|打款|红包时需要）
    'ssl_key'        => '',
    'ssl_cer'        => '',
    // 缓存目录配置（可选，需拥有读写权限）
    'cache_path'     => '',
];
```

3.1 实例指定接口
```php
try {

    // 实例对应的接口对象
    $user = new \WeChat\User($config);
    
    // 调用接口对象方法
    $list = $user->getUserList();
    
    // 处理返回的结果
    echo '<pre>';
    var_export($list);
    
} catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;
    
}
```

微信支付
---
```php
  // 创建接口实例
  $wechat = new \WeChat\Pay($config);
  
  // 组装参数，可以参考官方商户文档
  $options = [
      'body'             => '测试商品',
      'out_trade_no'     => time(),
      'total_fee'        => '1',
      'openid'           => 'o38gpszoJoC9oJYz3UHHf6bEp0Lo',
      'trade_type'       => 'JSAPI',
      'notify_url'       => 'http://a.com/text.html',
      'spbill_create_ip' => '127.0.0.1',
  ];
    
try {

    // 生成预支付码
    $result = $wechat->createOrder($options);
    
    // 创建JSAPI参数签名
    $options = $wechat->createParamsForJsApi($result['prepay_id']);
    
    // @todo 把 $options 传到前端用js发起支付就可以了
    
} catch (Exception $e) {

    // 出错啦，处理下吧
    echo $e->getMessage() . PHP_EOL;
    
}
```
* 更多功能请阅读测试代码或SDK封装源码

支付宝支付
----
* 支付参数配置（可用沙箱模式）
```php
$config = [
    // 沙箱模式
    'debug'       => true,
    // 应用ID
    'appid'       => '2016090900468879',
    // 支付宝公钥(1行填写)
    'public_key'  => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtU71NY53UDGY7JNvLYAhsNa+taTF6KthIHJmGgdio9bkqeJGhHk6ttkTKkLqFgwIfgAkHpdKiOv1uZw6gVGZ7TCu5LfHTqKrCd6Uz+N7hxhY+4IwicLgprcV1flXQLmbkJYzFMZqkXGkSgOsR2yXh4LyQZczgk9N456uuzGtRy7MoB4zQy34PLUkkxR6W1B2ftNbLRGXv6tc7p/cmDcrY6K1bSxnGmfRxFSb8lRfhe0V0UM6pKq2SGGSeovrKHN0OLp+Nn5wcULVnFgATXGCENshRlp96piPEBFwneXs19n+sX1jx60FTR7/rME3sW3AHug0fhZ9mSqW4x401WjdnwIDAQAB',
    // 支付宝私钥(1行填写)
    'private_key' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC3pbN7esinxgjE8uxXAsccgGNKIq+PR1LteNTFOy0fsete43ObQCrzd9DO0zaUeBUzpIOnxrKxez7QoZROZMYrinttFZ/V5rbObEM9E5AR5Tv/Fr4IBywoS8ZtN16Xb+fZmibfU91yq9O2RYSvscncU2qEYmmaTenM0QlUO80ZKqPsM5JkgCNdcYZTUeHclWeyER3dSImNtlSKiSBSSTHthb11fkudjzdiUXua0NKVWyYuAOoDMcpXbD6NJmYqEA/iZ/AxtQt08pv0Mow581GPB0Uop5+qA2hCV85DpagE94a067sKcRui0rtkJzHem9k7xVL+2RoFm1fv3RnUkMwhAgMBAAECggEAAetkddzxrfc+7jgPylUIGb8pyoOUTC4Vqs/BgZI9xYAJksNT2QKRsFvHPfItNt4Ocqy8h4tnIL3GCU43C564B4p6AcjhE85GiN/O0BudPOKlfuQQ9mqExqMMHuYeQfz0cmzPDTSGMwWiv9v4KBH2pyvkCCAzNF6uG+rvawb4/NNVuiI7C8Ku/wYsamtbgjMZVOFFdScYgIw1BgA99RUU/fWBLMnTQkoyowSRb9eSmEUHjt/WQt+/QgKAT2WmuX4RhaGy0qcQLbNaJNKXdJ+PVhQrSiasINNtqYMa8GsQuuKsk3X8TCg9K6/lowivt5ruhyWcP2sx93zY/LGzIHgHcQKBgQDoZlcs9RWxTdGDdtH8kk0J/r+QtMijNzWI0a+t+ZsWOyd3rw+uM/8O4JTNP4Y98TvvxhJXewITbfiuOIbW1mxh8bnO/fcz7+RXZKgPDeoTeNo717tZFZGBEyUdH9M9Inqvht7+hjVDIMCYBDomYebdk3Xqo4mDBjLRdVNGrhGmVQKBgQDKS/MgTMK8Ktfnu1KzwCbn/FfHTOrp1a1t1wWPv9AW0rJPYeaP6lOkgIoO/1odG9qDDhdB6njqM+mKY5Yr3N94PHamHbwJUCmbkqEunCWpGzgcQZ1Q254xk9D7UKq/XUqW2WDqDq80GQeNial+fBc46yelQzokwdA+JdIFKoyinQKBgQCBems9V/rTAtkk1nFdt6EGXZEbLS3PiXXhGXo4gqV+OEzf6H/i/YMwJb2hsK+5GQrcps0XQihA7PctEb9GOMa/tu5fva0ZmaDtc94SLR1p5d4okyQFGPgtIp594HpPSEN0Qb9BrUJFeRz0VP6U3dzDPGHo7V4yyqRLgIN6EIcy1QKBgAqdh6mHPaTAHspDMyjJiYEc5cJIj/8rPkmIQft0FkhMUB0IRyAALNlyAUyeK61hW8sKvz+vPR8VEEk5xpSQp41YpuU6pDZc5YILZLfca8F+8yfQbZ/jll6Foi694efezl4yE/rUQG9cbOAJfEJt4o4TEOaEK5XoMbRBKc8pl22lAoGARTq0qOr9SStihRAy9a+8wi2WEwL4QHcmOjH7iAuJxy5b5TRDSjlk6h+0dnTItiFlTXdfpO8KhWA8EoSJVBZ1kcACQDFgMIA+VM+yXydtzMotOn21W4stfZ4I6dHFiujMsnKpNYVpQh3oCrJf4SeXiQDdiSCodqb1HlKkEc6naHQ=',
    // 支付成功通知地址
    'notify_url'  => '', // 可以应用的时候配置哦
    // 网页支付回跳地址
    'return_url'  => '', // 可以应用的时候配置哦
];
```
* 支付宝发起PC网站支付
```php
// 参考公共参数  https://docs.open.alipay.com/203/107090/
$config['notify_url'] = 'http://pay.thinkadmin.top/test/alipay-notify.php';
$config['return_url'] = 'http://pay.thinkadmin.top/test/alipay-success.php';

try {
    
    // 实例支付对象
    $pay = We::AliPayWeb($config);
    // $pay = new \AliPay\Web($config);
    
    // 参考链接：https://docs.open.alipay.com/api_1/alipay.trade.page.pay
    $result = $pay->apply([
        'out_trade_no' => time(), // 商户订单号
        'total_amount' => '1',    // 支付金额
        'subject'      => '支付订单描述', // 支付订单描述
    ]);
    
    echo $result; // 直接输出HTML（提交表单跳转)
    
} catch (Exception $e) {

    // 异常处理
    echo $e->getMessage();
    
}
```
* 支付宝发起手机网站支付
```php
// 参考公共参数  https://docs.open.alipay.com/203/107090/
$config['notify_url'] = 'http://pay.thinkadmin.top/test/alipay-notify.php';
$config['return_url'] = 'http://pay.thinkadmin.top/test/alipay-success.php';

try {

    // 实例支付对象
    $pay = We::AliPayWap($config);
    // $pay = new \AliPay\Wap($config);

    // 参考链接：https://docs.open.alipay.com/api_1/alipay.trade.wap.pay
    $result = $pay->apply([
        'out_trade_no' => time(), // 商户订单号
        'total_amount' => '1',    // 支付金额
        'subject'      => '支付订单描述', // 支付订单描述
    ]);

    echo $result; // 直接输出HTML（提交表单跳转)

} catch (Exception $e) {

    // 异常处理
    echo $e->getMessage();

}
```
* 更多功能请阅读测试代码或SDK封装源码

开源协议
----
* WeChatDeveloper 基于`MIT`协议发布，任何人可以用在任何地方，不受约束
* WeChatDeveloper 部分代码来自互联网，若有异议，可以联系作者进行删除

赞助支持
----
![赞助](http://static.thinkadmin.top/pay.png)


