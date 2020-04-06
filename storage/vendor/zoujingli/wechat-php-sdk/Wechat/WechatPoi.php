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

use Wechat\Lib\Common;
use Wechat\Lib\Tools;

/**
 * 微信门店接口
 * @author Anyon <zoujingli@qq.com>
 * @date 2016/10/26 15:43
 */
class WechatPoi extends Common
{

    /** 创建门店 */
    const POI_ADD = '/cgi-bin/poi/addpoi?';

    /** 查询门店信息 */
    const POI_GET = '/cgi-bin/poi/getpoi?';

    /** 获取门店列表 */
    const POI_GET_LIST = '/cgi-bin/poi/getpoilist?';

    /** 修改门店信息 */
    const POI_UPDATE = '/cgi-bin/poi/updatepoi?';

    /** 删除门店 */
    const POI_DELETE = '/cgi-bin/poi/delpoi?';

    /** 获取门店类目表 */
    const POI_CATEGORY = '/cgi-bin/poi/getwxcategory?';

    /**
     * 创建门店
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444378120&token=&lang=zh_CN
     * @param array $data
     * @return bool
     */
    public function addPoi($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::POI_ADD . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 删除门店
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444378120&token=&lang=zh_CN
     * @param string $poi_id JSON数据格式
     * @return bool|array
     */
    public function delPoi($poi_id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('poi_id' => $poi_id);
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::POI_DELETE . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 修改门店服务信息
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444378120&token=&lang=zh_CN
     * @param array $data
     * @return bool
     */
    public function updatePoi($data)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::POI_UPDATE . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return true;
        }
        return false;
    }

    /**
     * 查询门店信息
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444378120&token=&lang=zh_CN
     * @param string $poi_id
     * @return bool
     */
    public function getPoi($poi_id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('poi_id' => $poi_id);
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::POI_GET . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 查询门店列表
     * @link https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1444378120&token=&lang=zh_CN
     * @param int $begin 开始位置，0 即为从第一条开始查询
     * @param int $limit 返回数据条数，最大允许50，默认为20
     * @return bool|array
     */
    public function getPoiList($begin = 0, $limit = 50)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $limit > 50 && $limit = 50;
        $data = array('begin' => $begin, 'limit' => $limit);
        $result = Tools::httpPost(self::API_BASE_URL_PREFIX . self::POI_GET_LIST . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

    /**
     * 获取商家门店类目表
     * @return bool|string
     */
    public function getCategory()
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_URL_PREFIX . self::POI_CATEGORY . "access_token={$this->access_token}");
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json;
        }
        return false;
    }

}
