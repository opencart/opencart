<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * Mock files.
 */
class FileMock
{
	private const Protocol = 'mock';

	/** @var string[] */
	public static array $files = [];

	/** @var resource used by PHP itself */
	public $context;

	private string $content;
	private int $readingPos;
	private int $writingPos;
	private bool $appendMode;
	private bool $isReadable;
	private bool $isWritable;


	/**
	 * @return string  file name
	 */
	public static function create(string $content = '', ?string $extension = null): string
	{
		self::register();

		static $id;
		$name = self::Protocol . '://' . (++$id) . '.' . $extension;
		self::$files[$name] = $content;
		return $name;
	}


	public static function register(): void
	{
		if (!in_array(self::Protocol, stream_get_wrappers(), true)) {
			stream_wrapper_register(self::Protocol, self::class);
		}
	}


	public function stream_open(string $path, string $mode): bool
	{
		if (!preg_match('#^([rwaxc]).*?(\+)?#', $mode, $m)) {
			// Windows: failed to open stream: Bad file descriptor
			// Linux: failed to open stream: Illegal seek
			$this->warning("failed to open stream: Invalid mode '$mode'");
			return false;

		} elseif ($m[1] === 'x' && isset(self::$files[$path])) {
			$this->warning('failed to open stream: File exists');
			return false;

		} elseif ($m[1] === 'r' && !isset(self::$files[$path])) {
			$this->warning('failed to open stream: No such file or directory');
			return false;

		} elseif ($m[1] === 'w' || $m[1] === 'x') {
			self::$files[$path] = '';
		}

		$tmp = &self::$files[$path];
		$tmp = (string) $tmp;
		$this->content = &$tmp;
		$this->appendMode = $m[1] === 'a';
		$this->readingPos = 0;
		$this->writingPos = $this->appendMode ? strlen($this->content) : 0;
		$this->isReadable = isset($m[2]) || $m[1] === 'r';
		$this->isWritable = isset($m[2]) || $m[1] !== 'r';

		return true;
	}


	public function stream_read(int $length)
	{
		if (!$this->isReadable) {
			return false;
		}

		$result = substr($this->content, $this->readingPos, $length);
		$this->readingPos += strlen($result);
		$this->writingPos += $this->appendMode ? 0 : strlen($result);
		return $result;
	}


	public function stream_write(string $data)
	{
		if (!$this->isWritable) {
			return false;
		}

		$length = strlen($data);
		$this->content = str_pad($this->content, $this->writingPos, "\x00");
		$this->content = substr_replace($this->content, $data, $this->writingPos, $length);
		$this->readingPos += $length;
		$this->writingPos += $length;
		return $length;
	}


	public function stream_tell(): int
	{
		return $this->readingPos;
	}


	public function stream_eof(): bool
	{
		return $this->readingPos >= strlen($this->content);
	}


	public function stream_seek(int $offset, int $whence): bool
	{
		if ($whence === SEEK_CUR) {
			$offset += $this->readingPos;
		} elseif ($whence === SEEK_END) {
			$offset += strlen($this->content);
		}

		if ($offset >= 0) {
			$this->readingPos = $offset;
			$this->writingPos = $this->appendMode ? $this->writingPos : $offset;
			return true;
		} else {
			return false;
		}
	}


	public function stream_truncate(int $size): bool
	{
		if (!$this->isWritable) {
			return false;
		}

		$this->content = substr(str_pad($this->content, $size, "\x00"), 0, $size);
		$this->writingPos = $this->appendMode ? $size : $this->writingPos;
		return true;
	}


	public function stream_set_option(int $option, int $arg1, int $arg2): bool
	{
		return false;
	}


	public function stream_stat(): array
	{
		return ['mode' => 0100666, 'size' => strlen($this->content)];
	}


	public function url_stat(string $path, int $flags)
	{
		return isset(self::$files[$path])
			? ['mode' => 0100666, 'size' => strlen(self::$files[$path])]
			: false;
	}


	public function stream_lock(int $operation): bool
	{
		return false;
	}


	public function stream_metadata(string $path, int $option, $value): bool
	{
		switch ($option) {
			case STREAM_META_TOUCH:
				return true;
		}

		return false;
	}


	public function unlink(string $path): bool
	{
		if (isset(self::$files[$path])) {
			unset(self::$files[$path]);
			return true;
		}

		$this->warning('No such file');
		return false;
	}


	private function warning(string $message): void
	{
		$bt = debug_backtrace(0, 3);
		if (isset($bt[2]['function'])) {
			$message = $bt[2]['function'] . '(' . @$bt[2]['args'][0] . '): ' . $message;
		}

		trigger_error($message, E_USER_WARNING);
	}
}
