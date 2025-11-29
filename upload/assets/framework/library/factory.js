import { Config } from './config.js';
import { Storage } from './storage.js';
import { Language } from './language.js';
import { Template } from './template.js';
import { Url } from './url.js';
import { Session } from './session.js';
import { Local } from './local.js';
import { Db } from './db.js';
import { Cart } from './cart.js';
import { Tax } from './tax.js';
import { Currency } from './currency.js';

class Factory {
    static instance;
    data = {
        config: async (path) => {
            return new Config(path);
        },
        storage: (path) => {
            return new Storage(path);
        },
        language: (path) => {
            return new Language(path);
        },
        template: (path) => {
            return new Template(path);
        },
        session: () => {
            return new Session();
        },
        local: () => {
            return new Local();
        },
        db: () => {
            return new Db();
        },
        cart: async (registry) => {
            return new Cart(registry);
        },
        tax: (tax_classes) => {
            return new Tax(tax_classes);
        },
        currency: (currencies) => {
            return new Currency(currencies);
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
