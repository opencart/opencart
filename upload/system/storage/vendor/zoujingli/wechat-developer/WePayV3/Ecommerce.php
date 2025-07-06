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
use WePayV3\Contracts\BasicWePay;

/**
 * 平台收付通
 * @class Ecommerce
 * @package WePayV3
 */
class Ecommerce extends BasicWePay
{
    /**
     * 提交申请单（商户进件）
     * @param array $data 进件参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidDecryptException
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function ecommerceApplyments($data)
    {
        if (isset($data['id_card_info'])) {
            if (isset($data['id_card_info']['id_card_name'])) $data['id_card_info']['id_card_name'] = $this->rsaEncode($data['id_card_info']['id_card_name']);
            if (isset($data['id_card_info']['id_card_number'])) $data['id_card_info']['id_card_number'] = $this->rsaEncode($data['id_card_info']['id_card_number']);
        }
        if (isset($data['id_doc_info'])) {
            if (isset($data['id_doc_info']['id_doc_name'])) $data['id_doc_info']['id_doc_name'] = $this->rsaEncode($data['id_doc_info']['id_doc_name']);
            if (isset($data['id_doc_info']['id_doc_number'])) $data['id_doc_info']['id_doc_number'] = $this->rsaEncode($data['id_doc_info']['id_doc_number']);
        }
        if (isset($data['contact_info'])) {
            if (isset($data['contact_info']['contact_name'])) $data['contact_info']['contact_name'] = $this->rsaEncode($data['contact_info']['contact_name']);
            if (isset($data['contact_info']['contact_id_card_number'])) $data['contact_info']['contact_id_card_number'] = $this->rsaEncode($data['contact_info']['contact_id_card_number']);
            if (isset($data['contact_info']['mobile_phone'])) $data['contact_info']['mobile_phone'] = $this->rsaEncode($data['contact_info']['mobile_phone']);
        }
        if (isset($data['account_info'])) {
            if (isset($data['account_info']['account_name'])) $data['account_info']['account_name'] = $this->rsaEncode($data['account_info']['account_name']);
            if (isset($data['account_info']['account_number'])) $data['account_info']['account_number'] = $this->rsaEncode($data['account_info']['account_number']);
        }
        if (!empty($data['ubo_info_list'])) {
            $data['ubo_info_list'] = array_map(function ($item) {
                $item['ubo_id_doc_name'] = $this->rsaEncode($item['ubo_id_doc_name']);
                $item['ubo_id_doc_number'] = $this->rsaEncode($item['ubo_id_doc_number']);
                $item['ubo_id_doc_address'] = $this->rsaEncode($item['ubo_id_doc_address']);
                return $item;
            }, $data['ubo_info_list']);
        }
        return $this->doRequest('POST', '/v3/ecommerce/applyments/', json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 通过业务申请编号查询申请状态（商户进件）
     * @param string $out_request_no 业务申请编号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function ecommerceApplymentsByRequestNo($out_request_no)
    {
        $pathinfo = "/v3/ecommerce/applyments/out-request-no/{$out_request_no}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 通过申请单ID查询申请状态（商户进件）
     * @param string $applyment_id 微信支付申请单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function ecommerceApplymentsByApplymentId($applyment_id)
    {
        $pathinfo = "/v3/ecommerce/applyments/{$applyment_id}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 修改结算账户（商户进件）
     * @param string $sub_mchid 特约商户/二级商户号
     * @param array $data 包体参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function modifySettlement($sub_mchid, $data)
    {
        $pathinfo = "/v3/apply4sub/sub_merchants/{$sub_mchid}/modify-settlement";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询结算账户修改申请状态（商户进件）
     * @param string $sub_mchid 特约商户/二级商户号
     * @param string $application_no 修改结算账户申请单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function modifySettlementResult($sub_mchid, $application_no)
    {
        $pathinfo = "/v3/apply4sub/sub_merchants/{$sub_mchid}/application/{$application_no}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }


    /**
     * 查询结算账户（商户进件）
     * @param string $sub_mchid 特约商户/二级商户号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function getSettlement($sub_mchid)
    {
        $pathinfo = "/v3/apply4sub/sub_merchants/{$sub_mchid}/settlement";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 上传文件（商户进件）
     * @param string $filename 文件目录
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function mediaUpload($filename)
    {
        return $this->doUpload('/v3/merchant/media/upload', $filename, true);
    }

    /**
     * APP下单（普通支付）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function transactionsApp($data)
    {
        $pathinfo = "/v3/pay/partner/transactions/app";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * JSAPI下单（普通支付）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function transactionsJsapi($data)
    {
        $pathinfo = "/v3/pay/partner/transactions/jsapi";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * Native下单（普通支付）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function transactionsNative($data)
    {
        $pathinfo = "/v3/pay/partner/transactions/native";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * H5下单（普通支付）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function transactionsH5($data)
    {
        $pathinfo = "/v3/pay/partner/transactions/h5";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 微信支付订单号查询订单（普通支付）
     * @param string $transaction_id
     * @param string $sub_mchid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function getTransactionsById($transaction_id, $sub_mchid)
    {
        $pathinfo = "/v3/pay/partner/transactions/id/{$transaction_id}";
        $pathinfo = $pathinfo . "?sp_mchid={$this->config['mch_id']}&sub_mchid={$sub_mchid}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 微信支付商户订单号查询订单（普通支付）
     * @param string $out_trade_no
     * @param string $sub_mchid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function getTransactionsByTradeNo($out_trade_no, $sub_mchid)
    {
        $pathinfo = "/v3/pay/partner/transactions/out-trade-no/{$out_trade_no}";
        $pathinfo = $pathinfo . "?sp_mchid={$this->config['mch_id']}&sub_mchid={$sub_mchid}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 关闭订单（普通支付）
     * @param string $out_trade_no 商户订单号
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function transactionsClose($out_trade_no, $data)
    {
        $pathinfo = "/v3/pay/partner/transactions/out-trade-no/{$out_trade_no}/close";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);

    }

    /**
     * JSAPI支付（合单下单）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function combineTransactionsJsapi($data)
    {
        $pathinfo = "/v3/combine-transactions/jsapi";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * APP支付（合单下单）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function combineTransactionsApp($data)
    {
        $pathinfo = "/v3/combine-transactions/app";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * H5支付（合单下单）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function combineTransactionsH5($data)
    {
        $pathinfo = "/v3/combine-transactions/h5";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * Native支付（合单下单）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function combineTransactionsNative($data)
    {
        $pathinfo = "/v3/combine-transactions/native";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 合单查询（合单支付）
     * @param string $combine_out_trade_no
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function getCombineTransactionsByTradeNo($combine_out_trade_no)
    {
        $pathinfo = "/v3/combine-transactions/out-trade-no/{$combine_out_trade_no}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 合单关单（合单下单）
     * @param $combine_out_trade_no
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function combineTransactionsClose($combine_out_trade_no, $data)
    {
        $pathinfo = "/v3/combine-transactions/out-trade-no/{$combine_out_trade_no}/close";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 请求分账(分账)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingOrders($data)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/orders";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询分账结果(分账)
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryProfitsharingOrders($param = [])
    {
        $pathinfo = "/v3/ecommerce/profitsharing/orders?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 请求分账回退(分账)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingReturnOrders($data)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/returnorders";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询分账回退结果(分账)
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryProfitsharingReturnOrders($param = [])
    {
        $pathinfo = "/v3/ecommerce/profitsharing/returnorders?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 完结分账(分账)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingFinishOrder($data)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/finish-order";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询订单剩余待分金额(分账)
     * @param string $transaction_id 微信订单号
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingReturnOrdersAmounts($transaction_id)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/orders/{$transaction_id}/amounts";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 添加分账接收方(分账)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingReceiversAdd($data)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/receivers/add";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 添加分账接收方(分账)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingReceiversDelete($data)
    {
        $pathinfo = "/v3/ecommerce/profitsharing/receivers/delete";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 请求补差(补差)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function subsidiesCreate($data)
    {
        $pathinfo = "/v3/ecommerce/subsidies/create";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 请求补差回退(补差)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function subsidiesReturn($data)
    {
        $pathinfo = "/v3/ecommerce/subsidies/return";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 取消补差(补差)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function subsidiesCancel($data)
    {
        $pathinfo = "/v3/ecommerce/subsidies/cancel";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 申请退款(退款)
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function refundsApply($data)
    {
        $pathinfo = "/v3/ecommerce/refunds/apply";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询单笔退款（按商户退款单号）(退款)
     * @param string $refund_id
     * @param string $sub_mchid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryRefundsById($refund_id, $sub_mchid)
    {
        $pathinfo = "/v3/ecommerce/refunds/id/{$refund_id}?sub_mchid={$$sub_mchid}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询单笔退款（按商户退款单号）(退款)
     * @param string $out_refund_no
     * @param string $sub_mchid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryRefundsByNo($out_refund_no, $sub_mchid)
    {
        $pathinfo = "/v3/ecommerce/refunds/out-refund-no/{$out_refund_no}?sub_mchid={$sub_mchid}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询垫付回补结果(退款)
     * @param string $refund_id
     * @param string $sub_mchid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryRefundsReturnAdvance($refund_id, $sub_mchid)
    {
        $pathinfo = "/v3/ecommerce/refunds/{$refund_id}/return-advance?sub_mchid={$sub_mchid}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 垫付退款回补
     * @param string $refund_id
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function refundsReturnAdvance($refund_id, $data)
    {
        $pathinfo = "/v3/ecommerce/refunds/{$refund_id}/return-advance";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 查询二级商户账户实时余额API(余额查询)
     * @param string $sub_mchid
     * @param string $account_type 二级商户账户类型 BASIC: 基本账户 FEES: 手续费账户 OPERATION: 运营账户 DEPOSIT: 保证金账户
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function fundBalance($sub_mchid, $account_type = 'BASIC')
    {
        $pathinfo = "/v3/ecommerce/fund/balance/{$sub_mchid}?account_type={$account_type}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询二级商户账户日终余额API(余额查询)
     * @param string $sub_mchid
     * @param array $query
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function fundEnddayBalance($sub_mchid, $query)
    {
        $pathinfo = "/v3/ecommerce/fund/enddaybalance/{$sub_mchid}?" . http_build_query($query);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询收付通平台账户实时余额API(余额查询)
     * @param string $account_type
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function merchantFundBalance($account_type)
    {
        $pathinfo = "/v3/merchant/fund/balance/{$account_type}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询收付通平台账户日终余额API(余额查询)
     * @param string $account_type
     * @param array $query
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function merchantEnddayBalance($account_type, $query)
    {
        $pathinfo = "/v3/merchant/fund/enddaybalance/{$account_type}?" . http_build_query($query);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 二级商户预约提现（商户提现）
     * @param array $data POST请求参数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function fundWithdraw($data)
    {
        $pathinfo = "/v3/ecommerce/fund/withdraw";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 二级商户查询预约提现状态（根据商户预约提现单号查询）（商户提现）
     * @param string $out_request_no
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryFundWithdrawByNo($out_request_no)
    {
        $pathinfo = "/v3/ecommerce/fund/withdraw/out-request-no/{$out_request_no}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 二级商户查询预约提现状态（根据微信支付预约提现单号查询）（商户提现）
     * @param string $withdraw_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryFundWithdrawById($withdraw_id)
    {
        $pathinfo = "/v3/ecommerce/fund/withdraw/{$withdraw_id}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 收付通平台预约提现（商户提现）
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function merchantFundWithdraw($data)
    {
        $pathinfo = "/v3/merchant/fund/withdraw";
        return $this->doRequest('POST', $pathinfo, json_encode($data, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 收付通平台查询预约提现状态（根据商户预约提现单号查询）（商户提现）
     * @param string $out_request_no
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryMerchantFundWithdrawByNo($out_request_no)
    {
        $pathinfo = "/v3/merchant/fund/withdraw/out-request-no/{$out_request_no}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 收付通平台查询预约提现状态（根据微信支付预约提现单号查询）（商户提现）
     * @param string $withdraw_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryMerchantFundWithdrawById($withdraw_id)
    {
        $pathinfo = "/v3/merchant/fund/withdraw/withdraw-id/{$withdraw_id}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 按日下载提现异常文件（商户提现）
     * @param $bill_type
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function fundWithdrawBill($bill_type, $param = [])
    {
        $pathinfo = "/v3/merchant/fund/withdraw/bill-type/{$bill_type}?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 申请交易账单（下载账单）
     * @param $bill_type
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function tradeBill($bill_type, $param = [])
    {
        $pathinfo = "/v3/bill/tradebill?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }


    /**
     * 申请资金账单（下载账单）
     * @param $bill_type
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function fundFlowBill($bill_type, $param = [])
    {
        $pathinfo = "/v3/bill/fundflowbill?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }


    /**
     * 申请分账账单（下载账单）
     * @param string $bill_type
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function profitsharingBill($bill_type, $param = [])
    {
        $pathinfo = "/v3/profitsharing/bills?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }


    /**
     * 申请二级商户资金账单（下载账单）
     * @param string $bill_type
     * @param array $param
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function subFundFlowBill($bill_type, $param = [])
    {
        $pathinfo = "/v3/ecommerce/bill/fundflowbill?" . http_build_query($param);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询支持个人业务的银行列表
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function personalBanking($offset, $limit)
    {
        $pathinfo = "/v3/capital/capitallhh/banks/personal-banking?" . http_build_query(['offset' => $offset, 'limit' => $limit]);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询省份列表
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function provinces()
    {
        $pathinfo = "/v3/capital/capitallhh/areas/provinces";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询城市列表
     * @param int $code
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function cities($code)
    {
        $pathinfo = "/v3/capital/capitallhh/areas/provinces/{$code}/cities";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询支行列表
     * @param int $code
     * @param $city
     * @param $offset
     * @param $limit
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function branches($code, $city, $offset, $limit)
    {
        $pathinfo = "/v3/capital/capitallhh/banks/{$code}/branches?" . http_build_query(['city_code' => $city, 'offset' => $offset, 'limit' => $limit]);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询支持对公业务的银行列表
     * @param int $offset
     * @param int $limit
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function corporateBanking($offset, $limit)
    {
        $pathinfo = "/v3/capital/capitallhh/banks/corporate-banking?" . http_build_query(['offset' => $offset, 'limit' => $limit]);
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 通过支付预订单ID获取支付参数
     * @param string $prepay_id 支付预订单ID
     * @param string $type 类型
     * @return array
     */
    public function getJsApiParameters($prepay_id, $type = 'jsapi')
    {
        // 支付参数签名
        $time = strval(time());
        $appid = $this->config['appid'];
        $nonceStr = Tools::createNoncestr();
        if ($type === Order::WXPAY_APP) {
            $sign = $this->signBuild(join("\n", [$appid, $time, $nonceStr, $prepay_id, '']));
            return ['partnerId' => $this->config['mch_id'], 'prepayId' => $prepay_id, 'package' => 'Sign=WXPay', 'nonceStr' => $nonceStr, 'timeStamp' => $time, 'sign' => $sign];
        } elseif ($type === Order::WXPAY_JSAPI) {
            $sign = $this->signBuild(join("\n", [$appid, $time, $nonceStr, "prepay_id={$prepay_id}", '']));
            return ['appId' => $appid, 'timestamp' => $time, 'timeStamp' => $time, 'nonceStr' => $nonceStr, 'package' => "prepay_id={$prepay_id}", 'signType' => 'RSA', 'paySign' => $sign];
        } else {
            return [];
        }
    }
}