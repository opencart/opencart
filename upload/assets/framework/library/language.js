export class Language {
    directory = '';
    path = [];
    loaded = [];
    data = [];

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path[namespace] = path;
        }
    }

    get(key) {
        return key in this.data ? this.data[key] : null;
    }

    set(key, value) {
        this.data[key] = value;
    }

    has(key) {
        return key in this.data;
    }

    remove(key) {
        if (key in this.data) delete this.data[key];
    }

    all() {
        return this.data;
    }

    async load(path) {
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
            let json = await response.json();

            console.log(json);

            this.data[path] = json;

            return this.data[path];
        } else {
            console.log('Could not load language file ' + path);
        }
    }

    /*
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
            let json = await response.json();

            console.log(Array.from(json));

            this.data[path] = json;

            return this.data[path];
        } else {
            console.log('Could not load language file ' + path);
        }
    }
    */
}