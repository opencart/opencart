<?php declare(strict_types = 1);

namespace ApiGen;

use Composer\InstalledVersions;
use ErrorException;
use Nette\DI\Compiler;
use Nette\DI\Config\Loader;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\Extensions\ExtensionsExtension;
use Nette\DI\Helpers as DIHelpers;
use Nette\Schema\Expect;
use Nette\Schema\Helpers as SchemaHelpers;
use Nette\Schema\Processor;
use Nette\Utils\FileSystem;
use Symfony\Component\Console\Style\OutputStyle;

use function array_keys;
use function array_map;
use function assert;
use function count;
use function dirname;
use function error_reporting;
use function getcwd;
use function ini_set;
use function is_array;
use function is_file;
use function is_int;
use function method_exists;
use function set_error_handler;
use function str_starts_with;
use function sys_get_temp_dir;

use const E_ALL;
use const E_DEPRECATED;
use const E_USER_DEPRECATED;
use const PHP_RELEASE_VERSION;
use const PHP_VERSION_ID;


class Bootstrap
{
	public static function configureErrorHandling(): void
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 'stderr');
		ini_set('log_errors', '0');

		set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
			if (error_reporting() & $severity && $severity !== E_DEPRECATED && $severity !== E_USER_DEPRECATED) {
				throw new ErrorException($message, 0, $severity, $file, $line);

			} else {
				return false;
			}
		});
	}


	/**
	 * @param string[] $configPaths indexed by []
	 */
	public static function createApiGen(OutputStyle $output, array $parameters, array $configPaths): ApiGen
	{
		$workingDir = getcwd();
		$tempDir = sys_get_temp_dir() . '/apigen';
		$version = InstalledVersions::getPrettyVersion('apigen/apigen');

		if ($workingDir === false) {
			throw new \RuntimeException('Unable to get current working directory.');
		}

		$autoDiscoveryPath = "$workingDir/apigen.neon";
		if (count($configPaths) === 0 && is_file($autoDiscoveryPath)) {
			$output->text("Using configuration file $autoDiscoveryPath.\n");
			$configPaths[] = $autoDiscoveryPath;
		}

		$config = self::mergeConfigs(
			['parameters' => ['workingDir' => $workingDir, 'tempDir' => $tempDir, 'version' => $version]],
			self::loadConfig(__DIR__ . '/../apigen.neon'),
			...array_map(self::loadConfig(...), $configPaths),
			...[['parameters' => self::resolvePaths($parameters, $workingDir)]],
		);

		$parameters = $config['parameters'];
		unset($config['parameters']);

		self::validateParameters($parameters);
		$parameters = DIHelpers::expand($parameters, $parameters);
		$containerLoader = new ContainerLoader($parameters['tempDir'], autoRebuild: true);

		$containerGenerator = function (Compiler $compiler) use ($config, $parameters): void {
			$compiler->addExtension('extensions', new ExtensionsExtension);
			$compiler->addConfig($config);
			$compiler->setDynamicParameterNames(array_keys($parameters));
		};

		$containerKey = [
			$config,
			PHP_VERSION_ID - PHP_RELEASE_VERSION,
		];

		/** @var class-string<Container> $containerClassName */
		$containerClassName = $containerLoader->load($containerGenerator, $containerKey);

		$container = new $containerClassName($parameters);
		assert($container instanceof Container);
		assert(method_exists($container, 'initialize'));

		$container->addService('symfonyConsole.output', $output);
		$container->initialize();
		ini_set('memory_limit', $container->parameters['memoryLimit']);

		$apiGen = $container->getByType(ApiGen::class) ?? throw new \LogicException();
		assert($apiGen instanceof ApiGen);

		return $apiGen;
	}


	protected static function validateParameters(array $parameters): void
	{
		$schema = Expect::structure([
			// input
			'paths' => Expect::listOf('string')->min(1),
			'include' => Expect::listOf('string'),
			'exclude' => Expect::listOf('string'),

			// analysis
			'excludeProtected' => Expect::bool(),
			'excludePrivate' => Expect::bool(),
			'excludeTagged' => Expect::listOf('string'),

			// output
			'outputDir' => Expect::string(),
			'themeDir' => Expect::string()->nullable(),
			'title' => Expect::string(),
			'version' => Expect::string(),
			'baseUrl' => Expect::string(),

			// system
			'workingDir' => Expect::string(),
			'tempDir' => Expect::string(),
			'workerCount' => Expect::int()->min(1),
			'memoryLimit' => Expect::string(),
		]);

		(new Processor)->process($schema, $parameters);
	}


	protected static function mergeConfigs(array...$configs): array
	{
		$mergedConfig = [];

		foreach ($configs as $config) {
			foreach ($config['parameters'] ?? [] as $key => $value) {
				if (is_array($value)) {
					$config['parameters'][$key][SchemaHelpers::PREVENT_MERGING] = true;
				}
			}

			$mergedConfig = SchemaHelpers::merge($config, $mergedConfig);
			assert(is_array($mergedConfig));
		}

		return $mergedConfig;
	}


	protected static function loadConfig(string $path): array
	{
		$data = (new Loader)->load($path);
		$data['parameters'] = self::resolvePaths($data['parameters'] ?? [], Helpers::realPath(dirname($path)));

		return $data;
	}


	protected static function resolvePaths(array $parameters, string $base): array
	{
		foreach (['tempDir', 'workingDir', 'outputDir', 'themeDir'] as $parameterKey) {
			if (isset($parameters[$parameterKey])) {
				$parameters[$parameterKey] = self::resolvePath($parameters[$parameterKey], $base);
			}
		}

		foreach ($parameters['paths'] ?? [] as $i => $path) {
			if (is_int($i)) {
				$parameters['paths'][$i] = self::resolvePath($parameters['paths'][$i], $base);
			}
		}

		return $parameters;
	}


	protected static function resolvePath(string $path, string $base): string
	{
		return (FileSystem::isAbsolute($path) || str_starts_with($path, '%'))
			? $path
			: FileSystem::joinPaths($base, $path);
	}
}
