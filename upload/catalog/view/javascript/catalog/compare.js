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
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: () => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: async (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json.has('error')) {
                    for (let key in json['error']) {
                        let value = key.replaceAll('_', '-');

                        let input = this.form.querySelector('#input-' + value);

                        if (input) {
                            input.classList.add('is-invalid');

                            // If the element has inputs inside.
                            input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        }

                        let error = this.form.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
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
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('catalog-compare', CatalogCompare);