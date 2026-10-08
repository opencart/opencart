import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import './product_thumb.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_list');

class ProductList extends WebComponent {
    static observedAttributes = [
        'search',
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

        data.set('search', '');
        data.set('path', '');
        data.set('filter', '');
        data.set('manufacturer_id', 0);
        data.set('sort', 'asc');
        data.set('order', 'latest');
        data.set('page', 1);
        data.set('limit', Number(config.get('config_pagination')));

        if (this.hasAttribute('search')) {
            data.set('search', this.getAttribute('search'));
        }

        if (this.hasAttribute('path')) {
            data.set('path', this.getAttribute('path').replace(/[^0-9_]+/));
        }

        if (this.hasAttribute('filter')) {
            data.set('filter', this.getAttribute('filter'));
        }

        if (this.hasAttribute('manufacturer_id')) {
            data.set('manufacturer_id', parseInt(this.getAttribute('manufacturer_id')));
        }

        if (this.hasAttribute('sort')) {
            data.set('sort', this.getAttribute('sort'));
        }

        if (this.hasAttribute('order')) {
            data.set('order', this.getAttribute('order') === 'asc' ? 'asc' : 'desc');
        }

        if (this.hasAttribute('limit')) {
            data.set('limit', parseInt(this.getAttribute('limit')));
        }

        if (this.hasAttribute('page')) {
            data.set('page', parseInt(this.getAttribute('page')));
        }

        data.set('limits', [10, 20, 30, 40, 50]);

        // Products
        data.set('products', []);
        data.set('total', 0);

        let products = await loader.storage('category/category-product-' + data.get('path'));

        let product_total = products.length;

        if (products instanceof Array) {
            if (data.get('sort') == 'asc') {
                data.set('products', products);
            } else {
                data.set('products', products.reverse());
            }

            data.set('total', product_total);
        }

        return loader.template('catalog/product_list', [ data, language, config ]);
    }

    onFilter(e) {
        this.getAttribute('filter', e.target.value);
    }

    onManufacturerId(e) {
        this.getAttribute('manufacturer_id', e.target.value);
    }

    onSort(e) {
        this.getAttribute('sort', e.target.value);
    }

    onOrder(e) {
        this.getAttribute('order', e.target.value);

        this.update();
    }

    onLimit(e) {
        this.getAttribute('limit', e.target.value);
    }

    onPage(e) {
        this.getAttribute('page', e.target.value);
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