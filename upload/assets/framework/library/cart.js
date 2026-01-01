import { loader } from './loader.js';

class Cart {
    static instance = null;
    config = null;
    session = null;
    customer = null;
    tax = null;
    data = [];

    constructor(loader) {
        this.config = loader.config('config');
        this.session = loader.library('session');
        this.tax = loader.library('tax');

        //this.customer = this.session.get('customer');
        //this.data = this.session.get('cart');
    }

    add(item) {
        this.data = item;
    }

    remove(key) {
        return this.data;
    }

    getProducts() {

    }

    getTotal() {

    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Cart();
        }

        return this.instance;
    }
}

const cart = Cart.getInstance(loader);

export default cart;