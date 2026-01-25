import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/header');

// library
const session = await loader.library('session');

class CommonHeader extends WebComponent {
    async connected() {

    }

    render() {
        let data = {};

        data.wishlist = 0;

        data.logged = session.has('customer');

        if (data.logged) {
            data.wishlist = session.get('customer').getWishlist().length;
        }

        return loader.template('common/header', { ...data, ...language, ...config });
    }
}

customElements.define('common-header', CommonHeader);