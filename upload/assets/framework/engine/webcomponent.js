import { Binder } from './binder.js';
import { State } from './state.js';
//import { Sheet } from './Sheet.js';

/**
 * BaseComponent
 * -------------
 * A minimal base class for building web components with:
 *   - Automatic shadow DOM setup
 *   - Template + styles rendering
 *   - Automatic ref/event binding via ElementBinder (data-ref / data-on)
 *   - Clean lifecycle hooks (onConnect, onDisconnect, onAttributeChange)
 *   - A simple `define()` helper for registration
 *
 * Usage:
 *
 *   class MyCounter extends BaseComponent {
 *     static get observedAttributes() { return ['count']; }
 *
 *     styles() {
 *       return `
 *         button { font-size: 1rem; }
 *         span { font-weight: bold; margin-left: 0.5rem; }
 *       `;
 *     }
 *
 *     template() {
 *       return `
 *         <button data-ref="btn" data-on="click:increment">+1</button>
 *         <span data-ref="display">0</span>
 *       `;
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

    /** Override: list of external CSS file URLs to adopt into this component. */
    static get stylesheets() {
        return [];
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
            onChange: (keys) => this._handleStateChange(keys),
        });

        // Adds reactive component event changes to the attributes of the element to re-render the contents.
        for (let attribute of this.attributes) {
            this.addEventListener('[' + attribute.name + ']', this.update.bind(this));
        }
    }

    /** Override: return the initial values for `this.state`. */
    initialState() {
        return {};
    }

    _handleStateChange(changedKeys) {
        if (typeof this.onStateChange === 'function') {
            this.onStateChange(changedKeys, this.state);
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

        /*
        const hrefs = this.constructor.stylesheets;

        if (hrefs && hrefs.length) {
            // Adopts asynchronously; inline `styles()` above still applies
            // immediately so there's no unstyled flash for critical CSS.
            StylesheetImporter.adopt(this.shadow, hrefs).catch((err) =>
                console.error('BaseComponent: failed to adopt stylesheets', err)
            );
        }
        */
    }

    async disconnectedCallback() {
        if (this.binder) {
            this.binder.destroy();
        }

        if (typeof this.onDisconnected === 'function') {
            await this.onDisconnected();
        }
    }

    async adoptedCallback() {
        if (typeof this.render === 'function') {
            await this.update();
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