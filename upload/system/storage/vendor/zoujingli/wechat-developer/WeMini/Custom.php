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
 * 小程序客服服务
 * Class Custom
 * @package WeMini
 */
class Custom extends BasicWeChat
{

    /**
     * 创建商户
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessRegister($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/business/register?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 更新商户信息
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessUpdate($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/business/update?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 拉取单个商户信息
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessGet($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/business/get?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 拉取多个商户信息
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessList($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/business/list?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 发送客服消息
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessSend($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/message/custom/business/send?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }

    /**
     * 客服输入状态
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function businessTyping($data)
    {
        $url = 'https://api.weixin.qq.com/cgi‐bin/message/custom/business/typing?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 获取客服基本信息
     * @param string $business_id 客服子商户的business_id，对于普通小程序客服不需要填business_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getKfList($business_id = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN';
        if (!empty($business_id)) {
            $url .= '&business_id=' . $business_id;
        }
        return $this->callGetApi($url);
    }


    /**
     * 获取在线客服列表
     * @param string $business_id 客服子商户的business_id，对于普通小程序客服不需要填business_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getOnlineKfList($business_id = '')
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token=ACCESS_TOKEN';
        if (!empty($business_id)) {
            $url .= '&business_id=' . $business_id;
        }
        return $this->callGetApi($url);
    }


    /**
     * 客服输入状态
     * @param array $data
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addKfAccount($data)
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }


    /**
     * 删除客服账号
     * @param string $kf_openid 客服openid
     * @param string $business_id 客服子商户的business_id，对于普通小程序客服不需要填business_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delKfAccount($kf_openid, $business_id = '')
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/del?access_token=ACCESS_TOKEN&kf_openid=' . $kf_openid;
        if (!empty($business_id)) {
            $url .= '&business_id=' . $business_id;
        }
        return $this->callGetApi($url);
    }

    /**
     * 设置客服管理员
     * @param string $kf_openid 客服openid
     * @param string $business_id 客服子商户的business_id，对于普通小程序客服不需要填business_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function setKfAdmin($kf_openid, $business_id = '')
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/setadmin?access_token=ACCESS_TOKEN&kf_openid=' . $kf_openid;
        if (!empty($business_id)) {
            $url .= '&business_id=' . $business_id;
        }
        return $this->callGetApi($url);
    }


    /**
     * 取消客服管理员
     * @param string $kf_openid 客服openid
     * @param string $business_id 客服子商户的business_id，对于普通小程序客服不需要填business_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function cancelKfAdmin($kf_openid, $business_id = '')
    {
        $url = 'https://api.weixin.qq.com/customservice/kfaccount/canceladmin?access_token=ACCESS_TOKEN&kf_openid=' . $kf_openid;
        if (!empty($business_id)) {
            $url .= '&business_id=' . $business_id;
        }
        return $this->callGetApi($url);
    }
}