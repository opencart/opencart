<?php
/*
 
Installed versionh
current version
latest version

Preparation

1. Check compatibility of extensions with the latest version

Backup

2. Download a copy of the current version

3. Scan files to confirm what changes have been made to the installed version and the current

4. Let the user download the copies of all the modified files

Upgrade

5. Download a copy of the latest version

6. Scan files to confirm what changes have been made between the current version and latest

6. Alert the user to any modified files that have not be updated         


7. Allow the user to download the changed files.

8. Replace the files	
*/
class ControllerUpgradeBackup extends Controller {
	public function index() {
		$this->load->language('upgrade/backup');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}
				
		if (!$json) {
            $curl = curl_init(OPENCART_SERVER . 'index.php?route=upgrade/upgrade');

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);

            $response = curl_exec($curl);

            curl_close($curl);

            $response_info = json_decode($response, true);

            if ($response_info) {

            }
		}		
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function calculate() {
		$this->load->language('upgrade/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!empty($this->request->get['backup'])) {
			$json['error'] = $this->language->get('error_backup');
		}
		
		if (!$json) {
			$backup = explode(',', $this->request->get['backup']);
			
			if (in_array('file', $backup)) {
				$files = $this->getFiles();
				DIR_CATALOG
                DIR_APPLICATION
                DIR_CONFIG .
                DIR_SYSTEM . 'engine'
                DIR_SYSTEM . 'engine'






                $directories[] = DIR_IMAGE . $file;
			}			
			
			$directories = array();
			
			if (in_array('image', $backup)) {
				$directories[] = DIR_IMAGE;
			}
			
			if (in_array('download', $backup)) {
				$directories[] = DIR_DOWNLOAD;
			}
			
			if (in_array('upload', $backup)) {
				$directories[] = DIR_UPLOAD;
			}
			
			if (isset($this->request->post['path'])) {
				$paths = $this->request->post['path'];
			} else {
				$paths = array();
			}


			// Loop through each path
			foreach ($directories as $directory) {
				$path = rtrim(DIR_IMAGE . $path, '/');

				// If path is just a file delete it
				if (is_file($path)) {
					unlink($path);

				// If path is a directory beging deleting each file and sub folder
				} elseif (is_dir($path)) {
					$files = array();

					// Make path into an array
					$path = array($path);

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

					foreach ($files as $file) {
						// If file just delete
						if (is_file($file)) {
							unlink($file);

						// If directory use the remove directory function
						} elseif (is_dir($file)) {
							rmdir($file);
						}
					}
				}
			}

			
			
			
			disk_total_space();
			
		} 
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	function database() {
        $this->load->language('upgrade/backup');

        $json = array();

        if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    function image() {
        $this->load->language('upgrade/backup');

        $json = array();

        if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    function download() {
        $this->load->language('upgrade/backup');

        $json = array();

        if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            $files = glob(DIR_DOWNLOAD . );



            foreach ($files as $file) {



            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    function upload() {
        $this->load->language('upgrade/backup');

        $json = array();

        if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {

        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

	public function current() {
		$this->load->language('upgrade/backup');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}		
		
		if (!$json) {
			
		}
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function modified() {
		$this->load->language('upgrade/backup');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}		
		
		if (!$json) {
			
		}
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
	
	public function zip() {
		$this->load->language('upgrade/backup');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}		
		
		if (!$json) {
			
		}
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}
	
	public function download() {
		$this->load->language('tool/upgrade');
		
		$json = array();
		
		if (!$this->user->hasPermission('modify', 'upgrade/backup')) {
			$json['error'] = $this->language->get('error_permission');
		}		
		
		if (!$json) {
			
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));	
	}	
}