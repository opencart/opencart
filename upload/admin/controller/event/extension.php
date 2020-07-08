<?php
class ControllerEventExtension {
	public function controller() {
		$results = $this->setting_event->getPaths('admin/controller/');


		$results = $this->setting_extension->getPaths('admin/controller/');



	}

	public function model() {


	}

	public function view() {


	}
}