<?php
namespace Opencart\Catalog\Controller\Common;

class Home extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('common/home');

		$this->document->setTitle($this->language->get('heading_title'));

		$header = $this->load->controller('common/header');
		$home   = $this->load->view('common/home', []);
		$footer = $this->load->controller('common/footer');

		$output  = '<!DOCTYPE html>';
		$output .= '<html lang="en">';
		$output .= '<head>';
		$output .= '<meta charset="UTF-8"/>';
		$output .= '<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>';
		$output .= '<base href="' . $this->config->get('config_url') . '"/>';
		$output .= '<title>' . $this->document->getTitle() . '</title>';
		$output .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>';
		$output .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>';
		$output .= '</head>';
		$output .= '<body class="d-flex flex-column min-vh-100">';
		$output .= $header;
		$output .= '<main class="flex-grow-1 mb-4">' . $home . '</main>';
		$output .= $footer;
		$output .= '</body>';
		$output .= '</html>';

		return $output;
	}
}
