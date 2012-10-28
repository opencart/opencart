<?php
class ControllerFooter extends Controller {
	public function index() {
		$this->template = 'footer.tpl';

		$this->render();
	}
}
?>