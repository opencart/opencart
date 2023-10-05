<?php

declare(strict_types = 1);

/*
 * This file is part of the Doctum utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Doctum\Parser\ClassVisitor;

use Doctum\Parser\ClassVisitorInterface;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\ParameterReflection;
use Doctum\Reflection\Reflection;

/**
 * Looks for @method tags on classes in the format of:
 *
 * @phpstan-ignore-next-line
 * @method [[static] return type] [name]([[type] [parameter]<, ...>]) [<description>]
 */
class MethodClassVisitor implements ClassVisitorInterface
{

    public function visit(ClassReflection $class)
    {
        $modified = false;

        $methods = $class->getTags('method');
        if (!empty($methods)) {
            foreach ($methods as $methodTag) {
                if ($this->injectMethod($class, implode(' ', $methodTag))) {
                    $modified = true;
                }
            }
        }

        return $modified;
    }

    /**
     * @license MIT
     * @copyright 2010 Mike van Riel
     *
     * Parse the parts of an @method tag into an associative array.
     *
     * Original @method parsing by https://github.com/phpDocumentor/ReflectionDocBlock/blob/5.2.0/src/DocBlock/Tags/Method.php#L84
     *
     * @param string $body Method tag contents
     *
     * @return array|null
     */
    protected function parseMethod($body): ?array
    {
        // 1. none or more whitespace
        // 2. optionally the keyword "static" followed by whitespace
        // 3. optionally a word with underscores followed by whitespace : as
        //    type for the return value
        // 4. then optionally a word with underscores followed by () and
        //    whitespace : as method name as used by phpDocumentor
        // 5. then a word with underscores, followed by ( and any character
        //    until a ) and whitespace : as method name with signature
        // 6. any remaining text : as description
        $regex = '/^
            # Static keyword
            # Declares a static method ONLY if type is also present
            (?:
                (static)
                \s+
            )?
            # Return type
            (?:
                (
                    (?:[\w\|_\\\\]*\$this[\w\|_\\\\]*)
                    |
                    (?:
                        (?:[\w\|_\\\\]+)
                        # array notation
                        (?:\[\])*
                    )*+
                )
                \s+
            )?
            # Method name
            ([\w_]+)
            # Arguments
            (?:
                \((.*(?=\)))\)
            )?
            \s*
            # Description
            (.*)
        $/sux';
        if (!preg_match($regex, $body, $matches)) {
            return null;
        }

        [, $static, $returnType, $methodName, $argumentLines, $description] = $matches;

        $isStatic = $static === 'static';

        if ($returnType === '') {
            $returnType = 'void';
        }

        $arguments = [];
        if ($argumentLines !== '') {
            $argumentsExploded = explode(',', $argumentLines);
            foreach ($argumentsExploded as $argument) {
                $argument           = explode(' ', trim($argument), 2);
                $defaultValue       = '';
                $argumentType       = '';
                $argumentName       = '';
                $hasVariadicAtStart = strpos($argument[0], '...$') === 0;
                if (strpos($argument[0], '$') === 0 || $hasVariadicAtStart) {// Only param name, example: $param1
                    $argumentName = substr($argument[0], $hasVariadicAtStart ? 4 : 1);// Remove $
                } else {// Type and param name, example: string $param1 or just a type, example: string
                    $argumentType = $argument[0];
                    if (isset($argument[1])) {// Type and param name
                        $hasVariadicAtStart = strpos($argument[1], '...$') === 0;
                        $argumentName       = substr($argument[1], $hasVariadicAtStart ? 4 : 1);// Remove $
                        $defaultPart        = explode('=', $argumentName, 2);
                        if (count($defaultPart) === 2) {// detected varName = defaultValue
                            $argumentName = $defaultPart[0];
                            $defaultValue = $defaultPart[1];
                        }
                    }
                }

                $argumentName = trim($argumentName);
                $argumentType = trim($argumentType);
                $defaultValue = trim($defaultValue);

                $arguments[$argumentName] = [
                    'isVariadic' => $hasVariadicAtStart,
                    'name' => $argumentName,
                    'hint' => $argumentType,
                    'default' => $defaultValue,
                ];
            }
        }

        return [
            'isStatic' => $isStatic,
            'hint' => trim($returnType),
            'name' => $methodName,
            'args' => $arguments,
            'description' => $description,
        ];
    }

    /**
     * Adds a new method to the class using an array of tokens.
     *
     * @param ClassReflection $class     Class reflection
     * @param string          $methodTag Method tag contents
     *
     * @return bool
     */
    protected function injectMethod(ClassReflection $class, $methodTag)
    {
        $data = $this->parseMethod($methodTag);

        // Bail if the method format is invalid
        if ($data === null) {
            return false;
        }

        $method = new MethodReflection($data['name'], $class->getLine());
        $method->setDocComment($data['description']);
        $method->setShortDesc($data['description']);

        if ($data['hint']) {
            $method->setHint([[$data['hint'], null]]);
        }
        if ($data['isStatic']) {
            $method->setModifiers(Reflection::MODIFIER_STATIC);
        }

        // Add arguments to the method
        foreach ($data['args'] as $name => $arg) {
            $param = new ParameterReflection($name, $class->getLine());
            if (!empty($arg['hint'])) {
                $param->setHint([[$arg['hint'], null]]);
            }
            if (!empty($arg['default'])) {
                $param->setDefault($arg['default']);
            }
            $param->setVariadic($arg['isVariadic']);
            $method->addParameter($param);
        }

        $class->addMethod($method);

        return true;
    }

}
