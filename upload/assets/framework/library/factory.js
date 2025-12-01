import { Api } from './api.js';
import { Cart } from './cart.js';
import { Config } from './config.js';
import { Currency } from './currency.js';
import { Db } from './db.js';
import { Language } from './language.js';
import { Local } from './local.js';
import { Session } from './session.js';
import { Storage } from './storage.js';
import { Tax } from './tax.js';
import { Template } from './template.js';
import { Url } from './url.js';

class Factory {
    static instance;
    data = {
        api() {
            return new Api();
        },
        async cart(registry) {
            return new Cart(registry);
        },
        config(args) {
            return new Config(args.path);
        },
        async currency(args) {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

            return new Currency(currencies);
        },
        db() {
            return new Db();
        },
        language(args) {
            return new Language(args.path);
        },
        local() {
            return new Local();
        },
        session() {
            return new Session();
        },
        storage(args) {
            return new Storage(args.path);
        },
        async tax(args) {
            let tax_classes = await this.registry.get('storage').fetch('localisation/tax_class');

            return new Tax(tax_classes);
        },
        template(args) {
            return new Template(args.path);
        },
        url() {
            return new Url();
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