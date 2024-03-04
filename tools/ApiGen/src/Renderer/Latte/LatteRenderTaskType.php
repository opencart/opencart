<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;


enum LatteRenderTaskType
{
	case Asset;
	case ElementsJs;
	case Index;
	case Tree;
	case Sitemap;
	case Namespace;
	case ClassLike;
	case Function;
	case Source;
}
