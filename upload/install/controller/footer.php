<?php
class ControllerFooter extends Controller {
	public function index() {
		$this->id       = 'footer';
		$this->template = 'footer.tpl';

		$this->render();
	}
}
?>