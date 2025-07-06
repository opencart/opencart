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

namespace WePayV3;

use WePayV3\Contracts\BasicWePay;

/**
 * 普通商户商家分账
 * Class Profitsharing
 * @package WePayV3
 */
class ProfitSharing extends BasicWePay
{
    /**
     * 请求分账
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function create(array $options)
    {
        $options['appid'] = $this->config['appid'];
        return $this->doRequest('POST', '/v3/profitsharing/orders', json_encode($options, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询分账结果
     * @param string $outOrderNo 商户分账单号
     * @param string $transactionId 微信订单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function query($outOrderNo, $transactionId)
    {
        $pathinfo = "/v3/profitsharing/orders/{$outOrderNo}?&transaction_id={$transactionId}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 解冻剩余资金
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function unfreeze(array $options)
    {
        return $this->doRequest('POST', '/v3/profitsharing/orders/unfreeze', json_encode($options, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询剩余待分金额
     * @param string $transactionId 微信订单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function amounts($transactionId)
    {
        $pathinfo = "/v3/profitsharing/transactions/{$transactionId}/amounts";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 添加分账接收方
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function addReceiver(array $options)
    {
        $options['appid'] = $this->config['appid'];
        if (isset($options['name'])) {
            $options['name'] = $this->rsaEncode($options['name']);
        }
        return $this->doRequest('POST', "/v3/profitsharing/receivers/add", json_encode($options, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 删除分账接收方
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function deleteReceiver(array $options)
    {
        $options['appid'] = $this->config['appid'];
        return $this->doRequest('POST', "/v3/profitsharing/receivers/delete", json_encode($options, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 请求分账回退
     * @param array $options
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function backspace(array $options)
    {
        $options['appid'] = $this->config['appid'];
        return $this->doRequest('POST', "/v3/profitsharing/return-orders", json_encode($options, JSON_UNESCAPED_UNICODE), true);
    }
}
