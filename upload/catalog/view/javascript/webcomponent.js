import './liquid.browser.umd.js';

const engine = new liquidjs.Liquid();

export class WebComponent extends HTMLElement {
    static observed = [];
    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });
    }

    async addStylesheet(stylesheet) {
        let module = await (await import('./../stylesheet/' + stylesheet, {with: {type: 'css'}}));

        this.shadow.adoptedStyleSheets.push(module.default);
    }

    async parse(code, data) {
        return engine.parseAndRender(code, this.data).then(function(html) {
            document.body.innerHTML = html
        });
    }

    async render(template, data) {
        let module = await fetch('./template/' + template);

        return engine.parseAndRender(await module.text(), this.data);
    }

    connectedCallback() {
        if (this.event.connected) {
            this.event.connected();
        }
    }

    disconnectedCallback() {
        if (this.event.disconnected) {
            this.event.disconnected();
        }
    }

    adoptedCallback() {
        if (this.event.adopted) {
            this.event.adopted();
        }
    }

    static get observedAttributes() {
        return this.observed;
    }

    attributeChangedCallback(name, value_old, value_new) {
        if (this.event.changed) {
            this.event.changed(name, value_old, value_new);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    import('./webcomponent/country.js');
    import('./webcomponent/zone.js');
});
