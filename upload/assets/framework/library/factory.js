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
        async config(args) {
            return new Config(args.path);
        },
        async storage(args) {
            return new Storage(args.path);
        },
        async language(args) {
            return new Language(args.path);
        },
        async template(args) {
            return new Template(args.path);
        },
        session(args) {
            return new Session();
        },
        local(args) {
            return new Local();
        },
        db(args) {
            return new Db();
        },
        async cart(registry) {
            return new Cart(registry);
        },
        async tax(args) {
            let tax_classes = await this.registry.get('storage').fetch('localisation/tax_class');

            return new Tax(tax_classes);
        },
        async currency(args) {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

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