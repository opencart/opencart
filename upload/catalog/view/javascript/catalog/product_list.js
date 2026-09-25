import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import './product_thumb.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_list');

customElements.define('product-list', class extends WebComponent {
    static observed = [
        'path',
        'filter',
        'manufacturer_id',
        'sort',
        'order',
        'limit',
        'page'
    ];

    async render(){
        let data = new Map();

        console.log(this.observed);

        for (let attribute of attributes) {
            if (this.hasAttribute(attribute)) {
                data.get(attribute, this.getAttribute(attribute));
            }
        }

        // Products
        data.set('products', []);

        let products = await loader.storage('category/category-product-' + this.getAttribute('category_id'));

        if (products instanceof Map) {
            data.get('products').push(products);
        }

        return loader.template('catalog/product_list', [ data, language, config ]);
    }

    onChange(e) {

    }
});

/*
$(document).ready(function() {
    // Product List
    $('#button-list').on('click', function() {
        var element = this;

        $('#product-list').attr('class', 'row row-cols-1 product-list');

        $('#button-grid').removeClass('active');
        $('#button-list').addClass('active');

        localStorage.setItem('display', 'list');
    });

    // Product Grid
    $('#button-grid').on('click', function() {
        var element = this;

        // What a shame bootstrap does not take into account dynamically loaded columns
        $('#product-list').attr('class', 'row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3');

        $('#button-list').removeClass('active');
        $('#button-grid').addClass('active');

        localStorage.setItem('display', 'grid');
    });

 */