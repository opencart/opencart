<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Error
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Error extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->registry->set('log', new \Opencart\System\Library\Log($this->config->get('config_error_filename')));

		set_error_handler([$this, 'error']);
		set_exception_handler([$this, 'exception']);
	}

	/**
	 * Error
	 *
	 * @param int    $code
	 * @param string $message
	 * @param string $file
	 * @param int    $line
	 *
	 * @return bool
	 */
	public function error(int $code, string $message, string $file, int $line): bool {
		// error suppressed with @
		if (!(error_reporting() & $code)) {
			return false;
		}

		throw new \Exception($message, $code, 0, $file, $line);

		return true;
	}

	/**
	 * Exception
	 *
	 * @param \Throwable $e
	 *
	 * @return void
	 */
	public function exception(\Throwable $e): void {
		$error = $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();

		if ($this->config->get('config_error_log')) {
			$this->log->write($error);
		}

		if ($this->config->get('config_error_display')) {
			echo $error;
		} else {
			header('Location: ' . $this->config->get('error_page'));
			exit();
		}
	}
}
