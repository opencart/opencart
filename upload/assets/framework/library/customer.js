import { loader } from './loader.js';

// library
const session = await loader.library('session');

export default class Customer {

    data = new Map();

    constructor() {
        if (session.has('customer')) {
            session.get('customer');
        } else {

        }
    }

    login(data) {

        session
    }

    isLogged() {

    }

    getId() {

    }

    getFirstName() {

    }

    getLastName() {

    }

    getGroupId() {

    }

    getEmail() {

    }

    addAddress() {

    }

    getAddressId() {

    }

    getAddress(address_id) {
        this.address.get();
    }

    getToken() {

    }

    getBalance() {


    }

    getRewardPoints() {


    }
}