import { WebComponent } from '../component.js';
import { loader, ajax, cart, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/confirm');

customElements.define('checkout-confirm', class extends WebComponent {
    async connected(){

    }

    async render(){
        let data = {};

        return loader.template('checkout/confirm', { ...data,  ...language });
    }

    onSubmit() {


    }
});


