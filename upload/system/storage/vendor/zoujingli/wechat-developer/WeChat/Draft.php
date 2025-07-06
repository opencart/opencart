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
 * 微信草稿箱管理
 * Class Draft
 * @author taoxin
 * @package WeChat
 */
class Draft extends BasicWeChat
{
    /**
     * 新建草稿
     * @param $articles
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function add($articles)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/add?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['articles' => $articles]);
    }

    /**
     * 获取草稿
     * @param string $mediaId
     * @param string $outType 返回处理函数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function get($mediaId, $outType = null)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/get?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['media_id' => $mediaId]);
    }

    /**
     * 删除草稿
     * @param string $mediaId
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function delete($mediaId)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/delete?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['media_id' => $mediaId]);
    }

    /**
     * 新增图文素材
     * @param array $data 文件名称
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function addNews($data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, $data);
    }

    /**
     * 修改草稿
     * @param string $media_id 要修改的图文消息的id
     * @param int $index 要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
     * @param $articles
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function update($media_id, $index, $articles)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/update?access_token=ACCESS_TOKEN";
        $data = ['media_id' => $media_id, 'index' => $index, 'articles' => $articles];
        return $this->callPostApi($url, $data);
    }

    /**
     * 获取草稿总数
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function getCount()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/count?access_token=ACCESS_TOKEN";
        return $this->callGetApi($url);
    }

    /**
     * 获取草稿列表
     * @param int $offset 从全部素材的该偏移位置开始返回，0表示从第一个素材返回
     * @param int $count 返回素材的数量，取值在1到20之间
     * @param int $noContent 1 表示不返回 content 字段，0 表示正常返回，默认为 0
     * @return array
     * @throws \WeChat\Exceptions\InvalidResponseException
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function batchGet($offset = 0, $count = 20, $noContent = 0)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/draft/batchget?access_token=ACCESS_TOKEN";
        return $this->callPostApi($url, ['no_content' => $noContent, 'offset' => $offset, 'count' => $count]);
    }
}
