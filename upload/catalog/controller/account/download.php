<?php
namespace Opencart\Catalog\Controller\Account;
class Download extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('account/download');

		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_downloads'),
			'href' => $this->url->link('account/download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$this->load->model('account/download');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = $this->config->get('config_pagination');

		$data['downloads'] = [];

		$download_total = $this->model_account_download->getTotalDownloads();

		$results = $this->model_account_download->getDownloads(($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			if (is_file(DIR_DOWNLOAD . $result['filename'])) {
				$size = filesize(DIR_DOWNLOAD . $result['filename']);

				$i = 0;

				$suffix = [
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				];

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['downloads'][] = [
					'order_id'   => $result['order_id'],
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'name'       => $result['name'],
					'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
					'href'       => $this->url->link('account/download|download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&download_id=' . $result['download_id'])
				];
			}
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $download_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($download_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($download_total - $limit)) ? $download_total : ((($page - 1) * $limit) + $limit), $download_total, ceil($download_total / $limit));
		
		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/download', $data));
	}

	public function download(): void {
		if (!$this->customer->isLogged() || (!isset($this->request->get['customer_token']) || !isset($this->session->data['customer_token']) || ($this->request->get['customer_token'] != $this->session->data['customer_token']))) {
			$this->session->data['redirect'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('account/download');

		if (isset($this->request->get['download_id'])) {
			$download_id = (int)$this->request->get['download_id'];
		} else {
			$download_id = 0;
		}

		$download_info = $this->model_account_download->getDownload($download_id);

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (is_file($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) {
						ob_end_clean();
					}

					readfile($file, 'rb');

					$this->model_account_download->addReport($download_id, $this->request->server['REMOTE_ADDR']);

					exit();
				} else {
					exit(sprintf($this->language->get('error_not_found'), basename($file)));
				}
			} else {
				exit($this->language->get('error_headers_sent'));
			}
		} else {
			$this->response->redirect($this->url->link('account/download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']));
		}
	}
}
