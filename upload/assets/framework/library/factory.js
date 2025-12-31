import { registry } from './registry.js';
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
    data = new Map(Object.entries({
        cart: async () => {
            return new Cart(this.registry);
        },
        config: (config) => {
            return new Config();
        },
        currency: async () => {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

            return new Currency(currencies);
        },
        db: () => {
            return new Db();
        },
        language: () => {
            return new Language();
        },
        local: () => {
            return new Local();
        },
        request: () => {
            return new Request();
        },
        session: () => {
            return new Session();
        },
        storage: (config) => {
            return new Storage();
        },
        tax: async () => {
            return new Tax(this.registry);
        },
        template: () => {
            return new Template();
        }
    }));

    get(key) {
        return this.data.has(key) ? this.data.get(key) : null;
    }

    set(key, value) {
        this.data.set(key, value);
    }

    has(key) {
        return this.data.has(key);
    }

    remove(key) {
        if (this.data.has(key)) this.data.delete(key);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Factory();
        }

        return this.instance;
    }
}

const factory = Factory.getInstance();

// Set the factory object so it can be used by the loader
registry.set('factory', factory);

export { factory };