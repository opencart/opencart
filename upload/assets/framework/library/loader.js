import { registry } from './registry.js';

export class Loader {
    static instance = null;
    registry = null;
    data = [];

    constructor(registry) {
        this.registry = registry;
    }

    async storage(path) {
        return await this.registry.storage.fetch(path);
    }

    async language(path) {
        return await this.registry.language.fetch(path);
    }

    async template(path, data) {
        return await this.registry.template.render(path, data);
    }

    async library(key) {
        if (this.registry[key] !== undefined) {
            return;
        }

        let response = await import('./' + key + '.js');

        if (response.default.getInstance !== undefined) {
            this.registry[key] = await response.default.getInstance(this.registry);
        } else {
            console.log('Error: Library ' + key + ' does not exist!');
        }
    }

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Loader(registry);
        }

        return this.instance;
    }
}

const loader = Loader.getInstance(registry);

export { loader, registry };