import '../liquid.browser.umd.js';

class Template {
    static #instance = null;
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

    static getInstance() {
        if (!Template.#instance) {
            Template.#instance = new Template();
        }

        return Template.#instance;
    }
}

const template = Template.getInstance();

export { template };