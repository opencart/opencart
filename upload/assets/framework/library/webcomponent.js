import { registry } from '../index.js';

export class WebComponent extends HTMLElement {
    static registry = null;

    constructor() {
        super();

        this.registry = registry;
        this.load = registry.get('loader');



        console.log('WebComponent');
        console.log(this.registry);


        //for ([key, value] of registry.all().entries()) {
        //    this[key] = registry.get();
        //}
    }

    //get load() {
    //   return registry.get('loader');
    //}

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