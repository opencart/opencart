import { Cart } from './cart.js';
import { Config } from './config.js';
import { Currency } from './currency.js';
import { Db } from './db.js';
import { Language } from './language.js';
import { Local } from './local.js';
import { Request } from './request.js';
import { Session } from './session.js';
import { Storage } from './storage.js';
import { Tax } from './tax.js';
import { Template } from './template.js';

class Factory {
    static instance;
    data = {
        async cart() {
            return new Cart(this.registry);
        },
        config(config) {
            return new Config();
        },
        async currency() {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

            return new Currency(currencies);
        },
        db() {
            return new Db();
        },
        language() {
            return new Language();
        },
        local() {
            return new Local();
        },
        request() {
            return new Request();
        },
        session() {
            return new Session();
        },
        storage(config) {
            return new Storage();
        },
        async tax() {
            return new Tax(this.registry);
        },
        template() {
            return new Template();
        }
    };

    get(key) {
        return key in this.data ? this.data[key] : null;
    }

    set(key, value) {
        this.data[key] = value;
    }

    has(key) {
        return key in this.data;
    }

    remove(key) {
        if (key in this.data) delete this.data[key];
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Factory();
        }

        return this.instance;
    }
}

const factory = Factory.getInstance();

export { factory };