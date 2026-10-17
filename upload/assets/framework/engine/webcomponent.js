import { Binder } from './binder.js';
import { Global } from './global.js';
import { State } from './state.js';
import { stylesheet } from './stylesheet.js';

/**
 * WebComponent
 * -------------
 * A minimal base class for building web components with:
 *   - Automatic shadow DOM setup
 *   - Template + styles rendering
 *   - Automatic ref/event binding via Binder (@ref / @click / @submit, etc...)
 *   - Clean lifecycle hooks (onConnect, onDisconnect, onAttributeChange)
 *
 * Usage:
 *
 *   class MyCounter extends BaseComponent {
 *     observed ['count'];
 *
 *     styles() {
 *       return `
 *          button { font-size: 1rem; }
 *          span { font-weight: bold; margin-left: 0.5rem; }
 *       `;
 *     }
 *
 *     template() {
 *          return `<button @ref="btn" @click="increment">+1</button><span data-ref="display">0</span>`;
 *     }
 *
 *     onConnect() {
 *          this.count = Number(this.getAttribute('count')) || 0;
 *     }
 *
 *     increment() {
 *          this.count++;
 *          this.setAttribute('count', this.count);
 *     }
 *
 *     onAttributeChange(name, value_old, val_new) {
 *          if (name === 'count') this.display.textContent = new_val;
 *      }
 *  }
 *
 *   customElements.define('my-counter', MyCounter);
 */
export class WebComponent extends HTMLElement {
    static formAssociated = false;
    static shadow = true;
    #root = null;
    #binder = null;
    #global = null;
    // Make sure reactive attributes don't work until after render has been called.
    #rendered = false;

    static get observedAttributes() {
        return [];
    }

    static states() {
        return {};
    }

    constructor() {
        super();

        // Attach Shadow or use light DOM based on the static property
        this.#root = this.constructor.shadow ? this.attachShadow({ mode: 'open' }) : this;

        // Global
        this.#global = Global;

        // State
        this.state = new State(this.constructor.states(), this.stateChangedCallback.bind(this));

        // Attach Internals
        this.internal = this.attachInternals();
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

    async connectedCallback() {
        if (typeof this.handleConnect === 'function') {
            await this.handleConnect();
        }

        await this.update();

        if (typeof this.test === 'function') this.test();

        this.#rendered = true;
    }

    async disconnectedCallback() {
        console.log('disconnectedCallback');

        if (this.binder) {
            this.binder.destroy();
        }

        if (this.state) {
            this.state.destroy();
        }

        if (typeof this.handleDisconnect === 'function') {
            await this.handleDisconnect();
        }
    }

    async adoptedCallback() {
        if (typeof this.render === 'function') {
            await this.update();
        }
    }

    async attributeChangedCallback(name, value_old, value_new) {
        if (!this.#rendered || value_old === value_new) return;

        console.log(`${name} changed from ${value_old} to ${value_new}`);

        // Default handler for attribute changes
        if (typeof this.handleAttribute == 'function') {
            await this.handleAttribute(name, value_new, value_old);
        }

        // Call the specific handler for this attribute change, if it exists.
        let handler = `handle${name.charAt(0).toUpperCase() + name.slice(1)}`;

        if (typeof this[handler] === 'function') {
            await this[handler](value_new, value_old);
        }
    }

    async stateChangedCallback(changed, state) {
        if (!this.#rendered || !changed.length) return;

        // Default handler for state changes
        if (typeof this.handleState == 'function') {
            await this.handleState(changed, state);
        }

        for (let key of changed) {
            let handler = `handle${key.charAt(0).toUpperCase() + key.slice(1)}`;

            if (typeof this[handler] === 'function') {
                await this[handler](state);
            }
        }
    }

    async update() {
        if (typeof this.render !== 'function') return;

        this.#root.innerHTML = await this.render();

        if (!this.#binder) {
            this.#binder = new Binder(this.#root, this);
        } else {
            this.#binder.refresh();
        }

        // Stylesheet
        const hrefs = this.stylesheets();

        if (hrefs && hrefs.length) {
            // Adopts asynchronously; inline `styles()` above still applies
            // immediately so there's no unstyled flash for critical CSS.
            stylesheet.adopt(this.#root, hrefs).catch(error => console.error('WebComponent: failed to adopt stylesheets', error));
        }
    }
}