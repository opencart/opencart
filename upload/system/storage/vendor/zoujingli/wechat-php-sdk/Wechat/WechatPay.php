<?php

// +----------------------------------------------------------------------
// | wechat-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方文档: https://www.kancloud.cn/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------

namespace Wechat;

use Wechat\Lib\Tools;

/**
 * 微信支付SDK
 * @author zoujingli <zoujingli@qq.com>
 * @date 2015/05/13 12:12:00
 */
class WechatPay
{

    /** 支付接口基础地址 */
    const MCH_BASE_URL = 'https://api.mch.weixin.qq.com';

    /** 公众号appid */
    public $appid;

    /** 公众号配置 */
    public $config;

    /** 商户身份ID */
    public $mch_id;

    /** 商户支付密钥Key */
    public $partnerKey;

    /** 证书路径 */
    public $ssl_cer;
    public $ssl_key;

    /** 执行错误消息及代码 */
    public $errMsg;
    public $errCode;

    /**
     * WechatPay constructor.
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->config = Loader::config($options);
        $this->appid = isset($this->config['appid']) ? $this->config['appid'] : '';
        $this->mch_id = isset($this->config['mch_id']) ? $this->config['mch_id'] : '';
        $this->partnerKey = isset($this->config['partnerkey']) ? $this->config['partnerkey'] : '';
        $this->ssl_cer = isset($this->config['ssl_cer']) ? $this->config['ssl_cer'] : '';
        $this->ssl_key = isset($this->config['ssl_key']) ? $this->config['ssl_key'] : '';
    }

    /**
     * 获取当前错误内容
     * @return string
     */
    public function getError()
    {
        return $this->errMsg;
    }

    /**
     * 当前当前错误代码
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errCode;
    }

    /**
     * 获取当前操作公众号APPID
     * @return string
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * 获取SDK配置参数
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 设置标配的请求参数，生成签名，生成接口参数xml
     * @param array $data
     * @return string
     */
    protected function createXml($data)
    {
        if (!isset($data['wxappid']) && !isset($data['mch_appid']) && !isset($data['appid'])) {
            $data['appid'] = $this->appid;
        }
        if (!isset($data['mchid']) && !isset($data['mch_id'])) {
            $data['mch_id'] = $this->mch_id;
        }
        isset($data['nonce_str']) || $data['nonce_str'] = Tools::createNoncestr();
        $data["sign"] = Tools::getPaySign($data, $this->partnerKey);
        return Tools::arr2xml($data);
    }

    /**
     * POST提交XML
     * @param array $data
     * @param string $url
     * @return mixed
     */
    public function postXml($data, $url)
    {
        return Tools::httpPost($url, $this->createXml($data));
    }

    /**
     * 使用证书post请求XML
     * @param array $data
     * @param string $url
     * @return mixed
     */
    function postXmlSSL($data, $url)
    {
        return Tools::httpsPost($url, $this->createXml($data), $this->ssl_cer, $this->ssl_key);
    }

    /**
     * POST提交获取Array结果
     * @param array $data 需要提交的数据
     * @param string $url
     * @param string $method
     * @return array
     */
    public function getArrayResult($data, $url, $method = 'postXml')
    {
        return Tools::xml2arr($this->$method($data, $url));
    }

    /**
     * 解析返回的结果
     * @param array $result
     * @return bool|array
     */
    protected function _parseResult($result)
    {
        if (empty($result)) {
            $this->errCode = 'result error';
            $this->errMsg = '解析返回结果失败';
            return false;
        }
        if ($result['return_code'] !== 'SUCCESS') {
            $this->errCode = $result['return_code'];
            $this->errMsg = $result['return_msg'];
            return false;
        }
        if (isset($result['err_code']) && $result['err_code'] !== 'SUCCESS') {
            $this->errMsg = $result['err_code_des'];
            $this->errCode = $result['err_code'];
            return false;
        }
        return $result;
    }

    /**
     * 创建刷卡支付参数包
     * @param string $auth_code 授权Code号
     * @param string $out_trade_no 商户订单号
     * @param int $total_fee 支付费用
     * @param string $body 订单标识
     * @param null $goods_tag 商品标签
     * @return array|bool
     */
    public function createMicroPay($auth_code, $out_trade_no, $total_fee, $body, $goods_tag = null)
    {
        $data = array(
            "appid"            => $this->appid,
            "mch_id"           => $this->mch_id,
            "body"             => $body,
            "out_trade_no"     => $out_trade_no,
            "total_fee"        => $total_fee,
            "auth_code"        => $auth_code,
            "spbill_create_ip" => Tools::getAddress(),
        );
        empty($goods_tag) || $data['goods_tag'] = $goods_tag;
        $json = Tools::xml2arr($this->postXml($data, self::MCH_BASE_URL . '/pay/micropay'));
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }

    /**
     * 支付通知验证处理
     * @return bool|array
     */
    public function getNotify()
    {
        $disableEntities = libxml_disable_entity_loader(true);
        $notifyInfo = (array)simplexml_load_string(file_get_contents("php://input"), 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_disable_entity_loader($disableEntities);
        if (empty($notifyInfo)) {
            Tools::log('Payment notification forbidden access.', "ERR - {$this->appid}");
            $this->errCode = '404';
            $this->errMsg = 'Payment notification forbidden access.';
            return false;
        }
        if (empty($notifyInfo['sign'])) {
            Tools::log('Payment notification signature is missing.' . var_export($notifyInfo, true), "ERR - {$this->appid}");
            $this->errCode = '403';
            $this->errMsg = 'Payment notification signature is missing.';
            return false;
        }
        $data = $notifyInfo;
        unset($data['sign']);
        if ($notifyInfo['sign'] !== Tools::getPaySign($data, $this->partnerKey)) {
            Tools::log('Payment notification signature verification failed.' . var_export($notifyInfo, true), "ERR - {$this->appid}");
            $this->errCode = '403';
            $this->errMsg = 'Payment signature verification failed.';
            return false;
        }
        Tools::log('Payment notification signature verification success.' . var_export($notifyInfo, true), "MSG - {$this->appid}");
        $this->errCode = '0';
        $this->errMsg = '';
        return $notifyInfo;
    }


    /**
     * 支付XML统一回复
     * @param array $data 需要回复的XML内容数组
     * @param bool $isReturn 是否返回XML内容，默认不返回
     * @return string
     */
    public function replyXml(array $data, $isReturn = false)
    {
        $xml = Tools::arr2xml($data);
        if ($isReturn) {
            return $xml;
        }
        ob_clean();
        exit($xml);
    }

    /**
     * 获取预支付ID
     * @param string $openid 用户openid，JSAPI必填
     * @param string $body 商品标题
     * @param string $out_trade_no 第三方订单号
     * @param int $total_fee 订单总价
     * @param string $notify_url 支付成功回调地址
     * @param string $trade_type 支付类型JSAPI|NATIVE|APP
     * @param string $goods_tag 商品标记，代金券或立减优惠功能的参数
     * @param string $fee_type 交易币种
     * @param null $no_credit 是否禁止信用
     * @return bool|string
     */
    public function getPrepayId($openid, $body, $out_trade_no, $total_fee, $notify_url, $trade_type = "JSAPI", $goods_tag = null, $fee_type = 'CNY', $no_credit = null)
    {
        $postdata = array(
            "body"             => $body,
            "out_trade_no"     => $out_trade_no,
            "fee_type"         => $fee_type,
            "total_fee"        => $total_fee,
            "notify_url"       => $notify_url,
            "trade_type"       => $trade_type,
            "spbill_create_ip" => Tools::getAddress(),
        );
        empty($openid) || $postdata['openid'] = $openid;
        empty($goods_tag) || $postdata['goods_tag'] = $goods_tag;
        is_null($no_credit) || $postdata['no_credit'] = $no_credit;
        $result = $this->getArrayResult($postdata, self::MCH_BASE_URL . '/pay/unifiedorder');
        if (false === $this->_parseResult($result)) {
            return false;
        }
        return in_array($trade_type, array('JSAPI', 'APP')) ? $result['prepay_id'] : ($trade_type === 'MWEB' ? $result['mweb_url'] : $result['code_url']);
    }

    /**
     * 获取二维码预支付ID
     * @param string $openid 用户openid，JSAPI必填
     * @param string $body 商品标题
     * @param string $out_trade_no 第三方订单号
     * @param int $total_fee 订单总价
     * @param string $notify_url 支付成功回调地址
     * @param string $goods_tag 商品标记，代金券或立减优惠功能的参数
     * @param string $fee_type 交易币种
     * @return bool|string
     */
    public function getQrcPrepayId($openid, $body, $out_trade_no, $total_fee, $notify_url, $goods_tag = null, $fee_type = 'CNY')
    {
        $postdata = array(
            "body"             => $body,
            "out_trade_no"     => $out_trade_no,
            "fee_type"         => $fee_type,
            "total_fee"        => $total_fee,
            "notify_url"       => $notify_url,
            "trade_type"       => 'NATIVE',
            "spbill_create_ip" => Tools::getAddress(),
        );
        empty($goods_tag) || $postdata['goods_tag'] = $goods_tag;
        empty($openid) || $postdata['openid'] = $openid;
        $result = $this->getArrayResult($postdata, self::MCH_BASE_URL . '/pay/unifiedorder');
        if (false === $this->_parseResult($result) || empty($result['prepay_id'])) {
            return false;
        }
        return $result['prepay_id'];
    }

    /**
     * 获取支付规二维码
     * @param string $product_id 商户定义的商品id 或者订单号
     * @return string
     */
    public function getQrcPayUrl($product_id)
    {
        $data = array(
            'appid'      => $this->appid,
            'mch_id'     => $this->mch_id,
            'time_stamp' => (string)time(),
            'nonce_str'  => Tools::createNoncestr(),
            'product_id' => (string)$product_id,
        );
        $data['sign'] = Tools::getPaySign($data, $this->partnerKey);
        return "weixin://wxpay/bizpayurl?" . http_build_query($data);
    }


    /**
     * 创建JSAPI支付参数包
     * @param string $prepay_id
     * @return array
     */
    public function createMchPay($prepay_id)
    {
        $option = array();
        $option["appId"] = $this->appid;
        $option["timeStamp"] = (string)time();
        $option["nonceStr"] = Tools::createNoncestr();
        $option["package"] = "prepay_id={$prepay_id}";
        $option["signType"] = "MD5";
        $option["paySign"] = Tools::getPaySign($option, $this->partnerKey);
        $option['timestamp'] = $option['timeStamp'];
        return $option;
    }

    /**
     * 关闭订单
     * @param string $out_trade_no
     * @return bool
     */
    public function closeOrder($out_trade_no)
    {
        $data = array('out_trade_no' => $out_trade_no);
        $result = $this->getArrayResult($data, self::MCH_BASE_URL . '/pay/closeorder');
        if (false === $this->_parseResult($result)) {
            return false;
        }
        return ($result['return_code'] === 'SUCCESS');
    }

    /**
     * 查询订单详情
     * @param $out_trade_no
     * @return bool|array
     */
    public function queryOrder($out_trade_no)
    {
        $data = array('out_trade_no' => $out_trade_no);
        $result = $this->getArrayResult($data, self::MCH_BASE_URL . '/pay/orderquery');
        if (false === $this->_parseResult($result)) {
            return false;
        }
        return $result;
    }

    /**
     * 订单退款接口
     * @param string $out_trade_no 商户订单号
     * @param string $transaction_id 微信订单号，与 out_refund_no 二选一（不选时传0或false）
     * @param string $out_refund_no 商户退款订单号，与 transaction_id 二选一（不选时传0或false）
     * @param int $total_fee 商户订单总金额
     * @param int $refund_fee 退款金额，不可大于订单总金额
     * @param int|null $op_user_id 操作员ID，默认商户ID
     * @param string $refund_account 退款资金来源
     *        仅针对老资金流商户使用
     *        REFUND_SOURCE_UNSETTLED_FUNDS --- 未结算资金退款（默认使用未结算资金退款）
     *        REFUND_SOURCE_RECHARGE_FUNDS  --- 可用余额退款
     * @param string $refund_desc 退款原因
     * @param string $refund_fee_type 退款货币种类
     * @return bool
     */
    public function refund($out_trade_no, $transaction_id, $out_refund_no, $total_fee, $refund_fee, $op_user_id = null, $refund_account = '', $refund_desc = '', $refund_fee_type = 'CNY')
    {
        $data = array();
        $data['out_trade_no'] = $out_trade_no;
        $data['total_fee'] = $total_fee;
        $data['refund_fee'] = $refund_fee;
        $data['refund_fee_type'] = $refund_fee_type;
        $data['op_user_id'] = empty($op_user_id) ? $this->mch_id : $op_user_id;
        !empty($out_refund_no) && $data['out_refund_no'] = $out_refund_no;
        !empty($transaction_id) && $data['transaction_id'] = $transaction_id;
        !empty($refund_account) && $data['refund_account'] = $refund_account;
        !empty($refund_desc) && $data['refund_desc'] = $refund_desc;
        $result = $this->getArrayResult($data, self::MCH_BASE_URL . '/secapi/pay/refund', 'postXmlSSL');
        if (false === $this->_parseResult($result)) {
            return false;
        }
        return ($result['return_code'] === 'SUCCESS');
    }

    /**
     * 退款查询接口
     * @param string $out_trade_no
     * @return bool|array
     */
    public function refundQuery($out_trade_no)
    {
        $data = array();
        $data['out_trade_no'] = $out_trade_no;
        $result = $this->getArrayResult($data, self::MCH_BASE_URL . '/pay/refundquery');
        if (false === $this->_parseResult($result)) {
            return false;
        }
        return $result;
    }

    /**
     * 获取对账单
     * @param string $bill_date 账单日期，如 20141110
     * @param string $bill_type ALL|SUCCESS|REFUND|REVOKED
     * @return bool|array
     */
    public function getBill($bill_date, $bill_type = 'ALL')
    {
        $data = array();
        $data['bill_date'] = $bill_date;
        $data['bill_type'] = $bill_type;
        $result = $this->postXml($data, self::MCH_BASE_URL . '/pay/downloadbill');
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }

    /**
     * 发送现金红包
     * @param string $openid 红包接收者OPENID
     * @param int $total_amount 红包总金额
     * @param string $mch_billno 商户订单号
     * @param string $sendname 商户名称
     * @param string $wishing 红包祝福语
     * @param string $act_name 活动名称
     * @param string $remark 备注信息
     * @param null|int $total_num 红包发放总人数（大于1为裂变红包）
     * @param null|string $scene_id 场景id
     * @param string $risk_info 活动信息
     * @param null|string $consume_mch_id 资金授权商户号
     * @return array|bool
     * @link  https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_5
     */
    public function sendRedPack($openid, $total_amount, $mch_billno, $sendname, $wishing, $act_name, $remark, $total_num = 1, $scene_id = null, $risk_info = '', $consume_mch_id = null)
    {
        $data = array();
        $data['mch_billno'] = $mch_billno; // 商户订单号 mch_id+yyyymmdd+10位一天内不能重复的数字
        $data['wxappid'] = $this->appid;
        $data['send_name'] = $sendname; //商户名称
        $data['re_openid'] = $openid; //红包接收者
        $data['total_amount'] = $total_amount; //红包总金额
        $data['total_num'] = '1'; //发放人数据
        $data['wishing'] = $wishing; //红包祝福语
        $data['client_ip'] = Tools::getAddress(); //调用接口的机器Ip地址
        $data['act_name'] = $act_name; //活动名称
        $data['remark'] = $remark; //备注信息
        $data['total_num'] = $total_num;
        !empty($scene_id) && $data['scene_id'] = $scene_id;
        !empty($risk_info) && $data['risk_info'] = $risk_info;
        !empty($consume_mch_id) && $data['consume_mch_id'] = $consume_mch_id;
        if ($total_num > 1) {
            $data['amt_type'] = 'ALL_RAND';
            $api = self::MCH_BASE_URL . '/mmpaymkttransfers/sendgroupredpack';
        } else {
            $api = self::MCH_BASE_URL . '/mmpaymkttransfers/sendredpack';
        }
        $result = $this->postXmlSSL($data, $api);
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }


    /**
     * 现金红包状态查询
     * @param string $billno
     * @return bool|array
     * @link https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_7&index=6
     */
    public function queryRedPack($billno)
    {
        $data['mch_billno'] = $billno;
        $data['bill_type'] = 'MCHT';
        $result = $this->postXmlSSL($data, self::MCH_BASE_URL . '/mmpaymkttransfers/gethbinfo');
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }

    /**
     * 企业付款
     * @param string $openid 红包接收者OPENID
     * @param int $amount 红包总金额
     * @param string $billno 商户订单号
     * @param string $desc 备注信息
     * @return bool|array
     * @link https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_2
     */
    public function transfers($openid, $amount, $billno, $desc)
    {
        $data = array();
        $data['mchid'] = $this->mch_id;
        $data['mch_appid'] = $this->appid;
        $data['partner_trade_no'] = $billno;
        $data['openid'] = $openid;
        $data['amount'] = $amount;
        $data['check_name'] = 'NO_CHECK'; #不验证姓名
        $data['spbill_create_ip'] = Tools::getAddress(); //调用接口的机器Ip地址
        $data['desc'] = $desc; //备注信息
        $result = $this->postXmlSSL($data, self::MCH_BASE_URL . '/mmpaymkttransfers/promotion/transfers');
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }

    /**
     * 企业付款查询
     * @param string $billno
     * @return bool|array
     * @link https://pay.weixin.qq.com/wiki/doc/api/tools/mch_pay.php?chapter=14_3
     */
    public function queryTransfers($billno)
    {
        $data['appid'] = $this->appid;
        $data['mch_id'] = $this->mch_id;
        $data['partner_trade_no'] = $billno;
        $result = $this->postXmlSSL($data, self::MCH_BASE_URL . '/mmpaymkttransfers/gettransferinfo');
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }

    /**
     * 二维码链接转成短链接
     * @param string $url 需要处理的长链接
     * @return bool|string
     */
    public function shortUrl($url)
    {
        $data = array();
        $data['long_url'] = $url;
        $result = $this->getArrayResult($data, self::MCH_BASE_URL . '/tools/shorturl');
        if (!$result || $result['return_code'] !== 'SUCCESS') {
            $this->errCode = $result['return_code'];
            $this->errMsg = $result['return_msg'];
            return false;
        }
        if (isset($result['err_code']) && $result['err_code'] !== 'SUCCESS') {
            $this->errMsg = $result['err_code_des'];
            $this->errCode = $result['err_code'];
            return false;
        }
        return $result['short_url'];
    }

    /**
     * 发放代金券
     * @param int $coupon_stock_id 代金券批次id
     * @param string $partner_trade_no 商户此次发放凭据号（格式：商户id+日期+流水号），商户侧需保持唯一性
     * @param string $openid Openid信息
     * @param string $op_user_id 操作员帐号, 默认为商户号 可在商户平台配置操作员对应的api权限
     * @return bool|array
     * @link  https://pay.weixin.qq.com/wiki/doc/api/tools/sp_coupon.php?chapter=12_3
     */
    public function sendCoupon($coupon_stock_id, $partner_trade_no, $openid, $op_user_id = null)
    {
        $data = array();
        $data['appid'] = $this->appid;
        $data['coupon_stock_id'] = $coupon_stock_id;
        $data['openid_count'] = 1;
        $data['partner_trade_no'] = $partner_trade_no;
        $data['openid'] = $openid;
        $data['op_user_id'] = empty($op_user_id) ? $this->mch_id : $op_user_id;
        $result = $this->postXmlSSL($data, self::MCH_BASE_URL . '/mmpaymkttransfers/send_coupon');
        $json = Tools::xml2arr($result);
        if (!empty($json) && false === $this->_parseResult($json)) {
            return false;
        }
        return $json;
    }
}
