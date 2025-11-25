import { factory } from './factory.js';

export class Registry {
    static instance = null;
    factory = {};
    data = {};

    constructor(factory) {
        this.factory = factory;
    }

    async get(key) {
        if (this.data[key] == undefined && this.factory !== undefined) {
            this.data[key] = await this.factory[key](this);
        }

        return this.data[key];
    }

    set(key, value) {
        this.data[key] = value;
    }

    remove(key) {
        delete this.data[key];
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Registry(factory);
        }

        return this.instance;
    }
}

const registry = Registry.getInstance(factory);

export { registry };