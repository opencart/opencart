import {local, WebComponent} from '../index.js';
import { loader, ajax, cart, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/confirm');

customElements.define('checkout-confirm', class extends WebComponent {
    async onConnect(){

    }

    async render(){
        let data = new Map();

        data.set('products', cart.getProducts());
        data.set('currency', local.get('currency'));

        return loader.template('checkout/confirm', [ data, language, config ]);
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {

            },
            onComplete: () => {

            },
            onSuccess: this.succcess.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
});


