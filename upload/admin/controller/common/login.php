<?php

class ControllerCommonLogin extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('common/login');

        $this->document->setTitle($this->language->get('heading_title'));

        if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
            $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $data['change_password_href'] = HTTPS_SERVER . 'index.php?route=common/change_password&token=' . $this->session->data['token'];
        $data['change_password_text'] = $this->language->get('text_change_password');
        
        $data['security_alert'] = '';
        $data['is_safe_ip'] = $this->user->isSafeIP();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            
            if ($this->validate()) {
                $this->session->data['token'] = md5(mt_rand());               
                if ($this->user->isSafeIP() === FALSE) {
                    $today = date('Y-m-d H:m:s');
                    $this->emailSecurityAlert('Username='.$this->request->post['username'].' on unsafe IP Address='.$this->session->data['CLIENT_IP']. ' has logged in succefully on '.$today.'.', false);
                }
                if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], HTTP_SERVER) === 0 || strpos($this->request->post['redirect'], HTTPS_SERVER) === 0 )) {
                    $this->response->redirect($this->request->post['redirect'] . '&token=' . $this->session->data['token']);
                } else {
                    $this->response->redirect($this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'));
                }
            } else {
                $sa = $this->user->getSecurityAlert();
                if (!empty($sa)) {
                    $today = date('Y-m-d H:m:s');
                    $this->emailSecurityAlert('Username='.$this->request->post['username'].' on IP Address='.$this->session->data['CLIENT_IP']. ' attempt to login failed on '.$today.'.', true);
                    $data['security_alert'] = 'You have exceeded to maximum login attempts allowed. Please call your system administrator.';
                }
            }
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_login'] = $this->language->get('text_login');
        $data['text_forgotten'] = $this->language->get('text_forgotten');        
        $data['text_change_password'] = $this->language->get('text_change_password');
        $data['text_answer_security_question'] = $this->language->get('text_answer_security_question');        
        $data['entry_security_question'] = $this->language->get('entry_security_question');      
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');

        $data['button_login'] = $this->language->get('button_login');

        if ($this->session->data['login_session_expired'] === true) {
            $this->error['warning'] = $this->language->get('error_login_sesion_expired');
        }

        if ((isset($this->session->data['token']) && !isset($this->request->get['token'])) || ((isset($this->request->get['token']) && (isset($this->session->data['token']) && ($this->request->get['token'] != $this->session->data['token']))))) {
            $this->error['warning'] = $this->language->get('error_token');
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

        $data['action'] = $this->url->link('common/login', '', 'SSL');

        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } else {
            $data['username'] = '';
        }

        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } else {
            $data['password'] = '';
        }

        $data['password_expires_warning'] = '';
        $data['password_expired_error'] = '';
                
        $exp_days = $this->user->getPasswordExpiresDays($data['username']);
        
        $data['password_expires_days'] = $exp_days;
        
        if ($exp_days === FALSE) {
            //*** no user_id available in fresh login session
        } elseif ($exp_days < 0) {
            $data['password_expired_error'] = $this->language->get('password_expired_error');
        } elseif ($exp_days == 0) {
            $data['password_expires_warning'] = $this->language->get('password_expired_today_warning');
        } elseif ($exp_days <= 3) {
            $data['password_expires_warning'] = sprintf($this->language->get('password_expires_warning'), $exp_days);
        }
        
        if (isset($this->request->get['route'])) {
            $route = $this->request->get['route'];

            unset($this->request->get['route']);
            unset($this->request->get['token']);

            $url = '';

            if ($this->request->get) {
                $url .= http_build_query($this->request->get);
            }

            $data['redirect'] = $this->url->link($route, $url, 'SSL');
        } else {
            $data['redirect'] = '';
        }

        if ($this->config->get('config_password')) {
            $data['forgotten'] = $this->url->link('common/forgotten', '', 'SSL');
        } else {
            $data['forgotten'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('common/login.tpl', $data));
    }

    public function check() {
        $route = isset($this->request->get['route']) ? $this->request->get['route'] : '';

        $ignore = array(
            'common/login',
            'common/forgotten',
            'common/reset'
        );

        if (!$this->user->isLogged() && !in_array($route, $ignore)) {
            return new Action('common/login');
        }

        if (isset($this->request->get['route'])) {
            $ignore = array(
                'common/login',
                'common/logout',
                'common/forgotten',
                'common/reset',
                'error/not_found',
                'error/permission'
            );

            if (!in_array($route, $ignore) && (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token']))) {
                return new Action('common/login');
            }
        } else {
            if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
                return new Action('common/login');
            }
        }
    }
    
    public function getPasswordExpires() {
        
        $this->load->language('common/login');
        
        $data = array ('password_expires_days' => '', 'password_expires_warning' => '', 'password_expired_error' => '');

        if (isset($this->request->post['username'])) {
            $exp_days = $this->user->getPasswordExpiresDays($this->request->post['username']);

            $data['password_expires_days'] = $exp_days;

            if ($exp_days === FALSE) {
                //*** no user_id available in fresh login session
            } elseif ($exp_days < 0) {
                $data['password_expired_error'] = $this->language->get('password_expired_error');
            } elseif ($exp_days == 0) {
                $data['password_expires_warning'] = $this->language->get('password_expired_today_warning');
            } elseif ($exp_days <= 3) {
                $data['password_expires_warning'] = sprintf($this->language->get('password_expires_warning'), $exp_days);
            }
        }

        $this->load->library('json');

        $this->response->setOutput(Json::encode($data));
    }

    private function validate() {
        $this->error['warning'] = $this->language->get('error_login');
        
        if ($this->user->isSafeIP()) {
            if (isset($this->request->post['username']) && isset($this->request->post['password']) && $this->user->login($this->request->post['username'], $this->request->post['password'])) {
                unset($this->error);
            }
        } else {
            if (isset($this->request->post['username']) && isset($this->request->post['password']) && isset($this->request->post['security_answer']) && $this->user->login($this->request->post['username'], $this->request->post['password'], $this->request->post['security_answer'])) {
                unset($this->error);
            }
        }
        
        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    private function emailSecurityAlert($alert_message, $failed_login_attempt) {
        
        $message = $alert_message . "\n\n";
        if ($failed_login_attempt) {
            $message .= $this->user->getSecurityAlert() . "\n";
        }

        $mail = new Mail();
        $mail->protocol = $this->config->get('config_mail_protocol');
        $mail->hostname = $this->config->get('config_smtp_host');
        $mail->username = $this->config->get('config_smtp_username');
        $mail->password = $this->config->get('config_smtp_password');
        $mail->port = $this->config->get('config_smtp_port');
        $mail->timeout = $this->config->get('config_smtp_timeout');							
        $mail->parameter = " -r ".$this->config->get('config_email');
        $mail->setTo('security@cudazoo.com');
        $mail->setFrom('cudazoo@cudazoo.com');
        $mail->setSender('CudaZoo Systems Admin');
        $mail->setSubject('OC Admin Login Security Alert');
        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();        
    }
}
