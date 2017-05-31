<?php
abstract class Controller {
	protected $registry;

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function __get($key) {
		return $this->registry->get($key);
	}

	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}

	/**
	 * This method provides single point to render tpls that matching OpenCart convention
	 * from controllers with simplified call: parent::outputTpl($data);
	 *
	 * @param array $data Will be passed to tpl
	 * @param string $folder_name Specify folder under template path to workaround convention
	 * @param string $file_name Specify filename under template path to workaround convention
	 * @param string $tpl_path Specify direct path to tpl and folder_name and file_name params will be ignored
	 */
	protected function outputTpl($data = array(), $folder_name = '', $file_name = '', $tpl_path = '') {
		if (!empty($tpl_path)) {
			$this->response->setOutput($this->load->view($tpl_path, $data));
		}

		if (empty($folder_name) or empty($file_name)) {
			// Look for controller path
			$debug_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);

			if (!empty($debug_backtrace[0]) and $debug_backtrace[0]['function'] == 'outputTpl') {
				$caller_filepath_parts = pathinfo($debug_backtrace[0]['file']);

				if (empty($folder_name)) {
					$folder_name = basename($caller_filepath_parts['dirname']);
				}

				if (empty($file_name)) {
					$file_name = $caller_filepath_parts['filename'];
				}
			} else {
				trigger_error(
					'Could not get backtrace to get controller path, you need to specify $folder_name and $file_name manually',
					E_USER_ERROR
				);
			}
		}

		// Find tpl path basing on opencart convention
		$path_tpl = '%s/template/%s/%s.tpl';

		$current_template_path = sprintf(
			$path_tpl,
			$this->config->get('config_template'),
			$folder_name,
			$file_name
		);

		if (file_exists(DIR_TEMPLATE . $current_template_path)) {
			$result_path = $current_template_path;
		} else {
			$result_path = sprintf(
				$path_tpl,
				'default',
				$folder_name,
				$file_name
			);
		}

		$this->response->setOutput($this->load->view($result_path, $data));
	}
}