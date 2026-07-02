import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/header');

// library
const session = await loader.library('session');

export default class extends Controller {
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

        target.src = e.target.getAttribute('href');
    }
};