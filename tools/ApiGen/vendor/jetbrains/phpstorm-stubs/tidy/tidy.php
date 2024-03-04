<?php

// Start of tidy v.2.0

/**
 * An HTML node in an HTML file, as detected by tidy.
 * @link https://php.net/manual/en/class.tidy.php
 */
class tidy
{
    /**
     * @var string  The last warnings and errors from TidyLib
     */
    public $errorBuffer;

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Returns the value of the specified configuration option for the tidy document
     * @link https://php.net/manual/en/tidy.getopt.php
     * @param string $option <p>
     * You will find a list with each configuration option and their types
     * at: http://tidy.sourceforge.net/docs/quickref.html.
     * </p>
     * @return mixed the value of the specified <i>option</i>.
     * The return type depends on the type of the specified one.
     */
    public function getOpt($option) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Execute configured cleanup and repair operations on parsed markup
     * @link https://php.net/manual/en/tidy.cleanrepair.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function cleanRepair() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Parse markup in file or URI
     * @link https://php.net/manual/en/tidy.parsefile.php
     * @param string $filename <p>
     * If the <i>filename</i> parameter is given, this function
     * will also read that file and initialize the object with the file,
     * acting like <b>tidy_parse_file</b>.
     * </p>
     * @param mixed $config [optional] <p>
     * The config <i>config</i> can be passed either as an
     * array or as a string. If a string is passed, it is interpreted as the
     * name of the configuration file, otherwise, it is interpreted as the
     * options themselves.
     * </p>
     * <p>
     * For an explanation about each option, see
     * http://tidy.sourceforge.net/docs/quickref.html.
     * </p>
     * @param string|null $encoding [optional] <p>
     * The <i>encoding</i> parameter sets the encoding for
     * input/output documents. The possible values for encoding are:
     * ascii, latin0, latin1,
     * raw, utf8, iso2022,
     * mac, win1252, ibm858,
     * utf16, utf16le, utf16be,
     * big5, and shiftjis.
     * </p>
     * @param bool $use_include_path [optional] <p>
     * Search for the file in the include_path.
     * </p>
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function parseFile($filename, $config = null, $encoding = null, $use_include_path = false) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Parse a document stored in a string
     * @link https://php.net/manual/en/tidy.parsestring.php
     * @param string $input <p>
     * The data to be parsed.
     * </p>
     * @param mixed $config [optional] <p>
     * The config <i>config</i> can be passed either as an
     * array or as a string. If a string is passed, it is interpreted as the
     * name of the configuration file, otherwise, it is interpreted as the
     * options themselves.
     * </p>
     * <p>
     * For an explanation about each option, visit http://tidy.sourceforge.net/docs/quickref.html.
     * </p>
     * @param string|null $encoding [optional] <p>
     * The <i>encoding</i> parameter sets the encoding for
     * input/output documents. The possible values for encoding are:
     * ascii, latin0, latin1,
     * raw, utf8, iso2022,
     * mac, win1252, ibm858,
     * utf16, utf16le, utf16be,
     * big5, and shiftjis.
     * </p>
     * @return bool a new <b>tidy</b> instance.
     */
    public function parseString($input, $config = null, $encoding = null) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
     * Repair a string using an optionally provided configuration file
     * @link https://php.net/manual/en/tidy.repairstring.php
     * @param string $data <p>
     * The data to be repaired.
     * </p>
     * @param mixed $config [optional] <p>
     * The config <i>config</i> can be passed either as an
     * array or as a string. If a string is passed, it is interpreted as the
     * name of the configuration file, otherwise, it is interpreted as the
     * options themselves.
     * </p>
     * <p>
     * Check http://tidy.sourceforge.net/docs/quickref.html for
     * an explanation about each option.
     * </p>
     * @param string|null $encoding [optional] <p>
     * The <i>encoding</i> parameter sets the encoding for
     * input/output documents. The possible values for encoding are:
     * ascii, latin0, latin1,
     * raw, utf8, iso2022,
     * mac, win1252, ibm858,
     * utf16, utf16le, utf16be,
     * big5, and shiftjis.
     * </p>
     * @return string the repaired string.
     */
    public function repairString($data, $config = null, $encoding = null) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
     * Repair a file and return it as a string
     * @link https://php.net/manual/en/tidy.repairfile.php
     * @param string $filename <p>
     * The file to be repaired.
     * </p>
     * @param mixed $config [optional] <p>
     * The config <i>config</i> can be passed either as an
     * array or as a string. If a string is passed, it is interpreted as the
     * name of the configuration file, otherwise, it is interpreted as the
     * options themselves.
     * </p>
     * <p>
     * Check http://tidy.sourceforge.net/docs/quickref.html for an
     * explanation about each option.
     * </p>
     * @param string|null $encoding [optional] <p>
     * The <i>encoding</i> parameter sets the encoding for
     * input/output documents. The possible values for encoding are:
     * ascii, latin0, latin1,
     * raw, utf8, iso2022,
     * mac, win1252, ibm858,
     * utf16, utf16le, utf16be,
     * big5, and shiftjis.
     * </p>
     * @param bool $use_include_path [optional] <p>
     * Search for the file in the include_path.
     * </p>
     * @return string the repaired contents as a string.
     */
    public function repairFile($filename, $config = null, $encoding = null, $use_include_path = false) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Run configured diagnostics on parsed and repaired markup
     * @link https://php.net/manual/en/tidy.diagnose.php
     * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
     */
    public function diagnose() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Get release date (version) for Tidy library
     * @link https://php.net/manual/en/tidy.getrelease.php
     * @return string a string with the release date of the Tidy library.
     */
    public function getRelease() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
     * Get current Tidy configuration
     * @link https://php.net/manual/en/tidy.getconfig.php
     * @return array an array of configuration options.
     * </p>
     * <p>
     * For an explanation about each option, visit http://tidy.sourceforge.net/docs/quickref.html.
     */
    public function getConfig() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Get status of specified document
     * @link https://php.net/manual/en/tidy.getstatus.php
     * @return int 0 if no error/warning was raised, 1 for warnings or accessibility
     * errors, or 2 for errors.
     */
    public function getStatus() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Get the Detected HTML version for the specified document
     * @link https://php.net/manual/en/tidy.gethtmlver.php
     * @return int the detected HTML version.
     * </p>
     * <p>
     * This function is not yet implemented in the Tidylib itself, so it always
     * return 0.
     */
    public function getHtmlVer() {}

    /**
     * Returns the documentation for the given option name
     * @link https://php.net/manual/en/tidy.getoptdoc.php
     * @param string $optname <p>
     * The option name
     * </p>
     * @return string a string if the option exists and has documentation available, or
     * <b>FALSE</b> otherwise.
     */
    public function getOptDoc($optname) {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Indicates if the document is a XHTML document
     * @link https://php.net/manual/en/tidy.isxhtml.php
     * @return bool This function returns <b>TRUE</b> if the specified tidy
     * <i>object</i> is a XHTML document, or <b>FALSE</b> otherwise.
     * </p>
     * <p>
     * This function is not yet implemented in the Tidylib itself, so it always
     * return <b>FALSE</b>.
     */
    public function isXhtml() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Indicates if the document is a generic (non HTML/XHTML) XML document
     * @link https://php.net/manual/en/tidy.isxml.php
     * @return bool This function returns <b>TRUE</b> if the specified tidy
     * <i>object</i> is a generic XML document (non HTML/XHTML),
     * or <b>FALSE</b> otherwise.
     * </p>
     * <p>
     * This function is not yet implemented in the Tidylib itself, so it always
     * return <b>FALSE</b>.
     */
    public function isXml() {}

    /**
     * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
     * Returns a <b>tidyNode</b> object representing the root of the tidy parse tree
     * @link https://php.net/manual/en/tidy.root.php
     * @return tidyNode the <b>tidyNode</b> object.
     */
    public function root() {}

    /**
     * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
     * Returns a <b>tidyNode</b> object starting from the &lt;head&gt; tag of the tidy parse tree
     * @link https://php.net/manual/en/tidy.head.php
     * @return tidyNode the <b>tidyNode</b> object.
     */
    public function head() {}

    /**
     * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
     * Returns a <b>tidyNode</b> object starting from the &lt;html&gt; tag of the tidy parse tree
     * @link https://php.net/manual/en/tidy.html.php
     * @return tidyNode the <b>tidyNode</b> object.
     */
    public function html() {}

    /**
     * (PHP 5, PECL tidy 0.5.2-1.0)<br/>
     * Returns a <b>tidyNode</b> object starting from the &lt;body&gt; tag of the tidy parse tree
     * @link https://php.net/manual/en/tidy.body.php
     * @return tidyNode a <b>tidyNode</b> object starting from the
     * &lt;body&gt; tag of the tidy parse tree.
     */
    public function body() {}

    /**
     * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
     * Constructs a new <b>tidy</b> object
     * @link https://php.net/manual/en/tidy.construct.php
     * @param string $filename [optional] <p>
     * If the <i>filename</i> parameter is given, this function
     * will also read that file and initialize the object with the file,
     * acting like <b>tidy_parse_file</b>.
     * </p>
     * @param mixed $config [optional] <p>
     * The config <i>config</i> can be passed either as an
     * array or as a string. If a string is passed, it is interpreted as the
     * name of the configuration file, otherwise, it is interpreted as the
     * options themselves.
     * </p>
     * <p>
     * For an explanation about each option, visit http://tidy.sourceforge.net/docs/quickref.html.
     * </p>
     * @param string|null $encoding [optional] <p>
     * The <i>encoding</i> parameter sets the encoding for
     * input/output documents. The possible values for encoding are:
     * ascii, latin0, latin1,
     * raw, utf8, iso2022,
     * mac, win1252, ibm858,
     * utf16, utf16le, utf16be,
     * big5, and shiftjis.
     * </p>
     * @param bool $use_include_path [optional] <p>
     * Search for the file in the include_path.
     * </p>
     */
    public function __construct($filename = null, $config = null, $encoding = null, $use_include_path = null) {}
}

/**
 * An HTML node in an HTML file, as detected by tidy.
 * @link https://php.net/manual/en/class.tidynode.php
 */
final class tidyNode
{
    /**
     * <p style="margin-top:0;">The HTML representation of the node, including the surrounding tags.</p>
     * @var string
     */
    public $value;

    /**
     * <p style="margin-top:0;">The name of the HTML node</p>
     * @var string
     */
    public $name;

    /**
     * <p style="margin-top:0;">The type of the tag (one of the constants above, e.g. <b><code>TIDY_NODETYPE_PHP</code></b>)</p>
     * @var int
     */
    public $type;

    /**
     * <p style="margin-top:0;">The line number at which the tags is located in the file</p>
     * @var int
     */
    public $line;

    /**
     * <p style="margin-top:0;">The column number at which the tags is located in the file</p>
     * @var int
     */
    public $column;

    /**
     * <p style="margin-top:0;">Indicates if the node is a proprietary tag</p>
     * @var bool
     */
    public $proprietary;

    /**
     * <p style="margin-top:0;">The ID of the tag (one of the constants above, e.g. <b><code>TIDY_TAG_FRAME</code></b>)</p>
     * @var int
     */
    public $id;

    /**
     * <p style="margin-top:0;">
     * An array of string, representing
     * the attributes names (as keys) of the current node.
     * </p>
     * @var array
     */
    public $attribute;

    /**
     * <p style="margin-top:0;">
     * An array of <b>tidyNode</b>, representing
     * the children of the current node.
     * </p>
     * @var array
     */
    public $child;

    /**
     * Checks if a node has children
     * @link https://php.net/manual/en/tidynode.haschildren.php
     * @return bool <b>TRUE</b> if the node has children, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function hasChildren() {}

    /**
     * Checks if a node has siblings
     * @link https://php.net/manual/en/tidynode.hassiblings.php
     * @return bool <b>TRUE</b> if the node has siblings, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function hasSiblings() {}

    /**
     * Checks if a node represents a comment
     * @link https://php.net/manual/en/tidynode.iscomment.php
     * @return bool <b>TRUE</b> if the node is a comment, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isComment() {}

    /**
     * Checks if a node is part of a HTML document
     * @link https://php.net/manual/en/tidynode.ishtml.php
     * @return bool <b>TRUE</b> if the node is part of a HTML document, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isHtml() {}

    /**
     * Checks if a node represents text (no markup)
     * @link https://php.net/manual/en/tidynode.istext.php
     * @return bool <b>TRUE</b> if the node represent a text, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isText() {}

    /**
     * Checks if this node is JSTE
     * @link https://php.net/manual/en/tidynode.isjste.php
     * @return bool <b>TRUE</b> if the node is JSTE, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isJste() {}

    /**
     * Checks if this node is ASP
     * @link https://php.net/manual/en/tidynode.isasp.php
     * @return bool <b>TRUE</b> if the node is ASP, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isAsp() {}

    /**
     * Checks if a node is PHP
     * @link https://php.net/manual/en/tidynode.isphp.php
     * @return bool <b>TRUE</b> if the current node is PHP code, <b>FALSE</b> otherwise.
     * @since 5.0.1
     */
    public function isPhp() {}

    /**
     * Returns the parent node of the current node
     * @link https://php.net/manual/en/tidynode.getparent.php
     * @return tidyNode a tidyNode if the node has a parent, or <b>NULL</b>
     * otherwise.
     * @since 5.2.2
     */
    public function getParent() {}

    private function __construct() {}
}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Returns the value of the specified configuration option for the tidy document
 * @link https://php.net/manual/en/tidy.getopt.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @param string $option <p>
 * You will find a list with each configuration option and their types
 * at: http://tidy.sourceforge.net/docs/quickref.html.
 * </p>
 * @return mixed the value of the specified <i>option</i>.
 * The return type depends on the type of the specified one.
 */
function tidy_getopt(tidy $object, $option) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Parse a document stored in a string
 * @link https://php.net/manual/en/tidy.parsestring.php
 * @param string $input <p>
 * The data to be parsed.
 * </p>
 * @param mixed $config [optional] <p>
 * The config <i>config</i> can be passed either as an
 * array or as a string. If a string is passed, it is interpreted as the
 * name of the configuration file, otherwise, it is interpreted as the
 * options themselves.
 * </p>
 * <p>
 * For an explanation about each option, visit http://tidy.sourceforge.net/docs/quickref.html.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The <i>encoding</i> parameter sets the encoding for
 * input/output documents. The possible values for encoding are:
 * ascii, latin0, latin1,
 * raw, utf8, iso2022,
 * mac, win1252, ibm858,
 * utf16, utf16le, utf16be,
 * big5, and shiftjis.
 * </p>
 * @return tidy a new <b>tidy</b> instance.
 */
function tidy_parse_string($input, $config = null, $encoding = null) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Parse markup in file or URI
 * @link https://php.net/manual/en/tidy.parsefile.php
 * @param string $filename <p>
 * If the <i>filename</i> parameter is given, this function
 * will also read that file and initialize the object with the file,
 * acting like <b>tidy_parse_file</b>.
 * </p>
 * @param mixed $config [optional] <p>
 * The config <i>config</i> can be passed either as an
 * array or as a string. If a string is passed, it is interpreted as the
 * name of the configuration file, otherwise, it is interpreted as the
 * options themselves.
 * </p>
 * <p>
 * For an explanation about each option, see
 * http://tidy.sourceforge.net/docs/quickref.html.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The <i>encoding</i> parameter sets the encoding for
 * input/output documents. The possible values for encoding are:
 * ascii, latin0, latin1,
 * raw, utf8, iso2022,
 * mac, win1252, ibm858,
 * utf16, utf16le, utf16be,
 * big5, and shiftjis.
 * </p>
 * @param bool $use_include_path [optional] <p>
 * Search for the file in the include_path.
 * </p>
 * @return tidy a new <b>tidy</b> instance.
 */
function tidy_parse_file($filename, $config = null, $encoding = null, $use_include_path = false) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Return a string representing the parsed tidy markup
 * @link https://php.net/manual/en/function.tidy-get-output.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return string the parsed tidy markup.
 */
function tidy_get_output(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Return warnings and errors which occurred parsing the specified document
 * @link https://php.net/manual/en/tidy.props.errorbuffer.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return mixed the error buffer as a string.
 */
function tidy_get_error_buffer(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Execute configured cleanup and repair operations on parsed markup
 * @link https://php.net/manual/en/tidy.cleanrepair.php
 * @param tidy $object The Tidy object.
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function tidy_clean_repair(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
 * Repair a string using an optionally provided configuration file
 * @link https://php.net/manual/en/tidy.repairstring.php
 * @param string $data <p>
 * The data to be repaired.
 * </p>
 * @param mixed $config [optional] <p>
 * The config <i>config</i> can be passed either as an
 * array or as a string. If a string is passed, it is interpreted as the
 * name of the configuration file, otherwise, it is interpreted as the
 * options themselves.
 * </p>
 * <p>
 * Check http://tidy.sourceforge.net/docs/quickref.html for
 * an explanation about each option.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The <i>encoding</i> parameter sets the encoding for
 * input/output documents. The possible values for encoding are:
 * ascii, latin0, latin1,
 * raw, utf8, iso2022,
 * mac, win1252, ibm858,
 * utf16, utf16le, utf16be,
 * big5, and shiftjis.
 * </p>
 * @return string the repaired string.
 */
function tidy_repair_string($data, $config = null, $encoding = null) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
 * Repair a file and return it as a string
 * @link https://php.net/manual/en/tidy.repairfile.php
 * @param string $filename <p>
 * The file to be repaired.
 * </p>
 * @param mixed $config [optional] <p>
 * The config <i>config</i> can be passed either as an
 * array or as a string. If a string is passed, it is interpreted as the
 * name of the configuration file, otherwise, it is interpreted as the
 * options themselves.
 * </p>
 * <p>
 * Check http://tidy.sourceforge.net/docs/quickref.html for an
 * explanation about each option.
 * </p>
 * @param string|null $encoding [optional] <p>
 * The <i>encoding</i> parameter sets the encoding for
 * input/output documents. The possible values for encoding are:
 * ascii, latin0, latin1,
 * raw, utf8, iso2022,
 * mac, win1252, ibm858,
 * utf16, utf16le, utf16be,
 * big5, and shiftjis.
 * </p>
 * @param bool $use_include_path [optional] <p>
 * Search for the file in the include_path.
 * </p>
 * @return string the repaired contents as a string.
 */
function tidy_repair_file($filename, $config = null, $encoding = null, $use_include_path = false) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Run configured diagnostics on parsed and repaired markup
 * @link https://php.net/manual/en/tidy.diagnose.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function tidy_diagnose(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Get release date (version) for Tidy library
 * @link https://php.net/manual/en/tidy.getrelease.php
 * @return string a string with the release date of the Tidy library.
 */
function tidy_get_release() {}

/**
 * (PHP 5, PECL tidy &gt;= 0.7.0)<br/>
 * Get current Tidy configuration
 * @link https://php.net/manual/en/tidy.getconfig.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return array an array of configuration options.
 * <p>
 * For an explanation about each option, visit http://tidy.sourceforge.net/docs/quickref.html.
 * </p>
 */
function tidy_get_config(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Get status of specified document
 * @link https://php.net/manual/en/tidy.getstatus.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int 0 if no error/warning was raised, 1 for warnings or accessibility
 * errors, or 2 for errors.
 */
function tidy_get_status(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Get the Detected HTML version for the specified document
 * @link https://php.net/manual/en/tidy.gethtmlver.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int the detected HTML version.
 * <p>
 * This function is not yet implemented in the Tidylib itself, so it always
 * return 0.
 * </p>
 */
function tidy_get_html_ver(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Indicates if the document is a XHTML document
 * @link https://php.net/manual/en/tidy.isxhtml.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return bool This function returns <b>TRUE</b> if the specified tidy
 * <i>object</i> is a XHTML document, or <b>FALSE</b> otherwise.
 * </p>
 * <p>
 * This function is not yet implemented in the Tidylib itself, so it always
 * return <b>FALSE</b>.
 */
function tidy_is_xhtml(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Indicates if the document is a generic (non HTML/XHTML) XML document
 * @link https://php.net/manual/en/tidy.isxml.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return bool This function returns <b>TRUE</b> if the specified tidy
 * <i>object</i> is a generic XML document (non HTML/XHTML),
 * or <b>FALSE</b> otherwise.
 * </p>
 * <p>
 * This function is not yet implemented in the Tidylib itself, so it always
 * return <b>FALSE</b>.
 */
function tidy_is_xml(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Returns the Number of Tidy errors encountered for specified document
 * @link https://php.net/manual/en/function.tidy-error-count.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int the number of errors.
 */
function tidy_error_count(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Returns the Number of Tidy warnings encountered for specified document
 * @link https://php.net/manual/en/function.tidy-warning-count.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int the number of warnings.
 */
function tidy_warning_count(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Returns the Number of Tidy accessibility warnings encountered for specified document
 * @link https://php.net/manual/en/function.tidy-access-count.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int the number of warnings.
 */
function tidy_access_count(tidy $object) {}

/**
 * (PHP 5, PECL tidy &gt;= 0.5.2)<br/>
 * Returns the Number of Tidy configuration errors encountered for specified document
 * @link https://php.net/manual/en/function.tidy-config-count.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return int the number of errors.
 */
function tidy_config_count(tidy $object) {}

/**
 * Returns the documentation for the given option name
 * @link https://php.net/manual/en/tidy.getoptdoc.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @param string $optname <p>
 * The option name
 * </p>
 * @return string a string if the option exists and has documentation available, or
 * <b>FALSE</b> otherwise.
 */
function tidy_get_opt_doc(tidy $object, $optname) {}

/**
 * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
 * Returns a <b>tidyNode</b> object representing the root of the tidy parse tree
 * @link https://php.net/manual/en/tidy.root.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return tidyNode the <b>tidyNode</b> object.
 */
function tidy_get_root(tidy $object) {}

/**
 * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
 * Returns a <b>tidyNode</b> object starting from the &lt;head&gt; tag of the tidy parse tree
 * @link https://php.net/manual/en/tidy.head.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return tidyNode the <b>tidyNode</b> object.
 */
function tidy_get_head(tidy $object) {}

/**
 * (PHP 5, PECL tidy 0.5.2-1.0.0)<br/>
 * Returns a <b>tidyNode</b> object starting from the &lt;html&gt; tag of the tidy parse tree
 * @link https://php.net/manual/en/tidy.html.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return tidyNode the <b>tidyNode</b> object.
 */
function tidy_get_html(tidy $object) {}

/**
 * (PHP 5, PECL tidy 0.5.2-1.0)<br/>
 * Returns a <b>tidyNode</b> object starting from the &lt;body&gt; tag of the tidy parse tree
 * @link https://php.net/manual/en/tidy.body.php
 * @param tidy $object <p>
 * The <b>Tidy</b> object.
 * </p>
 * @return tidyNode a <b>tidyNode</b> object starting from the
 * &lt;body&gt; tag of the tidy parse tree.
 */
function tidy_get_body(tidy $object) {}

/**
 * ob_start callback function to repair the buffer
 * @link https://php.net/manual/en/function.ob-tidyhandler.php
 * @param string $input <p>
 * The buffer.
 * </p>
 * @param int $mode [optional] <p>
 * The buffer mode.
 * </p>
 * @return string the modified buffer.
 */
function ob_tidyhandler($input, $mode = null) {}

/**
 * description
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_TAG_UNKNOWN', 0);
define('TIDY_TAG_A', 1);
define('TIDY_TAG_ABBR', 2);
define('TIDY_TAG_ACRONYM', 3);
define('TIDY_TAG_ADDRESS', 4);
define('TIDY_TAG_ALIGN', 5);
define('TIDY_TAG_APPLET', 6);
define('TIDY_TAG_AREA', 7);
define('TIDY_TAG_B', 8);
define('TIDY_TAG_BASE', 9);
define('TIDY_TAG_BASEFONT', 10);
define('TIDY_TAG_BDO', 11);
define('TIDY_TAG_BGSOUND', 12);
define('TIDY_TAG_BIG', 13);
define('TIDY_TAG_BLINK', 14);
define('TIDY_TAG_BLOCKQUOTE', 15);
define('TIDY_TAG_BODY', 16);
define('TIDY_TAG_BR', 17);
define('TIDY_TAG_BUTTON', 18);
define('TIDY_TAG_CAPTION', 19);
define('TIDY_TAG_CENTER', 20);
define('TIDY_TAG_CITE', 21);
define('TIDY_TAG_CODE', 22);
define('TIDY_TAG_COL', 23);
define('TIDY_TAG_COLGROUP', 24);
define('TIDY_TAG_COMMENT', 25);
define('TIDY_TAG_DD', 26);
define('TIDY_TAG_DEL', 27);
define('TIDY_TAG_DFN', 28);
define('TIDY_TAG_DIR', 29);
define('TIDY_TAG_DIV', 30);
define('TIDY_TAG_DL', 31);
define('TIDY_TAG_DT', 32);
define('TIDY_TAG_EM', 33);
define('TIDY_TAG_EMBED', 34);
define('TIDY_TAG_FIELDSET', 35);
define('TIDY_TAG_FONT', 36);
define('TIDY_TAG_FORM', 37);
define('TIDY_TAG_FRAME', 38);
define('TIDY_TAG_FRAMESET', 39);
define('TIDY_TAG_H1', 40);
define('TIDY_TAG_H2', 41);
define('TIDY_TAG_H3', 42);
define('TIDY_TAG_H4', 43);
define('TIDY_TAG_H5', 44);
define('TIDY_TAG_H6', 45);
define('TIDY_TAG_HEAD', 46);
define('TIDY_TAG_HR', 47);
define('TIDY_TAG_HTML', 48);
define('TIDY_TAG_I', 49);
define('TIDY_TAG_IFRAME', 50);
define('TIDY_TAG_ILAYER', 51);
define('TIDY_TAG_IMG', 52);
define('TIDY_TAG_INPUT', 53);
define('TIDY_TAG_INS', 54);
define('TIDY_TAG_ISINDEX', 55);
define('TIDY_TAG_KBD', 56);
define('TIDY_TAG_KEYGEN', 57);
define('TIDY_TAG_LABEL', 58);
define('TIDY_TAG_LAYER', 59);
define('TIDY_TAG_LEGEND', 60);
define('TIDY_TAG_LI', 61);
define('TIDY_TAG_LINK', 62);
define('TIDY_TAG_LISTING', 63);
define('TIDY_TAG_MAP', 64);
define('TIDY_TAG_MARQUEE', 65);
define('TIDY_TAG_MENU', 66);
define('TIDY_TAG_META', 67);
define('TIDY_TAG_MULTICOL', 68);
define('TIDY_TAG_NOBR', 69);
define('TIDY_TAG_NOEMBED', 70);
define('TIDY_TAG_NOFRAMES', 71);
define('TIDY_TAG_NOLAYER', 72);
define('TIDY_TAG_NOSAVE', 73);
define('TIDY_TAG_NOSCRIPT', 74);
define('TIDY_TAG_OBJECT', 75);
define('TIDY_TAG_OL', 76);
define('TIDY_TAG_OPTGROUP', 77);
define('TIDY_TAG_OPTION', 78);
define('TIDY_TAG_P', 79);
define('TIDY_TAG_PARAM', 80);
define('TIDY_TAG_PLAINTEXT', 81);
define('TIDY_TAG_PRE', 82);
define('TIDY_TAG_Q', 83);
define('TIDY_TAG_RB', 84);
define('TIDY_TAG_RBC', 85);
define('TIDY_TAG_RP', 86);
define('TIDY_TAG_RT', 87);
define('TIDY_TAG_RTC', 88);
define('TIDY_TAG_RUBY', 89);
define('TIDY_TAG_S', 90);
define('TIDY_TAG_SAMP', 91);
define('TIDY_TAG_SCRIPT', 92);
define('TIDY_TAG_SELECT', 93);
define('TIDY_TAG_SERVER', 94);
define('TIDY_TAG_SERVLET', 95);
define('TIDY_TAG_SMALL', 96);
define('TIDY_TAG_SPACER', 97);
define('TIDY_TAG_SPAN', 98);
define('TIDY_TAG_STRIKE', 99);
define('TIDY_TAG_STRONG', 100);
define('TIDY_TAG_STYLE', 101);
define('TIDY_TAG_SUB', 102);
define('TIDY_TAG_SUP', 103);
define('TIDY_TAG_TABLE', 104);
define('TIDY_TAG_TBODY', 105);
define('TIDY_TAG_TD', 106);
define('TIDY_TAG_TEXTAREA', 107);
define('TIDY_TAG_TFOOT', 108);
define('TIDY_TAG_TH', 109);
define('TIDY_TAG_THEAD', 110);
define('TIDY_TAG_TITLE', 111);
define('TIDY_TAG_TR', 112);
define('TIDY_TAG_TT', 113);
define('TIDY_TAG_U', 114);
define('TIDY_TAG_UL', 115);
define('TIDY_TAG_VAR', 116);
define('TIDY_TAG_WBR', 117);
define('TIDY_TAG_XMP', 118);
/**
 * @since 7.4
 */
define('TIDY_TAG_ARTICLE', 123);
/**
 * @since 7.4
 */
define('TIDY_TAG_ASIDE', 124);
/**
 * @since 7.4
 */
define('TIDY_TAG_AUDIO', 125);
/**
 * @since 7.4
 */
define('TIDY_TAG_BDI', 126);
/**
 * @since 7.4
 */
define('TIDY_TAG_CANVAS', 127);
/**
 * @since 7.4
 */
define('TIDY_TAG_COMMAND', 128);
/**
 * @since 7.4
 */
define('TIDY_TAG_DATALIST', 129);
/**
 * @since 7.4
 */
define('TIDY_TAG_DETAILS', 130);
/**
 * @since 7.4
 */
define('TIDY_TAG_DIALOG', 131);
/**
 * @since 7.4
 */
define('TIDY_TAG_FIGCAPTION', 132);
/**
 * @since 7.4
 */
define('TIDY_TAG_FIGURE', 133);
/**
 * @since 7.4
 */
define('TIDY_TAG_FOOTER', 134);
/**
 * @since 7.4
 */
define('TIDY_TAG_HEADER', 135);
/**
 * @since 7.4
 */
define('TIDY_TAG_HGROUP', 136);
/**
 * @since 7.4
 */
define('TIDY_TAG_MAIN', 137);
/**
 * @since 7.4
 */
define('TIDY_TAG_MARK', 138);
/**
 * @since 7.4
 */
define('TIDY_TAG_MENUITEM', 139);
/**
 * @since 7.4
 */
define('TIDY_TAG_METER', 140);
/**
 * @since 7.4
 */
define('TIDY_TAG_NAV', 141);
/**
 * @since 7.4
 */
define('TIDY_TAG_OUTPUT', 142);
/**
 * @since 7.4
 */
define('TIDY_TAG_PROGRESS', 143);
/**
 * @since 7.4
 */
define('TIDY_TAG_SECTION', 144);
/**
 * @since 7.4
 */
define('TIDY_TAG_SOURCE', 145);
/**
 * @since 7.4
 */
define('TIDY_TAG_SUMMARY', 146);
/**
 * @since 7.4
 */
define('TIDY_TAG_TEMPLATE', 147);
/**
 * @since 7.4
 */
define('TIDY_TAG_TIME', 148);
/**
 * @since 7.4
 */
define('TIDY_TAG_TRACK', 149);
/**
 * @since 7.4
 */
define('TIDY_TAG_VIDEO', 150);

/**
 * root node
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_ROOT', 0);

/**
 * doctype
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_DOCTYPE', 1);

/**
 * HTML comment
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_COMMENT', 2);

/**
 * Processing Instruction
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_PROCINS', 3);

/**
 * Text
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_TEXT', 4);

/**
 * start tag
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_START', 5);

/**
 * end tag
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_END', 6);

/**
 * empty tag
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_STARTEND', 7);

/**
 * CDATA
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_CDATA', 8);

/**
 * XML section
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_SECTION', 9);

/**
 * ASP code
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_ASP', 10);

/**
 * JSTE code
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_JSTE', 11);

/**
 * PHP code
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_PHP', 12);

/**
 * XML declaration
 * @link https://php.net/manual/en/tidy.constants.php
 */
define('TIDY_NODETYPE_XMLDECL', 13);

// End of tidy v.2.0
