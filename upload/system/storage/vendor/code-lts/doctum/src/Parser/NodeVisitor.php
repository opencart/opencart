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

namespace Doctum\Parser;

use PhpParser\Node as AbstractNode;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\ClassConst as ClassConstNode;
use PhpParser\Node\Stmt\ClassMethod as ClassMethodNode;
use PhpParser\Node\Stmt\Class_ as ClassNode;
use PhpParser\Node\Stmt\ClassLike as ClassLikeNode;
use PhpParser\Node\Stmt\Interface_ as InterfaceNode;
use PhpParser\Node\Stmt\Namespace_ as NamespaceNode;
use PhpParser\Node\Stmt\Function_ as FunctionNode;
use PhpParser\Node\Stmt\Property as PropertyNode;
use PhpParser\Node\Stmt\TraitUse as TraitUseNode;
use PhpParser\Node\Stmt\Trait_ as TraitNode;
use PhpParser\Node\Stmt\Use_ as UseNode;
use PhpParser\Node\NullableType;
use PhpParser\Node\Expr\Error as ExprError;
use PhpParser\Node\Expr as NodeExpr;
use Doctum\Project;
use Doctum\Reflection\Reflection;
use Doctum\Reflection\FunctionReflection;
use Doctum\Reflection\ClassReflection;
use Doctum\Reflection\ConstantReflection;
use Doctum\Reflection\MethodReflection;
use Doctum\Reflection\ParameterReflection;
use Doctum\Reflection\PropertyReflection;
use Doctum\Parser\Node\DocBlockNode;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\UnionType;

class NodeVisitor extends NodeVisitorAbstract
{
    protected $context;

    public function __construct(ParserContext $context)
    {
        $this->context = $context;
    }

    public function enterNode(AbstractNode $node)
    {
        if ($node instanceof NamespaceNode) {
            $this->context->enterNamespace($node->name === null ? '' : $node->name->__toString());
        } elseif ($node instanceof UseNode) {
            $this->addAliases($node);
        } elseif ($node instanceof InterfaceNode) {
            $this->addInterface($node);
        } elseif ($node instanceof ClassNode) {
            $this->addClass($node);
        } elseif ($node instanceof TraitNode) {
            $this->addTrait($node);
        } elseif ($node instanceof FunctionNode) {
            $this->addFunction($node, $this->context->getNamespace());
        } elseif ($this->context->getClass() && $node instanceof TraitUseNode) {
            $this->addTraitUse($node);
        } elseif ($this->context->getClass() && $node instanceof PropertyNode) {
            $this->addProperty($node);
        } elseif ($this->context->getClass() && $node instanceof ClassMethodNode) {
            $this->addMethod($node);
        } elseif ($this->context->getClass() && $node instanceof ClassConstNode) {
            $this->addConstant($node);
        }
        return null;
    }

    public function leaveNode(AbstractNode $node)
    {
        if ($node instanceof NamespaceNode) {
            $this->context->leaveNamespace();
        } elseif ($node instanceof ClassNode || $node instanceof InterfaceNode || $node instanceof TraitNode) {
            $this->context->leaveClass();
        }
        return null;
    }

    protected function addAliases(UseNode $node)
    {
        foreach ($node->uses as $use) {
            $alias    = $use->getAlias()->toString();
            $fullName = $use->name->__toString();
            $this->context->addAlias($alias, $fullName);
        }
    }

    protected function addFunction(FunctionNode $node, ?string $namespace = null)
    {
        $function = new FunctionReflection($node->name->__toString(), $node->getLine());
        $function->setNamespace($namespace !== null ? $namespace : '');
        $function->setByRef((string) $node->byRef);
        $function->setFile($this->context->getFile());

        foreach ($node->params as $param) {
            if ($param->var instanceof ExprError) {
                $errors = [
                    'The expression had an error, please report this to Doctum for a better handling of this error',
                ];
                $this->context->addErrors($function->__toString(), $node->getLine(), $errors);
                continue;
            }
            if ($param->var->name instanceof NodeExpr) {
                $errors = [
                    'This was unexpected, please report this to Doctum for a better handling of this error',
                ];
                $this->context->addErrors($function->__toString(), $node->getLine(), $errors);
                continue;
            }
            $parameter = new ParameterReflection(
                $param->var->name,
                $param->getLine()
            );
            $parameter->setModifiers($param->flags);
            $parameter->setByRef($param->byRef);
            if ($param->default) {
                $parameter->setDefault($this->context->getPrettyPrinter()->prettyPrintExpr($param->default));
            }

            $parameter->setVariadic($param->variadic);

            $type    = $param->type;
            $typeStr = $this->typeToString($type);

            if (null !== $typeStr) {
                $typeArr = [[$typeStr, false]];

                if ($param->type instanceof NullableType) {
                    $typeArr[] = ['null', false];
                }

                $parameter->setHint($this->resolveHint($typeArr));
            }

            $function->addParameter($parameter);
        }

        $docComment = $node->getDocComment();
        $docComment = $docComment === null ? null : $docComment->__toString();
        $comment    = $this->context->getDocBlockParser()->parse($docComment, $this->context, $function);
        $function->setDocComment($docComment);
        $function->setShortDesc($comment->getShortDesc());
        $function->setLongDesc($comment->getLongDesc());
        $function->setSee($this->resolveSee($comment->getTag('see')));
        if (!$errors = $comment->getErrors()) {
            $errors = $this->updateMethodParametersFromTags($function, $comment->getTag('param'));

            $this->addTagFromCommentToMethod('return', $comment, $function, $errors);

            $function->setExceptions($comment->getTag('throws'));
            $function->setTags($comment->getOtherTags());
        }


        $function->setModifiersFromTags();
        $function->setErrors($errors);

        $returnType    = $node->getReturnType();
        $returnTypeStr = $this->typeToString($returnType);

        if (null !== $returnTypeStr) {
            $returnTypeArr = [[$returnTypeStr, false]];

            if ($returnType instanceof NullableType) {
                $returnTypeArr[] = ['null', false];
            }

            $function->setHint($this->resolveHint($returnTypeArr));
        }

        $this->context->addFunction($function);

        if ($errors) {
            $this->context->addErrors((string) $function, $node->getLine(), $errors);
        }
    }

    /**
     * @param \PhpParser\Node\Identifier|\PhpParser\Node\Name|NullableType|UnionType|null $type Type declaration
     */
    protected function typeToString($type): ?string
    {
        $typeString = null;
        if ($type !== null && ! ($type instanceof NullableType || $type instanceof UnionType)) {
            $typeString = $type->__toString();
        } elseif ($type instanceof NullableType) {
            $typeString = $type->type->__toString();
        } elseif ($type instanceof UnionType) {
            $typeString = [];
            foreach ($type->types as $type) {
                $typeString[] = $type->__toString();
            }
            $typeString = implode('|', $typeString);
        }

        if ($typeString === null) {
            return null;
        }

        if ($type instanceof FullyQualified && 0 !== strpos($typeString, '\\')) {
            $typeString = '\\' . $typeString;
        }

        return $typeString;
    }

    protected function addInterface(InterfaceNode $node)
    {
        $class = $this->addClassOrInterface($node);

        $class->setInterface(true);
        foreach ($node->extends as $interface) {
            $class->addInterface((string) $interface);
        }
    }

    protected function addClass(ClassNode $node)
    {
        // Skip anonymous classes
        if ($node->isAnonymous()) {
            return;
        }

        $class = $this->addClassOrInterface($node);

        foreach ($node->implements as $interface) {
            $class->addInterface((string) $interface);
        }

        if ($node->extends) {
            $class->setParent((string) $node->extends);
        }
    }

    protected function addTrait(TraitNode $node)
    {
        $class = $this->addClassOrInterface($node);

        $class->setTrait(true);
    }

    protected function addClassOrInterface(ClassLikeNode $node)
    {
        $class = new ClassReflection((string) $node->namespacedName, $node->getLine());
        return $this->addClassOrInterfaceForReflection($class, $node);
    }

    protected function addClassOrInterfaceForReflection(ClassReflection $class, ClassLikeNode $node): ClassReflection
    {
        if ($node instanceof ClassNode) {
            $class->setModifiers($node->flags);
        }
        $class->setNamespace($this->context->getNamespace() ?? '');
        $class->setAliases($this->context->getAliases());
        $class->setHash($this->context->getHash());
        $class->setFile($this->context->getFile());

        $docComment = $node->getDocComment();
        $docComment = $docComment === null ? null : $docComment->__toString();
        $comment    = $this->context->getDocBlockParser()->parse($docComment, $this->context, $class);
        $class->setDocComment($docComment);
        $class->setShortDesc($comment->getShortDesc());
        $class->setLongDesc($comment->getLongDesc());
        $class->setSee($this->resolveSee($comment->getTag('see')));
        if ($errors = $comment->getErrors()) {
            $class->setErrors($errors);
        } else {
            $otherTags = $comment->getOtherTags();
            if (isset($otherTags['readonly'])) {
                $class->setReadOnly(true);
            }
            $class->setTags($otherTags);
        }

        if ($this->context->getFilter()->acceptClass($class)) {
            if ($errors) {
                $this->context->addErrors((string) $class, $node->getLine(), $errors);
            }
            $this->context->enterClass($class);
        }

        $class->setModifiersFromTags();

        return $class;
    }

    protected function addMethod(ClassMethodNode $node)
    {
        $method = new MethodReflection($node->name->__toString(), $node->getLine());
        $method->setModifiers($node->flags);
        $method->setByRef((string) $node->byRef);

        foreach ($node->params as $param) {
            if ($param->var instanceof ExprError) {
                $errors = [
                    'The expression had an error, please report this to Doctum for a better handling of this error',
                ];
                $this->context->addErrors($method->__toString(), $node->getLine(), $errors);
                continue;
            }
            if ($param->var->name instanceof NodeExpr) {
                $errors = [
                    'This was unexpected, please report this to Doctum for a better handling of this error',
                ];
                $this->context->addErrors($method->__toString(), $node->getLine(), $errors);
                continue;
            }
            $parameter = new ParameterReflection($param->var->name, $param->getLine());
            $parameter->setModifiers($param->flags);
            $parameter->setByRef($param->byRef);
            if ($param->default) {
                $parameter->setDefault($this->context->getPrettyPrinter()->prettyPrintExpr($param->default));
            }

            $parameter->setVariadic($param->variadic);

            $type    = $param->type;
            $typeStr = $this->typeToString($type);

            if (null !== $typeStr) {
                $typeArr = [[$typeStr, false]];

                if ($param->type instanceof NullableType) {
                    $typeArr[] = ['null', false];
                }

                $parameter->setHint($this->resolveHint($typeArr));
            }

            $method->addParameter($parameter);
        }

        $docComment = $node->getDocComment();
        $docComment = $docComment === null ? null : $docComment->__toString();
        $comment    = $this->context->getDocBlockParser()->parse(
            $docComment,
            $this->context,
            $method
        );
        $method->setDocComment($docComment);
        $method->setShortDesc($comment->getShortDesc());
        $method->setLongDesc($comment->getLongDesc());
        $method->setSee($this->resolveSee($comment->getTag('see')));
        if (!$errors = $comment->getErrors()) {
            $errors = $this->updateMethodParametersFromTags($method, $comment->getTag('param'));

            $this->addTagFromCommentToMethod('return', $comment, $method, $errors);

            $method->setExceptions($comment->getTag('throws'));
            $method->setTags($comment->getOtherTags());
        }

        $method->setModifiersFromTags();
        $method->setErrors($errors);

        $returnType    = $node->getReturnType();
        $returnTypeStr = $this->typeToString($returnType);

        if (null !== $returnTypeStr) {
            $returnTypeArr = [[$returnTypeStr, false]];

            if ($returnType instanceof NullableType) {
                $returnTypeArr[] = ['null', false];
            }

            $method->setHint($this->resolveHint($returnTypeArr));
        }

        if ($this->context->getFilter()->acceptMethod($method)) {
            $this->context->getClass()->addMethod($method);

            if ($errors) {
                $this->context->addErrors((string) $method, $node->getLine(), $errors);
            }
        }
    }

    protected function addTagFromCommentToMethod(
        string $tagName,
        DocBlockNode $comment,
        Reflection $methodOrFunctionOrProperty,
        array &$errors
    ): void {
        $tagsThatShouldHaveOnlyOne = ['return', 'var'];
        $tag                       = $comment->getTag($tagName);
        if (is_array($tag)) {
            if (in_array($tagName, $tagsThatShouldHaveOnlyOne, true) && count($tag) > 1) {
                $errors[] = sprintf(
                    'Too much @%s tags on "%s" at @%s found: %d @%s tags',
                    $tagName,
                    $methodOrFunctionOrProperty->getName(),
                    $tagName,
                    count($tag),
                    $tagName
                );
            }
            $firstTagFound = $tag[0] ?? null;
            if ($firstTagFound !== null) {
                if (is_array($firstTagFound)) {
                    $hint            = $firstTagFound[0];
                    $hintDescription = $firstTagFound[1] ?? null;
                    $methodOrFunctionOrProperty->setHint(is_array($hint) ? $this->resolveHint($hint) : $hint);
                    if ($hintDescription !== null) {
                        if (is_string($hintDescription)) {
                            $methodOrFunctionOrProperty->setHintDesc($hintDescription);
                        } else {
                            $errors[] = sprintf(
                                'The hint description on "%s" at @%s is invalid: "%s"',
                                $methodOrFunctionOrProperty->getName(),
                                $tagName,
                                $hintDescription
                            );
                        }
                    }
                } else {
                    $errors[] = sprintf(
                        'The hint on "%s" at @%s is invalid: "%s"',
                        $methodOrFunctionOrProperty->getName(),
                        $tagName,
                        $firstTagFound
                    );
                }
            }
        }
    }

    protected function addProperty(PropertyNode $node)
    {
        foreach ($node->props as $prop) {
            [$property, $errors] = $this->getPropertyReflectionFromParserProperty($node, $prop);

            if ($this->context->getFilter()->acceptProperty($property)) {
                $this->context->getClass()->addProperty($property);

                if ($errors) {
                    $this->context->addErrors((string) $property, $prop->getLine(), $errors);
                }
            }
        }
    }

    /**
     * @return array<int,PropertyReflection|string[]>
     * @phpstan-return array{PropertyReflection,string[]}
     */
    protected function getPropertyReflectionFromParserProperty(PropertyNode $node, PropertyProperty $prop): array
    {
        $property = new PropertyReflection($prop->name->toString(), $prop->getLine());
        $property->setModifiers($node->flags);

        $property->setDefault($prop->default);

        $docComment = $node->getDocComment();
        $docComment = $docComment === null ? null : $docComment->__toString();
        $comment    = $this->context->getDocBlockParser()->parse($docComment, $this->context, $property);
        $property->setDocComment($docComment);
        $property->setShortDesc($comment->getShortDesc());
        $property->setLongDesc($comment->getLongDesc());
        $property->setSee($this->resolveSee($comment->getTag('see')));
        if ($errors = $comment->getErrors()) {
            $property->setErrors($errors);
        } else {
            $this->addTagFromCommentToMethod('var', $comment, $property, $errors);
            $otherTags = $comment->getOtherTags();
            if (isset($otherTags['readonly'])) {
                $property->setReadOnly(true);
            }
            $property->setTags($otherTags);
        }
        $property->setModifiersFromTags();

        return [$property, $errors];
    }

    protected function addTraitUse(TraitUseNode $node)
    {
        foreach ($node->traits as $trait) {
            $this->context->getClass()->addTrait((string) $trait);
        }
    }

    protected function addConstant(ClassConstNode $node)
    {
        foreach ($node->consts as $const) {
            $constant   = new ConstantReflection($const->name->toString(), $const->getLine());
            $docComment = $node->getDocComment();
            $docComment = $docComment === null ? null : $docComment->__toString();
            $comment    = $this->context->getDocBlockParser()->parse($docComment, $this->context, $constant);
            $constant->setDocComment($docComment);
            $constant->setShortDesc($comment->getShortDesc());
            $constant->setLongDesc($comment->getLongDesc());
            $constant->setTags($comment->getOtherTags());
            $constant->setModifiers($node->flags);
            $constant->setModifiersFromTags();

            $this->context->getClass()->addConstant($constant);
        }
    }

    /**
     * @param array[] $tags
     * @phpstan-param array{array{string,bool}|empty-array|string,string,string} $tags
     *
     * @return array|null
     * @phpstan-return non-empty-array{non-empty-array{string,bool},string,string}|null
     */
    private function findParameterInTags(array $tags, string $tagName): ?array
    {
        foreach ($tags as $tag) {
            if (! is_array($tag)) {
                continue;
            }
            if (count($tag) < 2) {
                continue;
            }
            if ($tag[1] === $tagName) {
                return $tag;
            }
        }
        return null;
    }

    /**
     * @param array[] $tags All the tags
     *
     * @return string[] The invalid tags
     */
    private function getInvalidTags(array $tags): array
    {
        $invalidTags = [];
        foreach ($tags as $tag) {
            if (! is_array($tag)) {
                $invalidTags[] = $tag;
            }
        }
        return $invalidTags;
    }

    /**
     * @param FunctionReflection|MethodReflection $method
     * @param array[]                             $tags
     * @return string[]
     */
    protected function updateMethodParametersFromTags(Reflection $method, array $tags): array
    {
        $errors = [];

        // bypass if there is no @param tags defined (@param tags are optional)
        if (!count($tags)) {
            return $errors;
        }

        /** @var Reflection[] $parameters */
        $parameters = $method->getParameters();

        foreach ($parameters as $parameter) {
            $tag = $this->findParameterInTags($tags, $parameter->getName());
            if (! $parameter->hasHint() && $tag === null) {
                $errors[] = sprintf(
                    'The "%s" parameter of the method "%s" is missing a @param tag',
                    $parameter->getName(),
                    $method->getName()
                );
                continue;
            }

            if ($tag !== null) {
                $parameter->setShortDesc($tag[2]);
                if (! $parameter->hasHint()) {
                    $parameter->setHint($this->resolveHint($tag[0]));
                }
            }
        }

        if (count($tags) > count($parameters)) {
            $errors[] = sprintf(
                'The method "%s" has "%d" @param tags but only "%d" where expected.',
                $method->getName(),
                count($tags),
                count($method->getParameters())
            );
        }

        $invalidTags = $this->getInvalidTags($tags);
        if (count($invalidTags) > 0) {
            $errors[] = sprintf(
                'The method "%s" has "%d" invalid @param tags.',
                $method->getName(),
                count($invalidTags)
            );
            foreach ($invalidTags as $invalidTag) {
                $errors[] = sprintf(
                    'Invalid @param tag on "%s": "%s"',
                    $method->getName(),
                    $invalidTag
                );
            }
        }
        return $errors;
    }

    protected function resolveHint(array $hints): array
    {
        foreach ($hints as $i => $hint) {
            $hints[$i] = [$this->resolveAlias($hint[0]), $hint[1]];
        }

        return $hints;
    }

    protected function resolveAlias($alias)
    {
        // not a class
        if (Project::isPhpTypeHint($alias)) {
            return $alias;
        }

        // FQCN
        if ('\\' == substr($alias, 0, 1)) {
            return $alias;
        }

        $class = $this->context->getClass();

        // A class MIGHT or MIGHT NOT be present in context.
        // It is not present in cases, where eg. `@see` tag refers to non existing class/method.
        // We may want to run class related checks only, if class is actually present.
        if ($class) {
            // special aliases
            if ('self' === $alias || 'static' === $alias || '\$this' === $alias) {
                return $class->getName();
            }

            // an alias defined by a use statement
            $aliases = $class->getAliases();

            if (isset($aliases[$alias])) {
                return $aliases[$alias];
            }

            // a class in the current class namespace
            return $class->getNamespace() . '\\' . $alias;
        }

        return $alias;
    }

    /**
     * @return array<int,array<int,string|false>>
     */
    protected function resolveSee(array $see): array
    {
        $return = [];
        foreach ($see as $seeEntry) {
            // Example: @see Net_Sample::$foo, Net_Other::someMethod()
            if (is_string($seeEntry)) {// Support bad formatted @see tags
                $seeEntries = explode(',', $seeEntry);
                foreach ($seeEntries as $entry) {
                    $return[] = $this->getParsedSeeEntry(trim($entry, " \n\t"), '');
                }
                continue;
            }
            $reference   = $seeEntry[1];
            $description = $seeEntry[2] ?? '';
            $return[]    = $this->getParsedSeeEntry($reference, $description);
        }

        return $return;
    }

    /**
     * @return array<int,string|false>
     */
    protected function getParsedSeeEntry(string $reference, string $description): array
    {
        $matches = [];
        if ((bool) preg_match('/^[\w]+:\/\/.+$/', $reference)) { //URL
            return [
                $reference,
                $description,
                false,
                false,
                $reference,
            ];
        } elseif ((bool) preg_match('/(.+)\:\:(.+)\(.*\)/', $reference, $matches)) { //Method
            return [
                $reference,
                $description,
                $this->resolveAlias($matches[1]),
                $matches[2],
                false,
            ];
        } else { // We assume, that this is a class reference.
            return [
                $reference,
                $description,
                $this->resolveAlias($reference),
                false,
                false,
            ];
        }
    }

}
