<?php
CLass ControllerBlogEvent extends Controller {
	function index() {
		$this->event->register('controller/blog/blog', new Action());

	}
}