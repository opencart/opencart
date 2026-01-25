import { WebComponent } from '../component.js';
import { loader } from "../index.js";

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('catalog/thumb');

// Library
const tax = await loader.library('tax');

class ProductThumb extends WebComponent {
    async connected() {

    }

    async render() {
        let data = {};

        // customer groups
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
        }

        return loader.template('catalog/thumb', { ...data, ...language, ...config });
    }

    onSubmit(e) {


    }
}

customElements.define('product-thumb', ProductThumb);