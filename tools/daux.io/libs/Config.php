<?php namespace Todaymade\Daux;

use Todaymade\Daux\Format\Confluence\Config as ConfluenceConfig;
use Todaymade\Daux\Format\HTML\Config as HTMLConfig;
use Todaymade\Daux\Format\HTML\Template;
use Todaymade\Daux\Format\HTML\Theme;
use Todaymade\Daux\Tree\Content;
use Todaymade\Daux\Tree\Entry;

class Config extends BaseConfig
{
    protected Template $templateRenderer;

    public function getTitle()
    {
        return $this->getValue('title');
    }

    public function hasAuthor(): bool
    {
        return $this->hasValue('author');
    }

    public function getAuthor()
    {
        return $this->getValue('author');
    }

    public function hasTagline(): bool
    {
        return $this->hasValue('tagline');
    }

    public function getTagline()
    {
        return $this->getValue('tagline');
    }

    public function getCurrentPage()
    {
        return $this->getValue('current_page');
    }

    public function setCurrentPage(Content $entry)
    {
        $this->setValue('current_page', $entry);
    }

    public function getDocumentationDirectory()
    {
        return $this->getValue('docs_directory');
    }

    public function getThemesDirectory()
    {
        return $this->getValue('themes_directory');
    }

    public function getThemesPath()
    {
        return $this->getValue('themes_path');
    }

    public function getFormat()
    {
        return $this->getValue('format');
    }

    public function hasTimezone(): bool
    {
        return isset($this['timezone']);
    }

    public function getTimezone()
    {
        return $this->getValue('timezone');
    }

    public function getTree()
    {
        return $this->getValue('tree');
    }

    public function setTree($tree)
    {
        $this->setValue('tree', $tree);
    }

    public function isMultilanguage(): bool
    {
        return $this->hasValue('languages') && !empty($this->getValue('languages'));
    }

    public function getLanguages(): array
    {
        return $this->getValue('languages');
    }

    public function getLanguage(): string
    {
        return $this->getValue('language');
    }

    public function getMode()
    {
        return $this->getValue('mode');
    }

    public function isLive()
    {
        return $this->getValue('mode') == Daux::LIVE_MODE;
    }

    public function isStatic()
    {
        return $this->getValue('mode') == Daux::STATIC_MODE;
    }

    public function shouldInheritIndex()
    {
        // As the global configuration is always present, we
        // need to test for the existence of the legacy value
        // first. Then use the current value.
        if ($this->hasValue('live') && array_key_exists('inherit_index', $this['live'])) {
            return $this['live']['inherit_index'];
        }

        return $this['html']['inherit_index'];
    }

    public function getIndexKey()
    {
        return $this->getValue('mode') == Daux::STATIC_MODE ? 'index.html' : 'index';
    }

    public function getProcessor()
    {
        return $this->getValue('processor');
    }

    public function getConfluenceConfiguration(): ConfluenceConfig
    {
        if ($this->hasValue('confluence')) {
            if (!$this->getValue('confluence') instanceof ConfluenceConfig) {
                $this->setValue('confluence', new ConfluenceConfig($this->getValue('confluence')));
            }
        } else {
            $this->setValue('confluence', new ConfluenceConfig([]));
        }

        return $this->getValue('confluence');
    }

    public function getHTML(): HTMLConfig
    {
        return new HTMLConfig($this->hasValue('html') ? $this->getValue('html') : []);
    }

    public function getTheme(): ?Theme
    {
        return $this->hasValue('theme') ? new Theme($this->getValue('theme')) : null;
    }

    public function getValidContentExtensions()
    {
        return $this->getValue('valid_content_extensions');
    }

    public function setValidContentExtensions(array $value)
    {
        $this->setValue('valid_content_extensions', $value);
    }

    public function canCache()
    {
        return $this->getValue('cache', false);
    }

    public function getCacheKey()
    {
        $cloned = [];
        foreach ($this as $key => $value) {
            $cloned[$key] = ($value instanceof Entry) ? $value->dump() : $value;
        }

        return sha1(json_encode($cloned));
    }

    public function hasTranslationKey($language, $key): bool
    {
        return array_key_exists($language, $this['strings']) && array_key_exists($key, $this['strings'][$language]);
    }

    public function getTranslationKey($language, $key)
    {
        return $this['strings'][$language][$key];
    }

    public function hasImage(): bool
    {
        return $this->hasValue('image') && !empty($this->getValue('image'));
    }

    public function getImage()
    {
        return $this->getValue('image');
    }

    public function setImage($value)
    {
        $this->setValue('image', $value);
    }

    public function getLocalBase()
    {
        return $this->getValue('local_base');
    }

    public function getTemplates()
    {
        return $this->getValue('templates');
    }

    public function getBaseUrl()
    {
        return $this->getValue('base_url');
    }

    public function getBasePage()
    {
        if ($this->isLive()) {
            $value = $this->getBaseUrl();
            if (!$this['live']['clean_urls']) {
                $value .= 'index.php/';
            }

            return $value;
        }

        return $this->getBaseUrl();
    }

    public function hasEntryPage(): bool
    {
        return $this->hasValue('entry_page') && !empty($this->getValue('entry_page'));
    }

    public function getEntryPage()
    {
        return $this->getValue('entry_page');
    }

    public function setEntryPage($value)
    {
        $this->setValue('entry_page', $value);
    }

    public function hasRequest(): bool
    {
        return $this->hasValue('request') && !empty($this->getValue('request'));
    }

    public function getRequest()
    {
        return $this->getValue('request');
    }

    public function setRequest($value)
    {
        $this->setValue('request', $value);
    }

    public function getIndex()
    {
        return $this->getValue('index');
    }

    public function setIndex($value)
    {
        $this->setValue('index', $value);
    }

    public function hasProcessorInstance()
    {
        return $this->hasValue('processor_instance');
    }

    public function getProcessorInstance()
    {
        return $this->getValue('processor_instance');
    }

    public function setProcessorInstance($value)
    {
        $this->setValue('processor_instance', $value);
    }

    public function getIgnore()
    {
        return $this->getValue('ignore');
    }

    public function hasHost()
    {
        return $this->hasValue('host');
    }

    public function getHost()
    {
        return $this->getValue('host');
    }

    public function getTemplateRenderer()
    {
        return $this->templateRenderer;
    }

    public function setTemplateRenderer(Template $template)
    {
        $this->templateRenderer = $template;
    }
}
