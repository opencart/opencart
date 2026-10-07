export default class Storage {
    static instance = null;

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
        if (this.cache.has(path)) return this.cache.get(path);

        let file = this.directory + path + '.json';
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace + '/')) {
                file = this.path.get(namespace + '/') + path.substr(namespace.length) + '.json';
            }
        }

        let response = await fetch(file);

        if (response.status !== 200) {
            throw new Error('Could not load storage file ' + path);

            return undefined;
        }

        let data = await response.json();

        if (!Array.isArray(data)) {
            this.cache.set(path, new Map(Object.entries(data)));
        } else {
            this.cache.set(path, data);
        }

        return this.cache.get(path);
    }

    static getInstance() {
        if (!Storage.instance) {
            Storage.instance = new Storage();
        }

        return Storage.instance;
    }
}

const storage = Storage.getInstance();

export { storage };