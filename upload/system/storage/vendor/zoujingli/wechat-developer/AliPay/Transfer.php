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

namespace AliPay;

use WeChat\Contracts\BasicAliPay;

/**
 * 支付宝转账到账户
 * Class Transfer
 * @package AliPay
 */
class Transfer extends BasicAliPay
{

    /**
     * 旧版 向指定支付宝账户转账
     * @param array $options
     * @return array|bool
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function apply($options)
    {
        $this->options->set('method', 'alipay.fund.trans.toaccount.transfer');
        return $this->getResult($options);
    }

    /**
     * 新版 向指定支付宝账户转账
     * @param array $options
     * @return array|bool
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create($options = [])
    {
        $this->options->set('method', 'alipay.fund.trans.uni.transfer');
        return $this->getResult($options);
    }

    /**
     * 新版 转账业务单据查询接口
     * @param array $options
     * @return array|bool
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function queryResult($options = [])
    {
        $this->options->set('method', 'alipay.fund.trans.common.query');
        return $this->getResult($options);
    }

    /**
     * 新版 支付宝资金账户资产查询接口
     * @param array $options
     * @return array|bool
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function queryAccount($options = [])
    {
        $this->options->set('method', 'alipay.fund.account.query');
        return $this->getResult($options);
    }
}