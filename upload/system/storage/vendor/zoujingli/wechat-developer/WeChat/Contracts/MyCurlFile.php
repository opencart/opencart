<?php

namespace WeChat\Contracts;

/**
 * 自定义CURL文件类
 * Class MyCurlFile
 * @package WeChat\Contracts
 */
class MyCurlFile extends \stdClass
{
    /**
     * 当前数据类型
     * @var string
     */
    public $datatype = 'MY_CURL_FILE';

    /**
     * MyCurlFile constructor.
     * @param string|array $filename
     * @param string $mimetype
     * @param string $postname
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function __construct($filename, $mimetype = '', $postname = '')
    {
        if (is_array($filename)) {
            foreach ($filename as $k => $v) $this->{$k} = $v;
        } else {
            $this->mimetype = $mimetype;
            $this->postname = $postname;
            $this->extension = pathinfo($filename, PATHINFO_EXTENSION);
            if (empty($this->extension)) $this->extension = 'tmp';
            if (empty($this->mimetype)) $this->mimetype = Tools::getExtMine($this->extension);
            if (empty($this->postname)) $this->postname = pathinfo($filename, PATHINFO_BASENAME);
            $this->content = base64_encode(file_get_contents($filename));
            $this->tempname = md5($this->content) . ".{$this->extension}";
        }
    }

    /**
     * 获取文件信息
     * @return \CURLFile|string
     * @throws \WeChat\Exceptions\LocalCacheException
     */
    public function get()
    {
        $this->filename = Tools::pushFile($this->tempname, base64_decode($this->content));
        if (class_exists('CURLFile')) {
            return new \CURLFile($this->filename, $this->mimetype, $this->postname);
        }
        return "@{$this->tempname};filename={$this->postname};type={$this->mimetype}";
    }

    /**
     * 类销毁处理
     */
    public function __destruct()
    {
        // Tools::delCache($this->tempname);
    }

}