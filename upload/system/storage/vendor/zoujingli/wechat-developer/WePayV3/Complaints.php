<?php

namespace WePayV3;

use WePayV3\Contracts\BasicWePay;

/**
 * 普通商户消费者投诉2.0
 * @class Complaints
 * @package WePayV3
 */
class Complaints extends BasicWePay
{

    /**
     * 查询投诉列表
     * @param int $offset 分页开始位置
     * @param int $limit 分页大小
     * @param String $begin_date 开始日期
     * @param String $end_date 结束日期
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function complaintList($offset, $limit, $begin_date, $end_date)
    {
        $mchId = $this->config['mch_id'];
        $pathinfo = "/v3/merchant-service/complaints-v2?limit={$limit}&offset={$offset}&begin_date={$begin_date}&end_date={$end_date}&complainted_mchid={$mchId}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询投诉详情
     * @param string $complaint_id 被投诉单号
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function complaintDetails($complaint_id)
    {
        $pathinfo = "/v3/merchant-service/complaints-v2/{$complaint_id}";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 查询投诉协商历史
     * @param string $complaint_id 被投诉单号
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function negotiationHistory($complaint_id)
    {
        $pathinfo = "/v3/merchant-service/complaints-v2/{$complaint_id}/negotiation-historys";
        return $this->doRequest('GET', $pathinfo, '', true);
    }

    /**
     * 创建投诉通知回调地址
     * @param string $url 回调通知地址
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function CreateComplaintsNotify($url)
    {
        return $this->doRequest('POST', '/v3/merchant-service/complaint-notifications', json_encode(['url' => $url], JSON_UNESCAPED_UNICODE), true);

    }

    /**
     * 查询投诉通知回调地址
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function queryComplaintsNotify()
    {
        return $this->doRequest('GET', '/v3/merchant-service/complaint-notifications', '', true);

    }

    /**
     * 更新投诉通知回调地址
     * @param string $url 回调通知地址
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function updateComplaintsNotify($url)
    {
        return $this->doRequest('PUT', '/v3/merchant-service/complaint-notifications', json_encode(['url' => $url], JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 删除投诉通知回调地址
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function deleteComplaintsNotify()
    {
        return $this->doRequest('DELETE', '/v3/merchant-service/complaint-notifications', '', true);
    }

    /**
     * 回复投诉
     * @param string $complaint_id 被投诉单号
     * @param array $content 回复内容
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function replyInfo($complaint_id, array $content)
    {
        $content['complainted_mchid'] = $this->config['mch_id'];
        $pathinfo = "/v3/merchant-service/complaints-v2/{$complaint_id}/response";
        return $this->doRequest('POST', $pathinfo, json_encode($content, JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 反馈处理完成
     * @param string $complaint_id 被投诉单号
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function completeComplaints($complaint_id)
    {
        $mchId = $this->config['mch_id'];
        $pathinfo = "/v3/merchant-service/complaints-v2/{$complaint_id}/complete";
        return $this->doRequest('POST', $pathinfo, json_encode(['complainted_mchid' => $mchId], JSON_UNESCAPED_UNICODE), true);
    }

    /**
     * 图片请求接口
     * @param string $pathinfo
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function downLoadImg($pathinfo)
    {
        return $this->doRequest('GET', $pathinfo, '', true, false);
    }

    /**
     * 图片上传接口
     * @param array $imginfo
     * @return array|string
     * @throws \WeChat\Exceptions\InvalidResponseException
     */
    public function uploadImg(array $imginfo)
    {
        return $this->doRequest('POST', '/v3/merchant-service/images/upload', json_encode($imginfo, JSON_UNESCAPED_UNICODE), true);
    }
}