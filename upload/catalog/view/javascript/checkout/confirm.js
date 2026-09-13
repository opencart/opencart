import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// library
const ajax = await loader.library('ajax');
const cart = await loader.library('cart');
const customer = await loader.library('customer');

// Language
const language = await loader.language('checkout/confirm');

customElements.define('checkout-confirm', class extends WebComponent {
    async connect(){

    }

    async render(){


        return loader.template('checkout/confirm', { ...data,  ...language });
    }

    onSubmit() {


    }
});


