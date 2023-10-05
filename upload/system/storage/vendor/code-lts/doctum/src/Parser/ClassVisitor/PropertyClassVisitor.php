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
use Doctum\Parser\ParserContext;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\PropertyReflection;

/**
 * Looks for @property tags on classes in the format of:.
 *
 * @phpstan-ignore-next-line
 * @property [<type>] [name] [<description>]
 *
 * Or -read -write properties
 *
 * @phpstan-ignore-next-line
 * @property-read [Type] [name] [<description>]
 * @property-write [Type] [name] [<description>]
 */
class PropertyClassVisitor implements ClassVisitorInterface
{
    protected $context;

    public function __construct(ParserContext $context)
    {
        $this->context = $context;
    }

    public function visit(ClassReflection $class)
    {
        $modified        = false;
        $properties      = $class->getTags('property');
        $propertiesRead  = $class->getTags('property-read');
        $propertiesWrite = $class->getTags('property-write');

        if (!empty($properties)) {
            foreach ($properties as $propertyTag) {
                if ($this->injectProperty($class, $propertyTag, 'property')) {
                    $modified = true;
                }
            }
        }

        if (!empty($propertiesRead)) {
            foreach ($propertiesRead as $propertyTag) {
                if ($this->injectProperty($class, $propertyTag, 'property-read')) {
                    $modified = true;
                }
            }
        }

        if (!empty($propertiesWrite)) {
            foreach ($propertiesWrite as $propertyTag) {
                if ($this->injectProperty($class, $propertyTag, 'property-write')) {
                    $modified = true;
                }
            }
        }
        return $modified;
    }

    /**
     * Adds a new property to the class using an array of tokens.
     *
     * @param ClassReflection $class       Class reflection
     * @param array           $propertyTag Property tag contents
     * @param string          $tagName     The tag name, for example: property-read
     */
    protected function injectProperty(ClassReflection $class, array $propertyTag, string $tagName): bool
    {
        if (count($propertyTag) === 3 && !empty($propertyTag[1])) {
            $property = new PropertyReflection($propertyTag[1], $class->getLine());
            $property->setDocComment($propertyTag[2]);
            $property->setShortDesc($propertyTag[2]);

            if (!empty($propertyTag[0])) {
                $property->setHint($propertyTag[0]);
            }

            if ($tagName === 'property-read') {
                $property->setReadOnly(true);
            } elseif ($tagName === 'property-write') {
                $property->setWriteOnly(true);
            }

            $class->addProperty($property);


            return true;
        }

        return false;
    }

}
