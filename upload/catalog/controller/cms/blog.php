<?php
namespace Opencart\Catalog\Controller\cms;
/**
 * Class Blog
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Blog extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/blog');

		if (isset($this->request->get['search'])) {
			$search = (string)$this->request->get['search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['blog_category_id'])) {
			$blog_category_id = (int)$this->request->get['blog_category_id'];
		} else {
			$blog_category_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
		];

		$this->load->model('cms/blog_category');
		$this->load->model('tool/image');

		$blog_category_info = $this->model_cms_blog_category->getBlogCategory($blog_category_id);

		if ($blog_category_info) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . (string)$this->request->get['search'];
			}

			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . (int)$this->request->get['blog_category_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = [
				'text' => $blog_category_info['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];
		}

		if ($blog_category_info) {
			$this->document->setTitle($blog_category_info['meta_title']);
			$this->document->setDescription($blog_category_info['meta_description']);
			$this->document->setKeywords($blog_category_info['meta_keyword']);

			$data['heading_title'] = $blog_category_info['name'];

			$data['description'] = html_entity_decode($blog_category_info['description'], ENT_QUOTES, 'UTF-8');

			if (is_file(DIR_IMAGE . html_entity_decode($blog_category_info['image'], ENT_QUOTES, 'UTF-8'))) {
				$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($blog_category_info['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
			} else {
				$data['thumb'] = '';
			}
		} else {
			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
			$data['description'] = '';
			$data['thumb'] = '';
		}



		$limit = 20;

		$data['blogs'] = [];

		$filter_data = [
			'filter_search'           => $search,
			'filter_blog_category_id' => $blog_category_id,
			'start'                   => ($page - 1) * $limit,
			'limit'                   => $limit
		];

		$this->load->model('cms/blog');

		$blog_total = $this->model_cms_blog->getTotalBlogs($filter_data);

		$results = $this->model_cms_blog->getBlogs($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
			} else {
				$image = '';
			}

			$data['blogs'][] = [
				'blog_id'     => $result['blog_id'],
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => oc_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_product_description_length')) . '..',
				'href'        => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&blog_id=' . $result['blog_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $blog_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($blog_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($blog_total - $limit)) ? $blog_total : ((($page - 1) * $limit) + $limit), $blog_total, ceil($blog_total / $limit));

		// http://googlewebmastercentral.blogspot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language')), 'canonical');
		} else {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
		}

		if (ceil($blog_total / $limit) > $page) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. ($page + 1)), 'next');
		}

		$data['search'] = $search;
		$data['blog_category_id'] = $blog_category_id;
		$data['blog_categories'] = $this->model_cms_blog_category->getBlogCategories();

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('cms/blog_list', $data));
	}

	public function info(): object|null {
		$this->load->language('cms/blog');

		if (isset($this->request->get['blog_id'])) {
			$blog_id = (int)$this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}

		if (isset($this->request->get['blog_category_id'])) {
			$blog_category_id = (int)$this->request->get['blog_category_id'];
		} else {
			$blog_category_id = 0;
		}

		$this->load->model('cms/blog');

		$blog_info = $this->model_cms_blog->getBlog($blog_id);

		if ($blog_info) {
			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_blog'),
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];

			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . $this->request->get['search'];
			}

			if (isset($this->request->get['blog_category_id'])) {
				$url .= '&blog_category_id=' . $this->request->get['blog_category_id'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->load->model('cms/blog_category');

			$blog_category_info = $this->model_cms_blog_category->getBlogCategory($blog_category_id);

			if ($blog_category_info) {
				$data['breadcrumbs'][] = [
					'text' => $blog_category_info['name'],
					'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
				];
			}

			$this->document->setTitle($blog_info['meta_title']);
			$this->document->setDescription($blog_info['meta_description']);
			$this->document->setKeywords($blog_info['meta_keyword']);

			$data['breadcrumbs'][] = [
				'text' => $blog_info['name'],
				'href' => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&blog_id=' .  $blog_id . $url)
			];

			$data['heading_title'] = $blog_info['name'];

			$data['description'] = html_entity_decode($blog_info['description'], ENT_QUOTES, 'UTF-8');

			$data['continue'] = $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cms/blog_info', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}
	/*
	public function comment() {
		$this->load->model('cms/blog');

		if (isset($this->request->get['blog_id'])) {
			$blog_id = $this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['page'] > 0) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['comments'] = array();

		$comment_total = $this->model_cms_blog->getTotalComments($blog_id);

		$results = $this->model_cms_blog->getBlogComments($blog_id, ($page - 1) * 20, 20);

		foreach ($results as $result) {
			// Do the replies first so not to interfere with below results
			$reply_data = array();

			$reply_total = $this->model_cms_blog->getTotalBlogReplies($blog_id, $result['blog_comment_id']);

			$replies = $this->model_cms_blog->getBlogReplies($blog_id, $result['blog_comment_id'], 0, 5);

			foreach ($replies as $reply) {
				if ($reply['image']) {
					$image = $reply['image'];
				} else {
					$image = $this->config->get('config_image_placeholder');
				}

				$second = time() - strtotime($reply['date_added']);

				if ($second < 10) {
					$date_added = 'just now';
				} elseif ($second) {
					$date_added = $second . ' seconds ago';
				}

				$minute = floor($second / 60);

				if ($minute == 1) {
					$date_added = $minute . ' minute ago';
				} elseif ($minute) {
					$date_added = $minute . ' minutes ago';
				}

				$hour = floor($minute / 60);

				if ($hour == 1) {
					$date_added = $hour . ' hour ago';
				} elseif ($hour) {
					$date_added = $hour . ' hours ago';
				}

				$day = floor($hour / 24);

				if ($day == 1) {
					$date_added = $day . ' day ago';
				} elseif ($day) {
					$date_added = $day . ' days ago';
				}

				$week = floor($day / 7);

				if ($week == 1) {
					$date_added = $week . ' week ago';
				} elseif ($week) {
					$date_added = $week . ' weeks ago';
				}

				$month = floor($week / 4);

				if ($month == 1) {
					$date_added = $month . ' month ago';
				} elseif ($month) {
					$date_added = $month . ' months ago';
				}

				$year = floor($week / 52.1429);

				if ($year == 1) {
					$date_added = $year . ' year ago';
				} elseif ($year) {
					$date_added = $year . ' years ago';
				}

				if ($this->member->isModerator()) {
					$remove = $this->url->link('cms/blog/removecomment', (isset($this->session->data['member_token']) ? 'member_token=' . $this->session->data['member_token'] : '') . '&blog_id=' . $blog_id . '&blog_comment_id=' . $reply['blog_comment_id']);
				} else {
					$remove = '';
				}

				$reply_data[] = array(
					'blog_comment_id' => $result['blog_comment_id'],
					'blog_id'         => $reply['blog_id'],
					'member'          => $reply['member'],
					'image'           => ($this->request->server['HTTPS'] ? 'https://' : 'http://') . 'image.opencart.com/cache/' . substr($image, 0, strrpos($image, '.')) . '-resize-' . $this->config->get('config_image_member_width') . 'x' .  $this->config->get('config_image_member_height') . '.jpg',
					'comment'         => preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" rel="nofollow" target="_blank">$0</a>', nl2br(strip_tags($reply['comment']))),
					'date_added'      => $date_added,
					'remove'          => $remove
				);
			}

			if ($result['image']) {
				$image = $result['image'];
			} else {
				$image = $this->config->get('config_image_placeholder');
			}

			$second = time() - strtotime($result['date_added']);

			if ($second < 10) {
				$date_added = 'just now';
			} elseif ($second) {
				$date_added = $second . ' seconds ago';
			}

			$minute = floor($second / 60);

			if ($minute == 1) {
				$date_added = $minute . ' minute ago';
			} elseif ($minute) {
				$date_added = $minute . ' minutes ago';
			}

			$hour = floor($minute / 60);

			if ($hour == 1) {
				$date_added = $hour . ' hour ago';
			} elseif ($hour) {
				$date_added = $hour . ' hours ago';
			}

			$day = floor($hour / 24);

			if ($day == 1) {
				$date_added = $day . ' day ago';
			} elseif ($day) {
				$date_added = $day . ' days ago';
			}

			$week = floor($day / 7);

			if ($week == 1) {
				$date_added = $week . ' week ago';
			} elseif ($week) {
				$date_added = $week . ' weeks ago';
			}

			$month = floor($week / 4);

			if ($month == 1) {
				$date_added = $month . ' month ago';
			} elseif ($month) {
				$date_added = $month . ' months ago';
			}

			$year = floor($week / 51);

			if ($year == 1) {
				$date_added = $year . ' year ago';
			} elseif ($year) {
				$date_added = $year . ' years ago';
			}

			if ($reply_total > 5) {
				$next = $this->url->link('cms/blog/reply', (isset($this->session->data['member_token']) ? 'member_token=' . $this->session->data['member_token'] : '') . '&blog_id=' . $blog_id . '&parent_id=' . $result['blog_comment_id'] . '&page=2');
			} else {
				$next = '';
			}

			if ($this->member->isModerator()) {
				$remove = $this->url->link('cms/blog/removecomment', (isset($this->session->data['member_token']) ? 'member_token=' . $this->session->data['member_token'] : '') . '&blog_id=' . $blog_id . '&blog_comment_id=' . $result['blog_comment_id']);
			} else {
				$remove = '';
			}

			$data['comments'][] = array(
				'blog_comment_id'      => $result['blog_comment_id'],
				'member'               => $result['member'],
				'image'                => ($this->request->server['HTTPS'] ? 'https://' : 'http://') . 'image.opencart.com/cache/' . substr($image, 0, strrpos($image, '.')) . '-resize-' . $this->config->get('config_image_member_width') . 'x' .  $this->config->get('config_image_member_height') . '.jpg',
				'comment'              => preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" rel="nofollow" target="_blank">$0</a>', nl2br(strip_tags($result['comment']))),
				'date_added'           => $date_added,
				'reply'                => $reply_data,
				'add'                  => $this->url->link('cms/blog/addcomment', (isset($this->session->data['member_token']) ? 'member_token=' . $this->session->data['member_token'] : '') . '&blog_id=' . $result['blog_id'] . '&parent_id=' . $result['blog_comment_id']),
				'refresh'              => $this->url->link('cms/blog/reply', (isset($this->session->data['member_token']) ? 'member_token=' . $this->session->data['member_token'] : '') . '&blog_id=' . $blog_id . '&parent_id=' . $result['blog_comment_id'] . '&page=1'),
				'next'                 => $next,
				'remove'               => $remove
			);
		}

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $comment_total,
			'page'  => $page,
			'limit' => 20,
			'url'   => $this->url->link('cms/blog/comment', 'blog_id=' . $blog_id . '&page={page}')
		));

		$data['refresh'] = $this->url->link('cms/blog/comment', 'blog_id=' . $blog_id . '&page=' . $page);

		$data['logged'] = $this->member->isLogged();

		// Comment
		$data['comment_username'] = $this->member->getUsername();

		if ($this->member->isLogged()) {
			$image = $this->member->getImage();
		} else {
			$image = $this->config->get('config_image_placeholder');
		}

		$data['comment_image'] = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . '//image.opencart.com/cache/' . substr($image, 0, strrpos($image, '.')) . '-resize-' . $this->config->get('config_image_member_width') . 'x' .  $this->config->get('config_image_member_height') . '.jpg';

		$this->response->setOutput($this->load->view('cms/blog_comment', $data));
	}

	public function addComment() {
		$json = array();

		$this->load->model('cms/blog');

		if (isset($this->request->get['blog_id'])) {
			$blog_id = $this->request->get['blog_id'];
		} else {
			$blog_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = $this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		$blog_info = $this->model_cms_blog->getBlog($blog_id);

		if (!$blog_info) {
			$json['error'] = 'Warning: You must be logged in to comment!';
		}
			if (!$this->member->isLogged()) {
				$json['error'] = 'Warning: You must be logged in to comment!';
			}

			if (!isset($this->request->get['member_token']) || !isset($this->session->data['member_token']) || ($this->request->get['member_token'] != $this->session->data['member_token'])) {
				$json['error'] = 'Invalid token session. Please login again.';
			}

			if (!isset($this->request->post['comment']) || (utf8_strlen($this->request->post['comment']) < 2) || (utf8_strlen($this->request->post['comment']) > 1000)) {
				$json['error'] = 'Error: Comment must be greater than 2 and less than 1000 characters!';
			}

			if (!$json) {
				// Anti-Spam
				$comment = str_replace(' ' , '', $this->request->post['comment']);

				$this->load->model('fraud/spam');

				$spam = $this->model_fraud_spam->getSpam($comment);

				if (!$this->member->isCommentor() || $spam) {
					$status = 0;
				} else {
					$status = 1;
				}

				$this->model_cms_blog->addBlogComment($blog_id, $parent_id, $this->request->post['comment'], $status);

				if (!$status) {
					$json['success'] = 'Your comment has been added to our moderation queue!';
				} else {
					$json['success'] = 'Thank you for your comment!';
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
*/
}