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
        config: async (args) => {
            //console.log(args);

            return new Config(args.path);
        },
        storage: (args) => {
            //console.log(args);
            return new Storage(args.path);
        },
        language: (args) => {
            //console.log(args);
            return new Language(args.path);
        },
        template: (args) => {
            //console.log(args);
            return new Template(args.path);
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
        tax: async (args) => {
            let tax_classes = await args.registry.storage.fetch('localisation/tax_class');

            return new Tax(tax_classes);
        },
        currency: async (args) => {
            let currencies = await args.registry.storage.fetch('localisation/currency');

            return new Currency(currencies);
        }
    };

    get(key, args) {
        return key in this.data ? this.data[key](args) : null;
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
