<?php
namespace Opencart\admin\controller\blog;
/**
 * Class Author
 *
 * @package Opencart\Admin\Controller\Design
 */
class Author extends \Opencart\System\Engine\Controller
{

	/**
	 *
	 */
	public function index(): void
	{
		$this->load->language('blog/author');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('blog/author', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('blog/author.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('blog/author.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();


		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('blog/author', $data));
	}


	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('blog/author');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'blog_author_id';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('blog/author.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['blog_authors'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('tool/image');
		$this->load->model('blog/author');

		$author_total = $this->model_blog_author->getTotalAuthors();

		$results = $this->model_blog_author->getAuthors($filter_data);

		foreach ($results as $result) {
			if(!empty($result['photo'])){
				$result['photo'] = $this->model_tool_image->resize($result['photo'], 136, 136);
			}else{
				$result['photo'] = $this->model_tool_image->resize('no_image.png', 136, 136);
			}

			$data['blog_authors'][] = [
				'blog_author_id'   => $result['blog_author_id'],
				'fullname'      => $result['fullname'],
				'email' => $result['email'],
				'photo' => $result['photo'],
				'post_count' => $result['post_count'],
				'edit'       => $this->url->link('blog/author.form', 'user_token=' . $this->session->data['user_token'] . '&blog_author_id=' . $result['blog_author_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('blog/author.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_status'] = $this->url->link('blog/author.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $author_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('blog/author.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($author_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($author_total - $this->config->get('config_pagination_admin'))) ? $author_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $author_total, ceil($author_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('blog/author_list', $data);
	}


	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('blog/author');

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['blog_author_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('blog/author', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('blog/author.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('blog/author', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['blog_author_id'])) {
			$this->load->model('blog/author');

			$blog_author_info = $this->model_blog_author->getAuthor($this->request->get['blog_author_id']);
		}

		if (isset($this->request->get['blog_author_id'])) {
			$data['blog_author_id'] = (int)$this->request->get['blog_author_id'];
		} else {
			$data['blog_author_id'] = 0;
		}

		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 136, 136);
		if(!empty($blog_author_info)){
			$data['fullname'] = $blog_author_info['fullname'];
			$data['email'] = $blog_author_info['email'];
			if(!empty($blog_author_info['photo'])){
				$data['photo'] = $this->model_tool_image->resize($blog_author_info['photo'], 136, 136);
				$data['original_photo'] = $blog_author_info['photo'];
			}
			$data['post_count'] = $blog_author_info['post_count'];
		}else{
			$data['fullname'] = '';
			$data['email'] = '';
			$data['photo'] = $data['placeholder'];
			$data['original_photo'] = '';
			$data['post_count'] = null;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('blog/author_form', $data));
	}


	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('blog/author');

		$json = [];

		if (!$this->user->hasPermission('modify', 'blog/author')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['fullname']) < 3) || (oc_strlen($this->request->post['fullname']) > 255)) {
			$json['error']['fullname'] = $this->language->get('error_fullname');
		}

		$email = $this->request->post['email'] ?? '';
		$email = trim($email);
		if(oc_strlen($email) > 0){
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$json['error']['email'] = $this->language->get('error_email');
			}
		}

		if (!$json) {
			$this->load->model('blog/author');

			if (!$this->request->post['blog_author_id']) {
				$json['blog_author_id'] = $this->model_blog_author->add($this->request->post);
			} else {
				$this->model_blog_author->edit($this->request->post['blog_author_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('blog/author');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'blog/author')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('blog/author');

			foreach ($selected as $blog_author_id) {
				$this->model_blog_author->delete($blog_author_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('blog/author');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			];

			$results = $this->model_blog_author->getAuthors($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'blog_author_id' => $result['blog_author_id'],
					'name'            => strip_tags(html_entity_decode($result['fullname'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
