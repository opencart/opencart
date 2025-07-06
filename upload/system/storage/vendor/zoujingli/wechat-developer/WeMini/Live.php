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

namespace WeMini;

use WeChat\Contracts\BasicWeChat;

/**
 * 小程序直播接口
 * Class Live
 * @package WeMini
 */
class Live extends BasicWeChat
{
    /**
     * 创建直播间
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function create($data)
    {
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/create?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取直播房间列表
     * @param integer $start 起始拉取房间
     * @param integer $limit 每次拉取的个数上限
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getLiveList($start = 0, $limit = 10)
    {
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['start' => $start, 'limit' => $limit], true);
    }

    /**
     * 获取回放源视频
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getLiveInfo($data = [])
    {
        $url = 'https://api.weixin.qq.com/wxa/business/getliveinfo?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 直播间导入商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addLiveGoods($data = [])
    {
        $url = 'https://api.weixin.qq.com/wxaapi/broadcast/room/addgoods?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 商品添加并提审
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/add?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 商品撤回审核
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function resetAuditGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/resetaudit?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 重新提交审核
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function auditGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/audit?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 删除商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function deleteGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/delete?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 更新商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/update?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取商品状态
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function stateGoods($data)
    {
        $url = "https://api.weixin.qq.com/wxa/business/getgoodswarehouse?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取商品列表
     * @param integer $offset 分页条数起点
     * @param integer $status 商品状态，0：未审核。1：审核中，2：审核通过，3：审核驳回
     * @param integer $limit 分页大小，默认30，不超过100
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getGoods($offset, $status, $limit = 30)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/getapproved?access_token=ACCESS_TOKEN&offset={$offset}&limit={$limit}&status={$status}";
        return $this->callGetApi($url);
    }

    /**
     * 删除直播间
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delLive($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/deleteroom?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 编辑直播间
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function editLive($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/editroom?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取直播间推流地址
     * @param string $roomId
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getPushUrl($roomId)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/getpushurl?access_token=ACCESS_TOKEN&roomId={$roomId}";
        return $this->callGetApi($url);
    }

    /**
     * 获取直播间分享二维码
     * @param string $roomId
     * @param string $params
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getShareCode($roomId, $params = '')
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/getsharedcode?access_token=ACCESS_TOKEN&roomId={$roomId}&params={$params}";
        return $this->callGetApi($url);
    }

    /**
     * 添加管理直播间小助手
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addAssistant($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/addassistant?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 修改管理直播间小助手
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function modifyAssistant($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/modifyassistant?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 删除管理直播间小助手
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function removeAssistant($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/removeassistant?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 查询管理直播间小助手
     * @param string $roomId
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getAssistantList($roomId)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/getassistantlist?access_token=ACCESS_TOKEN&roomId={$roomId}";
        return $this->callGetApi($url);
    }

    /**
     * 添加主播副号
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addSubAnchor($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/addsubanchor?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 修改主播副号
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function modifySubAnchor($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/modifysubanchor?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 修删除主播副号
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delSubAnchor($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/deletesubanchor?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 查询除主播副号
     * @param string $roomId
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getSubAnchor($roomId)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/getsubanchor?access_token=ACCESS_TOKEN&roomId={$roomId}";
        return $this->callGetApi($url);
    }

    /**
     * 开启/关闭直播间官方收录
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateFeedPublic($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/updatefeedpublic?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 开启/关闭回放功能
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateReplay($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/updatereplay?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 开启/关闭客服功能
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateKf($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/updatekf?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 开启/关闭直播间全局禁言
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateComment($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/room/updatecomment?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 上下架商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function goodsOnsale($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/onsale?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 删除直播间商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function goodsDeleteInRoom($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/deleteInRoom?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 推送商品
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function goodsPush($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/push?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 商品排序
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function goodsSort($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/sort?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 下载商品讲解视频
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getVideo($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/goods/getVideo?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 获取长期订阅用户
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getFollowers($data)
    {
        $url = "https://api.weixin.qq.com/wxa/business/get_wxa_followers?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 长期订阅群发接口
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function pushMessage($data)
    {
        $url = "https://api.weixin.qq.com/wxa/business/push_message?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 设置成员角色
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addRole($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/role/addrole?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 解除成员角色
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delRole($data)
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/role/deleterole?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 查询成员角色
     * @param int $role
     * @param int $offset
     * @param int $limit
     * @param string $keyword
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getRole($role = -1, $offset = 0, $limit = 30, $keyword = '')
    {
        $url = "https://api.weixin.qq.com/wxaapi/broadcast/role/getrolelist?access_token=ACCESS_TOKEN&offset={$offset}&limit={$limit}&keyword={$keyword}&role={$role}";
        return $this->callGetApi($url);
    }
}