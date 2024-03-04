<?php

/**
 * This file is part of the Nette Tester.
 * Copyright (c) 2009 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Tester;


/**
 * PHP file mutator.
 * @internal
 */
class FileMutator
{
	private const Protocol = 'file';

	/** @var resource|null */
	public $context;

	/** @var resource|null */
	private $handle;

	/** @var callable[] */
	private static array $mutators = [];


	public static function addMutator(callable $mutator): void
	{
		self::$mutators[] = $mutator;
		stream_wrapper_unregister(self::Protocol);
		stream_wrapper_register(self::Protocol, self::class);
	}


	public function dir_closedir(): void
	{
		closedir($this->handle);
	}


	public function dir_opendir(string $path, int $options): bool
	{
		$this->handle = $this->context
			? $this->native('opendir', $path, $this->context)
			: $this->native('opendir', $path);
		return (bool) $this->handle;
	}


	public function dir_readdir()
	{
		return readdir($this->handle);
	}


	public function dir_rewinddir(): bool
	{
		return (bool) rewinddir($this->handle);
	}


	public function mkdir(string $path, int $mode, int $options): bool
	{
		$recursive = (bool) ($options & STREAM_MKDIR_RECURSIVE);
		return $this->context
			? $this->native('mkdir', $path, $mode, $recursive, $this->context)
			: $this->native('mkdir', $path, $mode, $recursive);
	}


	public function rename(string $pathFrom, string $pathTo): bool
	{
		return $this->context
			? $this->native('rename', $pathFrom, $pathTo, $this->context)
			: $this->native('rename', $pathFrom, $pathTo);
	}


	public function rmdir(string $path, int $options): bool
	{
		return $this->context
			? $this->native('rmdir', $path, $this->context)
			: $this->native('rmdir', $path);
	}


	public function stream_cast(int $castAs)
	{
		return $this->handle;
	}


	public function stream_close(): void
	{
		fclose($this->handle);
	}


	public function stream_eof(): bool
	{
		return feof($this->handle);
	}


	public function stream_flush(): bool
	{
		return fflush($this->handle);
	}


	public function stream_lock(int $operation): bool
	{
		return $operation
			? flock($this->handle, $operation)
			: true;
	}


	public function stream_metadata(string $path, int $option, $value): bool
	{
		switch ($option) {
			case STREAM_META_TOUCH:
				return $this->native('touch', $path, $value[0] ?? time(), $value[1] ?? time());
			case STREAM_META_OWNER_NAME:
			case STREAM_META_OWNER:
				return $this->native('chown', $path, $value);
			case STREAM_META_GROUP_NAME:
			case STREAM_META_GROUP:
				return $this->native('chgrp', $path, $value);
			case STREAM_META_ACCESS:
				return $this->native('chmod', $path, $value);
		}

		return false;
	}


	public function stream_open(string $path, string $mode, int $options, ?string &$openedPath): bool
	{
		$usePath = (bool) ($options & STREAM_USE_PATH);
		if ($mode === 'rb' && pathinfo($path, PATHINFO_EXTENSION) === 'php') {
			$content = $this->native('file_get_contents', $path, $usePath, $this->context);
			if ($content === false) {
				return false;
			} else {
				foreach (self::$mutators as $mutator) {
					$content = $mutator($content);
				}

				$this->handle = tmpfile();
				$this->native('fwrite', $this->handle, $content);
				$this->native('fseek', $this->handle, 0);
				return true;
			}
		} else {
			$this->handle = $this->context
				? $this->native('fopen', $path, $mode, $usePath, $this->context)
				: $this->native('fopen', $path, $mode, $usePath);
			return (bool) $this->handle;
		}
	}


	public function stream_read(int $count)
	{
		return fread($this->handle, $count);
	}


	public function stream_seek(int $offset, int $whence = SEEK_SET): bool
	{
		return fseek($this->handle, $offset, $whence) === 0;
	}


	public function stream_set_option(int $option, int $arg1, int $arg2): bool
	{
		return false;
	}


	public function stream_stat()
	{
		return fstat($this->handle);
	}


	public function stream_tell(): int
	{
		return ftell($this->handle);
	}


	public function stream_truncate(int $newSize): bool
	{
		return ftruncate($this->handle, $newSize);
	}


	public function stream_write(string $data)
	{
		return fwrite($this->handle, $data);
	}


	public function unlink(string $path): bool
	{
		return $this->native('unlink', $path);
	}


	public function url_stat(string $path, int $flags)
	{
		$func = $flags & STREAM_URL_STAT_LINK ? 'lstat' : 'stat';
		return $flags & STREAM_URL_STAT_QUIET
			? @$this->native($func, $path)
			: $this->native($func, $path);
	}


	private function native(string $func)
	{
		stream_wrapper_restore(self::Protocol);
		try {
			return $func(...array_slice(func_get_args(), 1));
		} finally {
			stream_wrapper_unregister(self::Protocol);
			stream_wrapper_register(self::Protocol, self::class);
		}
	}
}
