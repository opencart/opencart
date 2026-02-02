import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const cart = await loader.library('cart');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/cart');

// library
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

class CommonCart extends WebComponent {
    render() {
        let data = {};

        data.currency = currency;

        return loader.template('common/cart', { ...data,  ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        this.request.fetch({

        });
    }
}

