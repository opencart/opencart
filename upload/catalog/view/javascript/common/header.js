import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const session = await loader.library('session');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/header');

// Customer
const customer = session.get('customer');

class CommonHeader extends WebComponent {
    async connected() {
        let data = {};

        if (config.config_logo) {
            data.logo = config.config_url + 'image/' + config.config_logo;
        } else {
            data.logo = '';
        }

        data.name = config.config_name;
        data.telephone = config.config_telephone;

        if (session.has('customer')) {
            data.logged = customer.get('customer_id') ? true : false;
        } else {
            data.logged = false;
        }

        data.text_wishlist = language.text_wishlist.replace('%d', session.has('customer') ? customer.get('wishlist').size : 0);

        this.innerHTML = await loader.template('common/header', { ...data, ...language });
    }
}

customElements.define('common-header', CommonHeader);