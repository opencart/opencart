<?php
/**
 * Created by IntelliJ IDEA.
 * User: onigoetz
 * Date: 06/11/15
 * Time: 20:27.
 */
namespace Todaymade\Daux\Format\Base;

use Todaymade\Daux\DauxHelper;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Root;

class EmbedImages
{
    protected $tree;

    public function __construct(Root $tree)
    {
        $this->tree = $tree;
    }

    public function embed($page, Content $file, $callback)
    {
        return preg_replace_callback(
            "/<img\\s+[^>]*src=['\"]([^\"]*)['\"][^>]*>/",
            function ($matches) use ($file, $callback) {
                if ($result = $this->findImage($matches[1], $matches[0], $file, $callback)) {
                    return $result;
                }

                return $matches[0];
            },
            $page
        );
    }

    private function getAttributes($tag)
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($tag);

        $img = $dom->getElementsByTagName('img')->item(0);

        $attributes = ['align', 'class', 'title', 'style', 'alt', 'height', 'width'];
        $used = [];
        foreach ($attributes as $attr) {
            if ($img->attributes->getNamedItem($attr)) {
                $used[$attr] = $img->attributes->getNamedItem($attr)->value;
            }
        }

        return $used;
    }

    private function findImage($src, $tag, Content $file, $callback)
    {
        // for protocol relative or http requests : keep the original one
        if (substr($src, 0, strlen('http')) === 'http' || substr($src, 0, strlen('//')) === '//') {
            return $src;
        }

        // Get the path to the file, relative to the root of the documentation
        $url = DauxHelper::getCleanPath(dirname($file->getUrl()) . '/' . $src);

        // Get any file corresponding to the right one
        $file = DauxHelper::getFile($this->tree, $url);

        if ($file === false) {
            return false;
        }

        $result = $callback($src, $this->getAttributes($tag), $file);

        return $result ?: $src;
    }
}
