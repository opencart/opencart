import { loader } from './loader.js';

// library
const session = await loader.library('session');

// Config
const config = await loader.config('catalog');

class Customer {
    static instance = null;
    data = new Map(session.get('customer'));
    logged = false;

    constructor() {
        if (session.has('customer')) {
            this.data = new Map(session.get('customer'));

            this.logged = true;
        }
    }

    isLogged() {
        return this.logged;
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
        //if () {

       // }

        return this.data.get('customer_group_id');
    }

    getEmail() {
        return this.data.get('email');
    }

    getTelephone() {
        return this.data.get('telephone');
    }

    getAddressId() {
        return this.data.get('address_id');
    }

    getAddressId(address_id) {
        this.data.get('address');

        return;
    }

    getAddresses() {
        return this.data.get('address');
    }

    getWishlist() {
        //this.data.get('wishlist')
        return [];
    }

    getNewsletter() {
        return this.data.get('wishlist');
    }

    getBalance() {
        return this.data.get('balance');
    }

    getRewardPoints() {
        return this.data.get('reward_point');
    }

    isSafe() {
        return this.data.get('reward_point');
    }

    isCommenter() {
        return this.data.get('reward_point');
    }
}

export default Customer;