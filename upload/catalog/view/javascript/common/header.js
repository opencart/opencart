import { WebComponent } from '../component.js';
import { loader } from '../index.js';
import '../component/currency.js';
import '../component/language.js';
import '../component/search.js';
import '../component/cart.js';
import '../component/menu.js';

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

        console.log(e);


        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
});