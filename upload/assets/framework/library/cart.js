import { loader } from './loader.js';

// library
//let session = await loader.library('session');
//let tax = await loader.library('tax');

// Config
let config = await loader.config('default');

//let data = session.get('cart');

export default class Cart {
    constructor() {
        this.customer = null;
        this.data = new Map();
    }

    async add(cart_id, product_id, quantity = 1, option = [], subscription_plan_id = 0) {
        console.log('add');

        let item = {
            cart_id: cart_id,
            product_id: product_id,
            quantity: quantity,
            option: option,
            subscription_plan_id: subscription_plan_id
        };

        this.data.set(cart_id, item);
    }

    remove(key) {
        return this.data.delete(key);
    }

    async getProducts() {
        let product_data = [];

        for (let [cart_id, item] of this.data) {
            let stock_status = true;

            console.log(item);

            let product_info = await loader.storage('product/product-' + item.product_id);

            if (product_info !== undefined && item.quantity > 0) {
                let stock = product_info.quantity;

                let option_price = 0;
                let option_points = 0;
                let option_weight = 0;

                let option_data = [];

                for (let [key, value] of item.option) {
                    // Get option info
                    let option_info = product_info.options.find(option => option.product_option_id == key);

                    if (option_info.type == 'select' || option_info.type == 'radio') {
                        let option_value_info = option_info.find(option => option.product_option_value_id == value);

                        option_price += option_value_info.price;
                        option_points += option_value_info.points;
                        option_weight += option_value_info.weight;

                        if (option_value_info.subtract && (!option_value_info.quantity || (option_value_info.quantity < item.quantity))) {
                            stock_status = false;
                        }

                        option_data.push({
                            product_option_id: option_info.product_option_id,
                            product_option_value_id: option_value_info.product_option_value_id,
                            option_id: option_info.option_id, option_value_id: option_value_info.option_value_id,
                            name: option_info.description[config.config_language].name,
                            value: option_value_info.description[config.config_language].name,
                            type: option_info.type,
                            quantity: item.quantity,
                            subtract: option_value_info.subtract,
                            price: option_value_info.price,
                            points: option_value_info.points,
                            weight: option_value_info.weight
                        });
                    } else if (option_info.type == 'checkbox' && typeof value == 'array') {
                        for (let product_option_value_id of value) {
                            let option_value_info = option_info.find(option => option.product_option_value_id == product_option_value_id);

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
                                    quantity: item.quantity,
                                    subtract: option_value_info.subtract,
                                    price: option_value_info.price,
                                    points: option_value_info.points,
                                    weight: option_value_info.weight
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

                let price = product_info.price + option_price;

                let subscription_data = [];

                // Get option info
                let subscription_info = product_info.subscription_plans.find(subscription_plan => subscription_plan.subscription_plan_id == item.subscription_plan_id && subscription_plan.customer_group_id == config.config_customer_group_id);

                if (subscription_info) {
                    subscription_data.push({
                        subscription_plan_id: subscription_info.subscription_plan_id,
                        customer_group_id: subscription_info.customer_group_id,
                        name: subscription_info.description[config.config_language].name,
                        trial_price: subscription_info.trial_price,
                        trial_frequency: subscription_info.trial_frequency,
                        trial_duration: subscription_info.trial_duration,
                        trial_cycle: subscription_info.trial_cycle,
                        trial_status: subscription_info.trial_status,
                        cycle: subscription_info.cycle,
                        frequency: subscription_info.frequency,
                        duration: subscription_info.duration,
                        remaining: subscription_info.duration,
                        price: subscription_info.price,
                        sort_order: subscription_info.sort_order
                    });

                    // Set the new price if is subscription product
                    price = subscription_info.price;

                    if (subscription_info.trial_status) {
                        price = subscription_info.trial_price;
                    }
                }

                // Product Discounts
                let discount_info = product_info.discounts.find(discount => discount.customer_group_id == config.config_customer_group_id && discount.quantity <= product_total);

                if (discount_info) {
                    if (discount_info.type == 'F') {
                        // Fixed Price
                        price = discount_info.price + option_price;
                        // Percentage
                        price -= (price * (discount_info.price / 100));
                    } else if (discount_info.type == 'S') {
                        // Subtract
                        price -= discount_info.price;
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
                    reward = reward_info.points;
                }

                product_data.push({
                    cart_id: item.cart_id,
                    name: product_info.description[config.config_language].name,
                    model: product_info.model,
                    image: product_info.thumb,
                    option: option_data,
                    subscription: subscription_data,
                    shipping: product_info.shipping,
                    download: product_info.download,
                    quantity: item.quantity,
                    minimum: product_info.minimum,
                    minimum_status: minimum,
                    stock: stock,
                    stock_status: stock_status,
                    tax_class_id: product_info.tax_class_id,
                    price: price,
                    total: price * item.quantity,
                    reward: reward * item.quantity,
                    points: product_info.points ? (product_info.points + option_points) * item.quantity : 0,
                    weight: (product_info.weight + option_weight) * item.quantity,
                    weight_class_id: product_info.weight_class_id,
                    length: product_info.length,
                    width: product_info.width,
                    height: product_info.height,
                    length_class_id: product_info.length_class_id
                });
            }
        }

        return product_data;
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
    getSubscriptions() {
        let product_data = [];

        for (let value in this.getProducts()) {
            if (value['subscription']) {
                product_data.push(value);
            }
        }

        return $product_data;
    }


    getTotal() {

    }
}