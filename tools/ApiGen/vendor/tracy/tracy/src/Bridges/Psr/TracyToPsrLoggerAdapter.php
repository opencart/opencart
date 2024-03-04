<?php

/**
 * This file is part of the Tracy (https://tracy.nette.org)
 * Copyright (c) 2004 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tracy\Bridges\Psr;

use Psr;
use Tracy;


/**
 * Tracy\ILogger to Psr\Log\LoggerInterface adapter.
 */
class TracyToPsrLoggerAdapter extends Psr\Log\AbstractLogger
{
	/** PSR-3 log level to Tracy logger level mapping */
	private const LevelMap = [
		Psr\Log\LogLevel::EMERGENCY => Tracy\ILogger::CRITICAL,
		Psr\Log\LogLevel::ALERT => Tracy\ILogger::CRITICAL,
		Psr\Log\LogLevel::CRITICAL => Tracy\ILogger::CRITICAL,
		Psr\Log\LogLevel::ERROR => Tracy\ILogger::ERROR,
		Psr\Log\LogLevel::WARNING => Tracy\ILogger::WARNING,
		Psr\Log\LogLevel::NOTICE => Tracy\ILogger::WARNING,
		Psr\Log\LogLevel::INFO => Tracy\ILogger::INFO,
		Psr\Log\LogLevel::DEBUG => Tracy\ILogger::DEBUG,
	];


	public function __construct(
		private Tracy\ILogger $tracyLogger,
	) {
	}


	public function log($level, $message, array $context = []): void
	{
		$level = self::LevelMap[$level] ?? Tracy\ILogger::ERROR;

		if (isset($context['exception']) && $context['exception'] instanceof \Throwable) {
			$this->tracyLogger->log($context['exception'], $level);
			unset($context['exception']);
		}

		if ($context) {
			$message = [
				'message' => $message,
				'context' => $context,
			];
		}

		$this->tracyLogger->log($message, $level);
	}
}
