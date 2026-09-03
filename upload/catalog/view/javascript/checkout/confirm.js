import {Controller, WebComponent} from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

customElements.define('checkout-confirm', class extends WebComponent {
    async connected() {

    }

    async render() {




        return loader.template('checkout/confirm', { ...data,  ...language });
    }

    onSubmit() {


    }
});


