import { loader } from '../index.js';

const sdsd = (html) => {
    console.log(html);

    this.innerHTML = html;
}

// Attach Events based on elements that have data-on-* attributes
const event = (html) => {
    let elements = this.querySelectorAll('[data-on]');

    for (let element of elements) {
        for (let attribute of element.attributes) {

            console.log(attribute.name);

            let type = attribute.value.split('.');

            element.addEventListener(type, this[attribute.value]);

            element.removeAttribute(attribute.name);
        }
    }
}

export class WebComponent extends HTMLElement {
    constructor() {
        super();

        // Set any changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.render);
        }
    }

    connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }

        if (this.render !== undefined) {
            let response = this.render();

            console.log(response);

            response.then(sdsd.bind(this));
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