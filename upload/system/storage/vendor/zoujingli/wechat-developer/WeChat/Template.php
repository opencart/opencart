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
 * 模板消息
 * Class Template
 * @package WeChat
 */
class Template extends BasicWeChat
{
    /**
     * 设置所属行业
     * @param string $industryId1 公众号模板消息所属行业编号
     * @param string $industryId2 公众号模板消息所属行业编号
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function setIndustry($industryId1, $industryId2)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['industry_id1' => $industryId1, 'industry_id2' => $industryId2]);
    }

    /**
     * 获取设置的行业信息
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getIndustry()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token=ACCESS_TOKEN";
        return $this->callGetApi($url);
    }

    /**
     * 获得模板ID
     * @param string $templateIdShort 板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     * @param array $keywordNameList 选用的类目模板的关键词
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function addTemplate($templateIdShort, $keywordNameList = [])
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['template_id_short' => $templateIdShort, 'keyword_name_list' => $keywordNameList]);
    }

    /**
     * 获取模板列表
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function getAllPrivateTemplate()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=ACCESS_TOKEN";
        return $this->callGetApi($url);
    }

    /**
     * 删除模板ID
     * @param string $tplId 公众帐号下模板消息ID
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function delPrivateTemplate($tplId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/template/del_private_template?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['template_id' => $tplId]);
    }

    /**
     * 发送模板消息
     * @param array $data
     * @return array
     * @throws Exceptions\InvalidResponseException
     * @throws Exceptions\LocalCacheException
     */
    public function send(array $data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data);
    }
}