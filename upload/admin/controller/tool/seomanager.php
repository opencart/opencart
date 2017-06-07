<?php
// *	@copyright	OPENCART.PRO 2011 - 2017.
// *	@forum	http://forum.opencart.pro
// *	@source		See SOURCE.txt for source and other copyright.
// *	@license	GNU General Public License version 3; see LICENSE.txt

class ControllerToolSeoManager extends Controller {
        private $error = array();

        public function index() {
			
			$this->load->model('setting/setting');
			
			$this->load->language('tool/seomanager');
					 
	
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

				$this->model_setting_setting->editSetting('seomanager', $this->request->post);
				
				$this->session->data['success'] = $this->language->get('text_success');

				$this->response->redirect($this->url->link('tool/seomanager', 'token=' . $this->session->data['token'], true));
			}
			
			$this->getList();
		
		}

        public function update() {
			
			$this->load->language('tool/seomanager');
			
			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('tool/seomanager');

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

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
					$this->model_tool_seomanager->updateUrlAlias($this->request->post);
					$this->session->data['success'] = $this->language->get('text_success');
			}
			
			$this->response->redirect($this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				
        }
        
        public function clear() {
			
				$this->load->language('tool/seomanager');
				
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
                $this->cache->delete('seo_pro');
                $this->cache->delete('seo_url');
                $this->session->data['success'] = $this->language->get('text_success_clear');
		$this->response->redirect($this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        public function delete() {
                $this->load->language('tool/seomanager');
                $this->load->model('tool/seomanager');
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

                if (isset($this->request->post['selected']) && $this->validateDelete()) {
                        foreach ($this->request->post['selected'] as $url_alias_id) {
                                $this->model_tool_seomanager->deleteUrlAlias($url_alias_id);
                        }
                        $this->session->data['success'] = $this->language->get('text_success');
                }

                $this->response->redirect($this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url, 'SSL'));
        }

        private function getList() {
			
				$this->load->model('tool/seomanager');
			
                if (isset($this->request->get['sort'])) {
                        $sort = $this->request->get['sort'];
                } else {
                        $sort = 'ua.query';
                }

                if (isset($this->request->get['order'])) {
                        $order = $this->request->get['order'];
                } else {
                        $order = 'ASC';
                }

                if (isset($this->request->get['page'])) {
                        $page = $this->request->get['page'];
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
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url, 'SSL')
				);
				
				//dop
				$data['action'] = $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'], true);

				$data['cancel'] = $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'], true);
				
				$data['token'] = $this->session->data['token'];
				
				$data['entry_name'] = $this->language->get('entry_name');
				$data['entry_html_h1'] = $this->language->get('entry_html_h1');
				$data['entry_meta_title'] = $this->language->get('entry_meta_title');
				$data['entry_meta_description'] = $this->language->get('entry_meta_description');
				$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
				
				if (isset($this->request->post['seomanager_html_h1_special'])) {
					$data['seomanager_html_h1_special'] = $this->request->post['seomanager_html_h1_special'];
				} else {
					$data['seomanager_html_h1_special'] = $this->config->get('seomanager_html_h1_special');
				}
				
				if (isset($this->request->post['seomanager_meta_title_special'])) {
					$data['seomanager_meta_title_special'] = $this->request->post['seomanager_meta_title_special'];
				} else {
					$data['seomanager_meta_title_special'] = $this->config->get('seomanager_meta_title_special');
				}

				if (isset($this->request->post['seomanager_meta_description_special'])) {
					$data['seomanager_meta_description_special'] = $this->request->post['seomanager_meta_description_special'];
				} else {
					$data['seomanager_meta_description_special'] = $this->config->get('seomanager_meta_description_special');
				}

				if (isset($this->request->post['seomanager_meta_keyword_special'])) {
					$data['seomanager_meta_keyword_special'] = $this->request->post['seomanager_meta_keyword_special'];
				} else {
					$data['seomanager_meta_keyword_special'] = $this->config->get('seomanager_meta_keyword_special');
				}
				
				if (isset($this->request->post['seomanager_description_special'])) {
					$data['seomanager_description_special'] = $this->request->post['seomanager_description_special'];
				} else {
					$data['seomanager_description_special'] = $this->config->get('seomanager_description_special');
				}
				
				if (isset($this->request->post['seomanager_html_h1_bestseller'])) {
					$data['seomanager_html_h1_bestseller'] = $this->request->post['seomanager_html_h1_bestseller'];
				} else {
					$data['seomanager_html_h1_bestseller'] = $this->config->get('seomanager_html_h1_bestseller');
				}
				
				if (isset($this->request->post['seomanager_meta_title_bestseller'])) {
					$data['seomanager_meta_title_bestseller'] = $this->request->post['seomanager_meta_title_bestseller'];
				} else {
					$data['seomanager_meta_title_bestseller'] = $this->config->get('seomanager_meta_title_bestseller');
				}

				if (isset($this->request->post['seomanager_meta_description_bestseller'])) {
					$data['seomanager_meta_description_bestseller'] = $this->request->post['seomanager_meta_description_bestseller'];
				} else {
					$data['seomanager_meta_description_bestseller'] = $this->config->get('seomanager_meta_description_bestseller');
				}

				if (isset($this->request->post['seomanager_meta_keyword_bestseller'])) {
					$data['seomanager_meta_keyword_bestseller'] = $this->request->post['seomanager_meta_keyword_bestseller'];
				} else {
					$data['seomanager_meta_keyword_bestseller'] = $this->config->get('seomanager_meta_keyword_bestseller');
				}
				
				if (isset($this->request->post['seomanager_description_bestseller'])) {
					$data['seomanager_description_bestseller'] = $this->request->post['seomanager_description_bestseller'];
				} else {
					$data['seomanager_description_bestseller'] = $this->config->get('seomanager_description_bestseller');
				}
				
				if (isset($this->request->post['seomanager_html_h1_latest'])) {
					$data['seomanager_html_h1_latest'] = $this->request->post['seomanager_html_h1_latest'];
				} else {
					$data['seomanager_html_h1_latest'] = $this->config->get('seomanager_html_h1_latest');
				}
				
				if (isset($this->request->post['seomanager_meta_title_latest'])) {
					$data['seomanager_meta_title_latest'] = $this->request->post['seomanager_meta_title_latest'];
				} else {
					$data['seomanager_meta_title_latest'] = $this->config->get('seomanager_meta_title_latest');
				}

				if (isset($this->request->post['seomanager_meta_description_latest'])) {
					$data['seomanager_meta_description_latest'] = $this->request->post['seomanager_meta_description_latest'];
				} else {
					$data['seomanager_meta_description_latest'] = $this->config->get('seomanager_meta_description_latest');
				}

				if (isset($this->request->post['seomanager_meta_keyword_latest'])) {
					$data['seomanager_meta_keyword_latest'] = $this->request->post['seomanager_meta_keyword_latest'];
				} else {
					$data['seomanager_meta_keyword_latest'] = $this->config->get('seomanager_meta_keyword_latest');
				}
				
				if (isset($this->request->post['seomanager_description_latest'])) {
					$data['seomanager_description_latest'] = $this->request->post['seomanager_description_latest'];
				} else {
					$data['seomanager_description_latest'] = $this->config->get('seomanager_description_latest');
				}
				
				if (isset($this->request->post['seomanager_html_h1_mostviewed'])) {
					$data['seomanager_html_h1_mostviewed'] = $this->request->post['seomanager_html_h1_mostviewed'];
				} else {
					$data['seomanager_html_h1_mostviewed'] = $this->config->get('seomanager_html_h1_mostviewed');
				}
				
				if (isset($this->request->post['seomanager_meta_title_mostviewed'])) {
					$data['seomanager_meta_title_mostviewed'] = $this->request->post['seomanager_meta_title_mostviewed'];
				} else {
					$data['seomanager_meta_title_mostviewed'] = $this->config->get('seomanager_meta_title_mostviewed');
				}

				if (isset($this->request->post['seomanager_meta_description_mostviewed'])) {
					$data['seomanager_meta_description_mostviewed'] = $this->request->post['seomanager_meta_description_mostviewed'];
				} else {
					$data['seomanager_meta_description_mostviewed'] = $this->config->get('seomanager_meta_description_mostviewed');
				}

				if (isset($this->request->post['seomanager_meta_keyword_mostviewed'])) {
					$data['seomanager_meta_keyword_mostviewed'] = $this->request->post['seomanager_meta_keyword_mostviewed'];
				} else {
					$data['seomanager_meta_keyword_mostviewed'] = $this->config->get('seomanager_meta_keyword_mostviewed');
				}
				
				if (isset($this->request->post['seomanager_description_mostviewed'])) {
					$data['seomanager_description_mostviewed'] = $this->request->post['seomanager_description_mostviewed'];
				} else {
					$data['seomanager_description_mostviewed'] = $this->config->get('seomanager_description_mostviewed');
				}
				
				//dop

                $data['insert'] = $this->url->link('tool/seomanager/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['delete'] = $this->url->link('tool/seomanager/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['save'] = $this->url->link('tool/seomanager/update', 'token=' . $this->session->data['token'] . $url, 'SSL');
                $data['clear'] = $this->url->link('tool/seomanager/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');

                $data['url_aliases'] = array();

                $filterdata = array(
            		'sort' => $sort, 
            		'order' => $order, 
            		'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            		'limit' => $this->config->get('config_limit_admin')
            		);

                $url_alias_total = $this->model_tool_seomanager->getTotalUrlAalias();

                $results = $this->model_tool_seomanager->getUrlAaliases($filterdata);

                foreach ($results as $result) {
                        $data['url_aliases'][] = array(
                    		'url_alias_id' => $result['url_alias_id'], 
                    		'query' => $result['query'],
                    		'keyword' => $result['keyword'],
                    		'selected' => isset($this->request->post['selected']) && in_array($result['url_alias_id'], $this->request->post['selected']), 
                    		'action_text' => $this->language->get('text_edit')
                    		);
                }
				
				

                $data['heading_title'] = $this->language->get('heading_title');

                $data['text_no_results'] = $this->language->get('text_no_results');

                $data['column_query'] = $this->language->get('column_query');
                $data['column_keyword'] = $this->language->get('column_keyword');
                $data['column_action'] = $this->language->get('column_action');

                $data['button_insert'] = $this->language->get('button_insert');
                $data['button_delete'] = $this->language->get('button_delete');
                $data['button_save'] = $this->language->get('button_save');
                $data['button_cancel'] = $this->language->get('button_cancel');
                $data['button_clear_cache'] = $this->language->get('button_clear_cache');
				$data['button_edit'] = $this->language->get('button_edit');
				
				$data['entry_name'] = $this->language->get('entry_name');
				$data['entry_html_h1'] = $this->language->get('entry_html_h1');
				$data['entry_meta_title'] = $this->language->get('entry_meta_title');
				$data['entry_meta_description'] = $this->language->get('entry_meta_description');
				$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
				$data['entry_description'] = $this->language->get('entry_description');
				
				$data['tab_seourl'] = $this->language->get('tab_seourl');
				$data['tab_meta'] = $this->language->get('tab_meta');
				$data['tab_special'] = $this->language->get('tab_special');
				$data['tab_bestseller'] = $this->language->get('tab_bestseller');
				$data['tab_latest'] = $this->language->get('tab_latest');
				$data['tab_mostviewed'] = $this->language->get('tab_mostviewed');

                if (isset($this->error['warning'])) {
                        $data['error_warning'] = $this->error['warning'];
                } else {
                        $data['error_warning'] = '';
                }

                if (isset($this->session->data['success'])) {
                        $data['success'] = $this->session->data['success'];

                        unset($this->session->data['success']);
                } else {
                        $data['success'] = '';
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
				
				$data['breadcrumbs'] = array();

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('text_home'),
					'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
				);

				$data['breadcrumbs'][] = array(
					'text' => $this->language->get('heading_title'),
					'href' => $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url, 'SSL')
				);

                $data['sort_query'] = $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . '&sort=ua.query' . $url, 'SSL');
                $data['sort_keyword'] = $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . '&sort=ua.keyword' . $url, 'SSL');

                $url = '';

                if (isset($this->request->get['sort'])) {
                        $url .= '&sort=' . $this->request->get['sort'];
                }

                if (isset($this->request->get['order'])) {
                        $url .= '&order=' . $this->request->get['order'];
                }

                $pagination = new Pagination();
                $pagination->total = $url_alias_total;
                $pagination->page = $page;
                $pagination->limit = $this->config->get('config_limit_admin');
                $pagination->text = $this->language->get('text_pagination');
                $pagination->url = $this->url->link('tool/seomanager', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

                $data['pagination'] = $pagination->render();

                $data['sort'] = $sort;
                $data['order'] = $order;

				$data['header'] = $this->load->controller('common/header');
				$data['column_left'] = $this->load->controller('common/column_left');
				$data['footer'] = $this->load->controller('common/footer');
								
				$this->response->setOutput($this->load->view('tool/seomanager.tpl', $data));
        }

        private function validateForm() {
                if (!$this->user->hasPermission('modify', 'tool/seomanager')) {
                        $this->error['warning'] = $this->language->get('error_permission');
                }
                if (!$this->error) {
                        return true;
                } else {
                        return false;
                }
        }

        private function validateDelete() {
                if (!$this->user->hasPermission('modify', 'tool/seomanager')) {
                        $this->error['warning'] = $this->language->get('error_permission');
                }
                if (!$this->error) {
                        return true;
                } else {
                        return false;
                }
        }

        public function install() {
                $this->load->model('tool/seomanager');
                $this->model_tool_seomanager->install();

        }

        public function uninstall() {
                $this->load->model('tool/seomanager');
                $this->model_tool_seomanager->uninstall();
        }

}
?>
