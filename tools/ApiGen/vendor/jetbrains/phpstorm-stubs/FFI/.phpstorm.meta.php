<?php

namespace PHPSTORM_META {


    registerArgumentsSet('FFICType',
        'void *',

        'bool',

        'float',
        'double',
        'long double',

        'char',
        'signed char',
        'unsigned char',
        'int',
        'signed int',
        'unsigned int',
        'long',
        'signed long',
        'unsigned long',
        'long long',
        'signed long long',
        'unsigned long long',

        'intptr_t',
        'uintptr_t',
        'size_t',
        'ssize_t',
        'ptrdiff_t',
        'off_t',
        'va_list',
        '__builtin_va_list',
        '__gnuc_va_list',

        // stdint.h
        'int8_t',
        'uint8_t',
        'int16_t',
        'uint16_t',
        'int32_t',
        'uint32_t',
        'int64_t',
        'uint64_t',
    );

    registerArgumentsSet('FFICTypeKind',
        \FFI\CType::TYPE_VOID,
        \FFI\CType::TYPE_FLOAT,
        \FFI\CType::TYPE_DOUBLE,
        \FFI\CType::TYPE_LONGDOUBLE,
        \FFI\CType::TYPE_UINT8,
        \FFI\CType::TYPE_SINT8,
        \FFI\CType::TYPE_UINT16,
        \FFI\CType::TYPE_SINT16,
        \FFI\CType::TYPE_UINT32,
        \FFI\CType::TYPE_SINT32,
        \FFI\CType::TYPE_UINT64,
        \FFI\CType::TYPE_SINT64,
        \FFI\CType::TYPE_ENUM,
        \FFI\CType::TYPE_BOOL,
        \FFI\CType::TYPE_CHAR,
        \FFI\CType::TYPE_POINTER,
        \FFI\CType::TYPE_FUNC,
        \FFI\CType::TYPE_ARRAY,
        \FFI\CType::TYPE_STRUCT
    );

    registerArgumentsSet('FFICTypeEnumKind',
        \FFI\CType::TYPE_UINT32,
        \FFI\CType::TYPE_UINT64,
    );

    registerArgumentsSet('FFICTypeAttr',
        \FFI\CType::ATTR_CONST,
        \FFI\CType::ATTR_INCOMPLETE_TAG,
        \FFI\CType::ATTR_VARIADIC,
        \FFI\CType::ATTR_INCOMPLETE_ARRAY,
        \FFI\CType::ATTR_VLA,
        \FFI\CType::ATTR_UNION,
        \FFI\CType::ATTR_PACKED,
        \FFI\CType::ATTR_MS_STRUCT,
        \FFI\CType::ATTR_GCC_STRUCT,
    );

    registerArgumentsSet('FFICTypeAbi',
        \FFI\CType::ABI_DEFAULT,
        \FFI\CType::ABI_CDECL,
        \FFI\CType::ABI_FASTCALL,
        \FFI\CType::ABI_THISCALL,
        \FFI\CType::ABI_STDCALL,
        \FFI\CType::ABI_PASCAL,
        \FFI\CType::ABI_REGISTER,
        \FFI\CType::ABI_MS,
        \FFI\CType::ABI_SYSV,
        \FFI\CType::ABI_VECTORCALL
    );

    expectedArguments(\FFI::new(), 0, argumentsSet('FFICType'));
    expectedArguments(\FFI::cast(), 0, argumentsSet('FFICType'));
    expectedArguments(\FFI::type(), 0, argumentsSet('FFICType'));

    expectedReturnValues(\FFI\CType::getKind(), argumentsSet('FFICTypeKind'));
    expectedReturnValues(\FFI\CType::getEnumKind(), argumentsSet('FFICTypeEnumKind'));

    expectedReturnValues(\FFI\CType::getAttributes(), argumentsSet('FFICTypeAttr'));

    expectedReturnValues(\FFI\CType::getFuncABI(), argumentsSet('FFICTypeAbi'));

    override(\FFI::addr(), type(0));
}
