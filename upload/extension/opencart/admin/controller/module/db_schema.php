<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Module;
/**
 * Class DbSchema
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Module
 */
class DbSchema extends \Opencart\System\Engine\Controller {
    /**
     * @return void
     */
    public function index(): void {
        $this->load->language('extension/opencart/module/db_schema');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $url = '';
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $data['breadcrumbs'] = [];
        
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];
        
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];
        
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/opencart/module/db_schema', 'user_token=' . $this->session->data['user_token'] . $url)
        ];
        
        $data['report'] = $this->url->link('extension/opencart/module/db_schema/getReport', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');
        
        $data['list'] = $this->getList();
        
        $data['user_token'] = $this->session->data['user_token'];
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/opencart/module/db_schema', $data));
    }
    
    /**
     * @return void
     */
    public function list(): void {
        $this->load->language('extension/opencart/module/db_schema');
        
        $this->response->setOutput($this->getList());
    }
    
    /**
     * @return string
     */
    protected function getList(): string {
        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $url = '';
        
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $data['action'] = $this->url->link('extension/opencart/module/db_schema.list', 'user_token=' . $this->session->data['user_token'] . $url);
        
        $data['tables'] = [];
        
        // Structure
        $this->load->helper('db_schema');
        
        $tables = oc_db_schema();
        
        $filter_data = [
            'start' => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit' => $this->config->get('config_limit_admin')
        ];
        
        $results = $this->model_extension_module_db_schema->getTables($filter_data);
        
        foreach ($results as $result) {
            foreach ($tables as $table) {
                if ($table['primary'][0] == $result['Column_name'] && $result['COLUMN_KEY'] == 'PRI') {
                    $data['tables'][] = [
                        'table' => $result['TABLE_NAME'],
                        'field' => $table['primary'][0],
                        'type'  => $result['COLUMN_TYPE']
                    ];
                }
            }
        }
        
        $table_total = $this->model_extension_module_db_schema->getTotalTables();
        
        $data['pagination'] = $this->load->controller('common/pagination', [
            'total' => $table_total,
            'page'  => $page,
            'limit' => $this->config->get('config_pagination_admin'),
            'url'   => $this->url->link('extension/opencart/module/db_schema.list', 'user_token=' . $this->session->data['user_token'] . '&page={page}')
        ]);
        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($table_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($table_total - $this->config->get('config_pagination_admin'))) ? $table_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $table_total, ceil($table_total / $this->config->get('config_pagination_admin')));
        
        return $this->load->view('extension/opencart/module/db_schema_list', $data);
    }
    
    /**
     * getReport
     *
     * @return object|\Opencart\System\Engine\Action|null
     */
    public function getReport(): ?object {
        $this->load->language('extension/opencart/module/db_schema');
        
        $data['title'] = $this->language->get('text_report');
        
        if ($this->request->server['HTTPS']) {
            $data['base'] = HTTPS_SERVER;
        } else {
            $data['base'] = HTTP_SERVER;
        }
        
        $data['direction'] = $this->language->get('direction');
        $data['lang'] = $this->language->get('code');
        
        // Hard coding css so they can be replaced via the events' system.
        $data['bootstrap_css'] = 'view/stylesheet/bootstrap.css';
        $data['icons'] = 'view/stylesheet/fonts/fontawesome/css/all.min.css';
        $data['stylesheet'] = 'view/stylesheet/stylesheet.css';
        
        // Hard coding scripts so they can be replaced via the events' system.
        $data['jquery'] = 'view/javascript/jquery/jquery-3.7.1.min.js';
        $data['bootstrap_js'] = 'view/javascript/bootstrap/js/bootstrap.bundle.min.js';
        
        if (isset($this->request->post['selected'])) {
            $selected = $this->request->post['selected'];
        } else {
            $selected = [];
        }
        
        if ($this->user->hasPermission('modify', 'extension/opencart/module/db_schema')) {
            // DB Schema
            $this->load->model('extension/opencart/module/db_schema');
            
            // DB Schema
            $this->load->helper('db_schema');
            
            $tables = oc_db_schema();
            
            foreach ($tables as $table) {
                if (in_array($table['name'], $selected)) {
                    $field_data = [];
                    $filter_data = [];
                    
                    foreach ($table['field'] as $field) {
                        $fields = $this->model_extension_module_db_schema->getTable($table['name']);
                        
                        // Core
                        if ($fields) {
                            foreach ($fields as $result) {
                                if ($result['Column_name'] == $field['name']) {
                                    $data['tables'][$result['TABLE_NAME'] . '|parent'][] = [
                                        'name'          => $result['Column_name'],
                                        'previous_type' => $result['COLUMN_TYPE'],
                                        'type'          => $field['type']
                                    ];
                                }
                                
                                // Extensions
                                $field_data[] = $result['Column_name'];
                            }
                        }
                        
                        // Foreign
                        if (isset($table['foreign']) && $table['foreign']) {
                            foreach ($table['foreign'] as $foreign) {
                                $fields = $this->model_extension_module_db_schema->getTable($foreign['table']);
                                
                                if ($fields) {
                                    foreach ($fields as $result) {
                                        if ($result['Column_name'] == $field['name']) {
                                            $data['tables'][$result['TABLE_NAME'] . '|child'][] = [
                                                'name'          => $result['Column_name'],
                                                'previous_type' => $result['COLUMN_TYPE'],
                                                'type'          => $field['type']
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                        
                        if (!in_array($field['name'], $field_data)) {
                            foreach ($field_data as $result) {
                                if ($result != $field['name']) {
                                    $filter_data[] = $result;
                                }
                            }
                        }
                    }
                    
                    // Extension fields from core tables
                    if ($filter_data) {
                        $filter_data = array_unique($filter_data);
                        
                        $fields = $this->model_extension_module_db_schema->getTable($table['name'], $filter_data);
                        
                        if ($fields) {
                            foreach ($fields as $result) {
                                $data['tables'][$result['TABLE_NAME'] . '|extension'][] = [
                                    'name'          => $result['Column_name'],
                                    'previous_type' => $result['COLUMN_TYPE'],
                                    'type'          => $result['COLUMN_TYPE']
                                ];
                            }
                        }
                    }
                }
            }
            
            $this->response->setOutput($this->load->view('extension/opencart/module/db_schema_report', $data));
        } else {
            return new \Opencart\System\Engine\Action('error/permission');
        }
        
        return null;
    }
    
    /**
     * Install
     *
     * @return void
     */
    public function install(): void {
        $this->load->model('setting/setting');
        
        $post_data = [
            'module_db_schema_status' => 1
        ];
        
        $this->model_setting_setting->editSetting('module_db_schema', $post_data);
    }
    
    /**
     * Uninstall
     *
     * @return void
     */
    public function uninstall(): void {
        $this->load->model('setting/setting');
        
        $this->model_setting_setting->deleteSetting('module_db_schema');
    }
}
