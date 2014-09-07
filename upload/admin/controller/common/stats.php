<?php
class ControllerCommonStats extends Controller {
	public function index() {
		/*
		ob_start();
		passthru('typeperf -sc 1 "\processor(_total)\% processor time"',$status);
		$content = ob_get_contents();
		ob_end_clean();
		if ($status === 0) {
		if (preg_match("/\,\"([0-9]+\.[0-9]+)\"/",$content,$load)) {
		if ($load[1] > get_config('busy_error')) {
		header('HTTP/1.1 503 Too busy, try again later');
		die('Server too busy. Please try again later.');
		}
		}
		}
		
		$str = substr(strrchr(shell_exec("uptime"),":"),1);
		
		print_r(array_map("trim",explode(",",$str)));	
		///	sys_getloadavg()		
		//return $this->load->view('common/menu.tpl', $data);
		*/
	}
}