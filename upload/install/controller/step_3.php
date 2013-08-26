<?php
class ControllerStep3 extends Controller {
    private $error = array();

    public function index() {
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('install');

            $this->model_install->mysql($this->request->post);

            $siteConfig  = "<?php\n";

            $siteConfig .= 'define(\'WWW_ROOT\', $_SERVER[\'HTTP_HOST\'] . str_replace(\'index.php\', \'\', $_SERVER[\'SCRIPT_NAME\']));' . "\n";
            $siteConfig .= "define('ROOT', dirname(__FILE__));\n\n";

            $siteConfig .= "// HTTP\n";
            $siteConfig .= "define('HTTP_SERVER', 'http://' . WWW_ROOT);\n\n";

            $siteConfig .= "// HTTPS\n";
            $siteConfig .= "define('HTTPS_SERVER', 'http://' . WWW_ROOT);\n\n";


            $siteConfig .= "// DIR\n";
            $siteConfig .= "define('DIR_APPLICATION', ROOT . '/catalog/');\n";
            $siteConfig .= "define('DIR_SYSTEM', ROOT. '/system/');\n";
            $siteConfig .= "define('DIR_DATABASE', ROOT . '/system/database/');\n";
            $siteConfig .= "define('DIR_LANGUAGE', ROOT . '/catalog/language/');\n";
            $siteConfig .= "define('DIR_TEMPLATE', ROOT . '/catalog/view/theme/');\n";
            $siteConfig .= "define('DIR_CONFIG', ROOT . '/system/config/');\n";
            $siteConfig .= "define('DIR_IMAGE', ROOT . '/image/');\n";
            $siteConfig .= "define('DIR_CACHE', ROOT . '/system/cache/');\n";
            $siteConfig .= "define('DIR_DOWNLOAD', ROOT . '/system/download/');\n";
            $siteConfig .= "define('DIR_MODIFICATION', ROOT . '/system/modification/');\n";
            $siteConfig .= "define('DIR_LOGS', ROOT . '/system/logs/');\n\n";

            $siteConfig .= "// DB\n";
            $siteConfig .= "include_once ROOT . '/db_config.php';\n";
            $siteConfig .= "?>";

            $dbConfig  = "<?php\n";
            $dbConfig .= "define('DB_DRIVER', '" . addslashes($this->request->post['db_driver']) . "');\n";
            $dbConfig .= "define('DB_HOSTNAME', '" . addslashes($this->request->post['db_host']) . "');\n";
            $dbConfig .= "define('DB_USERNAME', '" . addslashes($this->request->post['db_user']) . "');\n";
            $dbConfig .= "define('DB_PASSWORD', '" . addslashes($this->request->post['db_password']) . "');\n";
            $dbConfig .= "define('DB_DATABASE', '" . addslashes($this->request->post['db_name']) . "');\n";
            $dbConfig .= "define('DB_PREFIX', '" . addslashes($this->request->post['db_prefix']) . "');\n";
            $dbConfig .= "?>";

            file_put_contents(DIR_OPENCART . 'config.php', $siteConfig);
            file_put_contents(DIR_OPENCART . 'db_config.php', $dbConfig);

            $adminConfig  = "<?php\n";
            $adminConfig .= 'define(\'WWW_ROOT\', $_SERVER[\'HTTP_HOST\'] . str_replace(\'admin/index.php\', \'\', $_SERVER[\'SCRIPT_NAME\']));' . "\n";
            $adminConfig .= "define('ROOT', dirname(__FILE__) . '/..');\n\n";

            $adminConfig .= "// HTTP\n";
            $adminConfig .= "define('HTTP_SERVER', 'http://' . WWW_ROOT . 'admin/');\n";
            $adminConfig .= "define('HTTP_CATALOG', 'http://' . WWW_ROOT);\n\n";;

            $adminConfig .= "// HTTPS\n";
            $adminConfig .= "define('HTTPS_SERVER', 'http://' . WWW_ROOT . 'admin/');\n";
            $adminConfig .= "define('HTTPS_CATALOG', 'http://' . WWW_ROOT);\n\n";


            $adminConfig .= "// DIR\n";
            $adminConfig .= "define('DIR_APPLICATION', ROOT . '/admin/');\n";
            $adminConfig .= "define('DIR_SYSTEM', ROOT. '/system/');\n";
            $adminConfig .= "define('DIR_DATABASE', ROOT . '/system/database/');\n";
            $adminConfig .= "define('DIR_LANGUAGE', ROOT . '/admin/language/');\n";
            $adminConfig .= "define('DIR_TEMPLATE', ROOT . '/admin/view/template/');\n";
            $adminConfig .= "define('DIR_CONFIG', ROOT . '/system/config/');\n";
            $adminConfig .= "define('DIR_IMAGE', ROOT . '/image/');\n";
            $adminConfig .= "define('DIR_CACHE', ROOT . '/system/cache/');\n";
            $adminConfig .= "define('DIR_DOWNLOAD', ROOT . '/system/download/');\n";
            $adminConfig .= "define('DIR_MODIFICATION', ROOT . '/system/modification/');\n";
            $adminConfig .= "define('DIR_LOGS', ROOT . '/system/logs/');\n";
            $adminConfig .= "define('DIR_CATALOG', ROOT . '/catalog/');\n\n";

            $adminConfig .= "// DB\n";
            $adminConfig .= "include_once ROOT . '/db_config.php';\n";
            $adminConfig .= "?>";

            file_put_contents(DIR_OPENCART . 'admin/config.php', $adminConfig);

            $this->redirect($this->url->link('step_4'));
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['db_host'])) {
            $this->data['error_db_host'] = $this->error['db_host'];
        } else {
            $this->data['error_db_host'] = '';
        }

        if (isset($this->error['db_user'])) {
            $this->data['error_db_user'] = $this->error['db_user'];
        } else {
            $this->data['error_db_user'] = '';
        }

        if (isset($this->error['db_name'])) {
            $this->data['error_db_name'] = $this->error['db_name'];
        } else {
            $this->data['error_db_name'] = '';
        }

        if (isset($this->error['db_prefix'])) {
            $this->data['error_db_prefix'] = $this->error['db_prefix'];
        } else {
            $this->data['error_db_prefix'] = '';
        }

        if (isset($this->error['username'])) {
            $this->data['error_username'] = $this->error['username'];
        } else {
            $this->data['error_username'] = '';
        }

        if (isset($this->error['password'])) {
            $this->data['error_password'] = $this->error['password'];
        } else {
            $this->data['error_password'] = '';
        }

        if (isset($this->error['email'])) {
            $this->data['error_email'] = $this->error['email'];
        } else {
            $this->data['error_email'] = '';
        }

        $this->data['action'] = $this->url->link('step_3');

        if (isset($this->request->post['db_driver'])) {
            $this->data['db_driver'] = $this->request->post['db_driver'];
        } else {
            $this->data['db_driver'] = 'mysql';
        }

        if (isset($this->request->post['db_host'])) {
            $this->data['db_host'] = $this->request->post['db_host'];
        } else {
            $this->data['db_host'] = 'localhost';
        }

        if (isset($this->request->post['db_user'])) {
            $this->data['db_user'] = html_entity_decode($this->request->post['db_user']);
        } else {
            $this->data['db_user'] = '';
        }

        if (isset($this->request->post['db_password'])) {
            $this->data['db_password'] = html_entity_decode($this->request->post['db_password']);
        } else {
            $this->data['db_password'] = '';
        }

        if (isset($this->request->post['db_name'])) {
            $this->data['db_name'] = html_entity_decode($this->request->post['db_name']);
        } else {
            $this->data['db_name'] = '';
        }

        if (isset($this->request->post['db_prefix'])) {
            $this->data['db_prefix'] = html_entity_decode($this->request->post['db_prefix']);
        } else {
            $this->data['db_prefix'] = 'oc_';
        }

        if (isset($this->request->post['username'])) {
            $this->data['username'] = $this->request->post['username'];
        } else {
            $this->data['username'] = 'admin';
        }

        if (isset($this->request->post['password'])) {
            $this->data['password'] = $this->request->post['password'];
        } else {
            $this->data['password'] = '';
        }

        if (isset($this->request->post['email'])) {
            $this->data['email'] = $this->request->post['email'];
        } else {
            $this->data['email'] = '';
        }

        $this->data['back'] = $this->url->link('step_2');

        $this->template = 'step_3.tpl';
        $this->children = array(
            'header',
            'footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->request->post['db_host']) {
            $this->error['db_host'] = 'Host required!';
        }

        if (!$this->request->post['db_user']) {
            $this->error['db_user'] = 'User required!';
        }

        if (!$this->request->post['db_name']) {
            $this->error['db_name'] = 'Database Name required!';
        }

        if ($this->request->post['db_prefix'] && preg_match('/[^a-z0-9_]/', $this->request->post['db_prefix'])) {
            $this->error['db_prefix'] = 'DB Prefix can only contain lowercase characters in the a-z range, 0-9 and "_"!';
        }

        if ($this->request->post['db_driver'] == 'mysql') {
            if (!$connection = @mysql_connect($this->request->post['db_host'], $this->request->post['db_user'], $this->request->post['db_password'])) {
                $this->error['warning'] = 'Error: Could not connect to the database please make sure the database server, username and password is correct!';
            } else {
                if (!@mysql_select_db($this->request->post['db_name'], $connection)) {
                    $this->error['warning'] = 'Error: Database does not exist!';
                }

                mysql_close($connection);
            }
        }

        if (!$this->request->post['username']) {
            $this->error['username'] = 'Username required!';
        }

        if (!$this->request->post['password']) {
            $this->error['password'] = 'Password required!';
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
            $this->error['email'] = 'Invalid E-Mail!';
        }

        if (!is_writable(DIR_OPENCART . 'config.php')) {
            $this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'config.php!';
        }

        if (!is_writable(DIR_OPENCART . 'admin/config.php')) {
            $this->error['warning'] = 'Error: Could not write to config.php please check you have set the correct permissions on: ' . DIR_OPENCART . 'admin/config.php!';
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }
}
?>
