import { loader } from '../index.js';

export class WebComponent extends HTMLElement {
    constructor() {
        super();

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            if (!attribute.name.startsWith('data-')) {
                this.addEventListener('[' + attribute.name + ']', this.initialize.bind(this));
            }
        }
    }

    async connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }

        if (this.render !== undefined) {
            this.initialize();
        }
    }

    async initialize() {
        let render = (html) => {
            this.innerHTML = html;

            // Attach Events based on elements that have data-on attributes
            let elements = this.querySelectorAll('[data-bind], [data-on]');

            for (let element of elements) {
                // Binds the element to an attribute by name.
                if (element.hasAttribute('data-bind')) {
                    this['$' + element.getAttribute('data-bind')] = element;

                    element.removeAttribute('data-bind');
                }

                if (element.hasAttribute('data-on')) {
                    let part = element.getAttribute('data-on').split(':');

                    if (part[1] !== undefined && part[1] in this) {
                        element.addEventListener(part[0], this[part[1]].bind(this));

                        element.removeAttribute('data-on');
                    }
                }
            }
        };

        let response = this.render();

        if (response instanceof Promise) {
            response.then(render.bind(this));
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