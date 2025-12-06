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

    async controller(path, ...args) {
        let pos = path.indexOf('.');

        if (pos == -1) {
            let key = 'controller_' + path.replaceAll('/', '_');
            let method = 'index';
        } else {
            let key = 'controller_' + path.substring(0, pos).replaceAll('/', '_');
            let method = path.substring(pos);
        }

        if (!this.registry.has(key)) {
            let factory = await this.factory.get('controller').bind({ registry: this.registry });

            this.registry.set(key, factory(pos !== -1 ? path : path.substring(0, pos)));
        }

        return this.registry.get(key).method.apply(args);
    }

    async model(path) {
        let key = 'model_' + path.replaceAll('/', '_');

        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        let factory = this.factory.get('model').bind({ registry: this.registry });

        this.registry.set(key, await factory(path));

        return this.registry.get(key);
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