<?php
class ControllerCommonSSO extends Controller {

    public function index() {

        if($this->isKeyValid()) {
            $email = $this->request->cookie['emailbbs'];
            if (isset($email)) {
                $this->login($email);
            }
        }
        $this->response->redirect($this->url->link('common/home'));
    }

    private $error = array();

    private function login($email) {
        $this->load->model('account/customer');
        $currentEmail =$this->customer->getEmail();
        // Login override for admin users
        if ($email !=$currentEmail) {
            $this->customer->logout();
            $this->cart->clear();

            unset($this->session->data['order_id']);
            unset($this->session->data['payment_address']);
            unset($this->session->data['payment_method']);
            unset($this->session->data['payment_methods']);
            unset($this->session->data['shipping_address']);
            unset($this->session->data['shipping_method']);
            unset($this->session->data['shipping_methods']);
            unset($this->session->data['comment']);
            unset($this->session->data['coupon']);
            unset($this->session->data['reward']);
            unset($this->session->data['voucher']);
            unset($this->session->data['vouchers']);
        }

        if ($this->customer->isLogged()) {
            $this->response->redirect($this->url->link('common/home', '', true));
        }

          if ( $this->validate($email)) {
            // Unset guest
            unset($this->session->data['guest']);

            // Default Shipping Address
            $this->load->model('account/address');

            if ($this->config->get('config_tax_customer') == 'payment') {
                $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            if ($this->config->get('config_tax_customer') == 'shipping') {
                $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
            }

            // Wishlist
            if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
                $this->load->model('account/wishlist');

                foreach ($this->session->data['wishlist'] as $key => $product_id) {
                    $this->model_account_wishlist->addWishlist($product_id);

                    unset($this->session->data['wishlist'][$key]);
                }
            }

            // Add to activity log
            $this->load->model('account/activity');

            $activity_data = array(
                'customer_id' => $this->customer->getId(),
                'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
            );

            $this->model_account_activity->addActivity('login', $activity_data);
            $this->response->redirect($this->url->link('common/home', '', true));
        }
    }

    private function  isKeyValid()
    {
        //todo add check
        $email = $_COOKIE['emailbbs'];
        $token = $_COOKIE['tokenbbs'];
        if(!empty($email) && !empty($token)) {
            return md5(SECRET . $email) == $token;
        }

        return false;
    }
    private function validate($email) {
        // Check how many login attempts have been made.
        $login_info = $this->model_account_customer->getLoginAttempts($email);

        if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
            $this->error['warning'] = $this->language->get('error_attempts');
        }

        // Check if customer has been approved.
        $customer_info = $this->model_account_customer->getCustomerByEmail($email);

        if ($customer_info && !$customer_info['approved']) {
            $this->error['warning'] = $this->language->get('error_approved');
        }

        if (!$this->error) {
            if (!$this->customer->login($email,null,true)) {
                $this->error['warning'] = $this->language->get('error_login');

                $this->model_account_customer->addLoginAttempt($email);
            } else {
                $this->model_account_customer->deleteLoginAttempts($email);
            }
        }

        return !$this->error;
    }
}