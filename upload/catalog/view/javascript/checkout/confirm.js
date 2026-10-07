import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/confirm');

customElements.define('checkout-confirm', class extends WebComponent {
    async render(){
        let data = new Map();

        data.set('products', cart.getProducts());
        data.set('currency', local.get('currency'));

        return loader.template('checkout/confirm', [ data, language, config ]);
    }

    async onConnect(){

    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {
                this.submitter.setAttribute('loading');
            },
            onComplete: () => {
                this.submitter.removeAttribute('loading');
            },
            onSuccess: this.succcess.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }



});


