<?php
declare(strict_types=1);

namespace StubTests\Parsers;

use LogicException;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BitwiseOr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeVisitorAbstract;
use RuntimeException;
use SplFileInfo;
use UnexpectedValueException;
use function array_slice;
use function count;

class MetaExpectedArgumentsCollector extends NodeVisitorAbstract
{
    private const EXPECTED_ARGUMENTS = 'expectedArguments';
    private const EXPECTED_RETURN_VALUES = 'expectedReturnValues';
    private const REGISTER_ARGUMENTS_SET_NAME = 'registerArgumentsSet';

    /**
     * @var ExpectedFunctionArgumentsInfo[]
     */
    private array $expectedArgumentsInfos = [];

    /**
     * @var string[]
     */
    private array $registeredArgumentsSet = [];

    /**
     * @throws LogicException
     * @throws UnexpectedValueException
     */
    public function __construct()
    {
        StubParser::processStubs(
            $this,
            null,
            fn (SplFileInfo $file): bool => $file->getFilename() === '.phpstorm.meta.php'
        );
    }

    /**
     * @throws RuntimeException
     */
    public function enterNode(Node $node): void
    {
        if ($node instanceof FuncCall) {
            $name = (string)$node->name;
            if ($name === self::EXPECTED_ARGUMENTS) {
                $args = $node->args;
                if (count($args) < 3) {
                    throw new RuntimeException('Expected at least 3 arguments for expectedArguments call');
                }
                $this->expectedArgumentsInfos[] = self::getExpectedArgumentsInfo($args[0]->value, array_slice($args, 2), $args[1]->value->value);
            } elseif ($name === self::REGISTER_ARGUMENTS_SET_NAME) {
                $args = $node->args;
                if (count($args) < 2) {
                    throw new RuntimeException('Expected at least 2 arguments for registerArgumentsSet call');
                }
                $this->expectedArgumentsInfos[] = self::getExpectedArgumentsInfo(null, array_slice($args, 1));
                $name = $args[0]->value->value;
                $this->registeredArgumentsSet[] = $name;
            } elseif ($name === self::EXPECTED_RETURN_VALUES) {
                $args = $node->args;
                if (count($args) < 2) {
                    throw new RuntimeException('Expected at least 2 arguments for expectedReturnValues call');
                }
                $this->expectedArgumentsInfos[] = self::getExpectedArgumentsInfo($args[0]->value, array_slice($args, 1));
            }
        }
    }

    /**
     * @return ExpectedFunctionArgumentsInfo[]
     */
    public function getExpectedArgumentsInfos(): array
    {
        return $this->expectedArgumentsInfos;
    }

    /**
     * @return string[]
     */
    public function getRegisteredArgumentsSet(): array
    {
        return $this->registeredArgumentsSet;
    }

    /**
     * @param Expr[] $expressions
     * @return Expr[]
     */
    private static function unpackArguments(array $expressions): array
    {
        $result = [];
        foreach ($expressions as $expr) {
            if ($expr instanceof BitwiseOr) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $result = array_merge($result, self::unpackArguments([$expr->left, $expr->right]));
            } else {
                $result[] = $expr;
            }
        }
        return $result;
    }

    /**
     * @param Expr|null $functionReference
     * @param Arg[] $args
     * @param int $index
     * @return ExpectedFunctionArgumentsInfo
     */
    private static function getExpectedArgumentsInfo(?Expr $functionReference, array $args, int $index = -1): ExpectedFunctionArgumentsInfo
    {
        $expressions = array_map(fn (Arg $arg): Expr => $arg->value, $args);
        return new ExpectedFunctionArgumentsInfo($functionReference, self::unpackArguments($expressions), $index);
    }
}
