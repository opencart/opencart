import { Config } from './config.js';
import { Language } from './language.js';
import { Storage } from './storage.js';
import { Template } from './template.js';

class Loader {
    constructor() {
        this.data = new Map();
        this.data.set('config', new Config());
        this.data.set('language', new Language());
        this.data.set('storage', new Storage());
        this.data.set('template', new Template());
    }

    async controller(path) {
        /*
        if (this.data.has(path)) {
            return this.data.get(path);
        }

        let config = this.data.get('config').fetch('default');

        let controller = await import(config.config_path + path + '.js');

        this.data.set('controller/' + path, new controller.default());

        let output = this.data.get(path);

        return output;
        */
    }

    async storage(path) {
        return await this.data.get('storage').fetch(path);
    }

    async language(path) {
        let output = await this.data.get('language').fetch(path);

        // Load Default Language
        let defaults = await this.data.get('language').fetch('default');

        return { ...output, ...defaults };
    }

    async template(path, data = {}) {
        return await this.data.get('template').render(path, data);
    }

    async library(path) {
        if (this.data.has(path)) {
            console.log('library', path);

            return this.data.get(path);
        }

        let object = await import('./' + path + '.js');

        this.data.set(path, new object.default());

        let output = this.data.get(path);

        return output;
    }

    async config(path) {
        return await this.data.get('config').fetch(path);
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