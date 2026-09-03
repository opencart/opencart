export class WebComponent extends HTMLElement {
    data = new Map();

    constructor() {
        super();

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }
    }

    element(name) {
        return this.data.get(name);
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
            this.querySelectorAll('[data-bind], [data-on], [data-type]').forEach(element => {
                // Attach elements that have data-bind attributes
                if (element.hasAttribute('data-bind')) {
                    this.data.set(element.getAttribute('data-bind'), element);

                    element.removeAttribute('data-bind');
                }

                // Attach events based on elements that have data-on attributes
                if (element.hasAttribute('data-on')) {
                    let [ event, method] = element.getAttribute('data-on').split(':');

                    if (method in this) {
                        element.addEventListener(event, this[method].bind(this));
                    }

                    element.removeAttribute('data-on');
                }

                // Attach
                if (element.hasAttribute('data-type')) {
                    let test = this.types.get(element.getAttribute('data-type'));

                    let tdest = test();


                    new test.initialize(element);



                    element.removeAttribute('data-type');
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