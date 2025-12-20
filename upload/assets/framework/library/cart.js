export class Cart {
    config = null;
    language = null;
    tax = null;
    session = null;
    customer = null;
    data = [];

    constructor(registry) {
        this.config = registry.get('config');
        this.language = registry.get('language');
        this.tax = registry.get('tax');
        this.session = registry.get('session');

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
}
