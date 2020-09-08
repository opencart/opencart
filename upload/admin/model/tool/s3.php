<?php
/*
Amazon S3 system wil be used for storing cloud data
*/
namespace Opencart\Application\Model\Tool;
class S3 extends \Opencart\System\Engine\Model {
	public function addFile($file, $path) {
		try {

			$option = [
				'version'     => 'latest',
				'region'      => 'us-west-2',
				'credentials' => false
			];

			$s3 = new Aws\S3\S3Client($option);
			$s3->completeMultipartUpload($option);

		} catch (Exception $exception) {
			// Catch the error to display and log
			$this->log->write($exception->getCode() . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine());
		}
	}

	public function getFile($file, $file) {
		try {

			$option = [
				'version'     => 'latest',
				'region'      => 'us-west-2',
				'credentials' => false
			];

			$s3 = new Aws\S3\S3Client($option);
			$s3->completeMultipartUpload($option);

		} catch (Exception $exception) {
			// Catch the error to display and log
			$this->log->write($exception->getCode() . ': ' . $exception->getMessage() . ' in ' . $exception->getFile() . ' on line ' . $exception->getLine());
		}
	}
}