import { config, language, storage, template } from '../index.js';

class Loader {
    #instance;
    #data;

    constructor() {
        this.#data = new Map();
    }

    async config(path) {
        return await config.fetch(path);
    }

    async language(path) {
        let output = await language.fetch(path);

        // Load Default Language
        let defaults = await language.fetch('default');

        return { ...output, ...defaults };
    }

    async library(path) {
        if (this.#data.has(path)) return this.#data.get(path);

        let object = await import('../library/' + path + '.js');

        this.#data.set(path, object.default.getInstance());

        return this.#data.get(path);
    }

    async storage(path) {
        return await storage.fetch(path);
    }

    async template(path, data = {}) {
        return await template.render(path, data);
    }

    static getInstance() {
        if (!this.#instance) {
            this.#instance = new Loader();
        }

        return this.#instance;
    }
}

const loader = Loader.getInstance();

export { loader };