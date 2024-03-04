<?php

// Start of SPL v.0.2
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;

/**
 * Exception that represents error in the program logic. This kind of
 * exception should lead directly to a fix in your code.
 * @link https://php.net/manual/en/class.logicexception.php
 */
class LogicException extends Exception {}

/**
 * Exception thrown if a callback refers to an undefined function or if some
 * arguments are missing.
 * @link https://php.net/manual/en/class.badfunctioncallexception.php
 */
class BadFunctionCallException extends LogicException {}

/**
 * Exception thrown if a callback refers to an undefined method or if some
 * arguments are missing.
 * @link https://php.net/manual/en/class.badmethodcallexception.php
 */
class BadMethodCallException extends BadFunctionCallException {}

/**
 * Exception thrown if a value does not adhere to a defined valid data domain.
 * @link https://php.net/manual/en/class.domainexception.php
 */
class DomainException extends LogicException {}

/**
 * Exception thrown if an argument is not of the expected type.
 * @link https://php.net/manual/en/class.invalidargumentexception.php
 */
class InvalidArgumentException extends LogicException {}

/**
 * Exception thrown if a length is invalid.
 * @link https://php.net/manual/en/class.lengthexception.php
 */
class LengthException extends LogicException {}

/**
 * Exception thrown when an illegal index was requested. This represents
 * errors that should be detected at compile time.
 * @link https://php.net/manual/en/class.outofrangeexception.php
 */
class OutOfRangeException extends LogicException {}

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 * @link https://php.net/manual/en/class.runtimeexception.php
 */
class RuntimeException extends Exception {}

/**
 * Exception thrown if a value is not a valid key. This represents errors
 * that cannot be detected at compile time.
 * @link https://php.net/manual/en/class.outofboundsexception.php
 */
class OutOfBoundsException extends RuntimeException {}

/**
 * Exception thrown when adding an element to a full container.
 * @link https://php.net/manual/en/class.overflowexception.php
 */
class OverflowException extends RuntimeException {}

/**
 * Exception thrown to indicate range errors during program execution.
 * Normally this means there was an arithmetic error other than
 * under/overflow. This is the runtime version of
 * <b>DomainException</b>.
 * @link https://php.net/manual/en/class.rangeexception.php
 */
class RangeException extends RuntimeException {}

/**
 * Exception thrown when performing an invalid operation on an empty container, such as removing an element.
 * @link https://php.net/manual/en/class.underflowexception.php
 */
class UnderflowException extends RuntimeException {}

/**
 * Exception thrown if a value does not match with a set of values. Typically
 * this happens when a function calls another function and expects the return
 * value to be of a certain type or value not including arithmetic or buffer
 * related errors.
 * @link https://php.net/manual/en/class.unexpectedvalueexception.php
 */
class UnexpectedValueException extends RuntimeException {}

/**
 * The EmptyIterator class for an empty iterator.
 * @link https://secure.php.net/manual/en/class.emptyiterator.php
 */
class EmptyIterator implements Iterator
{
    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    #[TentativeType]
    public function current(): never {}

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed The key of the current element.
     */
    #[TentativeType]
    public function key(): never {}

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    #[TentativeType]
    #[LanguageLevelTypeAware(['8.2' => 'false'], default: 'bool')]
    public function valid() {}

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    #[TentativeType]
    public function rewind(): void {}
}

/**
 * Filtered iterator using the callback to determine which items are accepted or rejected.
 * @link https://secure.php.net/manual/en/class.callbackfilteriterator.php
 * @since 5.4
 */
class CallbackFilterIterator extends FilterIterator
{
    /**
     * Creates a filtered iterator using the callback to determine which items are accepted or rejected.
     * @param Iterator $iterator The iterator to be filtered.
     * @param callable $callback The callback, which should return TRUE to accept the current item or FALSE otherwise.
     * May be any valid callable value.
     * The callback should accept up to three arguments: the current item, the current key and the iterator, respectively.
     * <code> function my_callback($current, $key, $iterator) </code>
     * @link https://secure.php.net/manual/en/callbackfilteriterator.construct.php
     */
    public function __construct(Iterator $iterator, callable $callback) {}

    /**
     * This method calls the callback with the current value, current key and the inner iterator.
     * The callback is expected to return TRUE if the current item is to be accepted, or FALSE otherwise.
     * @link https://secure.php.net/manual/en/callbackfilteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    #[TentativeType]
    public function accept(): bool {}
}

/**
 * (PHP 5 >= 5.4.0)<br>
 * RecursiveCallbackFilterIterator from a RecursiveIterator
 * @link https://secure.php.net/manual/en/class.recursivecallbackfilteriterator.php
 * @since 5.4
 */
class RecursiveCallbackFilterIterator extends CallbackFilterIterator implements RecursiveIterator
{
    /**
     * Create a RecursiveCallbackFilterIterator from a RecursiveIterator
     * @param RecursiveIterator $iterator The recursive iterator to be filtered.
     * @param callable $callback The callback, which should return TRUE to accept the current item or FALSE otherwise. See Examples.
     * May be any valid callable value.
     * @link https://www.php.net/manual/en/recursivecallbackfilteriterator.construct.php
     */
    public function __construct(
        RecursiveIterator $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback
    ) {}

    /**
     * Check whether the inner iterator's current element has children
     * @link https://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool Returns TRUE if the current element has children, FALSE otherwise.
     */
    #[TentativeType]
    public function hasChildren(): bool {}

    /**
     * Returns an iterator for the current entry.
     * @link https://secure.php.net/manual/en/recursivecallbackfilteriterator.haschildren.php
     * @return RecursiveCallbackFilterIterator containing the children.
     */
    #[TentativeType]
    public function getChildren(): RecursiveCallbackFilterIterator {}
}

/**
 * Classes implementing <b>RecursiveIterator</b> can be used to iterate
 * over iterators recursively.
 * @link https://php.net/manual/en/class.recursiveiterator.php
 */
interface RecursiveIterator extends Iterator
{
    /**
     * Returns if an iterator can be created for the current entry.
     * @link https://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    #[TentativeType]
    public function hasChildren(): bool;

    /**
     * Returns an iterator for the current entry.
     * @link https://php.net/manual/en/recursiveiterator.getchildren.php
     * @return RecursiveIterator|null An iterator for the current entry.
     */
    #[TentativeType]
    public function getChildren(): ?RecursiveIterator;
}

/**
 * Can be used to iterate through recursive iterators.
 * @link https://php.net/manual/en/class.recursiveiteratoriterator.php
 */
class RecursiveIteratorIterator implements OuterIterator
{
    /**
     * The default. Lists only leaves in iteration.
     */
    public const LEAVES_ONLY = 0;

    /**
     * Lists leaves and parents in iteration with parents coming first.
     */
    public const SELF_FIRST = 1;

    /**
     * Lists leaves and parents in iteration with leaves coming first.
     */
    public const CHILD_FIRST = 2;

    /**
     * Special flag: Ignore exceptions thrown in accessing children.
     */
    public const CATCH_GET_CHILD = 16;

    /**
     * Construct a RecursiveIteratorIterator
     * @link https://php.net/manual/en/recursiveiteratoriterator.construct.php
     * @param Traversable $iterator
     * @param int $mode [optional] The operation mode. See class constants for details.
     * @param int $flags [optional] A bitmask of special flags. See class constants for details.
     * @since 5.1.3
     */
    public function __construct(
        Traversable $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode = self::LEAVES_ONLY,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0
    ) {}

    /**
     * Rewind the iterator to the first element of the top level inner iterator
     * @link https://php.net/manual/en/recursiveiteratoriterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Check whether the current position is valid
     * @link https://php.net/manual/en/recursiveiteratoriterator.valid.php
     * @return bool true if the current position is valid, otherwise false
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Access the current key
     * @link https://php.net/manual/en/recursiveiteratoriterator.key.php
     * @return mixed The key of the current element.
     */
    #[TentativeType]
    public function key(): mixed {}

    /**
     * Access the current element value
     * @link https://php.net/manual/en/recursiveiteratoriterator.current.php
     * @return mixed The current elements value.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Move forward to the next element
     * @link https://php.net/manual/en/recursiveiteratoriterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Get the current depth of the recursive iteration
     * @link https://php.net/manual/en/recursiveiteratoriterator.getdepth.php
     * @return int The current depth of the recursive iteration.
     */
    #[TentativeType]
    public function getDepth(): int {}

    /**
     * The current active sub iterator
     * @link https://php.net/manual/en/recursiveiteratoriterator.getsubiterator.php
     * @param int $level [optional]
     * @return RecursiveIterator|null The current active sub iterator.
     */
    #[TentativeType]
    public function getSubIterator(#[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $level): ?RecursiveIterator {}

    /**
     * Get inner iterator
     * @link https://php.net/manual/en/recursiveiteratoriterator.getinneriterator.php
     * @return RecursiveIterator The current active sub iterator.
     */
    #[TentativeType]
    public function getInnerIterator(): RecursiveIterator {}

    /**
     * Begin Iteration
     * @link https://php.net/manual/en/recursiveiteratoriterator.beginiteration.php
     * @return void
     */
    #[TentativeType]
    public function beginIteration(): void {}

    /**
     * End Iteration
     * @link https://php.net/manual/en/recursiveiteratoriterator.enditeration.php
     * @return void
     */
    #[TentativeType]
    public function endIteration(): void {}

    /**
     * Has children
     * @link https://php.net/manual/en/recursiveiteratoriterator.callhaschildren.php
     * @return bool true if the element has children, otherwise false
     */
    #[TentativeType]
    public function callHasChildren(): bool {}

    /**
     * Get children
     * @link https://php.net/manual/en/recursiveiteratoriterator.callgetchildren.php
     * @return RecursiveIterator|null A <b>RecursiveIterator</b>.
     */
    #[TentativeType]
    public function callGetChildren(): ?RecursiveIterator {}

    /**
     * Begin children
     * @link https://php.net/manual/en/recursiveiteratoriterator.beginchildren.php
     * @return void
     */
    #[TentativeType]
    public function beginChildren(): void {}

    /**
     * End children
     * @link https://php.net/manual/en/recursiveiteratoriterator.endchildren.php
     * @return void
     */
    #[TentativeType]
    public function endChildren(): void {}

    /**
     * Next element
     * @link https://php.net/manual/en/recursiveiteratoriterator.nextelement.php
     * @return void
     */
    #[TentativeType]
    public function nextElement(): void {}

    /**
     * Set max depth
     * @link https://php.net/manual/en/recursiveiteratoriterator.setmaxdepth.php
     * @param int $maxDepth [optional] <p>
     * The maximum allowed depth. Default -1 is used
     * for any depth.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setMaxDepth(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $maxDepth = -1): void {}

    /**
     * Get max depth
     * @link https://php.net/manual/en/recursiveiteratoriterator.getmaxdepth.php
     * @return int|false The maximum accepted depth, or false if any depth is allowed.
     */
    #[TentativeType]
    public function getMaxDepth(): int|false {}
}

/**
 * Classes implementing <b>OuterIterator</b> can be used to iterate
 * over iterators.
 * @link https://php.net/manual/en/class.outeriterator.php
 */
interface OuterIterator extends Iterator
{
    /**
     * Returns the inner iterator for the current entry.
     * @link https://php.net/manual/en/outeriterator.getinneriterator.php
     * @return Iterator|null The inner iterator for the current entry.
     */
    #[TentativeType]
    public function getInnerIterator(): ?Iterator;
}

/**
 * This iterator wrapper allows the conversion of anything that is
 * Traversable into an Iterator.
 * It is important to understand that most classes that do not implement
 * Iterators have reasons as most likely they do not allow the full
 * Iterator feature set. If so, techniques should be provided to prevent
 * misuse, otherwise expect exceptions or fatal errors.
 * @link https://php.net/manual/en/class.iteratoriterator.php
 */
class IteratorIterator implements OuterIterator
{
    /**
     * Create an iterator from anything that is traversable
     * @link https://php.net/manual/en/iteratoriterator.construct.php
     * @param Traversable $iterator
     * @param string|null $class [optional]
     */
    public function __construct(Traversable $iterator, #[PhpStormStubsElementAvailable(from: '8.0')] ?string $class = '') {}

    /**
     * Get the inner iterator
     * @link https://php.net/manual/en/iteratoriterator.getinneriterator.php
     * @return Iterator|null The inner iterator as passed to IteratorIterator::__construct.
     */
    #[TentativeType]
    public function getInnerIterator(): ?Iterator {}

    /**
     * Rewind to the first element
     * @link https://php.net/manual/en/iteratoriterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Checks if the iterator is valid
     * @link https://php.net/manual/en/iteratoriterator.valid.php
     * @return bool true if the iterator is valid, otherwise false
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Get the key of the current element
     * @link https://php.net/manual/en/iteratoriterator.key.php
     * @return mixed The key of the current element.
     */
    #[TentativeType]
    public function key(): mixed {}

    /**
     * Get the current value
     * @link https://php.net/manual/en/iteratoriterator.current.php
     * @return mixed The value of the current element.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Forward to the next element
     * @link https://php.net/manual/en/iteratoriterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}
}

/**
 * This abstract iterator filters out unwanted values. This class should be extended to
 * implement custom iterator filters. The <b>FilterIterator::accept</b>
 * must be implemented in the subclass.
 * @link https://php.net/manual/en/class.filteriterator.php
 */
abstract class FilterIterator extends IteratorIterator
{
    /**
     * Check whether the current element of the iterator is acceptable
     * @link https://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    #[TentativeType]
    abstract public function accept(): bool;

    /**
     * Construct a filterIterator
     * @link https://php.net/manual/en/filteriterator.construct.php
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator) {}

    /**
     * Rewind the iterator
     * @link https://php.net/manual/en/filteriterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Check whether the current element is valid
     * @link https://php.net/manual/en/filteriterator.valid.php
     * @return bool true if the current element is valid, otherwise false
     */
    public function valid(): bool {}

    /**
     * Get the current key
     * @link https://php.net/manual/en/filteriterator.key.php
     * @return mixed The key of the current element.
     */
    public function key(): mixed {}

    /**
     * Get the current element value
     * @link https://php.net/manual/en/filteriterator.current.php
     * @return mixed The current element value.
     */
    public function current(): mixed {}

    /**
     * Move the iterator forward
     * @link https://php.net/manual/en/filteriterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Get the inner iterator
     * @link https://php.net/manual/en/filteriterator.getinneriterator.php
     * @return Iterator The inner iterator.
     */
    public function getInnerIterator(): Iterator {}
}

/**
 * This abstract iterator filters out unwanted values for a <b>RecursiveIterator</b>.
 * This class should be extended to implement custom filters.
 * The <b>RecursiveFilterIterator::accept</b> must be implemented in the subclass.
 * @link https://php.net/manual/en/class.recursivefilteriterator.php
 */
abstract class RecursiveFilterIterator extends FilterIterator implements RecursiveIterator
{
    /**
     * Create a RecursiveFilterIterator from a RecursiveIterator
     * @link https://php.net/manual/en/recursivefilteriterator.construct.php
     * @param RecursiveIterator $iterator
     */
    public function __construct(RecursiveIterator $iterator) {}

    /**
     * Check whether the inner iterator's current element has children
     * @link https://php.net/manual/en/recursivefilteriterator.haschildren.php
     * @return bool true if the inner iterator has children, otherwise false
     */
    #[TentativeType]
    public function hasChildren(): bool {}

    /**
     * Return the inner iterator's children contained in a RecursiveFilterIterator
     * @link https://php.net/manual/en/recursivefilteriterator.getchildren.php
     * @return RecursiveFilterIterator|null containing the inner iterator's children.
     */
    #[TentativeType]
    public function getChildren(): ?RecursiveFilterIterator {}
}

/**
 * This extended FilterIterator allows a recursive iteration using RecursiveIteratorIterator that only shows those elements which have children.
 * @link https://php.net/manual/en/class.parentiterator.php
 */
class ParentIterator extends RecursiveFilterIterator
{
    /**
     * Determines acceptability
     * @link https://php.net/manual/en/parentiterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    #[TentativeType]
    public function accept(): bool {}

    /**
     * Constructs a ParentIterator
     * @link https://php.net/manual/en/parentiterator.construct.php
     * @param RecursiveIterator $iterator
     */
    public function __construct(RecursiveIterator $iterator) {}

    /**
     * Check whether the inner iterator's current element has children
     * @link https://php.net/manual/en/recursivefilteriterator.haschildren.php
     * @return bool true if the inner iterator has children, otherwise false
     */
    public function hasChildren() {}

    /**
     * Return the inner iterator's children contained in a RecursiveFilterIterator
     * @link https://php.net/manual/en/recursivefilteriterator.getchildren.php
     * @return ParentIterator containing the inner iterator's children.
     */
    public function getChildren() {}
}

/**
 * The Seekable iterator.
 * @link https://php.net/manual/en/class.seekableiterator.php
 */
interface SeekableIterator extends Iterator
{
    /**
     * Seeks to a position
     * @link https://php.net/manual/en/seekableiterator.seek.php
     * @param int $offset <p>
     * The position to seek to.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function seek(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset): void;
}

/**
 * The <b>LimitIterator</b> class allows iteration over
 * a limited subset of items in an <b>Iterator</b>.
 * @link https://php.net/manual/en/class.limititerator.php
 */
class LimitIterator extends IteratorIterator
{
    /**
     * Construct a LimitIterator
     * @link https://php.net/manual/en/limititerator.construct.php
     * @param Iterator $iterator The iterator to limit.
     * @param int $offset [optional] The offset to start at. Must be zero or greater.
     * @param int $limit [optional] The number of items to iterate. Must be -1 or greater. -1, the default, means no limit.
     */
    public function __construct(
        Iterator $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset = 0,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $limit = -1
    ) {}

    /**
     * Rewind the iterator to the specified starting offset
     * @link https://php.net/manual/en/limititerator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Check whether the current element is valid
     * @link https://php.net/manual/en/limititerator.valid.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Get current key
     * @link https://php.net/manual/en/limititerator.key.php
     * @return mixed The key of the current element.
     */
    public function key(): mixed {}

    /**
     * Get current element
     * @link https://php.net/manual/en/limititerator.current.php
     * @return mixed the current element or null if there is none.
     */
    public function current(): mixed {}

    /**
     * Move the iterator forward
     * @link https://php.net/manual/en/limititerator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Seek to the given position
     * @link https://php.net/manual/en/limititerator.seek.php
     * @param int $offset <p>
     * The position to seek to.
     * </p>
     * @return int the offset position after seeking.
     */
    #[TentativeType]
    public function seek(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset): int {}

    /**
     * Return the current position
     * @link https://php.net/manual/en/limititerator.getposition.php
     * @return int The current position.
     */
    #[TentativeType]
    public function getPosition(): int {}

    /**
     * Get inner iterator
     * @link https://php.net/manual/en/limititerator.getinneriterator.php
     * @return Iterator The inner iterator passed to <b>LimitIterator::__construct</b>.
     */
    public function getInnerIterator(): Iterator {}
}

/**
 * This object supports cached iteration over another iterator.
 * @link https://php.net/manual/en/class.cachingiterator.php
 */
class CachingIterator extends IteratorIterator implements ArrayAccess, Countable, Stringable
{
    /**
     * String conversion flag (mutually exclusive): Uses the current element for the iterator's string conversion.
     * This converts the current element to a string only once, regardless of whether it is needed or not.
     */
    public const CALL_TOSTRING = 1;

    /**
     * String conversion flag (mutually exclusive). Uses the current key for the iterator's string conversion.
     */
    public const TOSTRING_USE_KEY = 2;

    /**
     * String conversion flag (mutually exclusive). Uses the current element for the iterator's string conversion.
     * This converts the current element to a string only when (and every time) it is needed.
     */
    public const TOSTRING_USE_CURRENT = 4;

    /**
     * String conversion flag (mutually exclusive). Forwards the string conversion to the inner iterator.
     * This converts the inner iterator to a string only once, regardless of whether it is needed or not.
     */
    public const TOSTRING_USE_INNER = 8;

    /**
     * Ignore exceptions thrown in accessing children. Only used with {@see RecursiveCachingIterator}.
     */
    public const CATCH_GET_CHILD = 16;

    /**
     * Cache all read data. This is needed to use {@see CachingIterator::getCache}, and ArrayAccess and Countable methods.
     */
    public const FULL_CACHE = 256;

    /**
     * Constructs a new CachingIterator.
     * @link https://php.net/manual/en/cachingiterator.construct.php
     * @param Iterator $iterator The iterator to cache.
     * @param int $flags [optional] A bitmask of flags. See CachingIterator class constants for details.
     */
    public function __construct(Iterator $iterator, #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = self::CALL_TOSTRING) {}

    /**
     * Rewind the iterator
     * @link https://php.net/manual/en/cachingiterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Check whether the current element is valid
     * @link https://php.net/manual/en/cachingiterator.valid.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Return the key for the current element
     * @link https://php.net/manual/en/cachingiterator.key.php
     * @return mixed The key of the current element.
     */
    public function key(): mixed {}

    /**
     * Return the current element
     * @link https://php.net/manual/en/cachingiterator.current.php
     * @return mixed
     */
    public function current(): mixed {}

    /**
     * Move the iterator forward
     * @link https://php.net/manual/en/cachingiterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Check whether the inner iterator has a valid next element
     * @link https://php.net/manual/en/cachingiterator.hasnext.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function hasNext(): bool {}

    /**
     * Return the string representation of the current iteration based on the flag being used.
     * @link https://php.net/manual/en/cachingiterator.tostring.php
     * @return string The string representation of the current iteration based on the flag being used.
     */
    #[TentativeType]
    public function __toString(): string {}

    /**
     * Returns the inner iterator
     * @link https://php.net/manual/en/cachingiterator.getinneriterator.php
     * @return Iterator an object implementing the Iterator interface.
     */
    public function getInnerIterator(): Iterator {}

    /**
     * Get flags used
     * @link https://php.net/manual/en/cachingiterator.getflags.php
     * @return int Bitmask of the flags
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * The setFlags purpose
     * @link https://php.net/manual/en/cachingiterator.setflags.php
     * @param int $flags Bitmask of the flags to set.
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Internal cache array index to retrieve.
     * @link https://php.net/manual/en/cachingiterator.offsetget.php
     * @param string $key The index of the element to retrieve.
     * @return mixed
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     */
    #[TentativeType]
    public function offsetGet($key): mixed {}

    /**
     * Set an element on the internal cache array.
     * @link https://php.net/manual/en/cachingiterator.offsetset.php
     * @param string $key The index of the element to be set.
     * @param string $value The new value for the <i>index</i>.
     * @return void
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     */
    #[TentativeType]
    public function offsetSet($key, #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Remove an element from the internal cache array.
     * @link https://php.net/manual/en/cachingiterator.offsetunset.php
     * @param string $key The index of the element to be unset.
     * @return void
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     */
    #[TentativeType]
    public function offsetUnset($key): void {}

    /**
     * Return whether an element at the index exists on the internal cache array.
     * @link https://php.net/manual/en/cachingiterator.offsetexists.php
     * @param string $key The index being checked.
     * @return bool true if an entry referenced by the offset exists, false otherwise.
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     */
    #[TentativeType]
    public function offsetExists($key): bool {}

    /**
     * Retrieve the contents of the cache
     * @link https://php.net/manual/en/cachingiterator.getcache.php
     * @return array An array containing the cache items.
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     */
    #[TentativeType]
    public function getCache(): array {}

    /**
     * The number of elements in the iterator
     * @link https://php.net/manual/en/cachingiterator.count.php
     * @return int The count of the elements iterated over.
     * @throws BadMethodCallException when the {@see CachingIterator::FULL_CACHE} flag is not being used.
     * @since 5.2.2
     */
    #[TentativeType]
    public function count(): int {}
}

/**
 * ...
 * @link https://php.net/manual/en/class.recursivecachingiterator.php
 */
class RecursiveCachingIterator extends CachingIterator implements RecursiveIterator
{
    /**
     * Constructs a new RecursiveCachingIterator.
     * @link https://php.net/manual/en/recursivecachingiterator.construct.php
     * @param Iterator $iterator The iterator to cache.
     * @param int $flags [optional] A bitmask of flags. See CachingIterator class constants for details.
     */
    public function __construct(Iterator $iterator, #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = self::CALL_TOSTRING) {}

    /**
     * Check whether the current element of the inner iterator has children
     * @link https://php.net/manual/en/recursivecachingiterator.haschildren.php
     * @return bool true if the inner iterator has children, otherwise false
     */
    #[TentativeType]
    public function hasChildren(): bool {}

    /**
     * Return the inner iterator's children as a RecursiveCachingIterator
     * @link https://php.net/manual/en/recursivecachingiterator.getchildren.php
     * @return RecursiveCachingIterator|null The inner iterator's children, as a RecursiveCachingIterator.
     */
    #[TentativeType]
    public function getChildren(): ?RecursiveCachingIterator {}
}

/**
 * This iterator cannot be rewinded.
 * @link https://php.net/manual/en/class.norewinditerator.php
 */
class NoRewindIterator extends IteratorIterator
{
    /**
     * Construct a NoRewindIterator
     * @link https://php.net/manual/en/norewinditerator.construct.php
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator) {}

    /**
     * Prevents the rewind operation on the inner iterator.
     * @link https://php.net/manual/en/norewinditerator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Validates the iterator
     * @link https://php.net/manual/en/norewinditerator.valid.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Get the current key
     * @link https://php.net/manual/en/norewinditerator.key.php
     * @return mixed The key of the current element.
     */
    #[TentativeType]
    public function key(): mixed {}

    /**
     * Get the current value
     * @link https://php.net/manual/en/norewinditerator.current.php
     * @return mixed The current value.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Forward to the next element
     * @link https://php.net/manual/en/norewinditerator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Get the inner iterator
     * @link https://php.net/manual/en/norewinditerator.getinneriterator.php
     * @return Iterator The inner iterator, as passed to <b>NoRewindIterator::__construct</b>.
     */
    public function getInnerIterator(): Iterator {}
}

/**
 * An Iterator that iterates over several iterators one after the other.
 * @link https://php.net/manual/en/class.appenditerator.php
 */
class AppendIterator extends IteratorIterator
{
    /**
     * Constructs an AppendIterator
     * @link https://php.net/manual/en/appenditerator.construct.php
     */
    public function __construct() {}

    /**
     * Appends an iterator
     * @link https://php.net/manual/en/appenditerator.append.php
     * @param Iterator $iterator <p>
     * The iterator to append.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function append(Iterator $iterator): void {}

    /**
     * Rewinds the Iterator
     * @link https://php.net/manual/en/appenditerator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Checks validity of the current element
     * @link https://php.net/manual/en/appenditerator.valid.php
     * @return bool true on success or false on failure.
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Gets the current key
     * @link https://php.net/manual/en/appenditerator.key.php
     * @return mixed The key of the current element.
     */
    public function key(): mixed {}

    /**
     * Gets the current value
     * @link https://php.net/manual/en/appenditerator.current.php
     * @return mixed The current value if it is valid or null otherwise.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Moves to the next element
     * @link https://php.net/manual/en/appenditerator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Gets an inner iterator
     * @link https://php.net/manual/en/appenditerator.getinneriterator.php
     * @return Iterator the current inner Iterator.
     */
    public function getInnerIterator(): Iterator {}

    /**
     * Gets an index of iterators
     * @link https://php.net/manual/en/appenditerator.getiteratorindex.php
     * @return int|null The index of iterators.
     */
    #[TentativeType]
    public function getIteratorIndex(): ?int {}

    /**
     * The getArrayIterator method
     * @link https://php.net/manual/en/appenditerator.getarrayiterator.php
     * @return ArrayIterator containing the appended iterators.
     */
    #[TentativeType]
    public function getArrayIterator(): ArrayIterator {}
}

/**
 * The <b>InfiniteIterator</b> allows one to
 * infinitely iterate over an iterator without having to manually
 * rewind the iterator upon reaching its end.
 * @link https://php.net/manual/en/class.infiniteiterator.php
 */
class InfiniteIterator extends IteratorIterator
{
    /**
     * Constructs an InfiniteIterator
     * @link https://php.net/manual/en/infiniteiterator.construct.php
     * @param Iterator $iterator
     */
    public function __construct(Iterator $iterator) {}

    /**
     * Moves the inner Iterator forward or rewinds it
     * @link https://php.net/manual/en/infiniteiterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}
}

/**
 * This iterator can be used to filter another iterator based on a regular expression.
 * @link https://php.net/manual/en/class.regexiterator.php
 */
class RegexIterator extends FilterIterator
{
    /**
     * Return all matches for the current entry @see preg_match_all
     */
    public const ALL_MATCHES = 2;

    /**
     * Return the first match for the current entry @see preg_match
     */
    public const GET_MATCH = 1;

    /**
     * Only execute match (filter) for the current entry @see preg_match
     */
    public const MATCH = 0;

    /**
     * Replace the current entry (Not fully implemented yet) @see preg_replace
     */
    public const REPLACE = 4;

    /**
     * Returns the split values for the current entry @see preg_split
     */
    public const SPLIT = 3;

    /**
     * Special flag: Match the entry key instead of the entry value.
     */
    public const USE_KEY = 1;
    public const INVERT_MATCH = 2;

    #[LanguageLevelTypeAware(['8.1' => 'string|null'], default: '')]
    public $replacement;

    /**
     * Create a new RegexIterator
     * @link https://php.net/manual/en/regexiterator.construct.php
     * @param Iterator $iterator The iterator to apply this regex filter to.
     * @param string $pattern The regular expression to match.
     * @param int $mode [optional] Operation mode, see RegexIterator::setMode() for a list of modes.
     * @param int $flags [optional] Special flags, see RegexIterator::setFlags() for a list of available flags.
     * @param int $pregFlags [optional] The regular expression flags. These flags depend on the operation mode parameter
     */
    public function __construct(
        Iterator $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $pattern,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode = self::MATCH,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $pregFlags = 0
    ) {}

    /**
     * Get accept status
     * @link https://php.net/manual/en/regexiterator.accept.php
     * @return bool true if a match, false otherwise.
     */
    #[TentativeType]
    public function accept(): bool {}

    /**
     * Returns operation mode.
     * @link https://php.net/manual/en/regexiterator.getmode.php
     * @return int the operation mode.
     */
    #[TentativeType]
    public function getMode(): int {}

    /**
     * Sets the operation mode.
     * @link https://php.net/manual/en/regexiterator.setmode.php
     * @param int $mode <p>
     * The operation mode.
     * </p>
     * <p>
     * The available modes are listed below. The actual
     * meanings of these modes are described in the
     * predefined constants.
     * <table>
     * <b>RegexIterator</b> modes
     * <tr valign="top">
     * <td>value</td>
     * <td>constant</td>
     * </tr>
     * <tr valign="top">
     * <td>0</td>
     * <td>
     * RegexIterator::MATCH
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>1</td>
     * <td>
     * RegexIterator::GET_MATCH
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>2</td>
     * <td>
     * RegexIterator::ALL_MATCHES
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>3</td>
     * <td>
     * RegexIterator::SPLIT
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>4</td>
     * <td>
     * RegexIterator::REPLACE
     * </td>
     * </tr>
     * </table>
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setMode(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode): void {}

    /**
     * Get flags
     * @link https://php.net/manual/en/regexiterator.getflags.php
     * @return int the set flags.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Sets the flags.
     * @link https://php.net/manual/en/regexiterator.setflags.php
     * @param int $flags <p>
     * The flags to set, a bitmask of class constants.
     * </p>
     * <p>
     * The available flags are listed below. The actual
     * meanings of these flags are described in the
     * predefined constants.
     * <table>
     * <b>RegexIterator</b> flags
     * <tr valign="top">
     * <td>value</td>
     * <td>constant</td>
     * </tr>
     * <tr valign="top">
     * <td>1</td>
     * <td>
     * RegexIterator::USE_KEY
     * </td>
     * </tr>
     * </table>
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Returns current regular expression
     * @link https://secure.php.net/manual/en/regexiterator.getregex.php
     * @return string
     * @since 5.4
     */
    #[TentativeType]
    public function getRegex(): string {}

    /**
     * Returns the regular expression flags.
     * @link https://php.net/manual/en/regexiterator.getpregflags.php
     * @return int a bitmask of the regular expression flags.
     */
    #[TentativeType]
    public function getPregFlags(): int {}

    /**
     * Sets the regular expression flags.
     * @link https://php.net/manual/en/regexiterator.setpregflags.php
     * @param int $pregFlags <p>
     * The regular expression flags. See <b>RegexIterator::__construct</b>
     * for an overview of available flags.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setPregFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $pregFlags): void {}
}

/**
 * This recursive iterator can filter another recursive iterator via a regular expression.
 * @link https://php.net/manual/en/class.recursiveregexiterator.php
 */
class RecursiveRegexIterator extends RegexIterator implements RecursiveIterator
{
    /**
     * Creates a new RecursiveRegexIterator.
     * @link https://php.net/manual/en/recursiveregexiterator.construct.php
     * @param RecursiveIterator $iterator The iterator to apply this regex filter to.
     * @param string $pattern The regular expression to match.
     * @param int $mode [optional] Operation mode, see RegexIterator::setMode() for a list of modes.
     * @param int $flags [optional] Special flags, see RegexIterator::setFlags() for a list of available flags.
     * @param int $pregFlags [optional] The regular expression flags. These flags depend on the operation mode parameter
     */
    public function __construct(
        RecursiveIterator $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $pattern,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode = self::MATCH,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $pregFlags = 0
    ) {}

    /**
     * Returns whether an iterator can be obtained for the current entry.
     * @link https://php.net/manual/en/recursiveregexiterator.haschildren.php
     * @return bool true if an iterator can be obtained for the current entry, otherwise returns false.
     */
    #[TentativeType]
    public function hasChildren(): bool {}

    /**
     * Returns an iterator for the current entry.
     * @link https://php.net/manual/en/recursiveregexiterator.getchildren.php
     * @return RecursiveRegexIterator An iterator for the current entry, if it can be iterated over by the inner iterator.
     */
    #[TentativeType]
    public function getChildren(): RecursiveRegexIterator {}
}

/**
 * Allows iterating over a <b>RecursiveIterator</b> to generate an ASCII graphic tree.
 * @link https://php.net/manual/en/class.recursivetreeiterator.php
 */
class RecursiveTreeIterator extends RecursiveIteratorIterator
{
    public const BYPASS_CURRENT = 4;
    public const BYPASS_KEY = 8;
    public const PREFIX_LEFT = 0;
    public const PREFIX_MID_HAS_NEXT = 1;
    public const PREFIX_MID_LAST = 2;
    public const PREFIX_END_HAS_NEXT = 3;
    public const PREFIX_END_LAST = 4;
    public const PREFIX_RIGHT = 5;

    /**
     * Construct a RecursiveTreeIterator
     * @link https://php.net/manual/en/recursivetreeiterator.construct.php
     * @param RecursiveIterator|IteratorAggregate $iterator
     * @param int $flags [optional] Flags to control the behavior of the RecursiveTreeIterator object.
     * @param int $cachingIteratorFlags [optional] Flags to affect the behavior of the {@see RecursiveCachingIterator} used internally.
     * @param int $mode [optional] Flags to affect the behavior of the {@see RecursiveIteratorIterator} used internally.
     */
    public function __construct(
        $iterator,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = self::BYPASS_KEY,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $cachingIteratorFlags = CachingIterator::CATCH_GET_CHILD,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $mode = self::SELF_FIRST
    ) {}

    /**
     * Rewind iterator
     * @link https://php.net/manual/en/recursivetreeiterator.rewind.php
     * @return void
     */
    public function rewind(): void {}

    /**
     * Check validity
     * @link https://php.net/manual/en/recursivetreeiterator.valid.php
     * @return bool true if the current position is valid, otherwise false
     */
    public function valid(): bool {}

    /**
     * Get the key of the current element
     * @link https://php.net/manual/en/recursivetreeiterator.key.php
     * @return string the current key prefixed and postfixed.
     */
    #[TentativeType]
    public function key(): mixed {}

    /**
     * Get current element
     * @link https://php.net/manual/en/recursivetreeiterator.current.php
     * @return string the current element prefixed and postfixed.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Move to next element
     * @link https://php.net/manual/en/recursivetreeiterator.next.php
     * @return void
     */
    public function next(): void {}

    /**
     * Begin iteration
     * @link https://php.net/manual/en/recursivetreeiterator.beginiteration.php
     * @return RecursiveIterator A <b>RecursiveIterator</b>.
     */
    public function beginIteration() {}

    /**
     * End iteration
     * @link https://php.net/manual/en/recursivetreeiterator.enditeration.php
     * @return void
     */
    public function endIteration() {}

    /**
     * Has children
     * @link https://php.net/manual/en/recursivetreeiterator.callhaschildren.php
     * @return bool true if there are children, otherwise false
     */
    public function callHasChildren() {}

    /**
     * Get children
     * @link https://php.net/manual/en/recursivetreeiterator.callgetchildren.php
     * @return RecursiveIterator A <b>RecursiveIterator</b>.
     */
    public function callGetChildren() {}

    /**
     * Begin children
     * @link https://php.net/manual/en/recursivetreeiterator.beginchildren.php
     * @return void
     */
    public function beginChildren() {}

    /**
     * End children
     * @link https://php.net/manual/en/recursivetreeiterator.endchildren.php
     * @return void
     */
    public function endChildren() {}

    /**
     * Next element
     * @link https://php.net/manual/en/recursivetreeiterator.nextelement.php
     * @return void
     */
    public function nextElement() {}

    /**
     * Get the prefix
     * @link https://php.net/manual/en/recursivetreeiterator.getprefix.php
     * @return string the string to place in front of current element
     */
    #[TentativeType]
    public function getPrefix(): string {}

    /**
     * @param string $postfix
     */
    #[TentativeType]
    public function setPostfix(#[PhpStormStubsElementAvailable(from: '7.3')] string $postfix): void {}

    /**
     * Set a part of the prefix
     * @link https://php.net/manual/en/recursivetreeiterator.setprefixpart.php
     * @param int $part <p>
     * One of the RecursiveTreeIterator::PREFIX_* constants.
     * </p>
     * @param string $value <p>
     * The value to assign to the part of the prefix specified in <i>part</i>.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setPrefixPart(
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $part,
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $value
    ): void {}

    /**
     * Get current entry
     * @link https://php.net/manual/en/recursivetreeiterator.getentry.php
     * @return string the part of the tree built for the current element.
     */
    #[TentativeType]
    public function getEntry(): string {}

    /**
     * Get the postfix
     * @link https://php.net/manual/en/recursivetreeiterator.getpostfix.php
     * @return string to place after the current element.
     */
    #[TentativeType]
    public function getPostfix(): string {}
}

/**
 * This class allows objects to work as arrays.
 * @link https://php.net/manual/en/class.arrayobject.php
 */
class ArrayObject implements IteratorAggregate, ArrayAccess, Serializable, Countable
{
    /**
     * Properties of the object have their normal functionality when accessed as list (var_dump, foreach, etc.).
     */
    public const STD_PROP_LIST = 1;

    /**
     * Entries can be accessed as properties (read and write).
     */
    public const ARRAY_AS_PROPS = 2;

    /**
     * Construct a new array object
     * @link https://php.net/manual/en/arrayobject.construct.php
     * @param array|object $array The input parameter accepts an array or an Object.
     * @param int $flags Flags to control the behaviour of the ArrayObject object.
     * @param string $iteratorClass Specify the class that will be used for iteration of the ArrayObject object. ArrayIterator is the default class used.
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'object|array'], default: '')] $array = [],
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $iteratorClass = "ArrayIterator"
    ) {}

    /**
     * Returns whether the requested index exists
     * @link https://php.net/manual/en/arrayobject.offsetexists.php
     * @param int|string $key <p>
     * The index being checked.
     * </p>
     * @return bool true if the requested index exists, otherwise false
     */
    #[TentativeType]
    public function offsetExists(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): bool {}

    /**
     * Returns the value at the specified index
     * @link https://php.net/manual/en/arrayobject.offsetget.php
     * @param int|string $key <p>
     * The index with the value.
     * </p>
     * @return mixed|false The value at the specified index or false.
     */
    #[TentativeType]
    public function offsetGet(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): mixed {}

    /**
     * Sets the value at the specified index to newval
     * @link https://php.net/manual/en/arrayobject.offsetset.php
     * @param int|string $key <p>
     * The index being set.
     * </p>
     * @param mixed $value <p>
     * The new value for the <i>index</i>.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value
    ): void {}

    /**
     * Unsets the value at the specified index
     * @link https://php.net/manual/en/arrayobject.offsetunset.php
     * @param int|string $key <p>
     * The index being unset.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): void {}

    /**
     * Appends the value
     * @link https://php.net/manual/en/arrayobject.append.php
     * @param mixed $value <p>
     * The value being appended.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function append(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Creates a copy of the ArrayObject.
     * @link https://php.net/manual/en/arrayobject.getarraycopy.php
     * @return array a copy of the array. When the <b>ArrayObject</b> refers to an object
     * an array of the public properties of that object will be returned.
     */
    #[TentativeType]
    public function getArrayCopy(): array {}

    /**
     * Get the number of public properties in the ArrayObject
     * When the <b>ArrayObject</b> is constructed from an array all properties are public.
     * @link https://php.net/manual/en/arrayobject.count.php
     * @return int The number of public properties in the ArrayObject.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Gets the behavior flags.
     * @link https://php.net/manual/en/arrayobject.getflags.php
     * @return int the behavior flags of the ArrayObject.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Sets the behavior flags.
     * @link https://php.net/manual/en/arrayobject.setflags.php
     * @param int $flags <p>
     * The new ArrayObject behavior.
     * It takes on either a bitmask, or named constants. Using named
     * constants is strongly encouraged to ensure compatibility for future
     * versions.
     * </p>
     * <p>
     * The available behavior flags are listed below. The actual
     * meanings of these flags are described in the
     * predefined constants.
     * <table>
     * ArrayObject behavior flags
     * <tr valign="top">
     * <td>value</td>
     * <td>constant</td>
     * </tr>
     * <tr valign="top">
     * <td>1</td>
     * <td>
     * ArrayObject::STD_PROP_LIST
     * </td>
     * </tr>
     * <tr valign="top">
     * <td>2</td>
     * <td>
     * ArrayObject::ARRAY_AS_PROPS
     * </td>
     * </tr>
     * </table>
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Sort the entries by value
     * @link https://php.net/manual/en/arrayobject.asort.php
     * @param int $flags [optional]
     * @return bool
     */
    #[TentativeType]
    public function asort(#[PhpStormStubsElementAvailable(from: '8.0')] int $flags = SORT_REGULAR): bool {}

    /**
     * Sort the entries by key
     * @link https://php.net/manual/en/arrayobject.ksort.php
     * @param int $flags [optional]
     * @return bool
     */
    #[TentativeType]
    public function ksort(#[PhpStormStubsElementAvailable(from: '8.0')] int $flags = SORT_REGULAR): bool {}

    /**
     * Sort the entries with a user-defined comparison function and maintain key association
     * @link https://php.net/manual/en/arrayobject.uasort.php
     * @param callable $callback <p>
     * Function <i>cmp_function</i> should accept two
     * parameters which will be filled by pairs of entries.
     * The comparison function must return an integer less than, equal
     * to, or greater than zero if the first argument is considered to
     * be respectively less than, equal to, or greater than the
     * second.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function uasort(#[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback): bool {}

    /**
     * Sort the entries by keys using a user-defined comparison function
     * @link https://php.net/manual/en/arrayobject.uksort.php
     * @param callable $callback <p>
     * The callback comparison function.
     * </p>
     * <p>
     * Function <i>cmp_function</i> should accept two
     * parameters which will be filled by pairs of entry keys.
     * The comparison function must return an integer less than, equal
     * to, or greater than zero if the first argument is considered to
     * be respectively less than, equal to, or greater than the
     * second.
     * </p>
     * @return bool
     */
    #[TentativeType]
    public function uksort(#[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback): bool {}

    /**
     * Sort entries using a "natural order" algorithm
     * @link https://php.net/manual/en/arrayobject.natsort.php
     * @return bool
     */
    #[TentativeType]
    public function natsort(): bool {}

    /**
     * Sort an array using a case insensitive "natural order" algorithm
     * @link https://php.net/manual/en/arrayobject.natcasesort.php
     * @return bool
     */
    #[TentativeType]
    public function natcasesort(): bool {}

    /**
     * Unserialize an ArrayObject
     * @link https://php.net/manual/en/arrayobject.unserialize.php
     * @param string $data <p>
     * The serialized <b>ArrayObject</b>.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function unserialize(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): void {}

    /**
     * Serialize an ArrayObject
     * @link https://php.net/manual/en/arrayobject.serialize.php
     * @return string The serialized representation of the <b>ArrayObject</b>.
     */
    #[TentativeType]
    public function serialize(): string {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __serialize(): array {}

    /**
     * @param array $data
     * @since 7.4
     */
    #[TentativeType]
    public function __unserialize(array $data): void {}

    /**
     * Create a new iterator from an ArrayObject instance
     * @link https://php.net/manual/en/arrayobject.getiterator.php
     * @return ArrayIterator An iterator from an <b>ArrayObject</b>.
     */
    #[TentativeType]
    public function getIterator(): Iterator {}

    /**
     * Exchange the array for another one.
     * @link https://php.net/manual/en/arrayobject.exchangearray.php
     * @param mixed $array <p>
     * The new array or object to exchange with the current array.
     * </p>
     * @return array the old array.
     */
    #[TentativeType]
    public function exchangeArray(#[LanguageLevelTypeAware(['8.0' => 'object|array'], default: '')] $array): array {}

    /**
     * Sets the iterator classname for the ArrayObject.
     * @link https://php.net/manual/en/arrayobject.setiteratorclass.php
     * @param string $iteratorClass <p>
     * The classname of the array iterator to use when iterating over this object.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setIteratorClass(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $iteratorClass): void {}

    /**
     * Gets the iterator classname for the ArrayObject.
     * @link https://php.net/manual/en/arrayobject.getiteratorclass.php
     * @return string the iterator class name that is used to iterate over this object.
     */
    #[TentativeType]
    public function getIteratorClass(): string {}
}

/**
 * This iterator allows to unset and modify values and keys while iterating
 * over Arrays and Objects.
 * @link https://php.net/manual/en/class.arrayiterator.php
 */
class ArrayIterator implements SeekableIterator, ArrayAccess, Serializable, Countable
{
    public const STD_PROP_LIST = 1;
    public const ARRAY_AS_PROPS = 2;

    /**
     * Construct an ArrayIterator
     * @link https://php.net/manual/en/arrayiterator.construct.php
     * @param array $array The array or object to be iterated on.
     * @param int $flags Flags to control the behaviour of the ArrayObject object.
     * @see ArrayObject::setFlags()
     */
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'object|array'], default: '')] $array = [],
        #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags = 0,
        #[PhpStormStubsElementAvailable(from: '7.0', to: '7.1')] $iterator_class = null
    ) {}

    /**
     * Check if offset exists
     * @link https://php.net/manual/en/arrayiterator.offsetexists.php
     * @param string $key <p>
     * The offset being checked.
     * </p>
     * @return bool true if the offset exists, otherwise false
     */
    #[TentativeType]
    public function offsetExists(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): bool {}

    /**
     * Get value for an offset
     * @link https://php.net/manual/en/arrayiterator.offsetget.php
     * @param string $key <p>
     * The offset to get the value from.
     * </p>
     * @return mixed The value at offset <i>index</i>.
     */
    #[TentativeType]
    public function offsetGet(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): mixed {}

    /**
     * Set value for an offset
     * @link https://php.net/manual/en/arrayiterator.offsetset.php
     * @param string $key <p>
     * The index to set for.
     * </p>
     * @param string $value <p>
     * The new value to store at the index.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value
    ): void {}

    /**
     * Unset value for an offset
     * @link https://php.net/manual/en/arrayiterator.offsetunset.php
     * @param string $key <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $key): void {}

    /**
     * Append an element
     * @link https://php.net/manual/en/arrayiterator.append.php
     * @param mixed $value <p>
     * The value to append.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function append(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value): void {}

    /**
     * Get array copy
     * @link https://php.net/manual/en/arrayiterator.getarraycopy.php
     * @return array A copy of the array, or array of public properties
     * if ArrayIterator refers to an object.
     */
    #[TentativeType]
    public function getArrayCopy(): array {}

    /**
     * Count elements
     * @link https://php.net/manual/en/arrayiterator.count.php
     * @return int<0,max> The number of elements or public properties in the associated
     * array or object, respectively.
     */
    #[TentativeType]
    public function count(): int {}

    /**
     * Get flags
     * @link https://php.net/manual/en/arrayiterator.getflags.php
     * @return int The current flags.
     */
    #[TentativeType]
    public function getFlags(): int {}

    /**
     * Set behaviour flags
     * @link https://php.net/manual/en/arrayiterator.setflags.php
     * @param string $flags <p>
     * A bitmask as follows:
     * 0 = Properties of the object have their normal functionality
     * when accessed as list (var_dump, foreach, etc.).
     * 1 = Array indices can be accessed as properties in read/write.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function setFlags(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $flags): void {}

    /**
     * Sort array by values
     * @link https://php.net/manual/en/arrayiterator.asort.php
     * @param int $flags [optional]
     * @return bool
     */
    #[TentativeType]
    public function asort(#[PhpStormStubsElementAvailable(from: '8.0')] int $flags = SORT_REGULAR): bool {}

    /**
     * Sort array by keys
     * @link https://php.net/manual/en/arrayiterator.ksort.php
     * @param int $flags [optional]
     * @return bool
     */
    #[TentativeType]
    public function ksort(#[PhpStormStubsElementAvailable(from: '8.0')] int $flags = SORT_REGULAR): bool {}

    /**
     * User defined sort
     * @link https://php.net/manual/en/arrayiterator.uasort.php
     * @param callable $callback <p>
     * The compare function used for the sort.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function uasort(#[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback): bool {}

    /**
     * User defined sort
     * @link https://php.net/manual/en/arrayiterator.uksort.php
     * @param callable $callback <p>
     * The compare function used for the sort.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function uksort(#[LanguageLevelTypeAware(['8.0' => 'callable'], default: '')] $callback): bool {}

    /**
     * Sort an array naturally
     * @link https://php.net/manual/en/arrayiterator.natsort.php
     * @return bool
     */
    #[TentativeType]
    public function natsort(): bool {}

    /**
     * Sort an array naturally, case insensitive
     * @link https://php.net/manual/en/arrayiterator.natcasesort.php
     * @return bool
     */
    #[TentativeType]
    public function natcasesort(): bool {}

    /**
     * Unserialize
     * @link https://php.net/manual/en/arrayiterator.unserialize.php
     * @param string $data <p>
     * The serialized ArrayIterator object to be unserialized.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function unserialize(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data): void {}

    /**
     * Serialize
     * @link https://php.net/manual/en/arrayiterator.serialize.php
     * @return string The serialized <b>ArrayIterator</b>.
     */
    #[TentativeType]
    public function serialize(): string {}

    /**
     * Rewind array back to the start
     * @link https://php.net/manual/en/arrayiterator.rewind.php
     * @return void
     */
    #[TentativeType]
    public function rewind(): void {}

    /**
     * Return current array entry
     * @link https://php.net/manual/en/arrayiterator.current.php
     * @return mixed The current array entry.
     */
    #[TentativeType]
    public function current(): mixed {}

    /**
     * Return current array key
     * @link https://php.net/manual/en/arrayiterator.key.php
     * @return string|int|null The key of the current element.
     */
    #[TentativeType]
    public function key(): string|int|null {}

    /**
     * Move to next entry
     * @link https://php.net/manual/en/arrayiterator.next.php
     * @return void
     */
    #[TentativeType]
    public function next(): void {}

    /**
     * Check whether array contains more entries
     * @link https://php.net/manual/en/arrayiterator.valid.php
     * @return bool
     */
    #[TentativeType]
    public function valid(): bool {}

    /**
     * Seek to position
     * @link https://php.net/manual/en/arrayiterator.seek.php
     * @param int $offset <p>
     * The position to seek to.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function seek(#[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $offset): void {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __debugInfo(): array {}

    /**
     * @return array
     * @since 7.4
     */
    #[TentativeType]
    public function __serialize(): array {}

    /**
     * @param array $data
     * @since 7.4
     */
    #[TentativeType]
    public function __unserialize(array $data): void {}
}

/**
 * This iterator allows to unset and modify values and keys while iterating over Arrays and Objects
 * in the same way as the ArrayIterator. Additionally it is possible to iterate
 * over the current iterator entry.
 * @link https://php.net/manual/en/class.recursivearrayiterator.php
 */
class RecursiveArrayIterator extends ArrayIterator implements RecursiveIterator
{
    public const CHILD_ARRAYS_ONLY = 4;

    /**
     * Returns whether current entry is an array or an object.
     * @link https://php.net/manual/en/recursivearrayiterator.haschildren.php
     * @return bool true if the current entry is an array or an object,
     * otherwise false is returned.
     */
    #[TentativeType]
    public function hasChildren(): bool {}

    /**
     * Returns an iterator for the current entry if it is an array or an object.
     * @link https://php.net/manual/en/recursivearrayiterator.getchildren.php
     * @return RecursiveArrayIterator|null An iterator for the current entry, if it is an array or object.
     */
    #[TentativeType]
    public function getChildren(): ?RecursiveArrayIterator {}
}
