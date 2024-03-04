<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Renderer\Filter;
use ApiGen\Renderer\UrlGenerator;
use Latte;


class LatteExtension extends Latte\Extension
{
	public function __construct(
		protected LatteFunctions $functions,
		protected Filter $filter,
		protected UrlGenerator $url,
	) {
	}


	public function getFunctions(): array
	{
		return [
			'isClass' => $this->functions->isClass(...),
			'isInterface' => $this->functions->isInterface(...),
			'isTrait' => $this->functions->isTrait(...),
			'isEnum' => $this->functions->isEnum(...),

			'textWidth' => $this->functions->textWidth(...),
			'htmlWidth' => $this->functions->htmlWidth(...),
			'highlight' => $this->functions->highlight(...),
			'shortDescription' => $this->functions->shortDescription(...),
			'longDescription' => $this->functions->longDescription(...),

			'treePageExists' => $this->filter->filterTreePage(...),
			'namespacePageExists' => $this->filter->filterNamespacePage(...),
			'classLikePageExists' => $this->filter->filterClassLikePage(...),
			'functionPageExists' => $this->filter->filterFunctionPage(...),
			'sourcePageExists' => $this->filter->filterSourcePage(...),

			'elementName' => $this->functions->elementName(...),
			'elementShortDescription' => $this->functions->elementShortDescription(...),
			'elementPageExists' => $this->functions->elementPageExists(...),
			'elementUrl' => $this->functions->elementUrl(...),

			'relativePath' => $this->url->getRelativePath(...),
			'assetUrl' => $this->url->getAssetUrl(...),
			'indexUrl' => $this->url->getIndexUrl(...),
			'treeUrl' => $this->url->getTreeUrl(...),
			'namespaceUrl' => $this->url->getNamespaceUrl(...),
			'classLikeUrl' => $this->url->getClassLikeUrl(...),
			'classLikeSourceUrl' => $this->url->getClassLikeSourceUrl(...),
			'memberUrl' => $this->url->getMemberUrl(...),
			'memberAnchor' => $this->url->getMemberAnchor(...),
			'memberSourceUrl' => $this->url->getMemberSourceUrl(...),
			'aliasUrl' => $this->url->getAliasUrl(...),
			'aliasAnchor' => $this->url->getAliasAnchor(...),
			'aliasSourceUrl' => $this->url->getAliasSourceUrl(...),
			'functionUrl' => $this->url->getFunctionUrl(...),
			'functionSourceUrl' => $this->url->getFunctionSourceUrl(...),
			'parameterAnchor' => $this->url->getParameterAnchor(...),
			'sourceUrl' => $this->url->getSourceUrl(...),
		];
	}


	public function getTags(): array
	{
		return [
			'pre' => LattePreNode::create(...),
		];
	}
}
