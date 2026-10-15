import { load } from '../../yaml/js-yaml.js';

export default class Language {
    static #instance = null;
    #directory = '';
    #path = new Map();
    #cache = new Map();

    addPath(namespace, path = '') {
        if (!path) {
            this.#directory = namespace;
        } else {
            this.#path.set(namespace, path);
        }
    }

    async fetch(path) {
        if (this.#cache.has(path)) return this.#cache.get(path);

        let file = this.#directory + path + '.yaml';
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.#path.has(namespace + '/')) {
                file = this.#path.get(namespace + '/') + path.substr(namespace.length) + '.yaml';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let data = load(await response.text());

            this.#cache.set(path, new Map(Object.entries(data)));

            return this.#cache.get(path);
        } else {
            throw new Error(`[Language] Could not load language file ${path}!`);
        }

        return undefined;
    }

    static getInstance() {
        if (!Language.#instance) {
            Language.#instance = new Language();
        }

        return Language.#instance;
    }
}

const language = Language.getInstance();

export { language };