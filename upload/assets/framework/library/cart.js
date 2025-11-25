import { registry } from './registry.js';

class Cart {
    static instance = null;
    config = null;
    language = null;
    tax = null;
    session = null;
    customer = null;
    data = [];

    constructor(registry) {
        //this.config = registry.config;
        this.language = registry.language;
        this.tax = registry.tax;
        this.session = registry.session;

        //this.customer = this.session.get('customer');

        //this.data = this.session.get('cart');
    }

    add(item) {
        this.data = item;
    }

    remove() {
        return this.data;
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Cart(registry);
        }

        return this.instance;
    }
}

const cart = Cart.getInstance(registry);

export { cart };
