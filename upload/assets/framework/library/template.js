import '../liquid.browser.umd.js';

export class Template {
    static instance;
    directory = '';
    path = new Map();
    engine = {};

    constructor(path) {
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

    async render(path, data = {}) {
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




        return this.engine.renderFile(file, data);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Template();
        }

        return this.instance;
    }
}

const template = Template.getInstance();

export default template;