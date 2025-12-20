import { WebComponent } from '../index.js';

class XHeader extends WebComponent {
    async connected() {
        this.innerHTML = '<p>test desfsedfs</p>';

        this.load.language('common/header');

        let data = [];
        /*
        if (this.config.get('config_logo')) {
            data['logo'] = this.config.get('config_url') + 'image/' + this.config.get('config_logo');
        } else {
            data['logo'] = '';
        }

        data['home'] = this.url.link('common/home', 'language=' . this.config.get('config_language'));

        let logged = false;
        let wishlist_total = 0;

        let customer = this.session.get('customer');

        if (customer) {
            logged = true;
			wishlist_total = customer.wishlist.length;
		}

        data['telephone'] = this.config.get('config_telephone');

        data['logged'] = logged;
        data['text_wishlist'] = sprintf(this.language.get('text_wishlist'), wishlist_total);

        /*
        $data['wishlist'] = $this->url->link('account/wishlist', 'language=' . $this->config->get('config_language') . (isset($this->session->data['customer_token']) ? '&customer_token=' . $this->session->data['customer_token'] : ''));

        if (!$this->customer->isLogged()) {
            $data['register'] = $this->url->link('account/register', 'language=' . $this->config->get('config_language'));
            $data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'));
        } else {
            $data['account'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
            $data['order'] = $this->url->link('account/order', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
            $data['transaction'] = $this->url->link('account/transaction', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
            $data['download'] = $this->url->link('account/download', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);
            $data['logout'] = $this->url->link('account/logout', 'language=' . $this->config->get('config_language'));
        }

        $data['shopping_cart'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
        $data['checkout'] = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
        $data['contact'] = $this->url->link('information/contact', 'language=' . $this->config->get('config_language'));
        */
        let test = await this.load.template('common/header', data);

        console.log(test);
        //this.innerHTML = this.load.template('common/header', data);
    }
}

customElements.define('x-header', XHeader);