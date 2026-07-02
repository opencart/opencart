export class Storage {
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
        //console.log('Storage');
        ///console.log(path);

        if (this.cache.has(path)) {
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

        //console.log('status', response.status);

        if (response.status == 200) {
            let data = await response.json();

            //console.log(data);

            this.cache.set(path, data);

            return this.cache.get(path);
        } else {
            console.log('Could not load storage file ' + path);
        }

        return undefined;
    }
}