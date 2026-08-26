import { Controller } from '../component.js';
import { loader } from '../index.js';
import './review.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_info');

// Library
const ajax = await loader.library('ajax');
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

// Storage
const stock_statuses = await loader.storage('localisation/stock_status');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        // Product Info
        let product = await loader.storage('product/product-' + request.get('product_id'));

        if (product !== undefined && config.config_language in product.description) {
            let description = product.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            // Price
            data.price = product.price;
            data.special = '';

            //  && discount.date_start >= Date.now() && discount.date_end <= Date.now()
            let discount = product.discounts.find(discount => discount.quantity == 1 && discount.customer_group_id == config.config_customer_group_id);

            if (discount) {
                let price = '';

                if (discount.type == 'F') {
                    price = discount.price;
                } else if (discount.type == 'P') {
                    price -= (data.price * (discount.price / 100));
                } else if (discount.type == 'S') {
                    price -= discount.price;
                }

                if (!discount.special) {
                    data.price = discount.price;
                } else {
                    data.special = discount.price;
                }
            }

            data.discounts = [];

            // && discount.date_start >= Date.now() && discount.date_end <= Date.now()
            let discounts = product.discounts.filter(discount => discount.customer_group_id == config.config_customer_group_id);

            console.log(discounts);

            discounts.sort(discounts => discount.quantity);

            for (let discount of discounts) {

                data.discounts.push({
                    dffd: product.discounts

                });

            }

            console.log(data.discounts);

            data.tax = '';

            if (config.config_tax) data.tax = product.special ? product.special : product.price;

            data.reward = 0;

            // && reward.date_start >= Date.now() && reward.date_end <= Date.now()
            let reward = product.rewards.find(reward => reward.customer_group_id == config.config_customer_group_id);

            if (reward) {
                data.reward = reward.points;
            }

            let stock_status_id = 0;

            if (product.quantity <= 0) {
                stock_status_id = product.stock_status_id;

                data.stock = false;
            } else if (!config.config_stock_display) {
                stock_status_id = config.config_stock_status_id;

                data.stock = false;
            } else {
                data.stock = true;
            }

            let stock_status = stock_statuses.find(stock_status => stock_status.stock_status_id == stock_status_id);

            if (stock_status) {
                data.stock_status = stock_status.description[config.config_language].name;
            }

            // Attributes
            data.attribute_groups = [];

            for (let attribute_group of product.attribute_groups) {
                let attributes = [];

                for (let attribute of attribute_group.attribute) {
                    attributes.push(attribute.description[config.config_language]);
                }

               data.attribute_groups.push({
                   name: attribute_group.description[config.config_language].name,
                   attribute: attributes
               });
            }

            data.options = [];

            for (let option of product.options) {
                let option_values = [];

                for (let option_value of option.option_value) {
                    option_values.push(Object.assign(option_value, option_value.description[config.config_language]));
                }

                data.options.push(Object.assign(option, {
                    name: option.description[config.config_language].name,
                    option_value: option_values
                }));
            }

            // Subscription Plans
            data.subscription_plans = [];

            for (let subscription_plan of product.subscription_plans) {
                let price = product.special ? product.special : product.price;

                if (subscription_plan.duration) {
                    price = (product.special ? product.special : product.price) / subscription_plan.duration;
                }

                data.subscription_plans.push({
                    name: subscription_plan.description[config.config_language].name,
                    ...subscription_plan
                });
            }

            // Tags
            data.tags = product.tags;

            console.log(data.tags);

            data.related = [];

            data.currency = currency;

            return loader.template('catalog/product_info', { ...product, ...description, ...data, ...language, ...config });
        }
    }

    async addToCart(e) {
        e.preventDefault();

        console.log('addToCart');

        this.bind('button-cart').loading = true;

        let target = e.target;

        let form = new FormData(target);

        ajax.post('index.php?route=checkout/cart.add', form, {
            beforeSend: (request) => {
               this.bind('button-cart').setAttribute('loading', '');
            },
            onComplete: (json) => {
                console.log(this.bind('button-cart'));

                //this.bind('button-cart').loading = false;
            },
            onSuccess: (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json['error'] !== undefined) {
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
                if (json['success'] !== undefined) {
                    let alert = target.querySelector('#alert');

                    if (alert) {
                        alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }

                    let output = [];

                    console.log(json['products']);

                    //console.log(Object.fromEntries(form));
                    for (let product of json['products']) {
                        cart.add(product);
                    }

                    let button = document.querySelector('#cart > button');

                    button.click();
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

    async addToCompare(e) {
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