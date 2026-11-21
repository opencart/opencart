import { loader } from './loader.js';
import { session } from './session.js';

const config = await loader.config('default');

// Customer data
const data = session.has('customer') ? await session.get('customer') : {};

export default class Customer {
    static #instance = null;
    #data = new Map();
	
    constructor() {
        this.#data = new Map(Object.entries(data));
    }

    login(data) {
        this.#data = new Map(...this.#data.entries());

        session.set('customer', this.#data);
    }

    logout() {
        this.#data.clear();
    }

    isLogged() {
        return this.#data.size > 0;
    }

    getId() {
        return this.#data.get('customer_id');
    }

    getFirstName() {
        return this.#data.get('firstname');
    }

    getLastName() {
        return this.#data.get('lastname');
    }

    getGroupId() {
        return this.isLogged() && this.#data.has('customer_group_id') ? this.#data.get('customer_group_id') : config.get('config_customer_group_id');
    }

    getEmail() {
        return this.#data.get('email');
    }

    getTelephone() {
        return this.#data.get('telephone');
    }

    getNewsletter() {
        return this.#data.get('newsletter');
    }

	getAddressId() {
		if (!this.#data.has('address')) return 0;
		
        return this.#data.get('address').find(address => address.default == 1);
    }

	getAddress(address_id) {
		if (!this.#data.has('address')) return [];

        return this.#data.get('address').find(address => address.address_id == address_id);
    }

    getAddresses() {
        return this.#data.get('address');
    }

	getWishlist() {
        return this.#data.get('wishlist');
    }

    getToken() {
        return this.#data.get('token');
    }

    static getInstance() {
        if (!Customer.#instance) {
            Customer.#instance = new Customer();
        }

        return Customer.#instance;
    }
}

const customer = new Customer();

export { customer };