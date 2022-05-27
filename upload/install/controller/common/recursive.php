<?php
namespace Opencart\Install\Controller\Common;
class Recursive extends \Opencart\System\Engine\Controller {
	public function index(array $setting): void {
		$this->recursiveMove($setting);
	}
	
	private function recursiveMove(array $setting): void {
		// If source is not a directory stop processing
		if (!is_dir($setting['src'])) return false;

		// If the destination directory does not exist create it
		if (!is_dir($setting['dest'])) {
			if (!@mkdir($setting['dest'])) {
				// If the destination directory could not be created stop processing
				return false;
			}
		}

		// Open the source directory to read in files
		$i = new \DirectoryIterator($setting['src']);

		foreach ($i as $f) {
			if ($f->isFile() && !file_exists("$setting['dest']/" . $f->getFilename())) {
				@rename($f->getRealPath(), "$setting['dest']/" . $f->getFilename());
			} elseif (!$f->isDot() && $f->isDir()) {
				$this->recursiveMove($f->getRealPath(), "$setting['dest']/$f");

				@unlink($f->getRealPath());
			}
		}

		// Remove source folder after move
		@unlink($setting['src']);
	}
}