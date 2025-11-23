import { registry } from './registry.js';

export default class Language {
    static instance = null;
    path = '';
    data = [];

    constructor(path) {
        this.path = path;
    }

    async fetch(filename) {
        let key = filename.replaceAll('/', '.');

        if (this.data[key] !== undefined) {
            return this.data[key];
        }

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();

            this.data[key] = json;

            return json;
        } else {
            console.log('Could not load file ' + filename + '.json');

            return [];
        }
    }

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Language(registry.config.get('language_path'));
        }

        return this.instance;
    }
}

registry.language = Language.getInstance(registry);