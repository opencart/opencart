import { WebComponent } from '../component.js';
import { loader, session, cart, customer } from '../index.js';
import '../common/currency.js';
import '../common/language.js';
import '../common/search.js';
import '../common/cart.js';
import '../common/menu.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/header');

customElements.define('common-header', class extends WebComponent {
    async render() {
        let data = {};

        data.logged = customer.isLogged();

        data.wishlist = 0;

        if (customer.isLogged()) {
            data.wishlist = customer.getWishlist().length;
        }

        return await loader.template('common/header', { ...data, ...language, ...config });
    }
});