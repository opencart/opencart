<?php declare(strict_types = 1);

namespace ApiGen\Info;


enum GenericParameterVariance
{
	case Invariant;
	case Covariant;
	case Contravariant;
}
