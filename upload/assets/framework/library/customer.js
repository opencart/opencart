import { loader } from './loader.js';
import { session } from './session.js';

const config = await loader.config('default');

export default class Customer {
    static #instance = null;
    #data = new Map();

    constructor() {
        if (session.has('customer')) {
            this.#data = new Map(session.get('customer'));
        }
    }

    login(data) {
        this.#data = new Map(data);

        session.set('customer', [...this.#data.entries()]);
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
        return this.#data.get('address').find(address => address.default == 1);
    }

    getAddress(address_id) {
        return this.#data.get('address').find(address => address.address_id == address_id);
    }

    getAddresses() {
        return this.#data.get('address');
    }

    getToken() {
        return this.#data.get('token');
    }

    isAffiliate() {
        return this.#data.has('affiliate') ? this.#data.get('affiliate') : false;
    }

    getBalance() {
        return this.#data.get('balance');
    }

    getRewardPoints() {
        return this.#data.get('reward');
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