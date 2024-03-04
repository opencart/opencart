<?php declare(strict_types = 1);

namespace ApiGen\Info;


class ErrorInfo
{
	public function __construct(
		public ErrorKind $kind,
		public string $message,
	) {
	}
}
