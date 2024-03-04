<?php

namespace {
    use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

    /**
     * Creates an array.
     * @link https://php.net/manual/en/function.array.php
     * @param mixed ...$_ [optional] <p>
     * Syntax "index => values", separated by commas, define index and values.
     * index may be of type string or integer. When index is omitted, an integer index is automatically generated,
     * starting at 0. If index is an integer, next generated index will be the biggest integer index + 1.
     * Note that when two identical index are defined, the last overwrite the first.
     * </p>
     * <p>
     * Having a trailing comma after the last defined array entry, while unusual, is a valid syntax.
     * </p>
     * @return array an array of the parameters. The parameters can be given an index with the => operator.
     */
    function PS_UNRESERVE_PREFIX_array(...$_) {}

    /**
     * Assigns a list of variables in one operation.
     * @link https://php.net/manual/en/function.list.php
     * @param mixed $var1 <p>A variable.</p>
     * @param mixed ...$_ [optional] <p>Another variable ...</p>
     * @return array the assigned array.
     */
    function PS_UNRESERVE_PREFIX_list($var1, ...$_) {}

    /**
     * <p>Terminates execution of the script. Shutdown functions and object destructors will always be executed even if exit is called.</p>
     * <p>die is a language construct and it can be called without parentheses if no status is passed.</p>
     * @link https://php.net/manual/en/function.die.php
     * @param int|string $status [optional] <p>
     * If status is a string, this function prints the status just before exiting.
     * </p>
     * <p>
     * If status is an integer, that value will be used as the exit status and not printed. Exit statuses should be in the range 0 to 254,
     * the exit status 255 is reserved by PHP and shall not be used. The status 0 is used to terminate the program successfully.
     * </p>
     * <p>
     * Note: PHP >= 4.2.0 does NOT print the status if it is an integer.
     * </p>
     * @return void
     */
    function PS_UNRESERVE_PREFIX_die($status = "") {}

    /**
     * <p>Terminates execution of the script. Shutdown functions and object destructors will always be executed even if exit is called.</p>
     * <p>exit is a language construct and it can be called without parentheses if no status is passed.</p>
     * @link https://php.net/manual/en/function.exit.php
     * @param int|string $status [optional] <p>
     * If status is a string, this function prints the status just before exiting.
     * </p>
     * <p>
     * If status is an integer, that value will be used as the exit status and not printed. Exit statuses should be in the range 0 to 254,
     * the exit status 255 is reserved by PHP and shall not be used. The status 0 is used to terminate the program successfully.
     * </p>
     * <p>
     * Note: PHP >= 4.2.0 does NOT print the status if it is an integer.
     * </p>
     * @return void
     */
    function PS_UNRESERVE_PREFIX_exit($status = "") {}

    /**
     * Determine whether a variable is considered to be empty. A variable is considered empty if it does not exist or if its value
     * equals <b>FALSE</b>. <b>empty()</b> does not generate a warning if the variable does not exist.
     * @link https://php.net/manual/en/function.empty.php
     * @param mixed $var <p>Variable to be checked.</p>
     * <p>Note: Prior to PHP 5.5, <b>empty()</b> only supports variables; anything else will result in a parse error. In other words,
     * the following will not work: <b>empty(trim($name))</b>. Instead, use <b>trim($name) == false</b>.
     * </p>
     * <p>
     * No warning is generated if the variable does not exist. That means <b>empty()</b> is essentially the concise equivalent
     * to <b>!isset($var) || $var == false</b>.
     * </p>
     * @return bool <p><b>FALSE</b> if var exists and has a non-empty, non-zero value. Otherwise returns <b>TRUE</b>.<p>
     * <p>
     * The following things are considered to be empty:
     * <ul>
     * <li>"" (an empty string)</li>
     * <li>0 (0 as an integer)</li>
     * <li>0.0 (0 as a float)</li>
     * <li>"0" (0 as a string)</li>
     * <li><b>NULL</b></li>
     * <li><b>FALSE</b></li>
     * <li>array() (an empty array)</li>
     * <li>$var; (a variable declared, but without a value)</li>
     * </ul>
     * </p>
     */
    function PS_UNRESERVE_PREFIX_empty($var) {}

    /**
     * <p>Determine if a variable is set and is not <b>NULL</b>.</p>
     * <p>If a variable has been unset with unset(), it will no longer be set. <b>isset()</b> will return <b>FALSE</b> if testing a variable
     * that has been set to <b>NULL</b>. Also note that a null character ("\0") is not equivalent to the PHP <b>NULL</b> constant.</p>
     * <p>If multiple parameters are supplied then <b>isset()</b> will return <b>TRUE</b> only if all of the parameters are set.
     * Evaluation goes from left to right and stops as soon as an unset variable is encountered.</p>
     * @link https://php.net/manual/en/function.isset.php
     * @param mixed $var <p>The variable to be checked.</p>
     * @param mixed ...$_ [optional] <p>Another variable ...</p>
     * @return bool Returns <b>TRUE</b> if var exists and has value other than <b>NULL</b>, <b>FALSE</b> otherwise.
     */
    function PS_UNRESERVE_PREFIX_isset($var, ...$_) {}

    /**
     * <p>Destroys the specified variables.</p>
     * <p>The behavior of <b>unset()</b> inside of a function can vary depending on what type of variable you are attempting to destroy.</p>
     * @link https://php.net/manual/en/function.unset.php
     * @param mixed $var <p>The variable to be unset.</p>
     * @param mixed ...$_ [optional] <p>Another variable ...</p>
     * @return void
     */
    function PS_UNRESERVE_PREFIX_unset($var, ...$_) {}

    /**
     * <p>Evaluates the given code as PHP.</p>
     * <p>Caution: The <b>eval()</b> language construct is very dangerous because it allows execution of arbitrary PHP code. Its use thus is
     * discouraged. If you have carefully verified that there is no other option than to use this construct, pay special attention not to
     * pass any user provided data into it without properly validating it beforehand.</p>
     * @link https://php.net/manual/en/function.eval.php
     * @param string $code <p>
     * Valid PHP code to be evaluated.
     * </p>
     * <p>
     * The code must not be wrapped in opening and closing PHP tags, i.e. 'echo "Hi!";' must be passed instead of '<?php echo "Hi!"; ?>'.
     * It is still possible to leave and re-enter PHP mode though using the appropriate PHP tags, e.g.
     * 'echo "In PHP mode!"; ?>In HTML mode!<?php echo "Back in PHP mode!";'.
     * </p>
     * <p>
     * Apart from that the passed code must be valid PHP. This includes that all statements must be properly terminated using a semicolon.
     * 'echo "Hi!"' for example will cause a parse error, whereas 'echo "Hi!";' will work.
     * </p>
     * <p>
     * A return statement will immediately terminate the evaluation of the code.
     * </p>
     * <p>
     * The code will be executed in the scope of the code calling <b>eval()</b>. Thus any variables defined or changed in the <b>eval()</b>
     * call will remain visible after it terminates.
     * </p>
     * @return mixed <b>NULL</b> unless return is called in the evaluated code, in which case the value passed to return is returned.
     * As of PHP 7, if there is a parse error in the evaluated code, <b>eval()</b> throws a ParseError exception. Before PHP 7, in this
     * case <b>eval()</b> returned <b>FALSE</b> and execution of the following code continued normally. It is not possible to catch a parse
     * error in <b>eval()</b> using set_error_handler().
     */
    function PS_UNRESERVE_PREFIX_eval($code) {}

    /**
     * @template TKey of array-key
     * @template TSend
     * @template TReturn
     * @template TYield
     *
     * Generator objects are returned from generators, cannot be instantiated via new.
     * @link https://secure.php.net/manual/en/class.generator.php
     * @link https://wiki.php.net/rfc/generators
     *
     * @template-implements Iterator<TKey, TYield>
     */
    final class Generator implements Iterator
    {
        /**
         * Throws an exception if the generator is currently after the first yield.
         * @return void
         */
        public function rewind(): void {}

        /**
         * Returns false if the generator has been closed, true otherwise.
         * @return bool
         */
        public function valid(): bool {}

        /**
         * Returns whatever was passed to yield or null if nothing was passed or the generator is already closed.
         * @return TYield
         */
        public function current(): mixed {}

        /**
         * Returns the yielded key or, if none was specified, an auto-incrementing key or null if the generator is already closed.
         * @return TKey
         */
        #[LanguageLevelTypeAware(['8.0' => 'mixed'], default: 'string|float|int|bool|null')]
        public function key() {}

        /**
         * Resumes the generator (unless the generator is already closed).
         * @return void
         */
        public function next(): void {}

        /**
         * Sets the return value of the yield expression and resumes the generator (unless the generator is already closed).
         * @param TSend $value
         * @return TYield|null
         */
        public function send(mixed $value): mixed {}

        /**
         * Throws an exception at the current suspension point in the generator.
         * @param Throwable $exception
         * @return TYield
         */
        public function PS_UNRESERVE_PREFIX_throw(Throwable $exception): mixed {}

        /**
         * Returns whatever was passed to return or null if nothing.
         * Throws an exception if the generator is still valid.
         * @link https://wiki.php.net/rfc/generator-return-expressions
         * @return TReturn
         * @since 7.0
         */
        public function getReturn(): mixed {}

        /**
         * Serialize callback
         * Throws an exception as generators can't be serialized.
         * @link https://php.net/manual/en/generator.wakeup.php
         * @return void
         */
        public function __wakeup() {}
    }

    class ClosedGeneratorException extends Exception {}
}

namespace ___PHPSTORM_HELPERS {
    class PS_UNRESERVE_PREFIX_this {}

    class PS_UNRESERVE_PREFIX_static {}

    class object
    {
        /**
         * PHP 5 allows developers to declare constructor methods for classes.
         * Classes which have a constructor method call this method on each newly-created object,
         * so it is suitable for any initialization that the object may need before it is used.
         *
         * Note: Parent constructors are not called implicitly if the child class defines a constructor.
         * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
         *
         * param [ mixed $args [, $... ]]
         * @link https://php.net/manual/en/language.oop5.decon.php
         */
        public function __construct() {}

        /**
         * PHP 5 introduces a destructor concept similar to that of other object-oriented languages, such as C++.
         * The destructor method will be called as soon as all references to a particular object are removed or
         * when the object is explicitly destroyed or in any order in shutdown sequence.
         *
         * Like constructors, parent destructors will not be called implicitly by the engine.
         * In order to run a parent destructor, one would have to explicitly call parent::__destruct() in the destructor body.
         *
         * Note: Destructors called during the script shutdown have HTTP headers already sent.
         * The working directory in the script shutdown phase can be different with some SAPIs (e.g. Apache).
         *
         * Note: Attempting to throw an exception from a destructor (called in the time of script termination) causes a fatal error.
         *
         * @return void
         * @link https://php.net/manual/en/language.oop5.decon.php
         */
        public function __destruct() {}

        /**
         * is triggered when invoking inaccessible methods in an object context.
         *
         * @param string $name
         * @param array $arguments
         * @return mixed
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
         */
        public function __call(string $name, array $arguments) {}

        /**
         * is triggered when invoking inaccessible methods in a static context.
         *
         * @param string $name
         * @param array $arguments
         * @return mixed
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
         */
        public static function __callStatic(string $name, array $arguments) {}

        /**
         * is utilized for reading data from inaccessible members.
         *
         * @param string $name
         * @return mixed
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
         */
        public function __get(string $name) {}

        /**
         * run when writing data to inaccessible members.
         *
         * @param string $name
         * @param mixed $value
         * @return void
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
         */
        public function __set(string $name, $value): void {}

        /**
         * is triggered by calling isset() or empty() on inaccessible members.
         *
         * @param string $name
         * @return bool
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
         */
        public function __isset(string $name): bool {}

        /**
         * is invoked when unset() is used on inaccessible members.
         *
         * @param string $name
         * @return void
         * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.members
         */
        public function __unset(string $name): void {}

        /**
         * serialize() checks if your class has a function with the magic name __sleep.
         * If so, that function is executed prior to any serialization.
         * It can clean up the object and is supposed to return an array with the names of all variables of that object that should be serialized.
         * If the method doesn't return anything then NULL is serialized and E_NOTICE is issued.
         * The intended use of __sleep is to commit pending data or perform similar cleanup tasks.
         * Also, the function is useful if you have very large objects which do not need to be saved completely.
         *
         * @return string[]
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.sleep
         */
        public function __sleep(): array {}

        /**
         * unserialize() checks for the presence of a function with the magic name __wakeup.
         * If present, this function can reconstruct any resources that the object may have.
         * The intended use of __wakeup is to reestablish any database connections that may have been lost during
         * serialization and perform other reinitialization tasks.
         *
         * @return void
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.sleep
         */
        public function __wakeup(): void {}

        /**
         * The __toString method allows a class to decide how it will react when it is converted to a string.
         *
         * @return string
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
         */
        public function __toString(): string {}

        /**
         * The __invoke method is called when a script tries to call an object as a function.
         *
         * @return mixed
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.invoke
         */
        public function __invoke() {}

        /**
         * This method is called by var_dump() when dumping an object to get the properties that should be shown.
         * If the method isn't defined on an object, then all public, protected and private properties will be shown.
         *
         * @return array|null
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
         */
        public function __debugInfo(): ?array {}

        /**
         * This static method is called for classes exported by var_export() since PHP 5.1.0.
         * The only parameter of this method is an array containing exported properties in the form array('property' => value, ...).
         *
         * @param array $an_array
         * @return object
         * @link https://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.set-state
         */
        public static function __set_state(array $an_array): object {}

        /**
         * When an object is cloned, PHP 5 will perform a shallow copy of all of the object's properties.
         * Any properties that are references to other variables, will remain references.
         * Once the cloning is complete, if a __clone() method is defined,
         * then the newly created object's __clone() method will be called, to allow any necessary properties that need to be changed.
         * NOT CALLABLE DIRECTLY.
         *
         * @return void
         * @link https://php.net/manual/en/language.oop5.cloning.php
         */
        public function __clone(): void {}

        /**
         * Returns array containing all the necessary state of the object.
         * @since 7.4
         * @link https://wiki.php.net/rfc/custom_object_serialization
         */
        public function __serialize(): array {}

        /**
         * Restores the object state from the given data array.
         * @param array $data
         * @since 7.4
         * @link https://wiki.php.net/rfc/custom_object_serialization
         */
        public function __unserialize(array $data): void {}
    }
}
