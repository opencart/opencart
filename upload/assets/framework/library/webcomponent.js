import { registry } from '../index.js';

export class WebComponent extends HTMLElement {
    static registry = null;
    data = [];

    constructor() {
        super();

        this.config = registry.get('config');
        this.db = registry.get('db');
        this.factory = registry.get('factory');
        this.language = registry.get('language');
        this.load = registry.get('loader');
        this.local = registry.get('local');
        this.registry = registry;
        this.session = registry.get('session');
        this.storage = registry.get('storage');
        this.template = registry.get('template');
    }

    async render(path) {
        // Merge language vars with the data
        let languages = this.language.all();

        for (let key in languages) {
            if (this.data[key] == undefined) {
                this.data[key] = languages[key];
            }
        }

        console.log(this.data);

        this.innerHTML = await this.template.render(path, this.data);
    }

    connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }
    }

    disconnectedCallback() {
        if (this.disconnected !== undefined) {
            this.disconnected();
        }
    }

    adoptedCallback() {
        if (this.adopted !== undefined) {
            this.adopted();
        }
    }

    static get observedAttributes() {
        return this.observed;
    }

    attributeChangedCallback(name, value_old, value_new) {
        let event = new CustomEvent('[' + name + ']', {
            bubbles: true,
            cancelable: true,
            detail: {
                value_old: value_old,
                value_new: value_new
            }
        });

        // Dispatch the event
        this.dispatchEvent(event);
    }
}