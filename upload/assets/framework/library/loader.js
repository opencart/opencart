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

    async controller(path) {
        return await this.factory.get('controller').bind({ registry: this.registry });
    }

    async model(path) {
        return await this.factory.get('model').bind({ registry: this.registry });
    }

    config(path) {
        this.registry.get('config').load(path);

        return this.registry.get('config').all();
    }

    storage(path) {
        return this.registry.get('storage').fetch(path);
    }

    language(path) {
        return this.registry.get('language').fetch(path);
    }

    template() {
        return this.registry.get('template').fetch(path, data);
    }

    async library(key, config = {}) {
        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        if (this.factory.has(key)) {
            let factory = this.factory.get(key).bind({ registry: this.registry });

            this.registry.set(key, await factory(config));
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

let loader = Loader.getInstance(registry);

export { loader };