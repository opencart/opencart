import { loader } from './loader.js';

// Config
const config = await loader.config('default');

// library
const ajax = await loader.library('ajax');
const session = await loader.library('session');
const tax = await loader.library('tax');
const weight_class = await loader.library('weight');

export default class Cart {
    items = new Map();

    constructor() {
        if (session.has('cart')) {
            let cart = session.get('cart');

            console.log('*****constructor*****');

            console.log('session', cart);

            //let data = Object.entries(cart);

            //console.log('data', data);

            //this.items = new Map(data);

            //console.log('this.items', this.items);
        }
    }

    async add(cart_id, item = []) {
        console.log('add');

        //this.clear();

        this.items.get(cart_id, item);

        //let data = this.items.values();

        console.log('this.items.values', Object.values(this.items));

        session.set('cart', { ...this.items });
    }

    update(cart_id, item = []) {

    }

    remove(cart_id) {
        this.items = this.items.filter(item => item.cart_id !== cart_id);

        //session.set('cart', this.items);
    }

    getProducts() {
        //console.log(this.items.values());

        return [];
    }

    has(cart_id) {

    }

    clear() {
        this.items = new Map();
    }

    /**
     * Get Subscriptions
     *
     * @return array<int, array<string, mixed>>
     *
     * @example
     *
     * $subscriptions = $this->cart->getSubscriptions();
     */
    async getSubscriptions() {
        let product_data = [];

        for (let product of this.getProducts()) {
            if (product.subscription) {
                product_data.push(product);
            }
        }

        return product_data;
    }

    getWeight() {
        let weight = 0;

        for (let product of this.getProducts()) {
            if (product.shipping) {
                weight += weight_class.convert(product.weight, product.weight_class_id, config.config_weight_class_id);
            }
        }

        return weight;
    }

    getSubTotal() {
        let total = 0;

        for (let product of this.getProducts()) {
            total += product.total;
        }

        return total;
    }

    getTaxes() {
        let tax_data = [];

        for (let product of this.getProducts()) {
            if (product.tax_class_id) {
                let tax_rates = tax.getRates(product.price, product.tax_class_id);

                for (let tax_rate of tax_rates) {
                    let quantity = 1;

                    if (tax_rate.type == 'P') {
                        quantity = product.quantity;
                    }

                    if (!tax_rate.tax_rate_id in tax_data) {
                        tax_data[tax_rate.tax_rate_id] = (tax_rate.amount * quantity);
                    } else {
                        tax_data[tax_rate.tax_rate_id] += (tax_rate.amount * quantity);
                    }
                }
            }
        }

        return tax_data;
    }

    getTotal() {
        let total = 0.00;

        for (let product of this.getProducts()) {
            total += tax.calculate(product.price, product.tax_class_id, config.config_tax) * product.quantity;
        }

        return total;
    }

    /**
     * Count Products
     *
     * @return int
     *
     * @example
     *
     * $count_products = $this->cart->countProducts();
     */
    countProducts() {
        let quantity = 0;

        for (let product of this.getProducts()) {
            quantity += product.quantity;
        }

        return quantity;
    }

    /**
     * Has Products
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasProducts();
     */
    hasProducts() {
        return this.items.length > 0;
    }

    /**
     * Has Subscription
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasSubscription();
     */
    hasSubscription() {
        return this.getSubscriptions().length > 0;
    }

    /**
     * Has Stock
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasStock();
     */
    hasStock() {
        for (let product of this.getProducts()) {
            if (!product.stock_status) {
                return false;
            }
        }

        return true;
    }

    /**
     * Has Minimum
     *
     * Check if any products have a minimum order quantity amount and do not meet the requirement
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasMinimum();
     */
    hasMinimum() {
        for (let product of this.getProducts()) {
            if (!product.minimum_status) {
                return false;
            }
        }

        return true;
    }

    /**
     * Has Shipping
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasShipping();
     */
    async hasShipping() {
        for (let product of this.getProducts()) {
            if (product.shipping) {
                return true;
            }
        }

        return false;
    }

    /**
     * Has Download
     *
     * @return bool
     *
     * @example
     *
     * $cart = $this->cart->hasDownload();
     */
    hasDownload() {
        for (let product of this.getProducts()) {
            if (product.download) {
                return true;
            }
        }

        return false;
    }
}