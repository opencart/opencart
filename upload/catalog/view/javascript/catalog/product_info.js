import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local, tax } from '../index.js';
import './review_form.js';
import './review_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_info');

// Storage
const stock_statuses = await loader.storage('localisation/stock_status');

export default class ProductInfo extends WebComponent {
    async render(){
        let data = new Map();

        let product = await loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product instanceof Map && local.get('language') in product.get('description')) {
            let description = product.get('description')[local.get('language')];

            // description.meta_title
            // description.meta_description
            // description.meta_keyword

            // Special
            data.set('special', '');

            let discount = product.get('discounts').find(discount => discount.quantity == 1 && (discount.customer_group_id == customer.getGroupId()) && (discount.date_start == '0000-00-00' || Date(discount.date_start).getTime() >= Date.now()) && (discount.date_end == '0000-00-00' || Date(discount.date_end).getTime() <= Date.now()));

            if (discount) {
                if (discount.type == 'F') {
                    data.set('special', Number(discount.price));
                } else if (discount.type == 'P') {
                    data.set('special', product.get('price') - Number(product.get('price') * (discount.price / 100)));
                } else if (discount.type == 'S') {
                    data.set('special', product.get('price') - Number(discount.price));
                }
            }

            // Discounts
            let discounts = product.get('discounts').filter(discount => discount.customer_group_id == customer.getGroupId() && (discount.date_start == '0000-00-00' || Date(discount.date_start).getTime() >= Date.now()) && (discount.date_end == '0000-00-00' || Date(discount.date_end).getTime() <= Date.now()));

            //data.discounts.sort(discounts => discount.quantity);

            data.set('tax', '');

            if (config.get('config_tax')) {
                data.set('tax', tax.getTax(data.get('special') ? data.get('special') : product.get('price'), product.get('tax_class_id')));
            }

            // Rewards
            data.set('reward', 0);

            let reward = product.get('rewards').find(reward => reward.customer_group_id == customer.getGroupId());

            if (reward) {
                data.set('reward', reward.points);
            }

            // Stock Status
            let stock_status_id = 0;

            if (product.get('quantity') <= 0) {
                stock_status_id = product.get('stock_status_id');

                data.set('stock', false);
            } else if (!config.get('config_stock_display')) {
                stock_status_id = config.get('config_stock_status_id');

                data.set('stock', false);
            } else {
                data.set('stock', true);
            }

            let stock_status = stock_statuses.find(stock_status => stock_status.stock_status_id == stock_status_id);

            if (stock_status) {
                data.set('stock_status', stock_status.description[local.get('language')].name);
            }

            // Attributes
            data.set('attribute_groups', []);

            for (let attribute_group of product.get('attribute_groups')) {
                let attributes = [];

                for (let attribute of attribute_group.attribute) {
                    attributes.push(attribute.description[local.get('language')]);
                }

               data.get('attribute_groups').push({
                   name: attribute_group.description[local.get('language')].name,
                   attribute: attributes
               });
            }

            // Options
            data.set('options', []);

            for (let option of product.get('options')) {
                let option_values = [];

                for (let option_value of option.option_value) {
                    option_values.push(Object.assign(option_value, option_value.description[local.get('language')]));
                }

                data.get('options').push(Object.assign(option, {
                    name: option.description[local.get('language')].name,
                    option_value: option_values
                }));
            }

            // Subscription Plans
            data.set('subscription_plans', []);

            for (let subscription_plan of product.get('subscription_plans')) {
                let price = data.get('special') ? data.get('special') : product.get('price');

                if (subscription_plan.duration) {
                    price = (data.get('special') ? data.get('special') : product.get('price')) / subscription_plan.duration;
                }

                data.get('subscription_plans').push({
                    name: subscription_plan.description[local.get('language')].name,
                    ...subscription_plan
                });
            }

            // Tags
            data.set('tags', product.get('tags'));
            data.set('related', []);

            data.set('currency', local.get('currency'));

            return loader.template('catalog/product_info', [ product, description, data, language, config ]);
        }
    }

    async addToCart(e){
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {
               this.button.button('loading');
            },
            onComplete: () => {
                this.get('button').button('reset');
            },
            onSuccess: async (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

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

                        let error = this.form.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
                }

                // Display success message
                if ('success' in json) {
                    let alert = this.form.querySelector('#alert');

                    if (alert) {
                        alert.prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');
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

    async addToWishList(e) {
        e.preventDefault();

        console.log('addToWishList');
        console.log(e);
    }

    async addToCompare(e){
        e.preventDefault();

        console.log('addToCompare');
        console.log(e);
    }

    onClick(e) {
        e.preventDefault();

        console.log('onClick');
        console.log(e);
    }

    onChange(e) {
        let subscription = e.currentTarget.value;

        subscription.classList.add('d-none');

        //$('#subscription-description-' + $(element).val()).classList.remove('d-none');
    }

    popup() {
        $('.magnific-popup').magnificPopup({
            type: 'image',
            delegate: 'a',
            gallery: {
                enabled: true
            }
        });
    }
}

customElements.define('product-info', ProductInfo);