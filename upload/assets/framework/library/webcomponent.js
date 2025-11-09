export default class WebComponent extends HTMLElement {
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