import { registry } from './registry.js';

export class Loader {
    static instance;
    registry = null;

    constructor(registry) {
        this.registry = registry;
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

    async library(key, callback) {
        if (this.registry.has(key)) {
            return this.registry.get(key);
        }

        if (this.registry.get('factory').has(key)) {
            this.registry.data[key] = await this.registry.get('factory').get(key)(this.registry);
        }
    }

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Loader(registry);
        }

        return this.instance;
    }
}

let loader = Loader.getInstance(registry);

export { loader };