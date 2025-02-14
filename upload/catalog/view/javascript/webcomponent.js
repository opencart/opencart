import './nunjucks.js';

export class WebComponent extends HTMLElement {
    static observed = [];

    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });
    }

    async addStylesheet(stylesheet) {
        let module = await (await import('./../style/' + stylesheet, {with: {type: 'css'}}));

        this.shadow.adoptedStyleSheets.push(module.default);
    }

    async render(template, data) {
        let module = await fetch('./template/' + template);

        return nunjucks.renderString(await module.text(), this.data);
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
    import('./component/alert.js');
    import('./component/country.js');
    import('./component/zone.js');
    import('./component/switch.js');
    import('./component/modal.js');
});
