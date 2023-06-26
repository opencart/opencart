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
use WeChat\Exceptions\InvalidResponseException;

/**
 * 微信商户退款
 * Class Refund
 * @package WePay
 */
class Refund extends BasicWePay
{

    /**
     * 创建退款订单
     * @param array $options
     * @return array
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/secapi/pay/refund';
        return $this->callPostApi($url, $options, true);
    }

    /**
     * 查询退款
     * @param array $options
     * @return array
     * @throws InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function query(array $options)
    {
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        return $this->callPostApi($url, $options);
    }

    /**
     * 获取退款通知
     * @return array
     * @throws InvalidResponseException
     */
    public function getNotify()
    {
        $data = Tools::xml2arr(file_get_contents("php://input"));
        if (!isset($data['return_code']) || $data['return_code'] !== 'SUCCESS') {
            throw new InvalidResponseException('获取退款通知XML失败！');
        }
        if (!class_exists('Prpcrypt', false)) {
            include dirname(__DIR__) . '/WeChat/Contracts/Prpcrypt.php';
        }
        $pc = new \Prpcrypt(md5($this->config->get('mch_key')));
        $array = $pc->decrypt(base64_decode($data['req_info']));
        if (intval($array[0]) > 0) {
            throw new InvalidResponseException($array[1], $array[0]);
        }
        $data['decode'] = $array[1];
        return $data;
    }

}