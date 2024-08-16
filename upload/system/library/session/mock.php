<?php
namespace Opencart\System\Library\Session;
/**
 * Class Mock
 *
 * @package Opencart\System\Library\Session
 */
class Mock {
	public function __construct(\Opencart\System\Engine\Registry $registry) {

	}

	/**
	 * Read
	 *
	 * @param string $session_id
	 *
	 * @return array<mixed>
	 */
	public function read(string $session_id): array {
		return [];
	}

	/**
	 * Write
	 *
	 * @param string       $session_id
	 * @param array<mixed> $data
	 *
	 * @return bool
	 */
	public function write(string $session_id, array $data): bool {
		return true;
	}

	/**
	 * Destroy
	 *
	 * @param string $session_id
	 *
	 * @return void
	 */
	public function destroy(string $session_id): void {

	}

	/**
	 * GC
	 *
	 * @return void
	 */
	public function gc(): void {

	}
}
