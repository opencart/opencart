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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;

/**
 * 微信小程序地址管理
 * Class Poi
 * @package WeMini
 */
class Poi extends BasicWeChat
{
    /**
     * 添加地点
     * @param string $related_name 经营资质主体
     * @param string $related_credential 经营资质证件号
     * @param string $related_address 经营资质地址
     * @param string $related_proof_material 相关证明材料照片临时素材mediaid
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addBearByPoi($related_name, $related_credential, $related_address, $related_proof_material)
    {
        $url = 'https://api.weixin.qq.com/wxa/addnearbypoi?access_token=ACCESS_TOKEN';
        $this->registerApi($url, __FUNCTION__, func_get_args());
        $data = [
            'related_name'    => $related_name, 'related_credential' => $related_credential,
            'related_address' => $related_address, 'related_proof_material' => $related_proof_material,
        ];
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 查看地点列表
     * @param integer $page 起始页id（从1开始计数）
     * @param integer $page_rows 每页展示个数（最多1000个）
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getNearByPoiList($page = 1, $page_rows = 1000)
    {
        $url = "https://api.weixin.qq.com/wxa/getnearbypoilist?page={$page}&page_rows={$page_rows}&access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callGetApi($url);
    }

    /**
     * 删除地点
     * @param string $poi_id 附近地点ID
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delNearByPoiList($poi_id)
    {
        $url = "https://api.weixin.qq.com/wxa/delnearbypoi?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['poi_id' => $poi_id], true);
    }

    /**
     * 展示/取消展示附近小程序
     * @param string $poi_id 附近地点ID
     * @param string $status 0：取消展示；1：展示
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function setNearByPoiShowStatus($poi_id, $status)
    {
        $url = "https://api.weixin.qq.com/wxa/setnearbypoishowstatus?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->callPostApi($url, ['poi_id' => $poi_id, 'status' => $status], true);
    }

}