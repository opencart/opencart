import { loader } from './loader.js';

// library
const session = await loader.library('session');

export default class Customer {
    data = new Map();

    constructor() {
        if (session.has('customer')) {
            let customer = session.get('customer');

            data.set
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
        return data.get('customer_id');
    }

    getFirstName() {
        return data.get('firstname');
    }

    getLastName() {
        return data.get('lastname');
    }

    getGroupId() {
        return data.get('customer_group_id');
    }

    getEmail() {
        return data.get('email');
    }

    addAddress() {

    }

    getAddressId() {

    }

    getAddress(address_id) {
        this.address.get();
    }

    getToken() {
        return data.get('token');
    }

    getBalance() {


    }

    getRewardPoints() {


    }
}