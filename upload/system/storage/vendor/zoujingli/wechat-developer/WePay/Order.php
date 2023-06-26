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

namespace WePay;

use WeChat\Contracts\BasicWePay;
use WeChat\Contracts\Tools;

/**
 * 微信商户订单
 * Class Order
 * @package WePay
 */
class Order extends BasicWePay
{

    /**
     * 统一下单
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        return $this->callPostApi($url, $options, false, 'MD5');
    }

    /**
     * 刷卡支付
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function micropay(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/micropay';
        return $this->callPostApi($url, $options, false, 'MD5');
    }

    /**
     * 查询订单
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/orderquery';
        return $this->callPostApi($url, $options);
    }

    /**
     * 关闭订单
     * @param string $outTradeNo 商户订单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function close($outTradeNo)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/closeorder';
        return $this->callPostApi($url, ['out_trade_no' => $outTradeNo]);
    }

    /**
     * 创建JsApi及H5支付参数
     * @param string $prepayId 统一下单预支付码
     * @return array
     */
    public function jsapiParams($prepayId)
    {
        $option = [];
        $option["appId"] = $this->config->get('appid');
        $option["timeStamp"] = (string)time();
        $option["nonceStr"] = Tools::createNoncestr();
        $option["package"] = "prepay_id={$prepayId}";
        $option["signType"] = "MD5";
        $option["paySign"] = $this->getPaySign($option, 'MD5');
        $option['timestamp'] = $option['timeStamp'];
        return $option;
    }

    /**
     * 获取支付规则二维码
     * @param string $productId 商户定义的商品id或者订单号
     * @return string
     */
    public function qrcParams($productId)
    {
        $data = [
            'appid'      => $this->config->get('appid'),
            'mch_id'     => $this->config->get('mch_id'),
            'time_stamp' => (string)time(),
            'nonce_str'  => Tools::createNoncestr(),
            'product_id' => (string)$productId,
        ];
        $data['sign'] = $this->getPaySign($data, 'MD5');
        return "weixin://wxpay/bizpayurl?" . http_build_query($data);
    }

    /**
     * 获取微信App支付秘需参数
     * @param string $prepayId 统一下单预支付码
     * @return array
     */
    public function appParams($prepayId)
    {
        $data = [
            'appid'     => $this->config->get('appid'),
            'partnerid' => $this->config->get('mch_id'),
            'prepayid'  => (string)$prepayId,
            'package'   => 'Sign=WXPay',
            'timestamp' => (string)time(),
            'noncestr'  => Tools::createNoncestr(),
        ];
        $data['sign'] = $this->getPaySign($data, 'MD5');
        return $data;
    }

    /**
     * 刷卡支付 撤销订单
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function reverse(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/reverse';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 刷卡支付 授权码查询openid
     * @param string $authCode 扫码支付授权码，设备读取用户微信中的条码或者二维码信息
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function queryAuthCode($authCode)
    {
        $url = 'https://api.mch.weixin.qq.com/tools/authcodetoopenid';
        return $this->callPostApi($url, ['auth_code' => $authCode]);
    }

    /**
     * 刷卡支付 交易保障
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function report(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/payitil/report';
        return $this->callPostApi($url, $options);
    }
}