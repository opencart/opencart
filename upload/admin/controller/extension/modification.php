<?php
class ControllerExtensionModification extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');

		$this->getList();
	}

	public function delete() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $modification_id) {
				$this->model_setting_modification->deleteModification($modification_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	/* A big thanks to Qphoria and mhcwebdesign for this part of the code! */
	public function refresh() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');

		if ($this->validate()) {
			// Log
			$log = new Log('vqmod.log');

			// Clear all modification files
			$files = glob(DIR_MODIFICATION . '{*.php,*.tpl}', GLOB_BRACE);

			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
			
			// Begin
			$xml = array();
			
			// Load the default modification XML
			$xml[] = file_get_contents(DIR_SYSTEM . 'modification.xml');
	
			// Get the default modification file
			$results = $this->model_setting_modification->getModifications();
	
			foreach ($results as $result) {
				if ($result['status']) {
					$xml[] = $result['code'];
				}
			}
			
			$modification = array();
			
			foreach ($xml as $xml) {
				$dom = new DOMDocument('1.0', 'UTF-8');
				$dom->preserveWhiteSpace = false;
				$dom->loadXml($xml);
				
				$version = '2.4.1';
				
				$modification_node = $dom->getElementsByTagName('modification')->item(0);
				$file_nodes = $modification_node->getElementsByTagName('file');
				$modification_id = $modification_node->getElementsByTagName('id')->item(0)->nodeValue;
				
				$vqmver = $modification_node->getElementsByTagName('vqmver')->item(0);
				
				if ($vqmver) {
					$version_check = $vqmver->getAttribute('required');
					
					if (strtolower($version_check) == 'true') {
						if (version_compare($version, $vqmver->nodeValue, '<')) {
							$message  = 'Modification::write - VQMOD VERSION \'' . $vqmver->nodeValue . '\' OR ABOVE REQUIRED, XML FILE HAS BEEN SKIPPED' . "\n";
							$message .= 'Modification ID = \'' . $modification_id . '\'' . "\n";
							
							$log->write($message);
							return;
						}
					}
				}
		
				foreach ($file_nodes as $file_node) {
					$path = '';
					
					// Get the full path of the files that are going to be used for modification
					if (substr($file->getAttribute('name'), 0, 7) == 'catalog') {
						$path = DIR_CATALOG . substr($file->getAttribute('name'), 8);
					} 
					
					if (substr($file->getAttribute('name'), 0, 5) == 'admin') {
						$path = DIR_APPLICATION . substr($file->getAttribute('name'), 6);
					} 
					
					if (substr($file->getAttribute('name'), 0, 6) == 'system') {
						$path = DIR_SYSTEM . substr($file->getAttribute('name'), 7);
					}
										
					$files = glob($path . $file_node->getAttribute('name'));
					
					if ($files === false) {
						$files = array();
					}
					
					$operation_nodes = $file_node->getElementsByTagName('operation');
					$file_node_error = $file_node->getAttribute('error');
		
					foreach ($files as $file) {
						if (!isset($modification[$file])) {
							$modification[$file] = file_get_contents($file);
						}
		
						foreach ($operation_nodes as $operation_node) {
							$operation_node_error = $operation_node->getAttribute('error');
							
							if (($operation_node_error != 'skip') && ($operation_node_error != 'log')) {
								$operation_node_error = 'abort';
							}
		
							$ignoreif_node = $operation_node->getElementsByTagName('ignoreif')->item(0);
							
							if ($ignoreif_node) {
								$ignoreif_node_regex = $ignoreif_node->getAttribute('regex');
								$ignoreif_node_value = trim($ignoreif_node_value->nodeValue);
								
								if ($ignoreif_node_regex == 'true') {
									if (preg_match($ignoreif_node_value, $modification[$file])) {
										continue;
									}
								} else {
									if (strpos($tmp, $ignoreif_node_value) !== false) {
										continue;
									}
								}
							}
		
							$search_node = $operation_node->getElementsByTagName('search')->item(0);
							$search_node_position = ($search_node->getAttribute('position')) ? $search_node->getAttribute('position') : 'replace';
							$search_node_indexes = $this->vqmodGetIndexes($search_node->getAttribute('index'));
							$search_node_offset = ($search_node->getAttribute('offset')) ? $search_node->getAttribute('offset') : '0';
							$search_node_regex = ($search_node->getAttribute('regex')) ? $search_node->getAttribute('regex') : 'false';
							$search_node_trim = ($search_node->getAttribute('trim') == 'false') ? 'false' : 'true';
							$search_node_value = ($search_node_trim=='true') ? trim($search_node->nodeValue) : $search_node->nodeValue;
		
							$add_node = $operation_node->getElementsByTagName('add')->item(0);
							$add_node_trim = ($add_node->getAttribute('trim') == 'false') ? 'false' : 'true';
							$add_node_value = ($add_node_trim == 'true') ? trim($add_node->nodeValue) : $add_node->nodeValue;
		
							$index_count = 0;
							$tmp = explode("\n", $modification[$file]);
							$line_max = count($tmp) - 1;
		
							// apply the next search and add operation to the file content
							switch ($search_node_position) {
								case 'top':
									$tmp[(int)$search_node_offset] = $add_node_value . $tmp[(int)$search_node_offset];
									break;
								case 'bottom':
									$offset = $lineMax - (int)$search_node_offset;
									
									if ($offset < 0) {
										$tmp[-1] = $add_node_value;
									} else {
										$tmp[$offset] .= $add_node_value;
									}
									break;
								default:
									$changed = false;
									
									foreach ($tmp as $line_num => $line) {
										if (strlen($search_node_value) == 0) {
											if ($operation_node_error == 'log' || $operation_node_error == 'abort') {
												$message  = 'Modification::write - EMPTY SEARCH CONTENT ERROR:' . "\n";
												$message .= 'Modification ID = \'' . $modification_id . '\'' . "\n";
												$message .= 'Filename = \'' . $file_node->getAttribute('name') . '\'' . "\n";
												
												$log->write($message);
											}
											
											break;
										}
		
										if ($search_node_regex == 'true') {
											$pos = @preg_match($search_node_value, $line);
											
											if ($pos === false) {
												if ($operation_node_error == 'log' || $operation_node_error == 'abort') {
													$message  = 'Modification::write - INVALID REGEX ERROR:' . "\n";
													$message .= 'Modification ID = \'' . $modification_id . '\'' . "\n";
													$message .= 'Filename = \'' . $file_node->getAttribute('name') . '\'' . "\n";
													$message .= 'Search = \'' . $search_node_value . '\'' . "\n";
													
													$log->write($message);
												}
												continue 2; // continue with next operation_node
											} elseif ($pos == 0) {
												$pos = false;
											}
										} else {
											$pos = strpos($line, $search_node_value);
										}
		
										if ($pos !== false) {
											$index_count++;
											$changed = true;
		
											if (!$search_node_indexes || ($search_node_indexes && in_array($index_count, $search_node_indexes))) {
												switch ($search_node_position) {
													case 'before':
														$offset = ($line_num - $search_node_offset < 0) ? -1 : $line_num - $search_node_offset;
														$tmp[$offset] = empty($tmp[$offset]) ? $add_node_value : $add_node_value . "\n" . $tmp[$offset];
														break;
													case 'after':
														$offset = ($line_num + $search_node_offset > $line_max) ? $line_max : $line_num + $search_node_offset;
														$tmp[$offset] = $tmp[$offset] . "\n" . $add_node_value;
														break;
													case 'ibefore':
														$tmp[$line_num] = str_replace($search_node_value, $add_node_value . $search_node_value, $line);
														break;
													case 'iafter':
														$tmp[$line_num] = str_replace($search_node_value, $search_node_value . $add_node_value, $line);
														break;
													default:
														if (!empty($search_node_offset)) {
															for ($i = 1; $i <= $search_node_offset; $i++) {
																if (isset($tmp[$line_num + $i])) {
																	$tmp[$line_num + $i] = '';
																}
															}
														}
														
														if ($search_node_regex == 'true') {
															$tmp[$line_num] = preg_replace($search_node_value, $add_node_value, $line);
														} else {
															$tmp[$line_num] = str_replace($search_node_value, $add_node_value, $line);
														}
														
														break;
												}
											}
										}
									}
		
									if (!$changed) {
										$skip_text = ($operation_node_error == 'skip' || $operation_node_error == 'log') ? '(SKIPPED)' : '(ABORTING MOD)';
										
										if ($operation_node_error == 'log' || $operation_node_error) {
											$message  = 'Modification::write - SEARCH NOT FOUND ' . $skip_text . ':' . "\n";
											$message .= 'Modification ID = \'' . $modification_id . '\'' . "\n";
											$message .= 'Filename = \'' . $file_node->getAttribute('name') . '\'' . "\n";
											$message .= 'Search = \'' . $search_node_value . '\'' . "\n";
											
											$log->write($message);
										}
		
										if ($operation_node_error == 'abort') {
											break 2; // skip this XML file
										}
									}
									break;
							}
		
							ksort($tmp);
							
							$modification[$file] = implode("\n", $tmp);
						} // end of $operation_nodes
					} // end of $files
				} // end of $file_nodes
			}
			
			// Write all modification files
			foreach ($modification as $key => $value) {
				$file = DIR_MODIFICATION . $key;
	
				$handle = fopen($file, 'w');
		
				fwrite($handle, $value);
		
				fclose($handle);	
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			//$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function clear() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');

		if ($this->validate()) {
			// Clear all modification files
			$files = glob(DIR_MODIFICATION . '{*.php,*.tpl}', GLOB_BRACE);

			if ($files) {
				foreach ($files as $file) {
					if (file_exists($file)) {
						unlink($file);
					}
				}
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function enable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');		

		if (isset($this->request->get['modification_id']) && $this->validate()) {
			$this->model_setting_modification->enableModification($this->request->get['modification_id']);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function disable() {
		$this->load->language('extension/modification');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/modification');		

		if (isset($this->request->get['modification_id']) && $this->validate()) {
			$this->model_setting_modification->disableModification($this->request->get['modification_id']);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function clearlog() {
		$this->load->language('extension/modification');
		
		if ($this->validate()) {
			$file = DIR_LOGS . 'vqmod.log';
	
			$handle = fopen($file, 'w+'); 
	
			fclose($handle); 		
	
			$this->session->data['success'] = $this->language->get('text_success');
				
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
				
			$this->response->redirect($this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL'));		
		}
		
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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
			'href' => $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['refresh'] = $this->url->link('extension/modification/refresh', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['clear'] = $this->url->link('extension/modification/clear', 'token=' . $this->session->data['token'] . $url, 'SSL');				
		$data['delete'] = $this->url->link('extension/modification/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$data['modifications'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$modification_total = $this->model_setting_modification->getTotalModifications();

		$results = $this->model_setting_modification->getModifications($filter_data);

		foreach ($results as $result) {
			$data['modifications'][] = array(
				'modification_id' => $result['modification_id'],
				'name'            => $result['name'],
				'author'          => $result['author'],
				'version'         => $result['version'],
				'status'          => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'link'            => $result['link'],
				'enable'          => $this->url->link('extension/modification/enable', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'], 'SSL'),
				'disable'         => $this->url->link('extension/modification/disable', 'token=' . $this->session->data['token'] . '&modification_id=' . $result['modification_id'], 'SSL'),
				'enabled'         => $result['status'],
			);
		}			

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_refresh'] = $this->language->get('text_refresh');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_author'] = $this->language->get('column_author');
		$data['column_version'] = $this->language->get('column_version');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_refresh'] = $this->language->get('button_refresh');
		$data['button_clear'] = $this->language->get('button_clear');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_link'] = $this->language->get('button_link');
		$data['button_enable'] = $this->language->get('button_enable');
		$data['button_disable'] = $this->language->get('button_disable');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_log'] = $this->language->get('tab_log');

		$data['token'] = $this->session->data['token'];

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_name'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_author'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_version'] = $this->url->link('extension/version', 'token=' . $this->session->data['token'] . '&sort=author' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_added'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $modification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/modification', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($modification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($modification_total - $this->config->get('config_limit_admin'))) ? $modification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $modification_total, ceil($modification_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		// Log
		$file = DIR_LOGS . 'vqmod.log';

		if (file_exists($file)) {
			$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$data['log'] = '';
		}

		$data['clear_log'] = $this->url->link('extension/modification/clearlog', 'token=' . $this->session->data['token'], 'SSL');
		
		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/modification.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/modification')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}