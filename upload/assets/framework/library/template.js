import '../liquid.browser.min.js';

export default class Template {
    engine = {};
    directory = '';
    path = new Map();
    loaded = new Map();

    constructor() {
        this.engine = new liquidjs.Liquid({
            root: '',
            extname: '.twig'
        });
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    async fetch(path) {
        let file = this.directory + path + '.twig';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(path, namespace.length) + '.twig';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let object = await response.text();

            this.loaded.set(path, object);

            return this.loaded.get(path);
        } else {
            console.log('Could not load template file ' + path);
        }

        return '';
    }

    parse(code, data = {}) {
        return this.engine.parseAndRender(code, data);
    }

    async render(path, data = {}) {
        console.log(path);
        console.log(data);

        return this.parse(await this.fetch(path), data);
    }
}