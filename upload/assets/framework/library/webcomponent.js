import { loader } from '../index.js';

export class WebComponent extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        if (this.connected !== undefined) {
            this.connected();
        }

        if (this.render !== undefined) {
            let render = (html) => {
                this.innerHTML = html;
            }

            let event = (html) => {
                let elements = this.querySelectorAll('*');

                for (let element of elements) {
                    for (let attribute of element.attributes) {
                        if (attribute.name.startsWith('data-on-')) {
                            let type = attribute.name.substr(8);

                            element.addEventListener(type, this[attribute.value]);

                            element.removeAttribute(attribute.name);
                        }
                    }
                }
            }

            let response = this.render();

            response.then(render.bind(this));
            response.then(event.bind(this));
        }
    }

    disconnectedCallback() {
        if (this.disconnected == undefined) {
            return;
        }

        this.disconnected();
    }

    adoptedCallback() {
        if (this.adopted == undefined) {
            return;
        }

        this.adopted();
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