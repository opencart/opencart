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

use WeChat\Contracts\Tools;
use WeChat\Exceptions\InvalidArgumentException;
use WeChat\Exceptions\InvalidResponseException;
use WePayV3\Contracts\BasicWePay;
use WePayV3\Contracts\DecryptAes;

/**
 * 直连商户 | 订单支付接口
 * Class Order
 * @package WePayV3
 */
class Order extends BasicWePay
{
    const WXPAY_H5 = 'h5';
    const WXPAY_APP = 'app';
    const WXPAY_JSAPI = 'jsapi';
    const WXPAY_NATIVE = 'native';

    /**
     * 创建支付订单
     * @param string $type 支付类型
     * @param array $data 支付参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_1_1.shtml
     */
    public function create($type, $data)
    {
        $types = [
            'h5'     => '/v3/pay/transactions/h5',
            'app'    => '/v3/pay/transactions/app',
            'jsapi'  => '/v3/pay/transactions/jsapi',
            'native' => '/v3/pay/transactions/native',
        ];
        if (empty($types[$type])) {
            throw new InvalidArgumentException("Payment {$type} not defined.");
        } else {
            // 创建预支付码
            $result = $this->doRequest('POST', $types[$type], json_encode($data, JSON_UNESCAPED_UNICODE), true);
            if (empty($result['h5_url']) && empty($result['code_url']) && empty($result['prepay_id'])) {
                $message = isset($result['code']) ? "[ {$result['code']} ] " : '';
                $message .= isset($result['message']) ? $result['message'] : json_encode($result, JSON_UNESCAPED_UNICODE);
                throw new InvalidResponseException($message);
            }
            // 支付参数签名
            $time = strval(time());
            $appid = $this->config['appid'];
            $nonceStr = Tools::createNoncestr();
            if ($type === self::WXPAY_APP) {
                $sign = $this->signBuild(join("\n", [$appid, $time, $nonceStr, $result['prepay_id'], '']));
                return ['appId' => $appid, 'partnerId' => $this->config['mch_id'], 'prepayId' => $result['prepay_id'], 'package' => 'Sign=WXPay', 'nonceStr' => $nonceStr, 'timeStamp' => $time, 'sign' => $sign];
            } elseif ($type === self::WXPAY_JSAPI) {
                $sign = $this->signBuild(join("\n", [$appid, $time, $nonceStr, "prepay_id={$result['prepay_id']}", '']));
                return ['appId' => $appid, 'timestamp' => $time, 'timeStamp' => $time, 'nonceStr' => $nonceStr, 'package' => "prepay_id={$result['prepay_id']}", 'signType' => 'RSA', 'paySign' => $sign];
            } else {
                return $result;
            }
        }
    }

    /**
     * 支付订单查询
     * @param string $tradeNo 订单单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_1_2.shtml
     */
    public function query($tradeNo)
    {
        $pathinfo = "/v3/pay/transactions/out-trade-no/{$tradeNo}";
        return $this->doRequest('GET', "{$pathinfo}?mchid={$this->config['mch_id']}", '', true);
    }

    /**
     * 关闭支付订单
     * @param string $tradeNo 订单单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function close($tradeNo)
    {
        $data = ['mchid' => $this->config['mch_id']];
        $path = "/v3/pay/transactions/out-trade-no/{$tradeNo}/close";
        return $this->doRequest('POST', $path, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 支付通知解析
     * @param array|null $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidDecryptException
     */
    public function notify($data = [])
    {
        if (empty($data)) {
            $data = json_decode(Tools::getRawInput(), true);
        }
        if (isset($data['resource'])) {
            $aes = new DecryptAes($this->config['mch_v3_key']);
            $data['result'] = $aes->decryptToString(
                $data['resource']['associated_data'],
                $data['resource']['nonce'],
                $data['resource']['ciphertext']
            );
        }
        return $data;
    }

    /**
     * 创建退款订单
     * @param array $data 退款参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_1_9.shtml
     */
    public function createRefund($data)
    {
        $path = '/v3/refund/domestic/refunds';
        return $this->doRequest('POST', $path, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 退款订单查询
     * @param string $refundNo 退款单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_1_10.shtml
     */
    public function queryRefund($refundNo)
    {
        $path = "/v3/refund/domestic/refunds/{$refundNo}";
        return $this->doRequest('GET', $path, '', true);
    }

    /**
     * 获取退款通知
     * @param mixed $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @deprecated 直接使用 Notify 方法
     */
    public function notifyRefund($data = [])
    {
        return $this->notify($data);
    }

    /**
     * 申请交易账单
     * @param array|string $params
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_3_6.shtml
     */
    public function tradeBill($params)
    {
        $path = '/v3/bill/tradebill?' . is_array($params) ? http_build_query($params) : $params;
        return $this->doRequest('GET', $path, '', true);
    }

    /**
     * 申请资金账单
     * @param array|string $params
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_3_7.shtml
     */
    public function fundflowBill($params)
    {
        $path = '/v3/bill/fundflowbill?' . is_array($params) ? http_build_query($params) : $params;
        return $this->doRequest('GET', $path, '', true);
    }

    /**
     * 下载账单文件
     * @param string $fileurl
     * @return string 二进制 Excel 内容
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @document https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_1.shtml
     */
    public function downloadBill($fileurl)
    {
        return $this->doRequest('GET', $fileurl, '', false, false);
    }
}
