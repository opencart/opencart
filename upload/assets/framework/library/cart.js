import { loader } from './loader.js';

// library
let session = await loader.library('session');
let tax = await loader.library('tax');

// Config
let config = await loader.config('default');

//let data = session.get('cart');

export default class Cart {
    constructor() {
        this.customer = null;
        this.data = new Array();
    }

    async add(item = []) {
        console.log('add');
        console.log(item);

        this.data.push(item);
    }

    remove(cart_id) {
        this.data = this.data.filter(item => item.cart_id !== cart_id);
    }

    getProducts() {
        console.log(this.data);

        return this.data;
    }

    update() {
    }

    has() {
    }

    remove() {
    }

    clear() {
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
                weight += weight.convert(product.weight, product.weight_class_id, config.config_weight_class_id);
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
            total += tax.calculate(product.price, product.tax_class_id, config.get('config_tax')) * product.quantity;
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
        return this.getProducts().length ? true : false;
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
        return this.getSubscriptions().length ? true : false;
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