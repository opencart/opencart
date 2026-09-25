import { config, language, storage, template } from '../index.js';

export default class Loader {
    instance;

    constructor() {
        this.data = new Map();
    }

    async config(path) {
        return await config.fetch(path);
    }

    async language(path) {
        let output = await language.fetch(path);

        // Load Default Language
        let defaults = await language.fetch('default');

        return new Map([ ...output, ...defaults ]);
    }

    async library(path) {
        if (this.data.has(path)) return this.data.get(path);

        let object = await import('../library/' + path + '.js');

        this.data.set(path, object.default.getInstance());

        return this.data.get(path);
    }

    async storage(path) {
        return await storage.fetch(path);
    }

    async template(path, data = {}) {
        let values = {};

        console.log(values);

        if (Array.isArray(data)) {
            console.log('IS ARRAY');

            for (let value of data) {
                console.log(typeof value);

                if (value instanceof Map) {
                    values = { ...values, ...value };
                } else if (typeof value === 'object') {
                    values = Object.assign(values, value);
                }
            }
        }

        console.log(values);

        if (data instanceof Map) {
            console.log('IS MAP');
            values = { ...data };
        }

        return await template.render(path, values);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Loader();
        }

        return this.instance;
    }
}

const loader = Loader.getInstance();

export { loader };