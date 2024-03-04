<?php 

class tidy
{
    public function __construct(?string $filename = null, array|string|null $config = null, ?string $encoding = null, bool $useIncludePath = false)
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_getopt
     * @return (string | int | bool)
     */
    public function getOpt(string $option)
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_clean_repair
     * @return bool
     */
    public function cleanRepair()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function parseFile(string $filename, array|string|null $config = null, ?string $encoding = null, bool $useIncludePath = false)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function parseString(string $string, array|string|null $config = null, ?string $encoding = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_repair_string
     * @return (string | false)
     */
    public static function repairString(string $string, array|string|null $config = null, ?string $encoding = null)
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_repair_file
     * @return (string | false)
     */
    public static function repairFile(string $filename, array|string|null $config = null, ?string $encoding = null, bool $useIncludePath = false)
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_diagnose
     * @return bool
     */
    public function diagnose()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_release
     * @return string
     */
    public function getRelease()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_config
     * @return array
     */
    public function getConfig()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_status
     * @return int
     */
    public function getStatus()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_html_ver
     * @return int
     */
    public function getHtmlVer()
    {
    }
    #ifdef HAVE_TIDYOPTGETDOC
    /**
     * @tentative-return-type
     * @alias tidy_get_opt_doc
     * @return (string | false)
     */
    public function getOptDoc(string $option)
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @alias tidy_is_xhtml
     * @return bool
     */
    public function isXhtml()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_is_xml
     * @return bool
     */
    public function isXml()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_root
     * @return (tidyNode | null)
     */
    public function root()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_head
     * @return (tidyNode | null)
     */
    public function head()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_html
     * @return (tidyNode | null)
     */
    public function html()
    {
    }
    /**
     * @tentative-return-type
     * @alias tidy_get_body
     * @return (tidyNode | null)
     */
    public function body()
    {
    }
}