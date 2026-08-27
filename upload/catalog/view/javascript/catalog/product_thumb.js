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
            let description = product.description[config.config_language];

            // && discount.date_start >= Date.now() && discount.date_end <= Date.now()
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

            data.price = tax.calculate(product.price);
            data.special = tax.calculate(product.special);
            data.tax = '';

            if (config.config_tax) {
                data.tax = product.special ? product.special : product.price;
            }

            data.currency = currency;

            return await loader.template('catalog/product_thumb', { ...product, ...description, ...data, ...language, ...config });
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