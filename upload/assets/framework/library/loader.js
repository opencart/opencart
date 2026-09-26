import { config } from './config.js';
import { language } from './language.js';
import { storage } from './storage.js';
import { template } from './template.js';

export default class Loader {
    static instance;

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

    async template(path, data = []) {
        if (!data instanceof Map && !data instanceof Array) return;

        let values = {};

        if (data instanceof Map) {
            Object.assign(values, Object.fromEntries(data));
        }

        if (data instanceof Array) {
            let entries = data.filter(value => value instanceof Map);

            for (let entry of entries) Object.assign(values, Object.fromEntries(entry));
        }

        return await template.render(path, values);
    }

    static getInstance() {
        if (!Loader.instance) {
            Loader.instance = new Loader();
        }

        return Loader.instance;
    }
}

const loader = Loader.getInstance();

export { loader };