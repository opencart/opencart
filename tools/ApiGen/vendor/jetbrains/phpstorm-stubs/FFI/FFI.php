<?php

// Start of FFI v.0.1.0

namespace {
    use FFI\CData;
    use FFI\CType;
    use FFI\ParserException;

    /**
     * FFI class provides access to a simple way to call native functions,
     * access native variables and create/access data structures defined
     * in C language.
     *
     * @since 7.4
     */
    class FFI
    {
        /**
         * The method creates a binding on the existing C function.
         *
         * All variables and functions defined by first arguments are bound
         * to corresponding native symbols in DSO library and then may be
         * accessed as FFI object methods and properties. C types of argument,
         * return value and variables are automatically converted to/from PHP
         * types (if possible). Otherwise, they are wrapped in a special CData
         * proxy object and may be accessed by elements.
         *
         * @param string $code The collection of C declarations.
         * @param string|null $lib DSO library.
         * @return FFI
         * @throws ParserException
         */
        public static function cdef(string $code = '', ?string $lib = null): FFI {}

        /**
         * <p>Instead of embedding of a long C definition into PHP string,
         * and creating FFI through FFI::cdef(), it's possible to separate
         * it into a C header file. Note, that C preprocessor directives
         * (e.g. #define or #ifdef) are not supported. And only a couple of
         * special macros may be used especially for FFI.</p>
         *
         * <code>
         *  #define FFI_LIB "libc.so.6"
         *
         *  int printf(const char *format, ...);
         * </code>
         *
         * Here, FFI_LIB specifies, that the given library should be loaded.
         *
         * <code>
         *  $ffi = FFI::load(__DIR__ . "/printf.h");
         *  $ffi->printf("Hello world!\n");
         * </code>
         *
         * @param string $filename
         * @return FFI|null
         */
        public static function load(string $filename): ?FFI {}

        /**
         * FFI definition parsing and shared library loading may take
         * significant time. It's not useful to do it on each HTTP request in
         * WEB environment. However, it's possible to pre-load FFI definitions
         * and libraries at php startup, and instantiate FFI objects when
         * necessary. Header files may be extended with FFI_SCOPE define
         * (default pre-loading scope is "C"). This name is going to be
         * used as FFI::scope() argument. It's possible to pre-load few
         * files into a single scope.
         *
         * <code>
         *  #define FFI_LIB "libc.so.6"
         *  #define FFI_SCOPE "libc"
         *
         *  int printf(const char *format, ...);
         * </code>
         *
         * These files are loaded through the same FFI::load() load function,
         * executed from file loaded by opcache.preload php.ini directive.
         *
         * <code>
         *  ffi.preload=/etc/php/ffi/printf.h
         * </code>
         *
         * Finally, FFI::scope() instantiate an FFI object, that implements
         * all C definition from the given scope.
         *
         * <code>
         *  $ffi = FFI::scope("libc");
         *  $ffi->printf("Hello world!\n");
         * </code>
         *
         * @param string $name
         * @return FFI
         */
        public static function scope(string $name): FFI {}

        /**
         * Method that creates an arbitrary C structure.
         *
         * @param string|CType $type
         * @param bool $owned
         * @param bool $persistent
         * @return CData|null
         * @throws ParserException
         */
        public static function new($type, bool $owned = true, bool $persistent = false): ?CData {}

        /**
         * Manually removes previously created "not-owned" data structure.
         *
         * @param CData $ptr
         * @return void
         */
        public static function free(CData $ptr): void {}

        /**
         * Casts given $pointer to another C type, specified by C declaration
         * string or FFI\CType object.
         *
         * This function may be called statically and use only predefined
         * types, or as a method of previously created FFI object. In last
         * case the first argument may reuse all type and tag names
         * defined in FFI::cdef().
         *
         * @param CType|string $type
         * @param CData|int|float|bool|null $ptr
         * @return CData|null
         */
        public static function cast($type, $ptr): ?CData {}

        /**
         * This function creates and returns a FFI\CType object, representng
         * type of the given C type declaration string.
         *
         * FFI::type() may be called statically and use only predefined types,
         * or as a method of previously created FFI object. In last case the
         * first argument may reuse all type and tag names defined in
         * FFI::cdef().
         *
         * @param string $type
         * @return CType|null
         */
        public static function type(string $type): ?CType {}

        /**
         * This function returns the FFI\CType object, representing the type of
         * the given FFI\CData object.
         *
         * @param CData $ptr
         * @return CType
         */
        public static function typeof(CData $ptr): CType {}

        /**
         * Constructs a new C array type with elements of $type and
         * dimensions specified by $dimensions.
         *
         * @param CType $type
         * @param int[] $dimensions
         * @return CType
         */
        public static function arrayType(CType $type, array $dimensions): CType {}

        /**
         * Returns C pointer to the given C data structure. The pointer is
         * not "owned" and won't be free. Anyway, this is a potentially
         * unsafe operation, because the life-time of the returned pointer
         * may be longer than life-time of the source object, and this may
         * cause dangling pointer dereference (like in regular C).
         *
         * @param CData $ptr
         * @return CData
         */
        public static function addr(CData $ptr): CData {}

        /**
         * Returns size of C data type of the given FFI\CData or FFI\CType.
         *
         * @param CData|CType $ptr
         * @return int
         */
        public static function sizeof($ptr): int {}

        /**
         * Returns size of C data type of the given FFI\CData or FFI\CType.
         *
         * @param CData|CType $ptr
         * @return int
         */
        public static function alignof($ptr): int {}

        /**
         * Copies $size bytes from memory area $source to memory area $target.
         * $source may be any native data structure (FFI\CData) or PHP string.
         *
         * @param CData $to
         * @param CData|string $from
         * @param int $size
         */
        public static function memcpy(CData $to, $from, int $size): void {}

        /**
         * Compares $size bytes from memory area $ptr1 and $ptr2.
         *
         * @param CData|string $ptr1
         * @param CData|string $ptr2
         * @param int $size
         * @return int
         */
        public static function memcmp($ptr1, $ptr2, int $size): int {}

        /**
         * Fills the $size bytes of the memory area pointed to by $target with
         * the constant byte $byte.
         *
         * @param CData $ptr
         * @param int $value
         * @param int $size
         */
        public static function memset(CData $ptr, int $value, int $size): void {}

        /**
         * Creates a PHP string from $size bytes of memory area pointed by
         * $source. If size is omitted, $source must be zero terminated
         * array of C chars.
         *
         * @param CData $ptr
         * @param int|null $size
         * @return string
         */
        public static function string(CData $ptr, ?int $size = null): string {}

        /**
         * Checks whether the FFI\CData is a null pointer.
         *
         * @param CData $ptr
         * @return bool
         */
        public static function isNull(CData $ptr): bool {}
    }
}

namespace FFI {
    /**
     * General FFI exception.
     *
     * @since 7.4
     */
    class Exception extends \Error {}

    /**
     * An exception that occurs when parsing invalid header files.
     *
     * @since 7.4
     */
    class ParserException extends Exception {}

    /**
     * Proxy object that provides access to compiled structures.
     *
     * In the case that CData is a wrapper over a scalar, it contains an
     * additional "cdata" property.
     *
     * @property int|float|bool|null|string|CData $cdata
     *
     * In the case that the CData is a wrapper over an arbitrary C structure,
     * then it allows reading and writing to the fields defined by
     * this structure.
     *
     * @method mixed __get(string $name)
     * @method mixed __set(string $name, mixed $value)
     *
     * In the case that CData is a wrapper over an array, it is an
     * implementation of the {@see \Traversable}, {@see \Countable},
     * and {@see \ArrayAccess}
     *
     * @mixin \Traversable
     * @mixin \Countable
     * @mixin \ArrayAccess
     *
     * In the case when CData is a wrapper over a function pointer, it can
     * be called.
     *
     * @method mixed __invoke(mixed ...$args)
     *
     * @since 7.4
     */
    class CData
    {
        /**
         * Note that this method does not physically exist and is only required
         * for correct type inference.
         *
         * @param int $offset
         * @return bool
         */
        private function offsetExists(int $offset) {}

        /**
         * Note that this method does not physically exist and is only required
         * for correct type inference.
         *
         * @param int $offset
         * @return CData|int|float|bool|null|string
         */
        private function offsetGet(int $offset) {}

        /**
         * Note that this method does not physically exist and is only required
         * for correct type inference.
         *
         * @param int $offset
         * @param CData|int|float|bool|null|string $value
         */
        private function offsetSet(int $offset, $value) {}

        /**
         * Note that this method does not physically exist and is only required
         * for correct type inference.
         *
         * @param int $offset
         */
        private function offsetUnset(int $offset) {}

        /**
         * Note that this method does not physically exist and is only required
         * for correct type inference.
         *
         * @return int
         */
        private function count(): int {}
    }

    /**
     * Class containing C type information.
     *
     * @since 7.4
     */
    class CType
    {
        /**
         * @since 8.1
         */
        public const TYPE_VOID = 0;

        /**
         * @since 8.1
         */
        public const TYPE_FLOAT = 1;

        /**
         * @since 8.1
         */
        public const TYPE_DOUBLE = 2;

        /**
         * Please note that this constant may NOT EXIST if there is
         * no long double support on the current platform.
         *
         * @since 8.1
         */
        public const TYPE_LONGDOUBLE = 3;

        /**
         * @since 8.1
         */
        public const TYPE_UINT8 = 4;

        /**
         * @since 8.1
         */
        public const TYPE_SINT8 = 5;

        /**
         * @since 8.1
         */
        public const TYPE_UINT16 = 6;

        /**
         * @since 8.1
         */
        public const TYPE_SINT16 = 7;

        /**
         * @since 8.1
         */
        public const TYPE_UINT32 = 8;

        /**
         * @since 8.1
         */
        public const TYPE_SINT32 = 9;

        /**
         * @since 8.1
         */
        public const TYPE_UINT64 = 10;

        /**
         * @since 8.1
         */
        public const TYPE_SINT64 = 11;

        /**
         * @since 8.1
         */
        public const TYPE_ENUM = 12;

        /**
         * @since 8.1
         */
        public const TYPE_BOOL = 13;

        /**
         * @since 8.1
         */
        public const TYPE_CHAR = 14;

        /**
         * @since 8.1
         */
        public const TYPE_POINTER = 15;

        /**
         * @since 8.1
         */
        public const TYPE_FUNC = 16;

        /**
         * @since 8.1
         */
        public const TYPE_ARRAY = 17;

        /**
         * @since 8.1
         */
        public const TYPE_STRUCT = 18;

        /**
         * @since 8.1
         */
        public const ATTR_CONST = 1;

        /**
         * @since 8.1
         */
        public const ATTR_INCOMPLETE_TAG = 2;

        /**
         * @since 8.1
         */
        public const ATTR_VARIADIC = 4;

        /**
         * @since 8.1
         */
        public const ATTR_INCOMPLETE_ARRAY = 8;

        /**
         * @since 8.1
         */
        public const ATTR_VLA = 16;

        /**
         * @since 8.1
         */
        public const ATTR_UNION = 32;

        /**
         * @since 8.1
         */
        public const ATTR_PACKED = 64;

        /**
         * @since 8.1
         */
        public const ATTR_MS_STRUCT = 128;

        /**
         * @since 8.1
         */
        public const ATTR_GCC_STRUCT = 256;

        /**
         * @since 8.1
         */
        public const ABI_DEFAULT = 0;

        /**
         * @since 8.1
         */
        public const ABI_CDECL = 1;

        /**
         * @since 8.1
         */
        public const ABI_FASTCALL = 2;

        /**
         * @since 8.1
         */
        public const ABI_THISCALL = 3;

        /**
         * @since 8.1
         */
        public const ABI_STDCALL = 4;

        /**
         * @since 8.1
         */
        public const ABI_PASCAL = 5;

        /**
         * @since 8.1
         */
        public const ABI_REGISTER = 6;

        /**
         * @since 8.1
         */
        public const ABI_MS = 7;

        /**
         * @since 8.1
         */
        public const ABI_SYSV = 8;

        /**
         * @since 8.1
         */
        public const ABI_VECTORCALL = 9;

        /**
         * Returns the name of the type.
         *
         * @since 8.0
         * @return string
         */
        public function getName(): string {}

        /**
         * Returns the identifier of the root type.
         *
         * Value may be one of:
         *  - {@see CType::TYPE_VOID}
         *  - {@see CType::TYPE_FLOAT}
         *  - {@see CType::TYPE_DOUBLE}
         *  - {@see CType::TYPE_LONGDOUBLE}
         *  - {@see CType::TYPE_UINT8}
         *  - {@see CType::TYPE_SINT8}
         *  - {@see CType::TYPE_UINT16}
         *  - {@see CType::TYPE_SINT16}
         *  - {@see CType::TYPE_UINT32}
         *  - {@see CType::TYPE_SINT32}
         *  - {@see CType::TYPE_UINT64}
         *  - {@see CType::TYPE_SINT64}
         *  - {@see CType::TYPE_ENUM}
         *  - {@see CType::TYPE_BOOL}
         *  - {@see CType::TYPE_CHAR}
         *  - {@see CType::TYPE_POINTER}
         *  - {@see CType::TYPE_FUNC}
         *  - {@see CType::TYPE_ARRAY}
         *  - {@see CType::TYPE_STRUCT}
         *
         * @since 8.1
         * @return int
         */
        public function getKind(): int {}

        /**
         * Returns the size of the type in bytes.
         *
         * @since 8.1
         * @return int
         */
        public function getSize(): int {}

        /**
         * Returns the alignment of the type in bytes.
         *
         * @since 8.1
         * @return int
         */
        public function getAlignment(): int {}

        /**
         * Returns the bit-mask of type attributes.
         *
         * @since 8.1
         * @return int
         */
        public function getAttributes(): int {}

        /**
         * Returns the identifier of the enum value type.
         *
         * Value may be one of:
         *  - {@see CType::TYPE_UINT32}
         *  - {@see CType::TYPE_UINT64}
         *
         * @since 8.1
         * @return int
         * @throws Exception In the case that the type is not an enumeration.
         */
        public function getEnumKind(): int {}

        /**
         * Returns the type of array elements.
         *
         * @since 8.1
         * @return CType
         * @throws Exception In the case that the type is not an array.
         */
        public function getArrayElementType(): CType {}

        /**
         * Returns the size of an array.
         *
         * @since 8.1
         * @return int
         * @throws Exception In the case that the type is not an array.
         */
        public function getArrayLength(): int {}

        /**
         * Returns the original type of the pointer.
         *
         * @since 8.1
         * @return CType
         * @throws Exception In the case that the type is not a pointer.
         */
        public function getPointerType(): CType {}

        /**
         * Returns the field string names of a structure or union.
         *
         * @since 8.1
         * @return array<string>
         * @throws Exception In the case that the type is not a struct or union.
         */
        public function getStructFieldNames(): array {}

        /**
         * Returns the offset of the structure by the name of this field. In
         * the case that the type is a union, then for each field of this type
         * the offset will be equal to 0.
         *
         * @since 8.1
         * @param string $name
         * @return int
         * @throws Exception In the case that the type is not a struct or union.
         */
        public function getStructFieldOffset(string $name): int {}

        /**
         * Returns the field type of a structure or union.
         *
         * @since 8.1
         * @param string $name
         * @return CType
         * @throws Exception In the case that the type is not a struct or union.
         */
        public function getStructFieldType(string $name): CType {}

        /**
         * Returns the application binary interface (ABI) identifier with which
         * you can call the function.
         *
         * Value may be one of:
         *  - {@see CType::ABI_DEFAULT}
         *  - {@see CType::ABI_CDECL}
         *  - {@see CType::ABI_FASTCALL}
         *  - {@see CType::ABI_THISCALL}
         *  - {@see CType::ABI_STDCALL}
         *  - {@see CType::ABI_PASCAL}
         *  - {@see CType::ABI_REGISTER}
         *  - {@see CType::ABI_MS}
         *  - {@see CType::ABI_SYSV}
         *  - {@see CType::ABI_VECTORCALL}
         *
         * @since 8.1
         * @return int
         * @throws Exception In the case that the type is not a function.
         */
        public function getFuncABI(): int {}

        /**
         * Returns the return type of the function.
         *
         * @since 8.1
         * @return CType
         * @throws Exception In the case that the type is not a function.
         */
        public function getFuncReturnType(): CType {}

        /**
         * Returns the number of arguments to the function.
         *
         * @since 8.1
         * @return int
         * @throws Exception In the case that the type is not a function.
         */
        public function getFuncParameterCount(): int {}

        /**
         * Returns the type of the function argument by its numeric index.
         *
         * @since 8.1
         * @param int $index
         * @return CType
         * @throws Exception In the case that the type is not a function.
         */
        public function getFuncParameterType(int $index): CType {}
    }
}
