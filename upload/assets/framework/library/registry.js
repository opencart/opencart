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

export class Registry {
    factory = {
        config: () => {
            return new Config();
        },
        storage: (registry) => {
            return new Storage(this.config.get('storage_path'));
        },
        language: (registry) => {
            return new Language(this.config.get('language_path'));
        },
        template: (registry) => {
            return new Template(this.config.get('template_path'));
        },
        url: () => {
            return new Url();
        },
        session: async () => {
            return new Session();
        },
        local: () => {
            return new Local();
        },
        db: () => {
            return new Db();
        },
        cart: async () => {
            return new Cart(this);
        },
        tax: async () => {
            let tax_classes = await this.storage.fetch('localisation/tax_class');

            return new Tax(tax_classes);
        },
        currency: async () => {
            let currencies = await this.storage.fetch('localisation/currency');

            return new Currency(currencies);
        }
    };
    data = [];

    async config() {
        if (this.data.config == undefined) {
            this.data.config = await this.factory.config();
        }

        return this.data.config;
    }

    async storage() {
        if (this.data.storage == undefined) {
            this.storage = await this.factory.storage(this);
        }

        return this.data.storage;
    }

    async language() {
        if (this.data.language == undefined) {
            this.data.language = await this.factory.language(this);
        }

        return this.data.language;
    }

    async template() {
        if (this.data.template == undefined) {
            this.data.template = await this.factory.template(this);
        }

        return this.data.template;
    }

    url() {
        if (this.data.url == undefined) {
            this.data.url = this.factory.url();
        }

        return this.data.url;
    }

    session() {
        if (this.data.session == undefined) {
            this.data.session = this.factory.session();
        }

        return this.data.session;
    }

    local() {
        if (this.data.local == undefined) {
            this.data.local = this.factory.local();
        }

        return this.data.local;
    }

    db() {
        if (this.data.db == undefined) {
            this.data.db = this.factory.db();
        }

        return this.data.db;
    }

    cart() {
        if (this.data.cart == undefined) {
            this.data.cart = this.factory.cart(this);
        }

        return this.data.cart;
    }

    async tax() {
        if (this.data.tax == undefined) {
            this.data.tax = await this.factory.tax(this);
        }

        return this.data.tax;
    }

    async currency() {
        if (this.data.currency == undefined) {
            this.data.currency = await this.factory.currency(this);
        }

        return this.data.currency;
    }
}