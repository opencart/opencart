import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const cart = await loader.library('cart');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/cart');

class CommonCart extends WebComponent {
    async connected() {

    };

    render() {
        let data = {};

        return loader.template('common/cart', { ...data,  ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        this.request.fetch({

        });
    }
}

