<?php
namespace Opencart\Admin\Controller\Startup;
/**
 * Class Error
 *
 * @package Opencart\Admin\Controller\Startup
 */
class Error extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->registry->set('log', new \Opencart\System\Library\Log($this->config->get('config_error_filename') ? $this->config->get('config_error_filename') : $this->config->get('error_filename')));

		set_error_handler([$this, 'error']);
		set_exception_handler([$this, 'exception']);
	}

	/**
	 * @param string $code
	 * @param string $message
	 * @param string $file
	 * @param string $line
	 *
	 * @return bool
	 */
	public function error(string $code, string $message, string $file, string $line): bool {
		switch ($code) {
			case E_NOTICE:
			case E_USER_NOTICE:
				$error = 'Notice';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$error = 'Warning';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$error = 'Fatal Error';
				break;
			default:
				$error = 'Unknown';
				break;
		}

		if ($this->config->get('config_error_log')) {
			$sting  = 'PHP ' . $error . ': ' . $message . "\n";
			$sting .= 'File: ' . $file . "\n";
			$sting .= 'Line: ' . $line . "\n";

			$this->log->write($sting);
		}

		if ($this->config->get('config_error_display')) {
			echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
		} else {
			header('Location: ' . $this->config->get('error_page'));
			exit();
		}

		return true;
	}

	/**
	 * @param \Throwable $e
	 *
	 * @return void
	 */
	public function exception(\Throwable $e): void {
		if ($this->config->get('config_error_log')) {
			$sting  = $e->getCode() . ':  ' . $e->getMessage() . "\n";
			$sting .= 'File: ' . $e->getFile() . "\n";
			$sting .= 'Line: ' . $e->getLine() . "\n";

			$this->log->write($sting);
		}

		if ($this->config->get('config_error_display')) {
			echo '<b>' . $e->getMessage() . '</b>: in <b>' . $e->getFile() . '</b> on line <b>' . $e->getLine() . '</b>';
		} else {
			header('Location: ' . $this->config->get('error_page'));
			exit();
		}
	}
}
