<?php
class ControllerCommonFooter extends \Engine\Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['text_footer'] = sprintf($this->language->get('text_footer'), VERSION);

		return $this->load->view('common/footer.tpl', $data);
	}
}