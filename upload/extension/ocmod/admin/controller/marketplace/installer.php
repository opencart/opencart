<?php
namespace Opencart\Extension\Ocmod\Admin\Controller\Marketplace;

class Installer extends \Opencart\System\Engine\Controller {
	/**
	 * Xml
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketplace/installer');

		$json = [];

		if (isset($this->request->get['extension_install_id'])) {
			$extension_install_id = $this->request->get['extension_install_id'];
		} else {
			$extension_install_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'marketplace/installer')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Extension
		$this->load->model('setting/extension');

		$extension_install_info = $this->model_setting_extension->getInstall($extension_install_id);

		if ($extension_install_info) {
			$file = DIR_STORAGE . 'marketplace/' . $extension_install_info['code'] . '.ocmod.zip';

			if (!is_file($file)) {
				$json['error'] = sprintf($this->language->get('error_file'), $extension_install_info['code'] . '.ocmod.zip');
			}
		} else {
			$json['error'] = $this->language->get('error_extension');
		}

		if (!$json) {
			// Unzip the files
			$zip = new \ZipArchive();

			if ($zip->open($file, \ZipArchive::RDONLY)) {
				// Modification
				$this->load->model('setting/modification');

				// If xml file, just put it straight into the DB
				// Check if any of the files already exist.
				for ($i = 0; $i < $zip->numFiles; $i++) {
					$source = $zip->getNameIndex($i);

					if (substr($source, 0, 6) == 'ocmod/' && substr($source, -10) == '.ocmod.xml') {
						$code = basename($source, '.ocmod.xml');

						// Check to see if the modification is already installed or not.
						$modification_info = $this->model_setting_modification->getModificationByCode($code);

						if (!$modification_info) {
							$xml = $zip->getFromName($source);

							if ($xml) {
								try {
									$dom = new \DOMDocument('1.0', 'UTF-8');
									$dom->loadXml($xml);

									$name = $dom->getElementsByTagName('name')->item(0);

									if ($name) {
										$name = $name->nodeValue;
									} else {
										$name = '';
									}

									$description = $dom->getElementsByTagName('description')->item(0);

									if ($description) {
										$description = $description->nodeValue;
									} else {
										$description = '';
									}

									$author = $dom->getElementsByTagName('author')->item(0);

									if ($author) {
										$author = $author->nodeValue;
									} else {
										$author = '';
									}

									$version = $dom->getElementsByTagName('version')->item(0);

									if ($version) {
										$version = $version->nodeValue;
									} else {
										$version = '';
									}

									$link = $dom->getElementsByTagName('link')->item(0);

									if ($link) {
										$link = $link->nodeValue;
									} else {
										$link = '';
									}

									$modification_data = [
										'extension_install_id' => $extension_install_id,
										'name'                 => strip_tags($name),
										'description'          => nl2br(strip_tags($description)),
										'code'                 => $code,
										'author'               => $author,
										'version'              => $version,
										'link'                 => $link,
										'xml'                  => $xml,
										'status'               => 0
									];

									$this->model_setting_modification->addModification($modification_data);
								} catch (\Exception $exception) {
									$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
								}
							}
						}
					}
				}
			} else {
				$json['error'] = $this->language->get('error_unzip');
			}
		}

		if (!$json) {
			$json['text'] = $this->language->get('text_vendor');

			$json['next'] = $this->url->link('marketplace/installer.vendor', 'user_token=' . $this->session->data['user_token'], true);
		}


		// Remove any OCMOD modifications
		$this->load->model('setting/modification');

		$this->model_setting_modification->deleteModificationsByExtensionInstallId($extension_install_id);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}


