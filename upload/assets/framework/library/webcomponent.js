import { loader } from '../index.js';

export class WebComponent extends HTMLElement {
    element = this;

    constructor() {
        super();
    }

    async connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }

        // Adds reactive components event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            // Create reactive attributes
            this.addEventListener('[' + attribute.name + ']', this.render);
        }

        if (this.render !== undefined) {
            this.innerHTML = await this.render();

            // Attach Events based on elements that have data-on attributes
            let elements = this.querySelectorAll('[data-on]');

            for (let element of elements) {
                let part= element.getAttribute('data-on').split(':');

                element.addEventListener(part[0], this[part[1]]);

                element.removeAttribute('data-on');
            }
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