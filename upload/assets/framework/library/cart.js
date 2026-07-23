import { loader } from './loader.js';

// library
//const session = await loader.library('session');
//const tax = await loader.library('tax');

// Config
//this.config = loader.config('catalog');

//const data = session.get('cart');

export default class Cart {
    constructor() {
        this.customer = null;
        this.data = new Map();
    }

    add(product_id, quantity = 1, option, $subscription_plan_id) {

        console.log('add');
        console.log(product_id);
        console.log(quantity);
        console.log(option);
        console.log(subscription_plan_id);

        //let key  = product_id + '.' + option.flat(Infinity) + '.';

           //console.log(key);


        //this.data.set(key, {

        //});
    }

    remove(key) {
        return this.data.delete(key);
    }

    getProducts() {
        this.data.get(key, item);

    }

    getTotal() {

    }
}