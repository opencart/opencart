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

use ScssPhp\ScssPhp\Block;
use ScssPhp\ScssPhp\Cache;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Exception\ParserException;
use ScssPhp\ScssPhp\Node;
use ScssPhp\ScssPhp\Type;

/**
 * Parser
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class Parser
{
    const SOURCE_INDEX  = -1;
    const SOURCE_LINE   = -2;
    const SOURCE_COLUMN = -3;

    /**
     * @var array
     */
    protected static $precedence = [
        '='   => 0,
        'or'  => 1,
        'and' => 2,
        '=='  => 3,
        '!='  => 3,
        '<=>' => 3,
        '<='  => 4,
        '>='  => 4,
        '<'   => 4,
        '>'   => 4,
        '+'   => 5,
        '-'   => 5,
        '*'   => 6,
        '/'   => 6,
        '%'   => 6,
    ];

    protected static $commentPattern;
    protected static $operatorPattern;
    protected static $whitePattern;

    protected $cache;

    private $sourceName;
    private $sourceIndex;
    private $sourcePositions;
    private $charset;
    private $count;
    private $env;
    private $inParens;
    private $eatWhiteDefault;
    private $discardComments;
    private $allowVars;
    private $buffer;
    private $utf8;
    private $encoding;
    private $patternModifiers;
    private $commentsSeen;

    private $cssOnly;

    /**
     * Constructor
     *
     * @api
     *
     * @param string                 $sourceName
     * @param integer                $sourceIndex
     * @param string                 $encoding
     * @param \ScssPhp\ScssPhp\Cache $cache
     */
    public function __construct($sourceName, $sourceIndex = 0, $encoding = 'utf-8', $cache = null, $cssOnly = false)
    {
        $this->sourceName       = $sourceName ?: '(stdin)';
        $this->sourceIndex      = $sourceIndex;
        $this->charset          = null;
        $this->utf8             = ! $encoding || strtolower($encoding) === 'utf-8';
        $this->patternModifiers = $this->utf8 ? 'Aisu' : 'Ais';
        $this->commentsSeen     = [];
        $this->commentsSeen     = [];
        $this->allowVars        = true;
        $this->cssOnly          = $cssOnly;

        if (empty(static::$operatorPattern)) {
            static::$operatorPattern = '([*\/%+-]|[!=]\=|\>\=?|\<\=\>|\<\=?|and|or)';

            $commentSingle      = '\/\/';
            $commentMultiLeft   = '\/\*';
            $commentMultiRight  = '\*\/';

            static::$commentPattern = $commentMultiLeft . '.*?' . $commentMultiRight;
            static::$whitePattern = $this->utf8
                ? '/' . $commentSingle . '[^\n]*\s*|(' . static::$commentPattern . ')\s*|\s+/AisuS'
                : '/' . $commentSingle . '[^\n]*\s*|(' . static::$commentPattern . ')\s*|\s+/AisS';
        }

        if ($cache) {
            $this->cache = $cache;
        }
    }

    /**
     * Get source file name
     *
     * @api
     *
     * @return string
     */
    public function getSourceName()
    {
        return $this->sourceName;
    }

    /**
     * Throw parser error
     *
     * @api
     *
     * @param string $msg
     *
     * @throws \ScssPhp\ScssPhp\Exception\ParserException
     */
    public function throwParseError($msg = 'parse error')
    {
        list($line, $column) = $this->getSourcePosition($this->count);

        $loc = empty($this->sourceName)
             ? "line: $line, column: $column"
             : "$this->sourceName on line $line, at column $column";

        if ($this->peek("(.*?)(\n|$)", $m, $this->count)) {
            $this->restoreEncoding();

            throw new ParserException("$msg: failed at `$m[1]` $loc");
        }

        $this->restoreEncoding();

        throw new ParserException("$msg: $loc");
    }

    /**
     * Parser buffer
     *
     * @api
     *
     * @param string $buffer
     *
     * @return \ScssPhp\ScssPhp\Block
     */
    public function parse($buffer)
    {
        if ($this->cache) {
            $cacheKey = $this->sourceName . ":" . md5($buffer);
            $parseOptions = [
                'charset' => $this->charset,
                'utf8' => $this->utf8,
            ];
            $v = $this->cache->getCache("parse", $cacheKey, $parseOptions);

            if (! \is_null($v)) {
                return $v;
            }
        }

        // strip BOM (byte order marker)
        if (substr($buffer, 0, 3) === "\xef\xbb\xbf") {
            $buffer = substr($buffer, 3);
        }

        $this->buffer          = rtrim($buffer, "\x00..\x1f");
        $this->count           = 0;
        $this->env             = null;
        $this->inParens        = false;
        $this->eatWhiteDefault = true;

        $this->saveEncoding();
        $this->extractLineNumbers($buffer);

        $this->pushBlock(null); // root block
        $this->whitespace();
        $this->pushBlock(null);
        $this->popBlock();

        while ($this->parseChunk()) {
            ;
        }

        if ($this->count !== \strlen($this->buffer)) {
            $this->throwParseError();
        }

        if (! empty($this->env->parent)) {
            $this->throwParseError('unclosed block');
        }

        if ($this->charset) {
            array_unshift($this->env->children, $this->charset);
        }

        $this->restoreEncoding();

        if ($this->cache) {
            $this->cache->setCache("parse", $cacheKey, $this->env, $parseOptions);
        }

        return $this->env;
    }

    /**
     * Parse a value or value list
     *
     * @api
     *
     * @param string       $buffer
     * @param string|array $out
     *
     * @return boolean
     */
    public function parseValue($buffer, &$out)
    {
        $this->count           = 0;
        $this->env             = null;
        $this->inParens        = false;
        $this->eatWhiteDefault = true;
        $this->buffer          = (string) $buffer;

        $this->saveEncoding();

        $list = $this->valueList($out);

        $this->restoreEncoding();

        return $list;
    }

    /**
     * Parse a selector or selector list
     *
     * @api
     *
     * @param string       $buffer
     * @param string|array $out
     *
     * @return boolean
     */
    public function parseSelector($buffer, &$out)
    {
        $this->count           = 0;
        $this->env             = null;
        $this->inParens        = false;
        $this->eatWhiteDefault = true;
        $this->buffer          = (string) $buffer;

        $this->saveEncoding();

        $selector = $this->selectors($out);

        $this->restoreEncoding();

        return $selector;
    }

    /**
     * Parse a media Query
     *
     * @api
     *
     * @param string       $buffer
     * @param string|array $out
     *
     * @return boolean
     */
    public function parseMediaQueryList($buffer, &$out)
    {
        $this->count           = 0;
        $this->env             = null;
        $this->inParens        = false;
        $this->eatWhiteDefault = true;
        $this->buffer          = (string) $buffer;

        $this->saveEncoding();

        $isMediaQuery = $this->mediaQueryList($out);

        $this->restoreEncoding();

        return $isMediaQuery;
    }

    /**
     * Parse a single chunk off the head of the buffer and append it to the
     * current parse environment.
     *
     * Returns false when the buffer is empty, or when there is an error.
     *
     * This function is called repeatedly until the entire document is
     * parsed.
     *
     * This parser is most similar to a recursive descent parser. Single
     * functions represent discrete grammatical rules for the language, and
     * they are able to capture the text that represents those rules.
     *
     * Consider the function Compiler::keyword(). (All parse functions are
     * structured the same.)
     *
     * The function takes a single reference argument. When calling the
     * function it will attempt to match a keyword on the head of the buffer.
     * If it is successful, it will place the keyword in the referenced
     * argument, advance the position in the buffer, and return true. If it
     * fails then it won't advance the buffer and it will return false.
     *
     * All of these parse functions are powered by Compiler::match(), which behaves
     * the same way, but takes a literal regular expression. Sometimes it is
     * more convenient to use match instead of creating a new function.
     *
     * Because of the format of the functions, to parse an entire string of
     * grammatical rules, you can chain them together using &&.
     *
     * But, if some of the rules in the chain succeed before one fails, then
     * the buffer position will be left at an invalid state. In order to
     * avoid this, Compiler::seek() is used to remember and set buffer positions.
     *
     * Before parsing a chain, use $s = $this->count to remember the current
     * position into $s. Then if a chain fails, use $this->seek($s) to
     * go back where we started.
     *
     * @return boolean
     */
    protected function parseChunk()
    {
        $s = $this->count;

        // the directives
        if (isset($this->buffer[$this->count]) && $this->buffer[$this->count] === '@') {
            if ($this->literal('@at-root', 8) &&
                ($this->selectors($selector) || true) &&
                ($this->map($with) || true) &&
                (($this->matchChar('(')
                    && $this->interpolation($with)
                    && $this->matchChar(')')) || true) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $atRoot = $this->pushSpecialBlock(Type::T_AT_ROOT, $s);
                $atRoot->selector = $selector;
                $atRoot->with     = $with;

                return true;
            }

            $this->seek($s);

            if ($this->literal('@media', 6) && $this->mediaQueryList($mediaQueryList) && $this->matchChar('{', false)) {
                $media = $this->pushSpecialBlock(Type::T_MEDIA, $s);
                $media->queryList = $mediaQueryList[2];

                return true;
            }

            $this->seek($s);

            if ($this->literal('@mixin', 6) &&
                $this->keyword($mixinName) &&
                ($this->argumentDef($args) || true) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $mixin = $this->pushSpecialBlock(Type::T_MIXIN, $s);
                $mixin->name = $mixinName;
                $mixin->args = $args;

                return true;
            }

            $this->seek($s);

            if ($this->literal('@include', 8) &&
                $this->keyword($mixinName) &&
                ($this->matchChar('(') &&
                    ($this->argValues($argValues) || true) &&
                    $this->matchChar(')') || true) &&
                ($this->end() ||
                    ($this->literal('using', 5) &&
                        $this->argumentDef($argUsing) &&
                        ($this->end() || $this->matchChar('{') && $hasBlock = true)) ||
                    $this->matchChar('{') && $hasBlock = true)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $child = [
                    Type::T_INCLUDE,
                    $mixinName,
                    isset($argValues) ? $argValues : null,
                    null,
                    isset($argUsing) ? $argUsing : null
                ];

                if (! empty($hasBlock)) {
                    $include = $this->pushSpecialBlock(Type::T_INCLUDE, $s);
                    $include->child = $child;
                } else {
                    $this->append($child, $s);
                }

                return true;
            }

            $this->seek($s);

            if ($this->literal('@scssphp-import-once', 20) &&
                $this->valueList($importPath) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_SCSSPHP_IMPORT_ONCE, $importPath], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@import', 7) &&
                $this->valueList($importPath) &&
                $this->end()
            ) {
                $this->append([Type::T_IMPORT, $importPath], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@import', 7) &&
                $this->url($importPath) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_IMPORT, $importPath], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@extend', 7) &&
                $this->selectors($selectors) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                // check for '!flag'
                $optional = $this->stripOptionalFlag($selectors);
                $this->append([Type::T_EXTEND, $selectors, $optional], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@function', 9) &&
                $this->keyword($fnName) &&
                $this->argumentDef($args) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $func = $this->pushSpecialBlock(Type::T_FUNCTION, $s);
                $func->name = $fnName;
                $func->args = $args;

                return true;
            }

            $this->seek($s);

            if ($this->literal('@break', 6) && $this->end()) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_BREAK], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@continue', 9) && $this->end()) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_CONTINUE], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@return', 7) && ($this->valueList($retVal) || true) && $this->end()) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_RETURN, isset($retVal) ? $retVal : [Type::T_NULL]], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@each', 5) &&
                $this->genericList($varNames, 'variable', ',', false) &&
                $this->literal('in', 2) &&
                $this->valueList($list) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $each = $this->pushSpecialBlock(Type::T_EACH, $s);

                foreach ($varNames[2] as $varName) {
                    $each->vars[] = $varName[1];
                }

                $each->list = $list;

                return true;
            }

            $this->seek($s);

            if ($this->literal('@while', 6) &&
                $this->expression($cond) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $while = $this->pushSpecialBlock(Type::T_WHILE, $s);
                $while->cond = $cond;

                return true;
            }

            $this->seek($s);

            if ($this->literal('@for', 4) &&
                $this->variable($varName) &&
                $this->literal('from', 4) &&
                $this->expression($start) &&
                ($this->literal('through', 7) ||
                    ($forUntil = true && $this->literal('to', 2))) &&
                $this->expression($end) &&
                $this->matchChar('{', false)
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $for = $this->pushSpecialBlock(Type::T_FOR, $s);
                $for->var   = $varName[1];
                $for->start = $start;
                $for->end   = $end;
                $for->until = isset($forUntil);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@if', 3) && $this->valueList($cond) && $this->matchChar('{', false)) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $if = $this->pushSpecialBlock(Type::T_IF, $s);

                while ($cond[0] === Type::T_LIST
                    && ! empty($cond['enclosing'])
                    && $cond['enclosing'] === 'parent'
                    && \count($cond[2]) == 1) {
                    $cond = reset($cond[2]);
                }

                $if->cond  = $cond;
                $if->cases = [];

                return true;
            }

            $this->seek($s);

            if ($this->literal('@debug', 6) &&
                $this->valueList($value) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_DEBUG, $value], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@warn', 5) &&
                $this->valueList($value) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_WARN, $value], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@error', 6) &&
                $this->valueList($value) &&
                $this->end()
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_ERROR, $value], $s);

                return true;
            }

            $this->seek($s);

            if ($this->literal('@content', 8) &&
                ($this->end() ||
                    $this->matchChar('(') &&
                    $this->argValues($argContent) &&
                    $this->matchChar(')') &&
                    $this->end())
            ) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $this->append([Type::T_MIXIN_CONTENT, isset($argContent) ? $argContent : null], $s);

                return true;
            }

            $this->seek($s);

            $last = $this->last();

            if (isset($last) && $last[0] === Type::T_IF) {
                list(, $if) = $last;

                if ($this->literal('@else', 5)) {
                    if ($this->matchChar('{', false)) {
                        $else = $this->pushSpecialBlock(Type::T_ELSE, $s);
                    } elseif ($this->literal('if', 2) && $this->valueList($cond) && $this->matchChar('{', false)) {
                        $else = $this->pushSpecialBlock(Type::T_ELSEIF, $s);
                        $else->cond = $cond;
                    }

                    if (isset($else)) {
                        $else->dontAppend = true;
                        $if->cases[] = $else;

                        return true;
                    }
                }

                $this->seek($s);
            }

            // only retain the first @charset directive encountered
            if ($this->literal('@charset', 8) &&
                $this->valueList($charset) &&
                $this->end()
            ) {
                if (! isset($this->charset)) {
                    $statement = [Type::T_CHARSET, $charset];

                    list($line, $column) = $this->getSourcePosition($s);

                    $statement[static::SOURCE_LINE]   = $line;
                    $statement[static::SOURCE_COLUMN] = $column;
                    $statement[static::SOURCE_INDEX]  = $this->sourceIndex;

                    $this->charset = $statement;
                }

                return true;
            }

            $this->seek($s);

            if ($this->literal('@supports', 9) &&
                ($t1=$this->supportsQuery($supportQuery)) &&
                ($t2=$this->matchChar('{', false))
            ) {
                $directive = $this->pushSpecialBlock(Type::T_DIRECTIVE, $s);
                $directive->name  = 'supports';
                $directive->value = $supportQuery;

                return true;
            }

            $this->seek($s);

            // doesn't match built in directive, do generic one
            if ($this->matchChar('@', false) &&
                $this->keyword($dirName) &&
                $this->directiveValue($dirValue, '{')
            ) {
                if ($dirName === 'media') {
                    $directive = $this->pushSpecialBlock(Type::T_MEDIA, $s);
                } else {
                    $directive = $this->pushSpecialBlock(Type::T_DIRECTIVE, $s);
                    $directive->name = $dirName;
                }

                if (isset($dirValue)) {
                    $directive->value = $dirValue;
                }

                return true;
            }

            $this->seek($s);

            // maybe it's a generic blockless directive
            if ($this->matchChar('@', false) &&
                $this->keyword($dirName) &&
                $this->directiveValue($dirValue) &&
                $this->end()
            ) {
                $this->append([Type::T_DIRECTIVE, [$dirName, $dirValue]], $s);

                return true;
            }

            $this->seek($s);

            return false;
        }

        // custom properties : right part is static
        if (($this->customProperty($name) || ($this->cssOnly && $this->propertyName($name))) &&
            $this->matchChar(':', false)
        ) {
            $start = $this->count;

            // but can be complex and finish with ; or }
            foreach ([';','}'] as $ending) {
                if ($this->openString($ending, $stringValue, '(', ')', false) &&
                    $this->end()
                ) {
                    $end = $this->count;
                    $value = $stringValue;

                    // check if we have only a partial value due to nested [] or { } to take in account
                    $nestingPairs = [['[', ']'], ['{', '}']];

                    foreach ($nestingPairs as $nestingPair) {
                        $p = strpos($this->buffer, $nestingPair[0], $start);

                        if ($p && $p < $end) {
                            $this->seek($start);

                            if ($this->openString($ending, $stringValue, $nestingPair[0], $nestingPair[1], false) &&
                                $this->end() &&
                                $this->count > $end
                            ) {
                                $end = $this->count;
                                $value = $stringValue;
                            }
                        }
                    }

                    $this->seek($end);
                    $this->append([Type::T_CUSTOM_PROPERTY, $name, $value], $s);

                    return true;
                }
            }

            // TODO: output an error here if nothing found according to sass spec
        }

        $this->seek($s);

        // property shortcut
        // captures most properties before having to parse a selector
        if ($this->keyword($name, false) &&
            $this->literal(': ', 2) &&
            $this->valueList($value) &&
            $this->end()
        ) {
            $name = [Type::T_STRING, '', [$name]];
            $this->append([Type::T_ASSIGN, $name, $value], $s);

            return true;
        }

        $this->seek($s);

        // variable assigns
        if ($this->variable($name) &&
            $this->matchChar(':') &&
            $this->valueList($value) &&
            $this->end()
        ) {
            if ($this->cssOnly) {
                $this->throwParseError("SCSS syntax not allowed in CSS file");
            }

            // check for '!flag'
            $assignmentFlags = $this->stripAssignmentFlags($value);
            $this->append([Type::T_ASSIGN, $name, $value, $assignmentFlags], $s);

            return true;
        }

        $this->seek($s);

        // misc
        if ($this->literal('-->', 3)) {
            return true;
        }

        // opening css block
        if ($this->selectors($selectors) && $this->matchChar('{', false)) {
            if ($this->cssOnly) {
                if (! empty($this->env->parent)) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }
            }

            $this->pushBlock($selectors, $s);

            if ($this->eatWhiteDefault) {
                $this->whitespace();
                $this->append(null); // collect comments at the beginning if needed
            }

            return true;
        }

        $this->seek($s);

        // property assign, or nested assign
        if ($this->propertyName($name) && $this->matchChar(':')) {
            $foundSomething = false;

            if ($this->valueList($value)) {
                if (empty($this->env->parent)) {
                    $this->throwParseError('expected "{"');
                }

                $this->append([Type::T_ASSIGN, $name, $value], $s);
                $foundSomething = true;
            }

            if ($this->matchChar('{', false)) {
                if ($this->cssOnly) {
                    $this->throwParseError("SCSS syntax not allowed in CSS file");
                }

                $propBlock = $this->pushSpecialBlock(Type::T_NESTED_PROPERTY, $s);
                $propBlock->prefix = $name;
                $propBlock->hasValue = $foundSomething;

                $foundSomething = true;
            } elseif ($foundSomething) {
                $foundSomething = $this->end();
            }

            if ($foundSomething) {
                return true;
            }
        }

        $this->seek($s);

        // closing a block
        if ($this->matchChar('}', false)) {
            $block = $this->popBlock();

            if (! isset($block->type) || $block->type !== Type::T_IF) {
                if ($this->env->parent) {
                    $this->append(null); // collect comments before next statement if needed
                }
            }

            if (isset($block->type) && $block->type === Type::T_INCLUDE) {
                $include = $block->child;
                unset($block->child);
                $include[3] = $block;
                $this->append($include, $s);
            } elseif (empty($block->dontAppend)) {
                $type = isset($block->type) ? $block->type : Type::T_BLOCK;
                $this->append([$type, $block], $s);
            }

            // collect comments just after the block closing if needed
            if ($this->eatWhiteDefault) {
                $this->whitespace();

                if ($this->env->comments) {
                    $this->append(null);
                }
            }

            return true;
        }

        // extra stuff
        if ($this->matchChar(';') ||
            $this->literal('<!--', 4)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Push block onto parse tree
     *
     * @param array   $selectors
     * @param integer $pos
     *
     * @return \ScssPhp\ScssPhp\Block
     */
    protected function pushBlock($selectors, $pos = 0)
    {
        list($line, $column) = $this->getSourcePosition($pos);

        $b = new Block;
        $b->sourceName   = $this->sourceName;
        $b->sourceLine   = $line;
        $b->sourceColumn = $column;
        $b->sourceIndex  = $this->sourceIndex;
        $b->selectors    = $selectors;
        $b->comments     = [];
        $b->parent       = $this->env;

        if (! $this->env) {
            $b->children = [];
        } elseif (empty($this->env->children)) {
            $this->env->children = $this->env->comments;
            $b->children = [];
            $this->env->comments = [];
        } else {
            $b->children = $this->env->comments;
            $this->env->comments = [];
        }

        $this->env = $b;

        // collect comments at the beginning of a block if needed
        if ($this->eatWhiteDefault) {
            $this->whitespace();

            if ($this->env->comments) {
                $this->append(null);
            }
        }

        return $b;
    }

    /**
     * Push special (named) block onto parse tree
     *
     * @param string  $type
     * @param integer $pos
     *
     * @return \ScssPhp\ScssPhp\Block
     */
    protected function pushSpecialBlock($type, $pos)
    {
        $block = $this->pushBlock(null, $pos);
        $block->type = $type;

        return $block;
    }

    /**
     * Pop scope and return last block
     *
     * @return \ScssPhp\ScssPhp\Block
     *
     * @throws \Exception
     */
    protected function popBlock()
    {

        // collect comments ending just before of a block closing
        if ($this->env->comments) {
            $this->append(null);
        }

        // pop the block
        $block = $this->env;

        if (empty($block->parent)) {
            $this->throwParseError('unexpected }');
        }

        if ($block->type == Type::T_AT_ROOT) {
            // keeps the parent in case of self selector &
            $block->selfParent = $block->parent;
        }

        $this->env = $block->parent;

        unset($block->parent);

        return $block;
    }

    /**
     * Peek input stream
     *
     * @param string  $regex
     * @param array   $out
     * @param integer $from
     *
     * @return integer
     */
    protected function peek($regex, &$out, $from = null)
    {
        if (! isset($from)) {
            $from = $this->count;
        }

        $r = '/' . $regex . '/' . $this->patternModifiers;
        $result = preg_match($r, $this->buffer, $out, null, $from);

        return $result;
    }

    /**
     * Seek to position in input stream (or return current position in input stream)
     *
     * @param integer $where
     */
    protected function seek($where)
    {
        $this->count = $where;
    }

    /**
     * Match string looking for either ending delim, escape, or string interpolation
     *
     * {@internal This is a workaround for preg_match's 250K string match limit. }}
     *
     * @param array  $m     Matches (passed by reference)
     * @param string $delim Delimeter
     *
     * @return boolean True if match; false otherwise
     */
    protected function matchString(&$m, $delim)
    {
        $token = null;

        $end = \strlen($this->buffer);

        // look for either ending delim, escape, or string interpolation
        foreach (['#{', '\\', $delim] as $lookahead) {
            $pos = strpos($this->buffer, $lookahead, $this->count);

            if ($pos !== false && $pos < $end) {
                $end = $pos;
                $token = $lookahead;
            }
        }

        if (! isset($token)) {
            return false;
        }

        $match = substr($this->buffer, $this->count, $end - $this->count);
        $m = [
            $match . $token,
            $match,
            $token
        ];
        $this->count = $end + \strlen($token);

        return true;
    }

    /**
     * Try to match something on head of buffer
     *
     * @param string  $regex
     * @param array   $out
     * @param boolean $eatWhitespace
     *
     * @return boolean
     */
    protected function match($regex, &$out, $eatWhitespace = null)
    {
        $r = '/' . $regex . '/' . $this->patternModifiers;

        if (! preg_match($r, $this->buffer, $out, null, $this->count)) {
            return false;
        }

        $this->count += \strlen($out[0]);

        if (! isset($eatWhitespace)) {
            $eatWhitespace = $this->eatWhiteDefault;
        }

        if ($eatWhitespace) {
            $this->whitespace();
        }

        return true;
    }

    /**
     * Match a single string
     *
     * @param string  $char
     * @param boolean $eatWhitespace
     *
     * @return boolean
     */
    protected function matchChar($char, $eatWhitespace = null)
    {
        if (! isset($this->buffer[$this->count]) || $this->buffer[$this->count] !== $char) {
            return false;
        }

        $this->count++;

        if (! isset($eatWhitespace)) {
            $eatWhitespace = $this->eatWhiteDefault;
        }

        if ($eatWhitespace) {
            $this->whitespace();
        }

        return true;
    }

    /**
     * Match literal string
     *
     * @param string  $what
     * @param integer $len
     * @param boolean $eatWhitespace
     *
     * @return boolean
     */
    protected function literal($what, $len, $eatWhitespace = null)
    {
        if (strcasecmp(substr($this->buffer, $this->count, $len), $what) !== 0) {
            return false;
        }

        $this->count += $len;

        if (! isset($eatWhitespace)) {
            $eatWhitespace = $this->eatWhiteDefault;
        }

        if ($eatWhitespace) {
            $this->whitespace();
        }

        return true;
    }

    /**
     * Match some whitespace
     *
     * @return boolean
     */
    protected function whitespace()
    {
        $gotWhite = false;

        while (preg_match(static::$whitePattern, $this->buffer, $m, null, $this->count)) {
            if (isset($m[1]) && empty($this->commentsSeen[$this->count])) {
                // comment that are kept in the output CSS
                $comment = [];
                $startCommentCount = $this->count;
                $endCommentCount = $this->count + \strlen($m[1]);

                // find interpolations in comment
                $p = strpos($this->buffer, '#{', $this->count);

                while ($p !== false && $p < $endCommentCount) {
                    $c           = substr($this->buffer, $this->count, $p - $this->count);
                    $comment[]   = $c;
                    $this->count = $p;
                    $out         = null;

                    if ($this->interpolation($out)) {
                        // keep right spaces in the following string part
                        if ($out[3]) {
                            while ($this->buffer[$this->count-1] !== '}') {
                                $this->count--;
                            }

                            $out[3] = '';
                        }

                        $comment[] = [Type::T_COMMENT, substr($this->buffer, $p, $this->count - $p), $out];
                    } else {
                        $comment[] = substr($this->buffer, $this->count, 2);

                        $this->count += 2;
                    }

                    $p = strpos($this->buffer, '#{', $this->count);
                }

                // remaining part
                $c = substr($this->buffer, $this->count, $endCommentCount - $this->count);

                if (! $comment) {
                    // single part static comment
                    $this->appendComment([Type::T_COMMENT, $c]);
                } else {
                    $comment[] = $c;
                    $staticComment = substr($this->buffer, $startCommentCount, $endCommentCount - $startCommentCount);
                    $this->appendComment([Type::T_COMMENT, $staticComment, [Type::T_STRING, '', $comment]]);
                }

                $this->commentsSeen[$startCommentCount] = true;
                $this->count = $endCommentCount;
            } else {
                // comment that are ignored and not kept in the output css
                $this->count += \strlen($m[0]);
            }

            $gotWhite = true;
        }

        return $gotWhite;
    }

    /**
     * Append comment to current block
     *
     * @param array $comment
     */
    protected function appendComment($comment)
    {
        if (! $this->discardComments) {
            if ($comment[0] === Type::T_COMMENT) {
                if (\is_string($comment[1])) {
                    $comment[1] = substr(preg_replace(['/^\s+/m', '/^(.)/m'], ['', ' \1'], $comment[1]), 1);
                }

                if (isset($comment[2]) and \is_array($comment[2]) and $comment[2][0] === Type::T_STRING) {
                    foreach ($comment[2][2] as $k => $v) {
                        if (\is_string($v)) {
                            $p = strpos($v, "\n");

                            if ($p !== false) {
                                $comment[2][2][$k] = substr($v, 0, $p + 1)
                                    . preg_replace(['/^\s+/m', '/^(.)/m'], ['', ' \1'], substr($v, $p+1));
                            }
                        }
                    }
                }
            }

            $this->env->comments[] = $comment;
        }
    }

    /**
     * Append statement to current block
     *
     * @param array   $statement
     * @param integer $pos
     */
    protected function append($statement, $pos = null)
    {
        if (! \is_null($statement)) {
            if (! \is_null($pos)) {
                list($line, $column) = $this->getSourcePosition($pos);

                $statement[static::SOURCE_LINE]   = $line;
                $statement[static::SOURCE_COLUMN] = $column;
                $statement[static::SOURCE_INDEX]  = $this->sourceIndex;
            }

            $this->env->children[] = $statement;
        }

        $comments = $this->env->comments;

        if ($comments) {
            $this->env->children = array_merge($this->env->children, $comments);
            $this->env->comments = [];
        }
    }

    /**
     * Returns last child was appended
     *
     * @return array|null
     */
    protected function last()
    {
        $i = \count($this->env->children) - 1;

        if (isset($this->env->children[$i])) {
            return $this->env->children[$i];
        }
    }

    /**
     * Parse media query list
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function mediaQueryList(&$out)
    {
        return $this->genericList($out, 'mediaQuery', ',', false);
    }

    /**
     * Parse media query
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function mediaQuery(&$out)
    {
        $expressions = null;
        $parts = [];

        if (($this->literal('only', 4) && ($only = true) || $this->literal('not', 3) && ($not = true) || true) &&
            $this->mixedKeyword($mediaType)
        ) {
            $prop = [Type::T_MEDIA_TYPE];

            if (isset($only)) {
                $prop[] = [Type::T_KEYWORD, 'only'];
            }

            if (isset($not)) {
                $prop[] = [Type::T_KEYWORD, 'not'];
            }

            $media = [Type::T_LIST, '', []];

            foreach ((array) $mediaType as $type) {
                if (\is_array($type)) {
                    $media[2][] = $type;
                } else {
                    $media[2][] = [Type::T_KEYWORD, $type];
                }
            }

            $prop[]  = $media;
            $parts[] = $prop;
        }

        if (empty($parts) || $this->literal('and', 3)) {
            $this->genericList($expressions, 'mediaExpression', 'and', false);

            if (\is_array($expressions)) {
                $parts = array_merge($parts, $expressions[2]);
            }
        }

        $out = $parts;

        return true;
    }

    /**
     * Parse supports query
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function supportsQuery(&$out)
    {
        $expressions = null;
        $parts = [];

        $s = $this->count;

        $not = false;

        if (($this->literal('not', 3) && ($not = true) || true) &&
            $this->matchChar('(') &&
            ($this->expression($property)) &&
            $this->literal(': ', 2) &&
            $this->valueList($value) &&
            $this->matchChar(')')
        ) {
            $support = [Type::T_STRING, '', [[Type::T_KEYWORD, ($not ? 'not ' : '') . '(']]];
            $support[2][] = $property;
            $support[2][] = [Type::T_KEYWORD, ': '];
            $support[2][] = $value;
            $support[2][] = [Type::T_KEYWORD, ')'];

            $parts[] = $support;
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->matchChar('(') &&
            $this->supportsQuery($subQuery) &&
            $this->matchChar(')')
        ) {
            $parts[] = [Type::T_STRING, '', [[Type::T_KEYWORD, '('], $subQuery, [Type::T_KEYWORD, ')']]];
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->literal('not', 3) &&
            $this->supportsQuery($subQuery)
        ) {
            $parts[] = [Type::T_STRING, '', [[Type::T_KEYWORD, 'not '], $subQuery]];
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->literal('selector(', 9) &&
            $this->selector($selector) &&
            $this->matchChar(')')
        ) {
            $support = [Type::T_STRING, '', [[Type::T_KEYWORD, 'selector(']]];

            $selectorList = [Type::T_LIST, '', []];

            foreach ($selector as $sc) {
                $compound = [Type::T_STRING, '', []];

                foreach ($sc as $scp) {
                    if (\is_array($scp)) {
                        $compound[2][] = $scp;
                    } else {
                        $compound[2][] = [Type::T_KEYWORD, $scp];
                    }
                }

                $selectorList[2][] = $compound;
            }

            $support[2][] = $selectorList;
            $support[2][] = [Type::T_KEYWORD, ')'];
            $parts[] = $support;
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->variable($var) or $this->interpolation($var)) {
            $parts[] = $var;
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->literal('and', 3) &&
            $this->genericList($expressions, 'supportsQuery', ' and', false)) {
            array_unshift($expressions[2], [Type::T_STRING, '', $parts]);

            $parts = [$expressions];
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if ($this->literal('or', 2) &&
            $this->genericList($expressions, 'supportsQuery', ' or', false)) {
            array_unshift($expressions[2], [Type::T_STRING, '', $parts]);

            $parts = [$expressions];
            $s = $this->count;
        } else {
            $this->seek($s);
        }

        if (\count($parts)) {
            if ($this->eatWhiteDefault) {
                $this->whitespace();
            }

            $out = [Type::T_STRING, '', $parts];

            return true;
        }

        return false;
    }


    /**
     * Parse media expression
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function mediaExpression(&$out)
    {
        $s = $this->count;
        $value = null;

        if ($this->matchChar('(') &&
            $this->expression($feature) &&
            ($this->matchChar(':') && $this->expression($value) || true) &&
            $this->matchChar(')')
        ) {
            $out = [Type::T_MEDIA_EXPRESSION, $feature];

            if ($value) {
                $out[] = $value;
            }

            return true;
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse argument values
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function argValues(&$out)
    {
        if ($this->genericList($list, 'argValue', ',', false)) {
            $out = $list[2];

            return true;
        }

        return false;
    }

    /**
     * Parse argument value
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function argValue(&$out)
    {
        $s = $this->count;

        $keyword = null;

        if (! $this->variable($keyword) || ! $this->matchChar(':')) {
            $this->seek($s);

            $keyword = null;
        }

        if ($this->genericList($value, 'expression')) {
            $out = [$keyword, $value, false];
            $s = $this->count;

            if ($this->literal('...', 3)) {
                $out[2] = true;
            } else {
                $this->seek($s);
            }

            return true;
        }

        return false;
    }

    /**
     * Parse directive value list that considers $vars as keyword
     *
     * @param array          $out
     * @param boolean|string $endChar
     *
     * @return boolean
     */
    protected function directiveValue(&$out, $endChar = false)
    {
        $s = $this->count;

        if ($this->variable($out)) {
            if ($endChar && $this->matchChar($endChar, false)) {
                return true;
            }

            if (! $endChar && $this->end()) {
                return true;
            }
        }

        $this->seek($s);

        if ($endChar and $this->openString($endChar, $out)) {
            if ($this->matchChar($endChar, false)) {
                return true;
            }
        }

        $this->seek($s);

        $allowVars = $this->allowVars;
        $this->allowVars = false;

        $res = $this->genericList($out, 'spaceList', ',');
        $this->allowVars = $allowVars;

        if ($res) {
            if ($endChar && $this->matchChar($endChar, false)) {
                return true;
            }

            if (! $endChar && $this->end()) {
                return true;
            }
        }

        $this->seek($s);

        if ($endChar && $this->matchChar($endChar, false)) {
            return true;
        }

        return false;
    }

    /**
     * Parse comma separated value list
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function valueList(&$out)
    {
        $discardComments = $this->discardComments;
        $this->discardComments = true;
        $res = $this->genericList($out, 'spaceList', ',');
        $this->discardComments = $discardComments;

        return $res;
    }

    /**
     * Parse space separated value list
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function spaceList(&$out)
    {
        return $this->genericList($out, 'expression');
    }

    /**
     * Parse generic list
     *
     * @param array    $out
     * @param callable $parseItem
     * @param string   $delim
     * @param boolean  $flatten
     *
     * @return boolean
     */
    protected function genericList(&$out, $parseItem, $delim = '', $flatten = true)
    {
        $s     = $this->count;
        $items = [];
        $value = null;

        while ($this->$parseItem($value)) {
            $trailing_delim = false;
            $items[] = $value;

            if ($delim) {
                if (! $this->literal($delim, \strlen($delim))) {
                    break;
                }

                $trailing_delim = true;
            }
        }

        if (! $items) {
            $this->seek($s);

            return false;
        }

        if ($trailing_delim) {
            $items[] = [Type::T_NULL];
        }

        if ($flatten && \count($items) === 1) {
            $out = $items[0];
        } else {
            $out = [Type::T_LIST, $delim, $items];
        }

        return true;
    }

    /**
     * Parse expression
     *
     * @param array   $out
     * @param boolean $listOnly
     * @param boolean $lookForExp
     *
     * @return boolean
     */
    protected function expression(&$out, $listOnly = false, $lookForExp = true)
    {
        $s = $this->count;
        $discard = $this->discardComments;
        $this->discardComments = true;
        $allowedTypes = ($listOnly ? [Type::T_LIST] : [Type::T_LIST, Type::T_MAP]);

        if ($this->matchChar('(')) {
            if ($this->enclosedExpression($lhs, $s, ")", $allowedTypes)) {
                if ($lookForExp) {
                    $out = $this->expHelper($lhs, 0);
                } else {
                    $out = $lhs;
                }

                $this->discardComments = $discard;

                return true;
            }

            $this->seek($s);
        }

        if (\in_array(Type::T_LIST, $allowedTypes) && $this->matchChar('[')) {
            if ($this->enclosedExpression($lhs, $s, "]", [Type::T_LIST])) {
                if ($lookForExp) {
                    $out = $this->expHelper($lhs, 0);
                } else {
                    $out = $lhs;
                }

                $this->discardComments = $discard;

                return true;
            }

            $this->seek($s);
        }

        if (! $listOnly && $this->value($lhs)) {
            if ($lookForExp) {
                $out = $this->expHelper($lhs, 0);
            } else {
                $out = $lhs;
            }

            $this->discardComments = $discard;

            return true;
        }

        $this->discardComments = $discard;

        return false;
    }

    /**
     * Parse expression specifically checking for lists in parenthesis or brackets
     *
     * @param array   $out
     * @param integer $s
     * @param string  $closingParen
     * @param array   $allowedTypes
     *
     * @return boolean
     */
    protected function enclosedExpression(&$out, $s, $closingParen = ")", $allowedTypes = [Type::T_LIST, Type::T_MAP])
    {
        if ($this->matchChar($closingParen) && \in_array(Type::T_LIST, $allowedTypes)) {
            $out = [Type::T_LIST, '', []];

            switch ($closingParen) {
                case ")":
                    $out['enclosing'] = 'parent'; // parenthesis list
                    break;

                case "]":
                    $out['enclosing'] = 'bracket'; // bracketed list
                    break;
            }

            return true;
        }

        if ($this->valueList($out) && $this->matchChar($closingParen) &&
            \in_array($out[0], [Type::T_LIST, Type::T_KEYWORD]) &&
            \in_array(Type::T_LIST, $allowedTypes)
        ) {
            if ($out[0] !== Type::T_LIST || ! empty($out['enclosing'])) {
                $out = [Type::T_LIST, '', [$out]];
            }

            switch ($closingParen) {
                case ")":
                    $out['enclosing'] = 'parent'; // parenthesis list
                    break;

                case "]":
                    $out['enclosing'] = 'bracket'; // bracketed list
                    break;
            }

            return true;
        }

        $this->seek($s);

        if (\in_array(Type::T_MAP, $allowedTypes) && $this->map($out)) {
            return true;
        }

        return false;
    }

    /**
     * Parse left-hand side of subexpression
     *
     * @param array   $lhs
     * @param integer $minP
     *
     * @return array
     */
    protected function expHelper($lhs, $minP)
    {
        $operators = static::$operatorPattern;

        $ss = $this->count;
        $whiteBefore = isset($this->buffer[$this->count - 1]) &&
            ctype_space($this->buffer[$this->count - 1]);

        while ($this->match($operators, $m, false) && static::$precedence[$m[1]] >= $minP) {
            $whiteAfter = isset($this->buffer[$this->count]) &&
                ctype_space($this->buffer[$this->count]);
            $varAfter = isset($this->buffer[$this->count]) &&
                $this->buffer[$this->count] === '$';

            $this->whitespace();

            $op = $m[1];

            // don't turn negative numbers into expressions
            if ($op === '-' && $whiteBefore && ! $whiteAfter && ! $varAfter) {
                break;
            }

            if (! $this->value($rhs) && ! $this->expression($rhs, true, false)) {
                break;
            }

            // peek and see if rhs belongs to next operator
            if ($this->peek($operators, $next) && static::$precedence[$next[1]] > static::$precedence[$op]) {
                $rhs = $this->expHelper($rhs, static::$precedence[$next[1]]);
            }

            $lhs = [Type::T_EXPRESSION, $op, $lhs, $rhs, $this->inParens, $whiteBefore, $whiteAfter];
            $ss = $this->count;
            $whiteBefore = isset($this->buffer[$this->count - 1]) &&
                ctype_space($this->buffer[$this->count - 1]);
        }

        $this->seek($ss);

        return $lhs;
    }

    /**
     * Parse value
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function value(&$out)
    {
        if (! isset($this->buffer[$this->count])) {
            return false;
        }

        $s = $this->count;
        $char = $this->buffer[$this->count];

        if ($this->literal('url(', 4) && $this->match('data:([a-z]+)\/([a-z0-9.+-]+);base64,', $m, false)) {
            $len = strspn(
                $this->buffer,
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyxz0123456789+/=',
                $this->count
            );

            $this->count += $len;

            if ($this->matchChar(')')) {
                $content = substr($this->buffer, $s, $this->count - $s);
                $out = [Type::T_KEYWORD, $content];

                return true;
            }
        }

        $this->seek($s);

        if ($this->literal('url(', 4, false) && $this->match('\s*(\/\/[^\s\)]+)\s*', $m)) {
            $content = 'url(' . $m[1];

            if ($this->matchChar(')')) {
                $content .= ')';
                $out = [Type::T_KEYWORD, $content];

                return true;
            }
        }

        $this->seek($s);

        // not
        if ($char === 'n' && $this->literal('not', 3, false)) {
            if ($this->whitespace() && $this->value($inner)) {
                $out = [Type::T_UNARY, 'not', $inner, $this->inParens];

                return true;
            }

            $this->seek($s);

            if ($this->parenValue($inner)) {
                $out = [Type::T_UNARY, 'not', $inner, $this->inParens];

                return true;
            }

            $this->seek($s);
        }

        // addition
        if ($char === '+') {
            $this->count++;

            $follow_white = $this->whitespace();

            if ($this->value($inner)) {
                $out = [Type::T_UNARY, '+', $inner, $this->inParens];

                return true;
            }

            if ($follow_white) {
                $out = [Type::T_KEYWORD, $char];
                return  true;
            }

            $this->seek($s);

            return false;
        }

        // negation
        if ($char === '-') {
            if ($this->customProperty($out)) {
                return true;
            }

            $this->count++;

            $follow_white = $this->whitespace();

            if ($this->variable($inner) || $this->unit($inner) || $this->parenValue($inner)) {
                $out = [Type::T_UNARY, '-', $inner, $this->inParens];

                return true;
            }

            if ($this->keyword($inner) && ! $this->func($inner, $out)) {
                $out = [Type::T_UNARY, '-', $inner, $this->inParens];

                return true;
            }

            if ($follow_white) {
                $out = [Type::T_KEYWORD, $char];

                return  true;
            }

            $this->seek($s);
        }

        // paren
        if ($char === '(' && $this->parenValue($out)) {
            return true;
        }

        if ($char === '#') {
            if ($this->interpolation($out) || $this->color($out)) {
                return true;
            }

            $this->count++;

            if ($this->keyword($keyword)) {
                $out = [Type::T_KEYWORD, "#" . $keyword];

                return true;
            }

            $this->count--;
        }

        if ($this->matchChar('&', true)) {
            $out = [Type::T_SELF];

            return true;
        }

        if ($char === '$' && $this->variable($out)) {
            return true;
        }

        if ($char === 'p' && $this->progid($out)) {
            return true;
        }

        if (($char === '"' || $char === "'") && $this->string($out)) {
            return true;
        }

        if ($this->unit($out)) {
            return true;
        }

        // unicode range with wildcards
        if ($this->literal('U+', 2) && $this->match('([0-9A-F]+\?*)(-([0-9A-F]+))?', $m, false)) {
            $out = [Type::T_KEYWORD, 'U+' . $m[0]];

            return true;
        }

        if ($this->keyword($keyword, false)) {
            if ($this->func($keyword, $out)) {
                return true;
            }

            $this->whitespace();

            if ($keyword === 'null') {
                $out = [Type::T_NULL];
            } else {
                $out = [Type::T_KEYWORD, $keyword];
            }

            return true;
        }

        return false;
    }

    /**
     * Parse parenthesized value
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function parenValue(&$out)
    {
        $s = $this->count;

        $inParens = $this->inParens;

        if ($this->matchChar('(')) {
            if ($this->matchChar(')')) {
                $out = [Type::T_LIST, '', []];

                return true;
            }

            $this->inParens = true;

            if ($this->expression($exp) && $this->matchChar(')')) {
                $out = $exp;
                $this->inParens = $inParens;

                return true;
            }
        }

        $this->inParens = $inParens;
        $this->seek($s);

        return false;
    }

    /**
     * Parse "progid:"
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function progid(&$out)
    {
        $s = $this->count;

        if ($this->literal('progid:', 7, false) &&
            $this->openString('(', $fn) &&
            $this->matchChar('(')
        ) {
            $this->openString(')', $args, '(');

            if ($this->matchChar(')')) {
                $out = [Type::T_STRING, '', [
                    'progid:', $fn, '(', $args, ')'
                ]];

                return true;
            }
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse function call
     *
     * @param string $name
     * @param array  $func
     *
     * @return boolean
     */
    protected function func($name, &$func)
    {
        $s = $this->count;

        if ($this->matchChar('(')) {
            if ($name === 'alpha' && $this->argumentList($args)) {
                $func = [Type::T_FUNCTION, $name, [Type::T_STRING, '', $args]];

                return true;
            }

            if ($name !== 'expression' && ! preg_match('/^(-[a-z]+-)?calc$/', $name)) {
                $ss = $this->count;

                if ($this->argValues($args) && $this->matchChar(')')) {
                    $func = [Type::T_FUNCTION_CALL, $name, $args];

                    return true;
                }

                $this->seek($ss);
            }

            if (($this->openString(')', $str, '(') || true) &&
                $this->matchChar(')')
            ) {
                $args = [];

                if (! empty($str)) {
                    $args[] = [null, [Type::T_STRING, '', [$str]]];
                }

                $func = [Type::T_FUNCTION_CALL, $name, $args];

                return true;
            }
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse function call argument list
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function argumentList(&$out)
    {
        $s = $this->count;
        $this->matchChar('(');

        $args = [];

        while ($this->keyword($var)) {
            if ($this->matchChar('=') && $this->expression($exp)) {
                $args[] = [Type::T_STRING, '', [$var . '=']];
                $arg = $exp;
            } else {
                break;
            }

            $args[] = $arg;

            if (! $this->matchChar(',')) {
                break;
            }

            $args[] = [Type::T_STRING, '', [', ']];
        }

        if (! $this->matchChar(')') || ! $args) {
            $this->seek($s);

            return false;
        }

        $out = $args;

        return true;
    }

    /**
     * Parse mixin/function definition  argument list
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function argumentDef(&$out)
    {
        $s = $this->count;
        $this->matchChar('(');

        $args = [];

        while ($this->variable($var)) {
            $arg = [$var[1], null, false];

            $ss = $this->count;

            if ($this->matchChar(':') && $this->genericList($defaultVal, 'expression')) {
                $arg[1] = $defaultVal;
            } else {
                $this->seek($ss);
            }

            $ss = $this->count;

            if ($this->literal('...', 3)) {
                $sss = $this->count;

                if (! $this->matchChar(')')) {
                    $this->throwParseError('... has to be after the final argument');
                }

                $arg[2] = true;

                $this->seek($sss);
            } else {
                $this->seek($ss);
            }

            $args[] = $arg;

            if (! $this->matchChar(',')) {
                break;
            }
        }

        if (! $this->matchChar(')')) {
            $this->seek($s);

            return false;
        }

        $out = $args;

        return true;
    }

    /**
     * Parse map
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function map(&$out)
    {
        $s = $this->count;

        if (! $this->matchChar('(')) {
            return false;
        }

        $keys = [];
        $values = [];

        while ($this->genericList($key, 'expression') && $this->matchChar(':') &&
            $this->genericList($value, 'expression')
        ) {
            $keys[] = $key;
            $values[] = $value;

            if (! $this->matchChar(',')) {
                break;
            }
        }

        if (! $keys || ! $this->matchChar(')')) {
            $this->seek($s);

            return false;
        }

        $out = [Type::T_MAP, $keys, $values];

        return true;
    }

    /**
     * Parse color
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function color(&$out)
    {
        $s = $this->count;

        if ($this->match('(#([0-9a-f]+))', $m)) {
            if (\in_array(\strlen($m[2]), [3,4,6,8])) {
                $out = [Type::T_KEYWORD, $m[0]];

                return true;
            }

            $this->seek($s);

            return false;
        }

        return false;
    }

    /**
     * Parse number with unit
     *
     * @param array $unit
     *
     * @return boolean
     */
    protected function unit(&$unit)
    {
        $s = $this->count;

        if ($this->match('([0-9]*(\.)?[0-9]+)([%a-zA-Z]+)?', $m, false)) {
            if (\strlen($this->buffer) === $this->count || ! ctype_digit($this->buffer[$this->count])) {
                $this->whitespace();

                $unit = new Node\Number($m[1], empty($m[3]) ? '' : $m[3]);

                return true;
            }

            $this->seek($s);
        }

        return false;
    }

    /**
     * Parse string
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function string(&$out)
    {
        $s = $this->count;

        if ($this->matchChar('"', false)) {
            $delim = '"';
        } elseif ($this->matchChar("'", false)) {
            $delim = "'";
        } else {
            return false;
        }

        $content = [];
        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;
        $hasInterpolation = false;

        while ($this->matchString($m, $delim)) {
            if ($m[1] !== '') {
                $content[] = $m[1];
            }

            if ($m[2] === '#{') {
                $this->count -= \strlen($m[2]);

                if ($this->interpolation($inter, false)) {
                    $content[] = $inter;
                    $hasInterpolation = true;
                } else {
                    $this->count += \strlen($m[2]);
                    $content[] = '#{'; // ignore it
                }
            } elseif ($m[2] === '\\') {
                if ($this->matchChar('"', false)) {
                    $content[] = $m[2] . '"';
                } elseif ($this->matchChar("'", false)) {
                    $content[] = $m[2] . "'";
                } elseif ($this->literal("\\", 1, false)) {
                    $content[] = $m[2] . "\\";
                } elseif ($this->literal("\r\n", 2, false) ||
                    $this->matchChar("\r", false) ||
                    $this->matchChar("\n", false) ||
                    $this->matchChar("\f", false)
                ) {
                    // this is a continuation escaping, to be ignored
                } else {
                    $content[] = $m[2];
                }
            } else {
                $this->count -= \strlen($delim);
                break; // delim
            }
        }

        $this->eatWhiteDefault = $oldWhite;

        if ($this->literal($delim, \strlen($delim))) {
            if ($hasInterpolation) {
                $delim = '"';

                foreach ($content as &$string) {
                    if ($string === "\\\\") {
                        $string = "\\";
                    } elseif ($string === "\\'") {
                        $string = "'";
                    } elseif ($string === '\\"') {
                        $string = '"';
                    }
                }
            }

            $out = [Type::T_STRING, $delim, $content];

            return true;
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse keyword or interpolation
     *
     * @param array   $out
     * @param boolean $restricted
     *
     * @return boolean
     */
    protected function mixedKeyword(&$out, $restricted = false)
    {
        $parts = [];

        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;

        for (;;) {
            if ($restricted ? $this->restrictedKeyword($key) : $this->keyword($key)) {
                $parts[] = $key;
                continue;
            }

            if ($this->interpolation($inter)) {
                $parts[] = $inter;
                continue;
            }

            break;
        }

        $this->eatWhiteDefault = $oldWhite;

        if (! $parts) {
            return false;
        }

        if ($this->eatWhiteDefault) {
            $this->whitespace();
        }

        $out = $parts;

        return true;
    }

    /**
     * Parse an unbounded string stopped by $end
     *
     * @param string  $end
     * @param array   $out
     * @param string  $nestingOpen
     * @param string  $nestingClose
     * @param boolean $trimEnd
     *
     * @return boolean
     */
    protected function openString($end, &$out, $nestingOpen = null, $nestingClose = null, $trimEnd = true)
    {
        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;

        if ($nestingOpen && ! $nestingClose) {
            $nestingClose = $end;
        }

        $patt = '(.*?)([\'"]|#\{|'
            . $this->pregQuote($end) . '|'
            . (($nestingClose && $nestingClose !== $end) ? $this->pregQuote($nestingClose) . '|' : '')
            . static::$commentPattern . ')';

        $nestingLevel = 0;

        $content = [];

        while ($this->match($patt, $m, false)) {
            if (isset($m[1]) && $m[1] !== '') {
                $content[] = $m[1];

                if ($nestingOpen) {
                    $nestingLevel += substr_count($m[1], $nestingOpen);
                }
            }

            $tok = $m[2];

            $this->count-= \strlen($tok);

            if ($tok === $end && ! $nestingLevel) {
                break;
            }

            if ($tok === $nestingClose) {
                $nestingLevel--;
            }

            if (($tok === "'" || $tok === '"') && $this->string($str)) {
                $content[] = $str;
                continue;
            }

            if ($tok === '#{' && $this->interpolation($inter)) {
                $content[] = $inter;
                continue;
            }

            $content[] = $tok;
            $this->count+= \strlen($tok);
        }

        $this->eatWhiteDefault = $oldWhite;

        if (! $content || $tok !== $end) {
            return false;
        }

        // trim the end
        if ($trimEnd && \is_string(end($content))) {
            $content[\count($content) - 1] = rtrim(end($content));
        }

        $out = [Type::T_STRING, '', $content];

        return true;
    }

    /**
     * Parser interpolation
     *
     * @param string|array $out
     * @param boolean      $lookWhite save information about whitespace before and after
     *
     * @return boolean
     */
    protected function interpolation(&$out, $lookWhite = true)
    {
        $oldWhite = $this->eatWhiteDefault;
        $allowVars = $this->allowVars;
        $this->allowVars = true;
        $this->eatWhiteDefault = true;

        $s = $this->count;

        if ($this->literal('#{', 2) && $this->valueList($value) && $this->matchChar('}', false)) {
            if ($value === [Type::T_SELF]) {
                $out = $value;
            } else {
                if ($lookWhite) {
                    $left = ($s > 0 && preg_match('/\s/', $this->buffer[$s - 1])) ? ' ' : '';
                    $right = preg_match('/\s/', $this->buffer[$this->count]) ? ' ': '';
                } else {
                    $left = $right = false;
                }

                $out = [Type::T_INTERPOLATE, $value, $left, $right];
            }

            $this->eatWhiteDefault = $oldWhite;
            $this->allowVars = $allowVars;

            if ($this->eatWhiteDefault) {
                $this->whitespace();
            }

            return true;
        }

        $this->seek($s);

        $this->eatWhiteDefault = $oldWhite;
        $this->allowVars = $allowVars;

        return false;
    }

    /**
     * Parse property name (as an array of parts or a string)
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function propertyName(&$out)
    {
        $parts = [];

        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;

        for (;;) {
            if ($this->interpolation($inter)) {
                $parts[] = $inter;
                continue;
            }

            if ($this->keyword($text)) {
                $parts[] = $text;
                continue;
            }

            if (! $parts && $this->match('[:.#]', $m, false)) {
                // css hacks
                $parts[] = $m[0];
                continue;
            }

            break;
        }

        $this->eatWhiteDefault = $oldWhite;

        if (! $parts) {
            return false;
        }

        // match comment hack
        if (preg_match(static::$whitePattern, $this->buffer, $m, null, $this->count)) {
            if (! empty($m[0])) {
                $parts[] = $m[0];
                $this->count += \strlen($m[0]);
            }
        }

        $this->whitespace(); // get any extra whitespace

        $out = [Type::T_STRING, '', $parts];

        return true;
    }

    /**
     * Parse custom property name (as an array of parts or a string)
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function customProperty(&$out)
    {
        $s = $this->count;

        if (! $this->literal('--', 2, false)) {
            return false;
        }

        $parts = ['--'];

        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;

        for (;;) {
            if ($this->interpolation($inter)) {
                $parts[] = $inter;
                continue;
            }

            if ($this->variable($var)) {
                $parts[] = $var;
                continue;
            }

            if ($this->keyword($text)) {
                $parts[] = $text;
                continue;
            }

            break;
        }

        $this->eatWhiteDefault = $oldWhite;

        if (\count($parts) == 1) {
            $this->seek($s);

            return false;
        }

        $this->whitespace(); // get any extra whitespace

        $out = [Type::T_STRING, '', $parts];

        return true;
    }

    /**
     * Parse comma separated selector list
     *
     * @param array   $out
     * @param boolean $subSelector
     *
     * @return boolean
     */
    protected function selectors(&$out, $subSelector = false)
    {
        $s = $this->count;
        $selectors = [];

        while ($this->selector($sel, $subSelector)) {
            $selectors[] = $sel;

            if (! $this->matchChar(',', true)) {
                break;
            }

            while ($this->matchChar(',', true)) {
                ; // ignore extra
            }
        }

        if (! $selectors) {
            $this->seek($s);

            return false;
        }

        $out = $selectors;

        return true;
    }

    /**
     * Parse whitespace separated selector list
     *
     * @param array   $out
     * @param boolean $subSelector
     *
     * @return boolean
     */
    protected function selector(&$out, $subSelector = false)
    {
        $selector = [];

        for (;;) {
            $s = $this->count;

            if ($this->match('[>+~]+', $m, true)) {
                if ($subSelector && \is_string($subSelector) && strpos($subSelector, 'nth-') === 0 &&
                    $m[0] === '+' && $this->match("(\d+|n\b)", $counter)
                ) {
                    $this->seek($s);
                } else {
                    $selector[] = [$m[0]];
                    continue;
                }
            }

            if ($this->selectorSingle($part, $subSelector)) {
                $selector[] = $part;
                $this->match('\s+', $m);
                continue;
            }

            if ($this->match('\/[^\/]+\/', $m, true)) {
                $selector[] = [$m[0]];
                continue;
            }

            break;
        }

        if (! $selector) {
            return false;
        }

        $out = $selector;

        return true;
    }

    /**
     * Parse the parts that make up a selector
     *
     * {@internal
     *     div[yes=no]#something.hello.world:nth-child(-2n+1)%placeholder
     * }}
     *
     * @param array   $out
     * @param boolean $subSelector
     *
     * @return boolean
     */
    protected function selectorSingle(&$out, $subSelector = false)
    {
        $oldWhite = $this->eatWhiteDefault;
        $this->eatWhiteDefault = false;

        $parts = [];

        if ($this->matchChar('*', false)) {
            $parts[] = '*';
        }

        for (;;) {
            if (! isset($this->buffer[$this->count])) {
                break;
            }

            $s = $this->count;
            $char = $this->buffer[$this->count];

            // see if we can stop early
            if ($char === '{' || $char === ',' || $char === ';' || $char === '}' || $char === '@') {
                break;
            }

            // parsing a sub selector in () stop with the closing )
            if ($subSelector && $char === ')') {
                break;
            }

            //self
            switch ($char) {
                case '&':
                    $parts[] = Compiler::$selfSelector;
                    $this->count++;
                    continue 2;

                case '.':
                    $parts[] = '.';
                    $this->count++;
                    continue 2;

                case '|':
                    $parts[] = '|';
                    $this->count++;
                    continue 2;
            }

            if ($char === '\\' && $this->match('\\\\\S', $m)) {
                $parts[] = $m[0];
                continue;
            }

            if ($char === '%') {
                $this->count++;

                if ($this->placeholder($placeholder)) {
                    $parts[] = '%';
                    $parts[] = $placeholder;
                    continue;
                }

                break;
            }

            if ($char === '#') {
                if ($this->interpolation($inter)) {
                    $parts[] = $inter;
                    continue;
                }

                $parts[] = '#';
                $this->count++;
                continue;
            }

            // a pseudo selector
            if ($char === ':') {
                if ($this->buffer[$this->count + 1] === ':') {
                    $this->count += 2;
                    $part = '::';
                } else {
                    $this->count++;
                    $part = ':';
                }

                if ($this->mixedKeyword($nameParts, true)) {
                    $parts[] = $part;

                    foreach ($nameParts as $sub) {
                        $parts[] = $sub;
                    }

                    $ss = $this->count;

                    if ($nameParts === ['not'] || $nameParts === ['is'] ||
                        $nameParts === ['has'] || $nameParts === ['where'] ||
                        $nameParts === ['slotted'] ||
                        $nameParts === ['nth-child'] || $nameParts == ['nth-last-child'] ||
                        $nameParts === ['nth-of-type'] || $nameParts == ['nth-last-of-type']
                    ) {
                        if ($this->matchChar('(', true) &&
                            ($this->selectors($subs, reset($nameParts)) || true) &&
                            $this->matchChar(')')
                        ) {
                            $parts[] = '(';

                            while ($sub = array_shift($subs)) {
                                while ($ps = array_shift($sub)) {
                                    foreach ($ps as &$p) {
                                        $parts[] = $p;
                                    }

                                    if (\count($sub) && reset($sub)) {
                                        $parts[] = ' ';
                                    }
                                }

                                if (\count($subs) && reset($subs)) {
                                    $parts[] = ', ';
                                }
                            }

                            $parts[] = ')';
                        } else {
                            $this->seek($ss);
                        }
                    } else {
                        if ($this->matchChar('(') &&
                            ($this->openString(')', $str, '(') || true) &&
                            $this->matchChar(')')
                        ) {
                            $parts[] = '(';

                            if (! empty($str)) {
                                $parts[] = $str;
                            }

                            $parts[] = ')';
                        } else {
                            $this->seek($ss);
                        }
                    }

                    continue;
                }
            }

            $this->seek($s);

            // 2n+1
            if ($subSelector && \is_string($subSelector) && strpos($subSelector, 'nth-') === 0) {
                if ($this->match("(\s*(\+\s*|\-\s*)?(\d+|n|\d+n))+", $counter)) {
                    $parts[] = $counter[0];
                    //$parts[] = str_replace(' ', '', $counter[0]);
                    continue;
                }
            }

            $this->seek($s);

            // attribute selector
            if ($char === '[' &&
                $this->matchChar('[') &&
                ($this->openString(']', $str, '[') || true) &&
                $this->matchChar(']')
            ) {
                $parts[] = '[';

                if (! empty($str)) {
                    $parts[] = $str;
                }

                $parts[] = ']';
                continue;
            }

            $this->seek($s);

            // for keyframes
            if ($this->unit($unit)) {
                $parts[] = $unit;
                continue;
            }

            if ($this->restrictedKeyword($name)) {
                $parts[] = $name;
                continue;
            }

            break;
        }

        $this->eatWhiteDefault = $oldWhite;

        if (! $parts) {
            return false;
        }

        $out = $parts;

        return true;
    }

    /**
     * Parse a variable
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function variable(&$out)
    {
        $s = $this->count;

        if ($this->matchChar('$', false) && $this->keyword($name)) {
            if ($this->allowVars) {
                $out = [Type::T_VARIABLE, $name];
            } else {
                $out = [Type::T_KEYWORD, '$' . $name];
            }

            return true;
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse a keyword
     *
     * @param string  $word
     * @param boolean $eatWhitespace
     *
     * @return boolean
     */
    protected function keyword(&$word, $eatWhitespace = null)
    {
        if ($this->match(
            $this->utf8
                ? '(([\pL\w\x{00A0}-\x{10FFFF}_\-\*!"\']|[\\\\].)([\pL\w\x{00A0}-\x{10FFFF}\-_"\']|[\\\\].)*)'
                : '(([\w_\-\*!"\']|[\\\\].)([\w\-_"\']|[\\\\].)*)',
            $m,
            $eatWhitespace
        )) {
            $word = $m[1];

            return true;
        }

        return false;
    }

    /**
     * Parse a keyword that should not start with a number
     *
     * @param string  $word
     * @param boolean $eatWhitespace
     *
     * @return boolean
     */
    protected function restrictedKeyword(&$word, $eatWhitespace = null)
    {
        $s = $this->count;

        if ($this->keyword($word, $eatWhitespace) && (\ord($word[0]) > 57 || \ord($word[0]) < 48)) {
            return true;
        }

        $this->seek($s);

        return false;
    }

    /**
     * Parse a placeholder
     *
     * @param string|array $placeholder
     *
     * @return boolean
     */
    protected function placeholder(&$placeholder)
    {
        if ($this->match(
            $this->utf8
                ? '([\pL\w\-_]+)'
                : '([\w\-_]+)',
            $m
        )) {
            $placeholder = $m[1];

            return true;
        }

        if ($this->interpolation($placeholder)) {
            return true;
        }

        return false;
    }

    /**
     * Parse a url
     *
     * @param array $out
     *
     * @return boolean
     */
    protected function url(&$out)
    {
        if ($this->match('(url\(\s*(["\']?)([^)]+)\2\s*\))', $m)) {
            $out = [Type::T_STRING, '', ['url(' . $m[2] . $m[3] . $m[2] . ')']];

            return true;
        }

        return false;
    }

    /**
     * Consume an end of statement delimiter
     *
     * @return boolean
     */
    protected function end()
    {
        if ($this->matchChar(';')) {
            return true;
        }

        if ($this->count === \strlen($this->buffer) || $this->buffer[$this->count] === '}') {
            // if there is end of file or a closing block next then we don't need a ;
            return true;
        }

        return false;
    }

    /**
     * Strip assignment flag from the list
     *
     * @param array $value
     *
     * @return array
     */
    protected function stripAssignmentFlags(&$value)
    {
        $flags = [];

        for ($token = &$value; $token[0] === Type::T_LIST && ($s = \count($token[2])); $token = &$lastNode) {
            $lastNode = &$token[2][$s - 1];

            while ($lastNode[0] === Type::T_KEYWORD && \in_array($lastNode[1], ['!default', '!global'])) {
                array_pop($token[2]);

                $node     = end($token[2]);
                $token    = $this->flattenList($token);
                $flags[]  = $lastNode[1];
                $lastNode = $node;
            }
        }

        return $flags;
    }

    /**
     * Strip optional flag from selector list
     *
     * @param array $selectors
     *
     * @return string
     */
    protected function stripOptionalFlag(&$selectors)
    {
        $optional = false;
        $selector = end($selectors);
        $part     = end($selector);

        if ($part === ['!optional']) {
            array_pop($selectors[\count($selectors) - 1]);

            $optional = true;
        }

        return $optional;
    }

    /**
     * Turn list of length 1 into value type
     *
     * @param array $value
     *
     * @return array
     */
    protected function flattenList($value)
    {
        if ($value[0] === Type::T_LIST && \count($value[2]) === 1) {
            return $this->flattenList($value[2][0]);
        }

        return $value;
    }

    /**
     * Quote regular expression
     *
     * @param string $what
     *
     * @return string
     */
    private function pregQuote($what)
    {
        return preg_quote($what, '/');
    }

    /**
     * Extract line numbers from buffer
     *
     * @param string $buffer
     */
    private function extractLineNumbers($buffer)
    {
        $this->sourcePositions = [0 => 0];
        $prev = 0;

        while (($pos = strpos($buffer, "\n", $prev)) !== false) {
            $this->sourcePositions[] = $pos;
            $prev = $pos + 1;
        }

        $this->sourcePositions[] = \strlen($buffer);

        if (substr($buffer, -1) !== "\n") {
            $this->sourcePositions[] = \strlen($buffer) + 1;
        }
    }

    /**
     * Get source line number and column (given character position in the buffer)
     *
     * @param integer $pos
     *
     * @return array
     */
    private function getSourcePosition($pos)
    {
        $low = 0;
        $high = \count($this->sourcePositions);

        while ($low < $high) {
            $mid = (int) (($high + $low) / 2);

            if ($pos < $this->sourcePositions[$mid]) {
                $high = $mid - 1;
                continue;
            }

            if ($pos >= $this->sourcePositions[$mid + 1]) {
                $low = $mid + 1;
                continue;
            }

            return [$mid + 1, $pos - $this->sourcePositions[$mid]];
        }

        return [$low + 1, $pos - $this->sourcePositions[$low]];
    }

    /**
     * Save internal encoding
     */
    private function saveEncoding()
    {
        if (\extension_loaded('mbstring')) {
            $this->encoding = mb_internal_encoding();

            mb_internal_encoding('iso-8859-1');
        }
    }

    /**
     * Restore internal encoding
     */
    private function restoreEncoding()
    {
        if (\extension_loaded('mbstring') && $this->encoding) {
            mb_internal_encoding($this->encoding);
        }
    }
}
