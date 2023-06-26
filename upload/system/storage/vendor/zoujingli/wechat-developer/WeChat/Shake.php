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


use WeChat\Contracts\Tools;
use WeChat\Contracts\BasicWeChat;

/**
 * 揺一揺周边
 * Class Shake
 * @package WeChat
 */
class Shake extends BasicWeChat
{
    /**
     * 申请开通功能
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function register(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/account/register?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询审核状态
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function auditStatus()
    {
        $url = "https://api.weixin.qq.com/shakearound/account/auditstatus?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpGetForJson($url);
    }

    /**
     * 申请设备ID
     * @param string $quantity 申请的设备ID的数量，单次新增设备超过500个，需走人工审核流程
     * @param string $apply_reason 申请理由，不超过100个汉字或200个英文字母
     * @param null|string $comment 备注，不超过15个汉字或30个英文字母
     * @param null|string $poi_id 设备关联的门店ID，关联门店后，在门店1KM的范围内有优先摇出信息的机会。
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function createApply($quantity, $apply_reason, $comment = null, $poi_id = null)
    {
        $data = ['quantity' => $quantity, 'apply_reason' => $apply_reason];
        is_null($poi_id) || $data['poi_id'] = $poi_id;
        is_null($comment) || $data['comment'] = $comment;
        $url = "https://api.weixin.qq.com/shakearound/device/applyid?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询设备ID申请审核状态
     * @param integer $applyId 批次ID，申请设备ID时所返回的批次ID
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getApplyStatus($applyId)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/applyid?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['apply_id' => $applyId]);
    }

    /**
     * 编辑设备信息
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateApply(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 配置设备与门店的关联关系
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function bindLocation(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/bindlocation?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询设备列表
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function search(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/search?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 页面管理
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function createPage(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/page/add?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 编辑页面信息
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updatePage(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/page/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询页面列表
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function searchPage(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/page/search?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 删除页面
     * @param integer page_id   指定页面的id
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function deletePage($page_id)
    {
        $url = "https://api.weixin.qq.com/shakearound/page/delete?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['page_id' => $page_id]);
    }

    /**
     * 上传图片素材
     * @param string $filename 图片名字
     * @param string $type Icon：摇一摇页面展示的icon图；License：申请开通摇一摇周边功能时需上传的资质文件；若不传type，则默认type=icon
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function upload($filename, $type = 'icon')
    {
        $url = "https://api.weixin.qq.com/shakearound/material/add?access_token=ACCESS_TOKEN&type={$type}";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['media' => Tools::createCurlFile($filename)]);
    }

    /**
     * 配置设备与页面的关联关系
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function bindPage(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/bindpage?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 查询设备与页面的关联关系
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function queryPage(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/relation/search?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 以设备为维度的数据统计接口
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function totalDevice(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/statistics/device?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 批量查询设备统计数据接口
     * @param integer $date 指定查询日期时间戳，单位为秒
     * @param integer $page_index 指定查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function totalDeviceList($date, $page_index = 1)
    {
        $url = "https://api.weixin.qq.com/shakearound/statistics/devicelist?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['date' => $date, 'page_index' => $page_index]);
    }

    /**
     * 以页面为维度的数据统计接口
     * @param integer $page_id 指定页面的设备ID
     * @param integer $begin_date 起始日期时间戳，最长时间跨度为30天，单位为秒
     * @param integer $end_date 结束日期时间戳，最长时间跨度为30天，单位为秒
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function totalPage($page_id, $begin_date, $end_date)
    {
        $url = "https://api.weixin.qq.com/shakearound/statistics/page?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['page_id' => $page_id, 'begin_date' => $begin_date, 'end_date' => $end_date]);
    }

    /**
     * 编辑分组信息
     * @param integer $group_id 分组唯一标识，全局唯一
     * @param string $group_name 分组名称，不超过100汉字或200个英文字母
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function updateGroup($group_id, $group_name)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/update?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['group_id' => $group_id, 'group_name' => $group_name]);
    }

    /**
     * 删除分组
     * @param integer $group_id 分组唯一标识，全局唯一
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function deleteGroup($group_id)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/delete?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['group_id' => $group_id]);
    }

    /**
     * 查询分组列表
     * @param integer $begin 分组列表的起始索引值
     * @param integer $count 待查询的分组数量，不能超过1000个
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getGroupList($begin = 0, $count = 10)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/getlist?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['begin' => $begin, 'count' => $count]);
    }


    /**
     * 查询分组详情
     * @param integer $group_id 分组唯一标识，全局唯一
     * @param integer $begin 分组里设备的起始索引值
     * @param integer $count 待查询的分组里设备的数量，不能超过1000个
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getGroupDetail($group_id, $begin = 0, $count = 100)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/getdetail?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, ['group_id' => $group_id, 'begin' => $begin, 'count' => $count]);
    }

    /**
     * 添加设备到分组
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function addDeviceGroup(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/adddevice?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

    /**
     * 从分组中移除设备
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function deleteDeviceGroup(array $data)
    {
        $url = "https://api.weixin.qq.com/shakearound/device/group/deletedevice?access_token=ACCESS_TOKEN";
        $this->registerApi($url, __FUNCTION__, func_get_args());
        return $this->httpPostForJson($url, $data);
    }

}