<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2017 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.github.io/scssphp
 */

namespace Leafo\ScssPhp;

use Leafo\ScssPhp\Compiler;
use Leafo\ScssPhp\Exception\ServerException;
use Leafo\ScssPhp\Version;

/**
 * Server
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class Server
{
    /**
     * @var boolean
     */
    private $showErrorsAsCSS;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @var \Leafo\ScssPhp\Compiler
     */
    private $scss;

    /**
     * Join path components
     *
     * @param string $left  Path component, left of the directory separator
     * @param string $right Path component, right of the directory separator
     *
     * @return string
     */
    protected function join($left, $right)
    {
        return rtrim($left, '/\\') . DIRECTORY_SEPARATOR . ltrim($right, '/\\');
    }

    /**
     * Get name of requested .scss file
     *
     * @return string|null
     */
    protected function inputName()
    {
        switch (true) {
            case isset($_GET['p']):
                return $_GET['p'];
            case isset($_SERVER['PATH_INFO']):
                return $_SERVER['PATH_INFO'];
            case isset($_SERVER['DOCUMENT_URI']):
                return substr($_SERVER['DOCUMENT_URI'], strlen($_SERVER['SCRIPT_NAME']));
        }
    }

    /**
     * Get path to requested .scss file
     *
     * @return string
     */
    protected function findInput()
    {
        if (($input = $this->inputName())
            && strpos($input, '..') === false
            && substr($input, -5) === '.scss'
        ) {
            $name = $this->join($this->dir, $input);

            if (is_file($name) && is_readable($name)) {
                return $name;
            }
        }

        return false;
    }

    /**
     * Get path to cached .css file
     *
     * @return string
     */
    protected function cacheName($fname)
    {
        return $this->join($this->cacheDir, md5($fname) . '.css');
    }

    /**
     * Get path to meta data
     *
     * @return string
     */
    protected function metadataName($out)
    {
        return $out . '.meta';
    }

    /**
     * Determine whether .scss file needs to be re-compiled.
     *
     * @param string $out  Output path
     * @param string $etag ETag
     *
     * @return boolean True if compile required.
     */
    protected function needsCompile($out, &$etag)
    {
        if (! is_file($out)) {
            return true;
        }

        $mtime = filemtime($out);

        $metadataName = $this->metadataName($out);

        if (is_readable($metadataName)) {
            $metadata = unserialize(file_get_contents($metadataName));

            foreach ($metadata['imports'] as $import => $originalMtime) {
                $currentMtime = filemtime($import);

                if ($currentMtime !== $originalMtime || $currentMtime > $mtime) {
                    return true;
                }
            }

            $metaVars = crc32(serialize($this->scss->getVariables()));

            if ($metaVars !== $metadata['vars']) {
                return true;
            }

            $etag = $metadata['etag'];

            return false;
        }

        return true;
    }

    /**
     * Get If-Modified-Since header from client request
     *
     * @return string|null
     */
    protected function getIfModifiedSinceHeader()
    {
        $modifiedSince = null;

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            $modifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'];

            if (false !== ($semicolonPos = strpos($modifiedSince, ';'))) {
                $modifiedSince = substr($modifiedSince, 0, $semicolonPos);
            }
        }

        return $modifiedSince;
    }

    /**
     * Get If-None-Match header from client request
     *
     * @return string|null
     */
    protected function getIfNoneMatchHeader()
    {
        $noneMatch = null;

        if (isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
            $noneMatch = $_SERVER['HTTP_IF_NONE_MATCH'];
        }

        return $noneMatch;
    }

    /**
     * Compile .scss file
     *
     * @param string $in  Input path (.scss)
     * @param string $out Output path (.css)
     *
     * @return array
     */
    protected function compile($in, $out)
    {
        $start   = microtime(true);
        $css     = $this->scss->compile(file_get_contents($in), $in);
        $elapsed = round((microtime(true) - $start), 4);

        $v    = Version::VERSION;
        $t    = gmdate('r');
        $css  = "/* compiled by scssphp $v on $t (${elapsed}s) */\n\n" . $css;
        $etag = md5($css);

        file_put_contents($out, $css);
        file_put_contents(
            $this->metadataName($out),
            serialize([
                'etag'    => $etag,
                'imports' => $this->scss->getParsedFiles(),
                'vars'    => crc32(serialize($this->scss->getVariables())),
            ])
        );

        return [$css, $etag];
    }

    /**
     * Format error as a pseudo-element in CSS
     *
     * @param \Exception $error
     *
     * @return string
     */
    protected function createErrorCSS(\Exception $error)
    {
        $message = str_replace(
            ["'", "\n"],
            ["\\'", "\\A"],
            $error->getfile() . ":\n\n" . $error->getMessage()
        );

        return "body { display: none !important; }
                html:after {
                    background: white;
                    color: black;
                    content: '$message';
                    display: block !important;
                    font-family: mono;
                    padding: 1em;
                    white-space: pre;
                }";
    }

    /**
     * Render errors as a pseudo-element within valid CSS, displaying the errors on any
     * page that includes this CSS.
     *
     * @param boolean $show
     */
    public function showErrorsAsCSS($show = true)
    {
        $this->showErrorsAsCSS = $show;
    }

    /**
     * Compile .scss file
     *
     * @param string $in  Input file (.scss)
     * @param string $out Output file (.css) optional
     *
     * @return string|bool
     *
     * @throws \Leafo\ScssPhp\Exception\ServerException
     */
    public function compileFile($in, $out = null)
    {
        if (! is_readable($in)) {
            throw new ServerException('load error: failed to find ' . $in);
        }

        $pi = pathinfo($in);

        $this->scss->addImportPath($pi['dirname'] . '/');

        $compiled = $this->scss->compile(file_get_contents($in), $in);

        if ($out !== null) {
            return file_put_contents($out, $compiled);
        }

        return $compiled;
    }

    /**
     * Check if file need compiling
     *
     * @param string $in  Input file (.scss)
     * @param string $out Output file (.css)
     *
     * @return bool
     */
    public function checkedCompile($in, $out)
    {
        if (! is_file($out) || filemtime($in) > filemtime($out)) {
            $this->compileFile($in, $out);

            return true;
        }

        return false;
    }

    /**
     * Compile requested scss and serve css.  Outputs HTTP response.
     *
     * @param string $salt Prefix a string to the filename for creating the cache name hash
     */
    public function serve($salt = '')
    {
        $protocol = isset($_SERVER['SERVER_PROTOCOL'])
            ? $_SERVER['SERVER_PROTOCOL']
            : 'HTTP/1.0';

        if ($input = $this->findInput()) {
            $output = $this->cacheName($salt . $input);
            $etag = $noneMatch = trim($this->getIfNoneMatchHeader(), '"');

            if ($this->needsCompile($output, $etag)) {
                try {
                    list($css, $etag) = $this->compile($input, $output);

                    $lastModified = gmdate('r', filemtime($output));

                    header('Last-Modified: ' . $lastModified);
                    header('Content-type: text/css');
                    header('ETag: "' . $etag . '"');

                    echo $css;
                } catch (\Exception $e) {
                    if ($this->showErrorsAsCSS) {
                        header('Content-type: text/css');

                        echo $this->createErrorCSS($e);
                    } else {
                        header($protocol . ' 500 Internal Server Error');
                        header('Content-type: text/plain');

                        echo 'Parse error: ' . $e->getMessage() . "\n";
                    }
                }

                return;
            }

            header('X-SCSS-Cache: true');
            header('Content-type: text/css');
            header('ETag: "' . $etag . '"');

            if ($etag === $noneMatch) {
                header($protocol . ' 304 Not Modified');

                return;
            }

            $modifiedSince = $this->getIfModifiedSinceHeader();
            $mtime = filemtime($output);

            if (strtotime($modifiedSince) === $mtime) {
                header($protocol . ' 304 Not Modified');

                return;
            }

            $lastModified  = gmdate('r', $mtime);
            header('Last-Modified: ' . $lastModified);

            echo file_get_contents($output);

            return;
        }

        header($protocol . ' 404 Not Found');
        header('Content-type: text/plain');

        $v = Version::VERSION;
        echo "/* INPUT NOT FOUND scss $v */\n";
    }

    /**
     * Based on explicit input/output files does a full change check on cache before compiling.
     *
     * @param string  $in
     * @param string  $out
     * @param boolean $force
     *
     * @return string Compiled CSS results
     *
     * @throws \Leafo\ScssPhp\Exception\ServerException
     */
    public function checkedCachedCompile($in, $out, $force = false)
    {
        if (! is_file($in) || ! is_readable($in)) {
            throw new ServerException('Invalid or unreadable input file specified.');
        }

        if (is_dir($out) || ! is_writable(file_exists($out) ? $out : dirname($out))) {
            throw new ServerException('Invalid or unwritable output file specified.');
        }

        if ($force || $this->needsCompile($out, $etag)) {
            list($css, $etag) = $this->compile($in, $out);
        } else {
            $css = file_get_contents($out);
        }

        return $css;
    }

    /**
     * Execute scssphp on a .scss file or a scssphp cache structure
     *
     * The scssphp cache structure contains information about a specific
     * scss file having been parsed. It can be used as a hint for future
     * calls to determine whether or not a rebuild is required.
     *
     * The cache structure contains two important keys that may be used
     * externally:
     *
     * compiled: The final compiled CSS
     * updated: The time (in seconds) the CSS was last compiled
     *
     * The cache structure is a plain-ol' PHP associative array and can
     * be serialized and unserialized without a hitch.
     *
     * @param mixed   $in    Input
     * @param boolean $force Force rebuild?
     *
     * @return array scssphp cache structure
     */
    public function cachedCompile($in, $force = false)
    {
        // assume no root
        $root = null;

        if (is_string($in)) {
            $root = $in;
        } elseif (is_array($in) and isset($in['root'])) {
            if ($force or ! isset($in['files'])) {
                // If we are forcing a recompile or if for some reason the
                // structure does not contain any file information we should
                // specify the root to trigger a rebuild.
                $root = $in['root'];
            } elseif (isset($in['files']) and is_array($in['files'])) {
                foreach ($in['files'] as $fname => $ftime) {
                    if (! file_exists($fname) or filemtime($fname) > $ftime) {
                        // One of the files we knew about previously has changed
                        // so we should look at our incoming root again.
                        $root = $in['root'];
                        break;
                    }
                }
            }
        } else {
            // TODO: Throw an exception? We got neither a string nor something
            // that looks like a compatible lessphp cache structure.
            return null;
        }

        if ($root !== null) {
            // If we have a root value which means we should rebuild.
            $out = array();
            $out['root'] = $root;
            $out['compiled'] = $this->compileFile($root);
            $out['files'] = $this->scss->getParsedFiles();
            $out['updated'] = time();
            return $out;
        } else {
            // No changes, pass back the structure
            // we were given initially.
            return $in;
        }
    }

    /**
     * Constructor
     *
     * @param string                       $dir      Root directory to .scss files
     * @param string                       $cacheDir Cache directory
     * @param \Leafo\ScssPhp\Compiler|null $scss     SCSS compiler instance
     */
    public function __construct($dir, $cacheDir = null, $scss = null)
    {
        $this->dir = $dir;

        if (! isset($cacheDir)) {
            $cacheDir = $this->join($dir, 'scss_cache');
        }

        $this->cacheDir = $cacheDir;

        if (! is_dir($this->cacheDir)) {
            throw new ServerException('Cache directory doesn\'t exist: ' . $cacheDir);
        }

        if (! isset($scss)) {
            $scss = new Compiler();
            $scss->setImportPaths($this->dir);
        }

        $this->scss = $scss;
        $this->showErrorsAsCSS = false;

        date_default_timezone_set('UTC');
    }
}
