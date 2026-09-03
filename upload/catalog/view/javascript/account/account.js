import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

// Library
const customer = await loader.library('customer');

export default class extends Controller {
    connect() {
        if (!customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/login';
        }
    }

    render() {
        let data = {};

        data.affiliate = customer.isAffiliate();

        return loader.template('account/account', { ...data, ...language, ...config });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }
};