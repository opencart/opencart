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
 * 公众号小程序模板消息支持
 * Class Mini
 * @package WeChat
 */
class Template extends BasicWeChat
{

    /**
     * 获取小程序模板库标题列表
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getTemplateLibraryList()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/list?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['offset' => '0', 'count' => '20'], true);
    }

    /**
     * 获取模板库某个模板标题下关键词库
     * @param string $template_id 模板标题id，可通过接口获取，也可登录小程序后台查看获取
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getTemplateLibrary($template_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/library/get?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['id' => $template_id], true);
    }

    /**
     * 组合模板并添加至帐号下的个人模板库
     * @param string $template_id 模板标题id，可通过接口获取，也可登录小程序后台查看获取
     * @param array $keyword_id_list 开发者自行组合好的模板关键词列表，关键词顺序可以自由搭配（例如[3,5,4]或[4,5,3]），最多支持10个关键词组合
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addTemplate($template_id, array $keyword_id_list)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/add?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['id' => $template_id, 'keyword_id_list' => $keyword_id_list], true);
    }

    /**
     * 获取帐号下已存在的模板列表
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getTemplateList()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/list?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['offset' => '0', 'count' => '20'], true);
    }

    /**
     * 删除模板消息
     * @param string $template_id 要删除的模板id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delTemplate($template_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxopen/template/del?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, ['template_id' => $template_id], true);
    }

    /**
     * 发送模板消息
     * @param array $data 发送的消息对象数组
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function send(array $data)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=ACCESS_TOKEN';
        return $this->callPostApi($url, $data, true);
    }
}