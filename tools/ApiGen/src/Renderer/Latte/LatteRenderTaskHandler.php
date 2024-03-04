<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Index\Index;
use ApiGen\Renderer\Filter;
use ApiGen\Renderer\Latte\Template\ClassLikeTemplate;
use ApiGen\Renderer\Latte\Template\ConfigParameters;
use ApiGen\Renderer\Latte\Template\FunctionTemplate;
use ApiGen\Renderer\Latte\Template\IndexTemplate;
use ApiGen\Renderer\Latte\Template\LayoutParameters;
use ApiGen\Renderer\Latte\Template\NamespaceTemplate;
use ApiGen\Renderer\Latte\Template\SitemapTemplate;
use ApiGen\Renderer\Latte\Template\SourceTemplate;
use ApiGen\Renderer\Latte\Template\TreeTemplate;
use ApiGen\Renderer\UrlGenerator;
use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;
use Latte;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use ReflectionClass;

use function array_key_first;
use function assert;
use function basename;
use function lcfirst;
use function sprintf;
use function substr;


/**
 * @implements TaskHandler<LatteRenderTask, string>
 */
class LatteRenderTaskHandler implements TaskHandler
{
	protected Index $index;

	protected ConfigParameters $config;

	public function __construct(
		protected Latte\Engine $latte,
		protected UrlGenerator $urlGenerator,
		protected Filter $filter,
		protected string $outputDir,
		mixed $context,
	) {
		assert($context instanceof LatteRenderTaskContext);
		$this->index = $context->index;
		$this->config = $context->config;
	}


	/**
	 * @param  LatteRenderTask $task
	 */
	public function handle(Task $task): string
	{
		return match ($task->type) {
			LatteRenderTaskType::Asset => $this->copyAsset($task->key),

			LatteRenderTaskType::ElementsJs => $this->renderElementsJs(),
			LatteRenderTaskType::Index => $this->renderIndex(),
			LatteRenderTaskType::Tree => $this->renderTree(),
			LatteRenderTaskType::Sitemap => $this->renderSitemap(),

			LatteRenderTaskType::Namespace => $this->renderNamespace($task->key),
			LatteRenderTaskType::ClassLike => $this->renderClassLike($task->key),
			LatteRenderTaskType::Function => $this->renderFunction($task->key),
			LatteRenderTaskType::Source => $this->renderSource($task->key),
		};
	}


	protected function copyAsset(string $key): string
	{
		$assetPath = $this->urlGenerator->getAssetPath(basename($key));
		FileSystem::copy($key, "$this->outputDir/$assetPath");

		return $assetPath;
	}


	protected function renderElementsJs(): string
	{
		$elements = [];

		foreach ($this->index->namespace as $namespace) {
			if ($this->filter->filterNamespacePage($namespace)) {
				$elements['namespace'][] = [$namespace->name->full, $this->urlGenerator->getNamespaceUrl($namespace)];
			}
		}

		foreach ($this->index->classLike as $classLike) {
			if (!$this->filter->filterClassLikePage($classLike)) {
				continue;
			}

			$members = [];

			foreach ($classLike->constants as $constant) {
				$members['constant'][] = [$constant->name, $this->urlGenerator->getMemberAnchor($constant)];
			}

			foreach ($classLike->properties as $property) {
				$members['property'][] = [$property->name, $this->urlGenerator->getMemberAnchor($property)];
			}

			foreach ($classLike->methods as $method) {
				$members['method'][] = [$method->name, $this->urlGenerator->getMemberAnchor($method)];
			}

			$elements['classLike'][] = [$classLike->name->full, $this->urlGenerator->getClassLikeUrl($classLike), $members];
		}

		foreach ($this->index->function as $function) {
			if ($this->filter->filterFunctionPage($function)) {
				$elements['function'][] = [$function->name->full, $this->urlGenerator->getFunctionUrl($function)];
			}
		}

		$js = sprintf('window.ApiGen?.resolveElements(%s)', Json::encode($elements));
		$assetPath = $this->urlGenerator->getAssetPath('elements.js');
		FileSystem::write("$this->outputDir/$assetPath", $js);

		return $assetPath;
	}


	protected function renderIndex(): string
	{
		return $this->renderTemplate($this->urlGenerator->getIndexPath(), new IndexTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters(activePage: 'index'),
		));
	}


	protected function renderTree(): string
	{
		return $this->renderTemplate($this->urlGenerator->getTreePath(), new TreeTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters(activePage: 'tree'),
		));
	}


	protected function renderSitemap(): string
	{
		return $this->renderTemplate($this->urlGenerator->getSitemapPath(), new SitemapTemplate(
			index: $this->index,
			config: $this->config,
		));
	}


	protected function renderNamespace(string $key): string
	{
		$info = $this->index->namespace[$key];

		return $this->renderTemplate($this->urlGenerator->getNamespacePath($info), new NamespaceTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters('namespace', activeNamespace: $info, noindex: !$info->primary),
			namespace: $info,
		));
	}


	protected function renderClassLike(string $key): string
	{
		$info = $this->index->classLike[$key];
		$activeNamespace = $this->index->namespace[$info->name->namespaceLower];

		return $this->renderTemplate($this->urlGenerator->getClassLikePath($info), new ClassLikeTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters('classLike', $activeNamespace, $info, noindex: !$info->primary),
			classLike: $info,
		));
	}


	protected function renderFunction(string $key): string
	{
		$info = $this->index->function[$key];
		$activeNamespace = $this->index->namespace[$info->name->namespaceLower];

		return $this->renderTemplate($this->urlGenerator->getFunctionPath($info), new FunctionTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters('function', $activeNamespace, $info, noindex: !$info->primary),
			function: $info,
		));
	}


	protected function renderSource(string $key): string
	{
		$info = $this->index->files[$key];
		$activeElement = $info->classLike[array_key_first($info->classLike)] ?? $info->function[array_key_first($info->function)] ?? null;
		$activeNamespace = $activeElement ? $this->index->namespace[$activeElement->name->namespaceLower] : null;

		return $this->renderTemplate($this->urlGenerator->getSourcePath($info->path), new SourceTemplate(
			index: $this->index,
			config: $this->config,
			layout: new LayoutParameters('source', $activeNamespace, $activeElement, noindex: !$info->primary),
			path: $info->path,
		));
	}


	protected function renderTemplate(string $outputPath, object $template): string
	{
		$className = (new ReflectionClass($template))->getShortName();
		$lattePath = 'pages/' . lcfirst(substr($className, 0, -8)) . '.latte';
		FileSystem::write("$this->outputDir/$outputPath", $this->latte->renderToString($lattePath, $template));

		return $outputPath;
	}
}
