<?php
namespace Opencart\Catalog\Controller\Tool;
/**
 * Class Upload
 *
 * @package Opencart\Catalog\Controller\Tool
 */
class Upload extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('tool/upload');

		$json = [];

		// Validate the filename length
		if (!isset($this->request->get['upload_token']) || !isset($this->session->data['upload_token']) || ($this->session->data['upload_token'] != $this->request->get['upload_token'])) {
			$json['error'] = $this->language->get('error_token');
		}

		if (!$json) {
			if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
				// Sanitize the filename
				$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

				// Validate the filename length
				if (!oc_validate_length($filename, 3, 64)) {
					$json['error'] = $this->language->get('error_filename');
				}

				// Allowed file extension types
				if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $this->config->get('upload_type_allowed'))) {
					$json['error'] = $this->language->get('error_file_type');
				}

				// Allowed file mime types
				if (!in_array($this->request->files['file']['type'], $this->config->get('upload_mime_allowed'))) {
					$json['error'] = $this->language->get('error_file_type');
				}

				// Check that the file content matches the image type claimed by the file extension
				$images = [
					'png'  => "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A",
					'webp' => "\x52\x49\x46\x46", // RIFF
					'jpe'  => "\xFF\xD8\xFF",
					'jpeg' => "\xFF\xD8\xFF",
					'jpg'  => "\xFF\xD8\xFF",
					'gif'  => "\x47\x49\x46\x38", // GIF8
					'bmp'  => "\x42\x4D"          // BM
				];

				$extension = strtolower(substr(strrchr($filename, '.'), 1));

				if (isset($images[$extension])) {
					$handle = fopen($this->request->files['file']['tmp_name'], 'r');

					$header = fread($handle, 12);

					fclose($handle);

					if (!str_starts_with($header, $images[$extension]) || ($extension == 'webp' && substr($header, 8, 4) != 'WEBP')) {
						$json['error'] = $this->language->get('error_file_type');
					}
				}

				// Return any upload error
				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = $this->language->get('error_upload');
			}
		}

		if (!$json) {
			$file = $filename . '.' . oc_token(32);

			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);

			// Hide the uploaded file name, so people cannot link to it directly.
			$this->load->model('tool/upload');

			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);

			$json['success'] = $this->language->get('text_upload');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
