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

    clear() {
        this.data = [];
    }

    async load(path) {
        let key = path.replaceAll('/', '.');

        if (key in this.loaded) {
            this.data = this.data.concat(this.loaded[key]);

            return;
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
            this.loaded[key] = await response.json();

            this.data = this.data.concat(this.loaded[key]);
        } else {
            console.log('Could not load language file ' + path);
        }
    }
}