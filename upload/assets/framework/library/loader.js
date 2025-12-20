import { registry } from './registry.js';
import { factory } from './factory.js';

export class Loader {
    static instance;
    registry = null;
    factory = null;

    constructor(registry) {
        this.registry = registry;
        this.factory = this.registry.get('factory');
    }

    config(path) {
        this.registry.get('config').load(path);
    }

    storage(path) {
        return this.registry.get('storage').fetch(path);
    }

    language(path) {
        this.registry.get('language').load(path);
    }

    template(path, data = []) {
        return this.registry.get('template').render(path, data);
    }

    async library(key, config = {}) {
        if (this.registry.has(key)) {
            return;
        }

        if (this.factory.has(key)) {
            this.registry.set(key, await this.factory.get(key).bind({ registry: this.registry }).apply(config));
        }
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

const loader = Loader.getInstance(registry);

registry.set('loader', loader);

export { loader };