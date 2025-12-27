export class Storage {
    directory = '';
    path = [];
    data = [];

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path[namespace] = path;
        }
    }

    async fetch(path) {
        if (path in this.data) {
            return this.data[path];
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

        let response = await fetch(file);

        if (response.status == 200) {
            this.data[path] = await response.json();

            return this.data[path];
        } else {
            console.log('Could not load storage file ' + path);
        }

        return {};
    }
}