import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import './product_thumb.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_list');

class ProductList extends WebComponent {
    static observed = [
        'path',
        'filter',
        'manufacturer_id',
        'sort',
        'order',
        'limit',
        'page'
    ];

    set path(path) {

    }

    constructor() {
        super();

        this.setAttribute('sort', 'latest');
        this.setAttribute('order', 'desc');
        this.setAttribute('page', 1);
        this.setAttribute('limit', config.get('config_pagination'));
    }

    async render(){
        let data = new Map();

        for (let attribute of ProductList.observed) {
            if (this.hasAttribute(attribute)) {
                data.set(attribute, this.getAttribute(attribute));
            } else if (!data.has(attribute)) {
                data.set(attribute, '');
            }
        }

        // Products
        data.set('products', []);
        data.set('total', 0);

        let products = await loader.storage('category/category-product-' + this.getAttribute('category_id'));

        if (products instanceof Array) {
            data.set('products', products);
            data.set('total', products.length);
        }

        return loader.template('catalog/product_list', [ data, language, config ]);
    }

    onChange(e) {
        this.update();
    }
}

customElements.define('product-list', ProductList);

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