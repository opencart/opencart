import { WebComponent } from '../index.js';
import { loader, ajax, cart, local, tax } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_thumb');

customElements.define('product-thumb', class extends WebComponent {
    async render() {
        let data = new Map();

        let product = await loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product instanceof Map && local.get('language') in product.get('description')) {
            let description = product.description[local.get('language')];

            // Special
            data.set('special', '');

            let discount = product.discounts.find(discount =>  discount.quantity == 1 && discount.customer_group_id == config.get('config_customer_group_id') && (discount.date_start == '0000-00-00' || Date(discount.date_start).getTime() >= Date.now()) && (discount.date_end == '0000-00-00' || Date(discount.date_end).getTime() <= Date.now()));

            if (discount) {
                if (discount.type == 'F') {
                    data.special = discount.price;
                } else if (discount.type == 'P') {
                    data.special -= (data.price * (discount.price / 100));
                } else if (discount.type == 'S') {
                    data.special -= discount.price;
                }
            }

            data.set('tax', '');

            if (config.get('config_tax')) {
                data.set('tax', tax.getTax(data.get('special') ? data.get('special') : product.get('price'), product.get('tax_class_id')));
            }

            data.set('currency', local.get('currency'));

            return await loader.template('catalog/product_thumb', [ product, description, data, language, config ]);
        }
    }

    addToCart(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {

            },
            onComplete: () => {

            },
            onSuccess: (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                if ('redirect' in json) {
                    location = json['redirect'];
                }

                // Display error messages
                if ('error' in json) {
                    for (let key in json['error']) {
                        let value = key.replaceAll('_', '-');

                        let input = target.querySelector('#input-' + value);

                        if (input) {
                            input.classList.add('is-invalid');

                            // If the element has inputs inside.
                            input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        }

                        let error = target.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
                }

                // Display success message
                if ('success' in json) {
                    this.alert.prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');

                    let item = [];

                    cart.add(cart);

                    let button = document.querySelector('#cart > button');

                    button.click();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    addToWishlist(e) {
        e.preventDefault();


    }

    addToCompare(e) {
        e.preventDefault();


    }
});