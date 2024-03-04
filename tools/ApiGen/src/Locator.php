<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Info\ClassLikeReferenceInfo;
use Composer\Autoload\ClassLoader;
use JetBrains\PHPStormStub\PhpStormStubsMap;
use League;
use PHPStan\Php8StubsMap;
use Symfony\Component\Console\Style\OutputStyle;

use function dirname;
use function implode;
use function is_dir;
use function strtolower;

use const PHP_VERSION_ID;


class Locator
{
	/**
	 * @param string[] $stubsMap indexed by [classLikeName]
	 */
	public function __construct(
		protected array $stubsMap,
		protected ClassLoader $classLoader,
	) {
	}


	public static function create(OutputStyle $output, string $projectDir): self
	{
		return new self(
			self::createStubsMap(),
			self::createComposerClassLoader($output, $projectDir),
		);
	}


	/**
	 * @return string[] indexed by [classLikeName]
	 */
	protected static function createStubsMap(): array
	{
		$stubsMap = [];

		$phpStormStubsDir = dirname(Helpers::classLikePath(PhpStormStubsMap::class));
		foreach (PhpStormStubsMap::CLASSES as $class => $path) {
			$stubsMap[strtolower($class)] = "$phpStormStubsDir/$path";
		}

		$phpStanStubsDir = dirname(Helpers::classLikePath(Php8StubsMap::class));
		foreach ((new Php8StubsMap(PHP_VERSION_ID))->classes as $class => $path) {
			$stubsMap[$class] = "$phpStanStubsDir/$path";
		}

		return $stubsMap;
	}


	protected static function createComposerClassLoader(OutputStyle $output, string $projectDir): ClassLoader
	{
		$vendorDir = "$projectDir/vendor";
		$loader = new ClassLoader();

		if (!is_dir($vendorDir)) {
			$output->warning(implode("\n", [
				"Unable to use Composer autoloader for finding dependencies because directory",
				"$vendorDir does not exist. Use --worker-dir to specify directory where vendor directory is located",
			]));

		} else {
			$output->text("Using Composer autoloader for finding dependencies in $vendorDir.\n");
			$loader->addClassMap(require "$vendorDir/composer/autoload_classmap.php");

			foreach (require "$vendorDir/composer/autoload_namespaces.php" as $prefix => $paths) {
				$loader->set($prefix, $paths);
			}

			foreach (require "$vendorDir/composer/autoload_psr4.php" as $prefix => $paths) {
				$loader->setPsr4($prefix, $paths);
			}
		}

		return $loader;
	}


	public function locate(ClassLikeReferenceInfo $name): ?string
	{
		return $this->classLoader->findFile($name->full) ?: $this->stubsMap[$name->fullLower] ?? null;
	}
}
