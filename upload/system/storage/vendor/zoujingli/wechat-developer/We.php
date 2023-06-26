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

use WeChat\Contracts\DataArray;
use WeChat\Exceptions\InvalidInstanceException;

/**
 * 加载缓存器
 *
 * Class We
 * @library WeChatDeveloper
 * @author Anyon<zoujingli@qq.com>
 * @date 2018/05/24 13:23
 *
 * ----- WeChat -----
 * @method \WeChat\Card WeChatCard($options = []) static 微信卡券管理
 * @method \WeChat\Custom WeChatCustom($options = []) static 微信客服消息
 * @method \WeChat\Limit WeChatLimit($options = []) static 接口调用频次限制
 * @method \WeChat\Media WeChatMedia($options = []) static 微信素材管理
 * @method \WeChat\Menu WeChatMenu($options = []) static 微信菜单管理
 * @method \WeChat\Oauth WeChatOauth($options = []) static 微信网页授权
 * @method \WeChat\Pay WeChatPay($options = []) static 微信支付商户
 * @method \WeChat\Product WeChatProduct($options = []) static 微信商店管理
 * @method \WeChat\Qrcode WeChatQrcode($options = []) static 微信二维码管理
 * @method \WeChat\Receive WeChatReceive($options = []) static 微信推送管理
 * @method \WeChat\Scan WeChatScan($options = []) static 微信扫一扫接入管理
 * @method \WeChat\Script WeChatScript($options = []) static 微信前端支持
 * @method \WeChat\Shake WeChatShake($options = []) static 微信揺一揺周边
 * @method \WeChat\Tags WeChatTags($options = []) static 微信用户标签管理
 * @method \WeChat\Template WeChatTemplate($options = []) static 微信模板消息
 * @method \WeChat\User WeChatUser($options = []) static 微信粉丝管理
 * @method \WeChat\Wifi WeChatWifi($options = []) static 微信门店WIFI管理
 *
 * ----- WeMini -----
 * @method \WeMini\Crypt WeMiniCrypt($options = []) static 小程序数据加密处理
 * @method \WeMini\Delivery WeMiniDelivery($options = []) static 小程序即时配送
 * @method \WeMini\Image WeMiniImage($options = []) static 小程序图像处理
 * @method \WeMini\Logistics WeMiniLogistics($options = []) static 小程序物流助手
 * @method \WeMini\Message WeMiniMessage($options = []) static 小程序动态消息
 * @method \WeMini\Ocr WeMiniOcr($options = []) static 小程序ORC服务
 * @method \WeMini\Plugs WeMiniPlugs($options = []) static 小程序插件管理
 * @method \WeMini\Poi WeMiniPoi($options = []) static 小程序地址管理
 * @method \WeMini\Qrcode WeMiniQrcode($options = []) static 小程序二维码管理
 * @method \WeMini\Security WeMiniSecurity($options = []) static 小程序内容安全
 * @method \WeMini\Soter WeMiniSoter($options = []) static 小程序生物认证
 * @method \WeMini\Template WeMiniTemplate($options = []) static 小程序模板消息支持
 * @method \WeMini\Total WeMiniTotal($options = []) static 小程序数据接口
 *
 * ----- WePay -----
 * @method \WePay\Bill WePayBill($options = []) static 微信商户账单及评论
 * @method \WePay\Order WePayOrder($options = []) static 微信商户订单
 * @method \WePay\Refund WePayRefund($options = []) static 微信商户退款
 * @method \WePay\Coupon WePayCoupon($options = []) static 微信商户代金券
 * @method \WePay\Redpack WePayRedpack($options = []) static 微信红包支持
 * @method \WePay\Transfers WePayTransfers($options = []) static 微信商户打款到零钱
 * @method \WePay\TransfersBank WePayTransfersBank($options = []) static 微信商户打款到银行卡
 *
 * ----- AliPay ----
 * @method \AliPay\App AliPayApp($options) static 支付宝App支付网关
 * @method \AliPay\Bill AliPayBill($options) static 支付宝电子面单下载
 * @method \AliPay\Pos AliPayPos($options) static 支付宝刷卡支付
 * @method \AliPay\Scan AliPayScan($options) static 支付宝扫码支付
 * @method \AliPay\Transfer AliPayTransfer($options) static 支付宝转账到账户
 * @method \AliPay\Wap AliPayWap($options) static 支付宝手机网站支付
 * @method \AliPay\Web AliPayWeb($options) static 支付宝网站支付
 */
class We
{
    /**
     * 定义当前版本
     * @var string
     */
    const VERSION = '1.2.17';

    /**
     * 静态配置
     * @var DataArray
     */
    private static $config;

    /**
     * 设置及获取参数
     * @param array $option
     * @return array
     */
    public static function config($option = null)
    {
        if (is_array($option)) {
            self::$config = new DataArray($option);
        }
        if (self::$config instanceof DataArray) {
            return self::$config->get();
        }
        return [];
    }

    /**
     * 静态魔术加载方法
     * @param string $name 静态类名
     * @param array $arguments 参数集合
     * @return mixed
     * @throws InvalidInstanceException
     */
    public static function __callStatic($name, $arguments)
    {
        if (substr($name, 0, 6) === 'WeChat') {
            $class = 'WeChat\\' . substr($name, 6);
        } elseif (substr($name, 0, 6) === 'WeMini') {
            $class = 'WeMini\\' . substr($name, 6);
        } elseif (substr($name, 0, 6) === 'AliPay') {
            $class = 'AliPay\\' . substr($name, 6);
        } elseif (substr($name, 0, 5) === 'WePay') {
            $class = 'WePay\\' . substr($name, 5);
        }
        if (!empty($class) && class_exists($class)) {
            $option = array_shift($arguments);
            $config = is_array($option) ? $option : self::$config->get();
            return new $class($config);
        }
        throw new InvalidInstanceException("class {$name} not found");
    }

}