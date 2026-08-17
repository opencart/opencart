import { load } from '../../yaml/js-yaml.js';

export class Language {
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
                file = this.path.get(namespace) + path.substr(path, namespace.length) + '.yaml';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let data = load(await response.text());

            this.cache.set(path, data);

            return this.cache.get(path);
        } else {
            console.log('Could not load language file ' + path);
        }

        return undefined;
    }
}