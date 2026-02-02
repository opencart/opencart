import { WebComponent } from '../component.js';
import { loader } from "../index.js";

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('catalog/thumb');

// library
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

class ProductThumb extends WebComponent {
    async render() {
        let data = {};

        // Get product by product ID
        let product = await loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product.length) {
            data.thumb = product.thumb;

            data.name = product.name;
            data.description = product.description;
            data.model = product.model;
            data.price = tax.calculate(product.price);
            data.special = tax.calculate(product.special);
            data.tax = '';

            if (config.config_tax) {
                data.tax = product.special ? product.special : product.price;
            }

            data.rating = product.rating;
            data.minimum =  product.minimum;

            data.currency = currency;
        }

        return loader.template('catalog/thumb', { ...data, ...language, ...config });
    }

    onSubmit(e) {
        e.preventDefault();

        this.$button_wishlist.getAttribute('action');
        this.$button_cart.getAttribute('action');
        this.$button_cart.getAttribute('compare_add');

    }
}

customElements.define('product-thumb', ProductThumb);