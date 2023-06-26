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

/**
 * 微信商户代金券
 * Class Coupon
 * @package WePay
 */
class Coupon extends BasicWePay
{
    /**
     * 发放代金券
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create(array $options)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/send_coupon";
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 查询代金券批次
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function queryStock(array $options)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock";
        return $this->callPostApi($url, $options, false);
    }

    /**
     * 查询代金券信息
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function queryInfo(array $options)
    {
        $url = "https://api.mch.weixin.qq.com/mmpaymkttransfers/query_coupon_stock";
        return $this->callPostApi($url, $options, false);
    }

}