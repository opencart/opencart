<?php
namespace Opencart\System\Library\Session;
class File {
	public function __construct(\Opencart\System\Engine\Registry $registry) {
		$this->config = $registry->get('config');
	}

	public function read(string $session_id): array {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		if (is_file($file)) {
			$size = filesize($file);

			if ($size) {
				$handle = fopen($file, 'r');

				flock($handle, LOCK_SH);

				$data = fread($handle, $size);

				flock($handle, LOCK_UN);

				fclose($handle);

				return json_decode($data, true);
			} else {
				return [];
			}
		}

		return [];
	}

	public function write(string $session_id, array $data): bool {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		$handle = fopen($file, 'c');

		flock($handle, LOCK_EX);

		fwrite($handle, json_encode($data));

		ftruncate($handle, ftell($handle));

		fflush($handle);

		flock($handle, LOCK_UN);

		fclose($handle);

		return true;
	}

	public function destroy(string $session_id): void {
		$file = DIR_SESSION . 'sess_' . basename($session_id);

		if (is_file($file)) {
			unlink($file);
		}
	}

	public function gc(): void {
		if (round(rand(1, $this->config->get('session_divisor') / $this->config->get('session_probability'))) == 1) {
			$expire = time() - $this->config->get('session_expire');

			$files = glob(DIR_SESSION . 'sess_*');

			foreach ($files as $file) {
				if (is_file($file) && filemtime($file) < $expire) {
					unlink($file);
				}
			}
		}
	}
}
