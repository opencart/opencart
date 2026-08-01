import { loader } from './loader.js';

// library
//let session = await loader.library('session');
//let tax = await loader.library('tax');

// Config
let config = await loader.config('catalog');

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


        for (let item of this.data) {
            let stock_status = true;

            let product_info = await import('product/product-' + item.product_id);



/*
                {
                    "product_option_id": "226",
                    "description": {
                    "en-gb": {
                        "name": "Select"
                    }
                },
                "type": "select",
                "value": null,
                "option_value": [
                    {
                        "product_option_value_id": "15",
                        "description": {
                            "en-gb": {
                                "name": "Red"
                            }
                        },
                        "image": "",
                        "quantity": "2",
                        "price": "0.0000",
                        "points": "0",
                        "weight": "0.00000000",
                        "sort_order": "1"
                    },
                    {
                    "product_option_value_id": "16",
                    "description": {
                        "en-gb": {
                            "name": "Blue"
                        }
                    },
                    "image": "",
                    "quantity": "5",
                    "price": "0.0000",
                    "points": "0",
                    "weight": "0.00000000",
                    "sort_order": "2"
                }
*/

            if (product_info !== undefined && item.quantity > 0) {

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
                            option_id: option_info.option_id,
                            option_value_id: option_value_info.option_value_id,
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


                /*
                             $subscription_data = [];

                             $subscription_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_subscription` `ps` LEFT JOIN `" . DB_PREFIX . "subscription_plan` `sp` ON (`ps`.`subscription_plan_id` = `sp`.`subscription_plan_id`) LEFT JOIN `" . DB_PREFIX . "subscription_plan_description` `spd` ON (`sp`.`subscription_plan_id` = `spd`.`subscription_plan_id`) WHERE `ps`.`product_id` = '" . (int)$cart['product_id'] . "' AND `ps`.`subscription_plan_id` = '" . (int)$cart['subscription_plan_id'] . "' AND `ps`.`customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `spd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND `sp`.`status` = '1'");

                             if ($subscription_query->num_rows) {
                                 $subscription_data = ['remaining' => $subscription_query->row['duration']] + $subscription_query->row;

                                 // Set the new price if is subscription product
                                 $price = $subscription_query->row['price'];

                                 if ($subscription_query->row['trial_status']) {
                                     $price = $subscription_query->row['trial_price'];
                                 }
                             }

                             // Product Discounts
                             $product_discount_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_discount` WHERE `product_id` = '" . (int)$cart['product_id'] . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "' AND `quantity` <= '" . (int)$product_total . "' AND ((`date_start` = '0000-00-00' OR `date_start` < NOW()) AND (`date_end` = '0000-00-00' OR `date_end` > NOW())) ORDER BY `quantity` DESC, `priority` ASC, `price` ASC LIMIT 1");

                             if ($product_discount_query->num_rows) {
                                 if ($product_discount_query->row['type'] == 'F') {
                                     // Fixed Price
                                     $price = $product_discount_query->row['price'] + $option_price;
                                 } elseif ($product_discount_query->row['type'] == 'P') {
                                     // Percentage
                                     $price -= ($price * ($product_discount_query->row['price'] / 100));
                                 } elseif ($product_discount_query->row['type'] == 'S') {
                                     // Subtract
                                     $price -= $product_discount_query->row['price'];
                                 }
                             }

                             // Stock
                             if (!$product_query->row['quantity'] || ($product_query->row['quantity'] < $product_total)) {
                                 $stock_status = false;
                             }

                             // Minimum Quantity
                             if ($product_query->row['minimum'] > $product_total) {
                                 minimum = false;
                             } else {
                                 minimum = true;
                             }

                             // Reward Points
                             $product_reward_query = $this->db->query("SELECT `points` FROM `" . DB_PREFIX . "product_reward` WHERE `product_id` = '" . (int)$cart['product_id'] . "' AND `customer_group_id` = '" . (int)$this->config->get('config_customer_group_id') . "'");

                             if ($product_reward_query->num_rows) {
                                 $reward = $product_reward_query->row['points'];
                             } else {
                                 $reward = 0;
                             }

                             // Downloads
                             $download_data = [];

                             $download_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product_to_download` `p2d` LEFT JOIN `" . DB_PREFIX . "download` `d` ON (`p2d`.`download_id` = `d`.`download_id`) LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `p2d`.`product_id` = '" . (int)$cart['product_id'] . "' AND `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

                             foreach ($download_query->rows as $download) {
                                 $download_data[] = $download;
                             }
                          */
            }
        }
    }

    getTotal() {

    }
}