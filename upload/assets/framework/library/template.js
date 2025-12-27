import '../liquid.browser.umd.js';

export class Template {
    directory = '';
    path = [];
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
            this.path[namespace] = path;
        }
    }

    async render(path, data = []) {
        let file = this.directory + path + '.twig';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path[namespace] !== undefined) {
                file = this.path[namespace] + path.substr(path, namespace.length) + '.twig';
            }
        }

        return this.engine.renderFile(file, data);
    }
}