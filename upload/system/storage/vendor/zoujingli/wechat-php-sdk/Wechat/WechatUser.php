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
 * 微信粉丝操作SDK
 *
 * @author Anyon <zoujingli@qq.com>
 * @date 2016/06/28 11:20
 */
class WechatUser extends Common
{

    /** 获取粉丝列表 */
    const USER_GET_URL = '/user/get?';
    /* 获取粉丝信息 */
    const USER_INFO_URL = '/user/info?';
    /* 批量获取粉丝信息 */
    const USER_BATCH_INFO_URL = '/user/info/batchget?';
    /* 更新粉丝标注 */
    const USER_UPDATEREMARK_URL = '/user/info/updateremark?';

    /** 创建标签 */
    const TAGS_CREATE_URL = '/tags/create?';
    /* 获取标签列表 */
    const TAGS_GET_URL = '/tags/get?';
    /* 更新标签 */
    const TAGS_UPDATE_URL = '/tags/update?';
    /* 删除标签 */
    const TAGS_DELETE_URL = '/tags/delete?';
    /* 获取标签下的粉丝列表 */
    const TAGS_GET_USER_URL = '/user/tag/get?';
    /* 批量为粉丝打标签 */
    const TAGS_MEMBER_BATCHTAGGING = '/tags/members/batchtagging?';
    /* 批量为粉丝取消标签 */
    const TAGS_MEMBER_BATCHUNTAGGING = '/tags/members/batchuntagging?';
    /* 获取粉丝的标签列表 */
    const TAGS_LIST = '/tags/getidlist?';

    /** 获取分组列表 */
    const GROUP_GET_URL = '/groups/get?';
    /* 获取粉丝所在的分组 */
    const USER_GROUP_URL = '/groups/getid?';
    /* 创建分组 */
    const GROUP_CREATE_URL = '/groups/create?';
    /* 更新分组 */
    const GROUP_UPDATE_URL = '/groups/update?';
    /* 删除分组 */
    const GROUP_DELETE_URL = '/groups/delete?';
    /* 修改粉丝所在分组 */
    const GROUP_MEMBER_UPDATE_URL = '/groups/members/update?';
    /* 批量修改粉丝所在分组 */
    const GROUP_MEMBER_BATCHUPDATE_URL = '/groups/members/batchupdate?';

    /** 获取黑名单列表 */
    const BACKLIST_GET_URL = '/tags/members/getblacklist?';
    /* 批量拉黑粉丝 */
    const BACKLIST_ADD_URL = '/tags/members/batchblacklist?';
    /* 批量取消拉黑粉丝 */
    const BACKLIST_DEL_URL = '/tags/members/batchunblacklist?';

    /**
     * 批量获取关注粉丝列表
     * @param string $next_openid
     * @return bool|array
     */
    public function getUserList($next_openid = '')
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_URL_PREFIX . self::USER_GET_URL . "access_token={$this->access_token}&next_openid={$next_openid}");
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
     * 获取关注者详细信息
     * @param string $openid
     * @return bool|array {subscribe,openid,nickname,sex,city,province,country,language,headimgurl,subscribe_time,[unionid]}
     * @注意：unionid字段 只有在粉丝将公众号绑定到微信开放平台账号后，才会出现。建议调用前用isset()检测一下
     */
    public function getUserInfo($openid)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_URL_PREFIX . self::USER_INFO_URL . "access_token={$this->access_token}&openid={$openid}");
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
     * 批量获取用户基本信息
     * @param array $openids 用户oepnid列表(最多支持100个openid)
     * @param string $lang 指定返回语言
     * @return bool|mixed
     */
    public function getUserBatchInfo(array $openids, $lang = 'zh_CN')
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('user_list' => array());
        foreach (array_unique($openids) as $openid) {
            $data['user_list'][] = array('openid' => $openid, 'lang' => $lang);
        }
        $result = Tools::httpPost(self::API_URL_PREFIX . self::USER_BATCH_INFO_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode']) || !isset($json['user_info_list'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json['user_info_list'];
        }
        return false;
    }

    /**
     * 设置粉丝备注名
     * @param string $openid
     * @param string $remark 备注名
     * @return bool|array
     */
    public function updateUserRemark($openid, $remark)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid' => $openid, 'remark' => $remark);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::USER_UPDATEREMARK_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 获取粉丝分组列表
     * @return bool|array
     */
    public function getGroup()
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_URL_PREFIX . self::GROUP_GET_URL . "access_token={$this->access_token}");
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
     * 删除粉丝分组
     * @param type $id
     * @return bool
     */
    public function delGroup($id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('group' => array('id' => $id));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::GROUP_DELETE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 获取粉丝所在分组
     * @param string $openid
     * @return bool|int 成功则返回粉丝分组id
     */
    public function getUserGroup($openid)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid' => $openid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::USER_GROUP_URL . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode']) || !isset($json['groupid'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json['groupid'];
        }
        return false;
    }

    /**
     * 新增自定分组
     * @param string $name 分组名称
     * @return bool|array
     */
    public function createGroup($name)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('group' => array('name' => $name));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::GROUP_CREATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 更改分组名称
     * @param int $groupid 分组id
     * @param string $name 分组名称
     * @return bool|array
     */
    public function updateGroup($groupid, $name)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('group' => array('id' => $groupid, 'name' => $name));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::GROUP_UPDATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 移动粉丝分组
     * @param int $groupid 分组id
     * @param string $openid 粉丝openid
     * @return bool|array
     */
    public function updateGroupMembers($groupid, $openid)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid' => $openid, 'to_groupid' => $groupid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::GROUP_MEMBER_UPDATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 批量移动粉丝分组
     * @param string $groupid 分组ID
     * @param string $openid_list 粉丝openid数组(一次不能超过50个)
     * @return bool|array
     */
    public function batchUpdateGroupMembers($groupid, $openid_list)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid_list' => $openid_list, 'to_groupid' => $groupid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::GROUP_MEMBER_BATCHUPDATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 新增自定标签
     * @param string $name 标签名称
     * @return bool|array
     */
    public function createTags($name)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('tag' => array('name' => $name));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_CREATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     *  更新标签
     * @param string $id 标签id
     * @param string $name 标签名称
     * @return bool|array
     */
    public function updateTag($id, $name)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('tag' => array('id' => $id, 'name' => $name));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_UPDATE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 获取粉丝标签列表
     * @return bool|array
     */
    public function getTags()
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $result = Tools::httpGet(self::API_URL_PREFIX . self::TAGS_GET_URL . "access_token={$this->access_token}");
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
     * 删除粉丝标签
     * @param string $id
     * @return bool
     */
    public function delTag($id)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('tag' => array('id' => $id));
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_DELETE_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 获取标签下的粉丝列表
     * @param string $tagid
     * @param string $next_openid
     * @return bool
     */
    public function getTagUsers($tagid, $next_openid = '')
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('tagid' => $tagid, 'next_openid' => $next_openid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_GET_USER_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     *  批量为粉丝打标签
     * @param string $tagid 标签ID
     * @param array $openid_list 粉丝openid数组，一次不能超过50个
     * @return bool|array
     */
    public function batchAddUserTag($tagid, $openid_list)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid_list' => $openid_list, 'tagid' => $tagid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_MEMBER_BATCHTAGGING . "access_token={$this->access_token}", Tools::json_encode($data));
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
     *  批量为粉丝取消标签
     * @param string $tagid 标签ID
     * @param array $openid_list 粉丝openid数组，一次不能超过50个
     * @return bool|array
     */
    public function batchDeleteUserTag($tagid, $openid_list)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid_list' => $openid_list, 'tagid' => $tagid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_MEMBER_BATCHUNTAGGING . "access_token={$this->access_token}", Tools::json_encode($data));
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
     *  获取粉丝的标签列表
     * @param string $openid 粉丝openid
     * @return bool|array
     */
    public function getUserTags($openid)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid' => $openid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::TAGS_LIST . "access_token={$this->access_token}", Tools::json_encode($data));
        if ($result) {
            $json = json_decode($result, true);
            if (empty($json) || !empty($json['errcode']) || !isset($json['tagid_list'])) {
                $this->errCode = isset($json['errcode']) ? $json['errcode'] : '505';
                $this->errMsg = isset($json['errmsg']) ? $json['errmsg'] : '无法解析接口返回内容！';
                return $this->checkRetry(__FUNCTION__, func_get_args());
            }
            return $json['tagid_list'];
        }
        return false;
    }

    /**
     * 批量获取黑名单粉丝
     * @param string $begin_openid
     * @return bool|array
     */
    public function getBacklist($begin_openid = '')
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = empty($begin_openid) ? array() : array('begin_openid' => $begin_openid);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::BACKLIST_GET_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 批量拉黑粉丝
     * @param string $openids
     * @return bool|array
     */
    public function addBacklist($openids)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid_list' => $openids);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::BACKLIST_ADD_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
     * 批量取消拉黑粉丝
     * @param string $openids
     * @return bool|array
     */
    public function delBacklist($openids)
    {
        if (!$this->access_token && !$this->getAccessToken()) {
            return false;
        }
        $data = array('openid_list' => $openids);
        $result = Tools::httpPost(self::API_URL_PREFIX . self::BACKLIST_DEL_URL . "access_token={$this->access_token}", Tools::json_encode($data));
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
