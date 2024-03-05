<?php namespace Todaymade\Daux\Server;

use Todaymade\Daux\Config;
use Todaymade\Daux\Format\HTML\SimplePage;
use Todaymade\Daux\Format\HTML\Template;

class ErrorPage extends SimplePage
{
    public const NORMAL_ERROR_TYPE = 'NORMAL_ERROR';
    public const MISSING_PAGE_ERROR_TYPE = 'MISSING_PAGE_ERROR';
    public const FATAL_ERROR_TYPE = 'FATAL_ERROR';

    private Config $config;

    /**
     * @param string $title
     * @param string $content
     * @param \Todaymade\Daux\Config $config
     */
    public function __construct($title, $content, $config)
    {
        parent::__construct($title, $content);
        $this->config = $config;
    }

    /**
     * @return string
     */
    protected function generatePage()
    {
        $page = [
            'title' => $this->title,
            'content' => $this->getPureContent(),
            'language' => '',
        ];

        $template = new Template($this->config);

        return $template->render('error', ['page' => $page, 'config' => $this->config]);
    }
}
