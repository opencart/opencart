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
        return this.registry.get('config').fetch(path);
    }

    storage(path) {
        return this.registry.get('storage').fetch(path);
    }

    language(path) {
        return this.registry.get('language').load(path);
    }

    template(path, data = {}) {
        return this.registry.get('template').render(path, data);
    }

    library(key, config = {}) {
        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        if (this.factory.has(key)) {
            this.registry.set(key, this.factory.get(key).bind({ registry: this.registry }).apply(config));
        }

        return this.registry.get(key);
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

// Add loader to the registry
registry.set('loader', loader);

export { loader };