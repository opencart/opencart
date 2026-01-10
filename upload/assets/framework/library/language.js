class Language {
    static instance;
    directory = '';
    path = new Map();
    loaded = new Map();

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    async fetch(path) {
        if (this.loaded.has(path)) {
            return this.loaded.get(path);
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

            this.loaded.set(path, new Map(Object.entries(object)));

            return this.loaded.get(path);
        } else {
            console.log('Could not load language file ' + path);
        }

        return new Map();
    }
}

export default Language;