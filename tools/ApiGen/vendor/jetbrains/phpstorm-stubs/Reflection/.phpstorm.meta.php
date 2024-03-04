<?php

namespace PHPSTORM_META {

    registerArgumentsSet('ReflectionGetAttributes',
        \ReflectionAttribute::IS_INSTANCEOF
    );

    registerArgumentsSet('ReflectionClassModifiers',
        \ReflectionClass::IS_FINAL|
        \ReflectionClass::IS_EXPLICIT_ABSTRACT|
        \ReflectionClass::IS_IMPLICIT_ABSTRACT
    );

    registerArgumentsSet('ReflectionMethodModifiers',
        \ReflectionMethod::IS_ABSTRACT|
        \ReflectionMethod::IS_FINAL|
        \ReflectionMethod::IS_PUBLIC|
        \ReflectionMethod::IS_PRIVATE|
        \ReflectionMethod::IS_PROTECTED|
        \ReflectionMethod::IS_STATIC
    );

    registerArgumentsSet('ReflectionPropertyModifiers',
        \ReflectionProperty::IS_PUBLIC|
        \ReflectionProperty::IS_PRIVATE|
        \ReflectionProperty::IS_PROTECTED|
        \ReflectionProperty::IS_STATIC
    );

    registerArgumentsSet('ReflectionConstantModifiers',
        \ReflectionClassConstant::IS_PUBLIC|
        \ReflectionClassConstant::IS_PRIVATE|
        \ReflectionClassConstant::IS_PROTECTED
    );

    registerArgumentsSet('ReflectionGeneratorGetTrace',
        \DEBUG_BACKTRACE_PROVIDE_OBJECT,
        \DEBUG_BACKTRACE_IGNORE_ARGS
    );

    registerArgumentsSet('ReflectionAttributeTarget',
        \Attribute::TARGET_CLASS|
        \Attribute::TARGET_FUNCTION|
        \Attribute::TARGET_METHOD|
        \Attribute::TARGET_PROPERTY|
        \Attribute::TARGET_CLASS_CONSTANT|
        \Attribute::TARGET_PARAMETER|
        \Attribute::TARGET_ALL
    );

    expectedArguments(\ReflectionClass::getAttributes(), 1, argumentsSet('ReflectionGetAttributes'));
    expectedArguments(\ReflectionClassConstant::getAttributes(), 1, argumentsSet('ReflectionGetAttributes'));
    expectedArguments(\ReflectionFunctionAbstract::getAttributes(), 1, argumentsSet('ReflectionGetAttributes'));
    expectedArguments(\ReflectionParameter::getAttributes(), 1, argumentsSet('ReflectionGetAttributes'));

    expectedArguments(\ReflectionClass::getMethods(), 0, argumentsSet('ReflectionMethodModifiers'));
    expectedArguments(\ReflectionClass::getProperties(), 0, argumentsSet('ReflectionPropertyModifiers'));
    expectedArguments(\ReflectionGenerator::getTrace(), 0, argumentsSet('ReflectionGeneratorGetTrace'));

    expectedReturnValues(\ReflectionClass::getModifiers(), argumentsSet('ReflectionClassModifiers'));
    expectedReturnValues(\ReflectionProperty::getModifiers(), argumentsSet('ReflectionPropertyModifiers'));
    expectedReturnValues(\ReflectionMethod::getModifiers(), argumentsSet('ReflectionMethodModifiers'));
    expectedReturnValues(\ReflectionClassConstant::getModifiers(), argumentsSet('ReflectionConstantModifiers'));
    expectedReturnValues(\ReflectionAttribute::getTarget(), argumentsSet('ReflectionAttributeTarget'));
}
