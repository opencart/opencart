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

    async handleConnect(){

    }

    async handleSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/cart.add', form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: this.succcess.bind(this),
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }



});


