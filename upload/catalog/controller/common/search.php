<?php
namespace Opencart\Application\Controller\Common;
class Search extends \Opencart\System\Engine\Controller {
	public function index() {
		$this->load->language('common/search');

		$data['text_search'] = $this->language->get('text_search');

		if (isset($this->request->get['search'])) {
			$data['search'] = $this->request->get['search'];
		} else {
			$data['search'] = '';
		}

		return $this->load->view('common/search', $data);
	}
}