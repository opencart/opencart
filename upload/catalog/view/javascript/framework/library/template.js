import './../../liquid.browser.umd.js';

export default class Template {
    engine = {};

    constructor() {
        this.engine = new liquidjs.Liquid({
            root: '',
            extname: '.liquid'
        });
    }

    async render(path, data) {
        return this.engine.renderFile('./template/' + path, data);
    }
}