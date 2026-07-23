import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_info');

// Library
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

// Storage
//const stock_status = await loader.storage('localisation/stock_status');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        // Product Info
        let product = await loader.storage('product/product-' + request.get('product_id'));

        if (product !== undefined && config.config_language in product.description) {
            data.product_id = product.product_id;

            // Images
            data.thumb = product.thumb;
            data.popup = product.popup;
            data.images = product.images;

            let description = product.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            data.heading_title = description.name;
            data.description = description.description;

            // Product Codes
            data.model = product.model;
            data.product_codes = product.product_codes;

            // Manufacturer
            data.manufacturer_id = product.manufacturer_id;
            data.manufacturer = product.manufacturer;

            // Price
            data.price = product.price;
            data.special = product.special;
            data.tax = '';

            if (config.config_tax) data.tax = product.special ? product.special : product.price;

            data.discounts = product.discounts;

            // Stock
            data.quantity = product.quantity;
            data.minimum = product.minimum;

            let stock_status_id = 0;

            if (product.quantity <= 0) {
                stock_status_id = product.stock_status_id;

                data.stock = false;
            } else if (!config.config_stock_display) {
                stock_status_id = config.config_stock_status_id;

                data.stock = true;
            } else {
                stock_status_id = 0;

                data.stock = true;
            }

            data.stock_status = product.stock_status;

            // Reward Points
            data.points = product.points;
            data.reward = product.reward;

            // Statistics
            data.sales = product.sales;
            data.rating = product.rating;

            // Weight
            data.weight = product.weight;
            data.weight_class_id = product.weight_class_id;

            // Dimensions
            data.length = product.length;
            data.width = product.width;
            data.height = product.height;
            data.length_class_id = product.length_class_id;

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

                data.options.push(Object.assign(option, { option_value: option_values }));
            }

            // Subscription Plans
            data.subscription_plans = [];

            for (let subscription_plan of product.subscription_plans) {
                let description = '';

                let price = product.special ? product.special : product.price;

                if (subscription_plan.duration) {
                    price = (product.special ? product.special : product.price) / subscription_plan.duration;
                }

                //price = tax.calculate(price, $product.tax_class_id, $this->config.config_tax'));
                let frequency = language['text_' + subscription_plan.frequency];

                if (subscription_plan.duration) {
                   //description = language['text_subscription_duration'] price, subscription_plan.cycle, frequency, subscription_plan.duration);
                } else {
                   //description = sprintf(language['text_subscription_cancel'], price, subscription_plan.cycle, frequency);
                }

                //data.subscription_plans[] = {
                //     'description' => description
                //} + $result;
            }

            // Tags
            data.tags = description.tag.split(',');

            data.related = [];

            data.review_status = config.config_review_status;

            data.currency = currency;

            return loader.template('catalog/product_info', { ...data, ...language, ...config });
        }
    }

    async addToCart(e) {
        e.preventDefault();

        console.log('addToCart');

        //this.$button_cart.state = 'loading';

        let target = e.target;

        let form = new FormData(target);

        let response = await fetch('index.php?route=checkout/cart.add', {
            method: 'POST',
            body: form
        });

        if (!response.ok) {
            console.log(response);

            //throw new Error(response.thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }

        let json = await response.json();

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

            //console.log(form);
            ///console.log(form.elements);
            //console.log(form.entries());
            //const uniqueString = [...new Set(matrix.flat(Infinity))].join(', ');

            let output = [];

            let regex = /^([^\[]+)\[(\w+)\]$/g;

            console.log('form2array');

            function nameToObject(str, value) {
                const match = str.match(/^([^\[]+)\[(\w+)\]$/);

                if (!match) return {
                    [str]: value
                };

                return {
                    [match[1]]: {
                        [match[2]]: value
                    }
                };
            }


            /*
            let test = form.entries().filter(item => {
               // console.log(item.get());

                item[0].match(/^option\[(.*?)\]/);
            });
*/
            console.log(nameToObject("test[1]", 'ghth'));


            for (let [key, value] of form.entries()) {
                // Get Options. JavaScript is terrible!
                let matches = key.match(/^option\[(.*?)\]/);

                console.log(key);
                console.log(value);
                console.log(matches);

                if (matches) {
                    let [, match] = matches;

                    console.log(match);

                    output[match] = value;
                }
            }



            let { product_id, quantity, option, subscription_plan_id } = Object.fromEntries(form);

            console.log(product_id);
            console.log(quantity);
            console.log(option);
            console.log(subscription_plan_id);

            //cart.add(product_id, quantity, option, subscription_plan_id);

           // let button = document.querySelector('#cart > button');

            //button.click();
        }

        //this.$button_cart.state = '';
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
        let subscription = e.target.value;

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