<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use Latte;

use function is_file;


class LatteCascadingLoader implements Latte\Loader
{
	/**
	 * @param string[] $baseDirs
	 */
	public function __construct(
		protected array $baseDirs,
		protected Latte\Loader $inner = new Latte\Loaders\FileLoader(),
	) {
	}


	public function getContent(string $name): string
	{
		foreach ($this->baseDirs as $baseDir) {
			$path = $baseDir . '/' . $name;

			if (is_file($path)) {
				return $this->inner->getContent($path);
			}
		}

		throw new Latte\RuntimeException("Missing template file '$name'.");
	}


	public function isExpired(string $name, int $time): bool
	{
		foreach ($this->baseDirs as $baseDir) {
			$path = $baseDir . '/' . $name;

			if (is_file($path)) {
				return $this->inner->isExpired($path, $time);
			}
		}

		throw new Latte\RuntimeException("Missing template file '$name'.");
	}


	public function getReferredName(string $name, string $referringName): string
	{
		return $this->inner->getReferredName($name, $referringName);
	}


	public function getUniqueId(string $name): string
	{
		foreach ($this->baseDirs as $baseDir) {
			$path = $baseDir . '/' . $name;

			if (is_file($path)) {
				return $this->inner->getUniqueId($path);
			}
		}

		throw new Latte\RuntimeException("Missing template file '$name'.");
	}
}
