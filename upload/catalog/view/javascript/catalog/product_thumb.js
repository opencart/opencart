import { WebComponent } from '../index.js';
import { loader, ajax, cart, local, tax } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_thumb');

customElements.define('product-thumb', class extends WebComponent {
    async render() {
        let data = new Map();

        let product = await loader.storage('product/product-' + parseInt(this.getAttribute('product_id')));

        if (!product instanceof Map || !local.get('language') in product.get('description')) return;

        let description = product.get('description')[local.get('language')];

        // Special
        data.set('special', '');

        let discount = product.get('discounts').find(discount =>  discount.quantity == 1 && discount.customer_group_id == config.get('config_customer_group_id') && (discount.date_start == '0000-00-00' || Date(discount.date_start).getTime() >= Date.now()) && (discount.date_end == '0000-00-00' || Date(discount.date_end).getTime() <= Date.now()));

        if (discount) {
            if (discount.type == 'F') {
                data.set('special', Number(discount.price));
            } else if (discount.type == 'P') {
                data.set('special', Number(product.get('price')) - Number(product.get('price') * (discount.price / 100)));
            } else if (discount.type == 'S') {
                data.set('special', Number(product.get('price')) - Number(discount.price));
            }
        }

        data.set('tax', '');

        if (config.get('config_tax')) {
            data.set('tax', tax.getTax(data.get('special') ? data.get('special') : product.get('price'), product.get('tax_class_id')));
        }

        data.set('currency', local.get('currency'));

        return await loader.template('catalog/product_thumb', [ product, description, data, language, config ]);
    }

    addToCart(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                this.form.state.clear();

                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.alert.append('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

                   // cart.add(cart);

                }
            },
            handleError: (e) => {
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