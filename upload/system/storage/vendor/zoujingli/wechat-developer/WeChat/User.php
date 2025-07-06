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

namespace WeChat;

use WeChat\Contracts\BasicWeChat;

/**
 * 微信粉丝管理
 * Class User
 * @package WeChat
 */
class User extends BasicWeChat
{

    /**
     * 设置用户备注名
     * @param string $openid
     * @param string $remark
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function updateMark($openid, $remark)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['openid' => $openid, 'remark' => $remark]);
    }

    /**
     * 获取用户基本信息（包括UnionID机制）
     * @param string $openid
     * @param string $lang
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getUserInfo($openid, $lang = 'zh_CN')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid={$openid}&lang={$lang}";
        return $this->callGetApi($url);
    }

    /**
     * 批量获取用户基本信息
     * @param array $openids
     * @param string $lang
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getBatchUserInfo(array $openids, $lang = 'zh_CN')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=ACCESS_TOKEN';
        $data = ['user_list' => []];
        foreach ($openids as $openid) {
            $data['user_list'][] = ['openid' => $openid, 'lang' => $lang];
        }
        return $this->callPostApi($url, $data);
    }

    /**
     * 获取用户列表
     * @param string $next_openid
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getUserList($next_openid = '')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid={$next_openid}";
        return $this->callGetApi($url);
    }

    /**
     * 获取标签下粉丝列表
     * @param integer $tagid 标签ID
     * @param string $nextOpenid 第一个拉取的OPENID
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getUserListByTag($tagid, $nextOpenid = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['tagid' => $tagid, 'next_openid' => $nextOpenid]);
    }

    /**
     * 获取公众号的黑名单列表
     * @param string $beginOpenid
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getBlackList($beginOpenid = '')
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['begin_openid' => $beginOpenid]);
    }

    /**
     * 批量拉黑用户
     * @param array $openids
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function batchBlackList(array $openids)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['openid_list' => $openids]);
    }

    /**
     * 批量取消拉黑用户
     * @param array $openids
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function batchUnblackList(array $openids)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['openid_list' => $openids]);
    }
}