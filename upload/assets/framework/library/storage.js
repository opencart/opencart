export default class Storage {
    directory = '';
    path = new Map();
    data = new Map();

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    async fetch(path) {
        if (this.data.has(path)) {
            return this.data.get(path);
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
            let data = await response.json();

            this.data.set(path, data);

            return this.data.get(path);
        } else {
            console.log('Could not load storage file ' + path);
        }

        return {};
    }
}