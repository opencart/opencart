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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;

/**
 * 小程序即时配送
 * Class Delivery
 * @package WeMini
 */
class Delivery extends BasicWeChat
{

    /**
     * 异常件退回商家商家确认收货接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function abnormalConfirm($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/confirm_return?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 下配送单接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/add?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 可以对待接单状态的订单增加小费
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addTip($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/addtips?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 取消配送单接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function cancelOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/cancel?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取已支持的配送公司列表接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getAllImmeDelivery($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/delivery/getall?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 拉取已绑定账号
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getBindAccount($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/shop/get?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 拉取配送单信息
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/get?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 模拟配送公司更新配送单状态
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function mockUpdateOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/test_update_order?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 预下配送单接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function preAddOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/pre_add?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 预取消配送单接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function preCancelOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/precancel?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 重新下单
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function reOrder($data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/express/local/business/order/readd?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, $data, true);
    }

}