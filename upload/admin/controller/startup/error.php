<?php
namespace Opencart\Admin\Controller\Startup;
class Error extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->registry->set('log', new \Opencart\System\Library\Log($this->config->get('config_error_filename') ? $this->config->get('config_error_filename') : $this->config->get('error_filename')));

		set_error_handler([$this, 'error']);
		set_exception_handler([$this, 'exception']);
	}

	public function error(string $code, string $message, string $file, string $line): bool {
		// error suppressed with @
		if (error_reporting() === 0) {
			return false;
		}

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

	public function exception(\Throwable $e): void {
		if ($this->config->get('config_error_log')) {
			$sting  = get_class($e) . ':  ' . $e->getMessage() . "\n";
			$sting .= 'File: ' . $e->getFile() . "\n";
			$sting .= 'Line: ' . $e->getLine() . "\n";

			$this->log->write($sting);
		}

		if ($this->config->get('config_error_display')) {
			echo '<b>' . get_class($e) . '</b>: ' . $e->getMessage() . ' in <b>' . $e->getFile() . '</b> on line <b>' . $e->getLine() . '</b>';
		} else {
			header('Location: ' . $this->config->get('error_page'));
			exit();
		}
	}
}
