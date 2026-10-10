import { WebComponent } from '../index.js';
import { loader, ajax, cart } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_info');

session.get('dff');

export default class CatalogCompare extends WebComponent {
    async render() {
        let data = new Map();

        return loader.template('catalog/product_info', [ product, description, data, language, config ]);
    }

    addToCompare() {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: async (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    let alert = this.form.querySelector('#alert');

                    if (alert) {
                        alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                    }

                    // Code to use [] with js
                    let option = new Map();

                    let inputs = [...form].filter(input => input[0].indexOf('option') !== -1);

                    for (let [key, value] of inputs) {
                        let option_id = key.match(/\[([^\]]*)\]/)[1];

                        if (key.substr(-2) !== '[]') {
                            option.set(option_id, value);
                        } else if (!option.has(option_id)) {
                            option.set(option_id, [value]);
                        } else {
                            option.set(option_id, [...option.get(option_id), value]);
                        }
                    }

                    await cart.add(form.get('product_id'), form.get('quantity'), option, form.get('subscription_plan_id'));

                    console.log('getProducts', cart.getProducts());
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('catalog-compare', CatalogCompare);