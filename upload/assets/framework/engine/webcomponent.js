import { Binder } from './binder.js';
import { State } from './state.js';
import { stylesheet } from './stylesheet.js';

/**
 * WebComponent
 * -------------
 * A minimal base class for building web components with:
 *   - Automatic shadow DOM setup
 *   - Template + styles rendering
 *   - Automatic ref/event binding via ElementBinder (data-ref / data-on)
 *   - Clean lifecycle hooks (onConnect, onDisconnect, onAttributeChange)
 *
 * Usage:
 *
 *   class MyCounter extends BaseComponent {
 *     static get observed { return ['count']; }
 *
 *     styles() {
 *       return `
 *         button { font-size: 1rem; }
 *         span { font-weight: bold; margin-left: 0.5rem; }
 *       `;
 *     }
 *
 *     template() {
 *       return `<button data-ref="btn" data-on="click:increment">+1</button><span data-ref="display">0</span>`;
 *     }
 *
 *     onConnect() {
 *       this.count = Number(this.getAttribute('count')) || 0;
 *     }
 *
 *     increment() {
 *       this.count++;
 *       this.setAttribute('count', this.count);
 *     }
 *`
 *     onAttributeChange(name, value_old, val_new) {
 *       if (name === 'count') this.display.textContent = new_val;
 *     }
 *   }
 *
 *   BaseComponent.define('my-counter', MyCounter);
 */
export class WebComponent extends HTMLElement {
    static observed = [];
    static formAssociated = false;

    static get observedAttributes() {
        return this.observed;
    }

    constructor() {
        super();

        // Attach Shadow
        this.shadow = this.attachShadow({ mode: 'open' });

        // Attach Internals
        this.internal = this.attachInternals();

        // Binder
        this.binder = null;

        // State
        this.state = new State(this.initialState(), {
            onChange: this.handleState.bind(this)
        });

        // Make sure reactive attributes don't work until after render has been called.
        this.connected = false;

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }
    }

    /** Override: return the initial values for `this.state`. */
    initialState() {
        return {};
    }

    handleState(keys) {
        if (typeof this.onStateChange === 'function') {
            this.onStateChange(keys, this.state);
        } else {
            this.update();
        }
    }

    async connectedCallback() {
        if (typeof this.onConnect === 'function') {
            await this.onConnect();
        }

        if (typeof this.render === 'function') {
            await this.update();
        }

        this.connected = true;
    }

    /** Override: list of external CSS file URLs to adopt into this component. */
    stylesheets() {
        return [
            'stylesheet.css',
            'fontawesome/css/all.css'
        ];
    }

    /** Override: return a CSS string scoped to this component's shadow root. */
    styles() {
        return '';
    }

    /** Override: return the HTML string for the component's shadow DOM. */
    template() {
        return '';
    }

    async update() {
        let output = await this.render();

        if (output) {
            this.shadow.innerHTML = output;

            if (this.binder) {
                this.binder.refresh();
            } else {
                this.binder = new Binder(this.shadow, this);
            }
        }

        // Stylesheet
        const hrefs = this.stylesheets();

        if (hrefs && hrefs.length) {
            // Adopts asynchronously; inline `styles()` above still applies
            // immediately so there's no unstyled flash for critical CSS.
            stylesheet.adopt(this.shadow, hrefs).catch(error => console.error('WebComponent: failed to adopt stylesheets', error));
        }
    }

    async disconnectedCallback() {
        if (this.binder) {
            this.binder.destroy();
        }

        if (this.state) {
            this.state.destroy();
        }

        if (typeof this.onDisconnect === 'function') {
            await this.onDisconnect();
        }
    }

    async adoptedCallback() {
        if (typeof this.render === 'function') {
            await this.update();
        }
    }

    attributeChangedCallback(name, value_old, value_new) {
        if (!this.connected || value_old === value_new) return;

        console.log(`${name} changed from ${value_old} to ${value_new}`);

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