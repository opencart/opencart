import { config } from './config.js';
import { event } from './event.js';
import { language } from './language.js';
import { storage } from './storage.js';
import { template } from './template.js';

class Loader {
    static instance;

    constructor() {
        this.data = new Map();
        this.data.set('config', config);
        this.data.set('event', event);
        this.data.set('language', language);
        this.data.set('storage', storage);
        this.data.set('template', template);
    }

    async config(path) {
        event.trigger('config/' + path + '/before', { path });

        let output = await config.fetch(path);

        event.trigger('config/' + path + '/after', { path, output });

        return output;
    }

    async storage(path) {
        event.trigger('storage/' + path + '/before', { path });

        let output = await storage.fetch(path);

        event.trigger('storage/' + path + '/after', { path, output });

        return output;
    }

    async language(path) {
        event.trigger('language/' + path + '/before', { path });

        let output = await language.fetch(path);

        event.trigger('language/' + path + '/after', { path, output });

        return output;
    }

    async template(path, data = {}) {
        event.trigger('template/' + path + '/before', { path, data });

        let output = await template.render(path, data);

        event.trigger('template/' + path + '/after', { path, data, output });

        return output;
    }

    async library(path) {
        event.trigger('library/' + path + '/before', { path });

        if (this.data.has(path)) {
            return this.data.get(path);
        }

        let object = await import('./' + path + '.js');

        this.data.set(path, new object.default());

        let output = this.data.get(path);

        event.trigger('library/' + path + '/after', { path, output });

        return output;
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