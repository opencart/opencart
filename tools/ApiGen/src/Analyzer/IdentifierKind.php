<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;


enum IdentifierKind
{
	case Keyword;
	case ClassLike;
	case Generic;
	case Alias;
}
