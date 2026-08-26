import { WebComponent } from '../component.js';
import { loader } from '../index.js';
import '../common/currency.js';
import '../common/language.js';
import '../common/search.js';
import '../common/cart.js';
import '../common/menu.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/header');

// library
const session = await loader.library('session');

customElements.define('common-header', class extends WebComponent {
    async render() {
        let data = {};

        data.wishlist = 0;

        data.logged = session.has('customer');

        if (data.logged) {
            data.wishlist = session.get('customer').getWishlist().length;
        }

        return await loader.template('common/header', { ...data, ...language, ...config });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        console.log(e);
        console.log(target);


        target.src = e.currentTarget.getAttribute('href');
    }
});