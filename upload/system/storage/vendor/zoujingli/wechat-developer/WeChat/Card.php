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

namespace WeChat;

use WeChat\Contracts\BasicWeChat;

/**
 * 卡券管理
 * Class Card
 * @package WeChat
 */
class Card extends BasicWeChat
{
    /**
     * 创建卡券
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function create(array $data)
    {
        $url = "https://api.weixin.qq.com/card/create?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 设置买单接口
     * @param string $card_id
     * @param bool $is_open
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setPaycell($card_id, $is_open = true)
    {
        $url = "https://api.weixin.qq.com/card/paycell/set?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'is_open' => $is_open]);
    }

    /**
     * 设置自助核销接口
     * @param string $card_id
     * @param bool $is_open
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setConsumeCell($card_id, $is_open = true)
    {
        $url = "https://api.weixin.qq.com/card/selfconsumecell/set?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'is_open' => $is_open]);
    }

    /**
     * 创建二维码接口
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function createQrc(array $data)
    {
        $url = "https://api.weixin.qq.com/card/qrcode/create?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 创建货架接口
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function createLandingPage(array $data)
    {
        $url = "https://api.weixin.qq.com/card/landingpage/create?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 导入自定义code
     * @param string $card_id
     * @param array $code
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function deposit($card_id, array $code)
    {
        $url = "https://api.weixin.qq.com/card/code/deposit?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'code' => $code]);
    }

    /**
     * 查询导入code数目
     * @param string $card_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getDepositCount($card_id)
    {
        $url = "https://api.weixin.qq.com/card/code/getdepositcount?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id]);
    }

    /**
     * 核查code接口
     * @param string $card_id 进行导入code的卡券ID
     * @param array $code 已经微信卡券后台的自定义code，上限为100个
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function checkCode($card_id, array $code)
    {
        $url = "https://api.weixin.qq.com/card/code/checkcode?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'code' => $code]);
    }

    /**
     * 图文消息群发卡券
     * @param string $card_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getNewsHtml($card_id)
    {
        $url = "https://api.weixin.qq.com/card/mpnews/gethtml?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id]);
    }

    /**
     * 设置测试白名单
     * @param array $openids
     * @param array $usernames
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setTestWhiteList($openids = [], $usernames = [])
    {
        $url = "https://api.weixin.qq.com/card/testwhitelist/set?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['openid' => $openids, 'username' => $usernames]);
    }

    /**
     * 线下核销查询Code
     * @param string $code 单张卡券的唯一标准
     * @param string $card_id 卡券ID代表一类卡券。自定义code卡券必填
     * @param bool $check_consume 是否校验code核销状态，填入true和false时的code异常状态返回数据不同
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCode($code, $card_id = null, $check_consume = null)
    {
        $data = ['code' => $code];
        is_null($card_id) || $data['card_id'] = $card_id;
        is_null($check_consume) || $data['check_consume'] = $check_consume;
        $url = "https://api.weixin.qq.com/card/code/get?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 线下核销核销Code
     * @param string $code 需核销的Code码
     * @param null $card_id 券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function consume($code, $card_id = null)
    {
        $data = ['code' => $code];
        is_null($card_id) || $data['card_id'] = $card_id;
        $url = "https://api.weixin.qq.com/card/code/consume?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * Code解码接口
     * @param string $encrypt_code
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function decrypt($encrypt_code)
    {
        $url = "https://api.weixin.qq.com/card/code/decrypt?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['encrypt_code' => $encrypt_code]);
    }

    /**
     * 获取用户已领取卡券接口
     * @param string $openid
     * @param null|string $card_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardList($openid, $card_id = null)
    {
        $data = ['openid' => $openid];
        is_null($card_id) || $data['card_id'] = $card_id;
        $url = "https://api.weixin.qq.com/card/user/getcardlist?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查看卡券详情
     * @param string $card_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCard($card_id)
    {
        $url = "https://api.weixin.qq.com/card/get?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id]);
    }

    /**
     * 批量查询卡券列表
     * @param int $offset 查询卡列表的起始偏移量，从0开始，即offset: 5是指从从列表里的第六个开始读取
     * @param int $count 需要查询的卡片的数量（数量最大50）
     * @param array $status_list 支持开发者拉出指定状态的卡券列表
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function batchGet($offset, $count = 50, array $status_list = [])
    {
        $data = ['offset' => $offset, 'count' => $count];
        empty($status_list) || $data['status_list'] = $status_list;
        $url = "https://api.weixin.qq.com/card/batchget?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 更改卡券信息接口
     * @param string $card_id
     * @param array $member_card
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateCard($card_id, array $member_card)
    {
        $url = "https://api.weixin.qq.com/card/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'member_card' => $member_card]);
    }

    /**
     * 修改库存接口
     * @param string $card_id 卡券ID
     * @param null|integer $increase_stock_value 增加多少库存，支持不填或填0
     * @param null|integer $reduce_stock_value 减少多少库存，可以不填或填0
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function modifyStock($card_id, $increase_stock_value = null, $reduce_stock_value = null)
    {
        $data = ['card_id' => $card_id];
        is_null($increase_stock_value) || $data['increase_stock_value'] = $increase_stock_value;
        is_null($reduce_stock_value) || $data['reduce_stock_value'] = $reduce_stock_value;
        $url = "https://api.weixin.qq.com/card/modifystock?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 更改Code接口
     * @param string $code 需变更的Code码
     * @param string $new_code 变更后的有效Code码
     * @param null|string $card_id 卡券ID
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateCode($code, $new_code, $card_id = null)
    {
        $data = ['code' => $code, 'new_code' => $new_code];
        is_null($card_id) || $data['card_id'] = $card_id;
        $url = "https://api.weixin.qq.com/card/code/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 删除卡券接口
     * @param string $card_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function deleteCard($card_id)
    {
        $url = "https://api.weixin.qq.com/card/delete?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id]);
    }

    /**
     * 设置卡券失效接口
     * @param string $code
     * @param string $card_id
     * @param null|string $reason
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function unAvailable($code, $card_id, $reason = null)
    {
        $data = ['code' => $code, 'card_id' => $card_id];
        is_null($reason) || $data['reason'] = $reason;
        $url = "https://api.weixin.qq.com/card/code/unavailable?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 拉取卡券概况数据接口
     * @param string $begin_date 查询数据的起始时间
     * @param string $end_date 查询数据的截至时间
     * @param string $cond_source 卡券来源(0为公众平台创建的卡券数据 1是API创建的卡券数据)
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardBizuininfo($begin_date, $end_date, $cond_source)
    {
        $data = ['begin_date' => $begin_date, 'end_date' => $end_date, 'cond_source' => $cond_source];
        $url = "https://api.weixin.qq.com/datacube/getcardbizuininfo?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 获取免费券数据接口
     * @param string $begin_date 查询数据的起始时间
     * @param string $end_date 查询数据的截至时间
     * @param integer $cond_source 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     * @param null $card_id 卡券ID
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardCardinfo($begin_date, $end_date, $cond_source, $card_id = null)
    {
        $data = ['begin_date' => $begin_date, 'end_date' => $end_date, 'cond_source' => $cond_source];
        is_null($card_id) || $data['card_id'] = $card_id;
        $url = "https://api.weixin.qq.com/datacube/getcardcardinfo?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }


    /**
     * 激活会员卡
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function activateMemberCard(array $data)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/activate?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 设置开卡字段接口
     * 用户激活时需要填写的选项
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setActivateMemberCardUser(array $data)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 获取用户提交资料
     * 根据activate_ticket获取到用户填写的信息
     * @param string $activate_ticket
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getActivateMemberCardTempinfo($activate_ticket)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/activatetempinfo/get?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['activate_ticket' => $activate_ticket]);
    }

    /**
     * 更新会员信息
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateMemberCardUser(array $data)
    {
        $url = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 拉取会员卡概况数据接口
     * @param string $begin_date 查询数据的起始时间
     * @param string $end_date 查询数据的截至时间
     * @param string $cond_source 卡券来源(0为公众平台创建的卡券数据 1是API创建的卡券数据)
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardMemberCardinfo($begin_date, $end_date, $cond_source)
    {
        $data = ['begin_date' => $begin_date, 'end_date' => $end_date, 'cond_source' => $cond_source];
        $url = "https://api.weixin.qq.com/datacube/getcardmembercardinfo?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 拉取单张会员卡数据接口
     * @param string $begin_date 查询数据的起始时间
     * @param string $end_date 查询数据的截至时间
     * @param string $card_id 卡券id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardMemberCardDetail($begin_date, $end_date, $card_id)
    {
        $data = ['begin_date' => $begin_date, 'end_date' => $end_date, 'card_id' => $card_id];
        $url = "https://api.weixin.qq.com/datacube/getcardmembercarddetail?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 拉取会员信息（积分查询）接口
     * @param string $card_id 查询会员卡的cardid
     * @param string $code 所查询用户领取到的code值
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCardMemberCard($card_id, $code)
    {
        $data = ['card_id' => $card_id, 'code' => $code];
        $url = "https://api.weixin.qq.com/card/membercard/userinfo/get?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 设置支付后投放卡券接口
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payGiftCard(array $data)
    {
        $url = "https://api.weixin.qq.com/card/paygiftcard/add?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 删除支付后投放卡券规则
     * @param integer $rule_id 支付即会员的规则名称
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function delPayGiftCard($rule_id)
    {
        $url = "https://api.weixin.qq.com/card/paygiftcard/add?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['rule_id' => $rule_id]);
    }

    /**
     * 查询支付后投放卡券规则详情
     * @param integer $rule_id 要查询规则id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getPayGiftCard($rule_id)
    {
        $url = "https://api.weixin.qq.com/card/paygiftcard/getbyid?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['rule_id' => $rule_id]);
    }

    /**
     * 批量查询支付后投放卡券规则
     * @param integer $offset 起始偏移量
     * @param integer $count 查询的数量
     * @param bool $effective 是否仅查询生效的规则
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function batchGetPayGiftCard($offset = 0, $count = 10, $effective = true)
    {
        $data = ['type' => 'RULE_TYPE_PAY_MEMBER_CARD', 'offset' => $offset, 'count' => $count, 'effective' => $effective];
        $url = "https://api.weixin.qq.com/card/paygiftcard/batchget?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 创建支付后领取立减金活动
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function addActivity(array $data)
    {
        $url = "https://api.weixin.qq.com/card/mkt/activity/create?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 开通券点账户接口
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payActivate()
    {
        $url = "https://api.weixin.qq.com/card/pay/activate?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpGetForJson($url);
    }

    /**
     * 对优惠券批价
     * @param string $card_id 需要来配置库存的card_id
     * @param integer $quantity 本次需要兑换的库存数目
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getPayprice($card_id, $quantity)
    {
        $url = "POST https://api.weixin.qq.com/card/pay/getpayprice?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['card_id' => $card_id, 'quantity' => $quantity]);
    }

    /**
     * 查询券点余额接口
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getCoinsInfo()
    {
        $url = "https://api.weixin.qq.com/card/pay/getcoinsinfo?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpGetForJson($url);
    }

    /**
     * 确认兑换库存接口
     * @param string $card_id 需要来兑换库存的card_id
     * @param integer $quantity 本次需要兑换的库存数目
     * @param string $order_id 仅可以使用上面得到的订单号，保证批价有效性
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payConfirm($card_id, $quantity, $order_id)
    {
        $data = ['card_id' => $card_id, 'quantity' => $quantity, 'order_id' => $order_id];
        $url = "https://api.weixin.qq.com/card/pay/confirm?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 充值券点接口
     * @param integer $coin_count
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payRecharge($coin_count)
    {
        $url = "https://api.weixin.qq.com/card/pay/recharge?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['coin_count' => $coin_count]);
    }

    /**
     * 查询订单详情接口
     * @param string $order_id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payGetOrder($order_id)
    {
        $url = "https://api.weixin.qq.com/card/pay/getorder?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['order_id' => $order_id]);
    }

    /**
     * 查询券点流水详情接口
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function payGetList(array $data)
    {
        $url = "https://api.weixin.qq.com/card/pay/getorderlist?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }
    
}