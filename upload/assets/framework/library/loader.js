import { registry } from './registry.js';
import { factory } from './factory.js';

export class Loader {
    static instance;
    registry = null;

    constructor(registry) {
        this.registry = registry;
        this.factory = this.registry.get('factory');
    }

    get config() {
        return this.registry.get('config');
    }

    get storage() {
        return this.registry.get('storage');
    }

    get language() {
        return this.registry.get('language');
    }

    get template() {
        return this.registry.get('template');
    }

    async library(key, args = {}) {
        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        if (this.factory.has(key)) {
            let factory = this.factory.get(key).bind(this);

            this.registry.set(key, await factory(args));
        }

        return this.registry.get('language');
    }

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Loader(registry);
        }

        return this.instance;
    }
}

// Set the factory object so it can be used by the loader
registry.set('factory', factory);

let loader = Loader.getInstance(registry);

export { loader };