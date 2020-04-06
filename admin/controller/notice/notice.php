<?php
class ControllerNoticeNotice extends Controller {
    private $error = array();

    public function index() {
        $this->load->language('notice/notice');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('notice/notice');

        $this->getList();
    }

    protected function getList() {

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'notice.title';
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
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('notice/notice', 'user_token=' . $this->session->data['user_token'] . $url)
        );

        $data['add'] = $this->url->link('notice/notice/add', 'user_token=' . $this->session->data['user_token'] . $url);
        $data['delete'] = $this->url->link('notice/notice/delete', 'user_token=' . $this->session->data['user_token'] . $url);

        $data['attributes'] = array();

        $filter_data = array(
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $this->config->get('config_pagination'),
            'limit' => $this->config->get('config_pagination')
        );

        $notice_total = $this->model_notice_notice->getTotalNotices();

        $results = $this->model_notice_notice->getNotices($filter_data);

        foreach ($results as $result) {
            $data['notices'][] = array(
                'id'    => $result['id'],
                'title'            => $result['title'],
                'edit'            => $this->url->link('notice/notice/edit', 'user_token=' . $this->session->data['user_token'] . '&id=' . $result['id'] . $url)
            );
        }

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

        $data['sort_title'] = $this->url->link('notice/notice', 'user_token=' . $this->session->data['user_token'] . '&sort=notice.title' . $url);
        $data['sort_sort_order'] = $this->url->link('notice/notice', 'user_token=' . $this->session->data['user_token'] . '&sort=n.sort_order' . $url);

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        $data['pagination'] = $this->load->controller('common/pagination', array(
            'total' => $notice_total,
            'page'  => $page,
            'limit' => $this->config->get('config_pagination'),
            'url'   => $this->url->link('notice/notice', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
        ));

        $data['results'] = sprintf($this->language->get('text_pagination'), ($notice_total) ? (($page - 1) * $this->config->get('config_pagination')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination')) > ($notice_total - $this->config->get('config_pagination'))) ? $notice_total : ((($page - 1) * $this->config->get('config_pagination')) + $this->config->get('config_pagination')), $notice_total, ceil($notice_total / $this->config->get('config_pagination')));

        $data['sort'] = $sort;
        $data['order'] = $order;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('notice/notice_list', $data));
    }

    protected function getForm() {
        //get form
    }

    protected function validateForm() {
        //validateForm
    }

    protected function validateDelete() {
        //validateDelete
    }

    protected function validateUnlock() {
        //validateUnlock
    }

    public function login() {
        //login
    }

    public function history() {
        //history
    }

    public function addHistory() {
        //add history
    }

    public function transaction() {
        //transaction
    }

    public function addTransaction() {
        //add transaction
    }

    public function reward() {
        //reward
    }

    public function addReward() {
        //add reward
    }

    public function ip() {
        //ip
    }

    public function autocomplete() {
        //autocomplete
    }

    public function customfield() {
        //customfield
    }

    public function address() {
        //address
    }
}