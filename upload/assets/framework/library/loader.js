import { registry } from './registry.js';
import { factory } from './factory.js';

export class Loader {
    static instance;
    registry = null;

    constructor(registry) {
        this.registry = registry;
        this.factory = this.registry.get('factory');
    }

    async config(path) {
        return await this.registry.get('config').fetch(path);
    }

    async storage(path) {
        return await this.registry.get('storage').fetch(path);
    }

    async language(path) {
        return await this.registry.get('language').fetch(path);
    }

    async template(path, data) {
        return await this.registry.get('template').render(path, data);
    }

    async library(key, args = {}) {
        if (this.registry.has(key)) {
            return;
        }

        if (this.registry.get('factory').has(key)) {
            let object = { registry: this.registry, ...args };

            console.log(key);
            console.log(object);

            this.registry.set(key, await this.registry.get('factory').get(key, object));
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

let loader = Loader.getInstance(registry);

export { loader };