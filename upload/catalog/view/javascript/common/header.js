import { WebComponent } from '../index.js';
import { loader, session, cart, customer } from '../index.js';
import '../common/cart.js';
import '../common/currency.js';
import '../common/language.js';
import '../common/menu.js';
import '../common/search.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/header');

customElements.define('common-header', class extends WebComponent {
    async render() {
        let data = new Map();

        data.set('logged', customer.isLogged());

        data.set('wishlist', customer.isLogged() ? customer.getWishlist().length : 0);

        return await loader.template('common/header', [ data, language, config ]);
    }
});