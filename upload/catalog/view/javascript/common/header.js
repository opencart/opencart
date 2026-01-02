import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const customer = await loader.library('customer');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/header');

class CommonHeader extends WebComponent {
    async connected() {
        let data = { ...Object.fromEntries(language) };

        if (config.has('config_logo')) {
            data.logo = config.get('config_url') + 'image/' + config.get('config_logo');
        } else {
            data.logo = '';
        }

        data.name = config.get('config_name');
        data.telephone = config.get('config_telephone');
        data.logged = customer.isLogged();
        data.wishlist = customer.getWishlist().length;

        data.text_wishlist = language.get('text_wishlist').replace('%d', customer.getWishlist().length);

        console.log();

        this.innerHTML = await loader.template('common/header', data);
    }
}

customElements.define('common-header', CommonHeader);