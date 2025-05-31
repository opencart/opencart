import Registry from './library/registry.js';
import Storage from './library/storage.js';
import Template from './library/template.js';
import Language from './library/language.js';
import Config from './library/config.js';

const registry = new Registry();

const base = new URL(document.querySelector('base').href);

let language = document.querySelector('html').lang.toLowerCase();

registry.set('storage', new Storage('./catalog/view/data/' + base.host + '/' + language + '/'));
registry.set('template', new Template());
registry.set('Language', new Language());
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

document.addEventListener('DOMContentLoaded', () => {
    import('./component/currency.js');
    import('./component/country.js');
    import('./component/switch.js');
    import('./component/zone.js');
});
