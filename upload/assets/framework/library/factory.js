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
import { Url } from './url.js';

class Factory {
    static instance;
    data = {
        api(config) {
            return new Request(config);
        },
        async cart() {
            return new Cart(this.registry);
        },
        config(config) {
            return new Config(config.path);
        },
        async controller(path) {
            return await import(path);
        },
        async currency() {
            let currencies = await this.registry.get('storage').fetch('localisation/currency');

            return new Currency(currencies);
        },
        db() {
            return new Db();
        },
        language(config) {
            return new Language(config.path);
        },
        local() {
            return new Local();
        },
        async model(config) {
            return await import(path);
        },
        session() {
            return new Session();
        },
        storage(config) {
            return new Storage(config.path);
        },
        async tax() {
            return new Tax(this.registry);
        },
        template(config) {
            return new Template(config.path);
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