<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use Latte;
use Throwable;

use function array_filter;


class LatteEngineFactory
{
	public function __construct(
		protected LatteExtension $extension,
		protected ?string $tempDir,
		protected ?string $themeDir,
	) {
	}


	public function create(): Latte\Engine
	{
		$latte = new Latte\Engine();
		$latte->setStrictTypes();
		$latte->setExceptionHandler(fn(Throwable $e) => throw $e);
		$latte->setTempDirectory($this->tempDir);
		$latte->setLoader(new LatteCascadingLoader(array_filter([$this->themeDir, __DIR__ . '/Template'])));
		$latte->addExtension($this->extension);

		return $latte;
	}
}
