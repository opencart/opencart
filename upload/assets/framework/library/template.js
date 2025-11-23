import { registry } from './registry.js';
import '../liquid.browser.umd.js';

export default class Template {
    static instance = null;
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

    static getInstance(registry) {
        if (!this.instance) {
            this.instance = new Template(registry.config.get('template_path'));
        }

        return this.instance;
    }
}

registry.template = Template.getInstance(registry);