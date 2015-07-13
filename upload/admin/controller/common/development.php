<?php
class ControllerCommonDevelopment extends Controller {

	public function index() {
		if ($this->config->get('config_development')) {

			// Clear Log
			if ($this->user->hasPermission('modify', 'tool/error_log')) {
				$this->clearLog();
			}
			
			// Refresh Modification
			if ($this->user->hasPermission('modify', 'extension/modification')) {
				$this->refreshModification();
			}
			
			// Clear Cache
			$this->clearCache();
		}
	}

	private function clearLog() {

		//Clear Log System
		$file = DIR_LOGS . $this->config->get('config_error_filename');

		$handle = fopen($file, 'w+');

		fclose($handle);

		//Clear Log OCMod
		$file = DIR_LOGS . 'ocmod.log';

		$handle = fopen($file, 'w+');

		fclose($handle);

		//Clear Log OpenBay
		$file = DIR_LOGS . 'openbay.log';

		$handle = fopen($file, 'w+');

		fclose($handle);
	}

	private function refreshModification() {
		$this->load->model('extension/modification');

		//Log
		$log = array();

		// Clear all modification files
		$files = array();

		// Make path into an array
		$path = array(DIR_MODIFICATION . '*');

		// While the path array is still populated keep looping through
		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next) as $file) {
				// If directory add to path array
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				// Add the file to the files to be deleted array
				$files[] = $file;
			}
		}

		// Reverse sort the file array
		rsort($files);

		// Clear all modification files
		foreach ($files as $file) {
			if ($file != DIR_MODIFICATION . 'index.html') {
				// If file just delete
				if (is_file($file)) {
					unlink($file);

				// If directory use the remove directory function
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}
		}

		// Begin
		$xml = array();

		// Load the default modification XML
		$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');

		// This is purly for developers so they can run mods directly and have them run without upload sfter each change.
		$files = glob(DIR_SYSTEM . '*.ocmod.xml');

		if ($files) {
			foreach ($files as $file) {
				$xml[] = file_get_contents($file);
			}
		}

		// Get the default modification file
		$results = $this->model_extension_modification->getModifications();

		foreach ($results as $result) {
			if ($result['status']) {
				$xml[] = $result['xml'];
			}
		}

		$modification = array();

		foreach ($xml as $xml) {
			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->preserveWhiteSpace = false;
			$dom->loadXml($xml);

			// Log
			$log[] = 'MOD: ' . $dom->getElementsByTagName('name')->item(0)->textContent;

			// Wipe the past modification store in the backup array
			$recovery = array();

			// Set the a recovery of the modification code in case we need to use it if an abort attribute is used.
			if (isset($modification)) {
				$recovery = $modification;
			}

			$files = $dom->getElementsByTagName('modification')->item(0)->getElementsByTagName('file');

			foreach ($files as $file) {
				$operations = $file->getElementsByTagName('operation');

				$files = explode(',', $file->getAttribute('path'));

				foreach ($files as $file) {
					$path = '';

					// Get the full path of the files that are going to be used for modification
					if (substr($file, 0, 7) == 'catalog') {
						$path = DIR_CATALOG . str_replace('../', '', substr($file, 8));
					}

					if (substr($file, 0, 5) == 'admin') {
						$path = DIR_APPLICATION . str_replace('../', '', substr($file, 6));
					}

					if (substr($file, 0, 6) == 'system') {
						$path = DIR_SYSTEM . str_replace('../', '', substr($file, 7));
					}

					if ($path) {
						$files = glob($path);

						if ($files) {
							foreach ($files as $file) {
								// Get the key to be used for the modification cache filename.
								if (substr($file, 0, strlen(DIR_CATALOG)) == DIR_CATALOG) {
									$key = 'catalog/' . substr($file, strlen(DIR_CATALOG));
								}

								if (substr($file, 0, strlen(DIR_APPLICATION)) == DIR_APPLICATION) {
									$key = 'admin/' . substr($file, strlen(DIR_APPLICATION));
								}

								if (substr($file, 0, strlen(DIR_SYSTEM)) == DIR_SYSTEM) {
									$key = 'system/' . substr($file, strlen(DIR_SYSTEM));
								}

								// If file contents is not already in the modification array we need to load it.
								if (!isset($modification[$key])) {
									$content = file_get_contents($file);

									$modification[$key] = preg_replace('~\r?\n~', "\n", $content);
									$original[$key] = preg_replace('~\r?\n~', "\n", $content);

									// Log
									$log[] = 'FILE: ' . $key;
								}

								foreach ($operations as $operation) {
									$error = $operation->getAttribute('error');

									// Ignoreif
									$ignoreif = $operation->getElementsByTagName('ignoreif')->item(0);

									if ($ignoreif) {
										if ($ignoreif->getAttribute('regex') != 'true') {
											if (strpos($modification[$key], $ignoreif->textContent) !== false) {
												continue;
											}
										} else {
											if (preg_match($ignoreif->textContent, $modification[$key])) {
												continue;
											}
										}
									}

									$status = false;

									// Search and replace
									if ($operation->getElementsByTagName('search')->item(0)->getAttribute('regex') != 'true') {
										// Search
										$search = $operation->getElementsByTagName('search')->item(0)->textContent;
										$trim = $operation->getElementsByTagName('search')->item(0)->getAttribute('trim');
										$index = $operation->getElementsByTagName('search')->item(0)->getAttribute('index');

										// Trim line if no trim attribute is set or is set to true.
										if (!$trim || $trim == 'true') {
											$search = trim($search);
										}

										// Add
										$add = $operation->getElementsByTagName('add')->item(0)->textContent;
										$trim = $operation->getElementsByTagName('add')->item(0)->getAttribute('trim');
										$position = $operation->getElementsByTagName('add')->item(0)->getAttribute('position');
										$offset = $operation->getElementsByTagName('add')->item(0)->getAttribute('offset');

										if ($offset == '') {
											$offset = 0;
										}

										// Trim line if is set to true.
										if ($trim == 'true') {
											$add = trim($add);
										}

										// Log
										$log[] = 'CODE: ' . $search;

										// Check if using indexes
										if ($index !== '') {
											$indexes = explode(',', $index);
										} else {
											$indexes = array();
										}

										// Get all the matches
										$i = 0;

										$lines = explode("\n", $modification[$key]);

										for ($line_id = 0; $line_id < count($lines); $line_id++) {
											$line = $lines[$line_id];

											// Status
											$match = false;

											// Check to see if the line matches the search code.
											if (stripos($line, $search) !== false) {
												// If indexes are not used then just set the found status to true.
												if (!$indexes) {
													$match = true;
												} elseif (in_array($i, $indexes)) {
													$match = true;
												}

												$i++;
											}

											// Now for replacing or adding to the matched elements
											if ($match) {
												switch ($position) {
													default:
													case 'replace':
														$new_lines = explode("\n", $add);

														if ($offset < 0) {
															array_splice($lines, $line_id + $offset, abs($offset) + 1, array(str_replace($search, $add, $line)));

															$line_id -= $offset;
														} else {
															array_splice($lines, $line_id, $offset + 1, array(str_replace($search, $add, $line)));
														}

														break;
													case 'before':
														$new_lines = explode("\n", $add);

														array_splice($lines, $line_id - $offset, 0, $new_lines);

														$line_id += count($new_lines);
														break;
													case 'after':
														$new_lines = explode("\n", $add);

														array_splice($lines, ($line_id + 1) + $offset, 0, $new_lines);

														$line_id += count($new_lines);
														break;
												}

												// Log
												$log[] = 'LINE: ' . $line_id;

												$status = true;
											}
										}

										$modification[$key] = implode("\n", $lines);
									} else {
										$search = trim($operation->getElementsByTagName('search')->item(0)->textContent);
										$limit = $operation->getElementsByTagName('search')->item(0)->getAttribute('limit');
										$replace = trim($operation->getElementsByTagName('add')->item(0)->textContent);

										// Limit
										if (!$limit) {
											$limit = -1;
										}

										// Log
										$match = array();

										preg_match_all($search, $modification[$key], $match, PREG_OFFSET_CAPTURE);

										// Remove part of the the result if a limit is set.
										if ($limit > 0) {
											$match[0] = array_slice($match[0], 0, $limit);
										}

										if ($match[0]) {
											$log[] = 'REGEX: ' . $search;

											for ($i = 0; $i < count($match[0]); $i++) {
												$log[] = 'LINE: ' . (substr_count(substr($modification[$key], 0, $match[0][$i][1]), "\n") + 1);
											}

											$status = true;
										}

										// Make the modification
										$modification[$key] = preg_replace($search, $replace, $modification[$key], $limit);
									}

									if (!$status) {
										// Log
										$log[] = 'NOT FOUND!';

										// Skip current operation
										if ($error == 'skip') {
											break;
										}

										// Abort applying this modification completely.
										if ($error == 'abort') {
											$modification = $recovery;

											// Log
											$log[] = 'ABORTING!';

											break 5;
										}
									}
								}
							}
						}
					}
				}
			}

			// Log
			$log[] = '----------------------------------------------------------------';
		}

		// Log
		$ocmod = new Log('ocmod.log');
		$ocmod->write(implode("\n", $log));

		// Write all modification files
		foreach ($modification as $key => $value) {
			// Only create a file if there are changes
			if ($original[$key] != $value) {
				$path = '';

				$directories = explode('/', dirname($key));

				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;

					if (!is_dir(DIR_MODIFICATION . $path)) {
						@mkdir(DIR_MODIFICATION . $path, 0777);
					}
				}

				$handle = fopen(DIR_MODIFICATION . $key, 'w');

				fwrite($handle, $value);

				fclose($handle);
			}
		}
	}

	private function clearCache() {

		// Cache Image
		$files = array();

		$path = array(DIR_IMAGE . 'cache/*');

		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next) as $file) {
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				$files[] = $file;
			}
		}

		rsort($files);

		foreach ($files as $file) {
			if ($file != DIR_IMAGE . 'cache/index.html') {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}
		}

		// Cache System
		$files = array();

		$path = array(DIR_CACHE . '*');

		while (count($path) != 0) {
			$next = array_shift($path);

			foreach (glob($next) as $file) {
				if (is_dir($file)) {
					$path[] = $file . '/*';
				}

				$files[] = $file;
			}
		}

		rsort($files);

		foreach ($files as $file) {
			if ($file != DIR_CACHE . 'index.html') {
				if (is_file($file)) {
					unlink($file);
				} elseif (is_dir($file)) {
					rmdir($file);
				}
			}
		}
	}
}