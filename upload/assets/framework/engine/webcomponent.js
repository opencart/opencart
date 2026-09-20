import { emitter } from '../index.js';

export class WebComponent extends HTMLElement {
    static observed = [];
    static formAssociated = false;
    #open;
    #shadow;
    #internal;
    #state;

    constructor() {
        super();

        this.#open = false;
        this.#state = new Map();

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }

        if (this.#open) {
            this.#shadow = this.attachShadow({ mode: 'open' });

            console.log(this.#open);
            console.log(this.#shadow);
        }

        // Attach Internals
        this.#internal = this.attachInternals();

        // Reactive state: wrap in a Proxy so any mutation (set/delete)
        // automatically schedules a re-render.
        //this.state = this.#createReactiveState(typeof this.initialState === 'function' ? this.initialState() : {});
    }

    async connectedCallback() {
        if ('connected' in this) {
            await this.connected();
        }

        if ('render' in this) {
            this.update();
        }
    }

    async update() {
        let output = await this.render();

        if (output) {
            this.innerHTML = output;

            // Autoload any custom elements not already loaded
            this.querySelectorAll('[data-bind], [data-on], [data-action], [data-state], [data-template]').forEach(element => {
                // Attach Events based on elements that have data-bind attributes
                if (element.hasAttribute('data-bind')) {
                    let name = element.getAttribute('data-bind');
                    let tag = element.tagName.toLowerCase();

                    let type = {
                        a: "click",
                        button: "click",
                        form: 'submit',
                        details: "toggle",
                        input: (element.getAttribute('type') == 'submit' ? 'click' : 'input'),
                        select: "change",
                        textarea: "input",
                    }

                    element.addEventListener(type[tag], this[name]);


                    emitter.signal().emit({
                        element: element,
                        type: type,
                        value: element.value
                    });

                    element.removeAttribute('data-bind');

                    //let test = emitter.signal(element.getAttribute('data-bind')).connect();
                    //binder.set(element.getAttribute('data-bind'), element);
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
                if (element.hasAttribute('data-action')) {



                    let parts = element.getAttribute('data-action').split(' ');

                    for (let part of parts) {
                        action.attach(part, element);
                    }



                    element.removeAttribute('data-action');
                }







                // Bind element to a state key: data-state="key" (or "key:attr" to bind
                // to a specific attribute instead of textContent/value).
                if (element.hasAttribute('data-state')) {
                    //let [ key, attr ] = element.getAttribute('data-state').split(':');

                    //this.#bindStateToElement(element, key, attr);
                }
            });
        }
    }

    // Merge helper: this.setState({ count: 1, name: 'x' })
    // or functional form: this.setState(state => ({ count: state.count + 1 }))
    setState(patch) {
        const updates = typeof patch === 'function' ? patch(this.state) : patch;

        for (let key in updates) {
            this.state[key] = updates[key];
        }
    }

    // Wires an element to display (and, for inputs, write back to) a state key.
    #bindStateToElement(element, key, attr) {
        const applyValue = () => {
            const value = this.state[key];

            if (attr) {
                if (value === false || value === null || value === undefined) {
                    element.removeAttribute(attr);
                } else {
                    element.setAttribute(attr, value);
                }
            } else if ('value' in element && (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT')) {
                element.value = value ?? '';
            } else {
                element.textContent = value ?? '';
            }
        };

        applyValue();

        // Keep this element in sync if state changes again before the next full render.
        this.addEventListener('[state:' + key + ']', applyValue);

        // Two-way binding: form elements write back into state on input.
        if (!attr && (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA' || element.tagName === 'SELECT')) {
            element.addEventListener('input', () => {
                this.state[key] = element.value;
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