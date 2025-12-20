export class Storage {
    directory = '';
    path = [];
    loaded = [];

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path[namespace] = path;
        }
    }

    async fetch(path) {
        let key = path.replaceAll('/', '.');

        if (key in this.loaded) {
            return this.loaded[key];
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

            if (this.path[namespace] !== undefined) {
                file = this.path[namespace] + path.substr(path, namespace.length) + '.json';
            }
        }

        const response = await fetch(file);

        if (response.status == 200) {
            this.loaded[key] = await response.json();

            return this.loaded[key];
        } else {
            console.log('Could not load storage file ' + path);

            return [];
        }
    }
}