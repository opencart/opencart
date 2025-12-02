import { Request } from './request.js';
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
        api(option) {
            return new Request(option);
        },
        async cart() {
            return new Cart(this.registry);
        },
        config(option) {
            return new Config(option.path);
        },
        async currency() {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

            return new Currency(currencies);
        },
        db() {
            return new Db();
        },
        language(option) {
            return new Language(option.path);
        },
        local() {
            return new Local();
        },
        session() {
            return new Session();
        },
        storage(option) {
            return new Storage(option.path);
        },
        async tax() {
            let tax_classes = await this.registry.get('storage').fetch('localisation/tax_class');

            return new Tax(tax_classes);
        },
        template(option) {
            return new Template(option.path);
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