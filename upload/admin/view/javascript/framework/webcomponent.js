import Registry from './library/registry.js';
import Storage from './library/storage.js';
import Template from './library/template.js';
import Language from './library/language.js';
import Observer from './library/observer.js';
import Config from './library/config.js';

const registry = new Registry();

const base = new URL(document.querySelector('base').href);

let language = document.getElementById('input-language');

registry.set('storage', new Storage('./view/data/en-gb/'));
registry.set('template', new Template());
registry.set('Language', new Language());
registry.set('observer', new Observer());
registry.set('config', new Config());

export class WebComponent extends HTMLElement {
    registry = {};
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

    get storage() {
        return this.registry.get('storage');
    }

    get template() {
        return this.registry.get('template');
    }

    get language() {
        return this.registry.get('language');
    }

    get observer() {
        return this.registry.get('observer');
    }

    get config() {
        return this.registry.get('config');
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
    import('./component/country.js');
    import('./component/zone.js');
});
