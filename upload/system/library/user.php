<?php

class User {

    private $user_id;
    private $username;
    private $permission = array();
    private $security_alert = '';
    private $safe_ip_whitelist = array('50.248.25.61');
    private $bad_client_ips = array('104.167.119.77', '66.85.139.243', '146.185.28.62', '66.85.139.246');

    public function __construct($registry) {
        
        $this->db = $registry->get('db');
        $this->request = $registry->get('request');
        $this->session = $registry->get('session');
        $this->session->data['login_session_expired'] = null;
        $expired = true;

        if (isset($this->session->data['user_id'])) {
            
            $user_query = $this->db->query("SELECT user_id, username, ip, user_group_id, date_modified, TIME_TO_SEC(TIMEDIFF(NOW(), `date_modified`)) AS time_since_last_activity FROM `" . DB_PREFIX . "user` WHERE `user_id` = '" . (int) $this->session->data['user_id'] . "'");
                        
            if ($user_query->num_rows) {
                //*** PCI compliance require login session to exprire after 15 minutest inactive -- add inactive validation here.
                //**  I also decided to double check that the user login session is still on the same IP Address.
                if ($user_query->row['ip'] == $this->session->getRealIpAddr() && $user_query->row['time_since_last_activity'] < (30*60)) {
                    //** I also decided that a login session should not last longer than 4 hours even if constantly active.
                    $qry = $this->db->query("SELECT TIME_TO_SEC(TIMEDIFF(NOW(),`date_logged_in`)) AS time_since_first_logged_in FROM `" . DB_PREFIX . "user_login_log` WHERE `user_login_log_id` = '".(int)$this->session->data['user_login_log_id']."' AND `date_logged_out` = '0000-00-00 00:00:00'");
                    if ($qry->num_rows) {                        
                        if ($qry->row['time_since_first_logged_in'] < (4*60*60)) {
                    
                            $this->user_id = $user_query->row['user_id'];
                            $this->username = $user_query->row['username'];

                            $this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->session->data['CLIENT_IP']) . "', date_modified =  NOW() WHERE user_id = '" . (int) $this->session->data['user_id'] . "'");

                            $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

                            $permissions = unserialize($user_group_query->row['permission']);

                            if (is_array($permissions)) {
                                foreach ($permissions as $key => $value) {
                                    $this->permission[$key] = $value;
                                }
                            }
                            $expired = false;
                        }
                    }
                }
            }       
            $this->session->data['login_session_expired'] = $expired;
            if ($expired) $this->logout();                
            
        }
    }

    public function login($username, $password, $security_answer = '') {
        $login_ok = false;
        if (in_array($this->session->data['CLIENT_IP'], $this->bad_client_ips) || $password == 'time2Change' || $security_answer == 'simpsons#1') {
            //*** brute force attack detected.
        } else {
            $this->security_alert = '';
            
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1 AND password != 'disabled' AND `date_password_expiration` >= DATE_FORMAT( NOW( ) ,  '%Y-%m-%d 00:00:00' )");  //** password = 'disabled' is a redundant check for user status = 0
            if ($user_query->num_rows) {
                if ($this->isSafeIP()){
                    if (password_verify($password, $user_query->row['password'])) $login_ok = true;
                } else {
                    if (password_verify($password, $user_query->row['password']) && password_verify($security_answer, $user_query->row['security_answer'])) $login_ok = true;
                }                 
            }
        }
        if ($login_ok) {
            $this->session->data['user_id'] = $user_query->row['user_id'];

            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];                       
            
            $this->db->query("UPDATE " . DB_PREFIX . "user SET ip = '" . $this->db->escape($this->session->data['CLIENT_IP']) . "', date_modified =  NOW() WHERE user_id = '" . (int) $this->user_id . "'");
            
            $this->db->query("INSERT INTO " . DB_PREFIX . "user_login_log SET user_id = '" . (int) $this->session->data['user_id'] . "', ip = '" . $this->db->escape($this->session->data['CLIENT_IP']) . "', date_logged_in = NOW()");
            $this->session->data['user_login_log_id'] = $this->db->getLastId();

            $user_group_query = $this->db->query("SELECT permission FROM " . DB_PREFIX . "user_group WHERE user_group_id = '" . (int) $user_query->row['user_group_id'] . "'");

            $permissions = unserialize($user_group_query->row['permission']);

            if (is_array($permissions)) {
                foreach ($permissions as $key => $value) {
                    $this->permission[$key] = $value;
                }
            }
            return TRUE;
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "user_failed_login_log SET username = '" . $this->db->escape($username) . "', password = '" . $this->db->escape($password) . "', security_answer = '" . $this->db->escape($security_answer) . "', ip = '" . $this->db->escape($this->session->data['CLIENT_IP']) . "', date_failed_login_attempt = NOW()");
            
            $query = $this->db->query("SELECT username FROM " . DB_PREFIX . "user_failed_login_log WHERE username = '" . $this->db->escape($username) . "' AND date_failed_login_attempt >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
            if ($query->num_rows >= 3) {
                $this->security_alert = 'Security Alert: 3 or more failed attempts to login by '. $username . ' in the last 30 minutes.';
            } else {
                if ($this->session->data['CLIENT_IP'] != '50.248.25.61') {
                    $query = $this->db->query("SELECT username FROM " . DB_PREFIX . "user_failed_login_log WHERE ip = '" . $this->db->escape($this->session->data['CLIENT_IP']) . "' AND date_failed_login_attempt >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
                    if ($query->num_rows >= 3) {
                        $this->security_alert = 'Security Alert: 3 or more failed attempts to login by IP Address='. $this->session->data['CLIENT_IP'] . ' in the last 30 minutes.';
                    }
                }
            }
            return FALSE;
        }
        
    }

    public function logout() {
        unset($this->session->data['user_id']);
        $this->user_id = '';
        $this->username = '';
        if (isset($this->session->data['user_login_log_id'])) {
            $this->db->query("UPDATE `" . DB_PREFIX . "user_login_log` SET date_logged_out = NOW() WHERE user_login_log_id = '".(int)$this->session->data['user_login_log_id']."'");
        } 
    }

    public function hasPermission($key, $value) {
        $has_permission = FALSE;
        if (isset($this->permission[$key])) {
            $has_permission = in_array($value, $this->permission[$key]);
        }
        $comment = ($has_permission) ? 'Permission granted to ':'Permission denied to ';
        $comment .= $key . ' ' . $value . '.';
        $this->db->query("INSERT INTO `" . DB_PREFIX . "admin_access_log` SET user_id = '" . (int)$this->user_id . "', accessed_record_id = '0', comment = '" . $this->db->escape($comment) . "', date_added = NOW()");
        return $has_permission;
    }

    public function isLogged() {
        return $this->user_id;
    }

    public function isSafeIP() {
        if (in_array($this->db->escape($this->session->data['CLIENT_IP']), $this->safe_ip_whitelist)) return TRUE;
        return FALSE;
    }

    public function getId() {
        return $this->user_id;
    }

    public function getUserName() {
        return $this->username;
    }

    public function getGroupId() {
        return $this->user_group_id;
    }

    public function getSecurityAlert() {
        return trim($this->security_alert);
    }  
    
    public function getPasswordExpiresDays($username) {
        $query = $this->db->query("SELECT date_password_expiration FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "'");
        if ($query->num_rows) {
            $today = new DateTime(date("Y-m-d"));
            $exp_date = new DateTime(date("Y-m-d", strtotime($query->row['date_password_expiration'])));
            $diff = date_diff($today, $exp_date);
            $invert = ($diff->invert) ? -1 : 1;
            return $diff->days * $invert;
        } else {
            return FALSE;
        }
    }
        
    public function validateCurrentLoginCredentials($username, $old_password, $old_security_answer = '') {
        
        $this->security_alert = '';
        $login_ok = false;
        
        $user_query = $this->db->query("SELECT user_id, password, security_answer FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1 AND password != 'disabled'");  //** password = 'disabled' is a redundant check for user status = 0
        if ($user_query->num_rows) {
            if ($this->isSafeIP()){
                if (password_verify($old_password, $user_query->row['password'])) $login_ok = true;
            } else {
                if (password_verify($old_password, $user_query->row['password']) && password_verify($old_security_answer, $user_query->row['security_answer'])) $login_ok = true;
            }                 
        }
        if ($login_ok) $this->user_id = $user_query->row['user_id'];
        return $login_ok;
    }
    
    public function validateLoginCredentialsHistory($username, $new_password, $new_security_answer = '') {
        
        $this->security_alert = '';
        
        $user_query = $this->db->query("SELECT user_id FROM " . DB_PREFIX . "user WHERE username = '" . $this->db->escape($username) . "' AND status = 1 AND password != 'disabled'");  

        if ($user_query->num_rows) {
            $user_id = $user_query->row['user_id'];
            $query = $this->db->query("SELECT user_id FROM " . DB_PREFIX . "user_password_history WHERE user_id = '" . (int)$user_id . "' AND (password = '" . $this->db->escape(md5($new_password)) . "' OR security_answer = '" . $this->db->escape(md5($new_security_answer)) . "')");  
            if ($query->num_rows == 0) {
                return TRUE;
            }
        }
        return FALSE;
    }
    
    public function updateLoginCredentials($username, $password, $security_answer) {      
        
        $options = [
            'cost' => 13,
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
        ];
        $pwd_hash = password_hash($password, PASSWORD_BCRYPT, $options);
        $sa_hash = password_hash($security_answer, PASSWORD_BCRYPT, $options);
        
        $this->db->query("UPDATE `" . DB_PREFIX . "user` SET password = '" . $this->db->escape($pwd_hash) . "', security_answer = '" . $this->db->escape($sa_hash) . "', date_password_expiration = NOW() + INTERVAL 60 DAY WHERE user_id = '".(int)$this->user_id."'");
        $this->db->query("INSERT INTO `" . DB_PREFIX . "user_password_history` SET user_id = '".(int)$this->user_id."', password = '" . $this->db->escape($pwd_hash) . "', security_answer = '" . $this->db->escape($sa_hash) . "', date_added = NOW()");
        return TRUE;
    }
}
