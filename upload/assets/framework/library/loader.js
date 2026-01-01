import { event } from './event.js';

class Loader {
    static instance;
    data = new Map();

    constructor(registry) {
        this.event = event;
    }

    async config(path) {
        this.event.trigger('config/' + path + '/before', { path });

        let output = await this.data.get('config').fetch(path);

        this.event.trigger('config/' + path + '/after', { path, output });

        return output;
    }

    async storage(path) {
        this.event.trigger('storage/' + path + '/before', { path });

        let output = await this.data.get('storage').fetch(path);

        this.event.trigger('storage/' + path + '/after', { path, output });

        return output;
    }

    async language(path) {
        this.event.trigger('language/' + path + '/before', { path });

        let output = await this.data.get('language').fetch(path);

        this.event.trigger('language/' + path + '/after', { path, output });

        return output;
    }

    async template(path, data = {}) {
        this.event.trigger('template/' + path + '/before', { path, data });

        let output = await this.data.get('template').render(path, data);

        this.event.trigger('template/' + path + '/after', { path, data, output });

        return output;
    }

    async library(path) {
        this.event.trigger('library/' + path + '/before', { path });

        if (this.data.has(path)) {
            return this.data.get(path);
        }

        let object = await import('./' + path + '.js');

        this.data.set(path, object.default);

        let output = this.data.get(path);

        this.event.trigger('library/' + path + '/after', { path, output });

        return output;
    }

    static getInstance(event) {
        if (!this.instance) {
            this.instance = new Loader(event);
        }

        return this.instance;
    }
}

const loader = Loader.getInstance(event);

export { loader };