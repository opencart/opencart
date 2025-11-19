export default class Cart {
    data = [];

    constructor(registry) {
        this.config = registry.config;
        this.language = registry.language;
        this.tax = registry.tax;
        this.session = registry.session;

        this.customer = this.session.get('customer');

        this.data = this.session.get('cart');
    }

    add(item) {
        this.data = item;
    }

    remove() {
        return this.data
    }

    static async getInstance(registry) {
        if (!this.instance) {
            this.instance = new Cart(registry);
        }

        return this.instance;
    }
}