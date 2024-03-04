<?php

// Start of Core v.5.3.6-13ubuntu3.2
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Internal\TentativeType;
use JetBrains\PhpStorm\Pure;

/**
 * Created by typecasting to object.
 * @link https://php.net/manual/en/reserved.classes.php
 */
class stdClass {}

/**
 * @link https://wiki.php.net/rfc/iterable
 */
interface iterable {}

/**
 * Interface to detect if a class is traversable using foreach.
 * Abstract base interface that cannot be implemented alone.
 * Instead it must be implemented by either {@see IteratorAggregate} or {@see Iterator}.
 *
 * @link https://php.net/manual/en/class.traversable.php
 * @template TKey
 * @template-covariant TValue
 *
 * @template-implements iterable<TKey, TValue>
 */
interface Traversable extends iterable {}

/**
 * Interface to create an external Iterator.
 * @link https://php.net/manual/en/class.iteratoraggregate.php
 * @template TKey
 * @template-covariant TValue
 * @template-implements Traversable<TKey, TValue>
 */
interface IteratorAggregate extends Traversable
{
    /**
     * Retrieve an external iterator
     * @link https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable<TKey, TValue>|TValue[] An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @throws Exception on failure.
     */
    #[TentativeType]
    public function getIterator(): Traversable;
}

/**
 * Interface for external iterators or objects that can be iterated
 * themselves internally.
 * @link https://php.net/manual/en/class.iterator.php
 * @template TKey
 * @template-covariant TValue
 * @template-implements Traversable<TKey, TValue>
 */
interface Iterator extends Traversable
{
    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return TValue Can return any type.
     */
    #[TentativeType]
    public function current(): mixed;

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    #[TentativeType]
    public function next(): void;

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return TKey|null TKey on success, or null on failure.
     */
    #[TentativeType]
    public function key(): mixed;

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return bool The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    #[TentativeType]
    public function valid(): bool;

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    #[TentativeType]
    public function rewind(): void;
}

/**
 * Interface to provide accessing objects as arrays.
 * @link https://php.net/manual/en/class.arrayaccess.php
 * @template TKey
 * @template TValue
 */
interface ArrayAccess
{
    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    #[TentativeType]
    public function offsetExists(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $offset): bool;

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return TValue Can return all value types.
     */
    #[TentativeType]
    public function offsetGet(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $offset): mixed;

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param TKey $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param TValue $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetSet(
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $offset,
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $value
    ): void;

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param TKey $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    #[TentativeType]
    public function offsetUnset(#[LanguageLevelTypeAware(['8.0' => 'mixed'], default: '')] $offset): void;
}

/**
 * Interface for customized serializing.<br>
 * As of PHP 8.1.0, a class which implements Serializable without also implementing `__serialize()` and `__unserialize()`
 * will generate a deprecation warning.
 * @link https://php.net/manual/en/class.serializable.php
 */
interface Serializable
{
    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize();

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $data The string representation of the object.
     * @return void
     */
    public function unserialize(#[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $data);
}

/**
 * Throwable is the base interface for any object that can be thrown via a throw statement in PHP 7,
 * including Error and Exception.
 * @link https://php.net/manual/en/class.throwable.php
 * @since 7.0
 */
interface Throwable extends Stringable
{
    /**
     * Gets the message
     * @link https://php.net/manual/en/throwable.getmessage.php
     * @return string
     * @since 7.0
     */
    public function getMessage(): string;

    /**
     * Gets the exception code
     * @link https://php.net/manual/en/throwable.getcode.php
     * @return int <p>
     * Returns the exception code as integer in
     * {@see Exception} but possibly as other type in
     * {@see Exception} descendants (for example as
     * string in {@see PDOException}).
     * </p>
     * @since 7.0
     */
    public function getCode();

    /**
     * Gets the file in which the exception occurred
     * @link https://php.net/manual/en/throwable.getfile.php
     * @return string Returns the name of the file from which the object was thrown.
     * @since 7.0
     */
    public function getFile(): string;

    /**
     * Gets the line on which the object was instantiated
     * @link https://php.net/manual/en/throwable.getline.php
     * @return int Returns the line number where the thrown object was instantiated.
     * @since 7.0
     */
    public function getLine(): int;

    /**
     * Gets the stack trace
     * @link https://php.net/manual/en/throwable.gettrace.php
     * @return array <p>
     * Returns the stack trace as an array in the same format as
     * {@see debug_backtrace()}.
     * </p>
     * @since 7.0
     */
    public function getTrace(): array;

    /**
     * Gets the stack trace as a string
     * @link https://php.net/manual/en/throwable.gettraceasstring.php
     * @return string Returns the stack trace as a string.
     * @since 7.0
     */
    public function getTraceAsString(): string;

    /**
     * Returns the previous Throwable
     * @link https://php.net/manual/en/throwable.getprevious.php
     * @return null|Throwable Returns the previous {@see Throwable} if available, or <b>NULL</b> otherwise.
     * @since 7.0
     */
    #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: '')]
    public function getPrevious();

    /**
     * Gets a string representation of the thrown object
     * @link https://php.net/manual/en/throwable.tostring.php
     * @return string <p>Returns the string representation of the thrown object.</p>
     * @since 7.0
     */
    public function __toString();
}

/**
 * Exception is the base class for
 * all Exceptions.
 * @link https://php.net/manual/en/class.exception.php
 */
class Exception implements Throwable
{
    /** The error message */
    protected $message;

    /** The error code */
    protected $code;

    /** The filename where the error happened  */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    protected $file;

    /** The line where the error happened */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    protected $line;

    /**
     * Clone the exception
     * Tries to clone the Exception, which results in Fatal error.
     * @link https://php.net/manual/en/exception.clone.php
     * @return void
     */
    #[PhpStormStubsElementAvailable(from: "5.4", to: "8.0")]
    final private function __clone(): void {}

    /**
     * Clone the exception
     * Tries to clone the Exception, which results in Fatal error.
     * @link https://php.net/manual/en/exception.clone.php
     * @return void
     */
    #[PhpStormStubsElementAvailable("8.1")]
    private function __clone(): void {}

    /**
     * Construct the exception. Note: The message is NOT binary safe.
     * @link https://php.net/manual/en/exception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    #[Pure]
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $message = "",
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $code = 0,
        #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: 'Throwable')] $previous = null
    ) {}

    /**
     * Gets the Exception message
     * @link https://php.net/manual/en/exception.getmessage.php
     * @return string the Exception message as a string.
     */
    #[Pure]
    final public function getMessage(): string {}

    /**
     * Gets the Exception code
     * @link https://php.net/manual/en/exception.getcode.php
     * @return mixed|int the exception code as integer in
     * <b>Exception</b> but possibly as other type in
     * <b>Exception</b> descendants (for example as
     * string in <b>PDOException</b>).
     */
    #[Pure]
    final public function getCode() {}

    /**
     * Gets the file in which the exception occurred
     * @link https://php.net/manual/en/exception.getfile.php
     * @return string the filename in which the exception was created.
     */
    #[Pure]
    final public function getFile(): string {}

    /**
     * Gets the line in which the exception occurred
     * @link https://php.net/manual/en/exception.getline.php
     * @return int the line number where the exception was created.
     */
    #[Pure]
    final public function getLine(): int {}

    /**
     * Gets the stack trace
     * @link https://php.net/manual/en/exception.gettrace.php
     * @return array the Exception stack trace as an array.
     */
    #[Pure]
    final public function getTrace(): array {}

    /**
     * Returns previous Exception
     * @link https://php.net/manual/en/exception.getprevious.php
     * @return null|Throwable Returns the previous {@see Throwable} if available, or <b>NULL</b> otherwise.
     * or null otherwise.
     */
    #[Pure]
    final public function getPrevious(): ?Throwable {}

    /**
     * Gets the stack trace as a string
     * @link https://php.net/manual/en/exception.gettraceasstring.php
     * @return string the Exception stack trace as a string.
     */
    #[Pure]
    final public function getTraceAsString(): string {}

    /**
     * String representation of the exception
     * @link https://php.net/manual/en/exception.tostring.php
     * @return string the string representation of the exception.
     */
    #[TentativeType]
    public function __toString(): string {}

    #[TentativeType]
    public function __wakeup(): void {}
}

/**
 * Error is the base class for all internal PHP error exceptions.
 * @link https://php.net/manual/en/class.error.php
 * @since 7.0
 */
class Error implements Throwable
{
    /** The error message */
    protected $message;

    /** The error code */
    protected $code;

    /** The filename where the error happened  */
    #[LanguageLevelTypeAware(['8.1' => 'string'], default: '')]
    protected $file;

    /** The line where the error happened */
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    protected $line;

    /**
     * Construct the error object.
     * @link https://php.net/manual/en/error.construct.php
     * @param string $message [optional] The Error message to throw.
     * @param int $code [optional] The Error code.
     * @param null|Throwable $previous [optional] The previous throwable used for the exception chaining.
     */
    #[Pure]
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $message = "",
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $code = 0,
        #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: 'Throwable')] $previous = null
    ) {}

    /***
     * Gets the message
     * @link https://php.net/manual/en/throwable.getmessage.php
     * @return string
     * @since 7.0
     */
    final public function getMessage(): string {}

    /**
     * Gets the exception code
     * @link https://php.net/manual/en/throwable.getcode.php
     * @return int <p>
     * Returns the exception code as integer in
     * {@see Exception} but possibly as other type in
     * {@see Exception} descendants (for example as
     * string in {@see PDOException}).
     * </p>
     * @since 7.0
     */
    final public function getCode() {}

    /**
     * Gets the file in which the exception occurred
     * @link https://php.net/manual/en/throwable.getfile.php
     * @return string Returns the name of the file from which the object was thrown.
     * @since 7.0
     */
    final public function getFile(): string {}

    /**
     * Gets the line on which the object was instantiated
     * @link https://php.net/manual/en/throwable.getline.php
     * @return int Returns the line number where the thrown object was instantiated.
     * @since 7.0
     */
    final public function getLine(): int {}

    /**
     * Gets the stack trace
     * @link https://php.net/manual/en/throwable.gettrace.php
     * @return array <p>
     * Returns the stack trace as an array in the same format as
     * {@see debug_backtrace()}.
     * </p>
     * @since 7.0
     */
    final public function getTrace(): array {}

    /**
     * Gets the stack trace as a string
     * @link https://php.net/manual/en/throwable.gettraceasstring.php
     * @return string Returns the stack trace as a string.
     * @since 7.0
     */
    final public function getTraceAsString(): string {}

    /**
     * Returns the previous Throwable
     * @link https://php.net/manual/en/throwable.getprevious.php
     * @return null|Throwable Returns the previous {@see Throwable} if available, or <b>NULL</b> otherwise.
     * @since 7.0
     */
    final public function getPrevious(): ?Throwable {}

    /**
     * Gets a string representation of the thrown object
     * @link https://php.net/manual/en/throwable.tostring.php
     * @return string <p>Returns the string representation of the thrown object.</p>
     * @since 7.0
     */
    public function __toString(): string {}

    /**
     * Clone the error
     * Error can not be clone, so this method results in fatal error.
     * @return void
     * @link https://php.net/manual/en/error.clone.php
     */
    #[PhpStormStubsElementAvailable(from: "7.0", to: "8.0")]
    final private function __clone(): void {}

    /**
     * Clone the error
     * Error can not be clone, so this method results in fatal error.
     * @return void
     * @link https://php.net/manual/en/error.clone.php
     */
    #[PhpStormStubsElementAvailable('8.1')]
    private function __clone(): void {}

    #[TentativeType]
    public function __wakeup(): void {}
}

class ValueError extends Error {}

/**
 * There are three scenarios where a TypeError may be thrown.
 * The first is where the argument type being passed to a function does not match its corresponding declared
 * parameter type. The second is where a value being returned from a function does not match the declared function return type. The third is where an
 * invalid number of arguments are passed to a built-in PHP function (strict mode only).
 * @link https://php.net/manual/en/class.typeerror.php
 * @since 7.0
 */
class TypeError extends Error {}

/**
 * ParseError is thrown when an error occurs while parsing PHP code, such as when {@see eval()} is called.
 * @link https://php.net/manual/en/class.parseerror.php
 * @since 7.0
 */
class ParseError extends CompileError {}

/**
 * ArgumentCountError is thrown when too few arguments are passed to a user
 * defined routine.
 *
 * @since 7.1
 * @see https://php.net/migration71.incompatible#migration71.incompatible.too-few-arguments-exception
 */
class ArgumentCountError extends TypeError {}

/**
 * ArithmeticError is thrown when an error occurs while performing mathematical operations.
 * In PHP 7.0, these errors include attempting to perform a bitshift by a negative amount,
 * and any call to {@see intdiv()} that would result in a value outside the possible bounds of an integer.
 * @link https://php.net/manual/en/class.arithmeticerror.php
 * @since 7.0
 */
class ArithmeticError extends Error {}

/**
 * Class CompileError
 * @link https://secure.php.net/manual/en/class.compileerror.php
 * @since 7.3
 */
class CompileError extends Error {}

/**
 * DivisionByZeroError is thrown when an attempt is made to divide a number by zero.
 * @link https://php.net/manual/en/class.divisionbyzeroerror.php
 * @since 7.0
 */
class DivisionByZeroError extends ArithmeticError {}

/**
 * @since 8.0
 */
class UnhandledMatchError extends Error {}

/**
 * An Error Exception.
 * @link https://php.net/manual/en/class.errorexception.php
 */
class ErrorException extends Exception
{
    #[LanguageLevelTypeAware(['8.1' => 'int'], default: '')]
    protected $severity;

    /**
     * Constructs the exception
     * @link https://php.net/manual/en/errorexception.construct.php
     * @param string $message [optional] The Exception message to throw.
     * @param int $code [optional] The Exception code.
     * @param int $severity [optional] The severity level of the exception.
     * @param string $filename [optional] The filename where the exception is thrown.
     * @param int $line [optional] The line number where the exception is thrown.
     * @param Exception $previous [optional] The previous exception used for the exception chaining.
     */
    #[Pure]
    public function __construct(
        #[LanguageLevelTypeAware(['8.0' => 'string'], default: '')] $message = "",
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $code = 0,
        #[LanguageLevelTypeAware(['8.0' => 'int'], default: '')] $severity = 1,
        #[LanguageLevelTypeAware(['8.0' => 'string|null'], default: '')] $filename = __FILE__,
        #[LanguageLevelTypeAware(['8.0' => 'int|null'], default: '')] $line = __LINE__,
        #[LanguageLevelTypeAware(['8.0' => 'Throwable|null'], default: 'Throwable')] $previous = null
    ) {}

    /**
     * Gets the exception severity
     * @link https://php.net/manual/en/errorexception.getseverity.php
     * @return int the severity level of the exception.
     */
    final public function getSeverity(): int {}
}

/**
 * Class used to represent anonymous functions.
 * <p>Anonymous functions, implemented in PHP 5.3, yield objects of this type.
 * This fact used to be considered an implementation detail, but it can now be relied upon.
 * Starting with PHP 5.4, this class has methods that allow further control of the anonymous function after it has been created.
 * <p>Besides the methods listed here, this class also has an __invoke method.
 * This is for consistency with other classes that implement calling magic, as this method is not used for calling the function.
 * @link https://secure.php.net/manual/en/class.closure.php
 */
final class Closure
{
    /**
     * This method exists only to disallow instantiation of the Closure class.
     * Objects of this class are created in the fashion described on the anonymous functions page.
     * @link https://secure.php.net/manual/en/closure.construct.php
     */
    private function __construct() {}

    /**
     * This is for consistency with other classes that implement calling magic,
     * as this method is not used for calling the function.
     * @param mixed ...$_ [optional]
     * @return mixed
     * @link https://secure.php.net/manual/en/class.closure.php
     */
    public function __invoke(...$_) {}

    /**
     * Duplicates the closure with a new bound object and class scope
     * @link https://secure.php.net/manual/en/closure.bindto.php
     * @param object|null $newThis The object to which the given anonymous function should be bound, or NULL for the closure to be unbound.
     * @param mixed $newScope The class scope to which associate the closure is to be associated, or 'static' to keep the current one.
     * If an object is given, the type of the object will be used instead.
     * This determines the visibility of protected and private methods of the bound object.
     * @return Closure|false Returns the newly created Closure object or FALSE on failure
     */
    public function bindTo(?object $newThis, object|string|null $newScope = 'static'): ?Closure {}

    /**
     * This method is a static version of Closure::bindTo().
     * See the documentation of that method for more information.
     * @link https://secure.php.net/manual/en/closure.bind.php
     * @param Closure $closure The anonymous functions to bind.
     * @param object|null $newThis The object to which the given anonymous function should be bound, or NULL for the closure to be unbound.
     * @param mixed $newScope The class scope to which associate the closure is to be associated, or 'static' to keep the current one.
     * If an object is given, the type of the object will be used instead.
     * This determines the visibility of protected and private methods of the bound object.
     * @return Closure|false Returns the newly created Closure object or FALSE on failure
     */
    public static function bind(Closure $closure, ?object $newThis, object|string|null $newScope = 'static'): ?Closure {}

    /**
     * Temporarily binds the closure to newthis, and calls it with any given parameters.
     * @link https://php.net/manual/en/closure.call.php
     * @param object $newThis The object to bind the closure to for the duration of the call.
     * @param mixed $args [optional] Zero or more parameters, which will be given as parameters to the closure.
     * @return mixed
     * @since 7.0
     */
    public function call(object $newThis, mixed ...$args): mixed {}

    /**
     * @param callable $callback
     * @return Closure
     * @since 7.1
     */
    public static function fromCallable(callable $callback): Closure {}
}

/**
 * Classes implementing <b>Countable</b> can be used with the
 * <b>count</b> function.
 * @link https://php.net/manual/en/class.countable.php
 */
interface Countable
{
    /**
     * Count elements of an object
     * @link https://php.net/manual/en/countable.count.php
     * @return int<0,max> The custom count as an integer.
     * <p>
     * The return value is cast to an integer.
     * </p>
     */
    #[TentativeType]
    public function count(): int;
}

/**
 * Weak references allow the programmer to retain a reference to an
 * object which does not prevent the object from being destroyed.
 * They are useful for implementing cache like structures.
 * @template T of object
 * @link https://www.php.net/manual/en/class.weakreference.php
 * @since 7.4
 */
final class WeakReference
{
    /**
     * This method exists only to disallow instantiation of the WeakReference
     * class. Weak references are to be instantiated with the factory method
     * <b>WeakReference::create()</b>.
     */
    public function __construct() {}

    /**
     * Create a new weak reference.
     * @link https://www.php.net/manual/en/weakreference.create.php
     * @template TIn of object
     * @param TIn $object Any object.
     * @return WeakReference<TIn> The freshly instantiated object.
     * @since 7.4
     */
    public static function create(object $object): WeakReference {}

    /**
     * Gets a weakly referenced object. If the object has already been
     * destroyed, NULL is returned.
     * @link https://www.php.net/manual/en/weakreference.get.php
     * @return T|null
     * @since 7.4
     */
    public function get(): ?object {}
}

/**
 * Weak maps allow creating a map from objects to arbitrary values
 * (similar to SplObjectStorage) without preventing the objects that are used
 * as keys from being garbage collected. If an object key is garbage collected,
 * it will simply be removed from the map.
 *
 * @since 8.0
 *
 * @template TKey of object
 * @template TValue
 * @template-implements IteratorAggregate<TKey, TValue>
 */
final class WeakMap implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Returns {@see true} if the value for the object is contained in
     * the {@see WeakMap} and {@see false} instead.
     *
     * @param TKey $object Any object
     * @return bool
     */
    public function offsetExists($object): bool {}

    /**
     * Returns the existsing value by an object.
     *
     * @param TKey $object Any object
     * @return TValue Value associated with the key object
     */
    public function offsetGet($object): mixed {}

    /**
     * Sets a new value for an object.
     *
     * @param TKey $object Any object
     * @param TValue $value Any value
     * @return void
     */
    public function offsetSet($object, mixed $value): void {}

    /**
     * Force removes an object value from the {@see WeakMap} instance.
     *
     * @param TKey $object Any object
     * @return void
     */
    public function offsetUnset($object): void {}

    /**
     * Returns an iterator in the "[object => mixed]" format.
     *
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Iterator {}

    /**
     * Returns the number of items in the {@see WeakMap} instance.
     *
     * @return int<0,max>
     */
    public function count(): int {}
}

/**
 * Stringable interface denotes a class as having a __toString() method.
 *
 * @since 8.0
 */
interface Stringable
{
    /**
     * Magic method {@see https://www.php.net/manual/en/language.oop5.magic.php#object.tostring}
     * allows a class to decide how it will react when it is treated like a string.
     *
     * @return string Returns string representation of the object that
     * implements this interface (and/or "__toString" magic method).
     */
    public function __toString(): string;
}

/**
 * @since 8.0
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Attribute
{
    public int $flags;

    /**
     * Marks that attribute declaration is allowed only in classes.
     */
    public const TARGET_CLASS = 1;

    /**
     * Marks that attribute declaration is allowed only in functions.
     */
    public const TARGET_FUNCTION = 2;

    /**
     * Marks that attribute declaration is allowed only in class methods.
     */
    public const TARGET_METHOD = 4;

    /**
     * Marks that attribute declaration is allowed only in class properties.
     */
    public const TARGET_PROPERTY = 8;

    /**
     * Marks that attribute declaration is allowed only in class constants.
     */
    public const TARGET_CLASS_CONSTANT = 16;

    /**
     * Marks that attribute declaration is allowed only in function or method parameters.
     */
    public const TARGET_PARAMETER = 32;

    /**
     * Marks that attribute declaration is allowed anywhere.
     */
    public const TARGET_ALL = 63;

    /**
     * Notes that an attribute declaration in the same place is
     * allowed multiple times.
     */
    public const IS_REPEATABLE = 64;

    /**
     * @param int $flags A value in the form of a bitmask indicating the places
     * where attributes can be defined.
     */
    public function __construct(#[ExpectedValues(flagsFromClass: Attribute::class)] int $flags = self::TARGET_ALL) {}
}

/**
 * @since 8.0
 */
final class InternalIterator implements Iterator
{
    private function __construct() {}

    public function current(): mixed {}

    public function next(): void {}

    public function key(): mixed {}

    public function valid(): bool {}

    public function rewind(): void {}
}

/**
 * @since 8.1
 */
interface UnitEnum
{
    public readonly string $name;

    /**
     * @return static[]
     */
    #[Pure]
    public static function cases(): array;
}

/**
 * @since 8.1
 */
interface BackedEnum extends UnitEnum
{
    public readonly int|string $value;

    /**
     * @param int|string $value
     * @return static
     */
    #[Pure]
    public static function from(int|string $value): static;

    /**
     * @param int|string $value
     * @return static|null
     */
    #[Pure]
    public static function tryFrom(int|string $value): ?static;
}

/**
 * @since 8.1
 * @internal
 *
 * Internal interface to ensure precise type inference
 */
interface IntBackedEnum extends BackedEnum
{
    public readonly int $value;

    /**
     * @param int $value
     * @return static
     */
    #[Pure]
    public static function from(int $value): static;

    /**
     * @param int $value
     * @return static|null
     */
    #[Pure]
    public static function tryFrom(int $value): ?static;
}

/**
 * @since 8.1
 * @internal
 *
 * Internal interface to ensure precise type inference
 */
interface StringBackedEnum extends BackedEnum
{
    public readonly string $value;

    #[Pure]
    public static function from(string $value): static;

    #[Pure]
    public static function tryFrom(string $value): ?static;
}

/**
 * @since 8.1
 *
 * @template TStart
 * @template TResume
 * @template TReturn
 * @template TSuspend
 */
final class Fiber
{
    /**
     * @param callable $callback Function to invoke when starting the fiber.
     */
    public function __construct(callable $callback) {}

    /**
     * Starts execution of the fiber. Returns when the fiber suspends or terminates.
     *
     * @param TStart ...$args Arguments passed to fiber function.
     *
     * @return TSuspend|null Value from the first suspension point or NULL if the fiber returns.
     *
     * @throws FiberError If the fiber has already been started.
     * @throws Throwable If the fiber callable throws an uncaught exception.
     */
    public function start(mixed ...$args): mixed {}

    /**
     * Resumes the fiber, returning the given value from {@see Fiber::suspend()}.
     * Returns when the fiber suspends or terminates.
     *
     * @param TResume $value
     *
     * @return TSuspend|null Value from the next suspension point or NULL if the fiber returns.
     *
     * @throws FiberError If the fiber has not started, is running, or has terminated.
     * @throws Throwable If the fiber callable throws an uncaught exception.
     */
    public function resume(mixed $value = null): mixed {}

    /**
     * Throws the given exception into the fiber from {@see Fiber::suspend()}.
     * Returns when the fiber suspends or terminates.
     *
     * @param Throwable $exception
     *
     * @return TSuspend|null Value from the next suspension point or NULL if the fiber returns.
     *
     * @throws FiberError If the fiber has not started, is running, or has terminated.
     * @throws Throwable If the fiber callable throws an uncaught exception.
     */
    public function throw(Throwable $exception): mixed {}

    /**
     * @return bool True if the fiber has been started.
     */
    public function isStarted(): bool {}

    /**
     * @return bool True if the fiber is suspended.
     */
    public function isSuspended(): bool {}

    /**
     * @return bool True if the fiber is currently running.
     */
    public function isRunning(): bool {}

    /**
     * @return bool True if the fiber has completed execution (returned or threw).
     */
    public function isTerminated(): bool {}

    /**
     * @return TReturn Return value of the fiber callback. NULL is returned if the fiber does not have a return statement.
     *
     * @throws FiberError If the fiber has not terminated or the fiber threw an exception.
     */
    public function getReturn(): mixed {}

    /**
     * @return self|null Returns the currently executing fiber instance or NULL if in {main}.
     */
    public static function getCurrent(): ?Fiber {}

    /**
     * Suspend execution of the fiber. The fiber may be resumed with {@see Fiber::resume()} or {@see Fiber::throw()}.
     *
     * Cannot be called from {main}.
     *
     * @param TSuspend $value Value to return from {@see Fiber::resume()} or {@see Fiber::throw()}.
     *
     * @return TResume Value provided to {@see Fiber::resume()}.
     *
     * @throws FiberError Thrown if not within a fiber (i.e., if called from {main}).
     * @throws Throwable Exception provided to {@see Fiber::throw()}.
     */
    public static function suspend(mixed $value = null): mixed {}
}

/**
 * @since 8.1
 */
final class FiberError extends Error
{
    public function __construct() {}
}

/**
 * @since 8.1
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class ReturnTypeWillChange
{
    public function __construct() {}
}

/**
 * @since 8.2
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class AllowDynamicProperties
{
    public function __construct() {}
}

/**
 * @since 8.2
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class SensitiveParameter
{
    public function __construct() {}
}

/**
 * @since 8.2
 */
final class SensitiveParameterValue
{
    private readonly mixed $value;

    public function __construct(mixed $value) {}

    public function getValue(): mixed {}

    public function __debugInfo(): array {}
}
