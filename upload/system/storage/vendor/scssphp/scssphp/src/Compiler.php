<?php
/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp;

use ScssPhp\ScssPhp\Base\Range;
use ScssPhp\ScssPhp\Block;
use ScssPhp\ScssPhp\Cache;
use ScssPhp\ScssPhp\Colors;
use ScssPhp\ScssPhp\Compiler\Environment;
use ScssPhp\ScssPhp\Exception\CompilerException;
use ScssPhp\ScssPhp\Formatter\OutputBlock;
use ScssPhp\ScssPhp\Node;
use ScssPhp\ScssPhp\SourceMap\SourceMapGenerator;
use ScssPhp\ScssPhp\Type;
use ScssPhp\ScssPhp\Parser;
use ScssPhp\ScssPhp\Util;

/**
 * The scss compiler and parser.
 *
 * Converting SCSS to CSS is a three stage process. The incoming file is parsed
 * by `Parser` into a syntax tree, then it is compiled into another tree
 * representing the CSS structure by `Compiler`. The CSS tree is fed into a
 * formatter, like `Formatter` which then outputs CSS as a string.
 *
 * During the first compile, all values are *reduced*, which means that their
 * types are brought to the lowest form before being dump as strings. This
 * handles math equations, variable dereferences, and the like.
 *
 * The `compile` function of `Compiler` is the entry point.
 *
 * In summary:
 *
 * The `Compiler` class creates an instance of the parser, feeds it SCSS code,
 * then transforms the resulting tree to a CSS tree. This class also holds the
 * evaluation context, such as all available mixins and variables at any given
 * time.
 *
 * The `Parser` class is only concerned with parsing its input.
 *
 * The `Formatter` takes a CSS tree, and dumps it to a formatted string,
 * handling things like indentation.
 */

/**
 * SCSS compiler
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class Compiler
{
    const LINE_COMMENTS = 1;
    const DEBUG_INFO    = 2;

    const WITH_RULE     = 1;
    const WITH_MEDIA    = 2;
    const WITH_SUPPORTS = 4;
    const WITH_ALL      = 7;

    const SOURCE_MAP_NONE   = 0;
    const SOURCE_MAP_INLINE = 1;
    const SOURCE_MAP_FILE   = 2;

    /**
     * @var array
     */
    protected static $operatorNames = [
        '+'   => 'add',
        '-'   => 'sub',
        '*'   => 'mul',
        '/'   => 'div',
        '%'   => 'mod',

        '=='  => 'eq',
        '!='  => 'neq',
        '<'   => 'lt',
        '>'   => 'gt',

        '<='  => 'lte',
        '>='  => 'gte',
        '<=>' => 'cmp',
    ];

    /**
     * @var array
     */
    protected static $namespaces = [
        'special'  => '%',
        'mixin'    => '@',
        'function' => '^',
    ];

    public static $true         = [Type::T_KEYWORD, 'true'];
    public static $false        = [Type::T_KEYWORD, 'false'];
    public static $NaN          = [Type::T_KEYWORD, 'NaN'];
    public static $Infinity     = [Type::T_KEYWORD, 'Infinity'];
    public static $null         = [Type::T_NULL];
    public static $nullString   = [Type::T_STRING, '', []];
    public static $defaultValue = [Type::T_KEYWORD, ''];
    public static $selfSelector = [Type::T_SELF];
    public static $emptyList    = [Type::T_LIST, '', []];
    public static $emptyMap     = [Type::T_MAP, [], []];
    public static $emptyString  = [Type::T_STRING, '"', []];
    public static $with         = [Type::T_KEYWORD, 'with'];
    public static $without      = [Type::T_KEYWORD, 'without'];

    protected $importPaths = [''];
    protected $importCache = [];
    protected $importedFiles = [];
    protected $userFunctions = [];
    protected $registeredVars = [];
    protected $registeredFeatures = [
        'extend-selector-pseudoclass' => false,
        'at-error'                    => true,
        'units-level-3'               => false,
        'global-variable-shadowing'   => false,
    ];

    protected $encoding = null;
    protected $lineNumberStyle = null;

    protected $sourceMap = self::SOURCE_MAP_NONE;
    protected $sourceMapOptions = [];

    /**
     * @var string|\ScssPhp\ScssPhp\Formatter
     */
    protected $formatter = 'ScssPhp\ScssPhp\Formatter\Nested';

    protected $rootEnv;
    protected $rootBlock;

    /**
     * @var \ScssPhp\ScssPhp\Compiler\Environment
     */
    protected $env;
    protected $scope;
    protected $storeEnv;
    protected $charsetSeen;
    protected $sourceNames;

    protected $cache;

    protected $indentLevel;
    protected $extends;
    protected $extendsMap;
    protected $parsedFiles;
    protected $parser;
    protected $sourceIndex;
    protected $sourceLine;
    protected $sourceColumn;
    protected $stderr;
    protected $shouldEvaluate;
    protected $ignoreErrors;
    protected $ignoreCallStackMessage = false;

    protected $callStack = [];

    /**
     * Constructor
     *
     * @param array|null $cacheOptions
     */
    public function __construct($cacheOptions = null)
    {
        $this->parsedFiles = [];
        $this->sourceNames = [];

        if ($cacheOptions) {
            $this->cache = new Cache($cacheOptions);
        }

        $this->stderr = fopen('php://stderr', 'w');
    }

    /**
     * Get compiler options
     *
     * @return array
     */
    public function getCompileOptions()
    {
        $options = [
            'importPaths'        => $this->importPaths,
            'registeredVars'     => $this->registeredVars,
            'registeredFeatures' => $this->registeredFeatures,
            'encoding'           => $this->encoding,
            'sourceMap'          => serialize($this->sourceMap),
            'sourceMapOptions'   => $this->sourceMapOptions,
            'formatter'          => $this->formatter,
        ];

        return $options;
    }

    /**
     * Set an alternative error output stream, for testing purpose only
     *
     * @param resource $handle
     */
    public function setErrorOuput($handle)
    {
        $this->stderr = $handle;
    }

    /**
     * Compile scss
     *
     * @api
     *
     * @param string $code
     * @param string $path
     *
     * @return string
     */
    public function compile($code, $path = null)
    {
        if ($this->cache) {
            $cacheKey       = ($path ? $path : "(stdin)") . ":" . md5($code);
            $compileOptions = $this->getCompileOptions();
            $cache          = $this->cache->getCache("compile", $cacheKey, $compileOptions);

            if (\is_array($cache) && isset($cache['dependencies']) && isset($cache['out'])) {
                // check if any dependency file changed before accepting the cache
                foreach ($cache['dependencies'] as $file => $mtime) {
                    if (! is_file($file) || filemtime($file) !== $mtime) {
                        unset($cache);
                        break;
                    }
                }

                if (isset($cache)) {
                    return $cache['out'];
                }
            }
        }


        $this->indentLevel    = -1;
        $this->extends        = [];
        $this->extendsMap     = [];
        $this->sourceIndex    = null;
        $this->sourceLine     = null;
        $this->sourceColumn   = null;
        $this->env            = null;
        $this->scope          = null;
        $this->storeEnv       = null;
        $this->charsetSeen    = null;
        $this->shouldEvaluate = null;
        $this->ignoreCallStackMessage = false;

        $this->parser = $this->parserFactory($path);
        $tree         = $this->parser->parse($code);
        $this->parser = null;

        $this->formatter = new $this->formatter();
        $this->rootBlock = null;
        $this->rootEnv   = $this->pushEnv($tree);

        $this->injectVariables($this->registeredVars);
        $this->compileRoot($tree);
        $this->popEnv();

        $sourceMapGenerator = null;

        if ($this->sourceMap) {
            if (\is_object($this->sourceMap) && $this->sourceMap instanceof SourceMapGenerator) {
                $sourceMapGenerator = $this->sourceMap;
                $this->sourceMap = self::SOURCE_MAP_FILE;
            } elseif ($this->sourceMap !== self::SOURCE_MAP_NONE) {
                $sourceMapGenerator = new SourceMapGenerator($this->sourceMapOptions);
            }
        }

        $out = $this->formatter->format($this->scope, $sourceMapGenerator);

        if (! empty($out) && $this->sourceMap && $this->sourceMap !== self::SOURCE_MAP_NONE) {
            $sourceMap    = $sourceMapGenerator->generateJson();
            $sourceMapUrl = null;

            switch ($this->sourceMap) {
                case self::SOURCE_MAP_INLINE:
                    $sourceMapUrl = sprintf('data:application/json,%s', Util::encodeURIComponent($sourceMap));
                    break;

                case self::SOURCE_MAP_FILE:
                    $sourceMapUrl = $sourceMapGenerator->saveMap($sourceMap);
                    break;
            }

            $out .= sprintf('/*# sourceMappingURL=%s */', $sourceMapUrl);
        }

        if ($this->cache && isset($cacheKey) && isset($compileOptions)) {
            $v = [
                'dependencies' => $this->getParsedFiles(),
                'out' => &$out,
            ];

            $this->cache->setCache("compile", $cacheKey, $v, $compileOptions);
        }

        return $out;
    }

    /**
     * Instantiate parser
     *
     * @param string $path
     *
     * @return \ScssPhp\ScssPhp\Parser
     */
    protected function parserFactory($path)
    {
        // https://sass-lang.com/documentation/at-rules/import
        // CSS files imported by Sass don’t allow any special Sass features.
        // In order to make sure authors don’t accidentally write Sass in their CSS,
        // all Sass features that aren’t also valid CSS will produce errors.
        // Otherwise, the CSS will be rendered as-is. It can even be extended!
        $cssOnly = false;

        if (substr($path, '-4') === '.css') {
            $cssOnly = true;
        }

        $parser = new Parser($path, \count($this->sourceNames), $this->encoding, $this->cache, $cssOnly);

        $this->sourceNames[] = $path;
        $this->addParsedFile($path);

        return $parser;
    }

    /**
     * Is self extend?
     *
     * @param array $target
     * @param array $origin
     *
     * @return boolean
     */
    protected function isSelfExtend($target, $origin)
    {
        foreach ($origin as $sel) {
            if (\in_array($target, $sel)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Push extends
     *
     * @param array      $target
     * @param array      $origin
     * @param array|null $block
     */
    protected function pushExtends($target, $origin, $block)
    {
        $i = \count($this->extends);
        $this->extends[] = [$target, $origin, $block];

        foreach ($target as $part) {
            if (isset($this->extendsMap[$part])) {
                $this->extendsMap[$part][] = $i;
            } else {
                $this->extendsMap[$part] = [$i];
            }
        }
    }

    /**
     * Make output block
     *
     * @param string $type
     * @param array  $selectors
     *
     * @return \ScssPhp\ScssPhp\Formatter\OutputBlock
     */
    protected function makeOutputBlock($type, $selectors = null)
    {
        $out = new OutputBlock;
        $out->type      = $type;
        $out->lines     = [];
        $out->children  = [];
        $out->parent    = $this->scope;
        $out->selectors = $selectors;
        $out->depth     = $this->env->depth;

        if ($this->env->block instanceof Block) {
            $out->sourceName   = $this->env->block->sourceName;
            $out->sourceLine   = $this->env->block->sourceLine;
            $out->sourceColumn = $this->env->block->sourceColumn;
        } else {
            $out->sourceName   = null;
            $out->sourceLine   = null;
            $out->sourceColumn = null;
        }

        return $out;
    }

    /**
     * Compile root
     *
     * @param \ScssPhp\ScssPhp\Block $rootBlock
     */
    protected function compileRoot(Block $rootBlock)
    {
        $this->rootBlock = $this->scope = $this->makeOutputBlock(Type::T_ROOT);

        $this->compileChildrenNoReturn($rootBlock->children, $this->scope);
        $this->flattenSelectors($this->scope);
        $this->missingSelectors();
    }

    /**
     * Report missing selectors
     */
    protected function missingSelectors()
    {
        foreach ($this->extends as $extend) {
            if (isset($extend[3])) {
                continue;
            }

            list($target, $origin, $block) = $extend;

            // ignore if !optional
            if ($block[2]) {
                continue;
            }

            $target = implode(' ', $target);
            $origin = $this->collapseSelectors($origin);

            $this->sourceLine = $block[Parser::SOURCE_LINE];
            $this->throwError("\"$origin\" failed to @extend \"$target\". The selector \"$target\" was not found.");
        }
    }

    /**
     * Flatten selectors
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $block
     * @param string                                 $parentKey
     */
    protected function flattenSelectors(OutputBlock $block, $parentKey = null)
    {
        if ($block->selectors) {
            $selectors = [];

            foreach ($block->selectors as $s) {
                $selectors[] = $s;

                if (! \is_array($s)) {
                    continue;
                }

                // check extends
                if (! empty($this->extendsMap)) {
                    $this->matchExtends($s, $selectors);

                    // remove duplicates
                    array_walk($selectors, function (&$value) {
                        $value = serialize($value);
                    });

                    $selectors = array_unique($selectors);

                    array_walk($selectors, function (&$value) {
                        $value = unserialize($value);
                    });
                }
            }

            $block->selectors = [];
            $placeholderSelector = false;

            foreach ($selectors as $selector) {
                if ($this->hasSelectorPlaceholder($selector)) {
                    $placeholderSelector = true;
                    continue;
                }

                $block->selectors[] = $this->compileSelector($selector);
            }

            if ($placeholderSelector && 0 === \count($block->selectors) && null !== $parentKey) {
                unset($block->parent->children[$parentKey]);

                return;
            }
        }

        foreach ($block->children as $key => $child) {
            $this->flattenSelectors($child, $key);
        }
    }

    /**
     * Glue parts of :not( or :nth-child( ... that are in general splitted in selectors parts
     *
     * @param array $parts
     *
     * @return array
     */
    protected function glueFunctionSelectors($parts)
    {
        $new = [];

        foreach ($parts as $part) {
            if (\is_array($part)) {
                $part = $this->glueFunctionSelectors($part);
                $new[] = $part;
            } else {
                // a selector part finishing with a ) is the last part of a :not( or :nth-child(
                // and need to be joined to this
                if (\count($new) && \is_string($new[\count($new) - 1]) &&
                    \strlen($part) && substr($part, -1) === ')' && strpos($part, '(') === false
                ) {
                    while (\count($new)>1 && substr($new[\count($new) - 1], -1) !== '(') {
                        $part = array_pop($new) . $part;
                    }
                    $new[\count($new) - 1] .= $part;
                } else {
                    $new[] = $part;
                }
            }
        }

        return $new;
    }

    /**
     * Match extends
     *
     * @param array   $selector
     * @param array   $out
     * @param integer $from
     * @param boolean $initial
     */
    protected function matchExtends($selector, &$out, $from = 0, $initial = true)
    {
        static $partsPile = [];
        $selector = $this->glueFunctionSelectors($selector);

        if (\count($selector) == 1 && \in_array(reset($selector), $partsPile)) {
            return;
        }

        $outRecurs = [];

        foreach ($selector as $i => $part) {
            if ($i < $from) {
                continue;
            }

            // check that we are not building an infinite loop of extensions
            // if the new part is just including a previous part don't try to extend anymore
            if (\count($part) > 1) {
                foreach ($partsPile as $previousPart) {
                    if (! \count(array_diff($previousPart, $part))) {
                        continue 2;
                    }
                }
            }

            $partsPile[] = $part;

            if ($this->matchExtendsSingle($part, $origin, $initial)) {
                $after       = \array_slice($selector, $i + 1);
                $before      = \array_slice($selector, 0, $i);
                list($before, $nonBreakableBefore) = $this->extractRelationshipFromFragment($before);

                foreach ($origin as $new) {
                    $k = 0;

                    // remove shared parts
                    if (\count($new) > 1) {
                        while ($k < $i && isset($new[$k]) && $selector[$k] === $new[$k]) {
                            $k++;
                        }
                    }

                    if (\count($nonBreakableBefore) and $k == \count($new)) {
                        $k--;
                    }

                    $replacement = [];
                    $tempReplacement = $k > 0 ? \array_slice($new, $k) : $new;

                    for ($l = \count($tempReplacement) - 1; $l >= 0; $l--) {
                        $slice = [];

                        foreach ($tempReplacement[$l] as $chunk) {
                            if (! \in_array($chunk, $slice)) {
                                $slice[] = $chunk;
                            }
                        }

                        array_unshift($replacement, $slice);

                        if (! $this->isImmediateRelationshipCombinator(end($slice))) {
                            break;
                        }
                    }

                    $afterBefore = $l != 0 ? \array_slice($tempReplacement, 0, $l) : [];

                    // Merge shared direct relationships.
                    $mergedBefore = $this->mergeDirectRelationships($afterBefore, $nonBreakableBefore);

                    $result = array_merge(
                        $before,
                        $mergedBefore,
                        $replacement,
                        $after
                    );

                    if ($result === $selector) {
                        continue;
                    }

                    $this->pushOrMergeExtentedSelector($out, $result);

                    // recursively check for more matches
                    $startRecurseFrom = \count($before) + min(\count($nonBreakableBefore), \count($mergedBefore));

                    if (\count($origin) > 1) {
                        $this->matchExtends($result, $out, $startRecurseFrom, false);
                    } else {
                        $this->matchExtends($result, $outRecurs, $startRecurseFrom, false);
                    }

                    // selector sequence merging
                    if (! empty($before) && \count($new) > 1) {
                        $preSharedParts = $k > 0 ? \array_slice($before, 0, $k) : [];
                        $postSharedParts = $k > 0 ? \array_slice($before, $k) : $before;

                        list($betweenSharedParts, $nonBreakabl2) = $this->extractRelationshipFromFragment($afterBefore);

                        $result2 = array_merge(
                            $preSharedParts,
                            $betweenSharedParts,
                            $postSharedParts,
                            $nonBreakabl2,
                            $nonBreakableBefore,
                            $replacement,
                            $after
                        );

                        $this->pushOrMergeExtentedSelector($out, $result2);
                    }
                }
            }
            array_pop($partsPile);
        }

        while (\count($outRecurs)) {
            $result = array_shift($outRecurs);
            $this->pushOrMergeExtentedSelector($out, $result);
        }
    }

    /**
     * Test a part for being a pseudo selector
     *
     * @param string $part
     * @param array  $matches
     *
     * @return boolean
     */
    protected function isPseudoSelector($part, &$matches)
    {
        if (strpos($part, ":") === 0
            && preg_match(",^::?([\w-]+)\((.+)\)$,", $part, $matches)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Push extended selector except if
     *  - this is a pseudo selector
     *  - same as previous
     *  - in a white list
     * in this case we merge the pseudo selector content
     *
     * @param array $out
     * @param array $extended
     */
    protected function pushOrMergeExtentedSelector(&$out, $extended)
    {
        if (\count($out) && \count($extended) === 1 && \count(reset($extended)) === 1) {
            $single = reset($extended);
            $part = reset($single);

            if ($this->isPseudoSelector($part, $matchesExtended) &&
                \in_array($matchesExtended[1], [ 'slotted' ])
            ) {
                $prev = end($out);
                $prev = $this->glueFunctionSelectors($prev);

                if (\count($prev) === 1 && \count(reset($prev)) === 1) {
                    $single = reset($prev);
                    $part = reset($single);

                    if ($this->isPseudoSelector($part, $matchesPrev) &&
                        $matchesPrev[1] === $matchesExtended[1]
                    ) {
                        $extended = explode($matchesExtended[1] . '(', $matchesExtended[0], 2);
                        $extended[1] = $matchesPrev[2] . ", " . $extended[1];
                        $extended = implode($matchesExtended[1] . '(', $extended);
                        $extended = [ [ $extended ]];
                        array_pop($out);
                    }
                }
            }
        }
        $out[] = $extended;
    }

    /**
     * Match extends single
     *
     * @param array   $rawSingle
     * @param array   $outOrigin
     * @param boolean $initial
     *
     * @return boolean
     */
    protected function matchExtendsSingle($rawSingle, &$outOrigin, $initial = true)
    {
        $counts = [];
        $single = [];

        // simple usual cases, no need to do the whole trick
        if (\in_array($rawSingle, [['>'],['+'],['~']])) {
            return false;
        }

        foreach ($rawSingle as $part) {
            // matches Number
            if (! \is_string($part)) {
                return false;
            }

            if (! preg_match('/^[\[.:#%]/', $part) && \count($single)) {
                $single[\count($single) - 1] .= $part;
            } else {
                $single[] = $part;
            }
        }

        $extendingDecoratedTag = false;

        if (\count($single) > 1) {
            $matches = null;
            $extendingDecoratedTag = preg_match('/^[a-z0-9]+$/i', $single[0], $matches) ? $matches[0] : false;
        }

        $outOrigin = [];
        $found = false;

        foreach ($single as $k => $part) {
            if (isset($this->extendsMap[$part])) {
                foreach ($this->extendsMap[$part] as $idx) {
                    $counts[$idx] = isset($counts[$idx]) ? $counts[$idx] + 1 : 1;
                }
            }

            if ($initial &&
                $this->isPseudoSelector($part, $matches) &&
                ! \in_array($matches[1], [ 'not' ])
            ) {
                $buffer    = $matches[2];
                $parser    = $this->parserFactory(__METHOD__);

                if ($parser->parseSelector($buffer, $subSelectors)) {
                    foreach ($subSelectors as $ksub => $subSelector) {
                        $subExtended = [];
                        $this->matchExtends($subSelector, $subExtended, 0, false);

                        if ($subExtended) {
                            $subSelectorsExtended = $subSelectors;
                            $subSelectorsExtended[$ksub] = $subExtended;

                            foreach ($subSelectorsExtended as $ksse => $sse) {
                                $subSelectorsExtended[$ksse] = $this->collapseSelectors($sse);
                            }

                            $subSelectorsExtended = implode(', ', $subSelectorsExtended);
                            $singleExtended = $single;
                            $singleExtended[$k] = str_replace("(".$buffer.")", "($subSelectorsExtended)", $part);
                            $outOrigin[] = [ $singleExtended ];
                            $found = true;
                        }
                    }
                }
            }
        }

        foreach ($counts as $idx => $count) {
            list($target, $origin, /* $block */) = $this->extends[$idx];

            $origin = $this->glueFunctionSelectors($origin);

            // check count
            if ($count !== \count($target)) {
                continue;
            }

            $this->extends[$idx][3] = true;

            $rem = array_diff($single, $target);

            foreach ($origin as $j => $new) {
                // prevent infinite loop when target extends itself
                if ($this->isSelfExtend($single, $origin) and !$initial) {
                    return false;
                }

                $replacement = end($new);

                // Extending a decorated tag with another tag is not possible.
                if ($extendingDecoratedTag && $replacement[0] != $extendingDecoratedTag &&
                    preg_match('/^[a-z0-9]+$/i', $replacement[0])
                ) {
                    unset($origin[$j]);
                    continue;
                }

                $combined = $this->combineSelectorSingle($replacement, $rem);

                if (\count(array_diff($combined, $origin[$j][\count($origin[$j]) - 1]))) {
                    $origin[$j][\count($origin[$j]) - 1] = $combined;
                }
            }

            $outOrigin = array_merge($outOrigin, $origin);

            $found = true;
        }

        return $found;
    }

    /**
     * Extract a relationship from the fragment.
     *
     * When extracting the last portion of a selector we will be left with a
     * fragment which may end with a direction relationship combinator. This
     * method will extract the relationship fragment and return it along side
     * the rest.
     *
     * @param array $fragment The selector fragment maybe ending with a direction relationship combinator.
     *
     * @return array The selector without the relationship fragment if any, the relationship fragment.
     */
    protected function extractRelationshipFromFragment(array $fragment)
    {
        $parents = [];
        $children = [];

        $j = $i = \count($fragment);

        for (;;) {
            $children = $j != $i ? \array_slice($fragment, $j, $i - $j) : [];
            $parents  = \array_slice($fragment, 0, $j);
            $slice    = end($parents);

            if (empty($slice) || ! $this->isImmediateRelationshipCombinator($slice[0])) {
                break;
            }

            $j -= 2;
        }

        return [$parents, $children];
    }

    /**
     * Combine selector single
     *
     * @param array $base
     * @param array $other
     *
     * @return array
     */
    protected function combineSelectorSingle($base, $other)
    {
        $tag    = [];
        $out    = [];
        $wasTag = false;
        $pseudo = [];

        while (\count($other) && strpos(end($other), ':')===0) {
            array_unshift($pseudo, array_pop($other));
        }

        foreach ([array_reverse($base), array_reverse($other)] as $single) {
            $rang = count($single);
            foreach ($single as $part) {
                if (preg_match('/^[\[:]/', $part)) {
                    $out[] = $part;
                    $wasTag = false;
                } elseif (preg_match('/^[\.#]/', $part)) {
                    array_unshift($out, $part);
                    $wasTag = false;
                } elseif (preg_match('/^[^_-]/', $part) and $rang==1) {
                    $tag[] = $part;
                    $wasTag = true;
                } elseif ($wasTag) {
                    $tag[\count($tag) - 1] .= $part;
                } else {
                    array_unshift($out, $part);
                }
                $rang--;
            }
        }

        if (\count($tag)) {
            array_unshift($out, $tag[0]);
        }

        while (\count($pseudo)) {
            $out[] = array_shift($pseudo);
        }

        return $out;
    }

    /**
     * Compile media
     *
     * @param \ScssPhp\ScssPhp\Block $media
     */
    protected function compileMedia(Block $media)
    {
        $this->pushEnv($media);

        $mediaQueries = $this->compileMediaQuery($this->multiplyMedia($this->env));

        if (! empty($mediaQueries) && $mediaQueries) {
            $previousScope = $this->scope;
            $parentScope = $this->mediaParent($this->scope);

            foreach ($mediaQueries as $mediaQuery) {
                $this->scope = $this->makeOutputBlock(Type::T_MEDIA, [$mediaQuery]);

                $parentScope->children[] = $this->scope;
                $parentScope = $this->scope;
            }

            // top level properties in a media cause it to be wrapped
            $needsWrap = false;

            foreach ($media->children as $child) {
                $type = $child[0];

                if ($type !== Type::T_BLOCK &&
                    $type !== Type::T_MEDIA &&
                    $type !== Type::T_DIRECTIVE &&
                    $type !== Type::T_IMPORT
                ) {
                    $needsWrap = true;
                    break;
                }
            }

            if ($needsWrap) {
                $wrapped = new Block;
                $wrapped->sourceName   = $media->sourceName;
                $wrapped->sourceIndex  = $media->sourceIndex;
                $wrapped->sourceLine   = $media->sourceLine;
                $wrapped->sourceColumn = $media->sourceColumn;
                $wrapped->selectors    = [];
                $wrapped->comments     = [];
                $wrapped->parent       = $media;
                $wrapped->children     = $media->children;

                $media->children = [[Type::T_BLOCK, $wrapped]];

                if (isset($this->lineNumberStyle)) {
                    $annotation = $this->makeOutputBlock(Type::T_COMMENT);
                    $annotation->depth = 0;

                    $file = $this->sourceNames[$media->sourceIndex];
                    $line = $media->sourceLine;

                    switch ($this->lineNumberStyle) {
                        case static::LINE_COMMENTS:
                            $annotation->lines[] = '/* line ' . $line
                                                 . ($file ? ', ' . $file : '')
                                                 . ' */';
                            break;

                        case static::DEBUG_INFO:
                            $annotation->lines[] = '@media -sass-debug-info{'
                                                 . ($file ? 'filename{font-family:"' . $file . '"}' : '')
                                                 . 'line{font-family:' . $line . '}}';
                            break;
                    }

                    $this->scope->children[] = $annotation;
                }
            }

            $this->compileChildrenNoReturn($media->children, $this->scope);

            $this->scope = $previousScope;
        }

        $this->popEnv();
    }

    /**
     * Media parent
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $scope
     *
     * @return \ScssPhp\ScssPhp\Formatter\OutputBlock
     */
    protected function mediaParent(OutputBlock $scope)
    {
        while (! empty($scope->parent)) {
            if (! empty($scope->type) && $scope->type !== Type::T_MEDIA) {
                break;
            }

            $scope = $scope->parent;
        }

        return $scope;
    }

    /**
     * Compile directive
     *
     * @param \ScssPhp\ScssPhp\Block|array $block
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     */
    protected function compileDirective($directive, OutputBlock $out)
    {
        if (\is_array($directive)) {
            $s = '@' . $directive[0];

            if (! empty($directive[1])) {
                $s .= ' ' . $this->compileValue($directive[1]);
            }

            $this->appendRootDirective($s . ';', $out);
        } else {
            $s = '@' . $directive->name;

            if (! empty($directive->value)) {
                $s .= ' ' . $this->compileValue($directive->value);
            }

            if ($directive->name === 'keyframes' || substr($directive->name, -10) === '-keyframes') {
                $this->compileKeyframeBlock($directive, [$s]);
            } else {
                $this->compileNestedBlock($directive, [$s]);
            }
        }
    }

    /**
     * Compile at-root
     *
     * @param \ScssPhp\ScssPhp\Block $block
     */
    protected function compileAtRoot(Block $block)
    {
        $env     = $this->pushEnv($block);
        $envs    = $this->compactEnv($env);
        list($with, $without) = $this->compileWith(isset($block->with) ? $block->with : null);

        // wrap inline selector
        if ($block->selector) {
            $wrapped = new Block;
            $wrapped->sourceName   = $block->sourceName;
            $wrapped->sourceIndex  = $block->sourceIndex;
            $wrapped->sourceLine   = $block->sourceLine;
            $wrapped->sourceColumn = $block->sourceColumn;
            $wrapped->selectors    = $block->selector;
            $wrapped->comments     = [];
            $wrapped->parent       = $block;
            $wrapped->children     = $block->children;
            $wrapped->selfParent   = $block->selfParent;

            $block->children = [[Type::T_BLOCK, $wrapped]];
            $block->selector = null;
        }

        $selfParent = $block->selfParent;

        if (! $block->selfParent->selectors && isset($block->parent) && $block->parent &&
            isset($block->parent->selectors) && $block->parent->selectors
        ) {
            $selfParent = $block->parent;
        }

        $this->env = $this->filterWithWithout($envs, $with, $without);

        $saveScope   = $this->scope;
        $this->scope = $this->filterScopeWithWithout($saveScope, $with, $without);

        // propagate selfParent to the children where they still can be useful
        $this->compileChildrenNoReturn($block->children, $this->scope, $selfParent);

        $this->scope = $this->completeScope($this->scope, $saveScope);
        $this->scope = $saveScope;
        $this->env   = $this->extractEnv($envs);

        $this->popEnv();
    }

    /**
     * Filter at-root scope depending of with/without option
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $scope
     * @param array                                  $with
     * @param array                                  $without
     *
     * @return mixed
     */
    protected function filterScopeWithWithout($scope, $with, $without)
    {
        $filteredScopes = [];
        $childStash = [];

        if ($scope->type === TYPE::T_ROOT) {
            return $scope;
        }

        // start from the root
        while ($scope->parent && $scope->parent->type !== TYPE::T_ROOT) {
            array_unshift($childStash, $scope);
            $scope = $scope->parent;
        }

        for (;;) {
            if (! $scope) {
                break;
            }

            if ($this->isWith($scope, $with, $without)) {
                $s = clone $scope;
                $s->children = [];
                $s->lines    = [];
                $s->parent   = null;

                if ($s->type !== Type::T_MEDIA && $s->type !== Type::T_DIRECTIVE) {
                    $s->selectors = [];
                }

                $filteredScopes[] = $s;
            }

            if (\count($childStash)) {
                $scope = array_shift($childStash);
            } elseif ($scope->children) {
                $scope = end($scope->children);
            } else {
                $scope = null;
            }
        }

        if (! \count($filteredScopes)) {
            return $this->rootBlock;
        }

        $newScope = array_shift($filteredScopes);
        $newScope->parent = $this->rootBlock;

        $this->rootBlock->children[] = $newScope;

        $p = &$newScope;

        while (\count($filteredScopes)) {
            $s = array_shift($filteredScopes);
            $s->parent = $p;
            $p->children[] = $s;
            $newScope = &$p->children[0];
            $p = &$p->children[0];
        }

        return $newScope;
    }

    /**
     * found missing selector from a at-root compilation in the previous scope
     * (if at-root is just enclosing a property, the selector is in the parent tree)
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $scope
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $previousScope
     *
     * @return mixed
     */
    protected function completeScope($scope, $previousScope)
    {
        if (! $scope->type && (! $scope->selectors || ! \count($scope->selectors)) && \count($scope->lines)) {
            $scope->selectors = $this->findScopeSelectors($previousScope, $scope->depth);
        }

        if ($scope->children) {
            foreach ($scope->children as $k => $c) {
                $scope->children[$k] = $this->completeScope($c, $previousScope);
            }
        }

        return $scope;
    }

    /**
     * Find a selector by the depth node in the scope
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $scope
     * @param integer                                $depth
     *
     * @return array
     */
    protected function findScopeSelectors($scope, $depth)
    {
        if ($scope->depth === $depth && $scope->selectors) {
            return $scope->selectors;
        }

        if ($scope->children) {
            foreach (array_reverse($scope->children) as $c) {
                if ($s = $this->findScopeSelectors($c, $depth)) {
                    return $s;
                }
            }
        }

        return [];
    }

    /**
     * Compile @at-root's with: inclusion / without: exclusion into 2 lists uses to filter scope/env later
     *
     * @param array $withCondition
     *
     * @return array
     */
    protected function compileWith($withCondition)
    {
        // just compile what we have in 2 lists
        $with = [];
        $without = ['rule' => true];

        if ($withCondition) {
            if ($this->libMapHasKey([$withCondition, static::$with])) {
                $without = []; // cancel the default
                $list = $this->coerceList($this->libMapGet([$withCondition, static::$with]));

                foreach ($list[2] as $item) {
                    $keyword = $this->compileStringContent($this->coerceString($item));

                    $with[$keyword] = true;
                }
            }

            if ($this->libMapHasKey([$withCondition, static::$without])) {
                $without = []; // cancel the default
                $list = $this->coerceList($this->libMapGet([$withCondition, static::$without]));

                foreach ($list[2] as $item) {
                    $keyword = $this->compileStringContent($this->coerceString($item));

                    $without[$keyword] = true;
                }
            }
        }

        return [$with, $without];
    }

    /**
     * Filter env stack
     *
     * @param array $envs
     * @param array $with
     * @param array $without
     *
     * @return \ScssPhp\ScssPhp\Compiler\Environment
     */
    protected function filterWithWithout($envs, $with, $without)
    {
        $filtered = [];

        foreach ($envs as $e) {
            if ($e->block && ! $this->isWith($e->block, $with, $without)) {
                $ec = clone $e;
                $ec->block     = null;
                $ec->selectors = [];

                $filtered[] = $ec;
            } else {
                $filtered[] = $e;
            }
        }

        return $this->extractEnv($filtered);
    }

    /**
     * Filter WITH rules
     *
     * @param \ScssPhp\ScssPhp\Block|\ScssPhp\ScssPhp\Formatter\OutputBlock $block
     * @param array                                                         $with
     * @param array                                                         $without
     *
     * @return boolean
     */
    protected function isWith($block, $with, $without)
    {
        if (isset($block->type)) {
            if ($block->type === Type::T_MEDIA) {
                return $this->testWithWithout('media', $with, $without);
            }

            if ($block->type === Type::T_DIRECTIVE) {
                if (isset($block->name)) {
                    return $this->testWithWithout($block->name, $with, $without);
                } elseif (isset($block->selectors) && preg_match(',@(\w+),ims', json_encode($block->selectors), $m)) {
                    return $this->testWithWithout($m[1], $with, $without);
                } else {
                    return $this->testWithWithout('???', $with, $without);
                }
            }
        } elseif (isset($block->selectors)) {
            // a selector starting with number is a keyframe rule
            if (\count($block->selectors)) {
                $s = reset($block->selectors);

                while (\is_array($s)) {
                    $s = reset($s);
                }

                if (\is_object($s) && $s instanceof Node\Number) {
                    return $this->testWithWithout('keyframes', $with, $without);
                }
            }

            return $this->testWithWithout('rule', $with, $without);
        }

        return true;
    }

    /**
     * Test a single type of block against with/without lists
     *
     * @param string $what
     * @param array  $with
     * @param array  $without
     *
     * @return boolean
     *   true if the block should be kept, false to reject
     */
    protected function testWithWithout($what, $with, $without)
    {

        // if without, reject only if in the list (or 'all' is in the list)
        if (\count($without)) {
            return (isset($without[$what]) || isset($without['all'])) ? false : true;
        }

        // otherwise reject all what is not in the with list
        return (isset($with[$what]) || isset($with['all'])) ? true : false;
    }


    /**
     * Compile keyframe block
     *
     * @param \ScssPhp\ScssPhp\Block $block
     * @param array                  $selectors
     */
    protected function compileKeyframeBlock(Block $block, $selectors)
    {
        $env = $this->pushEnv($block);

        $envs = $this->compactEnv($env);

        $this->env = $this->extractEnv(array_filter($envs, function (Environment $e) {
            return ! isset($e->block->selectors);
        }));

        $this->scope = $this->makeOutputBlock($block->type, $selectors);
        $this->scope->depth = 1;
        $this->scope->parent->children[] = $this->scope;

        $this->compileChildrenNoReturn($block->children, $this->scope);

        $this->scope = $this->scope->parent;
        $this->env   = $this->extractEnv($envs);

        $this->popEnv();
    }

    /**
     * Compile nested properties lines
     *
     * @param \ScssPhp\ScssPhp\Block                 $block
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     */
    protected function compileNestedPropertiesBlock(Block $block, OutputBlock $out)
    {
        $prefix = $this->compileValue($block->prefix) . '-';

        $nested = $this->makeOutputBlock($block->type);
        $nested->parent = $out;

        if ($block->hasValue) {
            $nested->depth = $out->depth + 1;
        }

        $out->children[] = $nested;

        foreach ($block->children as $child) {
            switch ($child[0]) {
                case Type::T_ASSIGN:
                    array_unshift($child[1][2], $prefix);
                    break;

                case Type::T_NESTED_PROPERTY:
                    array_unshift($child[1]->prefix[2], $prefix);
                    break;
            }

            $this->compileChild($child, $nested);
        }
    }

    /**
     * Compile nested block
     *
     * @param \ScssPhp\ScssPhp\Block $block
     * @param array                  $selectors
     */
    protected function compileNestedBlock(Block $block, $selectors)
    {
        $this->pushEnv($block);

        $this->scope = $this->makeOutputBlock($block->type, $selectors);
        $this->scope->parent->children[] = $this->scope;

        // wrap assign children in a block
        // except for @font-face
        if ($block->type !== Type::T_DIRECTIVE || $block->name !== "font-face") {
            // need wrapping?
            $needWrapping = false;

            foreach ($block->children as $child) {
                if ($child[0] === Type::T_ASSIGN) {
                    $needWrapping = true;
                    break;
                }
            }

            if ($needWrapping) {
                $wrapped = new Block;
                $wrapped->sourceName   = $block->sourceName;
                $wrapped->sourceIndex  = $block->sourceIndex;
                $wrapped->sourceLine   = $block->sourceLine;
                $wrapped->sourceColumn = $block->sourceColumn;
                $wrapped->selectors    = [];
                $wrapped->comments     = [];
                $wrapped->parent       = $block;
                $wrapped->children     = $block->children;
                $wrapped->selfParent   = $block->selfParent;

                $block->children = [[Type::T_BLOCK, $wrapped]];
            }
        }

        $this->compileChildrenNoReturn($block->children, $this->scope);

        $this->scope = $this->scope->parent;

        $this->popEnv();
    }

    /**
     * Recursively compiles a block.
     *
     * A block is analogous to a CSS block in most cases. A single SCSS document
     * is encapsulated in a block when parsed, but it does not have parent tags
     * so all of its children appear on the root level when compiled.
     *
     * Blocks are made up of selectors and children.
     *
     * The children of a block are just all the blocks that are defined within.
     *
     * Compiling the block involves pushing a fresh environment on the stack,
     * and iterating through the props, compiling each one.
     *
     * @see Compiler::compileChild()
     *
     * @param \ScssPhp\ScssPhp\Block $block
     */
    protected function compileBlock(Block $block)
    {
        $env = $this->pushEnv($block);
        $env->selectors = $this->evalSelectors($block->selectors);

        $out = $this->makeOutputBlock(null);

        if (isset($this->lineNumberStyle) && \count($env->selectors) && \count($block->children)) {
            $annotation = $this->makeOutputBlock(Type::T_COMMENT);
            $annotation->depth = 0;

            $file = $this->sourceNames[$block->sourceIndex];
            $line = $block->sourceLine;

            switch ($this->lineNumberStyle) {
                case static::LINE_COMMENTS:
                    $annotation->lines[] = '/* line ' . $line
                                         . ($file ? ', ' . $file : '')
                                         . ' */';
                    break;

                case static::DEBUG_INFO:
                    $annotation->lines[] = '@media -sass-debug-info{'
                                         . ($file ? 'filename{font-family:"' . $file . '"}' : '')
                                         . 'line{font-family:' . $line . '}}';
                    break;
            }

            $this->scope->children[] = $annotation;
        }

        $this->scope->children[] = $out;

        if (\count($block->children)) {
            $out->selectors = $this->multiplySelectors($env, $block->selfParent);

            // propagate selfParent to the children where they still can be useful
            $selfParentSelectors = null;

            if (isset($block->selfParent->selectors)) {
                $selfParentSelectors = $block->selfParent->selectors;
                $block->selfParent->selectors = $out->selectors;
            }

            $this->compileChildrenNoReturn($block->children, $out, $block->selfParent);

            // and revert for the following children of the same block
            if ($selfParentSelectors) {
                $block->selfParent->selectors = $selfParentSelectors;
            }
        }

        $this->popEnv();
    }


    /**
     * Compile the value of a comment that can have interpolation
     *
     * @param array   $value
     * @param boolean $pushEnv
     *
     * @return array|mixed|string
     */
    protected function compileCommentValue($value, $pushEnv = false)
    {
        $c = $value[1];

        if (isset($value[2])) {
            if ($pushEnv) {
                $this->pushEnv();
            }

            $ignoreCallStackMessage = $this->ignoreCallStackMessage;
            $this->ignoreCallStackMessage = true;

            try {
                $c = $this->compileValue($value[2]);
            } catch (\Exception $e) {
                // ignore error in comment compilation which are only interpolation
            }

            $this->ignoreCallStackMessage = $ignoreCallStackMessage;

            if ($pushEnv) {
                $this->popEnv();
            }
        }

        return $c;
    }

    /**
     * Compile root level comment
     *
     * @param array $block
     */
    protected function compileComment($block)
    {
        $out = $this->makeOutputBlock(Type::T_COMMENT);
        $out->lines[] = $this->compileCommentValue($block, true);

        $this->scope->children[] = $out;
    }

    /**
     * Evaluate selectors
     *
     * @param array $selectors
     *
     * @return array
     */
    protected function evalSelectors($selectors)
    {
        $this->shouldEvaluate = false;

        $selectors = array_map([$this, 'evalSelector'], $selectors);

        // after evaluating interpolates, we might need a second pass
        if ($this->shouldEvaluate) {
            $selectors = $this->revertSelfSelector($selectors);
            $buffer    = $this->collapseSelectors($selectors);
            $parser    = $this->parserFactory(__METHOD__);

            if ($parser->parseSelector($buffer, $newSelectors)) {
                $selectors = array_map([$this, 'evalSelector'], $newSelectors);
            }
        }

        return $selectors;
    }

    /**
     * Evaluate selector
     *
     * @param array $selector
     *
     * @return array
     */
    protected function evalSelector($selector)
    {
        return array_map([$this, 'evalSelectorPart'], $selector);
    }

    /**
     * Evaluate selector part; replaces all the interpolates, stripping quotes
     *
     * @param array $part
     *
     * @return array
     */
    protected function evalSelectorPart($part)
    {
        foreach ($part as &$p) {
            if (\is_array($p) && ($p[0] === Type::T_INTERPOLATE || $p[0] === Type::T_STRING)) {
                $p = $this->compileValue($p);

                // force re-evaluation
                if (strpos($p, '&') !== false || strpos($p, ',') !== false) {
                    $this->shouldEvaluate = true;
                }
            } elseif (\is_string($p) && \strlen($p) >= 2 &&
                ($first = $p[0]) && ($first === '"' || $first === "'") &&
                substr($p, -1) === $first
            ) {
                $p = substr($p, 1, -1);
            }
        }

        return $this->flattenSelectorSingle($part);
    }

    /**
     * Collapse selectors
     *
     * @param array   $selectors
     * @param boolean $selectorFormat
     *   if false return a collapsed string
     *   if true return an array description of a structured selector
     *
     * @return string
     */
    protected function collapseSelectors($selectors, $selectorFormat = false)
    {
        $parts = [];

        foreach ($selectors as $selector) {
            $output = [];
            $glueNext = false;

            foreach ($selector as $node) {
                $compound = '';

                array_walk_recursive(
                    $node,
                    function ($value, $key) use (&$compound) {
                        $compound .= $value;
                    }
                );

                if ($selectorFormat && $this->isImmediateRelationshipCombinator($compound)) {
                    if (\count($output)) {
                        $output[\count($output) - 1] .= ' ' . $compound;
                    } else {
                        $output[] = $compound;
                    }

                    $glueNext = true;
                } elseif ($glueNext) {
                    $output[\count($output) - 1] .= ' ' . $compound;
                    $glueNext = false;
                } else {
                    $output[] = $compound;
                }
            }

            if ($selectorFormat) {
                foreach ($output as &$o) {
                    $o = [Type::T_STRING, '', [$o]];
                }

                $output = [Type::T_LIST, ' ', $output];
            } else {
                $output = implode(' ', $output);
            }

            $parts[] = $output;
        }

        if ($selectorFormat) {
            $parts = [Type::T_LIST, ',', $parts];
        } else {
            $parts = implode(', ', $parts);
        }

        return $parts;
    }

    /**
     * Parse down the selector and revert [self] to "&" before a reparsing
     *
     * @param array $selectors
     *
     * @return array
     */
    protected function revertSelfSelector($selectors)
    {
        foreach ($selectors as &$part) {
            if (\is_array($part)) {
                if ($part === [Type::T_SELF]) {
                    $part = '&';
                } else {
                    $part = $this->revertSelfSelector($part);
                }
            }
        }

        return $selectors;
    }

    /**
     * Flatten selector single; joins together .classes and #ids
     *
     * @param array $single
     *
     * @return array
     */
    protected function flattenSelectorSingle($single)
    {
        $joined = [];

        foreach ($single as $part) {
            if (empty($joined) ||
                ! \is_string($part) ||
                preg_match('/[\[.:#%]/', $part)
            ) {
                $joined[] = $part;
                continue;
            }

            if (\is_array(end($joined))) {
                $joined[] = $part;
            } else {
                $joined[\count($joined) - 1] .= $part;
            }
        }

        return $joined;
    }

    /**
     * Compile selector to string; self(&) should have been replaced by now
     *
     * @param string|array $selector
     *
     * @return string
     */
    protected function compileSelector($selector)
    {
        if (! \is_array($selector)) {
            return $selector; // media and the like
        }

        return implode(
            ' ',
            array_map(
                [$this, 'compileSelectorPart'],
                $selector
            )
        );
    }

    /**
     * Compile selector part
     *
     * @param array $piece
     *
     * @return string
     */
    protected function compileSelectorPart($piece)
    {
        foreach ($piece as &$p) {
            if (! \is_array($p)) {
                continue;
            }

            switch ($p[0]) {
                case Type::T_SELF:
                    $p = '&';
                    break;

                default:
                    $p = $this->compileValue($p);
                    break;
            }
        }

        return implode($piece);
    }

    /**
     * Has selector placeholder?
     *
     * @param array $selector
     *
     * @return boolean
     */
    protected function hasSelectorPlaceholder($selector)
    {
        if (! \is_array($selector)) {
            return false;
        }

        foreach ($selector as $parts) {
            foreach ($parts as $part) {
                if (\strlen($part) && '%' === $part[0]) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function pushCallStack($name = '')
    {
        $this->callStack[] = [
          'n' => $name,
          Parser::SOURCE_INDEX => $this->sourceIndex,
          Parser::SOURCE_LINE => $this->sourceLine,
          Parser::SOURCE_COLUMN => $this->sourceColumn
        ];

        // infinite calling loop
        if (\count($this->callStack) > 25000) {
            // not displayed but you can var_dump it to deep debug
            $msg = $this->callStackMessage(true, 100);
            $msg = "Infinite calling loop";

            $this->throwError($msg);
        }
    }

    protected function popCallStack()
    {
        array_pop($this->callStack);
    }

    /**
     * Compile children and return result
     *
     * @param array                                  $stms
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     * @param string                                 $traceName
     *
     * @return array|null
     */
    protected function compileChildren($stms, OutputBlock $out, $traceName = '')
    {
        $this->pushCallStack($traceName);

        foreach ($stms as $stm) {
            $ret = $this->compileChild($stm, $out);

            if (isset($ret)) {
                $this->popCallStack();

                return $ret;
            }
        }

        $this->popCallStack();

        return null;
    }

    /**
     * Compile children and throw exception if unexpected @return
     *
     * @param array                                  $stms
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     * @param \ScssPhp\ScssPhp\Block                 $selfParent
     * @param string                                 $traceName
     *
     * @throws \Exception
     */
    protected function compileChildrenNoReturn($stms, OutputBlock $out, $selfParent = null, $traceName = '')
    {
        $this->pushCallStack($traceName);

        foreach ($stms as $stm) {
            if ($selfParent && isset($stm[1]) && \is_object($stm[1]) && $stm[1] instanceof Block) {
                $stm[1]->selfParent = $selfParent;
                $ret = $this->compileChild($stm, $out);
                $stm[1]->selfParent = null;
            } elseif ($selfParent && \in_array($stm[0], [TYPE::T_INCLUDE, TYPE::T_EXTEND])) {
                $stm['selfParent'] = $selfParent;
                $ret = $this->compileChild($stm, $out);
                unset($stm['selfParent']);
            } else {
                $ret = $this->compileChild($stm, $out);
            }

            if (isset($ret)) {
                $this->throwError('@return may only be used within a function');
                $this->popCallStack();

                return;
            }
        }

        $this->popCallStack();
    }


    /**
     * evaluate media query : compile internal value keeping the structure inchanged
     *
     * @param array $queryList
     *
     * @return array
     */
    protected function evaluateMediaQuery($queryList)
    {
        static $parser = null;

        $outQueryList = [];

        foreach ($queryList as $kql => $query) {
            $shouldReparse = false;

            foreach ($query as $kq => $q) {
                for ($i = 1; $i < \count($q); $i++) {
                    $value = $this->compileValue($q[$i]);

                    // the parser had no mean to know if media type or expression if it was an interpolation
                    // so you need to reparse if the T_MEDIA_TYPE looks like anything else a media type
                    if ($q[0] == Type::T_MEDIA_TYPE &&
                        (strpos($value, '(') !== false ||
                        strpos($value, ')') !== false ||
                        strpos($value, ':') !== false ||
                        strpos($value, ',') !== false)
                    ) {
                        $shouldReparse = true;
                    }

                    $queryList[$kql][$kq][$i] = [Type::T_KEYWORD, $value];
                }
            }

            if ($shouldReparse) {
                if (\is_null($parser)) {
                    $parser = $this->parserFactory(__METHOD__);
                }

                $queryString = $this->compileMediaQuery([$queryList[$kql]]);
                $queryString = reset($queryString);

                if (strpos($queryString, '@media ') === 0) {
                    $queryString = substr($queryString, 7);
                    $queries = [];

                    if ($parser->parseMediaQueryList($queryString, $queries)) {
                        $queries = $this->evaluateMediaQuery($queries[2]);

                        while (\count($queries)) {
                            $outQueryList[] = array_shift($queries);
                        }

                        continue;
                    }
                }
            }

            $outQueryList[] = $queryList[$kql];
        }

        return $outQueryList;
    }

    /**
     * Compile media query
     *
     * @param array $queryList
     *
     * @return array
     */
    protected function compileMediaQuery($queryList)
    {
        $start   = '@media ';
        $default = trim($start);
        $out     = [];
        $current = "";

        foreach ($queryList as $query) {
            $type = null;
            $parts = [];

            $mediaTypeOnly = true;

            foreach ($query as $q) {
                if ($q[0] !== Type::T_MEDIA_TYPE) {
                    $mediaTypeOnly = false;
                    break;
                }
            }

            foreach ($query as $q) {
                switch ($q[0]) {
                    case Type::T_MEDIA_TYPE:
                        $newType = array_map([$this, 'compileValue'], \array_slice($q, 1));

                        // combining not and anything else than media type is too risky and should be avoided
                        if (! $mediaTypeOnly) {
                            if (\in_array(Type::T_NOT, $newType) || ($type && \in_array(Type::T_NOT, $type) )) {
                                if ($type) {
                                    array_unshift($parts, implode(' ', array_filter($type)));
                                }

                                if (! empty($parts)) {
                                    if (\strlen($current)) {
                                        $current .= $this->formatter->tagSeparator;
                                    }

                                    $current .= implode(' and ', $parts);
                                }

                                if ($current) {
                                    $out[] = $start . $current;
                                }

                                $current = "";
                                $type    = null;
                                $parts   = [];
                            }
                        }

                        if ($newType === ['all'] && $default) {
                            $default = $start . 'all';
                        }

                        // all can be safely ignored and mixed with whatever else
                        if ($newType !== ['all']) {
                            if ($type) {
                                $type = $this->mergeMediaTypes($type, $newType);

                                if (empty($type)) {
                                    // merge failed : ignore this query that is not valid, skip to the next one
                                    $parts = [];
                                    $default = ''; // if everything fail, no @media at all
                                    continue 3;
                                }
                            } else {
                                $type = $newType;
                            }
                        }
                        break;

                    case Type::T_MEDIA_EXPRESSION:
                        if (isset($q[2])) {
                            $parts[] = '('
                                . $this->compileValue($q[1])
                                . $this->formatter->assignSeparator
                                . $this->compileValue($q[2])
                                . ')';
                        } else {
                            $parts[] = '('
                                . $this->compileValue($q[1])
                                . ')';
                        }
                        break;

                    case Type::T_MEDIA_VALUE:
                        $parts[] = $this->compileValue($q[1]);
                        break;
                }
            }

            if ($type) {
                array_unshift($parts, implode(' ', array_filter($type)));
            }

            if (! empty($parts)) {
                if (\strlen($current)) {
                    $current .= $this->formatter->tagSeparator;
                }

                $current .= implode(' and ', $parts);
            }
        }

        if ($current) {
            $out[] = $start . $current;
        }

        // no @media type except all, and no conflict?
        if (! $out && $default) {
            $out[] = $default;
        }

        return $out;
    }

    /**
     * Merge direct relationships between selectors
     *
     * @param array $selectors1
     * @param array $selectors2
     *
     * @return array
     */
    protected function mergeDirectRelationships($selectors1, $selectors2)
    {
        if (empty($selectors1) || empty($selectors2)) {
            return array_merge($selectors1, $selectors2);
        }

        $part1 = end($selectors1);
        $part2 = end($selectors2);

        if (! $this->isImmediateRelationshipCombinator($part1[0]) && $part1 !== $part2) {
            return array_merge($selectors1, $selectors2);
        }

        $merged = [];

        do {
            $part1 = array_pop($selectors1);
            $part2 = array_pop($selectors2);

            if (! $this->isImmediateRelationshipCombinator($part1[0]) && $part1 !== $part2) {
                if ($this->isImmediateRelationshipCombinator(reset($merged)[0])) {
                    array_unshift($merged, [$part1[0] . $part2[0]]);
                    $merged = array_merge($selectors1, $selectors2, $merged);
                } else {
                    $merged = array_merge($selectors1, [$part1], $selectors2, [$part2], $merged);
                }

                break;
            }

            array_unshift($merged, $part1);
        } while (! empty($selectors1) && ! empty($selectors2));

        return $merged;
    }

    /**
     * Merge media types
     *
     * @param array $type1
     * @param array $type2
     *
     * @return array|null
     */
    protected function mergeMediaTypes($type1, $type2)
    {
        if (empty($type1)) {
            return $type2;
        }

        if (empty($type2)) {
            return $type1;
        }

        if (\count($type1) > 1) {
            $m1 = strtolower($type1[0]);
            $t1 = strtolower($type1[1]);
        } else {
            $m1 = '';
            $t1 = strtolower($type1[0]);
        }

        if (\count($type2) > 1) {
            $m2 = strtolower($type2[0]);
            $t2 = strtolower($type2[1]);
        } else {
            $m2 = '';
            $t2 = strtolower($type2[0]);
        }

        if (($m1 === Type::T_NOT) ^ ($m2 === Type::T_NOT)) {
            if ($t1 === $t2) {
                return null;
            }

            return [
                $m1 === Type::T_NOT ? $m2 : $m1,
                $m1 === Type::T_NOT ? $t2 : $t1,
            ];
        }

        if ($m1 === Type::T_NOT && $m2 === Type::T_NOT) {
            // CSS has no way of representing "neither screen nor print"
            if ($t1 !== $t2) {
                return null;
            }

            return [Type::T_NOT, $t1];
        }

        if ($t1 !== $t2) {
            return null;
        }

        // t1 == t2, neither m1 nor m2 are "not"
        return [empty($m1)? $m2 : $m1, $t1];
    }

    /**
     * Compile import; returns true if the value was something that could be imported
     *
     * @param array                                  $rawPath
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     * @param boolean                                $once
     *
     * @return boolean
     */
    protected function compileImport($rawPath, OutputBlock $out, $once = false)
    {
        if ($rawPath[0] === Type::T_STRING) {
            $path = $this->compileStringContent($rawPath);

            if ($path = $this->findImport($path)) {
                if (! $once || ! \in_array($path, $this->importedFiles)) {
                    $this->importFile($path, $out);
                    $this->importedFiles[] = $path;
                }

                return true;
            }

            $this->appendRootDirective('@import ' . $this->compileValue($rawPath). ';', $out);

            return false;
        }

        if ($rawPath[0] === Type::T_LIST) {
            // handle a list of strings
            if (\count($rawPath[2]) === 0) {
                return false;
            }

            foreach ($rawPath[2] as $path) {
                if ($path[0] !== Type::T_STRING) {
                    $this->appendRootDirective('@import ' . $this->compileValue($rawPath) . ';', $out);

                    return false;
                }
            }

            foreach ($rawPath[2] as $path) {
                $this->compileImport($path, $out, $once);
            }

            return true;
        }

        $this->appendRootDirective('@import ' . $this->compileValue($rawPath) . ';', $out);

        return false;
    }


    /**
     * Append a root directive like @import or @charset as near as the possible from the source code
     * (keeping before comments, @import and @charset coming before in the source code)
     *
     * @param string                                        $line
     * @param @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     * @param array                                         $allowed
     */
    protected function appendRootDirective($line, $out, $allowed = [Type::T_COMMENT])
    {
        $root = $out;

        while ($root->parent) {
            $root = $root->parent;
        }

        $i = 0;

        while ($i < \count($root->children)) {
            if (! isset($root->children[$i]->type) || ! \in_array($root->children[$i]->type, $allowed)) {
                break;
            }

            $i++;
        }

        // remove incompatible children from the bottom of the list
        $saveChildren = [];

        while ($i < \count($root->children)) {
            $saveChildren[] = array_pop($root->children);
        }

        // insert the directive as a comment
        $child = $this->makeOutputBlock(Type::T_COMMENT);
        $child->lines[]      = $line;
        $child->sourceName   = $this->sourceNames[$this->sourceIndex];
        $child->sourceLine   = $this->sourceLine;
        $child->sourceColumn = $this->sourceColumn;

        $root->children[] = $child;

        // repush children
        while (\count($saveChildren)) {
            $root->children[] = array_pop($saveChildren);
        }
    }

    /**
     * Append lines to the current output block:
     * directly to the block or through a child if necessary
     *
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     * @param string                                 $type
     * @param string|mixed                           $line
     */
    protected function appendOutputLine(OutputBlock $out, $type, $line)
    {
        $outWrite = &$out;

        if ($type === Type::T_COMMENT) {
            $parent = $out->parent;

            if (end($parent->children) !== $out) {
                $outWrite = &$parent->children[\count($parent->children) - 1];
            }
        }

        // check if it's a flat output or not
        if (\count($out->children)) {
            $lastChild = &$out->children[\count($out->children) - 1];

            if ($lastChild->depth === $out->depth &&
                \is_null($lastChild->selectors) &&
                ! \count($lastChild->children)
            ) {
                $outWrite = $lastChild;
            } else {
                $nextLines = $this->makeOutputBlock($type);
                $nextLines->parent = $out;
                $nextLines->depth  = $out->depth;

                $out->children[] = $nextLines;
                $outWrite = &$nextLines;
            }
        }

        $outWrite->lines[] = $line;
    }

    /**
     * Compile child; returns a value to halt execution
     *
     * @param array                                  $child
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     *
     * @return array
     */
    protected function compileChild($child, OutputBlock $out)
    {
        if (isset($child[Parser::SOURCE_LINE])) {
            $this->sourceIndex  = isset($child[Parser::SOURCE_INDEX]) ? $child[Parser::SOURCE_INDEX] : null;
            $this->sourceLine   = isset($child[Parser::SOURCE_LINE]) ? $child[Parser::SOURCE_LINE] : -1;
            $this->sourceColumn = isset($child[Parser::SOURCE_COLUMN]) ? $child[Parser::SOURCE_COLUMN] : -1;
        } elseif (\is_array($child) && isset($child[1]->sourceLine)) {
            $this->sourceIndex  = $child[1]->sourceIndex;
            $this->sourceLine   = $child[1]->sourceLine;
            $this->sourceColumn = $child[1]->sourceColumn;
        } elseif (! empty($out->sourceLine) && ! empty($out->sourceName)) {
            $this->sourceLine   = $out->sourceLine;
            $this->sourceIndex  = array_search($out->sourceName, $this->sourceNames);
            $this->sourceColumn = $out->sourceColumn;

            if ($this->sourceIndex === false) {
                $this->sourceIndex = null;
            }
        }

        switch ($child[0]) {
            case Type::T_SCSSPHP_IMPORT_ONCE:
                $rawPath = $this->reduce($child[1]);

                $this->compileImport($rawPath, $out, true);
                break;

            case Type::T_IMPORT:
                $rawPath = $this->reduce($child[1]);

                $this->compileImport($rawPath, $out);
                break;

            case Type::T_DIRECTIVE:
                $this->compileDirective($child[1], $out);
                break;

            case Type::T_AT_ROOT:
                $this->compileAtRoot($child[1]);
                break;

            case Type::T_MEDIA:
                $this->compileMedia($child[1]);
                break;

            case Type::T_BLOCK:
                $this->compileBlock($child[1]);
                break;

            case Type::T_CHARSET:
                if (! $this->charsetSeen) {
                    $this->charsetSeen = true;
                    $this->appendRootDirective('@charset ' . $this->compileValue($child[1]) . ';', $out);
                }
                break;

            case Type::T_CUSTOM_PROPERTY:
                list(, $name, $value) = $child;
                $compiledName = $this->compileValue($name);

                // if the value reduces to null from something else then
                // the property should be discarded
                if ($value[0] !== Type::T_NULL) {
                    $value = $this->reduce($value);

                    if ($value[0] === Type::T_NULL || $value === static::$nullString) {
                        break;
                    }
                }

                $compiledValue = $this->compileValue($value);

                $line = $this->formatter->customProperty(
                    $compiledName,
                    $compiledValue
                );

                $this->appendOutputLine($out, Type::T_ASSIGN, $line);
                break;

            case Type::T_ASSIGN:
                list(, $name, $value) = $child;

                if ($name[0] === Type::T_VARIABLE) {
                    $flags     = isset($child[3]) ? $child[3] : [];
                    $isDefault = \in_array('!default', $flags);
                    $isGlobal  = \in_array('!global', $flags);

                    if ($isGlobal) {
                        $this->set($name[1], $this->reduce($value), false, $this->rootEnv, $value);
                        break;
                    }

                    $shouldSet = $isDefault &&
                        (\is_null($result = $this->get($name[1], false)) ||
                        $result === static::$null);

                    if (! $isDefault || $shouldSet) {
                        $this->set($name[1], $this->reduce($value), true, null, $value);
                    }
                    break;
                }

                $compiledName = $this->compileValue($name);

                // handle shorthand syntaxes : size / line-height...
                if (\in_array($compiledName, ['font', 'grid-row', 'grid-column', 'border-radius'])) {
                    if ($value[0] === Type::T_VARIABLE) {
                        // if the font value comes from variable, the content is already reduced
                        // (i.e., formulas were already calculated), so we need the original unreduced value
                        $value = $this->get($value[1], true, null, true);
                    }

                    $shorthandValue=&$value;

                    $shorthandDividerNeedsUnit = false;
                    $maxListElements           = null;
                    $maxShorthandDividers      = 1;

                    switch ($compiledName) {
                        case 'border-radius':
                            $maxListElements = 4;
                            $shorthandDividerNeedsUnit = true;
                            break;
                    }

                    if ($compiledName === 'font' and $value[0] === Type::T_LIST && $value[1]==',') {
                        // this is the case if more than one font is given: example: "font: 400 1em/1.3 arial,helvetica"
                        // we need to handle the first list element
                        $shorthandValue=&$value[2][0];
                    }

                    if ($shorthandValue[0] === Type::T_EXPRESSION && $shorthandValue[1] === '/') {
                        $revert = true;

                        if ($shorthandDividerNeedsUnit) {
                            $divider = $shorthandValue[3];

                            if (\is_array($divider)) {
                                $divider = $this->reduce($divider, true);
                            }

                            if (\intval($divider->dimension) and ! \count($divider->units)) {
                                $revert = false;
                            }
                        }

                        if ($revert) {
                            $shorthandValue = $this->expToString($shorthandValue);
                        }
                    } elseif ($shorthandValue[0] === Type::T_LIST) {
                        foreach ($shorthandValue[2] as &$item) {
                            if ($item[0] === Type::T_EXPRESSION && $item[1] === '/') {
                                if ($maxShorthandDividers > 0) {
                                    $revert = true;
                                    // if the list of values is too long, this has to be a shorthand,
                                    // otherwise it could be a real division
                                    if (\is_null($maxListElements) or \count($shorthandValue[2]) <= $maxListElements) {
                                        if ($shorthandDividerNeedsUnit) {
                                            $divider = $item[3];

                                            if (\is_array($divider)) {
                                                $divider = $this->reduce($divider, true);
                                            }

                                            if (\intval($divider->dimension) and ! \count($divider->units)) {
                                                $revert = false;
                                            }
                                        }
                                    }

                                    if ($revert) {
                                        $item = $this->expToString($item);
                                        $maxShorthandDividers--;
                                    }
                                }
                            }
                        }
                    }
                }

                // if the value reduces to null from something else then
                // the property should be discarded
                if ($value[0] !== Type::T_NULL) {
                    $value = $this->reduce($value);

                    if ($value[0] === Type::T_NULL || $value === static::$nullString) {
                        break;
                    }
                }

                $compiledValue = $this->compileValue($value);

                // ignore empty value
                if (\strlen($compiledValue)) {
                    $line = $this->formatter->property(
                        $compiledName,
                        $compiledValue
                    );
                    $this->appendOutputLine($out, Type::T_ASSIGN, $line);
                }
                break;

            case Type::T_COMMENT:
                if ($out->type === Type::T_ROOT) {
                    $this->compileComment($child);
                    break;
                }

                $line = $this->compileCommentValue($child, true);
                $this->appendOutputLine($out, Type::T_COMMENT, $line);
                break;

            case Type::T_MIXIN:
            case Type::T_FUNCTION:
                list(, $block) = $child;
                // the block need to be able to go up to it's parent env to resolve vars
                $block->parentEnv = $this->getStoreEnv();
                $this->set(static::$namespaces[$block->type] . $block->name, $block, true);
                break;

            case Type::T_EXTEND:
                foreach ($child[1] as $sel) {
                    $results = $this->evalSelectors([$sel]);

                    foreach ($results as $result) {
                        // only use the first one
                        $result = current($result);
                        $selectors = $out->selectors;

                        if (! $selectors && isset($child['selfParent'])) {
                            $selectors = $this->multiplySelectors($this->env, $child['selfParent']);
                        }

                        $this->pushExtends($result, $selectors, $child);
                    }
                }
                break;

            case Type::T_IF:
                list(, $if) = $child;

                if ($this->isTruthy($this->reduce($if->cond, true))) {
                    return $this->compileChildren($if->children, $out);
                }

                foreach ($if->cases as $case) {
                    if ($case->type === Type::T_ELSE ||
                        $case->type === Type::T_ELSEIF && $this->isTruthy($this->reduce($case->cond))
                    ) {
                        return $this->compileChildren($case->children, $out);
                    }
                }
                break;

            case Type::T_EACH:
                list(, $each) = $child;

                $list = $this->coerceList($this->reduce($each->list), ',', true);

                $this->pushEnv();

                foreach ($list[2] as $item) {
                    if (\count($each->vars) === 1) {
                        $this->set($each->vars[0], $item, true);
                    } else {
                        list(,, $values) = $this->coerceList($item);

                        foreach ($each->vars as $i => $var) {
                            $this->set($var, isset($values[$i]) ? $values[$i] : static::$null, true);
                        }
                    }

                    $ret = $this->compileChildren($each->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            $store = $this->env->store;
                            $this->popEnv();
                            $this->backPropagateEnv($store, $each->vars);

                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }
                $store = $this->env->store;
                $this->popEnv();
                $this->backPropagateEnv($store, $each->vars);

                break;

            case Type::T_WHILE:
                list(, $while) = $child;

                while ($this->isTruthy($this->reduce($while->cond, true))) {
                    $ret = $this->compileChildren($while->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }
                break;

            case Type::T_FOR:
                list(, $for) = $child;

                $start = $this->reduce($for->start, true);
                $end   = $this->reduce($for->end, true);

                if (! ($start[2] == $end[2] || $end->unitless())) {
                    $this->throwError('Incompatible units: "%s" and "%s".', $start->unitStr(), $end->unitStr());

                    break;
                }

                $unit  = $start[2];
                $start = $start[1];
                $end   = $end[1];

                $d = $start < $end ? 1 : -1;

                $this->pushEnv();

                for (;;) {
                    if ((! $for->until && $start - $d == $end) ||
                        ($for->until && $start == $end)
                    ) {
                        break;
                    }

                    $this->set($for->var, new Node\Number($start, $unit));
                    $start += $d;

                    $ret = $this->compileChildren($for->children, $out);

                    if ($ret) {
                        if ($ret[0] !== Type::T_CONTROL) {
                            $store = $this->env->store;
                            $this->popEnv();
                            $this->backPropagateEnv($store, [$for->var]);
                            return $ret;
                        }

                        if ($ret[1]) {
                            break;
                        }
                    }
                }

                $store = $this->env->store;
                $this->popEnv();
                $this->backPropagateEnv($store, [$for->var]);

                break;

            case Type::T_BREAK:
                return [Type::T_CONTROL, true];

            case Type::T_CONTINUE:
                return [Type::T_CONTROL, false];

            case Type::T_RETURN:
                return $this->reduce($child[1], true);

            case Type::T_NESTED_PROPERTY:
                $this->compileNestedPropertiesBlock($child[1], $out);
                break;

            case Type::T_INCLUDE:
                // including a mixin
                list(, $name, $argValues, $content, $argUsing) = $child;

                $mixin = $this->get(static::$namespaces['mixin'] . $name, false);

                if (! $mixin) {
                    $this->throwError("Undefined mixin $name");
                    break;
                }

                $callingScope = $this->getStoreEnv();

                // push scope, apply args
                $this->pushEnv();
                $this->env->depth--;

                // Find the parent selectors in the env to be able to know what '&' refers to in the mixin
                // and assign this fake parent to childs
                $selfParent = null;

                if (isset($child['selfParent']) && isset($child['selfParent']->selectors)) {
                    $selfParent = $child['selfParent'];
                } else {
                    $parentSelectors = $this->multiplySelectors($this->env);

                    if ($parentSelectors) {
                        $parent = new Block();
                        $parent->selectors = $parentSelectors;

                        foreach ($mixin->children as $k => $child) {
                            if (isset($child[1]) && \is_object($child[1]) && $child[1] instanceof Block) {
                                $mixin->children[$k][1]->parent = $parent;
                            }
                        }
                    }
                }

                // clone the stored content to not have its scope spoiled by a further call to the same mixin
                // i.e., recursive @include of the same mixin
                if (isset($content)) {
                    $copyContent = clone $content;
                    $copyContent->scope = clone $callingScope;

                    $this->setRaw(static::$namespaces['special'] . 'content', $copyContent, $this->env);
                } else {
                    $this->setRaw(static::$namespaces['special'] . 'content', null, $this->env);
                }

                // save the "using" argument list for applying it to when "@content" is invoked
                if (isset($argUsing)) {
                    $this->setRaw(static::$namespaces['special'] . 'using', $argUsing, $this->env);
                } else {
                    $this->setRaw(static::$namespaces['special'] . 'using', null, $this->env);
                }

                if (isset($mixin->args)) {
                    $this->applyArguments($mixin->args, $argValues);
                }

                $this->env->marker = 'mixin';

                if (! empty($mixin->parentEnv)) {
                    $this->env->declarationScopeParent = $mixin->parentEnv;
                } else {
                    $this->throwError("@mixin $name() without parentEnv");
                }

                $this->compileChildrenNoReturn($mixin->children, $out, $selfParent, $this->env->marker . " " . $name);

                $this->popEnv();
                break;

            case Type::T_MIXIN_CONTENT:
                $env        = isset($this->storeEnv) ? $this->storeEnv : $this->env;
                $content    = $this->get(static::$namespaces['special'] . 'content', false, $env);
                $argUsing   = $this->get(static::$namespaces['special'] . 'using', false, $env);
                $argContent = $child[1];

                if (! $content) {
                    break;
                }

                $storeEnv = $this->storeEnv;
                $varsUsing = [];

                if (isset($argUsing) && isset($argContent)) {
                    // Get the arguments provided for the content with the names provided in the "using" argument list
                    $this->storeEnv = null;
                    $varsUsing = $this->applyArguments($argUsing, $argContent, false);
                }

                // restore the scope from the @content
                $this->storeEnv = $content->scope;

                // append the vars from using if any
                foreach ($varsUsing as $name => $val) {
                    $this->set($name, $val, true, $this->storeEnv);
                }

                $this->compileChildrenNoReturn($content->children, $out);

                $this->storeEnv = $storeEnv;
                break;

            case Type::T_DEBUG:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line  = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));

                fwrite($this->stderr, "File $fname on line $line DEBUG: $value\n");
                break;

            case Type::T_WARN:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line  = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));

                fwrite($this->stderr, "File $fname on line $line WARN: $value\n");
                break;

            case Type::T_ERROR:
                list(, $value) = $child;

                $fname = $this->sourceNames[$this->sourceIndex];
                $line  = $this->sourceLine;
                $value = $this->compileValue($this->reduce($value, true));

                $this->throwError("File $fname on line $line ERROR: $value\n");
                break;

            case Type::T_CONTROL:
                $this->throwError('@break/@continue not permitted in this scope');
                break;

            default:
                $this->throwError("unknown child type: $child[0]");
        }
    }

    /**
     * Reduce expression to string
     *
     * @param array $exp
     *
     * @return array
     */
    protected function expToString($exp)
    {
        list(, $op, $left, $right, /* $inParens */, $whiteLeft, $whiteRight) = $exp;

        $content = [$this->reduce($left)];

        if ($whiteLeft) {
            $content[] = ' ';
        }

        $content[] = $op;

        if ($whiteRight) {
            $content[] = ' ';
        }

        $content[] = $this->reduce($right);

        return [Type::T_STRING, '', $content];
    }

    /**
     * Is truthy?
     *
     * @param array $value
     *
     * @return boolean
     */
    protected function isTruthy($value)
    {
        return $value !== static::$false && $value !== static::$null;
    }

    /**
     * Is the value a direct relationship combinator?
     *
     * @param string $value
     *
     * @return boolean
     */
    protected function isImmediateRelationshipCombinator($value)
    {
        return $value === '>' || $value === '+' || $value === '~';
    }

    /**
     * Should $value cause its operand to eval
     *
     * @param array $value
     *
     * @return boolean
     */
    protected function shouldEval($value)
    {
        switch ($value[0]) {
            case Type::T_EXPRESSION:
                if ($value[1] === '/') {
                    return $this->shouldEval($value[2]) || $this->shouldEval($value[3]);
                }

                // fall-thru
            case Type::T_VARIABLE:
            case Type::T_FUNCTION_CALL:
                return true;
        }

        return false;
    }

    /**
     * Reduce value
     *
     * @param array   $value
     * @param boolean $inExp
     *
     * @return null|string|array|\ScssPhp\ScssPhp\Node\Number
     */
    protected function reduce($value, $inExp = false)
    {
        if (\is_null($value)) {
            return null;
        }

        switch ($value[0]) {
            case Type::T_EXPRESSION:
                list(, $op, $left, $right, $inParens) = $value;

                $opName = isset(static::$operatorNames[$op]) ? static::$operatorNames[$op] : $op;
                $inExp = $inExp || $this->shouldEval($left) || $this->shouldEval($right);

                $left = $this->reduce($left, true);

                if ($op !== 'and' && $op !== 'or') {
                    $right = $this->reduce($right, true);
                }

                // special case: looks like css shorthand
                if ($opName == 'div' && ! $inParens && ! $inExp && isset($right[2]) &&
                    (($right[0] !== Type::T_NUMBER && $right[2] != '') ||
                    ($right[0] === Type::T_NUMBER && ! $right->unitless()))
                ) {
                    return $this->expToString($value);
                }

                $left  = $this->coerceForExpression($left);
                $right = $this->coerceForExpression($right);
                $ltype = $left[0];
                $rtype = $right[0];

                $ucOpName = ucfirst($opName);
                $ucLType  = ucfirst($ltype);
                $ucRType  = ucfirst($rtype);

                // this tries:
                // 1. op[op name][left type][right type]
                // 2. op[left type][right type] (passing the op as first arg
                // 3. op[op name]
                $fn = "op${ucOpName}${ucLType}${ucRType}";

                if (\is_callable([$this, $fn]) ||
                    (($fn = "op${ucLType}${ucRType}") &&
                        \is_callable([$this, $fn]) &&
                        $passOp = true) ||
                    (($fn = "op${ucOpName}") &&
                        \is_callable([$this, $fn]) &&
                        $genOp = true)
                ) {
                    $coerceUnit = false;

                    if (! isset($genOp) &&
                        $left[0] === Type::T_NUMBER && $right[0] === Type::T_NUMBER
                    ) {
                        $coerceUnit = true;

                        switch ($opName) {
                            case 'mul':
                                $targetUnit = $left[2];

                                foreach ($right[2] as $unit => $exp) {
                                    $targetUnit[$unit] = (isset($targetUnit[$unit]) ? $targetUnit[$unit] : 0) + $exp;
                                }
                                break;

                            case 'div':
                                $targetUnit = $left[2];

                                foreach ($right[2] as $unit => $exp) {
                                    $targetUnit[$unit] = (isset($targetUnit[$unit]) ? $targetUnit[$unit] : 0) - $exp;
                                }
                                break;

                            case 'mod':
                                $targetUnit = $left[2];
                                break;

                            default:
                                $targetUnit = $left->unitless() ? $right[2] : $left[2];
                        }

                        $baseUnitLeft = $left->isNormalizable();
                        $baseUnitRight = $right->isNormalizable();

                        if ($baseUnitLeft && $baseUnitRight && $baseUnitLeft === $baseUnitRight) {
                            $left = $left->normalize();
                            $right = $right->normalize();
                        }
                        else {
                            if ($coerceUnit) {
                                $left = new Node\Number($left[1], []);
                            }
                        }
                    }

                    $shouldEval = $inParens || $inExp;

                    if (isset($passOp)) {
                        $out = $this->$fn($op, $left, $right, $shouldEval);
                    } else {
                        $out = $this->$fn($left, $right, $shouldEval);
                    }

                    if (isset($out)) {
                        if ($coerceUnit && $out[0] === Type::T_NUMBER) {
                            $out = $out->coerce($targetUnit);
                        }

                        return $out;
                    }
                }

                return $this->expToString($value);

            case Type::T_UNARY:
                list(, $op, $exp, $inParens) = $value;

                $inExp = $inExp || $this->shouldEval($exp);
                $exp = $this->reduce($exp);

                if ($exp[0] === Type::T_NUMBER) {
                    switch ($op) {
                        case '+':
                            return new Node\Number($exp[1], $exp[2]);

                        case '-':
                            return new Node\Number(-$exp[1], $exp[2]);
                    }
                }

                if ($op === 'not') {
                    if ($inExp || $inParens) {
                        if ($exp === static::$false || $exp === static::$null) {
                            return static::$true;
                        }

                        return static::$false;
                    }

                    $op = $op . ' ';
                }

                return [Type::T_STRING, '', [$op, $exp]];

            case Type::T_VARIABLE:
                return $this->reduce($this->get($value[1]));

            case Type::T_LIST:
                foreach ($value[2] as &$item) {
                    $item = $this->reduce($item);
                }

                return $value;

            case Type::T_MAP:
                foreach ($value[1] as &$item) {
                    $item = $this->reduce($item);
                }

                foreach ($value[2] as &$item) {
                    $item = $this->reduce($item);
                }

                return $value;

            case Type::T_STRING:
                foreach ($value[2] as &$item) {
                    if (\is_array($item) || $item instanceof \ArrayAccess) {
                        $item = $this->reduce($item);
                    }
                }

                return $value;

            case Type::T_INTERPOLATE:
                $value[1] = $this->reduce($value[1]);

                if ($inExp) {
                    return $value[1];
                }

                return $value;

            case Type::T_FUNCTION_CALL:
                return $this->fncall($value[1], $value[2]);

            case Type::T_SELF:
                $selfSelector = $this->multiplySelectors($this->env,!empty($this->env->block->selfParent) ? $this->env->block->selfParent : null);
                $selfSelector = $this->collapseSelectors($selfSelector, true);

                return $selfSelector;

            default:
                return $value;
        }
    }

    /**
     * Function caller
     *
     * @param string $name
     * @param array  $argValues
     *
     * @return array|null
     */
    protected function fncall($name, $argValues)
    {
        // SCSS @function
        if ($this->callScssFunction($name, $argValues, $returnValue)) {
            return $returnValue;
        }

        // native PHP functions
        if ($this->callNativeFunction($name, $argValues, $returnValue)) {
            return $returnValue;
        }

        // for CSS functions, simply flatten the arguments into a list
        $listArgs = [];

        foreach ((array) $argValues as $arg) {
            if (empty($arg[0])) {
                $listArgs[] = $this->reduce($arg[1]);
            }
        }

        return [Type::T_FUNCTION, $name, [Type::T_LIST, ',', $listArgs]];
    }

    /**
     * Normalize name
     *
     * @param string $name
     *
     * @return string
     */
    protected function normalizeName($name)
    {
        return str_replace('-', '_', $name);
    }

    /**
     * Normalize value
     *
     * @param array $value
     *
     * @return array
     */
    public function normalizeValue($value)
    {
        $value = $this->coerceForExpression($this->reduce($value));

        switch ($value[0]) {
            case Type::T_LIST:
                $value = $this->extractInterpolation($value);

                if ($value[0] !== Type::T_LIST) {
                    return [Type::T_KEYWORD, $this->compileValue($value)];
                }

                foreach ($value[2] as $key => $item) {
                    $value[2][$key] = $this->normalizeValue($item);
                }

                if (! empty($value['enclosing'])) {
                    unset($value['enclosing']);
                }

                return $value;

            case Type::T_STRING:
                return [$value[0], '"', [$this->compileStringContent($value)]];

            case Type::T_NUMBER:
                return $value->normalize();

            case Type::T_INTERPOLATE:
                return [Type::T_KEYWORD, $this->compileValue($value)];

            default:
                return $value;
        }
    }

    /**
     * Add numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    protected function opAddNumberNumber($left, $right)
    {
        return new Node\Number($left[1] + $right[1], $left[2]);
    }

    /**
     * Multiply numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    protected function opMulNumberNumber($left, $right)
    {
        return new Node\Number($left[1] * $right[1], $left[2]);
    }

    /**
     * Subtract numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    protected function opSubNumberNumber($left, $right)
    {
        return new Node\Number($left[1] - $right[1], $left[2]);
    }

    /**
     * Divide numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return array|\ScssPhp\ScssPhp\Node\Number
     */
    protected function opDivNumberNumber($left, $right)
    {
        if ($right[1] == 0) {
            return ($left[1] == 0) ? static::$NaN : static::$Infinity;
        }

        return new Node\Number($left[1] / $right[1], $left[2]);
    }

    /**
     * Mod numbers
     *
     * @param array $left
     * @param array $right
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    protected function opModNumberNumber($left, $right)
    {
        if ($right[1] == 0) {
            return static::$NaN;
        }

        return new Node\Number($left[1] % $right[1], $left[2]);
    }

    /**
     * Add strings
     *
     * @param array $left
     * @param array $right
     *
     * @return array|null
     */
    protected function opAdd($left, $right)
    {
        if ($strLeft = $this->coerceString($left)) {
            if ($right[0] === Type::T_STRING) {
                $right[1] = '';
            }

            $strLeft[2][] = $right;

            return $strLeft;
        }

        if ($strRight = $this->coerceString($right)) {
            if ($left[0] === Type::T_STRING) {
                $left[1] = '';
            }

            array_unshift($strRight[2], $left);

            return $strRight;
        }

        return null;
    }

    /**
     * Boolean and
     *
     * @param array   $left
     * @param array   $right
     * @param boolean $shouldEval
     *
     * @return array|null
     */
    protected function opAnd($left, $right, $shouldEval)
    {
        $truthy = ($left === static::$null || $right === static::$null) ||
                  ($left === static::$false || $left === static::$true) &&
                  ($right === static::$false || $right === static::$true);

        if (! $shouldEval) {
            if (! $truthy) {
                return null;
            }
        }

        if ($left !== static::$false && $left !== static::$null) {
            return $this->reduce($right, true);
        }

        return $left;
    }

    /**
     * Boolean or
     *
     * @param array   $left
     * @param array   $right
     * @param boolean $shouldEval
     *
     * @return array|null
     */
    protected function opOr($left, $right, $shouldEval)
    {
        $truthy = ($left === static::$null || $right === static::$null) ||
                  ($left === static::$false || $left === static::$true) &&
                  ($right === static::$false || $right === static::$true);

        if (! $shouldEval) {
            if (! $truthy) {
                return null;
            }
        }

        if ($left !== static::$false && $left !== static::$null) {
            return $left;
        }

        return $this->reduce($right, true);
    }

    /**
     * Compare colors
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opColorColor($op, $left, $right)
    {
        $out = [Type::T_COLOR];

        foreach ([1, 2, 3] as $i) {
            $lval = isset($left[$i]) ? $left[$i] : 0;
            $rval = isset($right[$i]) ? $right[$i] : 0;

            switch ($op) {
                case '+':
                    $out[] = $lval + $rval;
                    break;

                case '-':
                    $out[] = $lval - $rval;
                    break;

                case '*':
                    $out[] = $lval * $rval;
                    break;

                case '%':
                    $out[] = $lval % $rval;
                    break;

                case '/':
                    if ($rval == 0) {
                        $this->throwError("color: Can't divide by zero");
                        break 2;
                    }

                    $out[] = (int) ($lval / $rval);
                    break;

                case '==':
                    return $this->opEq($left, $right);

                case '!=':
                    return $this->opNeq($left, $right);

                default:
                    $this->throwError("color: unknown op $op");
                    break 2;
            }
        }

        if (isset($left[4])) {
            $out[4] = $left[4];
        } elseif (isset($right[4])) {
            $out[4] = $right[4];
        }

        return $this->fixColor($out);
    }

    /**
     * Compare color and number
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opColorNumber($op, $left, $right)
    {
        $value = $right[1];

        return $this->opColorColor(
            $op,
            $left,
            [Type::T_COLOR, $value, $value, $value]
        );
    }

    /**
     * Compare number and color
     *
     * @param string $op
     * @param array  $left
     * @param array  $right
     *
     * @return array
     */
    protected function opNumberColor($op, $left, $right)
    {
        $value = $left[1];

        return $this->opColorColor(
            $op,
            [Type::T_COLOR, $value, $value, $value],
            $right
        );
    }

    /**
     * Compare number1 == number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opEq($left, $right)
    {
        if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
            $lStr[1] = '';
            $rStr[1] = '';

            $left = $this->compileValue($lStr);
            $right = $this->compileValue($rStr);
        }

        return $this->toBool($left === $right);
    }

    /**
     * Compare number1 != number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opNeq($left, $right)
    {
        if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
            $lStr[1] = '';
            $rStr[1] = '';

            $left = $this->compileValue($lStr);
            $right = $this->compileValue($rStr);
        }

        return $this->toBool($left !== $right);
    }

    /**
     * Compare number1 >= number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opGteNumberNumber($left, $right)
    {
        return $this->toBool($left[1] >= $right[1]);
    }

    /**
     * Compare number1 > number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opGtNumberNumber($left, $right)
    {
        return $this->toBool($left[1] > $right[1]);
    }

    /**
     * Compare number1 <= number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opLteNumberNumber($left, $right)
    {
        return $this->toBool($left[1] <= $right[1]);
    }

    /**
     * Compare number1 < number2
     *
     * @param array $left
     * @param array $right
     *
     * @return array
     */
    protected function opLtNumberNumber($left, $right)
    {
        return $this->toBool($left[1] < $right[1]);
    }

    /**
     * Three-way comparison, aka spaceship operator
     *
     * @param array $left
     * @param array $right
     *
     * @return \ScssPhp\ScssPhp\Node\Number
     */
    protected function opCmpNumberNumber($left, $right)
    {
        $n = $left[1] - $right[1];

        return new Node\Number($n ? $n / abs($n) : 0, '');
    }

    /**
     * Cast to boolean
     *
     * @api
     *
     * @param mixed $thing
     *
     * @return array
     */
    public function toBool($thing)
    {
        return $thing ? static::$true : static::$false;
    }

    /**
     * Compiles a primitive value into a CSS property value.
     *
     * Values in scssphp are typed by being wrapped in arrays, their format is
     * typically:
     *
     *     array(type, contents [, additional_contents]*)
     *
     * The input is expected to be reduced. This function will not work on
     * things like expressions and variables.
     *
     * @api
     *
     * @param array $value
     *
     * @return string|array
     */
    public function compileValue($value)
    {
        $value = $this->reduce($value);

        switch ($value[0]) {
            case Type::T_KEYWORD:
                return $value[1];

            case Type::T_COLOR:
                // [1] - red component (either number for a %)
                // [2] - green component
                // [3] - blue component
                // [4] - optional alpha component
                list(, $r, $g, $b) = $value;

                $r = $this->compileRGBAValue($r);
                $g = $this->compileRGBAValue($g);
                $b = $this->compileRGBAValue($b);

                if (\count($value) === 5) {
                    $alpha = $this->compileRGBAValue($value[4], true);

                    if (! is_numeric($alpha) || $alpha < 1) {
                        $colorName = Colors::RGBaToColorName($r, $g, $b, $alpha);

                        if (! \is_null($colorName)) {
                            return $colorName;
                        }

                        if (is_numeric($alpha)) {
                            $a = new Node\Number($alpha, '');
                        } else {
                            $a = $alpha;
                        }

                        return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . $a . ')';
                    }
                }

                if (! is_numeric($r) || ! is_numeric($g) || ! is_numeric($b)) {
                    return 'rgb(' . $r . ', ' . $g . ', ' . $b . ')';
                }

                $colorName = Colors::RGBaToColorName($r, $g, $b);

                if (! \is_null($colorName)) {
                    return $colorName;
                }

                $h = sprintf('#%02x%02x%02x', $r, $g, $b);

                // Converting hex color to short notation (e.g. #003399 to #039)
                if ($h[1] === $h[2] && $h[3] === $h[4] && $h[5] === $h[6]) {
                    $h = '#' . $h[1] . $h[3] . $h[5];
                }

                return $h;

            case Type::T_NUMBER:
                return $value->output($this);

            case Type::T_STRING:
                return $value[1] . $this->compileStringContent($value) . $value[1];

            case Type::T_FUNCTION:
                $args = ! empty($value[2]) ? $this->compileValue($value[2]) : '';

                return "$value[1]($args)";

            case Type::T_LIST:
                $value = $this->extractInterpolation($value);

                if ($value[0] !== Type::T_LIST) {
                    return $this->compileValue($value);
                }

                list(, $delim, $items) = $value;
                $pre = $post = "";

                if (! empty($value['enclosing'])) {
                    switch ($value['enclosing']) {
                        case 'parent':
                            //$pre = "(";
                            //$post = ")";
                            break;
                        case 'forced_parent':
                            $pre = "(";
                            $post = ")";
                            break;
                        case 'bracket':
                        case 'forced_bracket':
                            $pre = "[";
                            $post = "]";
                            break;
                    }
                }

                $prefix_value = '';
                if ($delim !== ' ') {
                    $prefix_value = ' ';
                }

                $filtered = [];

                foreach ($items as $item) {
                    if ($item[0] === Type::T_NULL) {
                        continue;
                    }

                    $compiled = $this->compileValue($item);
                    if ($prefix_value && \strlen($compiled)) {
                        $compiled = $prefix_value . $compiled;
                    }
                    $filtered[] = $compiled;
                }

                return $pre . substr(implode("$delim", $filtered), \strlen($prefix_value)) . $post;

            case Type::T_MAP:
                $keys     = $value[1];
                $values   = $value[2];
                $filtered = [];

                for ($i = 0, $s = \count($keys); $i < $s; $i++) {
                    $filtered[$this->compileValue($keys[$i])] = $this->compileValue($values[$i]);
                }

                array_walk($filtered, function (&$value, $key) {
                    $value = $key . ': ' . $value;
                });

                return '(' . implode(', ', $filtered) . ')';

            case Type::T_INTERPOLATED:
                // node created by extractInterpolation
                list(, $interpolate, $left, $right) = $value;
                list(,, $whiteLeft, $whiteRight) = $interpolate;

                $delim = $left[1];

                if ($delim && $delim !== ' ' && ! $whiteLeft) {
                    $delim .= ' ';
                }

                $left = \count($left[2]) > 0 ?
                    $this->compileValue($left) . $delim . $whiteLeft: '';

                $delim = $right[1];

                if ($delim && $delim !== ' ') {
                    $delim .= ' ';
                }

                $right = \count($right[2]) > 0 ?
                    $whiteRight . $delim . $this->compileValue($right) : '';

                return $left . $this->compileValue($interpolate) . $right;

            case Type::T_INTERPOLATE:
                // strip quotes if it's a string
                $reduced = $this->reduce($value[1]);

                switch ($reduced[0]) {
                    case Type::T_LIST:
                        $reduced = $this->extractInterpolation($reduced);

                        if ($reduced[0] !== Type::T_LIST) {
                            break;
                        }

                        list(, $delim, $items) = $reduced;

                        if ($delim !== ' ') {
                            $delim .= ' ';
                        }

                        $filtered = [];

                        foreach ($items as $item) {
                            if ($item[0] === Type::T_NULL) {
                                continue;
                            }

                            $temp = $this->compileValue([Type::T_KEYWORD, $item]);

                            if ($temp[0] === Type::T_STRING) {
                                $filtered[] = $this->compileStringContent($temp);
                            } elseif ($temp[0] === Type::T_KEYWORD) {
                                $filtered[] = $temp[1];
                            } else {
                                $filtered[] = $this->compileValue($temp);
                            }
                        }

                        $reduced = [Type::T_KEYWORD, implode("$delim", $filtered)];
                        break;

                    case Type::T_STRING:
                        $reduced = [Type::T_KEYWORD, $this->compileStringContent($reduced)];
                        break;

                    case Type::T_NULL:
                        $reduced = [Type::T_KEYWORD, ''];
                }

                return $this->compileValue($reduced);

            case Type::T_NULL:
                return 'null';

            case Type::T_COMMENT:
                return $this->compileCommentValue($value);

            default:
                $this->throwError("unknown value type: ".json_encode($value));
        }
    }

    /**
     * Flatten list
     *
     * @param array $list
     *
     * @return string
     */
    protected function flattenList($list)
    {
        return $this->compileValue($list);
    }

    /**
     * Compile string content
     *
     * @param array $string
     *
     * @return string
     */
    protected function compileStringContent($string)
    {
        $parts = [];

        foreach ($string[2] as $part) {
            if (\is_array($part) || $part instanceof \ArrayAccess) {
                $parts[] = $this->compileValue($part);
            } else {
                $parts[] = $part;
            }
        }

        return implode($parts);
    }

    /**
     * Extract interpolation; it doesn't need to be recursive, compileValue will handle that
     *
     * @param array $list
     *
     * @return array
     */
    protected function extractInterpolation($list)
    {
        $items = $list[2];

        foreach ($items as $i => $item) {
            if ($item[0] === Type::T_INTERPOLATE) {
                $before = [Type::T_LIST, $list[1], \array_slice($items, 0, $i)];
                $after  = [Type::T_LIST, $list[1], \array_slice($items, $i + 1)];

                return [Type::T_INTERPOLATED, $item, $before, $after];
            }
        }

        return $list;
    }

    /**
     * Find the final set of selectors
     *
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param \ScssPhp\ScssPhp\Block                $selfParent
     *
     * @return array
     */
    protected function multiplySelectors(Environment $env, $selfParent = null)
    {
        $envs            = $this->compactEnv($env);
        $selectors       = [];
        $parentSelectors = [[]];

        $selfParentSelectors = null;

        if (! \is_null($selfParent) && $selfParent->selectors) {
            $selfParentSelectors = $this->evalSelectors($selfParent->selectors);
        }

        while ($env = array_pop($envs)) {
            if (empty($env->selectors)) {
                continue;
            }

            $selectors = $env->selectors;

            do {
                $stillHasSelf  = false;
                $prevSelectors = $selectors;
                $selectors     = [];

                foreach ($prevSelectors as $selector) {
                    foreach ($parentSelectors as $parent) {
                        if ($selfParentSelectors) {
                            foreach ($selfParentSelectors as $selfParent) {
                                // if no '&' in the selector, each call will give same result, only add once
                                $s = $this->joinSelectors($parent, $selector, $stillHasSelf, $selfParent);
                                $selectors[serialize($s)] = $s;
                            }
                        } else {
                            $s = $this->joinSelectors($parent, $selector, $stillHasSelf);
                            $selectors[serialize($s)] = $s;
                        }
                    }
                }
            } while ($stillHasSelf);

            $parentSelectors = $selectors;
        }

        $selectors = array_values($selectors);

        // case we are just starting a at-root : nothing to multiply but parentSelectors
        if (!$selectors and $selfParentSelectors) {
            $selectors = $selfParentSelectors;
        }

        return $selectors;
    }

    /**
     * Join selectors; looks for & to replace, or append parent before child
     *
     * @param array   $parent
     * @param array   $child
     * @param boolean $stillHasSelf
     * @param array   $selfParentSelectors

     * @return array
     */
    protected function joinSelectors($parent, $child, &$stillHasSelf, $selfParentSelectors = null)
    {
        $setSelf = false;
        $out = [];

        foreach ($child as $part) {
            $newPart = [];

            foreach ($part as $p) {
                // only replace & once and should be recalled to be able to make combinations
                if ($p === static::$selfSelector && $setSelf) {
                    $stillHasSelf = true;
                }

                if ($p === static::$selfSelector && ! $setSelf) {
                    $setSelf = true;

                    if (\is_null($selfParentSelectors)) {
                        $selfParentSelectors = $parent;
                    }

                    foreach ($selfParentSelectors as $i => $parentPart) {
                        if ($i > 0) {
                            $out[] = $newPart;
                            $newPart = [];
                        }

                        foreach ($parentPart as $pp) {
                            if (\is_array($pp)) {
                                $flatten = [];

                                array_walk_recursive($pp, function ($a) use (&$flatten) {
                                    $flatten[] = $a;
                                });

                                $pp = implode($flatten);
                            }

                            $newPart[] = $pp;
                        }
                    }
                } else {
                    $newPart[] = $p;
                }
            }

            $out[] = $newPart;
        }

        return $setSelf ? $out : array_merge($parent, $child);
    }

    /**
     * Multiply media
     *
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param array                                 $childQueries
     *
     * @return array
     */
    protected function multiplyMedia(Environment $env = null, $childQueries = null)
    {
        if (! isset($env) ||
            ! empty($env->block->type) && $env->block->type !== Type::T_MEDIA
        ) {
            return $childQueries;
        }

        // plain old block, skip
        if (empty($env->block->type)) {
            return $this->multiplyMedia($env->parent, $childQueries);
        }

        $parentQueries = isset($env->block->queryList)
            ? $env->block->queryList
            : [[[Type::T_MEDIA_VALUE, $env->block->value]]];

        $store = [$this->env, $this->storeEnv];

        $this->env      = $env;
        $this->storeEnv = null;
        $parentQueries  = $this->evaluateMediaQuery($parentQueries);

        list($this->env, $this->storeEnv) = $store;

        if (\is_null($childQueries)) {
            $childQueries = $parentQueries;
        } else {
            $originalQueries = $childQueries;
            $childQueries = [];

            foreach ($parentQueries as $parentQuery) {
                foreach ($originalQueries as $childQuery) {
                    $childQueries[] = array_merge(
                        $parentQuery,
                        [[Type::T_MEDIA_TYPE, [Type::T_KEYWORD, 'all']]],
                        $childQuery
                    );
                }
            }
        }

        return $this->multiplyMedia($env->parent, $childQueries);
    }

    /**
     * Convert env linked list to stack
     *
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     *
     * @return array
     */
    protected function compactEnv(Environment $env)
    {
        for ($envs = []; $env; $env = $env->parent) {
            $envs[] = $env;
        }

        return $envs;
    }

    /**
     * Convert env stack to singly linked list
     *
     * @param array $envs
     *
     * @return \ScssPhp\ScssPhp\Compiler\Environment
     */
    protected function extractEnv($envs)
    {
        for ($env = null; $e = array_pop($envs);) {
            $e->parent = $env;
            $env = $e;
        }

        return $env;
    }

    /**
     * Push environment
     *
     * @param \ScssPhp\ScssPhp\Block $block
     *
     * @return \ScssPhp\ScssPhp\Compiler\Environment
     */
    protected function pushEnv(Block $block = null)
    {
        $env = new Environment;
        $env->parent = $this->env;
        $env->parentStore = $this->storeEnv;
        $env->store  = [];
        $env->block  = $block;
        $env->depth  = isset($this->env->depth) ? $this->env->depth + 1 : 0;

        $this->env = $env;
        $this->storeEnv = null;

        return $env;
    }

    /**
     * Pop environment
     */
    protected function popEnv()
    {
        $this->storeEnv = $this->env->parentStore;
        $this->env = $this->env->parent;
    }

    /**
     * Propagate vars from a just poped Env (used in @each and @for)
     *
     * @param array      $store
     * @param null|array $excludedVars
     */
    protected function backPropagateEnv($store, $excludedVars = null)
    {
        foreach ($store as $key => $value) {
            if (empty($excludedVars) || ! \in_array($key, $excludedVars)) {
                $this->set($key, $value, true);
            }
        }
    }

    /**
     * Get store environment
     *
     * @return \ScssPhp\ScssPhp\Compiler\Environment
     */
    protected function getStoreEnv()
    {
        return isset($this->storeEnv) ? $this->storeEnv : $this->env;
    }

    /**
     * Set variable
     *
     * @param string                                $name
     * @param mixed                                 $value
     * @param boolean                               $shadow
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param mixed                                 $valueUnreduced
     */
    protected function set($name, $value, $shadow = false, Environment $env = null, $valueUnreduced = null)
    {
        $name = $this->normalizeName($name);

        if (! isset($env)) {
            $env = $this->getStoreEnv();
        }

        if ($shadow) {
            $this->setRaw($name, $value, $env, $valueUnreduced);
        } else {
            $this->setExisting($name, $value, $env, $valueUnreduced);
        }
    }

    /**
     * Set existing variable
     *
     * @param string                                $name
     * @param mixed                                 $value
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param mixed                                 $valueUnreduced
     */
    protected function setExisting($name, $value, Environment $env, $valueUnreduced = null)
    {
        $storeEnv = $env;
        $specialContentKey = static::$namespaces['special'] . 'content';

        $hasNamespace = $name[0] === '^' || $name[0] === '@' || $name[0] === '%';

        $maxDepth = 10000;

        for (;;) {
            if ($maxDepth-- <= 0) {
                break;
            }

            if (\array_key_exists($name, $env->store)) {
                break;
            }

            if (! $hasNamespace && isset($env->marker)) {
                if (! empty($env->store[$specialContentKey])) {
                    $env = $env->store[$specialContentKey]->scope;
                    continue;
                }

                if (! empty($env->declarationScopeParent)) {
                    $env = $env->declarationScopeParent;
                    continue;
                } else {
                    $env = $storeEnv;
                    break;
                }
            }

            if (isset($env->parentStore)) {
                $env = $env->parentStore;
            } elseif (isset($env->parent)) {
                $env = $env->parent;
            } else {
                $env = $storeEnv;
                break;
            }
        }

        $env->store[$name] = $value;

        if ($valueUnreduced) {
            $env->storeUnreduced[$name] = $valueUnreduced;
        }
    }

    /**
     * Set raw variable
     *
     * @param string                                $name
     * @param mixed                                 $value
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param mixed                                 $valueUnreduced
     */
    protected function setRaw($name, $value, Environment $env, $valueUnreduced = null)
    {
        $env->store[$name] = $value;

        if ($valueUnreduced) {
            $env->storeUnreduced[$name] = $valueUnreduced;
        }
    }

    /**
     * Get variable
     *
     * @api
     *
     * @param string                                $name
     * @param boolean                               $shouldThrow
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     * @param boolean                               $unreduced
     *
     * @return mixed|null
     */
    public function get($name, $shouldThrow = true, Environment $env = null, $unreduced = false)
    {
        $normalizedName = $this->normalizeName($name);
        $specialContentKey = static::$namespaces['special'] . 'content';

        if (! isset($env)) {
            $env = $this->getStoreEnv();
        }

        $hasNamespace = $normalizedName[0] === '^' || $normalizedName[0] === '@' || $normalizedName[0] === '%';

        $maxDepth = 10000;

        for (;;) {
            if ($maxDepth-- <= 0) {
                break;
            }

            if (\array_key_exists($normalizedName, $env->store)) {
                if ($unreduced && isset($env->storeUnreduced[$normalizedName])) {
                    return $env->storeUnreduced[$normalizedName];
                }

                return $env->store[$normalizedName];
            }

            if (! $hasNamespace && isset($env->marker)) {
                if (! empty($env->store[$specialContentKey])) {
                    $env = $env->store[$specialContentKey]->scope;
                    continue;
                }

                if (! empty($env->declarationScopeParent)) {
                    $env = $env->declarationScopeParent;
                } else {
                    $env = $this->rootEnv;
                }
                continue;
            }

            if (isset($env->parentStore)) {
                $env = $env->parentStore;
            } elseif (isset($env->parent)) {
                $env = $env->parent;
            } else {
                break;
            }
        }

        if ($shouldThrow) {
            $this->throwError("Undefined variable \$$name" . ($maxDepth <= 0 ? " (infinite recursion)" : ""));
        }

        // found nothing
        return null;
    }

    /**
     * Has variable?
     *
     * @param string                                $name
     * @param \ScssPhp\ScssPhp\Compiler\Environment $env
     *
     * @return boolean
     */
    protected function has($name, Environment $env = null)
    {
        return ! \is_null($this->get($name, false, $env));
    }

    /**
     * Inject variables
     *
     * @param array $args
     */
    protected function injectVariables(array $args)
    {
        if (empty($args)) {
            return;
        }

        $parser = $this->parserFactory(__METHOD__);

        foreach ($args as $name => $strValue) {
            if ($name[0] === '$') {
                $name = substr($name, 1);
            }

            if (! $parser->parseValue($strValue, $value)) {
                $value = $this->coerceValue($strValue);
            }

            $this->set($name, $value);
        }
    }

    /**
     * Set variables
     *
     * @api
     *
     * @param array $variables
     */
    public function setVariables(array $variables)
    {
        $this->registeredVars = array_merge($this->registeredVars, $variables);
    }

    /**
     * Unset variable
     *
     * @api
     *
     * @param string $name
     */
    public function unsetVariable($name)
    {
        unset($this->registeredVars[$name]);
    }

    /**
     * Returns list of variables
     *
     * @api
     *
     * @return array
     */
    public function getVariables()
    {
        return $this->registeredVars;
    }

    /**
     * Adds to list of parsed files
     *
     * @api
     *
     * @param string $path
     */
    public function addParsedFile($path)
    {
        if (isset($path) && is_file($path)) {
            $this->parsedFiles[realpath($path)] = filemtime($path);
        }
    }

    /**
     * Returns list of parsed files
     *
     * @api
     *
     * @return array
     */
    public function getParsedFiles()
    {
        return $this->parsedFiles;
    }

    /**
     * Add import path
     *
     * @api
     *
     * @param string|callable $path
     */
    public function addImportPath($path)
    {
        if (! \in_array($path, $this->importPaths)) {
            $this->importPaths[] = $path;
        }
    }

    /**
     * Set import paths
     *
     * @api
     *
     * @param string|array $path
     */
    public function setImportPaths($path)
    {
        $this->importPaths = (array) $path;
    }

    /**
     * Set number precision
     *
     * @api
     *
     * @param integer $numberPrecision
     */
    public function setNumberPrecision($numberPrecision)
    {
        Node\Number::$precision = $numberPrecision;
    }

    /**
     * Set formatter
     *
     * @api
     *
     * @param string $formatterName
     */
    public function setFormatter($formatterName)
    {
        $this->formatter = $formatterName;
    }

    /**
     * Set line number style
     *
     * @api
     *
     * @param string $lineNumberStyle
     */
    public function setLineNumberStyle($lineNumberStyle)
    {
        $this->lineNumberStyle = $lineNumberStyle;
    }

    /**
     * Enable/disable source maps
     *
     * @api
     *
     * @param integer $sourceMap
     */
    public function setSourceMap($sourceMap)
    {
        $this->sourceMap = $sourceMap;
    }

    /**
     * Set source map options
     *
     * @api
     *
     * @param array $sourceMapOptions
     */
    public function setSourceMapOptions($sourceMapOptions)
    {
        $this->sourceMapOptions = $sourceMapOptions;
    }

    /**
     * Register function
     *
     * @api
     *
     * @param string   $name
     * @param callable $func
     * @param array    $prototype
     */
    public function registerFunction($name, $func, $prototype = null)
    {
        $this->userFunctions[$this->normalizeName($name)] = [$func, $prototype];
    }

    /**
     * Unregister function
     *
     * @api
     *
     * @param string $name
     */
    public function unregisterFunction($name)
    {
        unset($this->userFunctions[$this->normalizeName($name)]);
    }

    /**
     * Add feature
     *
     * @api
     *
     * @param string $name
     */
    public function addFeature($name)
    {
        $this->registeredFeatures[$name] = true;
    }

    /**
     * Import file
     *
     * @param string                                 $path
     * @param \ScssPhp\ScssPhp\Formatter\OutputBlock $out
     */
    protected function importFile($path, OutputBlock $out)
    {
        $this->pushCallStack('import '.$path);
        // see if tree is cached
        $realPath = realpath($path);

        if (isset($this->importCache[$realPath])) {
            $this->handleImportLoop($realPath);

            $tree = $this->importCache[$realPath];
        } else {
            $code   = file_get_contents($path);
            $parser = $this->parserFactory($path);
            $tree   = $parser->parse($code);

            $this->importCache[$realPath] = $tree;
        }

        $pi = pathinfo($path);

        array_unshift($this->importPaths, $pi['dirname']);
        $this->compileChildrenNoReturn($tree->children, $out);
        array_shift($this->importPaths);
        $this->popCallStack();
    }

    /**
     * Return the file path for an import url if it exists
     *
     * @api
     *
     * @param string $url
     *
     * @return string|null
     */
    public function findImport($url)
    {
        $urls = [];

        $hasExtension = preg_match('/[.]s?css$/', $url);

        // for "normal" scss imports (ignore vanilla css and external requests)
        if (! preg_match('~\.css$|^https?://|^//~', $url)) {
            $isPartial = (strpos(basename($url), '_') === 0);

            // try both normal and the _partial filename
            $urls = [$url . ($hasExtension ? '' : '.scss')];

            if (! $isPartial) {
                $urls[] = preg_replace('~[^/]+$~', '_\0', $url) . ($hasExtension ? '' : '.scss');
            }

            if (! $hasExtension) {
                $urls[] = "$url/index.scss";
                $urls[] = "$url/_index.scss";
                // allow to find a plain css file, *if* no scss or partial scss is found
                $urls[] .= $url . ".css";
            }
        }

        foreach ($this->importPaths as $dir) {
            if (\is_string($dir)) {
                // check urls for normal import paths
                foreach ($urls as $full) {
                    $separator = (
                        ! empty($dir) &&
                        substr($dir, -1) !== '/' &&
                        substr($full, 0, 1) !== '/'
                    ) ? '/' : '';
                    $full = $dir . $separator . $full;

                    if (is_file($file = $full)) {
                        return $file;
                    }
                }
            } elseif (\is_callable($dir)) {
                // check custom callback for import path
                $file = \call_user_func($dir, $url);

                if (! \is_null($file)) {
                    return $file;
                }
            }
        }

        if ($urls) {
            if (! $hasExtension or preg_match('/[.]scss$/', $url)) {
                $this->throwError("`$url` file not found for @import");
            }
        }

        return null;
    }

    /**
     * Set encoding
     *
     * @api
     *
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * Ignore errors?
     *
     * @api
     *
     * @param boolean $ignoreErrors
     *
     * @return \ScssPhp\ScssPhp\Compiler
     */
    public function setIgnoreErrors($ignoreErrors)
    {
        $this->ignoreErrors = $ignoreErrors;

        return $this;
    }

    /**
     * Throw error (exception)
     *
     * @api
     *
     * @param string $msg Message with optional sprintf()-style vararg parameters
     *
     * @throws \ScssPhp\ScssPhp\Exception\CompilerException
     */
    public function throwError($msg)
    {
        if ($this->ignoreErrors) {
            return;
        }

        if (\func_num_args() > 1) {
            $msg = \call_user_func_array('sprintf', \func_get_args());
        }

        if (! $this->ignoreCallStackMessage) {
            $line   = $this->sourceLine;
            $column = $this->sourceColumn;

            $loc = isset($this->sourceNames[$this->sourceIndex])
                ? $this->sourceNames[$this->sourceIndex] . " on line $line, at column $column"
                : "line: $line, column: $column";

            $msg = "$msg: $loc";

            $callStackMsg = $this->callStackMessage();

            if ($callStackMsg) {
                $msg .= "\nCall Stack:\n" . $callStackMsg;
            }
        }

        throw new CompilerException($msg);
    }

    /**
     * Beautify call stack for output
     *
     * @param boolean $all
     * @param null    $limit
     *
     * @return string
     */
    protected function callStackMessage($all = false, $limit = null)
    {
        $callStackMsg = [];
        $ncall = 0;

        if ($this->callStack) {
            foreach (array_reverse($this->callStack) as $call) {
                if ($all || (isset($call['n']) && $call['n'])) {
                    $msg = "#" . $ncall++ . " " . $call['n'] . " ";
                    $msg .= (isset($this->sourceNames[$call[Parser::SOURCE_INDEX]])
                          ? $this->sourceNames[$call[Parser::SOURCE_INDEX]]
                          : '(unknown file)');
                    $msg .= " on line " . $call[Parser::SOURCE_LINE];

                    $callStackMsg[] = $msg;

                    if (! \is_null($limit) && $ncall > $limit) {
                        break;
                    }
                }
            }
        }

        return implode("\n", $callStackMsg);
    }

    /**
     * Handle import loop
     *
     * @param string $name
     *
     * @throws \Exception
     */
    protected function handleImportLoop($name)
    {
        for ($env = $this->env; $env; $env = $env->parent) {
            if (! $env->block) {
                continue;
            }

            $file = $this->sourceNames[$env->block->sourceIndex];

            if (realpath($file) === $name) {
                $this->throwError('An @import loop has been found: %s imports %s', $file, basename($file));
                break;
            }
        }
    }

    /**
     * Call SCSS @function
     *
     * @param string $name
     * @param array  $argValues
     * @param array  $returnValue
     *
     * @return boolean Returns true if returnValue is set; otherwise, false
     */
    protected function callScssFunction($name, $argValues, &$returnValue)
    {
        $func = $this->get(static::$namespaces['function'] . $name, false);

        if (! $func) {
            return false;
        }

        $this->pushEnv();

        // set the args
        if (isset($func->args)) {
            $this->applyArguments($func->args, $argValues);
        }

        // throw away lines and children
        $tmp = new OutputBlock;
        $tmp->lines    = [];
        $tmp->children = [];

        $this->env->marker = 'function';

        if (! empty($func->parentEnv)) {
            $this->env->declarationScopeParent = $func->parentEnv;
        } else {
            $this->throwError("@function $name() without parentEnv");
        }

        $ret = $this->compileChildren($func->children, $tmp, $this->env->marker . " " . $name);

        $this->popEnv();

        $returnValue = ! isset($ret) ? static::$defaultValue : $ret;

        return true;
    }

    /**
     * Call built-in and registered (PHP) functions
     *
     * @param string $name
     * @param array  $args
     * @param array  $returnValue
     *
     * @return boolean Returns true if returnValue is set; otherwise, false
     */
    protected function callNativeFunction($name, $args, &$returnValue)
    {
        // try a lib function
        $name = $this->normalizeName($name);
        $libName = null;

        if (isset($this->userFunctions[$name])) {
            // see if we can find a user function
            list($f, $prototype) = $this->userFunctions[$name];
        } elseif (($f = $this->getBuiltinFunction($name)) && \is_callable($f)) {
            $libName   = $f[1];
            $prototype = isset(static::$$libName) ? static::$$libName : null;
        } else {
            return false;
        }

        @list($sorted, $kwargs) = $this->sortNativeFunctionArgs($libName, $prototype, $args);

        if ($name !== 'if' && $name !== 'call') {
            $inExp = true;

            if ($name === 'join') {
                $inExp = false;
            }

            foreach ($sorted as &$val) {
                $val = $this->reduce($val, $inExp);
            }
        }

        $returnValue = \call_user_func($f, $sorted, $kwargs);

        if (! isset($returnValue)) {
            return false;
        }

        $returnValue = $this->coerceValue($returnValue);

        return true;
    }

    /**
     * Get built-in function
     *
     * @param string $name Normalized name
     *
     * @return array
     */
    protected function getBuiltinFunction($name)
    {
        $libName = 'lib' . preg_replace_callback(
            '/_(.)/',
            function ($m) {
                return ucfirst($m[1]);
            },
            ucfirst($name)
        );

        return [$this, $libName];
    }

    /**
     * Sorts keyword arguments
     *
     * @param string $functionName
     * @param array  $prototypes
     * @param array  $args
     *
     * @return array
     */
    protected function sortNativeFunctionArgs($functionName, $prototypes, $args)
    {
        static $parser = null;

        if (! isset($prototypes)) {
            $keyArgs = [];
            $posArgs = [];

            // separate positional and keyword arguments
            foreach ($args as $arg) {
                list($key, $value) = $arg;

                $key = $key[1];

                if (empty($key)) {
                    $posArgs[] = empty($arg[2]) ? $value : $arg;
                } else {
                    $keyArgs[$key] = $value;
                }
            }

            return [$posArgs, $keyArgs];
        }

        // specific cases ?
        if (\in_array($functionName, ['libRgb', 'libRgba', 'libHsl', 'libHsla'])) {
            // notation 100 127 255 / 0 is in fact a simple list of 4 values
            foreach ($args as $k => $arg) {
                if ($arg[1][0] === Type::T_LIST && \count($arg[1][2]) === 3) {
                    $last = end($arg[1][2]);

                    if ($last[0] === Type::T_EXPRESSION && $last[1] === '/') {
                        array_pop($arg[1][2]);
                        $arg[1][2][] = $last[2];
                        $arg[1][2][] = $last[3];
                        $args[$k] = $arg;
                    }
                }
            }
        }

        $finalArgs = [];

        if (! \is_array(reset($prototypes))) {
            $prototypes = [$prototypes];
        }

        $keyArgs = [];

        // trying each prototypes
        $prototypeHasMatch = false;
        $exceptionMessage = '';

        foreach ($prototypes as $prototype) {
            $argDef = [];

            foreach ($prototype as $i => $p) {
                $default = null;
                $p       = explode(':', $p, 2);
                $name    = array_shift($p);

                if (\count($p)) {
                    $p = trim(reset($p));

                    if ($p === 'null') {
                        // differentiate this null from the static::$null
                        $default = [Type::T_KEYWORD, 'null'];
                    } else {
                        if (\is_null($parser)) {
                            $parser = $this->parserFactory(__METHOD__);
                        }

                        $parser->parseValue($p, $default);
                    }
                }

                $isVariable = false;

                if (substr($name, -3) === '...') {
                    $isVariable = true;
                    $name = substr($name, 0, -3);
                }

                $argDef[] = [$name, $default, $isVariable];
            }

            $ignoreCallStackMessage = $this->ignoreCallStackMessage;
            $this->ignoreCallStackMessage = true;

            try {
                $vars = $this->applyArguments($argDef, $args, false, false);

                // ensure all args are populated
                foreach ($prototype as $i => $p) {
                    $name = explode(':', $p)[0];

                    if (! isset($finalArgs[$i])) {
                        $finalArgs[$i] = null;
                    }
                }

                // apply positional args
                foreach (array_values($vars) as $i => $val) {
                    $finalArgs[$i] = $val;
                }

                $keyArgs = array_merge($keyArgs, $vars);
                $prototypeHasMatch = true;

                // overwrite positional args with keyword args
                foreach ($prototype as $i => $p) {
                    $name = explode(':', $p)[0];

                    if (isset($keyArgs[$name])) {
                        $finalArgs[$i] = $keyArgs[$name];
                    }

                    // special null value as default: translate to real null here
                    if ($finalArgs[$i] === [Type::T_KEYWORD, 'null']) {
                        $finalArgs[$i] = null;
                    }
                }
                // should we break if this prototype seems fulfilled?
            } catch (CompilerException $e) {
                $exceptionMessage = $e->getMessage();
            }
            $this->ignoreCallStackMessage = $ignoreCallStackMessage;
        }

        if ($exceptionMessage && ! $prototypeHasMatch) {
            $this->throwError($exceptionMessage);
        }

        return [$finalArgs, $keyArgs];
    }

    /**
     * Apply argument values per definition
     *
     * @param array   $argDef
     * @param array   $argValues
     * @param boolean $storeInEnv
     * @param boolean $reduce
     *   only used if $storeInEnv = false
     *
     * @return array
     *
     * @throws \Exception
     */
    protected function applyArguments($argDef, $argValues, $storeInEnv = true, $reduce = true)
    {
        $output = [];

        if (\is_array($argValues) && \count($argValues) && end($argValues) === static::$null) {
            array_pop($argValues);
        }

        if ($storeInEnv) {
            $storeEnv = $this->getStoreEnv();

            $env = new Environment;
            $env->store = $storeEnv->store;
        }

        $hasVariable = false;
        $args = [];

        foreach ($argDef as $i => $arg) {
            list($name, $default, $isVariable) = $argDef[$i];

            $args[$name] = [$i, $name, $default, $isVariable];
            $hasVariable |= $isVariable;
        }

        $splatSeparator      = null;
        $keywordArgs         = [];
        $deferredKeywordArgs = [];
        $remaining           = [];
        $hasKeywordArgument  = false;

        // assign the keyword args
        foreach ((array) $argValues as $arg) {
            if (! empty($arg[0])) {
                $hasKeywordArgument = true;

                $name = $arg[0][1];
                if (! isset($args[$name])) {
                    foreach (array_keys($args) as $an) {
                        if (str_replace("_", "-", $an) === str_replace("_", "-", $name)) {
                            $name = $an;
                            break;
                        }
                    }
                }

                if (! isset($args[$name]) || $args[$name][3]) {
                    if ($hasVariable) {
                        $deferredKeywordArgs[$name] = $arg[1];
                    } else {
                        $this->throwError("Mixin or function doesn't have an argument named $%s.", $arg[0][1]);
                        break;
                    }
                } elseif ($args[$name][0] < \count($remaining)) {
                    $this->throwError("The argument $%s was passed both by position and by name.", $arg[0][1]);
                    break;
                } else {
                    $keywordArgs[$name] = $arg[1];
                }
            } elseif ($arg[2] === true) {
                $val = $this->reduce($arg[1], true);

                if ($val[0] === Type::T_LIST) {
                    foreach ($val[2] as $name => $item) {
                        if (! is_numeric($name)) {
                            if (! isset($args[$name])) {
                                foreach (array_keys($args) as $an) {
                                    if (str_replace("_", "-", $an) === str_replace("_", "-", $name)) {
                                        $name = $an;
                                        break;
                                    }
                                }
                            }

                            if ($hasVariable) {
                                $deferredKeywordArgs[$name] = $item;
                            } else {
                                $keywordArgs[$name] = $item;
                            }
                        } else {
                            if (\is_null($splatSeparator)) {
                                $splatSeparator = $val[1];
                            }

                            $remaining[] = $item;
                        }
                    }
                } elseif ($val[0] === Type::T_MAP) {
                    foreach ($val[1] as $i => $name) {
                        $name = $this->compileStringContent($this->coerceString($name));
                        $item = $val[2][$i];

                        if (! is_numeric($name)) {
                            if (! isset($args[$name])) {
                                foreach (array_keys($args) as $an) {
                                    if (str_replace("_", "-", $an) === str_replace("_", "-", $name)) {
                                        $name = $an;
                                        break;
                                    }
                                }
                            }

                            if ($hasVariable) {
                                $deferredKeywordArgs[$name] = $item;
                            } else {
                                $keywordArgs[$name] = $item;
                            }
                        } else {
                            if (\is_null($splatSeparator)) {
                                $splatSeparator = $val[1];
                            }

                            $remaining[] = $item;
                        }
                    }
                } else {
                    $remaining[] = $val;
                }
            } elseif ($hasKeywordArgument) {
                $this->throwError('Positional arguments must come before keyword arguments.');
                break;
            } else {
                $remaining[] = $arg[1];
            }
        }

        foreach ($args as $arg) {
            list($i, $name, $default, $isVariable) = $arg;

            if ($isVariable) {
                $val = [Type::T_LIST, \is_null($splatSeparator) ? ',' : $splatSeparator , [], $isVariable];

                for ($count = \count($remaining); $i < $count; $i++) {
                    $val[2][] = $remaining[$i];
                }

                foreach ($deferredKeywordArgs as $itemName => $item) {
                    $val[2][$itemName] = $item;
                }
            } elseif (isset($remaining[$i])) {
                $val = $remaining[$i];
            } elseif (isset($keywordArgs[$name])) {
                $val = $keywordArgs[$name];
            } elseif (! empty($default)) {
                continue;
            } else {
                $this->throwError("Missing argument $name");
                break;
            }

            if ($storeInEnv) {
                $this->set($name, $this->reduce($val, true), true, $env);
            } else {
                $output[$name] = ($reduce ? $this->reduce($val, true) : $val);
            }
        }

        if ($storeInEnv) {
            $storeEnv->store = $env->store;
        }

        foreach ($args as $arg) {
            list($i, $name, $default, $isVariable) = $arg;

            if ($isVariable || isset($remaining[$i]) || isset($keywordArgs[$name]) || empty($default)) {
                continue;
            }

            if ($storeInEnv) {
                $this->set($name, $this->reduce($default, true), true);
            } else {
                $output[$name] = ($reduce ? $this->reduce($default, true) : $default);
            }
        }

        return $output;
    }

    /**
     * Coerce a php value into a scss one
     *
     * @param mixed $value
     *
     * @return array|\ScssPhp\ScssPhp\Node\Number
     */
    protected function coerceValue($value)
    {
        if (\is_array($value) || $value instanceof \ArrayAccess) {
            return $value;
        }

        if (\is_bool($value)) {
            return $this->toBool($value);
        }

        if (\is_null($value)) {
            return static::$null;
        }

        if (is_numeric($value)) {
            return new Node\Number($value, '');
        }

        if ($value === '') {
            return static::$emptyString;
        }

        $value = [Type::T_KEYWORD, $value];
        $color = $this->coerceColor($value);

        if ($color) {
            return $color;
        }

        return $value;
    }

    /**
     * Coerce something to map
     *
     * @param array $item
     *
     * @return array
     */
    protected function coerceMap($item)
    {
        if ($item[0] === Type::T_MAP) {
            return $item;
        }

        if ($item[0] === static::$emptyList[0] &&
            $item[1] === static::$emptyList[1] &&
            $item[2] === static::$emptyList[2]
        ) {
            return static::$emptyMap;
        }

        return [Type::T_MAP, [$item], [static::$null]];
    }

    /**
     * Coerce something to list
     *
     * @param array   $item
     * @param string  $delim
     * @param boolean $removeTrailingNull
     *
     * @return array
     */
    protected function coerceList($item, $delim = ',', $removeTrailingNull = false)
    {
        if (isset($item) && $item[0] === Type::T_LIST) {
            // remove trailing null from the list
            if ($removeTrailingNull && end($item[2]) === static::$null) {
                array_pop($item[2]);
            }

            return $item;
        }

        if (isset($item) && $item[0] === Type::T_MAP) {
            $keys = $item[1];
            $values = $item[2];
            $list = [];

            for ($i = 0, $s = \count($keys); $i < $s; $i++) {
                $key = $keys[$i];
                $value = $values[$i];

                switch ($key[0]) {
                    case Type::T_LIST:
                    case Type::T_MAP:
                    case Type::T_STRING:
                    case Type::T_NULL:
                        break;

                    default:
                        $key = [Type::T_KEYWORD, $this->compileStringContent($this->coerceString($key))];
                        break;
                }

                $list[] = [
                    Type::T_LIST,
                    '',
                    [$key, $value]
                ];
            }

            return [Type::T_LIST, ',', $list];
        }

        return [Type::T_LIST, $delim, ! isset($item) ? []: [$item]];
    }

    /**
     * Coerce color for expression
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceForExpression($value)
    {
        if ($color = $this->coerceColor($value)) {
            return $color;
        }

        return $value;
    }

    /**
     * Coerce value to color
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceColor($value, $inRGBFunction = false)
    {
        switch ($value[0]) {
            case Type::T_COLOR:
                for ($i = 1; $i <= 3; $i++) {
                    if (! is_numeric($value[$i])) {
                        $cv = $this->compileRGBAValue($value[$i]);

                        if (! is_numeric($cv)) {
                            return null;
                        }

                        $value[$i] = $cv;
                    }

                    if (isset($value[4])) {
                        if (! is_numeric($value[4])) {
                            $cv = $this->compileRGBAValue($value[4], true);

                            if (! is_numeric($cv)) {
                                return null;
                            }

                            $value[4] = $cv;
                        }
                    }
                }

                return $value;

            case Type::T_LIST:
                if ($inRGBFunction) {
                    if (\count($value[2]) == 3 || \count($value[2]) == 4) {
                        $color = $value[2];
                        array_unshift($color, Type::T_COLOR);

                        return $this->coerceColor($color);
                    }
                }

                return null;

            case Type::T_KEYWORD:
                if (! \is_string($value[1])) {
                    return null;
                }

                $name = strtolower($value[1]);

                // hexa color?
                if (preg_match('/^#([0-9a-f]+)$/i', $name, $m)) {
                    $nofValues = \strlen($m[1]);

                    if (\in_array($nofValues, [3, 4, 6, 8])) {
                        $nbChannels = 3;
                        $color      = [];
                        $num        = hexdec($m[1]);

                        switch ($nofValues) {
                            case 4:
                                $nbChannels = 4;
                                // then continuing with the case 3:
                            case 3:
                                for ($i = 0; $i < $nbChannels; $i++) {
                                    $t = $num & 0xf;
                                    array_unshift($color, $t << 4 | $t);
                                    $num >>= 4;
                                }

                                break;

                            case 8:
                                $nbChannels = 4;
                                // then continuing with the case 6:
                            case 6:
                                for ($i = 0; $i < $nbChannels; $i++) {
                                    array_unshift($color, $num & 0xff);
                                    $num >>= 8;
                                }

                                break;
                        }

                        if ($nbChannels === 4) {
                            if ($color[3] === 255) {
                                $color[3] = 1; // fully opaque
                            } else {
                                $color[3] = round($color[3] / 255, 3);
                            }
                        }

                        array_unshift($color, Type::T_COLOR);

                        return $color;
                    }
                }

                if ($rgba = Colors::colorNameToRGBa($name)) {
                    return isset($rgba[3])
                        ? [Type::T_COLOR, $rgba[0], $rgba[1], $rgba[2], $rgba[3]]
                        : [Type::T_COLOR, $rgba[0], $rgba[1], $rgba[2]];
                }

                return null;
        }

        return null;
    }

    /**
     * @param integer|\ScssPhp\ScssPhp\Node\Number $value
     * @param boolean                              $isAlpha
     *
     * @return integer|mixed
     */
    protected function compileRGBAValue($value, $isAlpha = false)
    {
        if ($isAlpha) {
            return $this->compileColorPartValue($value, 0, 1, false);
        }

        return $this->compileColorPartValue($value, 0, 255, true);
    }

    /**
     * @param mixed         $value
     * @param integer|float $min
     * @param integer|float $max
     * @param boolean       $isInt
     * @param boolean       $clamp
     * @param boolean       $modulo
     *
     * @return integer|mixed
     */
    protected function compileColorPartValue($value, $min, $max, $isInt = true, $clamp = true, $modulo = false)
    {
        if (! is_numeric($value)) {
            if (\is_array($value)) {
                $reduced = $this->reduce($value);

                if (\is_object($reduced) && $value->type === Type::T_NUMBER) {
                    $value = $reduced;
                }
            }

            if (\is_object($value) && $value->type === Type::T_NUMBER) {
                $num = $value->dimension;

                if (\count($value->units)) {
                    $unit = array_keys($value->units);
                    $unit = reset($unit);

                    switch ($unit) {
                        case '%':
                            $num *= $max / 100;
                            break;
                        default:
                            break;
                    }
                }

                $value = $num;
            } elseif (\is_array($value)) {
                $value = $this->compileValue($value);
            }
        }

        if (is_numeric($value)) {
            if ($isInt) {
                $value = round($value);
            }

            if ($clamp) {
                $value = min($max, max($min, $value));
            }

            if ($modulo) {
                $value = $value % $max;

                // still negative?
                while ($value < $min) {
                    $value += $max;
                }
            }

            return $value;
        }

        return $value;
    }

    /**
     * Coerce value to string
     *
     * @param array $value
     *
     * @return array|null
     */
    protected function coerceString($value)
    {
        if ($value[0] === Type::T_STRING) {
            return $value;
        }

        return [Type::T_STRING, '', [$this->compileValue($value)]];
    }

    /**
     * Coerce value to a percentage
     *
     * @param array $value
     *
     * @return integer|float
     */
    protected function coercePercent($value)
    {
        if ($value[0] === Type::T_NUMBER) {
            if (! empty($value[2]['%'])) {
                return $value[1] / 100;
            }

            return $value[1];
        }

        return 0;
    }

    /**
     * Assert value is a map
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertMap($value)
    {
        $value = $this->coerceMap($value);

        if ($value[0] !== Type::T_MAP) {
            $this->throwError('expecting map, %s received', $value[0]);
        }

        return $value;
    }

    /**
     * Assert value is a list
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertList($value)
    {
        if ($value[0] !== Type::T_LIST) {
            $this->throwError('expecting list, %s received', $value[0]);
        }

        return $value;
    }

    /**
     * Assert value is a color
     *
     * @api
     *
     * @param array $value
     *
     * @return array
     *
     * @throws \Exception
     */
    public function assertColor($value)
    {
        if ($color = $this->coerceColor($value)) {
            return $color;
        }

        $this->throwError('expecting color, %s received', $value[0]);
    }

    /**
     * Assert value is a number
     *
     * @api
     *
     * @param array $value
     *
     * @return integer|float
     *
     * @throws \Exception
     */
    public function assertNumber($value)
    {
        if ($value[0] !== Type::T_NUMBER) {
            $this->throwError('expecting number, %s received', $value[0]);
        }

        return $value[1];
    }

    /**
     * Make sure a color's components don't go out of bounds
     *
     * @param array $c
     *
     * @return array
     */
    protected function fixColor($c)
    {
        foreach ([1, 2, 3] as $i) {
            if ($c[$i] < 0) {
                $c[$i] = 0;
            }

            if ($c[$i] > 255) {
                $c[$i] = 255;
            }
        }

        return $c;
    }

    /**
     * Convert RGB to HSL
     *
     * @api
     *
     * @param integer $red
     * @param integer $green
     * @param integer $blue
     *
     * @return array
     */
    public function toHSL($red, $green, $blue)
    {
        $min = min($red, $green, $blue);
        $max = max($red, $green, $blue);

        $l = $min + $max;
        $d = $max - $min;

        if ((int) $d === 0) {
            $h = $s = 0;
        } else {
            if ($l < 255) {
                $s = $d / $l;
            } else {
                $s = $d / (510 - $l);
            }

            if ($red == $max) {
                $h = 60 * ($green - $blue) / $d;
            } elseif ($green == $max) {
                $h = 60 * ($blue - $red) / $d + 120;
            } elseif ($blue == $max) {
                $h = 60 * ($red - $green) / $d + 240;
            }
        }

        return [Type::T_HSL, fmod($h, 360), $s * 100, $l / 5.1];
    }

    /**
     * Hue to RGB helper
     *
     * @param float $m1
     * @param float $m2
     * @param float $h
     *
     * @return float
     */
    protected function hueToRGB($m1, $m2, $h)
    {
        if ($h < 0) {
            $h += 1;
        } elseif ($h > 1) {
            $h -= 1;
        }

        if ($h * 6 < 1) {
            return $m1 + ($m2 - $m1) * $h * 6;
        }

        if ($h * 2 < 1) {
            return $m2;
        }

        if ($h * 3 < 2) {
            return $m1 + ($m2 - $m1) * (2/3 - $h) * 6;
        }

        return $m1;
    }

    /**
     * Convert HSL to RGB
     *
     * @api
     *
     * @param integer $hue        H from 0 to 360
     * @param integer $saturation S from 0 to 100
     * @param integer $lightness  L from 0 to 100
     *
     * @return array
     */
    public function toRGB($hue, $saturation, $lightness)
    {
        if ($hue < 0) {
            $hue += 360;
        }

        $h = $hue / 360;
        $s = min(100, max(0, $saturation)) / 100;
        $l = min(100, max(0, $lightness)) / 100;

        $m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;
        $m1 = $l * 2 - $m2;

        $r = $this->hueToRGB($m1, $m2, $h + 1/3) * 255;
        $g = $this->hueToRGB($m1, $m2, $h) * 255;
        $b = $this->hueToRGB($m1, $m2, $h - 1/3) * 255;

        $out = [Type::T_COLOR, $r, $g, $b];

        return $out;
    }

    // Built in functions

    protected static $libCall = ['name', 'args...'];
    protected function libCall($args, $kwargs)
    {
        $name = $this->compileStringContent($this->coerceString($this->reduce(array_shift($args), true)));
        $callArgs = [];

        // $kwargs['args'] is [Type::T_LIST, ',', [..]]
        foreach ($kwargs['args'][2] as $varname => $arg) {
            if (is_numeric($varname)) {
                $varname = null;
            } else {
                $varname = [ 'var', $varname];
            }

            $callArgs[] = [$varname, $arg, false];
        }

        return $this->reduce([Type::T_FUNCTION_CALL, $name, $callArgs]);
    }

    protected static $libIf = ['condition', 'if-true', 'if-false:'];
    protected function libIf($args)
    {
        list($cond, $t, $f) = $args;

        if (! $this->isTruthy($this->reduce($cond, true))) {
            return $this->reduce($f, true);
        }

        return $this->reduce($t, true);
    }

    protected static $libIndex = ['list', 'value'];
    protected function libIndex($args)
    {
        list($list, $value) = $args;

        if ($list[0] === Type::T_MAP ||
            $list[0] === Type::T_STRING ||
            $list[0] === Type::T_KEYWORD ||
            $list[0] === Type::T_INTERPOLATE
        ) {
            $list = $this->coerceList($list, ' ');
        }

        if ($list[0] !== Type::T_LIST) {
            return static::$null;
        }

        $values = [];

        foreach ($list[2] as $item) {
            $values[] = $this->normalizeValue($item);
        }

        $key = array_search($this->normalizeValue($value), $values);

        return false === $key ? static::$null : $key + 1;
    }

    protected static $libRgb = [
        ['color'],
        ['color', 'alpha'],
        ['channels'],
        ['red', 'green', 'blue'],
        ['red', 'green', 'blue', 'alpha'] ];
    protected function libRgb($args, $kwargs, $funcName = 'rgb')
    {
        switch (\count($args)) {
            case 1:
                if (! $color = $this->coerceColor($args[0], true)) {
                    $color = [Type::T_STRING, '', [$funcName . '(', $args[0], ')']];
                }
                break;

            case 3:
                $color = [Type::T_COLOR, $args[0], $args[1], $args[2]];

                if (! $color = $this->coerceColor($color)) {
                    $color = [Type::T_STRING, '', [$funcName .'(', $args[0], ', ', $args[1], ', ', $args[2], ')']];
                }

                return $color;

            case 2:
                if ($color = $this->coerceColor($args[0], true)) {
                    $alpha = $this->compileRGBAValue($args[1], true);

                    if (is_numeric($alpha)) {
                        $color[4] = $alpha;
                    } else {
                        $color = [Type::T_STRING, '',
                            [$funcName . '(', $color[1], ', ', $color[2], ', ', $color[3], ', ', $alpha, ')']];
                    }
                } else {
                    $color = [Type::T_STRING, '', [$funcName . '(', $args[0], ')']];
                }
                break;

            case 4:
            default:
                $color = [Type::T_COLOR, $args[0], $args[1], $args[2], $args[3]];

                if (! $color = $this->coerceColor($color)) {
                    $color = [Type::T_STRING, '',
                        [$funcName . '(', $args[0], ', ', $args[1], ', ', $args[2], ', ', $args[3], ')']];
                }
                break;
        }

        return $color;
    }

    protected static $libRgba = [
        ['color'],
        ['color', 'alpha'],
        ['channels'],
        ['red', 'green', 'blue'],
        ['red', 'green', 'blue', 'alpha'] ];
    protected function libRgba($args, $kwargs)
    {
        return $this->libRgb($args, $kwargs, 'rgba');
    }

    // helper function for adjust_color, change_color, and scale_color
    protected function alterColor($args, $fn)
    {
        $color = $this->assertColor($args[0]);

        foreach ([1 => 1, 2 => 2, 3 => 3, 7 => 4] as $iarg => $irgba) {
            if (isset($args[$iarg])) {
                $val = $this->assertNumber($args[$iarg]);

                if (! isset($color[$irgba])) {
                    $color[$irgba] = (($irgba < 4) ? 0 : 1);
                }

                $color[$irgba] = \call_user_func($fn, $color[$irgba], $val, $iarg);
            }
        }

        if (! empty($args[4]) || ! empty($args[5]) || ! empty($args[6])) {
            $hsl = $this->toHSL($color[1], $color[2], $color[3]);

            foreach ([4 => 1, 5 => 2, 6 => 3] as $iarg => $ihsl) {
                if (! empty($args[$iarg])) {
                    $val = $this->assertNumber($args[$iarg]);
                    $hsl[$ihsl] = \call_user_func($fn, $hsl[$ihsl], $val, $iarg);
                }
            }

            $rgb = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);

            if (isset($color[4])) {
                $rgb[4] = $color[4];
            }

            $color = $rgb;
        }

        return $color;
    }

    protected static $libAdjustColor = [
        'color', 'red:null', 'green:null', 'blue:null',
        'hue:null', 'saturation:null', 'lightness:null', 'alpha:null'
    ];
    protected function libAdjustColor($args)
    {
        return $this->alterColor($args, function ($base, $alter, $i) {
            return $base + $alter;
        });
    }

    protected static $libChangeColor = [
        'color', 'red:null', 'green:null', 'blue:null',
        'hue:null', 'saturation:null', 'lightness:null', 'alpha:null'
    ];
    protected function libChangeColor($args)
    {
        return $this->alterColor($args, function ($base, $alter, $i) {
            return $alter;
        });
    }

    protected static $libScaleColor = [
        'color', 'red:null', 'green:null', 'blue:null',
        'hue:null', 'saturation:null', 'lightness:null', 'alpha:null'
    ];
    protected function libScaleColor($args)
    {
        return $this->alterColor($args, function ($base, $scale, $i) {
            // 1, 2, 3 - rgb
            // 4, 5, 6 - hsl
            // 7 - a
            switch ($i) {
                case 1:
                case 2:
                case 3:
                    $max = 255;
                    break;

                case 4:
                    $max = 360;
                    break;

                case 7:
                    $max = 1;
                    break;

                default:
                    $max = 100;
            }

            $scale = $scale / 100;

            if ($scale < 0) {
                return $base * $scale + $base;
            }

            return ($max - $base) * $scale + $base;
        });
    }

    protected static $libIeHexStr = ['color'];
    protected function libIeHexStr($args)
    {
        $color = $this->coerceColor($args[0]);
        $color[4] = isset($color[4]) ? round(255 * $color[4]) : 255;

        return [Type::T_STRING, '', [sprintf('#%02X%02X%02X%02X', $color[4], $color[1], $color[2], $color[3])]];
    }

    protected static $libRed = ['color'];
    protected function libRed($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[1];
    }

    protected static $libGreen = ['color'];
    protected function libGreen($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[2];
    }

    protected static $libBlue = ['color'];
    protected function libBlue($args)
    {
        $color = $this->coerceColor($args[0]);

        return $color[3];
    }

    protected static $libAlpha = ['color'];
    protected function libAlpha($args)
    {
        if ($color = $this->coerceColor($args[0])) {
            return isset($color[4]) ? $color[4] : 1;
        }

        // this might be the IE function, so return value unchanged
        return null;
    }

    protected static $libOpacity = ['color'];
    protected function libOpacity($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        return $this->libAlpha($args);
    }

    // mix two colors
    protected static $libMix = ['color-1', 'color-2', 'weight:0.5'];
    protected function libMix($args)
    {
        list($first, $second, $weight) = $args;

        $first = $this->assertColor($first);
        $second = $this->assertColor($second);

        if (! isset($weight)) {
            $weight = 0.5;
        } else {
            $weight = $this->coercePercent($weight);
        }

        $firstAlpha = isset($first[4]) ? $first[4] : 1;
        $secondAlpha = isset($second[4]) ? $second[4] : 1;

        $w = $weight * 2 - 1;
        $a = $firstAlpha - $secondAlpha;

        $w1 = (($w * $a === -1 ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2.0;
        $w2 = 1.0 - $w1;

        $new = [Type::T_COLOR,
            $w1 * $first[1] + $w2 * $second[1],
            $w1 * $first[2] + $w2 * $second[2],
            $w1 * $first[3] + $w2 * $second[3],
        ];

        if ($firstAlpha != 1.0 || $secondAlpha != 1.0) {
            $new[] = $firstAlpha * $weight + $secondAlpha * (1 - $weight);
        }

        return $this->fixColor($new);
    }

    protected static $libHsl =[
        ['channels'],
        ['hue', 'saturation', 'lightness'],
        ['hue', 'saturation', 'lightness', 'alpha'] ];
    protected function libHsl($args, $kwargs, $funcName = 'hsl')
    {
        if (\count($args) == 1) {
            if ($args[0][0] !== Type::T_LIST || \count($args[0][2]) < 3 || \count($args[0][2]) > 4) {
                return [Type::T_STRING, '', [$funcName . '(', $args[0], ')']];
            }

            $args = $args[0][2];
        }

        $hue = $this->compileColorPartValue($args[0], 0, 360, false, false, true);
        $saturation = $this->compileColorPartValue($args[1], 0, 100, false);
        $lightness = $this->compileColorPartValue($args[2], 0, 100, false);

        $alpha = null;

        if (\count($args) === 4) {
            $alpha = $this->compileColorPartValue($args[3], 0, 100, false);

            if (! is_numeric($hue) || ! is_numeric($saturation) || ! is_numeric($lightness) || ! is_numeric($alpha)) {
                return [Type::T_STRING, '',
                    [$funcName . '(', $args[0], ', ', $args[1], ', ', $args[2], ', ', $args[3], ')']];
            }
        } else {
            if (! is_numeric($hue) || ! is_numeric($saturation) || ! is_numeric($lightness)) {
                return [Type::T_STRING, '', [$funcName . '(', $args[0], ', ', $args[1], ', ', $args[2], ')']];
            }
        }

        $color = $this->toRGB($hue, $saturation, $lightness);

        if (! \is_null($alpha)) {
            $color[4] = $alpha;
        }

        return $color;
    }

    protected static $libHsla = [
            ['channels'],
            ['hue', 'saturation', 'lightness', 'alpha:1'] ];
    protected function libHsla($args, $kwargs)
    {
        return $this->libHsl($args, $kwargs, 'hsla');
    }

    protected static $libHue = ['color'];
    protected function libHue($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[1], 'deg');
    }

    protected static $libSaturation = ['color'];
    protected function libSaturation($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[2], '%');
    }

    protected static $libLightness = ['color'];
    protected function libLightness($args)
    {
        $color = $this->assertColor($args[0]);
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);

        return new Node\Number($hsl[3], '%');
    }

    protected function adjustHsl($color, $idx, $amount)
    {
        $hsl = $this->toHSL($color[1], $color[2], $color[3]);
        $hsl[$idx] += $amount;
        $out = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);

        if (isset($color[4])) {
            $out[4] = $color[4];
        }

        return $out;
    }

    protected static $libAdjustHue = ['color', 'degrees'];
    protected function libAdjustHue($args)
    {
        $color = $this->assertColor($args[0]);
        $degrees = $this->assertNumber($args[1]);

        return $this->adjustHsl($color, 1, $degrees);
    }

    protected static $libLighten = ['color', 'amount'];
    protected function libLighten($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = Util::checkRange('amount', new Range(0, 100), $args[1], '%');

        return $this->adjustHsl($color, 3, $amount);
    }

    protected static $libDarken = ['color', 'amount'];
    protected function libDarken($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = Util::checkRange('amount', new Range(0, 100), $args[1], '%');

        return $this->adjustHsl($color, 3, -$amount);
    }

    protected static $libSaturate = [['color', 'amount'], ['number']];
    protected function libSaturate($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        $color = $this->assertColor($value);
        $amount = 100 * $this->coercePercent($args[1]);

        return $this->adjustHsl($color, 2, $amount);
    }

    protected static $libDesaturate = ['color', 'amount'];
    protected function libDesaturate($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = 100 * $this->coercePercent($args[1]);

        return $this->adjustHsl($color, 2, -$amount);
    }

    protected static $libGrayscale = ['color'];
    protected function libGrayscale($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        return $this->adjustHsl($this->assertColor($value), 2, -100);
    }

    protected static $libComplement = ['color'];
    protected function libComplement($args)
    {
        return $this->adjustHsl($this->assertColor($args[0]), 1, 180);
    }

    protected static $libInvert = ['color', 'weight:1'];
    protected function libInvert($args)
    {
        list($value, $weight) = $args;

        if (! isset($weight)) {
            $weight = 1;
        } else {
            $weight = $this->coercePercent($weight);
        }

        if ($value[0] === Type::T_NUMBER) {
            return null;
        }

        $color = $this->assertColor($value);
        $inverted = $color;
        $inverted[1] = 255 - $inverted[1];
        $inverted[2] = 255 - $inverted[2];
        $inverted[3] = 255 - $inverted[3];

        if ($weight < 1) {
            return $this->libMix([$inverted, $color, [Type::T_NUMBER, $weight]]);
        }

        return $inverted;
    }

    // increases opacity by amount
    protected static $libOpacify = ['color', 'amount'];
    protected function libOpacify($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = $this->coercePercent($args[1]);

        $color[4] = (isset($color[4]) ? $color[4] : 1) + $amount;
        $color[4] = min(1, max(0, $color[4]));

        return $color;
    }

    protected static $libFadeIn = ['color', 'amount'];
    protected function libFadeIn($args)
    {
        return $this->libOpacify($args);
    }

    // decreases opacity by amount
    protected static $libTransparentize = ['color', 'amount'];
    protected function libTransparentize($args)
    {
        $color = $this->assertColor($args[0]);
        $amount = $this->coercePercent($args[1]);

        $color[4] = (isset($color[4]) ? $color[4] : 1) - $amount;
        $color[4] = min(1, max(0, $color[4]));

        return $color;
    }

    protected static $libFadeOut = ['color', 'amount'];
    protected function libFadeOut($args)
    {
        return $this->libTransparentize($args);
    }

    protected static $libUnquote = ['string'];
    protected function libUnquote($args)
    {
        $str = $args[0];

        if ($str[0] === Type::T_STRING) {
            $str[1] = '';
        }

        return $str;
    }

    protected static $libQuote = ['string'];
    protected function libQuote($args)
    {
        $value = $args[0];

        if ($value[0] === Type::T_STRING && ! empty($value[1])) {
            return $value;
        }

        return [Type::T_STRING, '"', [$value]];
    }

    protected static $libPercentage = ['number'];
    protected function libPercentage($args)
    {
        return new Node\Number($this->coercePercent($args[0]) * 100, '%');
    }

    protected static $libRound = ['number'];
    protected function libRound($args)
    {
        $num = $args[0];

        return new Node\Number(round($num[1]), $num[2]);
    }

    protected static $libFloor = ['number'];
    protected function libFloor($args)
    {
        $num = $args[0];

        return new Node\Number(floor($num[1]), $num[2]);
    }

    protected static $libCeil = ['number'];
    protected function libCeil($args)
    {
        $num = $args[0];

        return new Node\Number(ceil($num[1]), $num[2]);
    }

    protected static $libAbs = ['number'];
    protected function libAbs($args)
    {
        $num = $args[0];

        return new Node\Number(abs($num[1]), $num[2]);
    }

    protected function libMin($args)
    {
        $numbers = $this->getNormalizedNumbers($args);
        $minOriginal = null;
        $minNormalized = null;

        foreach ($numbers as $key => $pair) {
            list($original, $normalized) = $pair;

            if (\is_null($normalized) or \is_null($minNormalized)) {
                if (\is_null($minOriginal) || $original[1] <= $minOriginal[1]) {
                    $minOriginal = $original;
                    $minNormalized = $normalized;
                }
            } elseif ($normalized[1] <= $minNormalized[1]) {
                $minOriginal = $original;
                $minNormalized = $normalized;
            }
        }

        return $minOriginal;
    }

    protected function libMax($args)
    {
        $numbers = $this->getNormalizedNumbers($args);
        $maxOriginal = null;
        $maxNormalized = null;

        foreach ($numbers as $key => $pair) {
            list($original, $normalized) = $pair;

            if (\is_null($normalized) or \is_null($maxNormalized)) {
                if (\is_null($maxOriginal) || $original[1] >= $maxOriginal[1]) {
                    $maxOriginal = $original;
                    $maxNormalized = $normalized;
                }
            } elseif ($normalized[1] >= $maxNormalized[1]) {
                $maxOriginal = $original;
                $maxNormalized = $normalized;
            }
        }

        return $maxOriginal;
    }

    /**
     * Helper to normalize args containing numbers
     *
     * @param array $args
     *
     * @return array
     */
    protected function getNormalizedNumbers($args)
    {
        $unit         = null;
        $originalUnit = null;
        $numbers      = [];

        foreach ($args as $key => $item) {
            if ($item[0] !== Type::T_NUMBER) {
                $this->throwError('%s is not a number', $item[0]);
                break;
            }

            $number = $item->normalize();

            if (empty($unit)) {
                $unit = $number[2];
                $originalUnit = $item->unitStr();
            } elseif ($number[1] && $unit !== $number[2] && ! empty($number[2])) {
                $this->throwError('Incompatible units: "%s" and "%s".', $originalUnit, $item->unitStr());
                break;
            }

            $numbers[$key] = [$args[$key], empty($number[2]) ? null : $number];
        }

        return $numbers;
    }

    protected static $libLength = ['list'];
    protected function libLength($args)
    {
        $list = $this->coerceList($args[0], ',', true);

        return \count($list[2]);
    }

    //protected static $libListSeparator = ['list...'];
    protected function libListSeparator($args)
    {
        if (\count($args) > 1) {
            return 'comma';
        }

        $list = $this->coerceList($args[0]);

        if (\count($list[2]) <= 1) {
            return 'space';
        }

        if ($list[1] === ',') {
            return 'comma';
        }

        return 'space';
    }

    protected static $libNth = ['list', 'n'];
    protected function libNth($args)
    {
        $list = $this->coerceList($args[0], ',', false);
        $n = $this->assertNumber($args[1]);

        if ($n > 0) {
            $n--;
        } elseif ($n < 0) {
            $n += \count($list[2]);
        }

        return isset($list[2][$n]) ? $list[2][$n] : static::$defaultValue;
    }

    protected static $libSetNth = ['list', 'n', 'value'];
    protected function libSetNth($args)
    {
        $list = $this->coerceList($args[0]);
        $n = $this->assertNumber($args[1]);

        if ($n > 0) {
            $n--;
        } elseif ($n < 0) {
            $n += \count($list[2]);
        }

        if (! isset($list[2][$n])) {
            $this->throwError('Invalid argument for "n"');

            return null;
        }

        $list[2][$n] = $args[2];

        return $list;
    }

    protected static $libMapGet = ['map', 'key'];
    protected function libMapGet($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $args[1];

        if (! \is_null($key)) {
            $key = $this->compileStringContent($this->coerceString($key));

            for ($i = \count($map[1]) - 1; $i >= 0; $i--) {
                if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                    return $map[2][$i];
                }
            }
        }

        return static::$null;
    }

    protected static $libMapKeys = ['map'];
    protected function libMapKeys($args)
    {
        $map = $this->assertMap($args[0]);
        $keys = $map[1];

        return [Type::T_LIST, ',', $keys];
    }

    protected static $libMapValues = ['map'];
    protected function libMapValues($args)
    {
        $map = $this->assertMap($args[0]);
        $values = $map[2];

        return [Type::T_LIST, ',', $values];
    }

    protected static $libMapRemove = ['map', 'key'];
    protected function libMapRemove($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $this->compileStringContent($this->coerceString($args[1]));

        for ($i = \count($map[1]) - 1; $i >= 0; $i--) {
            if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                array_splice($map[1], $i, 1);
                array_splice($map[2], $i, 1);
            }
        }

        return $map;
    }

    protected static $libMapHasKey = ['map', 'key'];
    protected function libMapHasKey($args)
    {
        $map = $this->assertMap($args[0]);
        $key = $this->compileStringContent($this->coerceString($args[1]));

        for ($i = \count($map[1]) - 1; $i >= 0; $i--) {
            if ($key === $this->compileStringContent($this->coerceString($map[1][$i]))) {
                return true;
            }
        }

        return false;
    }

    protected static $libMapMerge = ['map-1', 'map-2'];
    protected function libMapMerge($args)
    {
        $map1 = $this->assertMap($args[0]);
        $map2 = $this->assertMap($args[1]);

        foreach ($map2[1] as $i2 => $key2) {
            $key = $this->compileStringContent($this->coerceString($key2));

            foreach ($map1[1] as $i1 => $key1) {
                if ($key === $this->compileStringContent($this->coerceString($key1))) {
                    $map1[2][$i1] = $map2[2][$i2];
                    continue 2;
                }
            }

            $map1[1][] = $map2[1][$i2];
            $map1[2][] = $map2[2][$i2];
        }

        return $map1;
    }

    protected static $libKeywords = ['args'];
    protected function libKeywords($args)
    {
        $this->assertList($args[0]);

        $keys = [];
        $values = [];

        foreach ($args[0][2] as $name => $arg) {
            $keys[] = [Type::T_KEYWORD, $name];
            $values[] = $arg;
        }

        return [Type::T_MAP, $keys, $values];
    }

    protected static $libIsBracketed = ['list'];
    protected function libIsBracketed($args)
    {
        $list = $args[0];
        $this->coerceList($list, ' ');

        if (! empty($list['enclosing']) && $list['enclosing'] === 'bracket') {
            return true;
        }

        return false;
    }

    protected function listSeparatorForJoin($list1, $sep)
    {
        if (! isset($sep)) {
            return $list1[1];
        }

        switch ($this->compileValue($sep)) {
            case 'comma':
                return ',';

            case 'space':
                return ' ';

            default:
                return $list1[1];
        }
    }

    protected static $libJoin = ['list1', 'list2', 'separator:null', 'bracketed:auto'];
    protected function libJoin($args)
    {
        list($list1, $list2, $sep, $bracketed) = $args;

        $list1 = $this->coerceList($list1, ' ', true);
        $list2 = $this->coerceList($list2, ' ', true);
        $sep   = $this->listSeparatorForJoin($list1, $sep);

        if ($bracketed === static::$true) {
            $bracketed = true;
        } elseif ($bracketed === static::$false) {
            $bracketed = false;
        } elseif ($bracketed === [Type::T_KEYWORD, 'auto']) {
            $bracketed = 'auto';
        } elseif ($bracketed === static::$null) {
            $bracketed = false;
        } else {
            $bracketed = $this->compileValue($bracketed);
            $bracketed = ! ! $bracketed;

            if ($bracketed === true) {
                $bracketed = true;
            }
        }

        if ($bracketed === 'auto') {
            $bracketed = false;

            if (! empty($list1['enclosing']) && $list1['enclosing'] === 'bracket') {
                $bracketed = true;
            }
        }

        $res = [Type::T_LIST, $sep, array_merge($list1[2], $list2[2])];

        if (isset($list1['enclosing'])) {
            $res['enlcosing'] = $list1['enclosing'];
        }

        if ($bracketed) {
            $res['enclosing'] = 'bracket';
        }

        return $res;
    }

    protected static $libAppend = ['list', 'val', 'separator:null'];
    protected function libAppend($args)
    {
        list($list1, $value, $sep) = $args;

        $list1 = $this->coerceList($list1, ' ', true);
        $sep   = $this->listSeparatorForJoin($list1, $sep);
        $res   = [Type::T_LIST, $sep, array_merge($list1[2], [$value])];

        if (isset($list1['enclosing'])) {
            $res['enclosing'] = $list1['enclosing'];
        }

        return $res;
    }

    protected function libZip($args)
    {
        foreach ($args as $key => $arg) {
            $args[$key] = $this->coerceList($arg);
        }

        $lists = [];
        $firstList = array_shift($args);

        foreach ($firstList[2] as $key => $item) {
            $list = [Type::T_LIST, '', [$item]];

            foreach ($args as $arg) {
                if (isset($arg[2][$key])) {
                    $list[2][] = $arg[2][$key];
                } else {
                    break 2;
                }
            }

            $lists[] = $list;
        }

        return [Type::T_LIST, ',', $lists];
    }

    protected static $libTypeOf = ['value'];
    protected function libTypeOf($args)
    {
        $value = $args[0];

        switch ($value[0]) {
            case Type::T_KEYWORD:
                if ($value === static::$true || $value === static::$false) {
                    return 'bool';
                }

                if ($this->coerceColor($value)) {
                    return 'color';
                }

                // fall-thru
            case Type::T_FUNCTION:
                return 'string';

            case Type::T_LIST:
                if (isset($value[3]) && $value[3]) {
                    return 'arglist';
                }

                // fall-thru
            default:
                return $value[0];
        }
    }

    protected static $libUnit = ['number'];
    protected function libUnit($args)
    {
        $num = $args[0];

        if ($num[0] === Type::T_NUMBER) {
            return [Type::T_STRING, '"', [$num->unitStr()]];
        }

        return '';
    }

    protected static $libUnitless = ['number'];
    protected function libUnitless($args)
    {
        $value = $args[0];

        return $value[0] === Type::T_NUMBER && $value->unitless();
    }

    protected static $libComparable = ['number-1', 'number-2'];
    protected function libComparable($args)
    {
        list($number1, $number2) = $args;

        if (! isset($number1[0]) || $number1[0] !== Type::T_NUMBER ||
            ! isset($number2[0]) || $number2[0] !== Type::T_NUMBER
        ) {
            $this->throwError('Invalid argument(s) for "comparable"');

            return null;
        }

        $number1 = $number1->normalize();
        $number2 = $number2->normalize();

        return $number1[2] === $number2[2] || $number1->unitless() || $number2->unitless();
    }

    protected static $libStrIndex = ['string', 'substring'];
    protected function libStrIndex($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $substring = $this->coerceString($args[1]);
        $substringContent = $this->compileStringContent($substring);

        $result = strpos($stringContent, $substringContent);

        return $result === false ? static::$null : new Node\Number($result + 1, '');
    }

    protected static $libStrInsert = ['string', 'insert', 'index'];
    protected function libStrInsert($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $insert = $this->coerceString($args[1]);
        $insertContent = $this->compileStringContent($insert);

        list(, $index) = $args[2];

        $string[2] = [substr_replace($stringContent, $insertContent, $index - 1, 0)];

        return $string;
    }

    protected static $libStrLength = ['string'];
    protected function libStrLength($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        return new Node\Number(\strlen($stringContent), '');
    }

    protected static $libStrSlice = ['string', 'start-at', 'end-at:-1'];
    protected function libStrSlice($args)
    {
        if (isset($args[2]) && ! $args[2][1]) {
            return static::$nullString;
        }

        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $start = (int) $args[1][1];

        if ($start > 0) {
            $start--;
        }

        $end    = isset($args[2]) ? (int) $args[2][1] : -1;
        $length = $end < 0 ? $end + 1 : ($end > 0 ? $end - $start : $end);

        $string[2] = $length
            ? [substr($stringContent, $start, $length)]
            : [substr($stringContent, $start)];

        return $string;
    }

    protected static $libToLowerCase = ['string'];
    protected function libToLowerCase($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $string[2] = [\function_exists('mb_strtolower') ? mb_strtolower($stringContent) : strtolower($stringContent)];

        return $string;
    }

    protected static $libToUpperCase = ['string'];
    protected function libToUpperCase($args)
    {
        $string = $this->coerceString($args[0]);
        $stringContent = $this->compileStringContent($string);

        $string[2] = [\function_exists('mb_strtoupper') ? mb_strtoupper($stringContent) : strtoupper($stringContent)];

        return $string;
    }

    protected static $libFeatureExists = ['feature'];
    protected function libFeatureExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->toBool(
            \array_key_exists($name, $this->registeredFeatures) ? $this->registeredFeatures[$name] : false
        );
    }

    protected static $libFunctionExists = ['name'];
    protected function libFunctionExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        // user defined functions
        if ($this->has(static::$namespaces['function'] . $name)) {
            return true;
        }

        $name = $this->normalizeName($name);

        if (isset($this->userFunctions[$name])) {
            return true;
        }

        // built-in functions
        $f = $this->getBuiltinFunction($name);

        return $this->toBool(\is_callable($f));
    }

    protected static $libGlobalVariableExists = ['name'];
    protected function libGlobalVariableExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has($name, $this->rootEnv);
    }

    protected static $libMixinExists = ['name'];
    protected function libMixinExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has(static::$namespaces['mixin'] . $name);
    }

    protected static $libVariableExists = ['name'];
    protected function libVariableExists($args)
    {
        $string = $this->coerceString($args[0]);
        $name = $this->compileStringContent($string);

        return $this->has($name);
    }

    /**
     * Workaround IE7's content counter bug.
     *
     * @param array $args
     *
     * @return array
     */
    protected function libCounter($args)
    {
        $list = array_map([$this, 'compileValue'], $args);

        return [Type::T_STRING, '', ['counter(' . implode(',', $list) . ')']];
    }

    protected static $libRandom = ['limit:1'];
    protected function libRandom($args)
    {
        if (isset($args[0])) {
            $n = $this->assertNumber($args[0]);

            if ($n < 1) {
                $this->throwError("\$limit must be greater than or equal to 1");

                return null;
            }

            if ($n - \intval($n) > 0) {
                $this->throwError("Expected \$limit to be an integer but got $n for `random`");

                return null;
            }

            return new Node\Number(mt_rand(1, \intval($n)), '');
        }

        return new Node\Number(mt_rand(1, mt_getrandmax()), '');
    }

    protected function libUniqueId()
    {
        static $id;

        if (! isset($id)) {
            $id = PHP_INT_SIZE === 4
                ? mt_rand(0, pow(36, 5)) . str_pad(mt_rand(0, pow(36, 5)) % 10000000, 7, '0', STR_PAD_LEFT)
                : mt_rand(0, pow(36, 8));
        }

        $id += mt_rand(0, 10) + 1;

        return [Type::T_STRING, '', ['u' . str_pad(base_convert($id, 10, 36), 8, '0', STR_PAD_LEFT)]];
    }

    protected function inspectFormatValue($value, $force_enclosing_display = false)
    {
        if ($value === static::$null) {
            $value = [Type::T_KEYWORD, 'null'];
        }

        $stringValue = [$value];

        if ($value[0] === Type::T_LIST) {
            if (end($value[2]) === static::$null) {
                array_pop($value[2]);
                $value[2][] = [Type::T_STRING, '', ['']];
                $force_enclosing_display = true;
            }

            if (! empty($value['enclosing']) &&
                ($force_enclosing_display ||
                    ($value['enclosing'] === 'bracket') ||
                    ! \count($value[2]))
            ) {
                $value['enclosing'] = 'forced_'.$value['enclosing'];
                $force_enclosing_display = true;
            }

            foreach ($value[2] as $k => $listelement) {
                $value[2][$k] = $this->inspectFormatValue($listelement, $force_enclosing_display);
            }

            $stringValue = [$value];
        }

        return [Type::T_STRING, '', $stringValue];
    }

    protected static $libInspect = ['value'];
    protected function libInspect($args)
    {
        $value = $args[0];

        return $this->inspectFormatValue($value);
    }

    /**
     * Preprocess selector args
     *
     * @param array $arg
     *
     * @return array|boolean
     */
    protected function getSelectorArg($arg)
    {
        static $parser = null;

        if (\is_null($parser)) {
            $parser = $this->parserFactory(__METHOD__);
        }

        $arg = $this->libUnquote([$arg]);
        $arg = $this->compileValue($arg);

        $parsedSelector = [];

        if ($parser->parseSelector($arg, $parsedSelector)) {
            $selector = $this->evalSelectors($parsedSelector);
            $gluedSelector = $this->glueFunctionSelectors($selector);

            return $gluedSelector;
        }

        return false;
    }

    /**
     * Postprocess selector to output in right format
     *
     * @param array $selectors
     *
     * @return string
     */
    protected function formatOutputSelector($selectors)
    {
        $selectors = $this->collapseSelectors($selectors, true);

        return $selectors;
    }

    protected static $libIsSuperselector = ['super', 'sub'];
    protected function libIsSuperselector($args)
    {
        list($super, $sub) = $args;

        $super = $this->getSelectorArg($super);
        $sub = $this->getSelectorArg($sub);

        return $this->isSuperSelector($super, $sub);
    }

    /**
     * Test a $super selector again $sub
     *
     * @param array $super
     * @param array $sub
     *
     * @return boolean
     */
    protected function isSuperSelector($super, $sub)
    {
        // one and only one selector for each arg
        if (! $super || \count($super) !== 1) {
            $this->throwError("Invalid super selector for isSuperSelector()");
        }

        if (! $sub || \count($sub) !== 1) {
            $this->throwError("Invalid sub selector for isSuperSelector()");
        }

        $super = reset($super);
        $sub = reset($sub);

        $i = 0;
        $nextMustMatch = false;

        foreach ($super as $node) {
            $compound = '';

            array_walk_recursive(
                $node,
                function ($value, $key) use (&$compound) {
                    $compound .= $value;
                }
            );

            if ($this->isImmediateRelationshipCombinator($compound)) {
                if ($node !== $sub[$i]) {
                    return false;
                }

                $nextMustMatch = true;
                $i++;
            } else {
                while ($i < \count($sub) && ! $this->isSuperPart($node, $sub[$i])) {
                    if ($nextMustMatch) {
                        return false;
                    }

                    $i++;
                }

                if ($i >= \count($sub)) {
                    return false;
                }

                $nextMustMatch = false;
                $i++;
            }
        }

        return true;
    }

    /**
     * Test a part of super selector again a part of sub selector
     *
     * @param array $superParts
     * @param array $subParts
     *
     * @return boolean
     */
    protected function isSuperPart($superParts, $subParts)
    {
        $i = 0;

        foreach ($superParts as $superPart) {
            while ($i < \count($subParts) && $subParts[$i] !== $superPart) {
                $i++;
            }

            if ($i >= \count($subParts)) {
                return false;
            }

            $i++;
        }

        return true;
    }

    protected static $libSelectorAppend = ['selector...'];
    protected function libSelectorAppend($args)
    {
        // get the selector... list
        $args = reset($args);
        $args = $args[2];

        if (\count($args) < 1) {
            $this->throwError("selector-append() needs at least 1 argument");
        }

        $selectors = array_map([$this, 'getSelectorArg'], $args);

        return $this->formatOutputSelector($this->selectorAppend($selectors));
    }

    /**
     * Append parts of the last selector in the list to the previous, recursively
     *
     * @param array $selectors
     *
     * @return array
     *
     * @throws \ScssPhp\ScssPhp\Exception\CompilerException
     */
    protected function selectorAppend($selectors)
    {
        $lastSelectors = array_pop($selectors);

        if (! $lastSelectors) {
            $this->throwError("Invalid selector list in selector-append()");
        }

        while (\count($selectors)) {
            $previousSelectors = array_pop($selectors);

            if (! $previousSelectors) {
                $this->throwError("Invalid selector list in selector-append()");
            }

            // do the trick, happening $lastSelector to $previousSelector
            $appended = [];

            foreach ($lastSelectors as $lastSelector) {
                $previous = $previousSelectors;

                foreach ($lastSelector as $lastSelectorParts) {
                    foreach ($lastSelectorParts as $lastSelectorPart) {
                        foreach ($previous as $i => $previousSelector) {
                            foreach ($previousSelector as $j => $previousSelectorParts) {
                                $previous[$i][$j][] = $lastSelectorPart;
                            }
                        }
                    }
                }

                foreach ($previous as $ps) {
                    $appended[] = $ps;
                }
            }

            $lastSelectors = $appended;
        }

        return $lastSelectors;
    }

    protected static $libSelectorExtend = ['selectors', 'extendee', 'extender'];
    protected function libSelectorExtend($args)
    {
        list($selectors, $extendee, $extender) = $args;

        $selectors = $this->getSelectorArg($selectors);
        $extendee  = $this->getSelectorArg($extendee);
        $extender  = $this->getSelectorArg($extender);

        if (! $selectors || ! $extendee || ! $extender) {
            $this->throwError("selector-extend() invalid arguments");
        }

        $extended = $this->extendOrReplaceSelectors($selectors, $extendee, $extender);

        return $this->formatOutputSelector($extended);
    }

    protected static $libSelectorReplace = ['selectors', 'original', 'replacement'];
    protected function libSelectorReplace($args)
    {
        list($selectors, $original, $replacement) = $args;

        $selectors   = $this->getSelectorArg($selectors);
        $original    = $this->getSelectorArg($original);
        $replacement = $this->getSelectorArg($replacement);

        if (! $selectors || ! $original || ! $replacement) {
            $this->throwError("selector-replace() invalid arguments");
        }

        $replaced = $this->extendOrReplaceSelectors($selectors, $original, $replacement, true);

        return $this->formatOutputSelector($replaced);
    }

    /**
     * Extend/replace in selectors
     * used by selector-extend and selector-replace that use the same logic
     *
     * @param array   $selectors
     * @param array   $extendee
     * @param array   $extender
     * @param boolean $replace
     *
     * @return array
     */
    protected function extendOrReplaceSelectors($selectors, $extendee, $extender, $replace = false)
    {
        $saveExtends = $this->extends;
        $saveExtendsMap = $this->extendsMap;

        $this->extends = [];
        $this->extendsMap = [];

        foreach ($extendee as $es) {
            // only use the first one
            $this->pushExtends(reset($es), $extender, null);
        }

        $extended = [];

        foreach ($selectors as $selector) {
            if (! $replace) {
                $extended[] = $selector;
            }

            $n = \count($extended);

            $this->matchExtends($selector, $extended);

            // if didnt match, keep the original selector if we are in a replace operation
            if ($replace and \count($extended) === $n) {
                $extended[] = $selector;
            }
        }

        $this->extends = $saveExtends;
        $this->extendsMap = $saveExtendsMap;

        return $extended;
    }

    protected static $libSelectorNest = ['selector...'];
    protected function libSelectorNest($args)
    {
        // get the selector... list
        $args = reset($args);
        $args = $args[2];

        if (\count($args) < 1) {
            $this->throwError("selector-nest() needs at least 1 argument");
        }

        $selectorsMap = array_map([$this, 'getSelectorArg'], $args);
        $envs = [];

        foreach ($selectorsMap as $selectors) {
            $env = new Environment();
            $env->selectors = $selectors;

            $envs[] = $env;
        }

        $envs            = array_reverse($envs);
        $env             = $this->extractEnv($envs);
        $outputSelectors = $this->multiplySelectors($env);

        return $this->formatOutputSelector($outputSelectors);
    }

    protected static $libSelectorParse = ['selectors'];
    protected function libSelectorParse($args)
    {
        $selectors = reset($args);
        $selectors = $this->getSelectorArg($selectors);

        return $this->formatOutputSelector($selectors);
    }

    protected static $libSelectorUnify = ['selectors1', 'selectors2'];
    protected function libSelectorUnify($args)
    {
        list($selectors1, $selectors2) = $args;

        $selectors1 = $this->getSelectorArg($selectors1);
        $selectors2 = $this->getSelectorArg($selectors2);

        if (! $selectors1 || ! $selectors2) {
            $this->throwError("selector-unify() invalid arguments");
        }

        // only consider the first compound of each
        $compound1 = reset($selectors1);
        $compound2 = reset($selectors2);

        // unify them and that's it
        $unified = $this->unifyCompoundSelectors($compound1, $compound2);

        return $this->formatOutputSelector($unified);
    }

    /**
     * The selector-unify magic as its best
     * (at least works as expected on test cases)
     *
     * @param array $compound1
     * @param array $compound2
     *
     * @return array|mixed
     */
    protected function unifyCompoundSelectors($compound1, $compound2)
    {
        if (! \count($compound1)) {
            return $compound2;
        }

        if (! \count($compound2)) {
            return $compound1;
        }

        // check that last part are compatible
        $lastPart1 = array_pop($compound1);
        $lastPart2 = array_pop($compound2);
        $last      = $this->mergeParts($lastPart1, $lastPart2);

        if (! $last) {
            return [[]];
        }

        $unifiedCompound = [$last];
        $unifiedSelectors = [$unifiedCompound];

        // do the rest
        while (\count($compound1) || \count($compound2)) {
            $part1 = end($compound1);
            $part2 = end($compound2);

            if ($part1 && ($match2 = $this->matchPartInCompound($part1, $compound2))) {
                list($compound2, $part2, $after2) = $match2;

                if ($after2) {
                    $unifiedSelectors = $this->prependSelectors($unifiedSelectors, $after2);
                }

                $c = $this->mergeParts($part1, $part2);
                $unifiedSelectors = $this->prependSelectors($unifiedSelectors, [$c]);

                $part1 = $part2 = null;

                array_pop($compound1);
            }

            if ($part2 && ($match1 = $this->matchPartInCompound($part2, $compound1))) {
                list($compound1, $part1, $after1) = $match1;

                if ($after1) {
                    $unifiedSelectors = $this->prependSelectors($unifiedSelectors, $after1);
                }

                $c = $this->mergeParts($part2, $part1);
                $unifiedSelectors = $this->prependSelectors($unifiedSelectors, [$c]);

                $part1 = $part2 = null;

                array_pop($compound2);
            }

            $new = [];

            if ($part1 && $part2) {
                array_pop($compound1);
                array_pop($compound2);

                $s   = $this->prependSelectors($unifiedSelectors, [$part2]);
                $new = array_merge($new, $this->prependSelectors($s, [$part1]));
                $s   = $this->prependSelectors($unifiedSelectors, [$part1]);
                $new = array_merge($new, $this->prependSelectors($s, [$part2]));
            } elseif ($part1) {
                array_pop($compound1);

                $new = array_merge($new, $this->prependSelectors($unifiedSelectors, [$part1]));
            } elseif ($part2) {
                array_pop($compound2);

                $new = array_merge($new, $this->prependSelectors($unifiedSelectors, [$part2]));
            }

            if ($new) {
                $unifiedSelectors = $new;
            }
        }

        return $unifiedSelectors;
    }

    /**
     * Prepend each selector from $selectors with $parts
     *
     * @param array $selectors
     * @param array $parts
     *
     * @return array
     */
    protected function prependSelectors($selectors, $parts)
    {
        $new = [];

        foreach ($selectors as $compoundSelector) {
            array_unshift($compoundSelector, $parts);

            $new[] = $compoundSelector;
        }

        return $new;
    }

    /**
     * Try to find a matching part in a compound:
     * - with same html tag name
     * - with some class or id or something in common
     *
     * @param array $part
     * @param array $compound
     *
     * @return array|boolean
     */
    protected function matchPartInCompound($part, $compound)
    {
        $partTag = $this->findTagName($part);
        $before  = $compound;
        $after   = [];

        // try to find a match by tag name first
        while (\count($before)) {
            $p = array_pop($before);

            if ($partTag && $partTag !== '*' && $partTag == $this->findTagName($p)) {
                return [$before, $p, $after];
            }

            $after[] = $p;
        }

        // try again matching a non empty intersection and a compatible tagname
        $before = $compound;
        $after = [];

        while (\count($before)) {
            $p = array_pop($before);

            if ($this->checkCompatibleTags($partTag, $this->findTagName($p))) {
                if (\count(array_intersect($part, $p))) {
                    return [$before, $p, $after];
                }
            }

            $after[] = $p;
        }

        return false;
    }

    /**
     * Merge two part list taking care that
     * - the html tag is coming first - if any
     * - the :something are coming last
     *
     * @param array $parts1
     * @param array $parts2
     *
     * @return array
     */
    protected function mergeParts($parts1, $parts2)
    {
        $tag1 = $this->findTagName($parts1);
        $tag2 = $this->findTagName($parts2);
        $tag  = $this->checkCompatibleTags($tag1, $tag2);

        // not compatible tags
        if ($tag === false) {
            return [];
        }

        if ($tag) {
            if ($tag1) {
                $parts1 = array_diff($parts1, [$tag1]);
            }

            if ($tag2) {
                $parts2 = array_diff($parts2, [$tag2]);
            }
        }

        $mergedParts = array_merge($parts1, $parts2);
        $mergedOrderedParts = [];

        foreach ($mergedParts as $part) {
            if (strpos($part, ':') === 0) {
                $mergedOrderedParts[] = $part;
            }
        }

        $mergedParts = array_diff($mergedParts, $mergedOrderedParts);
        $mergedParts = array_merge($mergedParts, $mergedOrderedParts);

        if ($tag) {
            array_unshift($mergedParts, $tag);
        }

        return $mergedParts;
    }

    /**
     * Check the compatibility between two tag names:
     * if both are defined they should be identical or one has to be '*'
     *
     * @param string $tag1
     * @param string $tag2
     *
     * @return array|boolean
     */
    protected function checkCompatibleTags($tag1, $tag2)
    {
        $tags = [$tag1, $tag2];
        $tags = array_unique($tags);
        $tags = array_filter($tags);

        if (\count($tags) > 1) {
            $tags = array_diff($tags, ['*']);
        }

        // not compatible nodes
        if (\count($tags) > 1) {
            return false;
        }

        return $tags;
    }

    /**
     * Find the html tag name in a selector parts list
     *
     * @param array $parts
     *
     * @return mixed|string
     */
    protected function findTagName($parts)
    {
        foreach ($parts as $part) {
            if (! preg_match('/^[\[.:#%_-]/', $part)) {
                return $part;
            }
        }

        return '';
    }

    protected static $libSimpleSelectors = ['selector'];
    protected function libSimpleSelectors($args)
    {
        $selector = reset($args);
        $selector = $this->getSelectorArg($selector);

        // remove selectors list layer, keeping the first one
        $selector = reset($selector);

        // remove parts list layer, keeping the first part
        $part = reset($selector);

        $listParts = [];

        foreach ($part as $p) {
            $listParts[] = [Type::T_STRING, '', [$p]];
        }

        return [Type::T_LIST, ',', $listParts];
    }

    protected static $libScssphpGlob = ['pattern'];
    protected function libScssphpGlob($args)
    {
        $string = $this->coerceString($args[0]);
        $pattern = $this->compileStringContent($string);
        $matches = glob($pattern);
        $listParts = [];

        foreach ($matches as $match) {
            if (! is_file($match)) {
                continue;
            }

            $listParts[] = [Type::T_STRING, '"', [$match]];
        }

        return [Type::T_LIST, ',', $listParts];
    }
}
