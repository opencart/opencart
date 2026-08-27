import { loader } from './loader.js';

// library
const session = await loader.library('session');

export default class Customer {
    data = new Map();

    constructor() {
        if (session.has('customer')) {
            let customer = session.get('customer');

            this.data = new Map(customer);
        }
    }

    login(data) {
        session.set('customer', data);
    }

    logout() {
        this.data.clear();
    }

    isLogged() {
        return this.data.length > 0;
    }

    getId() {
        return this.data.get('customer_id');
    }

    getFirstName() {
        return this.data.get('firstname');
    }

    getLastName() {
        return this.data.get('lastname');
    }

    getGroupId() {
        return this.data.get('customer_group_id');
    }

    getEmail() {
        return this.data.get('email');
    }

    getAddressId() {
        return this.data.get('address').find(address => address.default == 1);
    }

    getAddress(address_id) {
        return this.data.get('address').find(address => address.address == address_id);
    }

    getToken() {
        return this.data.get('token');
    }

    getBalance() {
        return this.data.get('balance');
    }

    getRewardPoints() {
        return this.data.get('reward');
    }
}