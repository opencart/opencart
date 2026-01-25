import { WebComponent } from '../component.js';
import { loader } from "../index.js";

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('catalog/thumb');

// Template
const template = await loader.template('catalog/thumb');

// Library
const tax = await loader.library('tax');

class ProductThumb extends WebComponent {
    async connected() {

    }

    render() {
        let data = {};

        // customer groups
        let product = loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product.length) {
            data.name = product.name;
            data.description = product.description;
            data.thumb = product.thumb;
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

        return this.render(template, { ...data, ...language, ...config });
    }

    onSubmit() {


    }
}

customElements.define('product-thumb', ProductThumb);