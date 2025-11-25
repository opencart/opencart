import '../liquid.browser.umd.js';

export class Template {
    path = {};
    engine = {};

    constructor() {
        this.engine = new liquidjs.Liquid({
            root: '',
            extname: '.liquid'
        });
    }

    setPath(path) {
        this.path = path;
    }

    async render(path, data) {
        return this.engine.renderFile('./template/' + this.path + path, data);
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