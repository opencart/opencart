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
 * 发布能力
 * Class Freepublish
 * @author taoxin
 * @package WeChat
 */
class Freepublish extends BasicWeChat
{
    /**
     * 发布接口
     * 开发者需要先将图文素材以草稿的形式保存（见“草稿箱/新建草稿”，如需从已保存的草稿中选择，见“草稿箱/获取草稿列表”）
     * @param mixed $mediaId 选择要发布的草稿的media_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function submit($mediaId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/freepublish/submit?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['media_id' => $mediaId]);
    }

    /**
     * 发布状态轮询接口
     * @param mixed $publishId
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function get($publishId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/freepublish/get?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['publish_id' => $publishId]);
    }

    /**
     * 删除发布
     * 发布成功之后，随时可以通过该接口删除。此操作不可逆，请谨慎操作。
     * @param mixed $articleId 成功发布时返回的 article_id
     * @param int $index 要删除的文章在图文消息中的位置，第一篇编号为1，该字段不填或填0会删除全部文章
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delete($articleId, $index = 0)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/freepublish/delete?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['article_id' => $articleId, 'index' => $index]);
    }

    /**
     * 通过 article_id 获取已发布文章
     * @param mixed $articleId 要获取的草稿的article_id
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getArticle($articleId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/freepublish/getarticle?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['article_id' => $articleId]);
    }

    /**
     * 获取成功发布列表
     * @param int $offset 从全部素材的该偏移位置开始返回，0表示从第一个素材返回
     * @param int $count 返回素材的数量，取值在1到20之间
     * @param int $noContent 1 表示不返回 content 字段，0 表示正常返回，默认为 0
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function batchGet($offset = 0, $count = 20, $noContent = 0)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/freepublish/batchget?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['no_content' => $noContent, 'offset' => $offset, 'count' => $count]);
    }
}