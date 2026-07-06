export class Config {
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
        }5
    }

    async fetch(path) {
        if (this.cache.has(path)) {
            console.log('cache', path);

            return this.cache.get(path);
        }

        let file = this.directory + path + '.json';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(path, namespace.length) + '.json';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let object = await response.json();

            this.cache.set(path, object);

            return this.cache.get(path);
        } else {
            console.log('Could not load config file ' + path);
        }

        return undefined;
    }
}