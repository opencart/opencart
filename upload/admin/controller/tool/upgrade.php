<?php
namespace Opencart\Admin\Controller\Tool;
/**
 * Class Upgrade
 *
 * @package Opencart\Admin\Controller\Tool
 */
class Upgrade extends \Opencart\System\Engine\Controller {
	// Index Start
	public function index(): void {
		$this->load->language('tool/upgrade');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('tool/upgrade', 'user_token=' . $this->session->data['user_token'])
		];

		$data['current_version'] = VERSION;
		$data['upgrade'] = false;
		$data['rollback_available'] = false;

		$latestVersionInfo = $this->latestVersionInfo();
		$update_available = (bool) (version_compare($latestVersionInfo['name'], VERSION, '>'));
		$data['update_available'] = (bool) $update_available;
		$data['latest_version'] = $latestVersionInfo['name'];

		$data['date_added'] = date($this->language->get('date_format_short'), strtotime($latestVersionInfo['published_at']));

		$data['text_change'] = ($update_available)?
			$this->language->get('text_change') :
			$this->language->get('text_up_to_date')
		;

		$data['warning_leave'] = $this->language->get('warning_leave');
		$data['modal_rollback_title'] = $this->language->get('modal_rollback_title');
		$data['modal_rollback_message'] = $this->language->get('modal_rollback_message');
		$data['modal_update_title'] = $this->language->get('modal_update_title');
		$data['modal_update_message'] = $this->language->get('modal_update_message');
		$data['modal_backing_up_files'] = $this->language->get('modal_backing_up_files');
		$data['modal_rollback_status'] = $this->language->get('modal_rollback_status');
		$data['modal_rollback_ready'] = $this->language->get('modal_rollback_ready');

		$data['log'] = ($update_available)? $this->formatMarkdown($latestVersionInfo['body']) : '';

		$data['upgrade'] = (bool) $update_available;

		$rollback_versions = $this->fetchPreviousVersionList();
		if (!empty($rollback_versions)) {
			if (count($rollback_versions) == 1 && $rollback_versions[0] != VERSION) {
				$data['rollback_available'] = true;
				$data['rollback_versions'] = $rollback_versions;
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('tool/upgrade', $data));
	}

	public function formatMarkdown($content): string {
		// Split by new lines and trim
		$lines = array_map('trim', explode("\n", $content));
		
		$formatted = '';
		
		foreach ($lines as $line) {
			if (preg_match('/^(#+)\s*(.*)$/', $line, $matches)) {
				$hash_count = strlen($matches[1]);
				$content = htmlspecialchars(trim($matches[2]));
				$formatted .= '<h' . $hash_count . '>' . $content . '</h' . $hash_count . '>';
			}

			if (strpos($line, '* ') === 0) {
				$formatted = str_replace($line, '', $formatted);
				$line = str_replace('* ', '- ', $line);
				$formatted .= $line . '<br>';
			}
			
			if (preg_match('/(https:\/\/github\.com\/opencart\/opencart\/compare\/[\d\.]+)/', $line, $matches)) {
				$url = $matches[1];
				$updatedUrl = preg_replace('/(\d+\.\d+\.\d+\.\d+)(?=...)/', VERSION, $url);
				$formatted = str_replace($line, '', $formatted);
				$line = preg_replace('/(https:\/\/github\.com\/opencart\/opencart\/compare\/[\d\.]+)/', '<a href="' . $updatedUrl . '" target="_blank">' . $updatedUrl . '</a>', $line);
				$formatted .= $line . '<br>';
			}
			elseif (preg_match('/https?:\/\/[^\s]+/', $line, $matches)) {
				$url = $matches[0];

				$updatedUrl = preg_replace('/\d+\.\d+\.\d+\.\d+/', VERSION, $url);
				$formatted = str_replace($line, '', $formatted);
				$line = str_replace($url, '<a href="' . $updatedUrl . '" target="_blank">' . $updatedUrl . '</a>', $line);
				$formatted .= $line;
			}

			if (preg_match('/@(\w+)/', $line, $matches)) {
				$handle = $matches[1];
				$formatted = str_replace($line, '', $formatted);
				$line = preg_replace('/@(\w+)/', '<a href="https://github.com/' . $handle . '" target="_blank">@' . $handle . '</a>', $line);
				$formatted .= $line . '<br>';
			}

			if (preg_match('/\*\*(.*?)\*\*/', $line, $matches)) {
				$string = $matches[1];
				$formatted = str_replace($line, '', $formatted);
				$line = preg_replace('/\*\*(.*?)\*\*/', '<strong>' . $string . '</strong>', $line);
				$formatted .= $line;
			}
		}

		return $formatted;
	}

	public function latestVersionInfo(): array {
		// Get the latest release from OpenCart GitHub API
		$latestVersionUrl = 'https://api.github.com/repos/opencart/opencart/releases/latest';

		// Set a valid User-Agent header to avoid GitHub API blocking
		$opts = [
			"http" => [
				"header" => "User-Agent: OpenCartUpdater\r\n"
			]
		];
		$context = stream_context_create($opts);
		$latestVersionData = @file_get_contents($latestVersionUrl, false, $context);

		// Return empty array if unable to fetch version info
		if (!$latestVersionData) {
			return ['error' => 'Unable to fetch version info'];
		}

		$latestVersionInfo = json_decode($latestVersionData, true);

		return $latestVersionInfo;
	}
	
	public function fetchPreviousVersionList(): array {
		$versionList = [];
		
		$backupDir = DIR_STORAGE . 'backup/';

		if (is_dir($backupDir)) {
			$files = scandir($backupDir);

			foreach ($files as $file) {
				if ($file !== '.' && $file !== '..' && is_dir($backupDir . $file)) {
					$versionList[] = $file; // Add the folder name to the array
				}
			}
		}

		return $versionList;
	}
	// Index End

	// Update Start
	public function backupFiles(): void {
		$this->load->language('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$backupDir = DIR_STORAGE . 'backup/' . VERSION . '/';

			if (!is_dir($backupDir)) {
				mkdir($backupDir, 0755, true);
			}

			$this->recursiveCopy(DIR_OPENCART, $backupDir);

			$json['text'] = $this->language->get('text_backing_up_db');
			$json['next'] = $this->url->link('tool/upgrade.backupDatabase', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function recursiveCopy($source, $destination): void {
		$dir = opendir($source);

		if (!is_dir($destination)) {
			@mkdir($destination, 0755, true);
		}

		while (($file = readdir($dir)) !== false) {
			if ($file === '.' || $file === '..') {
				continue;
			}

			$srcPath = $source . '/' . $file;
			if (realpath($srcPath) === realpath(DIR_STORAGE . 'backup/' . VERSION)) {
				continue;
			}

			$destPath = $destination . '/' . $file;

			if (is_dir($srcPath)) {
				$this->recursiveCopy($srcPath, $destPath);
			} else {
				copy($srcPath, $destPath);
			}
		}
		closedir($dir);
	}

	public function backupDatabase(): void {
		$this->load->language('tool/upgrade');
		$this->load->model('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$backupDir = DIR_STORAGE . 'backup/' . VERSION . '/';

			// Create the backup directory if it doesn't exist
			if (!is_dir($backupDir)) {
				mkdir($backupDir, 0755, true);
			}

			$dbBackupPath = $backupDir . 'database_backup.sql';
			$tablesBackupPath = $backupDir . 'tables_backup.json';

			// Call the model method to back up the database
			if ($this->model_tool_upgrade->backupDatabase($dbBackupPath, $tablesBackupPath)) {
				$json['text'] = $this->language->get('text_compressing_backup');
				$json['next'] = $this->url->link('tool/upgrade.compressBackup', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
			} else {
				$json['error'] = $this->language->get('error_db_backup_fail');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function compressBackup(): void {
		$this->load->language('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';

		$json = [];
		$backupDir = DIR_STORAGE . 'backup/' . VERSION . '/';
		$zipFile = $backupDir  . 'backup.zip';

		// Remove existing zip file if it exists
		if (file_exists($zipFile)) {
			unlink($zipFile);
		}

		// Initialise ZipArchive
		$zip = new \ZipArchive();
		if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
			$this->addFolderToZip($backupDir, $zip, $backupDir); // Add all files to zip
			$zip->close();

			$json['text'] = $this->language->get('text_clearing_backup_files');
			$json['next'] = $this->url->link('tool/upgrade.clearBackupFiles', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		} else {
			$json['error'] = $this->language->get('error_zip_backup_fail');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Recursive function to add files/folders to zip
	private function addFolderToZip(string $folder, \ZipArchive $zip, string $baseFolder): void {
		$files = scandir($folder);

		foreach ($files as $file) {
			if ($file === '.' || $file === '..') {
				continue;
			}

			$filePath = $folder . $file;
			$relativePath = str_replace($baseFolder, '', $filePath);

			// If it's a directory, recurse into it
			if (is_dir($filePath)) {
				$this->addFolderToZip($filePath . '/', $zip, $baseFolder);
			} 
			// If it's a file, add to zip
			elseif (is_file($filePath)) {
				$zip->addFile($filePath, $relativePath);
			}
		}
	}

	public function clearBackupFiles(): void {
		$this->load->language('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';
		$type = (isset($this->request->get['type']))? $this->request->get['type'] : '';
		$typeRequest = ($type == '')? '' : '&type=rollback';

		$json = [];
		$backupDir = DIR_STORAGE . 'backup/' . VERSION . '/';
		$zipFile = $backupDir . 'backup.zip'; // Get the zip file to keep

		// Check if the backup directory exists
		if (is_dir($backupDir)) {
			$files = scandir($backupDir); // List all files and directories

			// Loop through the files in the backup directory
			foreach ($files as $file) {
				// Skip . and .. directories
				if ($file === '.' || $file === '..') {
					continue;
				}

				$filePath = $backupDir . $file;

				// Skip the zip file
				if ($filePath === $zipFile) {
					continue;
				}

				// If it's a file, delete it
				if (is_file($filePath)) {
					unlink($filePath);
				}
				// If it's a directory, remove the directory and its contents
				elseif (is_dir($filePath)) {
					$this->deleteDirectory($filePath);
				}
			}

			$link = ($type == '')? 'download' : 'clearCache';

			if ($type == '') {
				$json['text'] = $this->language->get('text_downloading_update');
			} else {
				$json['text'] = $this->language->get('text_clearing_cache');
			}
			
			$json['next'] = $this->url->link('tool/upgrade.' . $link, 'user_token=' . $this->session->data['user_token'] . '&version=' . $version . $typeRequest, true);

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}
	}

	private function deleteDirectory($dirPath): void {
		$files = scandir($dirPath); // List all files and directories in the sub-directory

		foreach ($files as $file) {
			// Skip . and .. directories
			if ($file === '.' || $file === '..') {
				continue;
			}

			$filePath = $dirPath . DIRECTORY_SEPARATOR . $file;

			// Recursively delete files and subdirectories
			if (is_dir($filePath)) {
				$this->deleteDirectory($filePath); // Recursively delete subdirectories
			} else {
				unlink($filePath); // Delete files
			}
		}

		rmdir($dirPath); // Delete the now-empty directory
	}

	public function download(): void {
		$this->load->language('tool/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (version_compare($version, VERSION, '<') || !preg_match('/^(\d+\.\d+\.\d+\.\d+)$/', $version)) {
			$json['error'] = $this->language->get('error_version');
		}

		if (!$json) {
			$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

			$handle = fopen($file, 'w');

			set_time_limit(0);

			$curl = curl_init('https://github.com/opencart/opencart/archive/' . $version . '.zip');

			curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart ' . VERSION);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
			curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 300);
			curl_setopt($curl, CURLOPT_FILE, $handle);
			curl_setopt($curl, CURLOPT_CAINFO, DIR_SYSTEM  . 'config/cacert.pem');

			curl_exec($curl);

			fclose($handle);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			if ($error = curl_error($curl)) {
				// If there's an error, set the error message
				$json['error'] = 'cURL error: ' . $error;
			} else {
				$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

				if ($status != 200) {
					$json['error'] = $this->language->get('error_download');
				}
			}

			curl_close($curl);
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_install');

			$json['next'] = $this->url->link('tool/upgrade.install', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		$this->load->language('tool/upgrade');

		$json = [];

		if (isset($this->request->get['version'])) {
			$version = $this->request->get['version'];
		} else {
			$version = '';
		}

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (version_compare($version, VERSION, '>') && preg_match('/^(\d+\.\d+\.\d+\.\d+)$/', $version)) {
			$file = DIR_DOWNLOAD . 'opencart-' . $version . '.zip';

			if (!is_file($file)) {
				$json['error'] = $this->language->get('error_file');
			}
		} else {
			$json['error'] = $this->language->get('error_version');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				$remove = 'opencart-' . $version . '/upload/';

				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, strlen($remove)) == $remove) {
						// Only extract the contents of the upload folder
						$destination = str_replace('\\', '/', substr($source, strlen($remove)));

						if (substr($destination, 0, 8) == 'install/') {
							// Default copy location
							$path = '';

							// Must not have a path before files and directories can be moved
							$directories = explode('/', dirname($destination));

							foreach ($directories as $directory) {
								if (!$path) {
									$path = $directory;
								} else {
									$path = $path . '/' . $directory;
								}

								if (!is_dir(DIR_OPENCART . $path) && !@mkdir(DIR_OPENCART . $path, 0777)) {
									$json['error'] = sprintf($this->language->get('error_directory'), $path);
								}
							}

							// Check if the path is not directory and check there is no existing file
							if (substr($destination, -1) != '/') {
								if (is_file(DIR_OPENCART . $destination)) {
									unlink(DIR_OPENCART . $destination);
								}

								if (file_put_contents(DIR_OPENCART . $destination, $zip->getFromIndex($i)) === false) {
									$json['error'] = sprintf($this->language->get('error_copy'), $source, $destination);
								}
							}
						}
					}
				}

				$zip->close();

				$json['text'] = $this->language->get('text_patch');

				$json['next'] = HTTP_CATALOG . 'install/index.php?route=upgrade/upgrade_1&version=' . $version . '&admin=' . rtrim(substr(DIR_APPLICATION, strlen(DIR_OPENCART), -1));
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function removeInstallDirectory(): void {
		$this->load->language('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';
		$installDir = DIR_OPENCART . 'install'; // Path to the install directory

		// Check if the install directory exists
		if (is_dir($installDir)) {
			$this->deleteDirectory($installDir);
		}

		$json['text'] = $this->language->get('text_clearing_cache');
		$json['next'] = $this->url->link('tool/upgrade.clearCache', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// Update End

	// Rollback Start
	public function unzipBackup(): void {
		$this->load->language('tool/upgrade');

		$version = (isset($this->request->get['version']))? $this->request->get['version'] : '';

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$backupDir = DIR_STORAGE . 'backup/' . $version . '/';
		$compressedFile = $backupDir . 'backup.zip';

		$zip = new \ZipArchive();

		// Zip error codes
		$zip_errors = [
			\ZipArchive::ER_EXISTS => $this->language->get('zip_error_exists'),
			\ZipArchive::ER_INCONS => $this->language->get('zip_error_incons'),
			\ZipArchive::ER_INVAL => $this->language->get('zip_error_inval'),
			\ZipArchive::ER_MEMORY => $this->language->get('zip_error_memory'),
			\ZipArchive::ER_NOENT => $this->language->get('zip_error_noent'),
			\ZipArchive::ER_NOZIP => $this->language->get('zip_error_nozip'),
			\ZipArchive::ER_OPEN => $this->language->get('zip_error_open'),
			\ZipArchive::ER_READ => $this->language->get('zip_error_read'),
			\ZipArchive::ER_SEEK => $this->language->get('zip_error_seek'),
		];

		// Check if the zip is valid
		$result_code = $zip->open($compressedFile);

		if ($result_code !== true) {
			$json['error'] = isset($zip_errors[$result_code]) ? $zip_errors[$result_code] : $this->language->get('error_unknown');
		}

		if (!$json) {
			$zip->extractTo($backupDir);
			$zip->close();
			$json['text'] = $this->language->get('text_rollback_db');
			$json['next'] = $this->url->link('tool/upgrade.rollbackDatabase', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function rollbackDatabase(): void {
		$this->load->language('tool/upgrade');
		$this->load->model('tool/upgrade');

		$version = isset($this->request->get['version']) ? $this->request->get['version'] : '';

		$backupDir = DIR_STORAGE . 'backup/' . $version . '/';
		$sqlFile = $backupDir . 'database_backup.sql';

		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			if (!file_exists($sqlFile)) {
				$json['error'] = $this->language->get('error_sql_not_found');
			}
		}

		if (!$json) {
			$backupDir = DIR_STORAGE . 'backup/' . $version . '/';
			$tablesBackupPath = $backupDir . 'tables_backup.json';
			
			if ($this->model_tool_upgrade->dropTables($tablesBackupPath) == false) {
				$json['error'] = $this->language->get('error_tables_drop');
			};
		}

		if (!$json) {
			if ($this->model_tool_upgrade->rollbackDatabase($sqlFile) == false) {
				$json['error'] = $this->language->get('error_db_rollback');
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_rollback_files');
			$json['next'] = $this->url->link('tool/upgrade.rollbackFiles', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rollbackFiles(): void {
		$this->load->language('tool/upgrade');

		$version = isset($this->request->get['version']) ? $this->request->get['version'] : '';
		$json = [];

		if (!$this->user->hasPermission('modify', 'tool/upgrade')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Define the directory paths
		$backupDir = DIR_STORAGE . 'backup/' . $version . '/';
		$rootDir = DIR_OPENCART;

		// Check if the backup directory exists
		if (!is_dir($backupDir)) {
			$json['error'] = $this->language->get('error_backup_not_found');
		}

		if (!$json) {
			$ignore = [
				'database_backup.sql',
				'tables_backup.json',
				'backup.zip'
			];
			// Get all files in the backup directory
			$files = scandir($backupDir);

			// Loop through the files and directories
			foreach ($files as $file) {
				// Skip '.' and '..'
				if ($file === '.' || $file === '..' || in_array($file, $ignore)) {
					continue;
				}

				// Get the full path of the file or directory
				$fullPath = $backupDir . DIRECTORY_SEPARATOR . $file;
				$targetPath = $rootDir . DIRECTORY_SEPARATOR . $file;

				if (is_dir($fullPath)) {
					// If it's a directory, create the directory in the target location if it doesn't exist
					if (!is_dir($targetPath)) {
						mkdir($targetPath, 0755, true);
					}

					// Recurse into subdirectories
					$this->copyDirectoryContents($fullPath, $targetPath);
				} else {
					// If it's a file, copy it to the target location, overwriting if necessary
					if (!copy($fullPath, $targetPath)) {
						$json['error'] = sprintf($this->language->get('error_copy'), $file);
						break;
					}
				}
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_clearing_rollback_files');
			$json['next'] = $this->url->link('tool/upgrade.clearBackupFiles', 'user_token=' . $this->session->data['user_token'] . '&version=' . $version . '&type=rollback', true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	private function copyDirectoryContents($sourceDir, $destinationDir) {
		$files = scandir($sourceDir);
		foreach ($files as $file) {
			// Skip '.' and '..'
			if ($file === '.' || $file === '..') {
				continue;
			}

			$sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $file;
			$destinationPath = $destinationDir . DIRECTORY_SEPARATOR . $file;

			if (is_dir($sourcePath)) {
				// If it's a directory, create the directory in the target location if it doesn't exist
				if (!is_dir($destinationPath)) {
					mkdir($destinationPath, 0755, true);
				}
				// Recurse into subdirectories
				$this->copyDirectoryContents($sourcePath, $destinationPath);
			} else {
				// If it's a file, copy it to the target location
				if (!copy($sourcePath, $destinationPath)) {
					$json['error'] = sprintf($this->language->get('error_copy'), $file);
					break;
				}
			}
		}
	}
	// Rollback End

	// Joint Functions
	public function clearCache(): void {
		$this->load->language('tool/upgrade');
		$type = (isset($this->request->get['type']))? $this->request->get['type'] : '';
		$this->cache->delete('*');
		
		if ($type == '') {
			$json['text'] = $this->language->get('text_update_complete');
			$json['upgradeComplete'] = true;
		} else {
			$json['text'] = $this->language->get('text_rollback_complete');
			$json['rollbackComplete'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
