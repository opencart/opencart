import './../../liquid.browser.umd.js';

export default class Template {
    engine;
    data = [];

    constructor() {
        this.engine = new liquidjs.Liquid();
    }

    get (key) {
        return this.data[key];
    }

    set (key, value) {
        this.data[key] = value;
    }

    has (key) {
        return this.data[key] !== undefined;
    }

    load(path) {
        this.fetch('./data/config/' + url + 'json').then((response) => {
            this.data = response.json();
        });
    }

    async render(path, data) {
        return this.engine.parseAndRender('./template/' + path + '.twig', data);
    }
}