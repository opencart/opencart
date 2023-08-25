<?php
namespace Opencart\admin\controller\blog;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Design
 */
class Article extends \Opencart\System\Engine\Controller
{

	/**
	 *
	 */
	public function index(): void
	{
		$this->load->language('blog/article');

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
			'href' => $this->url->link('blog/article', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('blog/article.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('blog/article.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();


		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('blog/article', $data));
	}


	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('blog/article');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'blog_article_id';
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

		$data['action'] = $this->url->link('blog/article.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['blog_articles'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('tool/image');
		$this->load->model('blog/article');

		$article_total = $this->model_blog_article->getTotalArticles($filter_data);

		$results = $this->model_blog_article->getArticles($filter_data);
		$data['image_placeholder'] = $this->model_tool_image->resize('no_image.png', 136, 136);

		foreach ($results as $result) {
			if(!empty($result['image'])){
				$result['image'] = $this->model_tool_image->resize($result['image'], 136, 136);
			}else{
				$result['image'] = $data['image_placeholder'];
			}

			$data['blog_articles'][] = [
				'blog_article_id'   => $result['blog_article_id'],
				'image' 			=> $result['image'],
				'name'      		=> $result['name'],
				'author_name' 		=> $result['author_name'],
				'view_count' 		=> $result['view_count'],
				'date_added' 		=> date($this->language->get('date_format_long'), strtotime($result['date_added'])),
				'edit'       		=> $this->url->link('blog/article.form', 'user_token=' . $this->session->data['user_token'] . '&blog_article_id=' . $result['blog_article_id'] . $url)
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

		$data['sort_name'] = $this->url->link('blog/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_status'] = $this->url->link('blog/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $article_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('blog/article.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($article_total - $this->config->get('config_pagination_admin'))) ? $article_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $article_total, ceil($article_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('blog/article_list', $data);
	}


	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('blog/article');

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');
		$this->document->addScript('view/javascript/bootstrap/js/bootstrap-tags.min.js');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['blog_article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('blog/article', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('blog/article.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('blog/article', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['blog_article_id'])) {
			$this->load->model('blog/article');

			$blog_article_info = $this->model_blog_article->getArticle($this->request->get['blog_article_id']);
		}

		if (isset($this->request->get['blog_article_id'])) {
			$data['blog_article_id'] = (int)$this->request->get['blog_article_id'];
		} else {
			$data['blog_article_id'] = 0;
		}

		$this->load->model('tool/image');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 136, 136);
		if(!empty($blog_article_info)){
			$data['name'] = $blog_article_info['name'];
			$data['author_name'] = $blog_article_info['author_name'];
			$data['blog_author_id'] = $blog_article_info['blog_author_id'];
			if(!empty($blog_article_info['image'])){
				$data['image'] = $this->model_tool_image->resize($blog_article_info['image'], 136, 136);
				$data['original_image'] = $blog_article_info['image'];
			}else{
				$data['image'] = $data['placeholder'];
				$data['original_image'] = '';
			}
			$data['view_count'] = $blog_article_info['view_count'];
			$data['status'] = $blog_article_info['status'];
		}else{
			$data['name'] = '';
			$data['author_name'] = '';
			$data['blog_author_id'] = null;
			$data['image'] = $data['placeholder'];
			$data['original_image'] = '';
			$data['view_count'] = null;
			$data['status'] = 1;
		}

		// Article Tags
		if (!empty($blog_article_info)) {
			$data['tags']  = $this->model_blog_article->getAllTags(intval($this->request->get['blog_article_id']));
		} else {
			$data['tags'] = [];
		}

		// Article Stores
		if (!empty($blog_article_info)) {
			$article_stores = $this->model_blog_article->getStores($this->request->get['blog_article_id']);
		} else {
			$article_stores = [
				[
					'store_id' 		=> 0,
					'store_name' 	=> $this->language->get('text_default'),
					'view_count' 	=> 0
				]
			];
		}

		$data['article_stores'] = [];
		$data['article_store_id'] = [];

		foreach ($article_stores as $article_store) {
			$data['article_stores'][] = [
				'store_id'    	=> 0,
				'store_name'    => $article_store['store_name'],
				'view_count'   	=> $article_store['view_count'],
			];
			$data['article_store_id'][] = $article_store['store_id'];
		}

		// Article Contents
		if (!empty($blog_article_info)) {
			$article_contents = $this->model_blog_article->getContents($this->request->get['blog_article_id']);
		} else {
			$article_contents = [];
		}

		$data['blog_article_content'] = [];

		foreach ($article_contents as $language_id => $article_content) {
			$data['blog_article_content'][$language_id] = [
				'title'      	=> $article_content['title'],
				'description'   => $article_content['description'],
				'content'     	=> $article_content['content']
			];
		}

		// Stores
		$this->load->model('setting/store');

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('blog/article_form', $data));
	}


	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('blog/article');

		$json = [];

		if (!$this->user->hasPermission('modify', 'blog/article')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['name']) < 3) || (oc_strlen($this->request->post['name']) > 255)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		$this->request->post['status'] = $this->request->post['status'] === '1';

		if (!$json) {
			$this->load->model('blog/article');

			if (!$this->request->post['blog_article_id']) {
				$json['blog_article_id'] = $this->model_blog_article->add($this->request->post);
			} else {
				$this->model_blog_article->edit($this->request->post['blog_article_id'], $this->request->post);
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
		$this->load->language('blog/article');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'blog/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('blog/article');

			foreach ($selected as $blog_article_id) {
				$this->model_blog_article->delete($blog_article_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
