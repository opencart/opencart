import Loader from './library/loader.js';
import Registry from './library/registry.js';
import Config from './library/config.js';
import Language from './library/language.js';
import Template from './library/template.js';

const registry = new Registry();
const loader= new Loader(registry);

registry.set('load', loader);
registry.set('data', []);

export class WebComponent extends HTMLElement {
    registry = {};
    data = [];
    event = {
        connected: null,
        disconnected: null,
        adopted: null,
        changed: null
    };

    static observed = [];

    /**
     * Constructor
     */
    constructor() {
        super();

        this.registry = registry;
        this.load = loader;
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
    //import('./component/zone.js');
});
