<?php

class ControllerCommonChangePassword extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('common/login');

        $this->document->title = $this->language->get('heading_title');

        $this->data['security_alert'] = '';
        $this->data['is_safe_ip'] = $this->user->isSafeIP();

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            
            if ($this->validate()) {
                
                if (isset($this->request->post['username']) && isset($this->request->post['password']) && isset($this->request->post['security_answer']) && $this->user->updateLoginCredentials($this->request->post['username'], $this->request->post['password'], $this->request->post['security_answer'])) {
                    unset($this->error);
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
                    if (!isset($this->error)) {
                        $today = date('Y-m-d H:m:s');
                        $this->emailSecurityAlert('Username='.$this->request->post['username'].' on IP Address='.$this->request->server['REMOTE_ADDR']. ' has successfuly changed password on '.$today.'.', false);
                        //*** Sucessful login immediately after password change has been updated in updateLoginCredentials() method.
                        $this->session->data['token'] = md5(mt_rand());               
                        $this->redirect(HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token']);
                    }
                } else {
                    $this->error['warning'] = $this->language->get('error_failed_to_update_login_credentials');
                }
            } else {
                $today = date('Y-m-d H:m:s');
                $this->emailSecurityAlert('Username='.$this->request->post['username'].' on IP Address='.$this->request->server['REMOTE_ADDR']. ' has attempeted to change password='.$this->request->post['old_password'].' on '.$today.'.', false);
            }
        }
        
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_please_change_password'] = $this->language->get('text_please_change_password');
        $this->data['text_answer_security_question'] = $this->language->get('text_answer_security_question');        
        $this->data['text_old_answer_security_question'] = $this->language->get('text_old_answer_security_question');                
        $this->data['text_new_answer_security_question'] = $this->language->get('text_new_answer_security_question');                

        $this->data['entry_username'] = $this->language->get('entry_username');
        $this->data['entry_new_password'] = $this->language->get('entry_new_password');
        $this->data['entry_confirm'] = $this->language->get('entry_confirm');     
        $this->data['entry_old_password'] = $this->language->get('entry_old_password');             
        $this->data['entry_security_question'] = $this->language->get('entry_security_question');
        
        $this->data['button_change_password'] = $this->language->get('button_change_password');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=common/change_password';


        if (isset($this->request->get['route'])) {
            $route = $this->request->get['route'];

            unset($this->request->get['route']);

            if (isset($this->request->get['token'])) {
                unset($this->request->get['token']);
            }

            $url = '';

            if ($this->request->get) {
                $url = '&' . http_build_query($this->request->get);
            }

            $this->data['redirect'] = HTTPS_SERVER . 'index.php?route=' . $route . $url;
        } else {
            $this->data['redirect'] = '';
        }

        $this->template = 'common/changepassword.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    private function validate() {
        $this->error['warning'] = $this->language->get('error_invalid_login_credentials_not_allow_password_change');
        
        if ($this->user->isSafeIP()) {
            if (isset($this->request->post['username']) && isset($this->request->post['old_password']) && $this->user->validateCurrentLoginCredentials($this->request->post['username'], $this->request->post['old_password'])) {
                unset($this->error['warning']);
            }
        } else {
            if (isset($this->request->post['username']) && isset($this->request->post['old_password']) && isset($this->request->post['old_security_answer']) && $this->user->validateCurrentLoginCredentials($this->request->post['username'], $this->request->post['old_password'], $this->request->post['old_security_answer'])) {
                unset($this->error['warning']);
            }
        }
        
        if (isset($this->error['warning'])) return FALSE;
        
        $error = '';
        $pwd = $this->request->post['password'];
        $confirm = $this->request->post['confirm'];

        if ($this->user->validateLoginCredentialsHistory($this->request->post['username'], $pwd, $this->request->post['security_answer'])) {
            if (strlen($pwd) < 6) {
                $error .= "<li>Password too short!</li>";
            }

            if (strlen($pwd) > 20) {
                $error .= "<li>Password too long!</li>";
            }

            if (!preg_match("#[0-9]+#", $pwd)) {
                $error .= "<li>Password must include at least one number!</li>";
            }

            if (!preg_match("#[a-z]+#", $pwd)) {
                $error .= "<li>Password must include at least one letter!</li>";
            }

            if (!preg_match("#[A-Z]+#", $pwd)) {
                $error .= "<li>Password must include at least one CAPS!</li>";
            }

            if (!preg_match("#\W+#", $pwd)) {
                $error .= "<li>Password must include at least one symbol!</li>";
            }        

            if ($pwd != $confirm) {
                $error .= "<li>Password confirmation does not match!</li>";
            }
        } else {
            $error .= "<li>Password and Security Answer cannot be repeated. Please enter something new you haven&apos;t used before.</li>";
        }

        if (empty($error)) {
            return TRUE;
        } else {
            $this->error['warning'] = 'Your new password failed the following criteria:<br><div style="text-align: left;display: inline-block;margin-left: -30px;"><ul>'.$error."</ul></div>";
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
        $mail->setSubject('OC Admin Login Credentials Change Security Alert');
        $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();        
    }

}
