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

namespace WePay;

use WeChat\Contracts\BasicWePay;

/**
 * 微信分账
 * Class ProfitSharing
 * @package WePay
 */
class ProfitSharing extends BasicWePay
{

    /**
     * 请求单次分账
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharing(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharing';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 请求多次分账
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function multiProfitSharing(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/multiprofitsharing';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 查询分账结果
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingQuery(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingquery';
        return $this->callPostApi($url, $options);
    }

    /**
     * 添加分账接收方
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingAddReceiver(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingaddreceiver';
        return $this->callPostApi($url, $options);
    }

    /**
     * 删除分账接收方
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingRemoveReceiver(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingremovereceiver';
        return $this->callPostApi($url, $options);
    }

    /**
     * 完结分账
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingFinish(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharingfinish';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 查询订单待分账金额
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingOrderAmountQuery(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingorderamountquery';
        return $this->callPostApi($url, $options);
    }

    /**
     * 分账回退
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingReturn(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/profitsharingreturn';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 回退结果查询
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function profitSharingReturnQuery(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/profitsharingreturnquery';
        return $this->callPostApi($url, $options);
    }
}
