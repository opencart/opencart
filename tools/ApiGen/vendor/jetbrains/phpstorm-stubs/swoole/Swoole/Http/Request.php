<?php

declare(strict_types=1);

namespace Swoole\Http;

class Request
{
    public $fd = 0;
    public $streamId = 0;
    public $header;
    public $server;
    public $cookie;
    public $get;
    public $files;
    public $post;
    public $tmpfiles;

    public function __destruct() {}

    /**
     * Get the request content, kind of like function call fopen('php://input').
     *
     * This method has an alias of \Swoole\Http\Request::rawContent().
     *
     * @return string|false Return the request content back; return FALSE when error happens.
     * @see \Swoole\Http\Request::rawContent()
     * @since 4.5.0
     */
    public function getContent() {}

    /**
     * Get the request content, kind of like function call fopen('php://input').
     *
     * Alias of method \Swoole\Http\Request::getContent().
     *
     * @return string|false Return the request content back; return FALSE when error happens.
     * @see \Swoole\Http\Request::getContent()
     */
    public function rawContent() {}

    /**
     * @return mixed
     */
    public function getData() {}

    /**
     * @param mixed|null $options
     * @return mixed
     */
    public static function create($options = null) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function parse($data) {}

    /**
     * @return mixed
     */
    public function isCompleted() {}

    /**
     * @return mixed
     */
    public function getMethod() {}
}
