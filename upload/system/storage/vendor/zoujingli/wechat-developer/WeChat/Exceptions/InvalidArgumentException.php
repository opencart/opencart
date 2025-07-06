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

namespace WeChat\Exceptions;

/**
 * 接口参数异常
 * Class InvalidArgumentException
 * @package WeChat
 */
class InvalidArgumentException extends \InvalidArgumentException
{
    /**
     * @var array
     */
    public $raw = [];

    /**
     * InvalidArgumentException constructor.
     * @param string $message
     * @param integer $code
     * @param array $raw
     */
    public function __construct($message, $code = 0, $raw = [])
    {
        parent::__construct($message, intval($code));
        $this->raw = $raw;
    }
}