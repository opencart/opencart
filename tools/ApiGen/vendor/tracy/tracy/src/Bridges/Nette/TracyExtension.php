<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tracy\Bridges\Nette;

use Nette;
use Nette\DI\Definitions\Statement;
use Nette\Schema\Expect;
use Tracy;


/**
 * Tracy extension for Nette DI.
 */
class TracyExtension extends Nette\DI\CompilerExtension
{
	private const ErrorSeverityPattern = 'E_(?:ALL|PARSE|STRICT|RECOVERABLE_ERROR|(?:CORE|COMPILE)_(?:ERROR|WARNING)|(?:USER_)?(?:ERROR|WARNING|NOTICE|DEPRECATED))';


	public function __construct(
		private bool $debugMode = false,
		private bool $cliMode = false,
	) {
	}


	public function getConfigSchema(): Nette\Schema\Schema
	{
		$errorSeverity = Expect::string()->pattern(self::ErrorSeverityPattern);
		$errorSeverityExpr = Expect::string()->pattern('(' . self::ErrorSeverityPattern . '|[ &|~()])+');

		return Expect::structure([
			'email' => Expect::anyOf(Expect::email(), Expect::listOf('email'))->dynamic(),
			'fromEmail' => Expect::email()->dynamic(),
			'emailSnooze' => Expect::string()->dynamic(),
			'logSeverity' => Expect::anyOf(Expect::int(), $errorSeverityExpr, Expect::listOf($errorSeverity)),
			'editor' => Expect::type('string|null')->dynamic(),
			'browser' => Expect::string()->dynamic(),
			'errorTemplate' => Expect::string()->dynamic(),
			'strictMode' => Expect::anyOf(Expect::bool(), Expect::int(), $errorSeverityExpr, Expect::listOf($errorSeverity)),
			'showBar' => Expect::bool()->dynamic(),
			'maxLength' => Expect::int()->dynamic(),
			'maxDepth' => Expect::int()->dynamic(),
			'maxItems' => Expect::int()->dynamic(),
			'keysToHide' => Expect::array(null)->dynamic(),
			'dumpTheme' => Expect::string()->dynamic(),
			'showLocation' => Expect::bool()->dynamic(),
			'scream' => Expect::anyOf(Expect::bool(), Expect::int(), $errorSeverityExpr, Expect::listOf($errorSeverity)),
			'bar' => Expect::listOf('string|Nette\DI\Definitions\Statement'),
			'blueScreen' => Expect::listOf('callable'),
			'editorMapping' => Expect::arrayOf('string')->dynamic()->default(null),
			'netteMailer' => Expect::bool(true),
		]);
	}


	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('logger'))
			->setClass(Tracy\ILogger::class)
			->setFactory([Tracy\Debugger::class, 'getLogger']);

		$builder->addDefinition($this->prefix('blueScreen'))
			->setFactory([Tracy\Debugger::class, 'getBlueScreen']);

		$builder->addDefinition($this->prefix('bar'))
			->setFactory([Tracy\Debugger::class, 'getBar']);
	}


	public function afterCompile(Nette\PhpGenerator\ClassType $class)
	{
		$initialize = $this->initialization ?? new Nette\PhpGenerator\Closure;
		$initialize->addBody('if (!Tracy\Debugger::isEnabled()) { return; }');

		$builder = $this->getContainerBuilder();

		$logger = $builder->getDefinition($this->prefix('logger'));
		$initialize->addBody($builder->formatPhp('$logger = ?;', [$logger]));
		if (
			!$logger instanceof Nette\DI\Definitions\ServiceDefinition
			|| $logger->getFactory()->getEntity() !== [Tracy\Debugger::class, 'getLogger']
		) {
			$initialize->addBody('Tracy\Debugger::setLogger($logger);');
		}

		$options = (array) $this->config;
		unset($options['bar'], $options['blueScreen'], $options['netteMailer']);

		foreach (['logSeverity', 'strictMode', 'scream'] as $key) {
			if (is_string($options[$key]) || is_array($options[$key])) {
				$options[$key] = $this->parseErrorSeverity($options[$key]);
			}
		}

		foreach ($options as $key => $value) {
			if ($value !== null) {
				$tbl = [
					'keysToHide' => 'array_push(Tracy\Debugger::getBlueScreen()->keysToHide, ... ?)',
					'fromEmail' => 'if ($logger instanceof Tracy\Logger) $logger->fromEmail = ?',
					'emailSnooze' => 'if ($logger instanceof Tracy\Logger) $logger->emailSnooze = ?',
				];
				$initialize->addBody($builder->formatPhp(
					($tbl[$key] ?? 'Tracy\Debugger::$' . $key . ' = ?') . ';',
					Nette\DI\Helpers::filterArguments([$value]),
				));
			}
		}

		if ($this->config->netteMailer && $builder->getByType(Nette\Mail\IMailer::class)) {
			$initialize->addBody($builder->formatPhp('if ($logger instanceof Tracy\Logger) $logger->mailer = ?;', [
				[new Statement(Tracy\Bridges\Nette\MailSender::class, ['fromEmail' => $this->config->fromEmail]), 'send'],
			]));
		}

		if ($this->debugMode) {
			foreach ($this->config->bar as $item) {
				if (is_string($item) && substr($item, 0, 1) === '@') {
					$item = new Statement(['@' . $builder::THIS_CONTAINER, 'getService'], [substr($item, 1)]);
				} elseif (is_string($item)) {
					$item = new Statement($item);
				}

				$initialize->addBody($builder->formatPhp(
					'$this->getService(?)->addPanel(?);',
					Nette\DI\Helpers::filterArguments([$this->prefix('bar'), $item]),
				));
			}

			if (
				!$this->cliMode
				&& Tracy\Debugger::getSessionStorage() instanceof Tracy\NativeSession
				&& ($name = $builder->getByType(Nette\Http\Session::class))
			) {
				$initialize->addBody('$this->getService(?)->start();', [$name]);
				$initialize->addBody('Tracy\Debugger::dispatch();');
			}
		}

		foreach ($this->config->blueScreen as $item) {
			$initialize->addBody($builder->formatPhp(
				'$this->getService(?)->addPanel(?);',
				Nette\DI\Helpers::filterArguments([$this->prefix('blueScreen'), $item]),
			));
		}

		if (empty($this->initialization)) {
			$class->getMethod('initialize')->addBody("($initialize)();");
		}

		if (($dir = Tracy\Debugger::$logDirectory) && !is_writable($dir)) {
			throw new Nette\InvalidStateException("Make directory '$dir' writable.");
		}
	}


	/**
	 * @param  string|string[]  $value
	 */
	private function parseErrorSeverity(string|array $value): int
	{
		$value = implode('|', (array) $value);
		$res = (int) @parse_ini_string('e = ' . $value)['e']; // @ may fail
		if (!$res) {
			throw new Nette\InvalidStateException("Syntax error in expression '$value'");
		}

		return $res;
	}
}
