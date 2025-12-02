import { registry } from './registry.js';

export class WebComponent extends HTMLElement {
    static registry = null;
    event = {
        connected: null,
        disconnected: null,
        adopted: null,
        changed: null
    };

    constructor() {
        super();

        this.registry = registry;
    }

    get load() {
        return this.registry.get('load');
    }

    get config() {
        return this.registry.get('config');
    }

    get storage() {
        return this.registry.get('storage');
    }

    get language() {
        return this.registry.get('language');
    }

    get template() {
        return this.registry.get('template');
    }

    get session() {
        return this.registry.get('session');
    }

    get local() {
        return this.registry.get('local');
    }

    get db() {
        return this.registry.get('db');
    }

    get cart() {
        return this.registry.get('cart');
    }

    get tax() {
        return this.registry.get('tax');
    }

    get currency() {
        return this.registry.get('currency');
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