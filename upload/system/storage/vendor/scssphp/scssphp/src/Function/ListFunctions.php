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

namespace ScssPhp\ScssPhp\Function;

use ScssPhp\ScssPhp\Exception\SassScriptException;
use ScssPhp\ScssPhp\Util\IterableUtil;
use ScssPhp\ScssPhp\Value\ListSeparator;
use ScssPhp\ScssPhp\Value\SassBoolean;
use ScssPhp\ScssPhp\Value\SassList;
use ScssPhp\ScssPhp\Value\SassNull;
use ScssPhp\ScssPhp\Value\SassNumber;
use ScssPhp\ScssPhp\Value\SassString;
use ScssPhp\ScssPhp\Value\Value;

/**
 * @internal
 */
class ListFunctions
{
    /**
     * @param list<Value> $arguments
     */
    public static function length(array $arguments): Value
    {
        return SassNumber::create(\count($arguments[0]->asList()));
    }

    /**
     * @param list<Value> $arguments
     */
    public static function nth(array $arguments): Value
    {
        $list = $arguments[0];
        $index = $arguments[1];

        return $list->asList()[$list->sassIndexToListIndex($index, 'n')];
    }

    /**
     * @param list<Value> $arguments
     */
    public static function setNth(array $arguments): Value
    {
        $list = $arguments[0];
        $index = $arguments[1];
        $value = $arguments[2];

        $newList = $list->asList();
        $newList[$list->sassIndexToListIndex($index, 'n')] = $value;
        \assert(array_is_list($newList), 'The mutation is guaranteed to affect an existing index');

        return $list->withListContents($newList);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function join(array $arguments): Value
    {
        $list1 = $arguments[0];
        $list2 = $arguments[1];
        $separatorParam = $arguments[2]->assertString('separator');
        $bracketedParam = $arguments[3];

        $separator = match ($separatorParam->getText()) {
            'auto' => self::getAutoJoinSeparator($list1->getSeparator(), $list2->getSeparator()),
            'space' => ListSeparator::SPACE,
            'comma' => ListSeparator::COMMA,
            'slash' => ListSeparator::SLASH,
            default => throw new SassScriptException('$separator: Must be "space", "comma", "slash", or "auto".')
        };

        $bracketed = $bracketedParam instanceof SassString && $bracketedParam->getText() === 'auto' ? $list1->hasBrackets() : $bracketedParam->isTruthy();

        $newList = [...$list1->asList(), ...$list2->asList()];

        return new SassList($newList, $separator, $bracketed);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function append(array $arguments): Value
    {
        $list = $arguments[0];
        $value = $arguments[1];
        $separatorParam = $arguments[2]->assertString('separator');

        $separator = match ($separatorParam->getText()) {
            'auto' => $list->getSeparator() === ListSeparator::UNDECIDED ? ListSeparator::SPACE : $list->getSeparator(),
            'space' => ListSeparator::SPACE,
            'comma' => ListSeparator::COMMA,
            'slash' => ListSeparator::SLASH,
            default => throw new SassScriptException('$separator: Must be "space", "comma", "slash", or "auto".')
        };

        $newList = [...$list->asList(), $value];

        return $list->withListContents($newList, $separator);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function zip(array $arguments): Value
    {
        $lists = array_map(fn (Value $list) => $list->asList(), $arguments[0]->asList());

        if (\count($lists) === 0) {
            return SassList::createEmpty(ListSeparator::COMMA);
        }

        $i = 0;
        $results = [];

        while (IterableUtil::every($lists, fn ($list) => $i !== \count($list))) {
            $results[] = new SassList(array_map(fn ($list) => $list[$i], $lists), ListSeparator::SPACE);
            $i++;
        }

        return new SassList($results, ListSeparator::COMMA);
    }

    /**
     * @param list<Value> $arguments
     */
    public static function index(array $arguments): Value
    {
        $list = $arguments[0]->asList();
        $value = $arguments[1];

        foreach ($list as $index => $item) {
            if ($item->equals($value)) {
                return SassNumber::create($index + 1);
            }
        }

        return SassNull::create();
    }

    /**
     * @param list<Value> $arguments
     */
    public static function separator(array $arguments): Value
    {
        return match ($arguments[0]->getSeparator()) {
            ListSeparator::COMMA => new SassString('comma', false),
            ListSeparator::SLASH => new SassString('slash', false),
            default => new SassString('space', false),
        };
    }

    /**
     * @param list<Value> $arguments
     */
    public static function isBracketed(array $arguments): Value
    {
        return SassBoolean::create($arguments[0]->hasBrackets());
    }

    private static function getAutoJoinSeparator(ListSeparator $separator1, ListSeparator $separator2): ListSeparator
    {
        if ($separator1 === ListSeparator::UNDECIDED && $separator2 === ListSeparator::UNDECIDED) {
            return ListSeparator::SPACE;
        }

        if ($separator1 === ListSeparator::UNDECIDED) {
            return $separator2;
        }

        return $separator1;
    }
}
