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
        let data = {};

        data.logged = customer.isLogged();

        data.wishlist = 0;

        if (data.logged) {
            data.wishlist = customer.getWishlist().length;
        }

        this.innerHTML = await loader.template('common/header', { ...data, ...language, ...config });
    }
}

customElements.define('common-header', CommonHeader);