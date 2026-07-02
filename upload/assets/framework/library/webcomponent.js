const base = '../catalog/view/javascript/'

const path = new Map([
    ['account', base + 'account/'],
    ['catalog', base + 'catalog/'],
    ['cms', base + 'cms/'],
    ['common', base + 'common/'],
    ['error', base + 'error/'],
    ['information', base + 'information/']
]);

export class WebComponent extends HTMLElement {
    constructor() {
        super();

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }
    }

    async connectedCallback() {
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
            this.innerHTML = output;

            // Autoload any custom elements not already loaded
            this.querySelectorAll('[data-bind], [data-on]').forEach((element) => {
                // Attach Events based on elements that have data-bind attributes
                if (element.hasAttribute('data-bind')) {
                    this['$' + element.getAttribute('data-bind')] = element;

                    element.removeAttribute('data-bind');
                }

                // Attach events based on elements that have data-on attributes
                if (element.getAttribute('data-on')) {
                    let [event, method] = element.getAttribute('data-on').split(':');

                    if (method in this) {
                        element.addEventListener(event, this[method].bind(this));
                    }

                    element.removeAttribute('data-on');
                }
            });

            /*
            elements = this.querySelectorAll('[data-action]');

            for (let element of elements) {
                let action = element.getAttribute('data-action');

                if (action in this.action) {
                    this.action[action](element);
                }
            }
            */
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
        //console.log(`${name} changed from ${value_old} to ${value_new}`);

        if (value_old !== null && value_old != value_new) {
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