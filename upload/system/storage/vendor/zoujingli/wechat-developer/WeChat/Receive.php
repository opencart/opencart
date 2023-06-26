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

use WeChat\Contracts\BasicPushEvent;

/**
 * 公众号推送管理
 * Class Receive
 * @package WeChat
 */
class Receive extends BasicPushEvent
{

    /**
     * 转发多客服消息
     * @param string $account
     * @return $this
     */
    public function transferCustomerService($account = '')
    {
        $this->message = [
            'CreateTime'   => time(),
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'MsgType'      => 'transfer_customer_service',
        ];
        empty($account) || $this->message['TransInfo'] = ['KfAccount' => $account];
        return $this;
    }

    /**
     * 设置文本消息
     * @param string $content 文本内容
     * @return $this
     */
    public function text($content = '')
    {
        $this->message = [
            'MsgType'      => 'text',
            'CreateTime'   => time(),
            'Content'      => $content,
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
        ];
        return $this;
    }

    /**
     * 设置回复图文
     * @param array $newsData
     * @return $this
     */
    public function news($newsData = [])
    {
        $this->message = [
            'CreateTime'   => time(),
            'MsgType'      => 'news',
            'Articles'     => $newsData,
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'ArticleCount' => count($newsData),
        ];
        return $this;
    }

    /**
     * 设置图片消息
     * @param string $mediaId 图片媒体ID
     * @return $this
     */
    public function image($mediaId = '')
    {
        $this->message = [
            'MsgType'      => 'image',
            'CreateTime'   => time(),
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'Image'        => ['MediaId' => $mediaId],
        ];
        return $this;
    }

    /**
     * 设置语音回复消息
     * @param string $mediaid 语音媒体ID
     * @return $this
     */
    public function voice($mediaid = '')
    {
        $this->message = [
            'CreateTime'   => time(),
            'MsgType'      => 'voice',
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'Voice'        => ['MediaId' => $mediaid],
        ];
        return $this;
    }

    /**
     * 设置视频回复消息
     * @param string $mediaid 视频媒体ID
     * @param string $title 视频标题
     * @param string $description 视频描述
     * @return $this
     */
    public function video($mediaid = '', $title = '', $description = '')
    {
        $this->message = [
            'CreateTime'   => time(),
            'MsgType'      => 'video',
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'Video'        => [
                'Title'       => $title,
                'MediaId'     => $mediaid,
                'Description' => $description,
            ],
        ];
        return $this;
    }

    /**
     * 设置音乐回复消息
     * @param string $title 音乐标题
     * @param string $desc 音乐描述
     * @param string $musicurl 音乐地址
     * @param string $hgmusicurl 高清音乐地址
     * @param string $thumbmediaid 音乐图片缩略图的媒体id（可选）
     * @return $this
     */
    public function music($title, $desc, $musicurl, $hgmusicurl = '', $thumbmediaid = '')
    {
        $this->message = [
            'CreateTime'   => time(),
            'MsgType'      => 'music',
            'ToUserName'   => $this->getOpenid(),
            'FromUserName' => $this->getToOpenid(),
            'Music'        => [
                'Title'       => $title,
                'Description' => $desc,
                'MusicUrl'    => $musicurl,
                'HQMusicUrl'  => $hgmusicurl,
            ],
        ];
        if ($thumbmediaid) {
            $this->message['Music']['ThumbMediaId'] = $thumbmediaid;
        }
        return $this;
    }
}