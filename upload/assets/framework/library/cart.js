import { loader } from './loader.js';

// library
const session = await loader.library('session');
const tax = await loader.library('tax');

// Config
//this.config = loader.config('catalog');

const data = session.get('cart');

export default class Cart {
    static instance = null;
    customer = null;
    data = [];

    constructor() {

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