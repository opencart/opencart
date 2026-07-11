import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_thumb');

// library
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

customElements.define('product-thumb', class extends WebComponent {
    async render() {
        let data = {};

        // Get product by product ID
        let product = await loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product !== undefined && config.config_language in product.description) {
            data.product_id = product.product_id;
            data.thumb = product.thumb;

            let description = product.description[config.config_language];

            data.name = description.name;
            data.description = description.description.substr(0, config.config_product_description_length);

            data.model = product.model;
            data.price = tax.calculate(product.price);
            data.special = tax.calculate(product.special);
            data.tax = '';

            if (config.config_tax) {
                data.tax = product.special ? product.special : product.price;
            }

            data.rating = product.rating;
            data.minimum = product.minimum;

            data.currency = currency;

            return await loader.template('catalog/product_thumb', { ...data, ...language, ...config });
        }
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }

    addToCart(e) {
        e.preventDefault();

        this.$button_wishlist.getAttribute('action');
        this.$button_cart.getAttribute('action');
        this.$button_cart.getAttribute('compare_add');


    }

    addToWishlist(e) {
        e.preventDefault();


    }

    addToCompare(e) {
        e.preventDefault();


    }
});