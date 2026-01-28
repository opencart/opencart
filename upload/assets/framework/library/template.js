import '../liquid.browser.min.js';

class Template {
    engine = {};
    directory = '';
    path = new Map();
    data = new Map();

    constructor() {
        this.engine = new liquidjs.Liquid({
            root: '',
            extname: '.html'
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
        let file = this.directory + path + '.html';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(path, namespace.length) + '.html';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let object = await response.text();

            this.data.set(path, object);

            return this.data.get(path);
        } else {
            console.log('Could not load template file ' + path);
        }

        return '';
    }

    parse(code, data = {}) {
        return this.engine.parseAndRender(code, data);
    }

    async render(path, data = {}) {
        return this.parse(await this.fetch(path), data);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Template();
        }

        return this.instance;
    }
}

const template = Template.getInstance();

export { template };