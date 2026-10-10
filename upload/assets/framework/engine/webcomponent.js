import { Binder } from './binder.js';
import { Global } from './global.js';
import { State } from './state.js';
import { Style } from './style.js';

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
 *       return `<button @ref="btn" @click="increment">+1</button><span data-ref="display">0</span>`;
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
    static observedAttributes = [];
    static formAssociated = false;
    states = {};

    constructor() {
        super();

        // Attach Shadow
        this.shadow = this.attachShadow({ mode: 'open' });

        // Attach Internals
        this.internal = this.attachInternals();

        // Binder
        this.binder = null;

        // Global
        this.global = Global;

        // State
        this.state = new State(this.states, this.onStateChange.bind(this));

        // Make sure reactive attributes don't work until after render has been called.
        this.rendered = false;
    }

    async connectedCallback() {
        if (typeof this.handleConnect === 'function') {
            await this.handleConnect();
        }

        await this.update();

        this.rendered = true;
    }

    async disconnectedCallback() {
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

    attributeChangedCallback(name, value_old, value_new) {
        if (!this.rendered || value_old === value_new) return;

        //console.log(`${name} changed from ${value_old} to ${value_new}`);
        this.onAttributeChange(name, value_old, value_new);
    }

    async update() {
        if (typeof this.render !== 'function') return;

        this.shadow.innerHTML = await this.render();

        if (this.binder) {
            this.binder.refresh();
        } else {
            this.binder = new Binder(this.shadow, this);
        }

        // Stylesheet
        const hrefs = this.stylesheets();

        if (hrefs && hrefs.length) {
            // Adopts asynchronously; inline `styles()` above still applies
            // immediately so there's no unstyled flash for critical CSS.
            Style.adopt(this.shadow, hrefs).catch(error => console.error('WebComponent: failed to adopt stylesheets', error));
        }
    }

    async onAttributeChange(key, value_new, value_old) {
        let name= 'handle' + key.split('-').map(value => value.charAt(0).toUpperCase() + value.slice(1)).join('');

        if (typeof this[name] === 'function') {
            await this[name](key, value_new, value_old);
        } else {
            this.update();
        }
    }

    async onStateChange(key, value_new, value_old) {
        let name= 'handle' + key.split('-').map(value => value.charAt(0).toUpperCase() + value.slice(1)).join('');

        if (typeof this[name] === 'function') {
            await this[name](key, value_new, value_old);
        } else {
            this.update();
        }
    }

    /** Override: list of external CSS file URLs to adopt into this component. */
    stylesheets() {
        return [];
    }

    /** Override: return a CSS string scoped to this component's shadow root. */
    styles() {
        return '';
    }

    /** Override: return the HTML string for the component's shadow DOM. */
    template() {
        return '';
    }
}