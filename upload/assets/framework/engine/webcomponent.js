import { Binder } from './binder.js';
import { State } from './state.js';
import { stylesheet } from './stylesheet.js';

const camel = (name) => name.split('-').map((part) => part.charAt(0).toUpperCase() + part.slice(1)).join('');
/**
 * WebComponent
 * -------------
 * A minimal base class for building web components with:
 *   - Shadow DOM, or light DOM with `static shadow = false`
 *   - render() + styles() + stylesheets()
 *   - Automatic ref/event binding via Binder (@ref / @click / @submit, etc...)
 *   - Reactive state via `states()` and this.state
 *   - Lifecycle hooks: handleConnect, handleDisconnect, handleAttribute, handleState
 *
 * Named handlers are called when they exist:
 *   - attribute "count" changes  ->  attributeCount(name, value_new, value_old)
 *   - state "count" changes      ->  stateCount(value, state)
 *
 * Usage:
 *
 *   class MyCounter extends WebComponent {
 *       static observedAttributes = ['count'];
 *
 *       states() {
 *           return { count: 0 };
 *       }
 *
 *       styles() {
 *           return `
 *              button { font-size: 1rem; }
 *              span { font-weight: bold; margin-left: 0.5rem; }
 *           `;
 *       }
 *
 *       render() {
 *           return `<button @click="increment">+1</button><span @ref="display">${this.state.get('count')}</span>`;
 *       }
 *
 *       handleConnect() {
 *           this.state.set('count', Number(this.getAttribute('count')) || 0);
 *       }
 *
 *       increment() {
 *           this.state.set('count', this.state.get('count') + 1);
 *       }
 *
 *       stateCount(value) {
 *           this.display.textContent = value;
 *       }
 *   }
 *
 *   customElements.define('my-counter', MyCounter);
 */
export class WebComponent extends HTMLElement {
    static formAssociated = false;
    static shadow = true;

    root = null;
    #binder = null;
    #template = document.createElement('template');
    #renders = 0;
    // Make sure reactive attributes don't work until after render has been called.
    #rendered = false;

    static get observedAttributes() {
        return [];
    }

    constructor() {
        super();

        // Attach Shadow or use light DOM based on the static property
        this.root = this.constructor.shadow ? this.attachShadow({ mode: 'open' }) : this;

        // State
        this.state = new State(this.states(), this.stateChangedCallback.bind(this));

        // Attach Internals
        this.internal = this.attachInternals();

        this.#style();
    }

    async connectedCallback() {
        if (typeof this.handleConnect === 'function') {
            await this.handleConnect();
        }

        await this.update();

        this.#rendered = true;
    }

    async disconnectedCallback() {
        this.#rendered = false;

        if (this.#binder) {
            this.#binder.destroy();
        }

        if (this.state) {
            this.state.destroy();
        }

        if (typeof this.handleDisconnect === 'function') {
            await this.handleDisconnect();
        }
    }

    async adoptedCallback() {
        await this.update();
    }

    async attributeChangedCallback(name, value_old, value_new) {
        if (!this.#rendered || value_old === value_new) return;

        // Default handler for attribute changes
        if (typeof this.handleAttribute == 'function') {
            await this.handleAttribute(name, value_new, value_old);
        }

        // Call the specific handler for this attribute change, if it exists.
        let handler = 'attribute' + camel(name);

        if (typeof this[handler] === 'function') {
            await this[handler](name, value_new, value_old);
        }
    }

    async stateChangedCallback(changed, state) {
        if (!this.#rendered || !changed.length) return;

        // Default handler for state changes
        if (typeof this.handleState == 'function') {
            await this.handleState(changed, state);
        }

        // Call the specific handler for each changed key, if it exists.
        for (let key of changed) {
            let handler = 'state' + camel(key);

            if (typeof this[handler] === 'function') {
                await this[handler](state[key], state);
            }
        }
    }

    states() {
        return {};
    }

    /** Override: CSS files to adopt into the shadow root, as names stylesheet.resolve() knows. */
    stylesheets() {
        return ['catalog/stylesheet.css', 'fontawesome/css/all.css'];
    }

    /** Override: return a CSS string scoped to this component's shadow root. */
    styles() {
        return '';
    }

    // Styles go on the shadow root once, from the constructor. A light DOM component uses the
    // page's CSS, since anything adopted into the document would apply to the whole page.
    // The files go first and styles() after them, so the component's own rules win.
    async #style() {
        if (this.shadow) return;

        await stylesheet.adopt(this.root, this.stylesheets());

        const css = this.styles();

        if (css) await stylesheet.adoptText(this.root, [css]);
    }

    async update() {
        if (typeof this.render !== 'function') return;

        const render = ++this.#renders;
        const html = String(await this.render());

        // A newer update started while this one was rendering, so this one is out of date.
        if (render !== this.#renders) return;

        this.#template.innerHTML = html;

        // Put the new content in place first, so the binder walks what is now on the page.
        this.root.replaceChildren(this.#template.content);

        if (!this.#binder) {
            this.#binder = new Binder(this.root, this);
        } else {
            this.#binder.refresh();
        }
    }
}
