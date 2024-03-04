<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Index\Index;
use ApiGen\Index\NamespaceIndex;
use ApiGen\Info\ClassInfo;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ConstantReferenceInfo;
use ApiGen\Info\ElementInfo;
use ApiGen\Info\EnumInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\FunctionReferenceInfo;
use ApiGen\Info\InterfaceInfo;
use ApiGen\Info\MemberReferenceInfo;
use ApiGen\Info\MethodReferenceInfo;
use ApiGen\Info\PropertyReferenceInfo;
use ApiGen\Info\TraitInfo;
use ApiGen\Renderer\Filter;
use ApiGen\Renderer\SourceHighlighter;
use ApiGen\Renderer\UrlGenerator;
use Latte\Runtime\Html;
use League\CommonMark\ConverterInterface;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use ReflectionFunction;

use function function_exists;
use function get_debug_type;
use function html_entity_decode;
use function implode;
use function is_array;
use function is_string;
use function sprintf;
use function str_contains;
use function strip_tags;
use function strtr;
use function substr_count;

use const ENT_HTML5;
use const ENT_QUOTES;


class LatteFunctions
{
	public function __construct(
		protected Filter $filter,
		protected UrlGenerator $url,
		protected SourceHighlighter $sourceHighlighter,
		protected ConverterInterface $markdown,
	) {
	}


	public function isClass(ClassLikeInfo $info): bool
	{
		return $info instanceof ClassInfo;
	}


	public function isInterface(ClassLikeInfo $info): bool
	{
		return $info instanceof InterfaceInfo;
	}


	public function isTrait(ClassLikeInfo $info): bool
	{
		return $info instanceof TraitInfo;
	}


	public function isEnum(ClassLikeInfo $info): bool
	{
		return $info instanceof EnumInfo;
	}


	public function textWidth(string $text): int
	{
		return Strings::length($text) + 3 * substr_count($text, "\t");
	}


	public function htmlWidth(Html $html): int
	{
		$text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
		return $this->textWidth($text);
	}


	public function highlight(string $path): Html
	{
		return new Html($this->sourceHighlighter->highlight($path));
	}


	/**
	 * @param  PhpDocTextNode[]|string $nodes indexed by []
	 */
	public function shortDescription(Index $index, ?ClassLikeInfo $classLike, array|string $nodes): Html
	{
		$description = is_array($nodes) ? $this->descriptionText($index, $classLike, $nodes) : $nodes;
		$base = Strings::before($description, "\n\n") ?? $description;
		$html = $this->markdown->convert($base)->getContent();
		return new Html(Strings::before($html, '<p>', 2) ?? $html);
	}


	/**
	 * @param  PhpDocTextNode[]|string $nodes indexed by []
	 */
	public function longDescription(Index $index, ?ClassLikeInfo $classLike, array|string $nodes): Html
	{
		$description = is_array($nodes) ? $this->descriptionText($index, $classLike, $nodes) : $nodes;
		return new Html($this->markdown->convert($description)->getContent());
	}


	public function elementName(ElementInfo $info): string
	{
		if ($info instanceof ClassLikeInfo || $info instanceof FunctionInfo) {
			return $info->name->short;

		} elseif ($info instanceof NamespaceIndex) {
			return $info->name->full === '' ? 'none' : $info->name->full;

		} else {
			throw new \LogicException(sprintf('Unexpected element type %s', get_debug_type($info)));
		}
	}


	public function elementShortDescription(Index $index, ?ClassLikeInfo $classLike, ElementInfo $info): Html
	{
		if ($info instanceof ClassLikeInfo || $info instanceof FunctionInfo) {
			return $this->shortDescription($index, $classLike, $info->description);

		} elseif ($info instanceof NamespaceIndex) {
			return new Html('');

		} else {
			throw new \LogicException(sprintf('Unexpected element type %s', get_debug_type($info)));
		}
	}


	public function elementPageExists(ElementInfo $info): bool
	{
		if ($info instanceof ClassLikeInfo) {
			return $this->filter->filterClassLikePage($info);

		} elseif ($info instanceof NamespaceIndex) {
			return $this->filter->filterNamespacePage($info);

		} elseif ($info instanceof FunctionInfo) {
			return $this->filter->filterFunctionPage($info);

		} else {
			throw new \LogicException(sprintf('Unexpected element type %s', get_debug_type($info)));
		}
	}


	public function elementUrl(ElementInfo $info): string
	{
		if ($info instanceof ClassLikeInfo) {
			return $this->url->getClassLikeUrl($info);

		} elseif ($info instanceof NamespaceIndex) {
			return $this->url->getNamespaceUrl($info);

		} elseif ($info instanceof FunctionInfo) {
			return $this->url->getFunctionUrl($info);

		} else {
			throw new \LogicException(sprintf('Unexpected element type %s', get_debug_type($info)));
		}
	}


	/**
	 * @param  PhpDocTextNode[] $nodes indexed by []
	 */
	protected function descriptionText(Index $index, ?ClassLikeInfo $scope, array $nodes): string
	{
		$text = [];

		foreach ($nodes as $node) {
			$url = null;
			$title = null;

			foreach ($node->getAttribute('targets') ?? [] as $target) {
				if ($target instanceof ClassLikeReferenceInfo) {
					$classLike = $target->resolve($index, $scope);

					if ($classLike !== null && $this->filter->filterClassLikePage($classLike)) {
						$url = $this->url->getClassLikeUrl($classLike);
						$title = $classLike->name->full;
						break;
					}

				} elseif ($target instanceof MemberReferenceInfo) {
					$classLike = $target->classLike->resolve($index, $scope);

					if ($classLike === null || !$this->filter->filterClassLikePage($classLike)) {
						continue;

					} elseif ($target instanceof ConstantReferenceInfo) {
						$member = $classLike->constants[$target->name] ?? null;
						$memberLabel = $member?->name;

					} elseif ($target instanceof PropertyReferenceInfo) {
						$member = $classLike->properties[$target->name] ?? null;
						$memberLabel = $member !== null ? '$' . $member->name : null;

					} elseif ($target instanceof MethodReferenceInfo) {
						$member = $classLike->methods[$target->nameLower] ?? null;
						$memberLabel = $member !== null ? $member->name . '()' : null;

					} else {
						throw new \LogicException('Unexpected member reference type: ' . get_debug_type($target));
					}

					if ($member !== null && $memberLabel !== null) {
						$url = $this->url->getMemberUrl($classLike, $member);
						$title = $classLike->name->full . '::' . $memberLabel;
						break;
					}

				} elseif ($target instanceof FunctionReferenceInfo) {
					$function = $index->function[$target->fullLower] ?? null;

					if ($function !== null && $this->filter->filterFunctionPage($function)) {
						$url = $this->url->getFunctionUrl($function);
						$title = $function->name->full . '()';
						break;

					} elseif (function_exists($target->fullLower) && !str_contains($target->fullLower, '\\') && (new ReflectionFunction($target->fullLower))->isInternal()) {
						$url = sprintf('https://www.php.net/manual/en/function.%s', strtr($target->fullLower, '_', '-'));
						$title = $target->full . '()';
						break;
					}

				} elseif (is_string($target) && Validators::isUrl($target)) {
					$url = $target;
					break;
				}
			}

			if ($url === null) {
				$text[] = $node->text;

			} elseif ($title === null || $title === $node->text) {
				$text[] = sprintf('[%s](%s)', $node->text, $url);

			} else {
				$text[] = sprintf('[%s](%s "%s")', $node->text, $url, $title);
			}
		}

		return implode(' ', $text);
	}
}
