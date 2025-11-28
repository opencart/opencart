import '../liquid.browser.umd.js';

export class Template {
    path = {};
    engine = {};

    constructor(path) {
        this.path = path;

        this.engine = new liquidjs.Liquid({
            root: '',
            extname: '.liquid'
        });
    }

    async render(path, data) {
        return this.engine.renderFile('./template/' + this.path + path, data);
    }
}