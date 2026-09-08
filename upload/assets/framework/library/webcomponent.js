import { binder, action } from '../index.js';

export class WebComponent extends HTMLElement {
    constructor() {
        super();

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }
    }

    async connectedCallback() {
        //this.attachShadow({ mode: 'open' });

        if (this.connected !== undefined) {
            this.connected();
        }

        if (this.render !== undefined) {
            this.update();
        }
    }

    async update() {
        let output = await this.render();

        if (output) {
            //this.shadowRoot.innerHTML = output;
            this.innerHTML = output;

            // Autoload any custom elements not already loaded
            this.querySelectorAll('[data-bind], [data-on], [data-action]').forEach(element => {
                // Attach Events based on elements that have data-bind attributes
                if (element.hasAttribute('data-bind')) {
                    binder.set(element.getAttribute('data-bind'), element);

                    element.removeAttribute('data-bind');
                }

                // Attach events based on elements that have data-on attributes
                if (element.hasAttribute('data-on')) {
                    let [ event, method] = element.getAttribute('data-on').split(':');

                    if (method in this) {
                        element.addEventListener(event, this[method]);
                    }

                    element.removeAttribute('data-on');
                }

                // Attach
                if (element.hasAttribute('data-action')) {
                    console.log(element);

                    let parts = element.getAttribute('data-action').split(' ');

                    console.log(parts);

                    for (let part of parts) {
                        let test = action.create(part, element);

                        let value = this.data.get(part);

                        let rrrr = new value(element);


                    }

                    //console.log('element', element);.button('loading')
                    console.log('element', Object.entries(element));


                    element.removeAttribute('data-action');
                }
            });
        }
    }

    disconnectedCallback() {
        if (this.disconnected !== undefined) {
            this.disconnected();
        }
    }

    adoptedCallback() {
        if (this.render !== undefined) {
            this.update();
        }
    }

    static get observedAttributes() {
        return this.observed;
    }

    attributeChangedCallback(name, value_old, value_new) {
        console.log(`${name} changed from ${value_old} to ${value_new}`);

        if (value_old != value_new) {
            let event = new CustomEvent('[' + name + ']', {
                bubbles: false,
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
}