import { loader } from '../index.js';

// Attach Events based on elements that have data-on-* attributes
const event = (html) => {
    let elements = this.querySelectorAll('[data-on]');

    for (let element of elements) {
        let part= element.getAttribute('data-on').split(':');

        element.addEventListener(part[0], this[part[1]]);

        element.removeAttribute('data-on');
    }
}

export class WebComponent extends HTMLElement {
    element = this;

    constructor() {
        super();
    }

    async connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }

        // Set any changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.render);
        }

        if (this.render !== undefined) {
            let response = this.render();

            //console.log(response);
           // console.log(render);

            response.then((html) => {
                this.innerHTML = html;
            });

            //response.then(event.bind(this));

            this.addEventListener('[value]', this.render);
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