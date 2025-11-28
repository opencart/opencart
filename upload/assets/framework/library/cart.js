export class Cart {
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
}
