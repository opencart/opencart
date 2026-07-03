import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('component/cart');

// library
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

customElements.define('component-cart', class extends WebComponent {
    render() {
        let data = {};

        data.currency = currency;

        return loader.template('component/cart', { ...data,  ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        loader.request({
            onComplete: () => {


            }
        });
    }
});