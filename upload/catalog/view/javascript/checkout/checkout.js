import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/checkout');

// library
const cart = await loader.library('cart');
const customer = await loader.library('customer');

export default class extends Controller {
    connected() {

    }

    async render() {
        let data = {};

        //let download = cart.hasDownload();
        //let minimum = cart.hasMinimum();

        data.logged = customer.isLogged();
        data.shipping = cart.hasShipping();

        return loader.template('checkout/checkout', { ...data, ...language, ...config });
    }
}