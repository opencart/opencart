import { Global } from './global.js';

/**
 * Binder
 * -------------
 * Scans a web component's shadow root (or any root node) for two kinds
 * of attributes and wires them up automatically:
 *
 *   1. Refs      — `@ref="name"` exposes the element as `this.refs.name`
 *                  and as a getter on the host (e.g. `this.name`).
 *   2. Events    — `@click="handleClick"` (or any `@eventname="method"`)
 *                  attaches a listener that calls the matching method
 *                  on the host. Modifiers can be chained with dots:
 *                  `@click.prevent.stop="handleClick"` calls
 *                  `preventDefault()`/`stopPropagation()` before running
 *                  the handler. `@click.once="handleClick"` removes
 *                  itself after firing once.
 *
 * Prefixing the attribute name with `:` instead of `@` binds against a
 * static registry on the Binder class itself — shared across
 * every component instance — instead of the local host/refs:
 *
 *   `:ref="appHeader"`    → stored in Binder.globalRefs.appHeader,
 *                           readable anywhere via Binder.getGlobalRef('appHeader')
 *   `:click="trackClick"` → calls a function registered with
 *                           Binder.registerListener('trackClick', fn)
 *                           instead of looking for the method on the host
 *
 * Register global listeners once, e.g. at app startup:
 *
 *   Binder.registerListener('trackClick', (event) => {
 *     analytics.log('click', event.target.id);
 *   });
 *   // or register several at once:
 *   Binder.registerListener({ trackClick, openModal });
 *
 * Then any component's template can use it without defining a local method:
 *
 *   template() {
 *     return `<button :click="trackClick">Buy</button>`;
 *   }
 *
 * `@`- and `:`-prefixed attribute names are valid HTML and parse
 * natively in the browser — no template compiler needed. Traversal uses
 * a TreeWalker (filtered to elements that actually carry one of these
 * attributes) rather than `querySelectorAll('*')`, so only qualifying
 * nodes are visited.
 *
 * Usage inside a custom element:
 *
 * class MyWidget extends HTMLElement {
 *     constructor() {
 *         super();
 *         this.attachShadow({ mode: 'open' });
 *         this.shadowRoot.innerHTML = `
 *           <button @ref="btn" @click="handleClick">Click me</button>
 *           <span @ref="label"></span>
 *         `;
 *     }
 *
 *     connectedCallback() {
 *       this.binder = new ElementBinder(this.shadowRoot, this);
 *       this.label.textContent = 'Ready';
 *     }
 *
 *     handleClick() {
 *       this.label.textContent = 'Clicked!';
 *     }
 *
 *     disconnectedCallback() {
 *       this.binder.destroy();
 *     }
 * }
 */
export class Binder {
    #root;
    #host;
    #refs = new Map();
    #listeners = []; // track for clean teardown
    #bindings = []; // { name, el } set by this instance, for cleanup

    constructor(root, host) {
        this.#root = root;
        this.#host = host || root.host || root;

        // Attach events based on elements that have data-bind attributes
        this.#walk(this.#root);
    }

    #walk(root) {
        let carries = (node) => node.getAttributeNames().some((name) => name.startsWith('@') || name.startsWith(':'));

        if (root.nodeType === 1 && carries(root)) this.bind(root);

        const walker = document.createTreeWalker(root, NodeFilter.SHOW_ELEMENT, {
            acceptNode: (node) => carries(node) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
        });

        let node = walker.nextNode();

        while (node) {
            this.bind(node);

            node = walker.nextNode();
        }
    }

    bind(element) {
        let names = element.getAttributeNames().filter((name) => name.startsWith('@') || name.startsWith(':'));

        names.forEach((name) => {
            let is_global = name.startsWith(':');
            let [key, ...options] = name.slice(1).split('.');
            let value = element.getAttribute(name);

            if (key === 'ref') {
                this.#ref(element, value, is_global);
            } else {
                this.#event(element, key, value, options, is_global);
            }

            element.removeAttribute(name);
        });
    }

    #ref(element, key, is_global) {
        if (!key) return;

        if (is_global) {
            Global.set(key, element);

            this.#bindings.push({ key, element });

            return;
        }

        if (key in HTMLElement.prototype) {
            throw new Error(`Binder: ref "${key}" on ${element.tagName} is the name of a native HTMLElement property, so this.${key} would not be the element; use another name`);
        }

        this.#refs.set(key, element);

        // Attach the getter property to the web component
        if (this.#host && !(key in this.#host)) {
            Object.defineProperty(this.#host, key, {
                get: () => this.#refs.get(key),
                configurable: true,
            });
        }
    }

    #event(element, event, method, options, is_global) {
        let handler = is_global ? Global.getListener(method) : this.#host[method];

        if (typeof handler !== 'function') {
            throw new Error(`Binder: no method "${method}" found in ${is_global ? 'Global' : 'Host'} for ${element.tagName} event "${event}"`);
        }

        let option = {};

        let keys = options.filter(value => ['capture', 'once', 'passive'].includes(value));

        for (let key of keys) option[key] = true;

        // Bind the handler to the host if it's not global
        let listener = is_global ? handler : handler.bind(this.#host);

        element.addEventListener(event, listener, option);

        this.#listeners.push({ element, event, listener, capture: !!option.capture });
    }

    /** Get a bound element by its data-ref name. */
    get(key) {
        return this.#refs.get(key);
    }

    has(key) {
        return this.#refs.has(key);
    }

    /** Binds a fresh render. The old bindings are removed first, listeners and global refs included. */
    refresh() {
        this.destroy();
        this.#refs = new Map();

        this.#walk(this.#root);
    }

    /** Remove all attached event listeners (call in disconnectedCallback). */
    destroy() {
        this.#listeners.forEach(({ element, event, listener, capture }) => element.removeEventListener(event, listener, capture));
        this.#listeners = [];

        // Only clear a global ref if it still points at the element *this*
        // instance set — avoids wiping out a ref another instance re-registered.
        this.#bindings.forEach(({ key, element }) => Global.delete(key, element));
        this.#bindings = [];
    }
}
