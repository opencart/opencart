import { factory } from './factory.js';

class Registry {
    static instance = null;
    data = {};

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
            this.instance = new Registry();
        }

        return this.instance;
    }
}

const registry = Registry.getInstance();

registry.set('factory', factory);

export { registry };