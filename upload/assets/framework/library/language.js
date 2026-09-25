import { load } from '../../yaml/js-yaml.js';

export default class Language {
    static instance;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    async fetch(path) {
        if (this.cache.has(path)) {
            return this.cache.get(path);
        }

        let file = this.directory + path + '.yaml';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(namespace.length) + '.yaml';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let data = load(await response.text());

            let map = new Map(Object.entries(data));

            this.cache.set(path, map);

            return this.cache.get(path);
        } else {
            console.log('Could not load language file ' + path);
        }

        return undefined;
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Language();
        }

        return this.instance;
    }
}

const language = Language.getInstance();

export { language };