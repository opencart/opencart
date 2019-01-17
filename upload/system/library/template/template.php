<?php
namespace Template;
final class Template {
	protected $data = array();

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function render($filename, $cache = true, $template_code=null) {
		// render from modified template code if there
		if ($template_code) {
			$file = DIR_CACHE.'tpl/'.$filename.'.tpl';
			if (is_file($file)) {
				if ($template_code != file_get_contents($file)) {
					file_put_contents($file,$template_code,LOCK_EX);
				}
			} else {
				$this->file_force_contents($file,$template_code,LOCK_EX);
			}
			ob_start();
			extract($this->data);
			include( $file );
			$output = ob_get_clean();
			return $output;
		}

		// remove old cache file if still there
		$file = DIR_CACHE.'tpl/'.$filename.'.tpl';
		if (is_file( $file )) {
			unlink( $file );
		}

		// render from PHP template file
		$file = DIR_TEMPLATE . $filename . '.tpl';
		if (is_file($file)) {
			extract($this->data);
			ob_start();
			include($file);
			$output = ob_get_clean();
			return $output;
		}

		throw new \Exception('Error: Could not load template ' . $file . '!');
		exit();
	}

	// helper function, see http://php.net/manual/en/function.file-put-contents.php#84180
	protected function file_force_contents($dir, $contents, $flags) {
		$parts = explode('/', substr($dir,1));
		$file = array_pop($parts);
		$dir = '';
		foreach ($parts as $part) {
			$dir .= "/$part";
			if (!is_dir($dir)) { 
				mkdir($dir);
			}
		}
		file_put_contents( "$dir/$file", $contents, $flags );
	}
}
