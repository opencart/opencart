import { loader } from './loader.js';

// Config
const config = await loader.config('default');

// library
const ajax = await loader.library('ajax');
const session = await loader.library('session');
const tax = await loader.library('tax');
const weight_class = await loader.library('weight');

let items = [];

//session.delete('cart');

if (session.has('cart')) {
    items = await session.get('cart');
}

export default class Cart {
    instance;

    constructor() {
        this.data = new Map();
        this.items = new Map();

        for (let item of items) {
            this.add(item.product_id, item.quantity, [...item.option], item.subscription_plan_id);
        }
    }

    async add(product_id, quantity, option, subscription_plan_id) {
        // Load product data information
        if (!this.data.has(product_id)) {
            let product = await loader.storage('product/product-' + product_id);

            if (!product) return;

            this.data.set(product_id, product);
        }

        // Create a key from item data
        let key = JSON.stringify({ product_id, option, subscription_plan_id });

        // If item exists just increase quantity
        if (this.items.has(key)) quantity = Number(this.items.get(key).quantity) + Number(quantity);

        // Assign to quantity to item
        let item = {
            product_id: product_id,
            quantity: quantity,
            option: option ? [...option] : [],
            subscription_plan_id: subscription_plan_id
        };

        this.items.set(key, item);

        // Update the session
        session.set('cart', [...this.items.values()]);
    }

    update(key, quantity) {
        if (this.items.has(key)) {
            // If item exists just increase quantity
            this.items.set(key, Object.assign(this.items.get(key), { quantity: quantity }));

            // Update the session
            session.set('cart', [...this.items.values()]);
        }
    }

    remove(key) {
        this.items.delete(key);

        // Update the session
        session.set('cart', [...this.items.values()]);
    }

    getProducts() {
        let product_data = [];

        for (let item of [...this.items.values()]) {
            let stock_status = true;

            let product_info = this.data.get(item.product_id);

            if (product_info !== undefined && item.quantity > 0) {
                let stock = product_info.quantity;

                let option_price = 0;
                let option_points = 0;
                let option_weight = 0;

                let option_data = [];

                for (let [key, value] of [...item.option]) {
                    // Get option info
                    let option_info = product_info.options.find(option => option.product_option_id == key);

                    if (option_info.type == 'select' || option_info.type == 'radio') {
                        let option_value_info = option_info.option_value.find(option => option.product_option_value_id == value);

                        option_price += Number(option_value_info.price);
                        option_points += Number(option_value_info.points);
                        option_weight += Number(option_value_info.weight);

                        if (option_value_info.subtract && (!option_value_info.quantity || (option_value_info.quantity < item.quantity))) {
                            stock_status = false;
                        }

                        option_data.push({
                            product_option_id: option_info.product_option_id,
                            product_option_value_id: option_value_info.product_option_value_id,
                            option_id: option_info.option_id,
                            option_value_id: option_value_info.option_value_id,
                            name: option_info.description[config.config_language].name,
                            value: option_value_info.description[config.config_language].name,
                            type: option_info.type,
                            quantity: Number(item.quantity),
                            subtract: option_value_info.subtract,
                            price: Number(option_value_info.price),
                            points: Number(option_value_info.points),
                            weight: Number(option_value_info.weight)
                        });
                    } else if (option_info.type == 'checkbox' && Array.isArray(value)) {
                        for (let product_option_value_id of value) {
                            let option_value_info = option_info.option_value.find(option => option.product_option_value_id == product_option_value_id);

                            if (option_value_info) {
                                option_price += option_value_info.price;
                                option_points += option_value_info.points;
                                option_weight += option_value_info.weight;

                                if (option_value_info.subtract && (!option_value_info.quantity || (option_value_info.quantity < item.quantity))) {
                                    stock_status = false;
                                }

                                option_data.push({
                                    product_option_id: option_info.product_option_id,
                                    product_option_value_id: option_value_info.product_option_value_id,
                                    name: option_info.description[config.config_language].name,
                                    value: option_value_info.description[config.config_language].name,
                                    type: option_info.type,
                                    quantity: Number(item.quantity),
                                    subtract: option_value_info.subtract,
                                    price: Number(option_value_info.price),
                                    points: Number(option_value_info.points),
                                    weight: Number(option_value_info.weight)
                                });
                            }
                        }
                    } else if (option_info.type == 'text' || option_info.type == 'textarea' || option_info.type == 'file' || option_info.type == 'date' || option_info.type == 'datetime' || option_info.type == 'time') {
                        option_data.push({
                            product_option_id: option_info.product_option_id,
                            product_option_value_id: 0,
                            name: option_info.description[config.config_language].name,
                            option_value_id: 0,
                            value: value,
                            quantity: 0,
                            subtract: 0,
                            price: 0,
                            points: 0,
                            weight: 0
                        });
                    }
                }

                // Get total products of the same product but with different options
                let product_total = 0;

                for (let item_2 of this.data) {
                    if (item_2.product_id == item.product_id) {
                        product_total += item_2.quantity;
                    }
                }

                let price = Number(product_info.price + option_price);

                let subscription_data = [];

                // Get option info
                let subscription_info = product_info.subscription_plans.find(subscription_plan => subscription_plan.subscription_plan_id == item.subscription_plan_id && subscription_plan.customer_group_id == config.config_customer_group_id);

                if (subscription_info) {
                    subscription_data.push({
                        subscription_plan_id: subscription_info.subscription_plan_id,
                        customer_group_id: subscription_info.customer_group_id,
                        name: subscription_info.description[config.config_language].name,
                        trial_price: Number(subscription_info.trial_price),
                        trial_frequency: subscription_info.trial_frequency,
                        trial_duration: subscription_info.trial_duration,
                        trial_cycle: subscription_info.trial_cycle,
                        trial_status: subscription_info.trial_status,
                        cycle: subscription_info.cycle,
                        frequency: subscription_info.frequency,
                        duration: Number(subscription_info.duration),
                        remaining: Number(subscription_info.duration),
                        price: Number(subscription_info.price),
                        sort_order: subscription_info.sort_order
                    });

                    // Set the new price if is subscription product
                    price = Number(subscription_info.price);

                    if (subscription_info.trial_status) {
                        price = Number(subscription_info.trial_price);
                    }
                }

                // Product Discounts
                let discount_info = product_info.discounts.find(discount => discount.customer_group_id == config.config_customer_group_id && discount.quantity <= product_total);

                if (discount_info) {
                    if (discount_info.type == 'F') {
                        // Fixed Price
                        price = Number(discount_info.price + option_price);
                        // Percentage
                        price -= Number(price * (discount_info.price / 100));
                    } else if (discount_info.type == 'S') {
                        // Subtract
                        price -= Number(discount_info.price);
                    }
                }

                // Stock
                if (!product_info.quantity || (product_info.quantity < product_total)) {
                    stock_status = false;
                }

                let minimum = true;

                // Minimum Quantity
                if (product_info.minimum > product_total) {
                    minimum = false;
                }

                // Reward Points
                let reward = 0;

                let reward_info = product_info.rewards.find(reward => reward.customer_group_id == config.config_customer_group_id);

                if (reward_info) {
                    reward = Number(reward_info.points);
                }

                product_data.push({
                    //key: item.key,
                    product_id: product_info.product_id,
                    name: product_info.description[config.config_language].name,
                    model: product_info.model,
                    image: product_info.image,
                    thumb: product_info.thumb,
                    option: option_data,
                    subscription: subscription_data,
                    shipping: product_info.shipping,
                    download: product_info.download,
                    quantity: Number(item.quantity),
                    minimum: Number(product_info.minimum),
                    minimum_status: minimum,
                    stock: stock,
                    stock_status: stock_status,
                    tax_class_id: Number(product_info.tax_class_id),
                    price: Number(price),
                    total: Number(price * item.quantity),
                    reward: Number(reward * item.quantity),
                    points: Number(product_info.points ? (product_info.points + option_points) * item.quantity : 0),
                    weight: Number((product_info.weight + option_weight) * item.quantity),
                    weight_class_id: Number(product_info.weight_class_id),
                    length: Number(product_info.length),
                    width: Number(product_info.width),
                    height: Number(product_info.height),
                    length_class_id: Number(product_info.length_class_id)
                });
            }
        }

        return product_data;
    }

    has(product_id) {
        return this.data.has(product_id);
    }

    clear() {
        this.items = [];
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
    getSubscriptions() {
        let product_data = [];

        for (let product of this.getProducts()) {
            if (product.subscription) {
                product_data.push(product);
            }
        }

        return product_data;
    }

    /**
     * Get Weight
     *
     * @return array<int, array<string, mixed>>
     *
     * @example
     *
     * $subscriptions = $this->cart->getSubscriptions();
     */
    getWeight() {
        let weight = 0;

        for (let product of this.getProducts()) {
            if (product.shipping) {
                weight += weight_class.convert(product.weight, product.weight_class_id, config.config_weight_class_id);
            }
        }

        return weight;
    }

    /**
     * Get SubTotal
     *
     * @return array<int, array<string, mixed>>
     *
     * @example
     *
     * $subscriptions = $this->cart->getSubscriptions();
     */
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

        let products = this.getProducts();

        console.log('products', products);

        for (let product of products) {
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
        return this.items.size > 0;
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
    hasShipping() {
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

    static getInstance() {
        if (!this.instance) {
            this.instance = new Cart();
        }

        return this.instance;
    }
}